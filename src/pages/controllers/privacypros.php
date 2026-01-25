<?php
require 'vendor/autoload.php';

\Stripe\Stripe::setApiKey('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'); // your Stripe secret key 

// Fetch all subscriptions (auto-paged)
$subscriptions = \Stripe\Subscription::all([
    'limit' => 100, // max per request
]);

$users = [];

foreach ($subscriptions->autoPagingIterator() as $subscription) {
    $customer = \Stripe\Customer::retrieve($subscription->customer);

    // Handle multiple subscription items (some subs may have more than one plan/price)
    foreach ($subscription->items->data as $item) {
        $price = $item->price;
        $amount = $price->unit_amount / 100; // Stripe stores amounts in cents
        $currency = strtoupper($price->currency);
        $interval = $price->recurring ? $price->recurring->interval : 'one-time';

        $users[] = [
            'subscription_id' => $subscription->id,
            'status'          => $subscription->status,
            'customer_id'     => $subscription->customer,
            'email'           => $customer->email,
            'name'            => $customer->name,
            'amount'          => $amount,
            'currency'        => $currency,
            'interval'        => $interval,
        ];
    }
}

// Display all users
echo "Total Subscriptions: " . count($users) . "<br><br>";

foreach ($users as $user) {
    echo "Subscription ID: " . $user['subscription_id'] . "<br>";
    echo "Status: " . $user['status'] . "<br>";
    echo "Customer ID: " . $user['customer_id'] . "<br>";
    echo "Customer Email: " . $user['email'] . "<br>";
    echo "Customer Name: " . $user['name'] . "<br>";
    echo "Amount: " . $user['amount'] . " " . $user['currency'] . " per " . $user['interval'] . "<hr>";
}
