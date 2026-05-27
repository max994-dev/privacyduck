<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/utils.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/database.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/smtp_relay_client.php';

/**
 * Handles POST from /privacy-request. Validates, rate-limits, inserts a row in
 * dsar_requests, emails staff, and redirects to the confirmation page.
 */

const PD_DSAR_VALID_TYPES = [
    'access', 'rectification', 'erasure', 'restrict',
    'portability', 'object', 'no_automated', 'withdraw_consent',
];

const PD_DSAR_TYPE_LABELS = [
    'access' => 'Access',
    'rectification' => 'Rectification',
    'erasure' => 'Erasure',
    'restrict' => 'Restrict processing',
    'portability' => 'Portability',
    'object' => 'Object to processing',
    'no_automated' => 'No automated decisions',
    'withdraw_consent' => 'Withdraw consent',
];

function pd_dsar_error(string $msg, array $extra = []): void
{
    $qs = ['err' => $msg] + $extra;
    header('Location: ' . WEB_DOMAIN . '/privacy-request?' . http_build_query($qs));
    exit;
}

function pd_dsar_generate_reference(): string
{
    return 'PD-DSAR-' . strtoupper(bin2hex(random_bytes(3)));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    pd_dsar_error('Invalid request.');
}

// Anti-abuse: honeypot. Bots fill hidden "website" field; humans never see it.
// Pretend success so attackers do not learn their submission was blocked.
if (!empty($_POST['website'])) {
    header('Location: ' . WEB_DOMAIN . '/privacy-request/sent?ref=' . urlencode(pd_dsar_generate_reference()));
    exit;
}

// ---- Validate inputs ----
$requestType = trim((string) ($_POST['request_type'] ?? ''));
if (!in_array($requestType, PD_DSAR_VALID_TYPES, true)) {
    pd_dsar_error('Please choose a request type.');
}

$email = trim((string) ($_POST['email'] ?? ''));
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255) {
    pd_dsar_error('Please enter a valid email address.');
}

$name = trim((string) ($_POST['name'] ?? ''));
if (strlen($name) > 200) {
    pd_dsar_error('Name is too long (max 200 characters).');
}

$country = trim((string) ($_POST['country'] ?? ''));
$validCountries = ['', 'UK', 'EU', 'US', 'CA', 'OTHER'];
if (!in_array($country, $validCountries, true)) {
    $country = '';
}

$capacity = trim((string) ($_POST['capacity'] ?? ''));
if (!in_array($capacity, ['self', 'representative'], true)) {
    pd_dsar_error('Please confirm your capacity.');
}

$details = trim((string) ($_POST['details'] ?? ''));
if (strlen($details) > 4000) {
    pd_dsar_error('Details too long (max 4000 characters).');
}

if (empty($_POST['declaration'])) {
    pd_dsar_error('Please tick the confirmation.');
}

// ---- Rate-limit: max 5 from this IP in the last hour ----
$ip = (string) ($_SERVER['REMOTE_ADDR'] ?? '');
$ua = substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 500);

$conn = getDBConnection();
$stmt = $conn->prepare(
    'SELECT COUNT(*) AS n FROM dsar_requests
     WHERE ip_address = ? AND received_at > (NOW() - INTERVAL 1 HOUR)'
);
$stmt->bind_param('s', $ip);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();
if ((int) ($row['n'] ?? 0) >= 5) {
    $conn->close();
    pd_dsar_error('Too many requests from this address. Please wait an hour and try again, or email privacy@privacyduck.com.');
}

