<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/utils.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/auth_redirect.php');
// mailer.php loaded only when sending the code (cookie / bypass paths skip SMTP entirely).

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

if ($email === '') {
    echo json_encode(['error' => 'Please enter your email first.']);
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
    echo json_encode(['error' => 'Invalid user!']);
    exit;
}

$data = $result->fetch_assoc();
$stmt->close();
$conn->close();

$email = isset($data['email']) ? (string) $data['email'] : $email;

if (!pd_user_may_login($data)) {
    echo json_encode(['error' => 'Invalid user!']);
    exit;
}

try {
    if (isset($_COOKIE['info']) && strcasecmp((string) $_COOKIE['info'], $email) === 0) {
        pd_apply_user_session_from_row($data, $email);
        echo json_encode([
            'success' => 'prelogin',
            'redirect' => pd_new_landing_post_auth_redirect_url($data),
        ]);
        exit;
    }

    if (email_verification_bypassed($email)) {
        pd_apply_user_session_from_row($data, $email);
        echo json_encode([
            'success' => 'prelogin',
            'redirect' => pd_new_landing_post_auth_redirect_url($data),
        ]);
        exit;
    }

    $verificationCode = random_int(100000, 999999);
    $_SESSION['verify_code'] = $verificationCode;
    $_SESSION['email'] = $email;
    $_SESSION['auth_flow'] = 'new_landing';

    require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/mailer.php';
    if (sendVerificationCodeEmail($email, $verificationCode, 'PrivacyDuck.com', 'Verification for Privacyduck.com')) {
        echo json_encode(['success' => 'verify']);
        exit;
    }
    unset($_SESSION['auth_flow']);
    echo json_encode(['error' => 'Could not send email. Try again.']);
    exit;
} catch (Throwable $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

