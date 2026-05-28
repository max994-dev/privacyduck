<?php
define("BASEPATH", $_SERVER["DOCUMENT_ROOT"]);
include_once(BASEPATH . "/src/common/config.php");
include_once(BASEPATH . "/src/common/utils.php");

// CSRF: state-mutating endpoint. Token comes from either
// <input name="csrf_token"> in the form OR the X-CSRF-Token header
// (utils.php injects it globally on jQuery.ajax/fetch).
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (function_exists('pd_csrf_require')) { pd_csrf_require(); }
}


header('Content-Type: application/json');

if (!isset($_GET['domain']) || $_GET['domain'] === '') {
    http_response_code(400);
    echo json_encode(["error" => "Missing domain parameter"]);
    exit;
}
if (!isset($_GET['user_id']) || $_GET['user_id'] === '') {
    http_response_code(400);
    echo json_encode(["error" => "Missing user_id parameter"]);
    exit;
}

$domain  = preg_replace('/[^A-Za-z0-9._-]/', '_', (string) $_GET['domain']);
$user_id = (int) $_GET['user_id'];
if ($domain === '' || $user_id <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid parameters"]);
    exit;
}

if (!isset($_FILES['file']) || !is_array($_FILES['file']) || ($_FILES['file']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(["error" => "No file uploaded"]);
    exit;
}

$uploadedFile = $_FILES['file'];
if (($uploadedFile['size'] ?? 0) > 10 * 1024 * 1024) {
    http_response_code(413);
    echo json_encode(["error" => "File too large"]);
    exit;
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime  = $finfo->file($uploadedFile['tmp_name']) ?: '';
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

$uploadDir = BASEPATH . "/assets/uploads/" . $user_id . "/google_scan";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$filename   = "scan_" . $domain . "_" . $user_id . "." . $ext;
$targetFile = $uploadDir . "/" . $filename;

if (move_uploaded_file($uploadedFile['tmp_name'], $targetFile)) {
    @chmod($targetFile, 0644);
    echo json_encode([
        "success" => true,
        "message" => "File uploaded successfully",
        "file"    => "/assets/uploads/" . $user_id . "/google_scan/" . $filename,
    ]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to move uploaded file"]);
}
