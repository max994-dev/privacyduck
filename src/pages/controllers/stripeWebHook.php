<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
if (!function_exists('pd_stripe_bootstrap')) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/config.php';
}
pd_stripe_bootstrap();

if (!function_exists('pd_stripe_webhook_log')) {
    function pd_stripe_webhook_log(string $message, array $context = []): void
    {
        $dir = $_SERVER['DOCUMENT_ROOT'] . '/storage/logs';
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }
        $line = '[' . date('Y-m-d H:i:s') . '] ' . $message;
        if (!empty($context)) {
            $line .= ' ' . json_encode($context, JSON_UNESCAPED_SLASHES);
        }
        $line .= PHP_EOL;
        @file_put_contents($dir . '/stripe_webhook.log', $line, FILE_APPEND);
    }
}

$endpoint_secret = pd_stripe_webhook_signing_secret();

// Get raw payload and signature
$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

// Verify the webhook signature
try {
    $event = \Stripe\Webhook::constructEvent(
        $payload,
        $sig_header,
        $endpoint_secret
    );
} catch (\UnexpectedValueException $e) {
    pd_stripe_webhook_log('invalid_payload', ['error' => $e->getMessage()]);
    http_response_code(400); // Invalid payload
    exit();
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    pd_stripe_webhook_log('invalid_signature', ['error' => $e->getMessage()]);
    http_response_code(400); // Invalid signature
    exit();
}
$conn = getDBConnection();
pd_stripe_webhook_log('received', [
    'event_id' => $event->id ?? null,
    'type' => $event->type ?? null,
    'livemode' => $event->livemode ?? null,
]);
try {
// Handle the event
if ($event->type == 'invoice.payment_succeeded') {
    $invoice = $event->data->object;
    // file_put_contents("log.txt", json_encode($invoice));

    // Use payload fields directly (no extra Stripe API fetch; avoids timeout failures).
    $subscription = $invoice->subscription ?? '';
    $stripeCustomerId = $invoice->customer ?? '';
    $email = trim((string) ($invoice->customer_email ?? ''));
    $stripePriceId = '';
    if (!empty($invoice->lines->data) && isset($invoice->lines->data[0]->price->id)) {
        $stripePriceId = (string) $invoice->lines->data[0]->price->id;
    }
    if ($email === '' && $stripeCustomerId !== '') {
        $lookupEmail = $conn->prepare('SELECT email FROM customers WHERE stripe_customer_id = ? LIMIT 1');
        $lookupEmail->bind_param('s', $stripeCustomerId);
        $lookupEmail->execute();
        $er = $lookupEmail->get_result()->fetch_assoc();
        $lookupEmail->close();
        if ($er && !empty($er['email'])) {
            $email = (string) $er['email'];
        }
    }
        pd_stripe_webhook_log('invoice.payment_succeeded', [
            'subscription' => $subscription,
            'email' => $email,
            'stripe_customer_id' => $stripeCustomerId,
            'stripe_price_id' => $stripePriceId,
        ]);
    if ($subscription === '') {
        pd_stripe_webhook_log('missing_subscription_on_invoice', []);
        http_response_code(200);
        exit();
    }
    $stmt = $conn->prepare("SELECT * FROM subscriptions WHERE stripe_subscription_id = ?");
    $stmt->bind_param("s", $subscription);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->num_rows > 0 ? $result->fetch_assoc() : null;

    // Determine local price row (existing subscription row first, otherwise resolve via Stripe price id from invoice payload).
    $localPrice = null;
    if ($row && !empty($row['price_id'])) {
        $pid = (int) $row['price_id'];
        $pStmt = $conn->prepare('SELECT * FROM prices WHERE id = ?');
        $pStmt->bind_param('i', $pid);
        $pStmt->execute();
        $localPrice = $pStmt->get_result()->fetch_assoc();
        $pStmt->close();
    } elseif ($stripePriceId !== '') {
        $pStmt = $conn->prepare('SELECT * FROM prices WHERE stripe_price_id = ?');
        $pStmt->bind_param('s', $stripePriceId);
        $pStmt->execute();
        $localPrice = $pStmt->get_result()->fetch_assoc();
        $pStmt->close();
    }
    if (!$localPrice) {
        pd_stripe_webhook_log('price_not_found_for_invoice', [
            'subscription' => $subscription,
            'stripe_price_id' => $stripePriceId,
        ]);
        http_response_code(200);
        exit();
    }

    // Ensure customer row exists/updates (for later subscription linkage).
    $customerId = null;
    if ($stripeCustomerId !== '') {
        $cStmt = $conn->prepare('SELECT id, email FROM customers WHERE stripe_customer_id = ? LIMIT 1');
        $cStmt->bind_param('s', $stripeCustomerId);
        $cStmt->execute();
        $cRow = $cStmt->get_result()->fetch_assoc();
        $cStmt->close();
        if ($cRow) {
            $customerId = (int) $cRow['id'];
            if ($email !== '' && strcasecmp((string)($cRow['email'] ?? ''), $email) !== 0) {
                $uCust = $conn->prepare('UPDATE customers SET email = ? WHERE id = ?');
                $uCust->bind_param('si', $email, $customerId);
                $uCust->execute();
                $uCust->close();
            }
        } elseif ($email !== '') {
            $insCust = $conn->prepare('INSERT INTO customers (email, stripe_customer_id, name, created_at) VALUES (?, ?, ?, ?)');
            $name = '';
            $createdAt = date('Y-m-d H:i:s');
            $insCust->bind_param('ssss', $email, $stripeCustomerId, $name, $createdAt);
            $insCust->execute();
            $customerId = (int) $conn->insert_id;
            $insCust->close();
        }
    }

    // Upsert local subscription row from invoice payload.
    $period_start = date("Y-m-d H:i:s");
    $period_end = date("Y-m-d H:i:s", strtotime("+" . (($localPrice['year'] ?? 'one') === 'two' ? 2 : 1) . " year", strtotime($period_start)));
    $localPriceId = (int) $localPrice['id'];
    if ($row) {
        $uSub = $conn->prepare('UPDATE subscriptions SET status = ?, price_id = ?, customer_id = COALESCE(?, customer_id), current_period_start = ?, current_period_end = ? WHERE stripe_subscription_id = ?');
        $status = 'active';
        $uSub->bind_param('siisss', $status, $localPriceId, $customerId, $period_start, $period_end, $subscription);
        $uSub->execute();
        $uSub->close();
    } elseif ($customerId !== null) {
        $insSub = $conn->prepare('INSERT INTO subscriptions (stripe_subscription_id, price_id, customer_id, status, current_period_start, current_period_end, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $status = 'active';
        $createdAt = date('Y-m-d H:i:s');
        $insSub->bind_param('siissss', $subscription, $localPriceId, $customerId, $status, $period_start, $period_end, $createdAt);
        $insSub->execute();
        $insSub->close();
    }

    // Update users plan if we can resolve user email.
    $plan_id = (int) $localPrice["plan_id"];
    if ($email !== '') {
        $uStmt = $conn->prepare("SELECT * FROM users WHERE LOWER(TRIM(email)) = LOWER(TRIM(?)) LIMIT 1");
        $uStmt->bind_param("s", $email);
        $uStmt->execute();
        $user = $uStmt->get_result()->fetch_assoc();
        $uStmt->close();
        if ($user && !empty($user["id"])) {
            $stmt = $conn->prepare("SELECT * FROM plans WHERE id = ?");
            $stmt->bind_param("i", $plan_id);
            $stmt->execute();
            $plan = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            if ($plan) {
                $uPlanStart = date("Y-m-d H:i:s");
                $uPlanEnd = date("Y-m-d H:i:s", strtotime("+" . ($plan['year'] == "two" ? 2 : 1) . " year", strtotime($uPlanStart)));
                $upUser = $conn->prepare("UPDATE users SET plan_id = ?, plan_start = ?, plan_end = ? WHERE id = ?");
                $upUser->bind_param("issi", $plan_id, $uPlanStart, $uPlanEnd, $user["id"]);
                $upUser->execute();
                $upUser->close();
            }
        } else {
            pd_stripe_webhook_log('user_not_found', ['email' => $email, 'subscription' => $subscription]);
        }
    } else {
        pd_stripe_webhook_log('invoice_has_no_customer_email', ['subscription' => $subscription, 'stripe_customer_id' => $stripeCustomerId]);
    }
} else if ($event->type == 'customer.subscription.created') {
    $invoice = $event->data->object;
    // file_put_contents("log-c.txt", json_encode($invoice));
    $email = '';
    $stripeCustomerId = (string) ($invoice->customer ?? '');
    if ($stripeCustomerId !== '') {
        $lookupEmail = $conn->prepare('SELECT email FROM customers WHERE stripe_customer_id = ? LIMIT 1');
        $lookupEmail->bind_param('s', $stripeCustomerId);
        $lookupEmail->execute();
        $er = $lookupEmail->get_result()->fetch_assoc();
        $lookupEmail->close();
        if ($er && !empty($er['email'])) {
            $email = (string) $er['email'];
        }
    }
    $price_id = $invoice->items->data[0]->price->id;
    $subscription = $invoice->items->data[0]->subscription;
    pd_stripe_webhook_log('customer.subscription.created', [
        'subscription' => $subscription,
        'email' => $email,
        'stripe_price_id' => $price_id,
    ]);
    $stmt = $conn->prepare("SELECT * FROM subscriptions WHERE stripe_subscription_id = ?");
    $stmt->bind_param("s", $subscription);
    $stmt->execute();
    $result = $stmt->get_result();
    // file_put_contents("log-c-debug.txt", 1);

    if ($result->num_rows == 0) {
        // file_put_contents("log-c-debug.txt", 2);
        $stmt = $conn->prepare("SELECT * FROM prices WHERE stripe_price_id = ?");
        $stmt->bind_param("s", $price_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $price = $result->fetch_assoc();
	//edited 2026;
	if (!$price) {
            pd_stripe_webhook_log('price_not_found_for_stripe_price_id', ['stripe_price_id' => $price_id, 'subscription' => $subscription]);
	    throw new Exception("Price not found for stripe_price_id={$price_id}");
	}

        // file_put_contents("log-c-debug.txt", 3);
        $stmt = $conn->prepare("SELECT * FROM customers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        // file_put_contents("log-c-debug.txt", 4);
        if ($result->num_rows == 0) {
            // file_put_contents("log-c-debug.txt", 5);
            if ($email === '') {
                pd_stripe_webhook_log('cannot_insert_customer_missing_email', ['stripe_customer_id' => $stripeCustomerId]);
                http_response_code(200);
                exit();
            }
            $stmt = $conn->prepare("INSERT INTO customers (email, stripe_customer_id, name, created_at) VALUES (?, ?, ?, ?)");
            $createdAt = date("Y-m-d H:i:s");
            $name = '';
	    $stmt->bind_param("ssss", $email, $stripeCustomerId, $name, $createdAt);
            $stmt->execute();
            $customerId = $conn->insert_id;
        } else $customerId = $result->fetch_assoc()["id"];
        // file_put_contents("log-c-debug.txt", 6);
        $now = date("Y-m-d H:i:s");
        $years = (($price['year'] ?? 'one') === 'two') ? 2 : 1;
	$current_period_end = date("Y-m-d H:i:s", strtotime("+{$years} year", strtotime($now)));
        // file_put_contents("log-c-debug.txt", 7);
        $current_period_start = $now;
        $stmt = $conn->prepare("INSERT INTO subscriptions (stripe_subscription_id, price_id, customer_id, status, current_period_start, current_period_end, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
        // file_put_contents("log-c-debug.txt", 8);
        $status = "active";
        $stmt->bind_param("siissss", $subscription, $price["id"], $customerId, $status, $current_period_start, $current_period_end, $now);
        $stmt->execute();

        $plan_id = $price["plan_id"];

        $stmt = $conn->prepare("SELECT * FROM plans WHERE id = ?");
        $stmt->bind_param("i", $plan_id);
        $stmt->execute();
        $plan = $stmt->get_result()->fetch_assoc();

        $stmt = $conn->prepare("SELECT * FROM users WHERE LOWER(TRIM(email)) = LOWER(TRIM(?))");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if (!$user || empty($user["id"])) {
            error_log('stripeWebHook customer.subscription.created: user not found email=' . $email);
            pd_stripe_webhook_log('user_not_found', ['email' => $email, 'subscription' => $subscription]);
            http_response_code(200);
            exit();
        }

        $stmt = $conn->prepare("UPDATE users SET plan_id = ?, plan_start = ?, plan_end = ? WHERE id = ?");
        $period_start = date("Y-m-d H:i:s");
        $period_end = date("Y-m-d H:i:s", strtotime("+" . ($plan['year'] == "two" ? 2 : 1) . " year", strtotime($period_start)));
        $stmt->bind_param("issi", $plan_id, $period_start, $period_end, $user["id"]);
        $stmt->execute();
    }
    // file_put_contents("log-c-e.txt", json_encode($invoice));
} else if ($event->type == 'customer.subscription.updated') {
    $invoice = $event->data->object;
    // file_put_contents("log-u.txt", json_encode($invoice));
    $email = '';
    $stripeCustomerId = (string) ($invoice->customer ?? '');
    if ($stripeCustomerId !== '') {
        $lookupEmail = $conn->prepare('SELECT email FROM customers WHERE stripe_customer_id = ? LIMIT 1');
        $lookupEmail->bind_param('s', $stripeCustomerId);
        $lookupEmail->execute();
        $er = $lookupEmail->get_result()->fetch_assoc();
        $lookupEmail->close();
        if ($er && !empty($er['email'])) {
            $email = (string) $er['email'];
        }
    }
    $price_id = $invoice->items->data[0]->price->id;
    $subscription = $invoice->items->data[0]->subscription;
    pd_stripe_webhook_log('customer.subscription.updated', [
        'subscription' => $subscription,
        'email' => $email,
        'stripe_price_id' => $price_id,
    ]);
    $stmt = $conn->prepare("SELECT * FROM subscriptions WHERE stripe_subscription_id = ?");
    $stmt->bind_param("s", $subscription);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $stmt = $conn->prepare("SELECT * FROM prices WHERE stripe_price_id = ?");
        $stmt->bind_param("s", $price_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $price = $result->fetch_assoc();
	if (!$price) {
	    throw new Exception("Price not found for stripe_price_id={$price_id}");
	}
	//edited 2026;
        $stmt = $conn->prepare("UPDATE subscriptions SET price_id = ? WHERE stripe_subscription_id = ?");
        $stmt->bind_param("is", $price["id"], $subscription);
        $stmt->execute();
    }
    // file_put_contents("log-u-e.txt", json_encode($invoice));
} else if ($event->type == 'customer.subscription.deleted') {
    $invoice = $event->data->object;
    // file_put_contents("log-d.txt", json_encode($invoice));
    $email = '';
    $stripeCustomerId = (string) ($invoice->customer ?? '');
    if ($stripeCustomerId !== '') {
        $lookupEmail = $conn->prepare('SELECT email FROM customers WHERE stripe_customer_id = ? LIMIT 1');
        $lookupEmail->bind_param('s', $stripeCustomerId);
        $lookupEmail->execute();
        $er = $lookupEmail->get_result()->fetch_assoc();
        $lookupEmail->close();
        if ($er && !empty($er['email'])) {
            $email = (string) $er['email'];
        }
    }
    $price_id = $invoice->items->data[0]->price->id;
    $subscription = $invoice->items->data[0]->subscription;
    pd_stripe_webhook_log('customer.subscription.deleted', [
        'subscription' => $subscription,
        'email' => $email,
        'stripe_price_id' => $price_id,
    ]);
    $stmt = $conn->prepare("SELECT * FROM subscriptions WHERE stripe_subscription_id = ?");
    $stmt->bind_param("s", $subscription);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $stmt = $conn->prepare("DELETE FROM subscriptions WHERE stripe_subscription_id = ?");
        $stmt->bind_param("s", $subscription);
        $stmt->execute();
    }
}

pd_stripe_webhook_log('handled_ok', ['type' => $event->type ?? null]);
http_response_code(200); // Stripe needs 200 response to consider webhook successful
} catch (Throwable $e) {
    pd_stripe_webhook_log('unhandled_exception', [
        'type' => $event->type ?? null,
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
    ]);
    error_log('stripeWebHook fatal: ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
    http_response_code(200);
}
