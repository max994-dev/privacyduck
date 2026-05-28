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

$first_name = trim((string) ($_POST["first_name"] ?? ""));
$last_name = trim((string) ($_POST["last_name"] ?? ""));
$email = trim((string) ($_POST["email"] ?? ""));
$contacts = $_POST["contacts"] ?? [];

if ($first_name === "" || $last_name === "" || $email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    die(json_encode(["error" => "Invalid name or email."]));
}

if (!is_array($contacts)) {
    http_response_code(400);
    die(json_encode(["error" => "Invalid contacts."]));
}

$row = $contacts[0] ?? [];
if (
    empty(trim((string) ($row["city"] ?? "")))
    || empty(trim((string) ($row["state"] ?? "")))
    || empty(trim((string) ($row["phone"] ?? "")))
    || empty(trim((string) ($row["zip"] ?? "")))
    || empty(trim((string) ($row["address"] ?? "")))
) {
    http_response_code(400);
    die(json_encode(["error" => "All address fields are required."]));
}

$_SESSION["pending_family_invite"] = [
    "first_name" => $first_name,
    "last_name" => $last_name,
    "email" => $email,
    "contacts" => $contacts,
    "saved_at" => time(),
];

$ret = trim((string) ($_POST["return_after_pay"] ?? ""));
if ($ret === "") {
    $ret = "/dashboard/family?invite_paid=1";
}
$_SESSION["pending_invite_stripe_return"] = $ret;

echo json_encode(["success" => true]);
