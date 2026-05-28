<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/config.php';

// CSRF: state-mutating endpoint. Token comes from either
// <input name="csrf_token"> in the form OR the X-CSRF-Token header
// (utils.php injects it globally on jQuery.ajax/fetch).
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (function_exists('pd_csrf_require')) { pd_csrf_require(); }
}


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_id'])) {
    header('Location: ' . WEB_DOMAIN . '/new_signin');
    exit;
}

$_SESSION['pd_book_call_done'] = 1;
unset($_SESSION['pd_book_call_intent']);

header('Location: ' . WEB_DOMAIN . '/dashboard');
exit;
