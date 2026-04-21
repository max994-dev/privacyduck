<?php

/**
 * Pull Stripe customer + active subscription and mirror into customers, subscriptions, users.plan_*.
 * $allowAnyEmail: when true (temporary full-data signup), sync runs for any email; otherwise only bypass list.
 */
function stripe_sync_privacyduck_subscription_for_email(mysqli $conn, string $email, bool $allowAnyEmail = false): void
{
    if (!$allowAnyEmail && (!function_exists('email_verification_bypassed') || !email_verification_bypassed($email))) {
        return;
    }

    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
    if (!function_exists('pd_stripe_bootstrap')) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/config.php';
    }

    try {
        pd_stripe_bootstrap();
    } catch (\Throwable $e) {
        return;
    }

    $emailTrim = trim($email);

    try {
        $customerList = \Stripe\Customer::all([
            'email' => $emailTrim,
            'limit' => 10,
        ]);
        if (empty($customerList->data)) {
            $customerList = \Stripe\Customer::all([
                'email' => strtolower($emailTrim),
                'limit' => 10,
            ]);
        }
    } catch (\Throwable $e) {
        error_log('stripe_signup_sync customers: ' . $e->getMessage());
        return;
    }

    $bestSub = null;
    $stripeCustomer = null;

    foreach ($customerList->data as $c) {
        try {
            $subs = \Stripe\Subscription::all([
                'customer' => $c->id,
                'limit' => 20,
                'status' => 'all',
            ]);
        } catch (\Throwable $e) {
            continue;
        }
        foreach ($subs->data as $sub) {
            if (!in_array($sub->status, ['active', 'trialing'], true)) {
                continue;
            }
            if ($bestSub === null || $sub->current_period_end > $bestSub->current_period_end) {
                $bestSub = $sub;
                $stripeCustomer = $c;
            }
        }
    }

    if ($bestSub === null || $stripeCustomer === null) {
        return;
    }

    if (empty($bestSub->items->data)) {
        return;
    }

    try {
    $priceObj = $bestSub->items->data[0]->price;
    $stripePriceId = is_string($priceObj) ? $priceObj : $priceObj->id;

    $stmt = $conn->prepare('SELECT * FROM prices WHERE stripe_price_id = ?');
    $stmt->bind_param('s', $stripePriceId);
    $stmt->execute();
    $priceRow = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$priceRow) {
        error_log('stripe_signup_sync: no local price for stripe_price_id=' . $stripePriceId);
        return;
    }

    $planId = (int) $priceRow['plan_id'];
    $custEmail = $stripeCustomer->email ?: $emailTrim;
    $custName = $stripeCustomer->name ?? '';
    $stripeCustId = $stripeCustomer->id;
    $now = date('Y-m-d H:i:s');

    $stmt = $conn->prepare('SELECT * FROM customers WHERE email = ?');
    $stmt->bind_param('s', $custEmail);
    $stmt->execute();
    $custRes = $stmt->get_result();

    if ($custRes->num_rows === 0) {
        $stmt->close();
        $stmt = $conn->prepare('INSERT INTO customers (email, stripe_customer_id, name, created_at) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssss', $custEmail, $stripeCustId, $custName, $now);
        $stmt->execute();
        $customerId = (int) $conn->insert_id;
        $stmt->close();
    } else {
        $existing = $custRes->fetch_assoc();
        $customerId = (int) $existing['id'];
        $stmt->close();
        $up = $conn->prepare('UPDATE customers SET stripe_customer_id = ?, name = ? WHERE id = ?');
        $up->bind_param('ssi', $stripeCustId, $custName, $customerId);
        $up->execute();
        $up->close();
    }

    $subId = $bestSub->id;
    $status = $bestSub->status;
    $periodStart = date('Y-m-d H:i:s', $bestSub->current_period_start);
    $periodEnd = date('Y-m-d H:i:s', $bestSub->current_period_end);
    $priceId = (int) $priceRow['id'];

    $stmt = $conn->prepare('SELECT id FROM subscriptions WHERE stripe_subscription_id = ?');
    $stmt->bind_param('s', $subId);
    $stmt->execute();
    $subExists = $stmt->get_result()->num_rows > 0;
    $stmt->close();

    if ($subExists) {
        $stmt = $conn->prepare('UPDATE subscriptions SET price_id = ?, customer_id = ?, status = ?, current_period_start = ?, current_period_end = ? WHERE stripe_subscription_id = ?');
        $stmt->bind_param('iissss', $priceId, $customerId, $status, $periodStart, $periodEnd, $subId);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $conn->prepare('INSERT INTO subscriptions (stripe_subscription_id, price_id, customer_id, status, current_period_start, current_period_end, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('siissss', $subId, $priceId, $customerId, $status, $periodStart, $periodEnd, $now);
        $stmt->execute();
        $stmt->close();
    }

    $stmt = $conn->prepare('UPDATE users SET plan_id = ?, plan_start = ?, plan_end = ? WHERE LOWER(TRIM(email)) = LOWER(?)');
    $stmt->bind_param('isss', $planId, $periodStart, $periodEnd, $emailTrim);
    $stmt->execute();
    $stmt->close();
    } catch (\Throwable $e) {
        error_log('stripe_signup_sync: ' . $e->getMessage());
    }
}
