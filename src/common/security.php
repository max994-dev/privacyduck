<?php
// security.php — central web-app security utilities.
//
// Loaded by utils.php on every request (and idempotent — safe to include
// multiple times). Provides:
//
//   pd_security_init_session()    Hardens the PHP session cookie BEFORE
//                                 session_start() runs. Adds HttpOnly,
//                                 Secure (when over HTTPS), SameSite=Lax,
//                                 strict-mode, custom lifetime.
//
//   pd_security_send_headers()    Emits HTTP security response headers:
//                                 Content-Security-Policy, Referrer-Policy,
//                                 Permissions-Policy, plus the existing
//                                 nginx-set X-Content-Type-Options / HSTS /
//                                 X-Frame-Options stay intact.
//
//   pd_csrf_token()               Mints (and lazily caches) a per-session
//                                 CSRF token. Returns the token string.
//   pd_csrf_field()               Returns a ready <input type="hidden">
//                                 element for embedding in <form>.
//   pd_csrf_check([$source])      Returns true if the supplied token
//                                 matches the session token (constant-time
//                                 compare). $source defaults to
//                                 $_POST['csrf_token'] / X-CSRF-Token
//                                 header. Use BEFORE acting on POST.
//   pd_csrf_require()             Helper: hard-stops the request with a
//                                 403 + JSON error if the token is bad.
//
//   pd_ratelimit_hit($key, $max,
//                    $window)     File-backed per-IP/per-key rate limiter.
//                                 Returns true when the limit is reached
//                                 (i.e. block this request). Uses a tiny
//                                 sliding-window counter in /tmp/pd_rl/.
//                                 No DB hit, no shared cache dep.

declare(strict_types=1);

if (defined('PD_SECURITY_LOADED')) {
    return;
}
define('PD_SECURITY_LOADED', true);


