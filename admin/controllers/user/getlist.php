<?php
header("Content-Type: application/json");
$conn = getDBConnection();
$stmt = $conn->prepare("SELECT * FROM users");
// $stmt = $conn->prepare("SELECT * FROM users LIMIT ? OFFSET ?");
// $stmt->bind_param("ii", $_GET["pageSize"], $offset);
// $offset = ($_GET["current"] - 1) * $_GET["pageSize"];
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_all(MYSQLI_ASSOC);

$stmt = $conn->prepare("SELECT COUNT(*) FROM users");
$stmt->execute();
$result = $stmt->get_result();
$total = $result->fetch_row()[0];

$stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE ((plan_id > 0 AND plan_end > NOW()) OR (plan_id > 0 AND pros_id IS NOT NULL))");
$stmt->execute();
$result = $stmt->get_result();
$paidusers = $result->fetch_row()[0];

$stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE role > 0 AND (plan_id IS NULL OR (plan_id > 0 AND plan_end < NOW() AND pros_id IS NULL))");
$stmt->execute();
$result = $stmt->get_result();
$unpaidusers = $result->fetch_row()[0];

$stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE role > 0 AND pros_id IS NOT NULL");
$stmt->execute();
$result = $stmt->get_result();
$blockedusers = $result->fetch_row()[0];

echo json_encode([
    "list" => $data,
    "total" => $total,
    "paidusers" => $paidusers,
    "unpaidusers" => $unpaidusers,
    "blockedusers" => $blockedusers,
    "pageSize" => $_GET["pageSize"],
    "currentPage" => $_GET["current"]
]);
