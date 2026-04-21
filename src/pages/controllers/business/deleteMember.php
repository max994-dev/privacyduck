<?php
header('Content-Type: application/json'); // Return JSON to browser

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Invalid request method."]);
    exit;
}
$mindmap_id = $_POST['id'] ?? '';
if (empty($mindmap_id)) {
    echo json_encode(["error" => "Missing mindmap id."]);
    exit;
}
$user_id = $_SESSION['work_user_id'];
$conn = getDBConnection();
$stmt = $conn->prepare("SELECT * FROM mindmap WHERE parent = ? and user_id = ?");
$parent_id = -1;
$stmt->bind_param("ii", $parent_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    echo json_encode(["error" => "Map not found"]);
    exit;
}
$parent_id = $result->fetch_assoc()['id'];


$stmt = $conn->prepare("SELECT * FROM mindmap WHERE id = ? and parent = ?");
$stmt->bind_param("ii", $mindmap_id, $parent_id);
$stmt->execute();
$result = $stmt->get_result();  
if ($result->num_rows == 0) {
    echo json_encode(["error" => "Map not found"]);
    exit;
}
$stmt = $conn->prepare("DELETE FROM mindmap WHERE id = ? and parent = ?");
$stmt->bind_param("ii", $mindmap_id, $parent_id); 
if ($stmt->execute()) {
    echo json_encode(["success" => "Member deleted successfully!"]);
} else {
    echo json_encode(["error" => "Failed to delete member."]);
}
