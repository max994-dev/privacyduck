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

$pending = $_SESSION["pending_family_invite"] ?? null;
if (!is_array($pending)) {
    echo json_encode(["success" => true, "skipped" => true]);
    exit;
}

$maxAge = 3600;
$savedAt = (int) ($pending["saved_at"] ?? 0);
if ($savedAt <= 0 || (time() - $savedAt) > $maxAge) {
    unset($_SESSION["pending_family_invite"]);
    http_response_code(400);
    die(json_encode(["error" => "Pending invite expired. Add the member again."]));
}

$_POST["first_name"] = $pending["first_name"];
$_POST["last_name"] = $pending["last_name"];
$_POST["email"] = $pending["email"];
$_POST["contacts"] = $pending["contacts"];

$_SESSION["invite_requirePayment"] = 1;
$_SESSION["invite_pay_verified"] = true;
$_SESSION["invite_pay_verified_at"] = time();

ob_start();
require __DIR__ . "/invite.php";
$output = trim(ob_get_clean());

if ($output === "success") {
    unset($_SESSION["pending_family_invite"]);
    echo json_encode(["success" => true]);
    exit;
}

if ($output === "repeat") {
    unset($_SESSION["pending_family_invite"]);
    echo json_encode(["success" => false, "error" => "This member was already added."]);
    exit;
}

echo json_encode(["success" => false, "error" => "Could not add member. Try again or contact support."]);
