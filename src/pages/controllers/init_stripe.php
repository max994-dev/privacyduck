<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/config.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/database.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php");
pd_stripe_bootstrap();

$conn = getDBConnection();

$productName = "Privacyduck Removal Subscription";

$stmt = $conn->prepare("SELECT * FROM products WHERE name = ?");
$stmt->bind_param("s", $productName);
$stmt->execute();
$result = $stmt->get_result();

$coupon = \Stripe\Coupon::create([
    'percent_off' => 15,
    'duration' => 'once', // or 'repeating', 'forever'
]);

$promotionCode = \Stripe\PromotionCode::create([
    'coupon' => $coupon->id,
    'code' => 'HAPPY', // Set your custom code
    // Optional: set usage limits, expiration, etc.
]);

exit();
$product_item = $result->fetch_assoc();
$product = \Stripe\Product::retrieve($product_item['stripe_product_id']);
$created_at = date("Y-m-d H:i:s");
$productId = $product_item['id'];
$stmt = $conn->prepare("SELECT * FROM plans where id = 1");
$stmt->execute();
$plan = $stmt->get_result()->fetch_assoc();



$interval = 'year';
$interval_count = ($plan['year'] == "two") ? 2 : 1;

$price = \Stripe\Price::create([
    'product' => $product->id,
    'unit_amount' => $plan['value'],
    'currency' => 'usd',
    'recurring' => [
        'interval' => $interval,
        'interval_count' => $interval_count
    ],
    'metadata' => [
        'country' => $plan["country"],
        'plan_type' => $plan['person'],
        'billing_period' => ($plan['year'] == "two") ? 2 : 1
    ]
]);

$created_at = date("Y-m-d H:i:s");
$stmt = $conn->prepare("INSERT INTO prices (
        product_id, stripe_price_id, country_code, plan_type, billing_period, amount, created_at, plan_id
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

$amount = $plan['value'] / 100;
$billing_period = $plan['year']=="two"?2:1;
$stmt->bind_param("sssssdsd", 
        $productId,
        $price->id,
        $plan["country"],
        $plan['person'],
        $billing_period,
        $amount,
        $created_at,
        $plan['id']
);
$stmt->execute();
$priceId = $conn->insert_id;
$paymentLinkEtc = \Stripe\PaymentLink::create([
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
    'allow_promotion_codes' => true,
]);

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
    'allow_promotion_codes' => true,
]);

$stmt = $conn->prepare("Update prices set stripe_payment_link_etc = ?, stripe_payment_link = ? where id = ?");
$stmt->bind_param("sss", $paymentLinkEtc->url, $paymentLink->url, $priceId);
$stmt->execute();

$stmt = $conn->prepare("Update plans set stripe_payment_link_etc = ?, stripe_payment_link = ? where id = ?");
$stmt->bind_param("sss", $paymentLinkEtc->url, $paymentLink->url, $plan['id']);
$stmt->execute();

// TRUNCATE TABLE prices;
// TRUNCATE TABLE products;
// TRUNCATE TABLE subscriptions;
// TRUNCATE TABLE customers;