// ---- Match to existing user (informational only - not a foreign key) ----
$stmt = $conn->prepare('SELECT id FROM users WHERE LOWER(TRIM(email)) = LOWER(?) LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$matched = $stmt->get_result()->fetch_assoc();
$stmt->close();
$matchedUserId = $matched ? (int) $matched['id'] : null;

// ---- Generate reference + insert ----
$reference = pd_dsar_generate_reference();
$receivedAt = date('Y-m-d H:i:s');
$deadlineAt = date('Y-m-d H:i:s', strtotime('+1 month'));

// Note: status omitted; column has DEFAULT 'open'. Avoids portability issues
// with ANSI_QUOTES SQL_MODE where double-quoted "open" would be parsed as a
// column reference. Single-quoted 'open' would also work; relying on the
// default is cleaner.
$stmt = $conn->prepare(
    'INSERT INTO dsar_requests
     (reference, request_type, email, name, country, capacity, matched_user_id,
      details, ip_address, user_agent, received_at, deadline_at)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
);
$nameVal = $name !== '' ? $name : null;
$countryVal = $country !== '' ? $country : null;
$detailsVal = $details !== '' ? $details : null;
// 12 placeholders => 12 type chars: 6 strings, 1 int (matched_user_id), 5 strings
$stmt->bind_param(
    'ssssssisssss',
    $reference,
    $requestType,
    $email,
    $nameVal,
    $countryVal,
    $capacity,
    $matchedUserId,
    $detailsVal,
    $ip,
    $ua,
    $receivedAt,
    $deadlineAt
);

if (!$stmt->execute()) {
    error_log('DSAR insert failed: ' . $stmt->error);
    $stmt->close();
    $conn->close();
    pd_dsar_error('Could not record your request. Please try again or email privacy@privacyduck.com.');
}
$stmt->close();
$conn->close();

// ---- Email staff (best-effort - do not block confirmation on email failure) ----
$typeLabel = PD_DSAR_TYPE_LABELS[$requestType] ?? $requestType;
$subject = "DSAR request {$reference}: {$typeLabel}";
$body = '<h2 style="margin:0 0 16px;font:600 18px/1.3 system-ui,sans-serif;">New privacy request</h2>'
      . '<table style="font:14px/1.5 system-ui,sans-serif;border-collapse:collapse;">'
      . '<tr><td style="padding:4px 12px 4px 0;color:#666;">Reference</td><td><strong>' . htmlspecialchars($reference) . '</strong></td></tr>'
      . '<tr><td style="padding:4px 12px 4px 0;color:#666;">Type</td><td>' . htmlspecialchars($typeLabel) . '</td></tr>'
      . '<tr><td style="padding:4px 12px 4px 0;color:#666;">Email</td><td>' . htmlspecialchars($email) . '</td></tr>'
      . '<tr><td style="padding:4px 12px 4px 0;color:#666;">Name</td><td>' . htmlspecialchars($name !== '' ? $name : '(not provided)') . '</td></tr>'
      . '<tr><td style="padding:4px 12px 4px 0;color:#666;">Country</td><td>' . htmlspecialchars($country !== '' ? $country : '(not provided)') . '</td></tr>'
      . '<tr><td style="padding:4px 12px 4px 0;color:#666;">Capacity</td><td>' . htmlspecialchars($capacity) . '</td></tr>'
      . '<tr><td style="padding:4px 12px 4px 0;color:#666;">Matches user</td><td>' . ($matchedUserId ? ('users.id = ' . (int) $matchedUserId) : '(no account with this email)') . '</td></tr>'
      . '<tr><td style="padding:4px 12px 4px 0;color:#666;">Received</td><td>' . htmlspecialchars($receivedAt) . '</td></tr>'
      . '<tr><td style="padding:4px 12px 4px 0;color:#666;">Deadline</td><td><strong style="color:#a00;">' . htmlspecialchars($deadlineAt) . '</strong></td></tr>'
      . '</table>'
      . '<h3 style="margin:20px 0 8px;font:600 14px/1.3 system-ui,sans-serif;">Details from the requester</h3>'
      . '<div style="white-space:pre-wrap;border-left:3px solid #24A556;padding:8px 12px;background:#f8faf9;font:14px/1.5 system-ui,sans-serif;">'
      . htmlspecialchars($details !== '' ? $details : '(none provided)')
      . '</div>'
      . '<p style="margin-top:20px;color:#888;font:11px/1.4 system-ui,sans-serif;">IP: ' . htmlspecialchars($ip) . '<br>UA: ' . htmlspecialchars($ua) . '</p>';

@pd_smtp_relay_send_html(
    'hello@privacyduck.com',
    $subject,
    $body,
    'noreply@privacyduck.com',
    'PrivacyDuck DSAR'
);

header('Location: ' . WEB_DOMAIN . '/privacy-request/sent?ref=' . urlencode($reference));
exit;
