<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/utils.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/database.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

pd_normalize_post_request();

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request.']);
    exit;
}

if (empty($_SESSION['password_reset_allowed']) || empty($_SESSION['password_reset_email'])
    || !is_string($_SESSION['password_reset_email'])) {
    echo json_encode(['error' => 'Reset session expired. Start again from sign in.']);
    exit;
}

$email = $_SESSION['password_reset_email'];
$password = $_POST['password'] ?? '';
$passwordConfirm = $_POST['password_confirm'] ?? '';

if (!is_string($password) || strlen($password) < 8) {
    echo json_encode(['error' => 'Password must be at least 8 characters.']);
    exit;
}
if (!is_string($passwordConfirm) || $passwordConfirm !== $password) {
    echo json_encode(['error' => 'Passwords do not match.']);
    exit;
}

$conn = getDBConnection();
$stmt = $conn->prepare('SELECT id, role, email, password FROM users WHERE LOWER(TRIM(email)) = LOWER(?)');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    $stmt->close();
    $conn->close();
    unset($_SESSION['password_reset_allowed'], $_SESSION['password_reset_email']);
    echo json_encode(['error' => 'Account not found.']);
    exit;
}
$row = $result->fetch_assoc();
$stmt->close();

$email = isset($row['email']) ? (string) $row['email'] : $email;

if (!pd_user_may_login($row)) {
    $conn->close();
    unset($_SESSION['password_reset_allowed'], $_SESSION['password_reset_email'], $_SESSION['password_reset_has_password']);
    echo json_encode(['error' => 'Invalid user.']);
    exit;
}

// Strong one-way hash (bcrypt / Argon2 via PASSWORD_DEFAULT). Do not use MD5/SHA1 for passwords.
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare('UPDATE users SET password = ? WHERE email = ?');
$stmt->bind_param('ss', $hash, $email);
$stmt->execute();
$stmt->close();
$conn->close();

unset($_SESSION['password_reset_allowed'], $_SESSION['password_reset_email'], $_SESSION['password_reset_has_password'], $_SESSION['email']);

echo json_encode([
    'success' => true,
    'redirect' => '/new_signin?reset=1',
]);
exit;
