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
$user_id = $_SESSION['work_user_id'] ?? '';
$conn = getDBConnection();
$stmt = $conn->prepare("SELECT * FROM workUsers WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    echo json_encode(["error" => "User not found!"]);
    exit;
}
$stmt = $conn->prepare("UPDATE workUsers SET delete_flag = ? WHERE id = ?");
$flag = 1;
$stmt->bind_param("ii", $flag, $user_id);
if ($stmt->execute()) {
    echo json_encode(["success" => "User deleted successfully!"]);
} else {
    echo json_encode(["error" => "Failed to delete user."]);
}
