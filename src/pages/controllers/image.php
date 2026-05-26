<?php
header('Content-Type: application/json');

if (!isset($_FILES['profilePicture'])) {
    http_response_code(400);
    echo json_encode(["error" => "No file uploaded"]);
    exit;
}

$uploadedFile = $_FILES['profilePicture'];
if (!is_array($uploadedFile) || ($uploadedFile['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(["error" => "Upload failed"]);
    exit;
}

// Size cap — 5 MiB
if (($uploadedFile['size'] ?? 0) > 5 * 1024 * 1024) {
    http_response_code(413);
    echo json_encode(["error" => "File too large"]);
    exit;
}

// MIME-based extension whitelist (don't trust the client-provided extension).
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($uploadedFile['tmp_name']) ?: '';
$extMap = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/gif'  => 'gif',
    'image/webp' => 'webp',
];
if (!isset($extMap[$mime])) {
    http_response_code(415);
    echo json_encode(["error" => "Unsupported file type"]);
    exit;
}
$ext = $extMap[$mime];

$rawEmail = isset($_POST['email']) ? (string) $_POST['email'] : '';
// Strip everything that could be path-traversal or shell-injection material.
$safeKey = preg_replace('/[^A-Za-z0-9._-]/', '_', $rawEmail);
if ($safeKey === '' || $safeKey === null) {
    $safeKey = bin2hex(random_bytes(8));
}

$uploadDir = BASEPATH . "/assets/uploads/specialinfo";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$filename   = "img_" . $safeKey . "." . $ext;
$targetFile = $uploadDir . "/" . $filename;

if (move_uploaded_file($uploadedFile['tmp_name'], $targetFile)) {
    @chmod($targetFile, 0644);
    echo json_encode([
        "success" => true,
        "message" => "File uploaded successfully",
        "url"     => "/assets/uploads/specialinfo/" . $filename,
    ]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to move uploaded file"]);
}
