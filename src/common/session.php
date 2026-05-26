<?php
// Set custom session config — must be set before session_start()
ini_set('session.gc_maxlifetime', 2592000);  // 30 days in seconds
ini_set('session.cookie_lifetime', 2592000); // 30 days in seconds
ini_set('session.save_path', '/var/lib/php/sessions');
ini_set('session.use_strict_mode', '1');     // prevent uninitialized session IDs

// Harden session cookie flags before starting the session.
$secureCookie = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 2592000,
        'path'     => '/',
        'secure'   => $secureCookie,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// Rolling idle timeout — destroy + immediately stop so the request can't continue
// as the "old" user. Previous version re-set LAST_ACTIVITY after destroy(), which
// silently undid the logout.
$idleLimit = 1800; // 30 min
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - (int) $_SESSION['LAST_ACTIVITY'] > $idleLimit)) {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires'  => time() - 42000,
            'path'     => $params['path'],
            'domain'   => $params['domain'],
            'secure'   => $params['secure'],
            'httponly' => $params['httponly'],
            'samesite' => $params['samesite'] ?? 'Lax',
        ]);
    }
    session_destroy();
    // Start a fresh, empty session so downstream code that touches $_SESSION won't warn.
    session_start();
    session_regenerate_id(true);
}
$_SESSION['LAST_ACTIVITY'] = time();
?>
