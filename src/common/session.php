<?php
// Set custom session config — must be set before session_start()
ini_set('session.gc_maxlifetime', 2592000);  // 30 days in seconds
ini_set('session.cookie_lifetime', 2592000); // 30 days in seconds
ini_set('session.save_path', '/var/lib/php/sessions');

// Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Optional: Auto-refresh session expiration (rolling session)
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // Last request was more than 30 mins ago
    session_unset();     // Unset $_SESSION variable
    session_destroy();   // Destroy session data
}
$_SESSION['LAST_ACTIVITY'] = time(); // Update activity timestamp
?>
