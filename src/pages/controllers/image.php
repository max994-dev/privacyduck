<?php
header('Content-Type: application/json'); // Return JSON to browser
$email = $_POST['email'];
if (!isset($_FILES['profilePicture'])) {
    http_response_code(400);
    echo json_encode(["error" => "No file uploaded"]);
    exit;
}

$uploadDir = BASEPATH . "/assets/uploads/" . basename("specialinfo");
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}
$uploadedFile = $_FILES['profilePicture'];
$ext = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);
$filename = "img_" . $email . "." . $ext;
$targetFile = $uploadDir . "/" . $filename;

if (move_uploaded_file($uploadedFile['tmp_name'], $targetFile)) {
    echo json_encode([
        "success" => true,
        "message" => "File uploaded successfully",
        "url" => "/uploads/specialinfo/" . $filename
    ]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to move uploaded file"]);
}
