<?php
header("Content-Type: application/json");

if (empty($_SESSION['admin']['isAdminAuthenticated'])) {
    http_response_code(401);
    echo json_encode(["error" => "Admin not authenticated"]);
    exit;
}

$conn = getDBConnection();

$pageSize = isset($_GET["pageSize"]) ? (int) $_GET["pageSize"] : 25;
if ($pageSize < 1 || $pageSize > 200) $pageSize = 25;
$current  = isset($_GET["current"]) ? max(1, (int) $_GET["current"]) : 1;
$offset   = ($current - 1) * $pageSize;

// Paginated list — never SELECT * the whole users table for the admin UI.
$stmt = $conn->prepare("SELECT id, email, firstname, lastname, plan_id, plan_end, pros_id, role, created_at FROM users ORDER BY id DESC LIMIT ? OFFSET ?");
$stmt->bind_param("ii", $pageSize, $offset);
$stmt->execute();
$data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Combine the four counts into one round-trip.
$countsRes = $conn->query("
    SELECT
        (SELECT COUNT(*) FROM users) AS total,
        (SELECT COUNT(*) FROM users WHERE ((plan_id > 0 AND plan_end > NOW()) OR (plan_id > 0 AND pros_id IS NOT NULL))) AS paidusers,
        (SELECT COUNT(*) FROM users WHERE role > 0 AND (plan_id IS NULL OR (plan_id > 0 AND plan_end < NOW() AND pros_id IS NULL))) AS unpaidusers,
        (SELECT COUNT(*) FROM users WHERE role > 0 AND pros_id IS NOT NULL) AS blockedusers
");
$counts = $countsRes ? $countsRes->fetch_assoc() : ["total" => 0, "paidusers" => 0, "unpaidusers" => 0, "blockedusers" => 0];

echo json_encode([
    "list"         => $data,
    "total"        => (int) $counts["total"],
    "paidusers"    => (int) $counts["paidusers"],
    "unpaidusers"  => (int) $counts["unpaidusers"],
    "blockedusers" => (int) $counts["blockedusers"],
    "pageSize"     => $pageSize,
    "currentPage"  => $current,
]);
