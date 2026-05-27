<?php
header("Content-Type: application/json");

include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/database.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['admin']['isAdminAuthenticated'])) {
    http_response_code(401);
    echo json_encode(["error" => "Admin not authenticated"]);
    exit;
}

$validStatuses = ['all', 'open', 'in_progress', 'completed', 'rejected', 'extended'];
$status = $_GET['status'] ?? 'open';
if (!in_array($status, $validStatuses, true)) {
    $status = 'open';
}

$conn = getDBConnection();

// Counts across all rows (independent of filter) so the dashboard cards stay accurate.
$countsRes = $conn->query(
    "SELECT
        COUNT(*) AS total,
        SUM(status = 'open') AS open_count,
        SUM(status = 'in_progress') AS in_progress_count,
        SUM(status = 'completed') AS completed_count,
        SUM(status = 'rejected') AS rejected_count,
        SUM(status = 'extended') AS extended_count,
        SUM(status NOT IN ('completed','rejected') AND deadline_at < NOW()) AS overdue_count,
        SUM(status NOT IN ('completed','rejected') AND deadline_at >= NOW() AND deadline_at < (NOW() + INTERVAL 7 DAY)) AS due_soon_count
     FROM dsar_requests"
);
$rawCounts = $countsRes ? $countsRes->fetch_assoc() : [];
$counts = [];
foreach ($rawCounts as $k => $v) {
    $counts[$k] = (int) ($v ?? 0);
}

// Filtered list. Ordering puts active requests first, then by deadline ascending.
if ($status === 'all') {
    $sql = "SELECT id, reference, request_type, email, name, country, capacity, matched_user_id,
                   status, received_at, deadline_at, completed_at,
                   TIMESTAMPDIFF(HOUR, NOW(), deadline_at) AS hours_remaining
            FROM dsar_requests
            ORDER BY (status IN ('completed','rejected')) ASC, deadline_at ASC
            LIMIT 200";
    $stmt = $conn->prepare($sql);
} else {
    $sql = "SELECT id, reference, request_type, email, name, country, capacity, matched_user_id,
                   status, received_at, deadline_at, completed_at,
                   TIMESTAMPDIFF(HOUR, NOW(), deadline_at) AS hours_remaining
            FROM dsar_requests
            WHERE status = ?
            ORDER BY deadline_at ASC
            LIMIT 200";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $status);
}
$stmt->execute();
$list = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();

echo json_encode([
    "list"   => $list,
    "counts" => $counts,
    "filter" => $status,
]);
