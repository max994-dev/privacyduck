<?php
header('Content-Type: application/json'); // Return JSON to browser

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Invalid request method."]);
    exit;
}

if (empty($_SESSION['work_isAuthenticated']) || empty($_SESSION['work_user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Not authenticated."]);
    exit;
}

$mindmap_id = isset($_POST['mindmap_id']) ? (int) $_POST['mindmap_id'] : 0;
// Coordinates may legitimately be 0 — only reject if missing, not if "empty"-falsy.
$x = isset($_POST['x']) && is_numeric($_POST['x']) ? (int) $_POST['x'] : null;
$y = isset($_POST['y']) && is_numeric($_POST['y']) ? (int) $_POST['y'] : null;

if ($mindmap_id <= 0) {
    echo json_encode(["error" => "Missing mindmap_id."]);
    exit;
}
if ($x === null || $y === null) {
    echo json_encode(["error" => "Missing x or y."]);
    exit;
}

$work_user_id = (int) $_SESSION['work_user_id'];
$conn = getDBConnection();

// Ownership check: the mindmap node must belong to this business OR be a child
// of a root node belonging to it. We use a single SELECT joining to the root.
$check = $conn->prepare("
    SELECT m.id
    FROM mindmap m
    LEFT JOIN mindmap root ON root.id = m.parent
    WHERE m.id = ?
      AND (m.user_id = ? OR root.user_id = ?)
    LIMIT 1
");
$check->bind_param("iii", $mindmap_id, $work_user_id, $work_user_id);
$check->execute();
$owned = (bool) $check->get_result()->fetch_row();
$check->close();

if (!$owned) {
    http_response_code(403);
    echo json_encode(["error" => "Forbidden."]);
    exit;
}

$stmt = $conn->prepare("UPDATE mindmap SET x = ?, y = ? WHERE id = ?");
$stmt->bind_param("iii", $x, $y, $mindmap_id);
if ($stmt->execute()) {
    echo json_encode(["success" => "Position updated successfully!"]);
} else {
    echo json_encode(["error" => "Failed to update position."]);
}
