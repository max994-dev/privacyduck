<?php
if (!isset($_SESSION["planable"]) || $_SESSION["planable"] == 0) {
    http_response_code(500);
    die(json_encode(["error" => "Planable error!"]));
}

header('Content-Type: application/json');

if (isset($_SESSION["invite_requirePayment"])) {
    unset($_SESSION["invite_requirePayment"]);
}

if (isset($_SESSION["invite_count"])) {
    unset($_SESSION["invite_count"]);
}
if (isset($_SESSION["invite_price"])) {
    unset($_SESSION["invite_price"]);
}

$email = trim((string) ($_POST["email"] ?? ""));

// One-time $99 payment is required for each added member.
$requirePayment = 1;

$conn = getDBConnection();

// If user changed invitee email, payment verification no longer applies.
if (isset($_SESSION["addon_invitee_email"]) && $_SESSION["addon_invitee_email"] !== $email) {
    unset($_SESSION["invite_pay_verified"], $_SESSION["invite_pay_verified_at"]);
}
$_SESSION["addon_invitee_email"] = $email;

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $payload = ["status" => 0, "requirePayment" => $requirePayment];
} else {
    $data = $result->fetch_assoc();

    $stmt = $conn->prepare("SELECT * FROM family WHERE core_id = ? And invite_id = ?");
    $stmt->bind_param("ii", $_SESSION["user_id"], $data["id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $_SESSION["invite_requirePayment"] = 0;
        echo json_encode(["status" => -1, "requirePayment" => 0]);
        exit;
    }

    $hasActivePlan = !empty($data["plan_id"]) && !empty($data["plan_end"]);
    $isPlanValid = $hasActivePlan && (new DateTime() < new DateTime($data["plan_end"]));
    if ($isPlanValid === true) {
        $payload = ["status" => 1, "requirePayment" => $requirePayment];
    } else {
        $payload = ["status" => 2, "requirePayment" => $requirePayment];
    }
}

// After fresh email verification only (short window), allow submit without paying again.
$inviteVerifyTtlSeconds = 1800; // 30 minutes
if (!empty($_SESSION["invite_pay_verified"])) {
    $verifiedAt = (int) ($_SESSION["invite_pay_verified_at"] ?? 0);
    if ($verifiedAt > 0 && (time() - $verifiedAt) <= $inviteVerifyTtlSeconds) {
        $payload["requirePayment"] = 0;
    } else {
        unset($_SESSION["invite_pay_verified"], $_SESSION["invite_pay_verified_at"]);
    }
}

$_SESSION["invite_requirePayment"] = $payload["requirePayment"];
echo json_encode($payload);