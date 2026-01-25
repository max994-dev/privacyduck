<?php
header('Content-Type: application/json'); // Return JSON to browser

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Invalid request method."]);
    exit;
}
$mindmap_id = $_POST['mindmap_id'] ?? '';
$x = $_POST['x'] ?? 0;
$y = $_POST['y'] ?? 0;
if (empty($mindmap_id)) {
    echo json_encode(["error" => "Missing mindmap_id."]);
    exit;
}
if (empty($x)) {
    echo json_encode(["error" => "Missing x."]);
    exit;
}
if (empty($y)) {
    echo json_encode(["error" => "Missing y."]);
    exit;
}
$conn = getDBConnection();
$stmt = $conn->prepare("UPDATE mindmap SET x = ?, y = ? WHERE id = ?");
$stmt->bind_param("iii", $x, $y, $mindmap_id);
if ($stmt->execute()) {
    echo json_encode(["success" => "Position updated successfully!"]);
} else {
    echo json_encode(["error" => "Failed to update position."]);
}