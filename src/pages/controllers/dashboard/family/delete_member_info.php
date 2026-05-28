<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/security.php';

// CSRF: state-mutating endpoint. Token comes from either
// <input name="csrf_token"> in the form OR the X-CSRF-Token header
// (utils.php injects it globally on jQuery.ajax/fetch).
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (function_exists('pd_csrf_require')) { pd_csrf_require(); }
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["invite_id"]) || !isset($_SESSION['user_id'])) {
    http_response_code(500);
    die(json_encode(["error" => "Invalid Request!"]));
}
header("Content-Type: application/json");
$core_id = $_SESSION['user_id'];
$invite_id = $_POST["invite_id"];

$conn = getDBConnection();

$sql = "DELETE FROM family WHERE core_id=? AND invite_id = ?";
$sql2 = "DELETE FROM results WHERE user_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $core_id, $invite_id);
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $invite_id);

if ($stmt->execute() && $stmt2->execute()) {
    echo json_encode(["status" => 1]);
} else {
    echo json_encode(["status" => 0]);
}
?>