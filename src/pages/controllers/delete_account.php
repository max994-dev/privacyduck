<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/security.php';

// CSRF: state-mutating endpoint. Token comes from either
// <input name="csrf_token"> in the form OR the X-CSRF-Token header
// (utils.php injects it globally on jQuery.ajax/fetch).
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (function_exists('pd_csrf_require')) { pd_csrf_require(); }
}

header('Content-Type: application/json');

if (!isset($_SESSION['isAuthenticated']) || $_SESSION['isAuthenticated'] !== true
    || empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Not authenticated"]);
    exit;
}

$user_id = (int) $_SESSION['user_id'];

try {
    $conn = getDBConnection();

    $stmt = $conn->prepare("UPDATE users SET role = -1 WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM results WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    closeDBConnection($conn);
    session_destroy();
    echo json_encode([
        "success" => "success"
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    error_log('delete_account: ' . $e->getMessage());
    echo json_encode([
        "error" => "Account deletion failed"
    ]);
}
