<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php");
// \Stripe\Stripe::setApiKey('sk_test_51RfUwfH7IMPWhKBfU8k7UjIxTEIGCTHj6TRMDxmAgXkhmJkh5Fb8NlCvOLIWjcIt7iq4kVl7aSf2PfZuQZrysVPN00qApWto6m');
\Stripe\Stripe::setApiKey('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');
// \Stripe\Stripe::setApiKey('sk_test_51NnPaLCqUk2FODuHtNQscSDsITgLluZBeKbyAGnKsnBJeOtDkH58gLEMear3nxKBxieiPYOMWG6UjwdIv8Cd0byp00tLcqA3u6');
\Stripe\Stripe::setApiVersion('2023-08-16');

$subscription = \Stripe\Subscription::retrieve("sub_1RiLo3CqUk2FODuHGk9aQlFV");
var_dump($subscription);
// $subscription = \Stripe\Subscription::retrieve("sub_1RiK1dCqUk2FODuHLOpYwChi");
// var_dump($subscription);
exit();

$conn = getDBConnection();

$productName = "Privacyduck Removal Subscription";

$stmt = $conn->prepare("SELECT * FROM products WHERE name = ?");
$stmt->bind_param("s", $productName);
$stmt->execute();
$result = $stmt->get_result();

// $coupon = \Stripe\Coupon::create([
//     'percent_off' => 100,
//     'duration' => 'once', // or 'repeating', 'forever'
// ]);

// $promotionCode = \Stripe\PromotionCode::create([
//     'coupon' => $coupon->id,
//     'code' => 'PRIDUCK1002025', // Set your custom code
//     // Optional: set usage limits, expiration, etc.
// ]);
$product_item = $result->fetch_assoc();
$product = \Stripe\Product::retrieve($product_item['stripe_product_id']);
$created_at = date("Y-m-d H:i:s");
$productId = $product_item['id'];
$stmt = $conn->prepare("SELECT * FROM plans");
$stmt->execute();
$pricingPlans = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
foreach ($pricingPlans as $plan) {
    $interval = 'year';
    $interval_count = ($plan['year'] == "two") ? 2 : 1;

    $stmt = $conn->prepare("SELECT * FROM prices WHERE plan_id = ?");
    $stmt->bind_param("s", $plan['id']);
    $stmt->execute();
    $price_item = $stmt->get_result()->fetch_assoc();
    $price = \Stripe\Price::retrieve($price_item['stripe_price_id']);



    $amount = $plan['value'] / 100;
    $paymentLink = \Stripe\PaymentLink::create([
        'line_items' => [[
            'price' => $price->id,
            'quantity' => 1,
        ]],
        'after_completion' => [
            'type' => 'redirect',
            'redirect' => [
                'url' => 'https://privacyduck.com/dashboard',
            ],
        ],
        'allow_promotion_codes' => $amount == 129 ? false : true,
    ]);
    $created_at = date("Y-m-d H:i:s");
    $stmt = $conn->prepare("UPDATE prices SET stripe_payment_link_etc = ? WHERE id = ?");
    $stmt->bind_param("ss", $paymentLink->url, $price_item['id']);
    $stmt->execute();

    $stmt = $conn->prepare("Update plans set stripe_payment_link_etc = ? where id = ?");
    $stmt->bind_param("ss", $paymentLink->url, $plan['id']);
    $stmt->execute();
}

// TRUNCATE TABLE prices;
// TRUNCATE TABLE products;
// TRUNCATE TABLE subscriptions;
// TRUNCATE TABLE customers;