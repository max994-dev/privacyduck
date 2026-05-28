<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/utils.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/auth_redirect.php');

// CSRF: state-mutating endpoint. Token comes from either
// <input name="csrf_token"> in the form OR the X-CSRF-Token header
// (utils.php injects it globally on jQuery.ajax/fetch).
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (function_exists('pd_csrf_require')) { pd_csrf_require(); }
}

// mailer.php (PHPMailer + autoload) is loaded only when we send a verification email - not on normal password login.

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

// Rate limits — keyed by IP (/24) and by IP+email. The per-(IP+email) bucket
// stops credential stuffing of a known account; the per-IP bucket stops
// horizontal spraying. Numbers tuned to be invisible to humans but lethal
// to bots: 8 attempts per 10 min per (ip, email); 40 per 10 min per ip.
$_pd_ip = pd_client_ip();
$_pd_email_key = strtolower($email);
if (pd_ratelimit_hit("login:$_pd_ip:$_pd_email_key", 8, 600) ||
    pd_ratelimit_hit("login:$_pd_ip", 40, 600)) {
    http_response_code(429);
    echo json_encode(['error' => 'Too many attempts. Please wait a few minutes and try again.']);
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

// Diagnostic logging (added 2026-05-28 to investigate report that
// verify still fires after password login). Logs which branch fires
// per request so we can see in production which path real users hit.
error_log(sprintf(
    '[signin_password] email=%s has_password=%s password_len_provided=%d',
    $email, $hasPassword ? 'yes' : 'NO',
    is_string($password) ? strlen($password) : 0
));

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
    error_log("[signin_password] email=$email branch=PASSWORD_MISMATCH");
    echo json_encode(['error' => 'Invalid email or password.']);
    exit;
}

pd_apply_user_session_from_row($data, $email);
$redirectUrl = pd_new_landing_post_auth_redirect_url($data);
error_log("[signin_password] email=$email branch=SUCCESS redirect=$redirectUrl");
echo json_encode([
    'success' => true,
    'redirect' => $redirectUrl,
]);
exit;

