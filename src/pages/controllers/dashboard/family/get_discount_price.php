<?php
if (!isset($_SESSION["planable"]) || $_SESSION["planable"] == 0) {
    http_response_code(500);
    die(json_encode(["error" => "Planable error!"]));
}

header('Content-Type: application/json');

$conn = getDBConnection();
$link = trim((string) FAMILY_MEMBER_ADDON_STRIPE_LINK);

if ($link === "") {
    // DB fallback: any configured one-time $99 link.
    $stmt = $conn->prepare("SELECT * FROM prices WHERE amount = 99 AND (stripe_payment_link != '' OR stripe_payment_link_etc != '') LIMIT 1");
    $stmt->execute();
    $price = $stmt->get_result()->fetch_assoc();
    if ($price) {
        $link = trim((string) ($price["stripe_payment_link"] ?? ""));
        if ($link === "") {
            $link = trim((string) ($price["stripe_payment_link_etc"] ?? ""));
        }
    }
}

if ($link === "") {
    echo json_encode([
        "error" => "Member add-on checkout link is missing. Set FAMILY_MEMBER_ADDON_STRIPE_LINK in config.php or add a $99 Stripe link in prices.",
    ]);
    exit;
}

echo json_encode([
    "success" => "pay",
    "id" => 0,
    "data" => [
        "value" => 9900,
        "price" => "Additional family member",
        "stripe_payment_link" => $link,
        "stripe_payment_link_etc" => $link,
    ],
    "value" => 9900,
    "price" => "Additional family member",
]);