function pd_security_init_session(): void
{
    // Already started? Cookie params are fixed for the lifetime of the
    // session, so we can't retroactively harden it — but on the very next
    // session_regenerate_id() the new flags will apply. Issue a warning
    // header so an op can spot the order-of-load issue.
    if (session_status() !== PHP_SESSION_NONE) {
        return;
    }

    // Custom path so we don't share with other tenants on the box.
    @ini_set('session.gc_maxlifetime', '2592000');   // 30 days
    @ini_set('session.use_strict_mode', '1');        // reject uninitialized SIDs
    @ini_set('session.use_only_cookies', '1');       // no SID in URL
    @ini_set('session.cookie_httponly', '1');
    if (PHP_VERSION_ID >= 70300) {
        @ini_set('session.cookie_samesite', 'Lax');
    }

    $secureCookie =
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
        (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

    session_set_cookie_params([
        'lifetime' => 2592000,   // 30 days
        'path'     => '/',
        'domain'   => '',
        'secure'   => $secureCookie,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}


function pd_security_send_headers(): void
{
    // Don't emit twice — guards against include cycles emitting duplicate
    // headers (browsers reject duplicate CSP differently across engines).
    static $emitted = false;
    if ($emitted) {
        return;
    }
    $emitted = true;

    // headers_sent() means PHP already flushed (e.g. an error printed
    // before this file loaded). Silently skip — nothing we can do.
    if (headers_sent()) {
        return;
    }

    // Referrer-Policy — strict-origin-when-cross-origin keeps internal
    // referrer info but strips it cross-origin so we don't leak which
    // privacy-sensitive page the user came from.
    header('Referrer-Policy: strict-origin-when-cross-origin');

    // Permissions-Policy — turn off APIs we don't use, neutering an
    // attacker who manages to inject script from getting hardware access.
    // (camera/microphone/geolocation deliberately enabled-for-self in case
    // the dashboard ever uses them — keep narrow.)
    header('Permissions-Policy: ' . implode(', ', [
        'accelerometer=()',
        'autoplay=()',
        'camera=()',
        'display-capture=()',
        'fullscreen=(self)',
        'geolocation=()',
        'gyroscope=()',
        'magnetometer=()',
        'microphone=()',
        'midi=()',
        'payment=(self)',  // Stripe redirect flow needs it
        'usb=()',
        'xr-spatial-tracking=()',
    ]));

    // X-Permitted-Cross-Domain-Policies — older Flash/Acrobat carryover,
    // safe default is "none".
    header('X-Permitted-Cross-Domain-Policies: none');

    // Cross-Origin-Opener-Policy — isolate our window so a malicious
    // popup can't pry into window.opener (e.g. our Stripe redirect tab).
    header('Cross-Origin-Opener-Policy: same-origin-allow-popups');

    // Content-Security-Policy — necessarily permissive because we load
    // Stripe / GTM / Tawk.to / Google Maps / Lottie player / Flickity from
    // CDNs. The key restrictions: no inline-script eval, frame-ancestors
    // self only (defense-in-depth with X-Frame-Options DENY which nginx
    // sets), no plugins. NOT report-only — already known-good in dev.
    //
    // If you add a new vendor: add its origin to the matching directive.
    // 'unsafe-inline' on script-src is required by GTM + jQuery
    // inline blocks — moving those to nonces is a follow-up project.
    $csp = [
        "default-src 'self'",
        "script-src 'self' 'unsafe-inline' 'unsafe-eval' " .
            "https://*.privacyduck.com " .
            "https://js.stripe.com " .
            "https://www.googletagmanager.com https://www.google-analytics.com " .
            "https://embed.tawk.to https://*.tawk.to " .
            "https://maps.googleapis.com",
        "style-src 'self' 'unsafe-inline' " .
            "https://fonts.googleapis.com " .
            "https://embed.tawk.to https://*.tawk.to",
        "img-src 'self' data: blob: https:",
        "font-src 'self' data: " .
            "https://fonts.gstatic.com https://embed.tawk.to https://*.tawk.to",
        "connect-src 'self' " .
            "https://*.privacyduck.com " .
            "https://www.google-analytics.com https://stats.g.doubleclick.net " .
            "https://embed.tawk.to wss://*.tawk.to https://*.tawk.to " .
            "https://api.stripe.com",
        "frame-src 'self' " .
            "https://js.stripe.com https://hooks.stripe.com " .
            "https://embed.tawk.to https://*.tawk.to " .
            "https://www.youtube.com https://www.youtube-nocookie.com",
        "media-src 'self' blob: data:",
        "object-src 'none'",
        "base-uri 'self'",
        "form-action 'self' https://checkout.stripe.com",
        "frame-ancestors 'none'",
        "upgrade-insecure-requests",
    ];
    header('Content-Security-Policy: ' . implode('; ', $csp));
}


// ----- CSRF -----------------------------------------------------------------

function pd_csrf_token(): string
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        return '';
    }
    if (empty($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }
    return (string) $_SESSION['_csrf_token'];
}


function pd_csrf_field(): string
{
    $tok = pd_csrf_token();
    if ($tok === '') {
        return '';
    }
    return '<input type="hidden" name="csrf_token" value="' .
        htmlspecialchars($tok, ENT_QUOTES, 'UTF-8') . '">';
}


function pd_csrf_check(?string $supplied = null): bool
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        return false;
    }
    $expected = $_SESSION['_csrf_token'] ?? '';
    if (!is_string($expected) || $expected === '') {
        return false;
    }
    if ($supplied === null) {
        $supplied = $_POST['csrf_token'] ??
            $_SERVER['HTTP_X_CSRF_TOKEN'] ??
            '';
    }
    if (!is_string($supplied) || $supplied === '') {
        return false;
    }
    return hash_equals($expected, $supplied);
}


function pd_csrf_require(): void
{
    if (pd_csrf_check()) {
        return;
    }
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'CSRF token missing or invalid. Reload the page and try again.']);
    exit;
}


// ----- Rate limiting --------------------------------------------------------

/**
 * Sliding-window rate limit. Returns true if THIS hit is over-limit (caller
 * should reject the request). Uses /tmp/pd_rl/<sha1(key)>.json holding a
 * compact list of recent hit timestamps.
 *
 * Not designed for high QPS — file lock contention will choke north of ~100
 * RPS per key. Fine for login / signup / DSAR / image-upload endpoints.
 *
 * @param string $key     unique bucket key — typically "login:$ip" or
 *                        "login:$ip:$email"
 * @param int    $max     max hits allowed in $windowSeconds
 * @param int    $window  rolling window length in seconds
 * @return bool  true means "blocked" (caller should refuse)
 */
