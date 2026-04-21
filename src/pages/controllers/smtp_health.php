<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/config.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/smtp_env.php';

header('Content-Type: application/json; charset=utf-8');

$providedKey = isset($_GET['key']) ? (string) $_GET['key'] : '';
if ($providedKey === '' || !defined('SMTP_HEALTH_KEY') || !hash_equals((string) SMTP_HEALTH_KEY, $providedKey)) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

$cfgDefaults = pd_smtp_config();
$host = isset($_GET['host']) ? trim((string) $_GET['host']) : $cfgDefaults['host'];
$user = isset($_GET['user']) ? (string) $_GET['user'] : $cfgDefaults['username'];
$pass = isset($_GET['pass']) && (string) $_GET['pass'] !== '' ? (string) $_GET['pass'] : $cfgDefaults['password'];
$port = isset($_GET['port']) ? (int) $_GET['port'] : (int) $cfgDefaults['port'];
$timeout = isset($_GET['timeout']) ? max(2, min(120, (int) $_GET['timeout'])) : (int) $cfgDefaults['timeout'];
$from = (isset($_GET['from']) && (string) $_GET['from'] !== '') ? (string) $_GET['from'] : $cfgDefaults['from_email'];
$ehlo = isset($_GET['ehlo']) ? trim((string) $_GET['ehlo']) : $cfgDefaults['helo'];

function smtp_read_line($fp, int $timeout = 8): string
{
    stream_set_timeout($fp, $timeout);
    $line = fgets($fp, 2048);
    return $line === false ? '' : rtrim($line, "\r\n");
}

function smtp_read_reply($fp, int $timeout = 8): array
{
    $lines = [];
    $code = 0;
    for ($i = 0; $i < 30; $i++) {
        $line = smtp_read_line($fp, $timeout);
        if ($line === '') {
            break;
        }
        $lines[] = $line;
        if (preg_match('/^(\d{3})([\s-])/', $line, $m)) {
            $code = (int) $m[1];
            if ($m[2] === ' ') {
                break;
            }
        } else {
            break;
        }
    }
    return ['code' => $code, 'lines' => $lines];
}

function smtp_cmd($fp, string $cmd, int $timeout = 8): array
{
    fwrite($fp, $cmd . "\r\n");
    $reply = smtp_read_reply($fp, $timeout);
    return ['cmd' => $cmd, 'reply' => $reply];
}

$result = [
    'ok' => false,
    'host' => $host,
    'port' => $port,
    'timeout' => $timeout,
    'dns' => [
        'a' => @gethostbynamel($host) ?: [],
        'mx' => [],
    ],
    'tcp' => null,
    'smtp' => [],
];

$mx = [];
if (@getmxrr($host, $mx)) {
    $result['dns']['mx'] = $mx;
}

$errno = 0;
$errstr = '';
$fp = @stream_socket_client("tcp://{$host}:{$port}", $errno, $errstr, $timeout);
if (!$fp) {
    $result['tcp'] = ['ok' => false, 'error_no' => $errno, 'error' => $errstr];
    echo json_encode($result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    exit;
}
$result['tcp'] = ['ok' => true];

stream_set_timeout($fp, $timeout);

$banner = smtp_read_reply($fp, $timeout);
$result['smtp'][] = ['phase' => 'banner', 'reply' => $banner];

$result['smtp'][] = ['phase' => 'ehlo1', 'data' => smtp_cmd($fp, 'EHLO ' . $ehlo, $timeout)];

$result['smtp'][] = ['phase' => 'starttls', 'data' => smtp_cmd($fp, 'STARTTLS', $timeout)];
$last = end($result['smtp']);
$starttlsCode = (int) ($last['data']['reply']['code'] ?? 0);
if ($starttlsCode !== 220) {
    @fclose($fp);
    echo json_encode($result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    exit;
}

$cryptoOk = @stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
$result['smtp'][] = ['phase' => 'tls_upgrade', 'ok' => (bool) $cryptoOk];
if (!$cryptoOk) {
    @fclose($fp);
    echo json_encode($result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    exit;
}

$result['smtp'][] = ['phase' => 'ehlo2', 'data' => smtp_cmd($fp, 'EHLO ' . $ehlo, $timeout)];
$result['smtp'][] = ['phase' => 'auth_login', 'data' => smtp_cmd($fp, 'AUTH LOGIN', $timeout)];
$result['smtp'][] = ['phase' => 'auth_user', 'data' => smtp_cmd($fp, base64_encode($user), $timeout)];
$result['smtp'][] = ['phase' => 'auth_pass', 'data' => smtp_cmd($fp, base64_encode($pass), $timeout)];

$last = end($result['smtp']);
$authCode = (int) ($last['data']['reply']['code'] ?? 0);
$result['ok'] = ($authCode === 235);

if ($result['ok']) {
    // Optional lightweight sender check (MAIL FROM only), no DATA/recipient is sent.
    $result['smtp'][] = ['phase' => 'mail_from', 'data' => smtp_cmd($fp, 'MAIL FROM:<' . $from . '>', $timeout)];
}

$result['smtp'][] = ['phase' => 'quit', 'data' => smtp_cmd($fp, 'QUIT', $timeout)];
@fclose($fp);

echo json_encode($result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
exit;
