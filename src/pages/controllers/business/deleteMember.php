<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/security.php';

// CSRF: state-mutating endpoint. Token comes from either
// <input name="csrf_token"> in the form OR the X-CSRF-Token header
// (utils.php injects it globally on jQuery.ajax/fetch).
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (function_exists('pd_csrf_require')) { pd_csrf_require(); }
}

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
if (empty($_SESSION['work_isAuthenticated']) || empty($_SESSION['work_user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Not authenticated."]);
    exit;
}
$user_id = (int) $_SESSION['work_user_id'];
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
