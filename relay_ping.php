<?php
/**
 * Test relay HTTP reachability + auth from this server (CLI):
 *   php relay_ping.php
 */
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/common/smtp_env.php';
require_once __DIR__ . '/src/common/smtp_relay_client.php';

$cfg = pd_smtp_config();
$url = trim((string) ($cfg['relay_url'] ?? ''));
$hasSecret = ($cfg['relay_secret'] ?? '') !== '';

echo "relay_url: " . ($url !== '' ? $url : '(empty)') . "\n";
echo "relay_secret set: " . ($hasSecret ? 'yes' : 'no') . "\n";

if ($url === '' || !$hasSecret) {
    fwrite(STDERR, "Configure relay_url + relay_secret in smtp_config.local.php or env.\n");
    exit(1);
}

$ok = pd_smtp_relay_send_html(
    (string) $cfg['from_email'],
    'Relay ping ' . gmdate('Y-m-d H:i:s') . ' UTC',
    '<p>Relay connectivity test from PrivacyDuck web server.</p>',
    (string) $cfg['from_email'],
    (string) $cfg['from_name'],
    'Relay connectivity test.',
    []
);

if ($ok) {
    echo "OK: relay accepted and reported success (check inbox for test mail).\n";
    exit(0);
}

fwrite(STDERR, "FAIL: check PHP error_log, or run from web server: curl -4 -s https://api.ipify.org\n");
fwrite(STDERR, "  If relay returns 403 client_ip_not_allowed, add that IPv4 to ALLOW_SOURCE_IPS on Windows.\n");
fwrite(STDERR, "  If invalid_bearer_token, sync RELAY_SECRET with relay_secret in smtp_config.local.php.\n");
exit(1);
