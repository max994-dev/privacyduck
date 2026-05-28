<?php
// image.php — profile picture upload endpoint.
//
// Hardening: previously this endpoint was reachable by anyone (no auth, no
// rate limit) and wrote up to 5 MiB per POST to /assets/uploads/specialinfo
// with a filename derived from the supplied `email` POST field. An
// attacker could (a) burn through disk by spamming uploads with random
// emails, (b) overwrite another customer's face image by guessing their
// email. Now we require a logged-in session and use the SESSION email
// (not the POST field) as the filename key.
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/utils.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    exit;
}

$sessionEmail = $_SESSION['email'] ?? null;
$sessionUserId = $_SESSION['user_id'] ?? null;
if (!$sessionEmail || !$sessionUserId) {
    http_response_code(401);
    echo json_encode(["error" => "Authentication required"]);
    exit;
}

// CSRF guard — protect against logged-in users being tricked into
// uploading a malicious image cross-origin.
if (!pd_csrf_check()) {
    http_response_code(403);
    echo json_encode(["error" => "Invalid CSRF token"]);
    exit;
}

// Rate-limit so a hijacked session can't burn disk: max 10 uploads / hour.
if (pd_ratelimit_hit("upload:image:" . (int) $sessionUserId, 10, 3600)) {
    http_response_code(429);
    echo json_encode(["error" => "Too many uploads. Please try again later."]);
    exit;
}

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

// Size cap - 5 MiB
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

// Filename key is derived from the AUTHENTICATED user's email (not the
// POST field). This prevents one user overwriting another user's image
// by guessing or knowing their address.
$safeKey = preg_replace('/[^A-Za-z0-9._-]/', '_', (string) $sessionEmail);
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
