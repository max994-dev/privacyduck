<?php
header("Content-Type: application/json");

$conn = getDBConnection();

// Get and sanitize pagination inputs
// $pageSize = isset($_GET["pageSize"]) ? (int)$_GET["pageSize"] : 10;
// $currentPage = isset($_GET["current"]) ? (int)$_GET["current"] : 1;
// $offset = ($currentPage - 1) * $pageSize;

// Fetch paginated user list
$stmt = $conn->prepare("SELECT * FROM users WHERE role > 0 AND (plan_id IS NULL OR (plan_id > 0 AND plan_end < NOW() AND pros_id IS NULL))");
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Run all counts in a single query
$query = "
    SELECT 
        (SELECT COUNT(*) FROM users) AS total,
        (SELECT COUNT(*) FROM users WHERE role > 0 AND ((plan_id > 0 AND plan_end > NOW()) OR (plan_id > 0 AND pros_id IS NOT NULL))) AS paidusers,
        (SELECT COUNT(*) FROM users WHERE role > 0 AND (plan_id IS NULL OR (plan_id > 0 AND plan_end < NOW() AND pros_id IS NULL))) AS unpaidusers,
        (SELECT COUNT(*) FROM users WHERE role > 0 AND pros_id IS NOT NULL) AS blockedusers
";
$result = $conn->query($query);
$counts = $result->fetch_assoc();

echo json_encode([
    "list" => $data,
    "total" => (int)$counts["total"],
    "paidusers" => (int)$counts["paidusers"],
    "unpaidusers" => (int)$counts["unpaidusers"],
    "blockedusers" => (int)$counts["blockedusers"],
]);
