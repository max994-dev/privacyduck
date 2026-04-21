<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/utils.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/mailer.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/auth_redirect.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

pd_normalize_post_request();

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request.']);
    exit;
}

$email = isset($_POST['email']) ? trim((string) $_POST['email']) : '';

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['error' => 'Please enter a valid email.']);
    exit;
}

$conn = getDBConnection();
$stmt = $conn->prepare('SELECT * FROM users WHERE LOWER(TRIM(email)) = LOWER(?)');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    $conn->close();
    echo json_encode(['error' => 'No account found for that email.']);
    exit;
}

$data = $result->fetch_assoc();
$stmt->close();
$conn->close();

$email = isset($data['email']) ? (string) $data['email'] : $email;

if (!pd_user_may_login($data)) {
    echo json_encode(['error' => 'Invalid user.']);
    exit;
}

try {
    $verificationCode = random_int(100000, 999999);
    $_SESSION['verify_code'] = $verificationCode;
    $_SESSION['email'] = $email;
    $_SESSION['auth_flow'] = 'password_reset';
    unset($_SESSION['password_reset_allowed'], $_SESSION['password_reset_email'], $_SESSION['password_reset_has_password']);

    if (sendVerificationCodeEmail(
        $email,
        $verificationCode,
        'PrivacyDuck.com',
        'Verification for Privacyduck.com',
        'Reset your PrivacyDuck password'
    )) {
        echo json_encode([
            'success' => 'reset',
            'redirect' => '/verify',
        ]);
    } else {
        unset($_SESSION['verify_code'], $_SESSION['auth_flow']);
        echo json_encode(['error' => 'Could not send email. Try again.']);
    }
} catch (Throwable $e) {
    echo json_encode(['error' => 'Could not send email. Try again.']);
}
exit;
