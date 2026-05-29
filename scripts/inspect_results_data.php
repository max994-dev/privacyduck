<?php
/**
 * Inspect results.data JSON column for a given user. Use to diagnose
 * "missing Name" errors from the removal pipeline.
 *
 * Usage: php scripts/inspect_results_data.php <user_id>
 */
define('BASEPATH', '/var/www/html');
$_SERVER['DOCUMENT_ROOT'] = BASEPATH;
require BASEPATH . '/src/common/database.php';

$userId = isset($argv[1]) ? (int) $argv[1] : 1992;
$conn = getDBConnection();

echo "=== users.* for user_id=$userId ===\n";
$stmt = $conn->prepare("SELECT firstname, lastname, email, city, state, zip, address, phone, birth_date FROM users WHERE id = ?");
$stmt->bind_param('i', $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
if (!$user) { fwrite(STDERR, "user not found\n"); exit(1); }
foreach ($user as $k => $v) echo "  $k = " . ($v ?? 'NULL') . "\n";
$stmt->close();

echo "\n=== sample of user $userId results.data (first 3 rows) ===\n";
$stmt = $conn->prepare(
    "SELECT id, target_domain,
            JSON_UNQUOTE(JSON_EXTRACT(data, '$.firstname')) AS fn,
            JSON_UNQUOTE(JSON_EXTRACT(data, '$.lastname'))  AS ln,
            JSON_UNQUOTE(JSON_EXTRACT(data, '$.email'))     AS em,
            LENGTH(data) AS data_len,
            LEFT(data, 200) AS data_preview
     FROM results WHERE user_id = ? AND kind = 1
     ORDER BY id LIMIT 3"
);
$stmt->bind_param('i', $userId);
$stmt->execute();
foreach ($stmt->get_result()->fetch_all(MYSQLI_ASSOC) as $row) {
    echo "id={$row['id']} domain={$row['target_domain']} fn=" . ($row['fn'] ?? 'NULL') . " ln=" . ($row['ln'] ?? 'NULL') . " em=" . ($row['em'] ?? 'NULL') . " data_len={$row['data_len']}\n";
    echo "  data=" . $row['data_preview'] . "\n";
}
$stmt->close();

echo "\n=== how many rows have empty/null data ===\n";
$stmt = $conn->prepare(
    "SELECT
        SUM(data IS NULL OR data = '{}' OR data = '') AS empty_count,
        SUM(JSON_EXTRACT(data, '$.firstname') IS NULL OR JSON_UNQUOTE(JSON_EXTRACT(data, '$.firstname')) = '') AS missing_fn,
        COUNT(*) AS total
     FROM results WHERE user_id = ? AND kind = 1"
);
$stmt->bind_param('i', $userId);
$stmt->execute();
$r = $stmt->get_result()->fetch_assoc();
echo "  total rows:      {$r['total']}\n";
echo "  empty data JSON: {$r['empty_count']}\n";
echo "  missing fn:      {$r['missing_fn']}\n";
$stmt->close();
