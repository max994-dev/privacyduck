<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_id'])) {
    header('Location: ' . WEB_DOMAIN . '/login');
    exit;
}

$_SESSION['pd_book_call_done'] = 1;
unset($_SESSION['pd_book_call_intent']);

header('Location: ' . WEB_DOMAIN . '/dashboard');
exit;
