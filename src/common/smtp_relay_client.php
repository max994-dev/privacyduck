<?php

/**
 * POST HTML mail to a remote relay (e.g. Windows VPS) that sends via SMTP.
 * Requires curl extension.
 */
if (!function_exists('pd_smtp_relay_send_html')) {
    /**
     * @param list<array{cid: string, path: string, mime?: string, filename?: string}> $inlineImages
     */
    function pd_smtp_relay_send_html(
        string $to,
        string $subject,
        string $htmlBody,
        string $fromEmail,
        string $fromName,
        string $altBody = '',
        array $inlineImages = []
    ): bool {
        require_once __DIR__ . '/smtp_env.php';
        $cfg = pd_smtp_config();
        $url = trim((string) ($cfg['relay_url'] ?? ''));
        $secret = (string) ($cfg['relay_secret'] ?? '');
        if ($url === '' || $secret === '') {
            return false;
        }

        $payload = [
            'to' => $to,
            'subject' => $subject,
            'html' => $htmlBody,
            'from_email' => $fromEmail,
            'from_name' => $fromName,
            'text_plain' => $altBody !== '' ? $altBody : strip_tags($htmlBody),
        ];

        $inlines = [];
        foreach ($inlineImages as $row) {
            $path = $row['path'] ?? '';
            if ($path === '' || !is_readable($path)) {
                continue;
            }
            $raw = @file_get_contents($path);
            if ($raw === false) {
                continue;
            }
            $inlines[] = [
                'cid' => (string) ($row['cid'] ?? ''),
                'mime' => (string) ($row['mime'] ?? 'image/png'),
                'filename' => (string) ($row['filename'] ?? basename($path)),
                'data' => base64_encode($raw),
            ];
        }
        if ($inlines !== []) {
            $payload['inline_images'] = $inlines;
        }

        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            error_log('pd_smtp_relay_send_html: json_encode failed');
            return false;
        }

        if (!function_exists('curl_init')) {
            error_log('pd_smtp_relay_send_html: PHP curl extension required');
            return false;
        }

        $ch = curl_init($url);
        $opts = [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json; charset=utf-8',
                'Authorization: Bearer ' . $secret,
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_TIMEOUT => min(120, max(15, (int) ($cfg['timeout'] ?? 60) + 30)),
        ];
        if (defined('CURL_IPRESOLVE_V4')) {
            $opts[CURLOPT_IPRESOLVE] = CURL_IPRESOLVE_V4;
        }
        curl_setopt_array($ch, $opts);

        $response = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            error_log('pd_smtp_relay_send_html: curl error: ' . $err);
            return false;
        }

        if ($code !== 200) {
            $snippet = substr((string) $response, 0, 800);
            $j = json_decode((string) $response, true);
            if (is_array($j) && isset($j['error'])) {
                error_log('pd_smtp_relay_send_html: HTTP ' . $code . ' relay error: ' . $j['error']
                    . (isset($j['hint']) ? ' — ' . $j['hint'] : '')
                    . (isset($j['client_ip']) ? ' (client seen as ' . $j['client_ip'] . ')' : ''));
            } else {
                error_log('pd_smtp_relay_send_html: HTTP ' . $code . ' body: ' . $snippet);
            }
            return false;
        }

        $decoded = json_decode((string) $response, true);
        return is_array($decoded) && !empty($decoded['ok']);
    }
}
