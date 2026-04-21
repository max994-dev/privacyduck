<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

$intent = isset($_POST['intent']) ? (int) $_POST['intent'] : 0;
if ($intent) {
    $_SESSION['pd_book_call_intent'] = 1;
} else {
    unset($_SESSION['pd_book_call_intent'], $_SESSION['pd_book_call_done']);
}

echo json_encode(['ok' => true, 'intent' => !empty($_SESSION['pd_book_call_intent'])]);
