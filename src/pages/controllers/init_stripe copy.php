<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/config.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/database.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php");
pd_stripe_bootstrap();

// $subscription = \Stripe\Subscription::retrieve("sub_1RiJx0CqUk2FODuHVfv2UpAj");
// var_dump($subscription);
// $subscription = \Stripe\Subscription::retrieve("sub_1RiK1dCqUk2FODuHLOpYwChi");
// var_dump($subscription);
exit();

$conn = getDBConnection();

$productName = "Privacyduck Removal Subscription";

$stmt = $conn->prepare("SELECT id FROM products WHERE name = ?");
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

if ($result->num_rows === 0) {
    $product = \Stripe\Product::create([
        'name' => $productName,
        'type' => 'service'
    ]);
    $created_at = date("Y-m-d H:i:s");
    $stmt = $conn->prepare("INSERT INTO products (name, stripe_product_id, created_at) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $productName, $product->id, $created_at);
    $stmt->execute();
    $productId = $conn->insert_id;
    $stmt = $conn->prepare("SELECT * FROM plans");
    $stmt->execute();
    $pricingPlans = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    foreach ($pricingPlans as $plan) {
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
            'allow_promotion_codes' => $amount == 129 ? true : false,
        ]);
        $created_at = date("Y-m-d H:i:s");
        $stmt = $conn->prepare("INSERT INTO prices (
                                    product_id, stripe_price_id, stripe_payment_link, country_code, plan_type, billing_period, amount, created_at, plan_id
                                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                            ");

        $billing_period = $plan['year'] == "two" ? 2 : 1;
        $stmt->bind_param(
            "ssssssdsd",
            $productId,
            $price->id,
            $paymentLink->url,
            $plan["country"],
            $plan['person'],
            $billing_period,
            $amount,
            $created_at,
            $plan['id']
        );
        $stmt->execute();

        $stmt = $conn->prepare("Update plans set stripe_payment_link = ? where id = ?");
        $stmt->bind_param("ss", $paymentLink->url, $plan['id']);
        $stmt->execute();
    }
}

// TRUNCATE TABLE prices;
// TRUNCATE TABLE products;
// TRUNCATE TABLE subscriptions;
// TRUNCATE TABLE customers;