<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/database.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Default: current authenticated user. Optional ?user_id= for admin/integration use.
$requestedUserId = isset($_GET['user_id']) ? (int) $_GET['user_id'] : 0;
$sessionUserId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;
$userId = $requestedUserId > 0 ? $requestedUserId : $sessionUserId;
if ($userId <= 0) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Missing user_id']);
    exit;
}

$conn = getDBConnection();
// kind=1 = removal site list. step: 0 pending, 1 in progress, 2 removed, 3 not found (terminal).
// Dashboard ring uses (step >= 2) / total — same as done_* below. Use COALESCE so NULL steps count as pending.
$sql = "SELECT
            SUM(CASE WHEN kind = 1 THEN 1 ELSE 0 END) AS total_sites,
            SUM(CASE WHEN kind = 1 AND COALESCE(step, 0) = 0 THEN 1 ELSE 0 END) AS pending_sites,
            SUM(CASE WHEN kind = 1 AND COALESCE(step, 0) = 1 THEN 1 ELSE 0 END) AS in_progress_sites,
            SUM(CASE WHEN kind = 1 AND COALESCE(step, 0) = 2 THEN 1 ELSE 0 END) AS removed_sites,
            SUM(CASE WHEN kind = 1 AND COALESCE(step, 0) = 3 THEN 1 ELSE 0 END) AS not_found_sites,
            SUM(CASE WHEN kind = 1 AND COALESCE(step, 0) >= 2 THEN 1 ELSE 0 END) AS done_sites
        FROM results
        WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc() ?: [];
$stmt->close();
$conn->close();

$total = (int) ($row['total_sites'] ?? 0);
$done = (int) ($row['done_sites'] ?? 0);
$removed = (int) ($row['removed_sites'] ?? 0);
$inProgress = (int) ($row['in_progress_sites'] ?? 0);

// Same formula as /src/pages/Dashboard/Main/progress/index.php (step >= 2 counts as completed).
$donePct = $total > 0 ? round(($done * 100.0) / $total, 2) : 0.0;
$removedPct = $total > 0 ? round(($removed * 100.0) / $total, 2) : 0.0;
$notFound = (int) ($row['not_found_sites'] ?? 0);
$notFoundPct = $total > 0 ? round(($notFound * 100.0) / $total, 2) : 0.0;

echo json_encode([
    'ok' => true,
    'user_id' => $userId,
    'metrics' => [
        'total_sites' => $total,
        'pending_sites' => (int) ($row['pending_sites'] ?? 0),
        'in_progress_sites' => $inProgress,
        'removed_sites' => $removed,
        'not_found_sites' => $notFound,
        'completed_sites' => $done,
        'done_sites' => $done,
        'completion_percentage' => $donePct,
        'done_percentage' => $donePct,
        'removed_percentage' => $removedPct,
        'not_found_percentage' => $notFoundPct,
    ],
    'legend' => [
        'completion_percentage' => 'Terminal sites (step >= 2: removed or not_found) / total kind=1 rows. Matches PD dashboard removal arc.',
        'removed_percentage' => 'Sites with step=2 only / total. Excludes not_found (step=3).',
    ],
]);
exit;

