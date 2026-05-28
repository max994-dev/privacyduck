<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/utils.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/database.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/auth_redirect.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/new_signup_insert.php';

function new_signup_redirect_error(string $msg): void
{
    header('Location: ' . WEB_DOMAIN . '/new_signup?err=' . rawurlencode($msg));
    exit;
}

function new_signup_clear_pending_session(): void
{
    unset(
        $_SESSION['verify_code'],
        $_SESSION['auth_flow'],
        $_SESSION['new_signup_password_hash'],
        $_SESSION['new_signup_agree_marketing'],
        $_SESSION['new_signup_profile'],
        $_SESSION['new_signup_consent_version'],
        $_SESSION['new_signup_consent_at']
    );
}

// UK GDPR Art. 7(1) audit trail: bump this string whenever the privacy policy
// has a material change. Existing users keep their old version; new signups
// from that day onward are recorded as accepting the new one.
const PD_PRIVACY_POLICY_VERSION = '2026-05-26';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    new_signup_redirect_error('Invalid request.');
}

// CSRF guard — signup is the highest-impact public form (creates an
// authenticated session) so it gets a token check before anything else.
if (!pd_csrf_check()) {
    new_signup_redirect_error('Your session has expired. Please refresh the page and try again.');
}

// Throttle signup attempts per (IP, email) — stops scripted enumeration
// runs against the "is this email registered?" response below.
$_pd_signup_ip = pd_client_ip();
$_pd_signup_email_key = strtolower(trim($_POST['email'] ?? ''));
if (pd_ratelimit_hit("signup:$_pd_signup_ip:$_pd_signup_email_key", 5, 600) ||
    pd_ratelimit_hit("signup:$_pd_signup_ip", 30, 600)) {
    new_signup_redirect_error('Too many signup attempts. Please wait a few minutes.');
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$passwordConfirm = $_POST['password_confirm'] ?? '';
$agreeTerms = isset($_POST['agree_terms']) && (string) $_POST['agree_terms'] === '1';
$agreeMarketing = isset($_POST['agree_marketing']) && (string) $_POST['agree_marketing'] === '1';

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
    new_signup_redirect_error('You must confirm you have read the Privacy Policy and Cookie Policy to create an account.');
}

// UK GDPR audit: capture exact moment of consent + which policy version applies.
$consentAt = date('Y-m-d H:i:s');
$consentVersion = PD_PRIVACY_POLICY_VERSION;
$marketingConsentAt = $agreeMarketing ? $consentAt : null;

$parsed = pd_new_signup_parse_profile_from_post($_POST);
if (!$parsed['ok']) {
    new_signup_redirect_error($parsed['error']);
}
$signupProfile = $parsed['data'];

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
$conn->close();

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

if (email_verification_bypassed($email)) {
    $data = pd_insert_new_signup_user(
        $email,
        $passwordHash,
        $agreeMarketing ? 1 : 0,
        $signupProfile,
        $consentVersion,
        $consentAt,
        $marketingConsentAt
    );
    if (!$data) {
        new_signup_redirect_error('Could not create account. Please try again.');
    }
    pd_apply_user_session_from_row($data, $email);
    header('Location: ' . pd_new_landing_post_auth_redirect_url($data));
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/mailer.php';

$verificationCode = random_int(100000, 999999);
$_SESSION['verify_code'] = $verificationCode;
$_SESSION['email'] = $email;
$_SESSION['auth_flow'] = 'new_signup';
$_SESSION['new_signup_password_hash'] = $passwordHash;
$_SESSION['new_signup_agree_marketing'] = $agreeMarketing ? 1 : 0;
$_SESSION['new_signup_profile'] = $signupProfile;
$_SESSION['new_signup_consent_version'] = $consentVersion;
$_SESSION['new_signup_consent_at'] = $consentAt;

if (!sendVerificationCodeEmail(
    $email,
    $verificationCode,
    'PrivacyDuck.com',
    'Verification for Privacyduck.com',
    'Verify your PrivacyDuck account'
)) {
    new_signup_clear_pending_session();
    new_signup_redirect_error('Could not send verification email. Please try again.');
}

header('Location: ' . WEB_DOMAIN . '/verify');
exit;
