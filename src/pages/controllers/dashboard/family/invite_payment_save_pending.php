<?php
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
