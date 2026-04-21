<?php
header('Content-Type: application/json');
$conn = getDBConnection();

// Set your desired range
$start_date = "2025-09-16 00:00:00";
$end_date   = "2025-09-25 23:59:59";

$sql = "
    SELECT r.*, u.email 
    FROM results r
    INNER JOIN users u ON r.user_id = u.id
    WHERE u.plan_id > 1 AND u.plan_start BETWEEN ? AND ?
    AND u.pros_id IS NULL
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die(json_encode(["error" => "Prepare failed: " . $conn->error]));
}

$stmt->bind_param("ss", $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
$totals = [
    "total_groups" => 0,
    "counts_per_group" => []
];

while ($row = $result->fetch_assoc()) {
    $email = $row['email'];
    
    if (!isset($data[$email])) {
        $data[$email] = [];
    }
    $data[$email][] = $row;
}

// Compute totals
$totals['total_groups'] = count($data);

foreach ($data as $email => $rows) {
    $count_step2_kind1 = 0;
    foreach ($rows as $row) {
        if ($row['step'] == 2 && $row['kind'] == 1) {
            $count_step2_kind1++;
        }
    }
    $totals['counts_per_group'][$email] = $count_step2_kind1*100/301;
}

// Return everything
$output = [
    // "groups" => $data,
    "totals" => $totals
];

echo json_encode($output, JSON_PRETTY_PRINT);

$stmt->close();
$conn->close();
?>
