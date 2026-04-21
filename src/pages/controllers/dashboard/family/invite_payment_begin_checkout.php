<?php
if (!isset($_SESSION["planable"]) || $_SESSION["planable"] == 0) {
    http_response_code(403);
    header("Content-Type: application/json");
    die(json_encode(["error" => "Planable error!"]));
}

header("Content-Type: application/json");

// New checkout attempt must not reuse an old "payment verified" session flag.
unset($_SESSION["invite_pay_verified"], $_SESSION["invite_pay_verified_at"], $_SESSION["verify_code"]);

echo json_encode(["success" => true]);
