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

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id < 1) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid id"]);
    exit;
}

$conn = getDBConnection();
$stmt = $conn->prepare(
    "SELECT id, reference, request_type, email, name, country, capacity, matched_user_id,
            details, ip_address, user_agent, received_at, deadline_at,
            status, staff_notes, completed_at
     FROM dsar_requests WHERE id = ? LIMIT 1"
);
$stmt->bind_param('i', $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();
$conn->close();

if (!$row) {
    http_response_code(404);
    echo json_encode(["error" => "DSAR request not found"]);
    exit;
}

echo json_encode(["data" => $row]);
