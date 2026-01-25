<?php
// Check for domain query parameter
if (!isset($_GET['domain']) || empty($_GET['domain'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing domain parameter"]);
    exit;
}

$domain = $_GET['domain'];
$user_id = $_GET['user_id'];

// Check if file was uploaded
if (!isset($_FILES['file'])) {
    http_response_code(400);
    echo json_encode(["error" => "No file uploaded"]);
    exit;
}

$uploadDir = BASEPATH . "/assets/uploads/" . basename($user_id); // Create directory per domain
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}
$uploadDir = BASEPATH . "/assets/uploads/" . basename($user_id) . "/scan"; // Create directory per domain
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}
$uploadedFile = $_FILES['file'];
$ext = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);
$filename = "scan_" . $domain . "_" . $user_id . "." . $ext;
$targetFile = $uploadDir . "/" . $filename;

if (move_uploaded_file($uploadedFile['tmp_name'], $targetFile)) {
    echo json_encode([
        "success" => true,
        "message" => "File uploaded successfully",
        "file" => "/uploads/" . $user_id . "/scan/" . $filename
    ]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to move uploaded file"]);
}
