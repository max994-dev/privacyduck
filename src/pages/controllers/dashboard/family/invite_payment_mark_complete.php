<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/security.php';

// CSRF: state-mutating endpoint. Token comes from either
// <input name="csrf_token"> in the form OR the X-CSRF-Token header
// (utils.php injects it globally on jQuery.ajax/fetch).
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (function_exists('pd_csrf_require')) { pd_csrf_require(); }
}

if (!isset($_SESSION["planable"]) || $_SESSION["planable"] == 0) {
    http_response_code(403);
    header("Content-Type: application/json");
    die(json_encode(["error" => "Planable error!"]));
}

header("Content-Type: application/json");

// Trust: user returned from hosted Stripe checkout (closing the checkout window).
// No email code - allow invite_member on this session (TTL enforced in check_status).
unset($_SESSION["verify_code"]);
$_SESSION["invite_pay_verified"] = true;
$_SESSION["invite_pay_verified_at"] = time();

echo json_encode(["success" => true]);
