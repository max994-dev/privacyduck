<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/config.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/database.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/src/pages/controllers/subscribe_init.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["plan_id"])|| !isset($_POST["payment_method_id"])) {
    http_response_code(500);
    die(json_encode(["error" => "Invalid Request!"]));
}

$plan_id = $_POST["plan_id"];
$paymentMethodId = $_POST["payment_method_id"];



try {
    $conn = getDBConnection();
    if (!isset($_SESSION["isAuthenticated"]) || $_SESSION["isAuthenticated"] != true) {
        $_SESSION["email"] = $_POST["email"];
        $_SESSION["fullName"] = $_POST["name"];
    }
    $stmt = $conn->prepare("SELECT * FROM plans WHERE id = ?");
    $stmt->bind_param("i", $plan_id);
    $stmt->execute();
    $plan = $stmt->get_result()->fetch_assoc();
    if (!$plan) {
        http_response_code(500);
        die(json_encode(["error" => "Invalid Request!"]));
    }
    $productId = initializeProductsAndPrices();
    $stmt = $conn->prepare("SELECT p.id, p.stripe_price_id, p.amount
        FROM prices p JOIN products pr ON p.product_id = pr.id
        WHERE pr.id = ? AND p.country_code = ? AND p.plan_type = ? 
        AND p.billing_period = ?
    ");
    $billing_period = $plan['year']=="two"?2:1;
    $stmt->bind_param("isss", $productId, $plan["country"], $plan['person'], $billing_period);
    $stmt->execute();

    $price = $stmt->get_result()->fetch_assoc();

    if (!$price) {
        throw new Exception("Price not found");
    }

    // Check if customer exists
    $stmt = $conn->prepare("SELECT stripe_customer_id, id FROM customers WHERE email = ?");
    $stmt->bind_param("s", $_SESSION["email"]);
    $stmt->execute();
    $customerResult = $stmt->get_result();
    if ($customerResult->num_rows > 0) {
        $customerRow = $customerResult->fetch_assoc();
        $stripeCustomerId = $customerRow["stripe_customer_id"];
        $customerId = $customerRow["id"];

        $customer = \Stripe\Customer::retrieve($stripeCustomerId);

        $paymentMethod = \Stripe\PaymentMethod::retrieve($paymentMethodId);
        $paymentMethod->attach(['customer' => $stripeCustomerId]);

        $customer->invoice_settings = ['default_payment_method' => $paymentMethodId];
        $customer->save();
    } else {
        $customer = \Stripe\Customer::create([
            'email' => $_SESSION["email"],
            'name' => $_SESSION["fullName"],
            'payment_method' => $paymentMethodId,
            'invoice_settings' => ['default_payment_method' => $paymentMethodId]
        ]);

        $stripeCustomerId = $customer->id;
        $created_at = date("Y-m-d H:i:s");

        $stmt = $conn->prepare("INSERT INTO customers (stripe_customer_id, email, name, created_at) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $stripeCustomerId, $_SESSION["email"], $_SESSION["fullName"], $created_at);
        $stmt->execute();

        $customerId = $conn->insert_id; // Get the auto-increment ID of the newly inserted customer
    }
    $stmt = $conn->prepare("SELECT * FROM subscriptions WHERE customer_id = ?");
    $stmt->bind_param("s", $customerId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stripeSubscriptionId = $row['stripe_subscription_id'];

        // Get current Stripe subscription
        $subscription = \Stripe\Subscription::retrieve($stripeSubscriptionId);
        $subscriptionItemId = $subscription->items->data[0]->id;
    
        // Update the subscription with the new price
        \Stripe\Subscription::update($stripeSubscriptionId, [
            'items' => [[
                'id' => $subscriptionItemId,
                'price' => $price['stripe_price_id']
            ]]
        ]);
    
        // Update MySQL subscription with new price
        $stmt = $conn->prepare("UPDATE subscriptions SET price_id = ? WHERE stripe_subscription_id = ?");
        $stmt->bind_param("is", $price['id'], $stripeSubscriptionId);
        $stmt->execute();
    }
    else {
        $subscription = \Stripe\Subscription::create([
            'customer' => $stripeCustomerId,
            'items' => [[
                'price' => $price["stripe_price_id"],
                'quantity' => 1
            ]],
            'payment_behavior' => 'default_incomplete'
        ]);
        $created_at = date("Y-m-d H:i:s");
        $status = $subscription->status;
        $periodStart = date("Y-m-d H:i:s", $subscription->current_period_start);
        $periodEnd = date("Y-m-d H:i:s", $subscription->current_period_end);

        $stmt = $conn->prepare("INSERT INTO subscriptions (
            stripe_subscription_id, customer_id, price_id, status,
            current_period_start, current_period_end, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss",
            $subscription->id,
            $customerId,
            $price['id'],
            $status,
            $periodStart,
            $periodEnd,
            $created_at
        );
        $stmt->execute();
    }

    $invoice = \Stripe\Invoice::retrieve([
        'id' => $subscription->latest_invoice,
        'expand' => ['payment_intent']
    ]);

    $paymentIntent = $invoice->payment_intent;

    $output = [
        'clientSecret' => $paymentIntent->client_secret
    ];
    $_SESSION["plan_id"] = $plan_id;
    echo json_encode($output);

} catch (Exception $e) {
    var_dump($e);
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
