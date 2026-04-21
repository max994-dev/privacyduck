<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/utils.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/auth_redirect.php');
// mailer.php (PHPMailer + autoload) is loaded only when we send a verification email — not on normal password login.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

pd_normalize_post_request();

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request.']);
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['error' => 'Please enter a valid email.']);
    exit;
}

$conn = getDBConnection();
$stmt = $conn->prepare('SELECT * FROM users WHERE LOWER(TRIM(email)) = LOWER(?) LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    $conn->close();
    echo json_encode(['error' => 'Invalid email or password.']);
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

$stored = $data['password'] ?? null;
$hasPassword = is_string($stored) && $stored !== '';

if (!$hasPassword) {
    try {
        if (email_verification_bypassed($email)) {
            $_SESSION['password_reset_allowed'] = true;
            $_SESSION['password_reset_email'] = $email;
            echo json_encode([
                'success' => 'reset',
                'redirect' => '/new_reset_password',
            ]);
            exit;
        }

        require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/mailer.php';
        $verificationCode = random_int(100000, 999999);
        $_SESSION['verify_code'] = $verificationCode;
        $_SESSION['email'] = $email;
        $_SESSION['auth_flow'] = 'password_setup';

        if (sendVerificationCodeEmail(
            $email,
            $verificationCode,
            'PrivacyDuck.com',
            'Verification for Privacyduck.com',
            'Set your PrivacyDuck password'
        )) {
            echo json_encode(['success' => 'verify', 'setup_password' => true]);
        } else {
            unset($_SESSION['auth_flow']);
            echo json_encode(['error' => 'Could not send email. Try again.']);
        }
    } catch (Throwable $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

if (!is_string($password) || $password === '') {
    echo json_encode(['error' => 'Please enter your password.']);
    exit;
}

if (!password_verify($password, $stored)) {
    echo json_encode(['error' => 'Invalid email or password.']);
    exit;
}

pd_apply_user_session_from_row($data, $email);
echo json_encode([
    'success' => true,
    'redirect' => pd_new_landing_post_auth_redirect_url($data),
]);
exit;

