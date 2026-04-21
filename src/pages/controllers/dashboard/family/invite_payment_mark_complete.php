<?php
if (!isset($_SESSION["planable"]) || $_SESSION["planable"] == 0) {
    http_response_code(403);
    header("Content-Type: application/json");
    die(json_encode(["error" => "Planable error!"]));
}

header("Content-Type: application/json");

// Trust: user returned from hosted Stripe checkout (closing the checkout window).
// No email code — allow invite_member on this session (TTL enforced in check_status).
unset($_SESSION["verify_code"]);
$_SESSION["invite_pay_verified"] = true;
$_SESSION["invite_pay_verified_at"] = time();

echo json_encode(["success" => true]);
