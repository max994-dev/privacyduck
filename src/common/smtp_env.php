<?php

/**
 * Shared SMTP settings for PHPMailer and diagnostics.
 *
 * Primary: src/common/smtp_config.local.php (return an array of host, username, password, etc.).
 * Override anything with environment variables when set (php-fpm pool, Apache SetEnv, systemd):
 *   SMTP_HOST, SMTP_PORT, SMTP_USERNAME, SMTP_PASSWORD, SMTP_TIMEOUT, SMTP_HELO, SMTP_PORT_FALLBACK,
 *   SMTP_FROM (from address), SMTP_FROM_NAME (display name),
 *   SMTP_RELAY_URL (e.g. http://144.126.136.20:8787/send), SMTP_RELAY_SECRET (Bearer token)
 *
 * If password is empty after env + local file, SMTP sends will fail until smtp_config.local.php
 * or SMTP_PASSWORD is set.
 */
if (!function_exists('pd_smtp_config')) {
    function pd_smtp_config(): array
    {
        static $cache = null;
        if ($cache !== null) {
            return $cache;
        }

        $local = [];
        $localPath = __DIR__ . '/smtp_config.local.php';
        if (is_readable($localPath)) {
            $loaded = require $localPath;
            if (is_array($loaded)) {
                $local = $loaded;
            }
        }

        $password = getenv('SMTP_PASSWORD');
        if ($password === false || $password === '') {
            $password = isset($local['password']) ? (string) $local['password'] : '';
        }
        $host = getenv('SMTP_HOST') ?: ($local['host'] ?? 'mail1.privacyduck.com');
        $username = getenv('SMTP_USERNAME') ?: ($local['username'] ?? 'hello@privacyduck.com');
        $fromEmail = getenv('SMTP_FROM');
        if ($fromEmail === false || $fromEmail === '') {
            $fromEmail = isset($local['from_email']) && (string) $local['from_email'] !== ''
                ? (string) $local['from_email']
                : $username;
        }
        $fromName = getenv('SMTP_FROM_NAME');
        if ($fromName === false || $fromName === '') {
            $fromName = isset($local['from_name']) && (string) $local['from_name'] !== ''
                ? (string) $local['from_name']
                : 'PrivacyDuck';
        }
        $timeout = (int) (getenv('SMTP_TIMEOUT') ?: ($local['timeout'] ?? '30'));
        $timeout = max(5, min(120, $timeout));
        $helo = getenv('SMTP_HELO') ?: ($local['helo'] ?? 'privacyduck.com');

        $port = (int) (getenv('SMTP_PORT') ?: ($local['port'] ?? '587'));
        if ($port < 1 || $port > 65535) {
            $port = 587;
        }

        $fallbackRaw = getenv('SMTP_PORT_FALLBACK');
        if ($fallbackRaw === false || $fallbackRaw === '') {
            $fallback = isset($local['port_fallback']) ? (int) $local['port_fallback'] : 465;
        } else {
            $fallback = (int) $fallbackRaw;
        }
        if ($fallback < 1 || $fallback > 65535) {
            $fallback = 0;
        }
        if ($fallback === $port) {
            $fallback = 0;
        }

        $relayUrl = getenv('SMTP_RELAY_URL');
        if ($relayUrl === false || $relayUrl === '') {
            $relayUrl = isset($local['relay_url']) ? trim((string) $local['relay_url']) : '';
        }
        $relaySecret = getenv('SMTP_RELAY_SECRET');
        if ($relaySecret === false || $relaySecret === '') {
            $relaySecret = isset($local['relay_secret']) ? (string) $local['relay_secret'] : '';
        }

        $cache = [
            'host' => $host,
            'port' => $port,
            'port_fallback' => $fallback,
            'username' => $username,
            'password' => $password,
            'timeout' => $timeout,
            'helo' => $helo,
            'from_email' => $fromEmail,
            'from_name' => $fromName,
            'relay_url' => $relayUrl,
            'relay_secret' => $relaySecret,
        ];
        return $cache;
    }
}

if (!function_exists('pd_phpmailer_apply_smtp')) {
    /**
     * @param \PHPMailer\PHPMailer\PHPMailer $mail
     */
    function pd_phpmailer_apply_smtp($mail, array $cfg, int $port): void
    {
        $mail->isSMTP();
        $mail->Host = $cfg['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $cfg['username'];
        $mail->Password = $cfg['password'];
        $mail->Port = $port;
        $mail->Timeout = $cfg['timeout'];
        $mail->SMTPKeepAlive = false;
        $mail->Hostname = $cfg['helo'];
        // PHPMailer: 'ssl' = implicit TLS (465), 'tls' = STARTTLS (587).
        $mail->SMTPSecure = $port === 465 ? 'ssl' : 'tls';
    }
}
