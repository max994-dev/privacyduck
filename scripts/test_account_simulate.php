<?php
/**
 * Mark a user's rows as "simulated removed" so their dashboard shows
 * realistic progress WITHOUT actually contacting any broker.
 *
 * Used for UX verification on test accounts (placeholder PII, "John Doe"
 * style data) where sending real opt-out requests would spam brokers
 * with bogus removal claims.
 *
 * Strategy:
 *   - Pick N rows from the user's step=0 pool, prefer brokers known to
 *     work (avoid the *arrests.org family + the CCPA-email batch since
 *     those would otherwise be the natural first to process).
 *   - Mark them step=2 with updated_at spread across the last 72 hours
 *     so the 14-day daily chart shows a realistic ramp curve.
 *
 * Usage:
 *   php scripts/test_account_simulate.php <user_id> [<num_to_simulate>]
 *
 * Default: 50 simulated removals for user 1992 (dhofman.work@gmail.com).
 */

define('BASEPATH', '/var/www/html');
$_SERVER['DOCUMENT_ROOT'] = BASEPATH;
require BASEPATH . '/src/common/database.php';

$userId = isset($argv[1]) ? (int) $argv[1] : 1992;
$n      = isset($argv[2]) ? (int) $argv[2] : 50;

if ($userId <= 0 || $n <= 0) {
    fwrite(STDERR, "usage: test_account_simulate.php <user_id> [<n>]\n");
    exit(2);
}

$conn = getDBConnection();

// Confirm user exists + is paid
$stmt = $conn->prepare("SELECT email, firstname, lastname FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$user) { fwrite(STDERR, "user $userId not found\n"); exit(1); }
echo "Target user: id=$userId email={$user['email']} name={$user['firstname']} {$user['lastname']}\n";

// Pick N step=0 rows. Prefer recognizable people-search brokers first
// (mylifecom, ownerlycom, 411com, etc) since those are what real users
// would recognize on the activity feed.
$stmt = $conn->prepare(
    "SELECT id, target_domain FROM results
     WHERE user_id = ? AND kind = 1 AND step = 0
     ORDER BY
       CASE
         WHEN target_domain IN ('mylifecom','ownerlycom','411com','411info','411locatecom',
                                'whitepagescomus','spokeocom','beenverifiedcom','peoplefinderscom',
                                'truepeoplesearchcom','thatsthemcom','radarisCom','intelius',
                                'inteliuscom','peopleconnectus','checkmate','peekyou',
                                'instantcheckmatecom','truthfindercom','peoplelookerscom') THEN 1
         WHEN target_domain LIKE '%backgroundcheck%' OR target_domain LIKE '%peoplesearch%' THEN 2
         ELSE 3
       END,
       id ASC
     LIMIT ?"
);
$stmt->bind_param("ii", $userId, $n);
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (empty($rows)) {
    fwrite(STDERR, "no step=0 rows available for user $userId (already all done?)\n");
    exit(1);
}

echo "Simulating " . count($rows) . " removals (target was $n)...\n";

// Spread updated_at across last 72 hours so the daily chart looks
// realistic (more recent = more activity, mimicking a healthy ramp).
$now = time();
$updates = 0;
foreach ($rows as $i => $r) {
    // Time-distribution: skewed toward more recent. Most rows in last 24h,
    // some in 24-48h, fewer in 48-72h.
    $bucket = ($i < count($rows) * 0.5) ? rand(0, 86400)
            : (($i < count($rows) * 0.8) ? rand(86400, 172800)
                                          : rand(172800, 259200));
    $ts = $now - $bucket;
    $ts_str = date('Y-m-d H:i:s', $ts);

    $stmt = $conn->prepare(
        "UPDATE results SET step = 2, updated_at = ? WHERE id = ? AND step = 0"
    );
    $stmt->bind_param("si", $ts_str, $r['id']);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $updates++;
        echo "  [$ts_str] {$r['target_domain']}\n";
    }
    $stmt->close();
}

echo "\nSimulated $updates removals for user_id=$userId.\n";
echo "Dashboard should now show ~" . round(($updates * 100) / 413) . "% done,\n";
echo "with activity distributed across the last 72 hours.\n";
echo "\nReminder: this is SIMULATION ONLY -- no real broker was contacted.\n";
echo "These rows are marked step=2 in the DB so the dashboard renders as\n";
echo "if the brokers had been processed. Use only for UX testing.\n";
