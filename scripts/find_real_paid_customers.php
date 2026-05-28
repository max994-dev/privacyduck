<?php
/**
 * Lists actual paid customers with complete profiles and meaningful
 * progress data. Use to pick a real account for verification testing
 * (so we don't have to lean on test/abandoned accounts like John Doe).
 */

define('BASEPATH', '/var/www/html');
$_SERVER['DOCUMENT_ROOT'] = BASEPATH;
require BASEPATH . '/src/common/database.php';

$conn = getDBConnection();

echo "=== paid users with COMPLETE profiles + real progress (top 10) ===\n";
$sql = "SELECT u.id, u.email, u.firstname, u.lastname, u.city, u.state, u.zip,
               u.address, u.phone, u.birth_date, u.planedAt,
               DATEDIFF(NOW(), u.planedAt) AS days_paid,
               (SELECT COUNT(*) FROM results WHERE user_id=u.id AND kind=1 AND step=2) AS done_count,
               (SELECT COUNT(*) FROM results WHERE user_id=u.id AND kind=1 AND step=0) AS queued_count,
               (SELECT COUNT(*) FROM results WHERE user_id=u.id AND kind=1) AS total_rows
        FROM users u
        WHERE u.plan_id IS NOT NULL AND u.plan_end > NOW()
          AND u.firstname IS NOT NULL AND u.firstname <> ''
          AND u.firstname NOT IN ('John', 'Test', 'test', 'TEST')
          AND u.lastname IS NOT NULL AND u.lastname <> ''
          AND u.lastname NOT IN ('Doe', 'Test', 'test', 'TEST')
          AND u.city IS NOT NULL AND u.city <> ''
          AND u.state IS NOT NULL AND u.state <> ''
          AND u.zip IS NOT NULL AND u.zip <> ''
          AND u.address IS NOT NULL AND u.address <> ''
        ORDER BY done_count DESC, days_paid DESC
        LIMIT 10";
$r = $conn->query($sql);

printf("%-5s %-32s %-12s %-12s %-12s %-3s %-7s %-9s %-6s %-6s\n",
       "id", "email", "first", "last", "city", "st", "zip", "days_paid", "done", "queued");
echo str_repeat("-", 110) . "\n";
while ($row = $r->fetch_assoc()) {
    printf("%-5s %-32s %-12s %-12s %-12s %-3s %-7s %-9s %-6s %-6s\n",
        $row['id'],
        substr($row['email'], 0, 30),
        substr($row['firstname'], 0, 10),
        substr($row['lastname'], 0, 10),
        substr($row['city'], 0, 10),
        substr($row['state'], 0, 2),
        substr($row['zip'], 0, 5),
        $row['days_paid'],
        $row['done_count'],
        $row['queued_count']
    );
}

echo "\n=== top 5 candidates -- full record for pick-one decision ===\n";
$r = $conn->query($sql);
$pick = 0;
while ($row = $r->fetch_assoc()) {
    if (++$pick > 5) break;
    echo "\n--- candidate #$pick (user_id={$row['id']}) ---\n";
    echo "  email:      {$row['email']}\n";
    echo "  name:       {$row['firstname']} {$row['lastname']}\n";
    echo "  address:    {$row['address']}, {$row['city']}, {$row['state']} {$row['zip']}\n";
    echo "  phone:      " . ($row['phone'] ?: '(not set)') . "\n";
    echo "  birth_date: " . ($row['birth_date'] ?: '(not set)') . "\n";
    echo "  paid since: {$row['planedAt']} ({$row['days_paid']} days ago)\n";
    echo "  rows:       {$row['done_count']} done / {$row['queued_count']} queued / {$row['total_rows']} total\n";
}

echo "\n=== suggested verification candidate ===\n";
$r = $conn->query($sql);
$best = $r->fetch_assoc();
if ($best) {
    echo "user_id = {$best['id']}\n";
    echo "email   = {$best['email']}\n";
    echo "(has the most done rows + complete profile)\n";
}