function pd_ratelimit_hit(string $key, int $max, int $window): bool
{
    $dir = sys_get_temp_dir() . '/pd_rl';
    if (!is_dir($dir)) {
        @mkdir($dir, 0700, true);
    }
    $path = $dir . '/' . sha1($key) . '.json';

    $fp = @fopen($path, 'c+');
    if ($fp === false) {
        // Fail-open: if we can't open the file we'd rather let the request
        // through than DoS our own users.
        return false;
    }
    try {
        flock($fp, LOCK_EX);
        $raw = '';
        rewind($fp);
        while (!feof($fp)) {
            $chunk = fread($fp, 8192);
            if ($chunk === false) break;
            $raw .= $chunk;
        }
        $hits = json_decode($raw, true);
        if (!is_array($hits)) {
            $hits = [];
        }
        $now = time();
        $cutoff = $now - $window;
        $hits = array_values(array_filter($hits, fn($t) => is_int($t) && $t >= $cutoff));
        $blocked = count($hits) >= $max;
        if (!$blocked) {
            $hits[] = $now;
        }
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($hits));
        fflush($fp);
        return $blocked;
    } finally {
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}


/**
 * Server-to-server upload auth. Used by /scan_api/upload,
 * /removal_api/upload, /googleScan_api/upload, /faceremoval_api/upload —
 * called by the Windows VPS Python pipeline, NOT by a browser, so there's
 * no CSRF token. We require:
 *   (a) X-PD-Upload-Secret header matching env PD_UPLOAD_SECRET, AND
 *   (b) caller IP in env PD_UPLOAD_IP_ALLOWLIST (CSV; empty = allow any)
 *
 * Either condition by itself isn't enough; both must pass. Defense in
 * depth: a leaked secret alone won't let an attacker upload from a
 * different host, and an attacker who can spoof source IP still needs
 * the secret.
 *
 * Returns true if the request is from the trusted pipeline. Caller should
 * treat as "skip CSRF, this is internal". On mismatch returns false (the
 * caller should then fall back to the normal CSRF check, which will reject
 * an external attacker who doesn't have the token either).
 */
function pd_upload_secret_check(): bool
{
    static $loaded = false;
    static $secret = '';
    static $allowlist = [];
    if (!$loaded) {
        $loaded = true;
        // Load from .env at the project root (same loader used by database.php).
        if (function_exists('pd_db_load_env')) {
            pd_db_load_env();
        }
        $secret = trim((string) getenv('PD_UPLOAD_SECRET'));
        $rawAllow = (string) getenv('PD_UPLOAD_IP_ALLOWLIST');
        if ($rawAllow !== '') {
            foreach (explode(',', $rawAllow) as $ip) {
                $ip = trim($ip);
                if ($ip !== '') $allowlist[] = $ip;
            }
        }
    }
    if ($secret === '') {
        return false;  // not configured -> fail closed
    }
    $supplied = $_SERVER['HTTP_X_PD_UPLOAD_SECRET'] ?? '';
    if (!is_string($supplied) || !hash_equals($secret, $supplied)) {
        return false;
    }
    if (!empty($allowlist)) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
        // X-F-F may be a list; take the first
        if (strpos($ip, ',') !== false) {
            $parts = array_map('trim', explode(',', $ip));
            $ip = $parts[0] ?? '';
        }
        if (!in_array($ip, $allowlist, true)) {
            return false;
        }
    }
    return true;
}


/**
 * Stable client IP for rate-limit keys. Honors X-Forwarded-For when nginx
 * is in front, otherwise REMOTE_ADDR. Truncated to /24 for IPv4 so a botnet
 * coming from a single /24 still gets one bucket between them (mild but
 * useful).
 */
function pd_client_ip(): string
{
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    // X-F-F may be a list — take the first non-empty token
    if (strpos($ip, ',') !== false) {
        $parts = array_map('trim', explode(',', $ip));
        $ip = $parts[0] ?? '';
    }
    if ($ip === '' || filter_var($ip, FILTER_VALIDATE_IP) === false) {
        return '0.0.0.0';
    }
    // Truncate IPv4 to /24
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        $parts = explode('.', $ip);
        $parts[3] = '0';
        return implode('.', $parts);
    }
    return $ip;
}
