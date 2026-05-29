<?php
/**
 * One-time fix for paid users whose results.data JSON is empty/null.
 *
 * Cause: scripts/fix_results_integrity.php (May 28 backfill) wrote
 * data='{}' for users who had ZERO existing rows -- there was no
 * sample to copy from. Those rows reach the removal pipeline with
 * JSON_UNQUOTE returning NULL for every field, so validate_required_pii
 * fails on "Name" missing and the row goes to step=5 (missing_pii)
 * for every single broker, blocking all opt-outs for those users.
 *
 * What this script does:
 *   For each paid active user:
 *     1. Find their results.data values that are empty/null.
 *     2. Build a proper dataRow JSON from users.* columns (same shape
 *        the dashboard_bootstrap.php plan() helper would have created).
 *     3. UPDATE results.data with the built JSON for those rows.
 *
 * Idempotent. DRY-RUN by default; pass --commit to apply.
 *
 * Usage:
 *   php scripts/fix_results_data_pii.php
 *   php scripts/fix_results_data_pii.php --commit
 */
define('BASEPATH', '/var/www/html');
$_SERVER['DOCUMENT_ROOT'] = BASEPATH;
require BASEPATH . '/src/common/database.php';

$DRY = !in_array('--commit', $argv ?? [], true);
$conn = getDBConnection();

// Find all paid active users + their profile
$r = $conn->query(
    "SELECT id, email, firstname, lastname, address, city, state, zip, phone, birth_date
     FROM users
     WHERE plan_id IS NOT NULL AND plan_id > 0 AND plan_end > NOW()
     ORDER BY id"
);
$users = $r->fetch_all(MYSQLI_ASSOC);
fwrite(STDOUT, "checking " . count($users) . " paid active users\n");
fwrite(STDOUT, "MODE: " . ($DRY ? "DRY-RUN (no writes)" : "COMMIT") . "\n\n");

$totalRowsFixed = 0;
$usersTouched = 0;

foreach ($users as $u) {
    $userId = (int) $u['id'];

    // Build the canonical dataRow JSON from this user's profile.
    // Schema mirrors what dashboard_bootstrap.php's plan()/buildPayload()
    // creates AND what __removal.py's get_pending_removal SELECT expects.
    $firstname = (string) ($u['firstname'] ?? '');
    $lastname  = (string) ($u['lastname']  ?? '');
    $email     = (string) ($u['email']     ?? '');
    $birth = $u['birth_date'] ?? null;
    $birthDay = $birthMonth = $birthYear = '';
    if ($birth && $birth !== '0000-00-00') {
        $parts = explode('-', $birth);
        if (count($parts) === 3) {
            $birthYear  = $parts[0];
            $birthMonth = ltrim($parts[1], '0');
            $birthDay   = ltrim($parts[2], '0');
        }
    }
    $age = '';
    if ($birthYear !== '') {
        $age = (string) (((int) date('Y')) - ((int) $birthYear));
    }
    $address = (string) ($u['address'] ?? '');
    $phone   = (string) ($u['phone']   ?? '');
    $city    = (string) ($u['city']    ?? '');
    $state   = (string) ($u['state']   ?? '');
    $zip     = (string) ($u['zip']     ?? '');

    $data = [
        'email'       => $email,
        'firstname'   => $firstname,
        'lastname'    => $lastname,
        'age'         => $age,
        'city'        => $city,
        'zip'         => $zip,
        'state'       => $state,
        'phone'       => $phone,
        'address'     => $address,
        'birth_day'   => $birthDay,
        'birth_month' => $birthMonth,
        'birth_year'  => $birthYear,
        'area_code'   => '',
        'street'      => $address,
        'county'      => '',
    ];
    $dataJson = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    // Find rows that need fixing: empty or NULL data, OR data missing firstname
    $stmt = $conn->prepare(
        "SELECT COUNT(*) AS n FROM results
         WHERE user_id = ? AND kind = 1
           AND (data IS NULL OR data = '' OR data = '{}'
                OR JSON_EXTRACT(data, '$.firstname') IS NULL
                OR JSON_UNQUOTE(JSON_EXTRACT(data, '$.firstname')) = '')"
    );
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $needFix = (int) ($stmt->get_result()->fetch_assoc()['n'] ?? 0);
    $stmt->close();

    if ($needFix === 0) {
        continue;
    }

    if ($DRY) {
        printf("user_id=%-5d %-40s would fix %d rows  (name=%s %s)\n",
            $userId, substr($u['email'], 0, 38), $needFix,
            substr($firstname, 0, 12), substr($lastname, 0, 12));
        $totalRowsFixed += $needFix;
        $usersTouched++;
        continue;
    }

    // COMMIT: update all empty-data rows for this user
    $stmt = $conn->prepare(
        "UPDATE results SET data = ?
         WHERE user_id = ? AND kind = 1
           AND (data IS NULL OR data = '' OR data = '{}'
                OR JSON_EXTRACT(data, '$.firstname') IS NULL
                OR JSON_UNQUOTE(JSON_EXTRACT(data, '$.firstname')) = '')"
    );
    $stmt->bind_param('si', $dataJson, $userId);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();
    printf("user_id=%-5d %-40s fixed %d rows  (name=%s %s)\n",
        $userId, substr($u['email'], 0, 38), $affected,
        substr($firstname, 0, 12), substr($lastname, 0, 12));
    $totalRowsFixed += $affected;
    $usersTouched++;
}

fwrite(STDOUT, "\n");
fwrite(STDOUT, "SUMMARY: $usersTouched users touched, $totalRowsFixed rows " . ($DRY ? "WOULD BE" : "were") . " fixed\n");
if ($DRY) {
    fwrite(STDOUT, "Re-run with --commit to apply.\n");
}
