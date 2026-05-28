<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/security.php';

// CSRF: state-mutating endpoint. Token comes from either
// <input name="csrf_token"> in the form OR the X-CSRF-Token header
// (utils.php injects it globally on jQuery.ajax/fetch).
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (function_exists('pd_csrf_require')) { pd_csrf_require(); }
}

    header('Content-Type: application/json');
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(["error" => "Invalid request method."]);
        exit;
    }
    if (empty($_SESSION['work_isAuthenticated']) || empty($_SESSION['work_user_id'])) {
        http_response_code(401);
        echo json_encode(["error" => "Not authenticated."]);
        exit;
    }
    $mindmap_name = trim((string) ($_POST['mindmap_name'] ?? ''));
    if ($mindmap_name === '') {
        echo json_encode(["error" => "Missing mindmap name."]);
        exit;
    }
    $user_id = (int) $_SESSION['work_user_id'];
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT * FROM mindmap WHERE user_id = ? and parent = -1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        echo json_encode(["error" => "Map not found!"]);
        exit;
    }
    $stmt = $conn->prepare("UPDATE mindmap SET mindmapname = ? WHERE user_id = ? and parent = -1");
    $stmt->bind_param("si", $mindmap_name, $user_id);
    if ($stmt->execute()) {
        $_SESSION['mindmap_name'] = $mindmap_name;
        echo json_encode(["success" => "Map name updated successfully!"]);
    } else {
        echo json_encode(["error" => "Failed to update map name."]);
    }