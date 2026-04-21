<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/utils.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/stripe_signup_sync.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/auth_redirect.php';

function new_signup_redirect_error(string $msg): void
{
    header('Location: ' . WEB_DOMAIN . '/new_signup?err=' . rawurlencode($msg));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    new_signup_redirect_error('Invalid request.');
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$passwordConfirm = $_POST['password_confirm'] ?? '';
$agreeTerms = isset($_POST['agree_terms']) && (string) $_POST['agree_terms'] === '1';
$agreeMarketing = isset($_POST['agree_marketing']) && (string) $_POST['agree_marketing'] === '1';
$facePhoto = $_FILES['face_photo'] ?? null;

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    new_signup_redirect_error('Please enter a valid email.');
}
if (!is_string($password) || strlen($password) < 8) {
    new_signup_redirect_error('Password must be at least 8 characters.');
}
if (!is_string($passwordConfirm) || $passwordConfirm === '' || $passwordConfirm !== $password) {
    new_signup_redirect_error('Passwords do not match.');
}
if (!$agreeTerms) {
    new_signup_redirect_error('You must agree to the Privacy Policy and Terms of Service to create an account.');
}
if (!is_array($facePhoto) || (int) ($facePhoto['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
    new_signup_redirect_error('Face photo is required.');
}

$facePhotoTmp = (string) ($facePhoto['tmp_name'] ?? '');
$facePhotoSize = (int) ($facePhoto['size'] ?? 0);
if ($facePhotoTmp === '' || !is_uploaded_file($facePhotoTmp)) {
    new_signup_redirect_error('Invalid face photo upload.');
}
if ($facePhotoSize <= 0 || $facePhotoSize > 5 * 1024 * 1024) {
    new_signup_redirect_error('Face photo must be 5MB or less.');
}
$faceMime = (string) mime_content_type($facePhotoTmp);
$allowedMimes = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/webp' => 'webp',
    'image/gif' => 'gif',
];
if (!isset($allowedMimes[$faceMime])) {
    new_signup_redirect_error('Face photo must be a valid image file.');
}
$faceExtension = $allowedMimes[$faceMime];
$safeEmailPart = preg_replace('/[^a-zA-Z0-9_\-]/', '_', strtolower($email));
$faceFilename = 'img_' . $safeEmailPart . '_' . bin2hex(random_bytes(4)) . '.' . $faceExtension;
$faceUploadDir = BASEPATH . '/assets/uploads/specialinfo';
if (!is_dir($faceUploadDir) && !mkdir($faceUploadDir, 0775, true) && !is_dir($faceUploadDir)) {
    new_signup_redirect_error('Could not create upload directory.');
}
$faceTargetPath = $faceUploadDir . '/' . $faceFilename;
if (!move_uploaded_file($facePhotoTmp, $faceTargetPath)) {
    new_signup_redirect_error('Could not store face photo. Please try again.');
}

// PASSWORD_DEFAULT uses bcrypt/Argon2 — suitable for password storage (not MD5/SHA1).
$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$firstname = 'Member';
$lastname = 'User';
$phone = '';
$address = '';
$city = '';
$state = '';
$zip = '';
$age = 0;
$contacts = [[
    'city' => '',
    'state' => '',
    'phone' => '',
    'zip' => '',
    'address' => '',
    // Marketing consent is stored in `contacts` so we don't need a DB migration right now.
    'marketing_opt_in' => $agreeMarketing ? 1 : 0,
]];
$contactsJson = json_encode($contacts);
$createdAt = date('Y-m-d H:i:s');

$conn = getDBConnection();
$stmt = $conn->prepare('SELECT id FROM users WHERE LOWER(TRIM(email)) = LOWER(?)');
$stmt->bind_param('s', $email);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    $stmt->close();
    $conn->close();
    new_signup_redirect_error('That email is already registered.');
}
$stmt->close();

$stmt = $conn->prepare(
    'INSERT INTO users (email, firstname, lastname, phone, city, zip, state, age, address, contacts, role, created_at, password, url) '
    . 'VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?, ?)'
);
$stmt->bind_param(
    'sssssssisssss',
    $email,
    $firstname,
    $lastname,
    $phone,
    $city,
    $zip,
    $state,
    $age,
    $address,
    $contactsJson,
    $createdAt,
    $passwordHash,
    $faceFilename
);

if (!$stmt->execute()) {
    $stmt->close();
    $conn->close();
    new_signup_redirect_error('Could not create account. Please try again.');
}
$userId = (int) $conn->insert_id;
$stmt->close();

stripe_sync_privacyduck_subscription_for_email($conn, $email, true);

$stmt = $conn->prepare('SELECT * FROM users WHERE id = ?');
$stmt->bind_param('i', $userId);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();
$conn->close();

if (!$data) {
    new_signup_redirect_error('Account created but session failed. Please log in.');
}

pd_apply_user_session_from_row($data, $email);
header('Location: ' . pd_new_landing_post_auth_redirect_url($data));
exit;
