<?php
/**
 * One-time fix for results-table data integrity issues:
 *
 *   Issue 1: 3 users have duplicate target_domains for kind=1
 *            (1024, 995, 959 rows but only 413 distinct domains).
 *            Likely a race condition in plan() being called twice.
 *
 *   Issue 2: 62 users have exactly 301 rows -- they signed up when the
 *            canonical broker list was 301 entries, never got the 112
 *            newer brokers added because plan() only inserts when the
 *            user has ZERO rows.
 *
 * What this script does, per paid user:
 *   A) Deduplicate: keep ONE row per (user_id, target_domain), preferring
 *      step=2 (done) > step=1 (in_flight) > step=0 (queued) > step=5
 *      (missing_pii) > step=3 (rejected) > step=4 (not_impl). Delete the rest.
 *   B) Backfill: for any broker in the canonical 413 list that's MISSING
 *      from the user's results, INSERT a new row with step=0, kind=1,
 *      planable=1, data='{}' so the pipeline picks it up.
 *
 * Idempotent. Safe to re-run. Prints before/after state per user.
 *
 * Run via: php /var/www/html/scripts/fix_results_integrity.php
 *   (or invoke from CLI with --commit flag to actually apply changes;
 *    default is DRY-RUN)
 */

define('BASEPATH', '/var/www/html');
$_SERVER['DOCUMENT_ROOT'] = BASEPATH;
require BASEPATH . '/src/common/database.php';

$DRY_RUN = !in_array('--commit', $argv ?? [], true);

// Load canonical broker list
require BASEPATH . '/src/pages/Dashboard/sites_data.php';
if (!isset($websites) || !is_array($websites)) {
    fwrite(STDERR, "sites_data.php did not define \$websites array\n");
    exit(1);
}
$canonicalDomains = array_keys($websites);
$canonicalCount = count($canonicalDomains);
fwrite(STDOUT, "canonical broker list: $canonicalCount domains\n");
fwrite(STDOUT, "MODE: " . ($DRY_RUN ? "DRY-RUN (no changes)" : "COMMIT") . "\n\n");

$conn = getDBConnection();

// Step preference for dedup: higher = keep
$STEP_PRIORITY = [2 => 6, 1 => 5, 0 => 4, 5 => 3, 3 => 2, 4 => 1];

// Get all paid active users
$users = [];
$r = $conn->query(
    "SELECT id, email FROM users
     WHERE plan_id IS NOT NULL AND plan_id > 0 AND plan_end > NOW()
     ORDER BY id"
);
while ($row = $r->fetch_assoc()) {
    $users[] = $row;
}
fwrite(STDOUT, "found " . count($users) . " paid active users\n\n");

$totalDeleted = 0;
$totalInserted = 0;
$usersTouched = 0;

foreach ($users as $u) {
    $userId = (int) $u['id'];
    $email = $u['email'];

    // Pull all kind=1 rows for this user
    $stmt = $conn->prepare(
        "SELECT id, target_domain, step, data, planable FROM results
         WHERE user_id = ? AND kind = 1 ORDER BY id"
    );
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Group by target_domain
    $byDomain = [];
    foreach ($rows as $rr) {
        $d = $rr['target_domain'];
        if (!isset($byDomain[$d])) {
            $byDomain[$d] = [];
        }
        $byDomain[$d][] = $rr;
    }

    $deletedThisUser = 0;
    $insertedThisUser = 0;
    $sampleData = '{}';

    // (A) Dedupe: for each domain with >1 rows, pick winner, queue rest for deletion
    $toDelete = [];
    foreach ($byDomain as $domain => $dRows) {
        if (count($dRows) <= 1) {
            // Grab a data sample from any single-row case for backfill template
            if ($sampleData === '{}' && !empty($dRows[0]['data'])) {
                $sampleData = $dRows[0]['data'];
            }
            continue;
        }
        // Pick winner by step priority, then by id (oldest first)
        usort($dRows, function ($a, $b) use ($STEP_PRIORITY) {
            $pa = $STEP_PRIORITY[(int) $a['step']] ?? 0;
            $pb = $STEP_PRIORITY[(int) $b['step']] ?? 0;
            if ($pa !== $pb) return $pb - $pa;  // higher priority first
            return (int) $a['id'] - (int) $b['id'];  // older first
        });
        $winner = array_shift($dRows);
        if ($sampleData === '{}' && !empty($winner['data'])) {
            $sampleData = $winner['data'];
        }
        foreach ($dRows as $loser) {
            $toDelete[] = (int) $loser['id'];
        }
    }
    if (!empty($toDelete)) {
        if ($DRY_RUN) {
            $deletedThisUser = count($toDelete);
        } else {
            // Batch delete in chunks of 500
            foreach (array_chunk($toDelete, 500) as $chunk) {
                $placeholders = implode(',', array_fill(0, count($chunk), '?'));
                $stmt = $conn->prepare("DELETE FROM results WHERE id IN ($placeholders)");
                $stmt->bind_param(str_repeat('i', count($chunk)), ...$chunk);
                $stmt->execute();
                $deletedThisUser += $stmt->affected_rows;
                $stmt->close();
            }
        }
    }

    // (B) Backfill: find canonical brokers missing from this user
    $userDomains = array_keys($byDomain);
    $missing = array_diff($canonicalDomains, $userDomains);
    if (!empty($missing)) {
        if ($DRY_RUN) {
            $insertedThisUser = count($missing);
        } else {
            // Batch insert. Use the user's sample data so all fields are set.
            foreach (array_chunk(array_values($missing), 200) as $chunk) {
                $placeholders = implode(',', array_fill(0, count($chunk),
                    "(?, ?, 0, 1, 1, ?, '', '')"));
                $sql = "INSERT INTO results (user_id, target_domain, step, kind, planable, data, removal_url, site_url) VALUES $placeholders";
                $stmt = $conn->prepare($sql);
                $params = [];
                $types = '';
                foreach ($chunk as $d) {
                    $params[] = $userId;
                    $params[] = $d;
                    $params[] = $sampleData;
                    $types .= 'iss';
                }
                $stmt->bind_param($types, ...$params);
                $stmt->execute();
                $insertedThisUser += $stmt->affected_rows;
                $stmt->close();
            }
        }
    }

    if ($deletedThisUser > 0 || $insertedThisUser > 0) {
        $usersTouched++;
        $totalDeleted += $deletedThisUser;
        $totalInserted += $insertedThisUser;
        $finalRows = count($rows) - $deletedThisUser + $insertedThisUser;
        printf("user_id=%-5d %-40s rows: %4d -> %4d  (deleted=%4d  inserted=%4d)\n",
            $userId, substr($email, 0, 38), count($rows), $finalRows,
            $deletedThisUser, $insertedThisUser);
    }
}

fwrite(STDOUT, "\n");
fwrite(STDOUT, "SUMMARY:\n");
fwrite(STDOUT, "  users touched:  $usersTouched\n");
fwrite(STDOUT, "  rows deleted:   $totalDeleted (duplicates)\n");
fwrite(STDOUT, "  rows inserted:  $totalInserted (missing backfill)\n");
fwrite(STDOUT, "  mode:           " . ($DRY_RUN ? "DRY-RUN -- re-run with --commit to apply" : "COMMITTED") . "\n");
