<?php
// require 'vendor/autoload.php'; // Include the Stripe PHP library

// // Database connection
// $db = new mysqli('DB_HOST', 'DB_USER', 'DB_PASSWORD', 'DB_NAME', DB_PORT);

// // Enable SSL for the database connection
// $db->ssl_set(NULL, NULL, '/path/to/ca-cert.pem', NULL, NULL);

// if ($db->connect_error) {
//     die("Connection failed: " . $db->connect_error);
// }

// // Fetch all emails from the database
// $result = $db->query("SELECT email FROM privacypros.users");
// file_put_contents('paying_users1.json', "123");

// $payingUsers = [];
// $paidSubscriptions = [];

// while ($row = $result->fetch_assoc()) {
//     $email = $row['email'];
//     try {
//         $customers = \Stripe\Customer::all(["email" => $email]);

//         if (count($customers->data) > 0) {
//             foreach ($customers->data as $customer) {
//                 $customerId = $customer->id;
//                 $isPaying = false;

//                 $subscriptions = \Stripe\Subscription::all(["customer" => $customerId]);
//                 $activeSubscriptions = array_filter($subscriptions->data, function ($subscription) {
//                     return $subscription->status === "active" && $subscription->items->data[0]->plan->amount_decimal > 0;
//                 });

//                 if (!empty($activeSubscriptions)) {
//                     $isPaying = true;
//                     usort($activeSubscriptions, function ($a, $b) {
//                         return $b->current_period_end - $a->current_period_end;
//                     });
//                     $paidSubscriptions[] = array("user" => $email, "subscription" => array(
//                         "amount" => $activeSubscriptions[0]->items->data[0]->plan->amount_decimal / 100,
//                         "current_period_start" => $activeSubscriptions[0]->items->data[0]->current_period_start,
//                         "current_period_end" => $activeSubscriptions[0]->items->data[0]->current_period_end
//                     ));
//                 }

//                 if (!$isPaying) {
//                     $paymentIntents = \Stripe\PaymentIntent::all(["customer" => $customerId]);
//                     $oneYearAgo = time() - (365 * 24 * 60 * 60);

//                     foreach ($paymentIntents->data as $paymentIntent) {
//                         if ($paymentIntent->status === 'succeeded' && $paymentIntent->created > $oneYearAgo) {
//                             $isPaying = true;
//                             $paidSubscriptions[] = array("user" => $email, "subscription" => array(
//                                 "amount" => $paymentIntent->amount / 100,
//                                 "current_period_start" => $paymentIntent->current_period_start,
//                                 "current_period_end" => $paymentIntent->current_period_end
//                             ));
//                         }
//                     }
//                 }

//                 if ($isPaying) {
//                     $payingUsers[] = $email;
//                     break;
//                 }
//             }
//         }
//     } catch (\Stripe\Exception\ApiErrorException $e) {
//         echo "Error processing $email: " . $e->getMessage() . "\n";
//     }
// }
// function findSubscription($email, $paidSubscriptions) {
//     foreach ($paidSubscriptions as $subscription) {
//         if ($subscription['user'] == $email) {
//             return $subscription['subscription'];
//         }
//     }
//     return null;
// }
// if (!empty($payingUsers)) {
//     $placeholders = implode(',', array_fill(0, count($payingUsers), '?'));
//     $types = str_repeat('s', count($payingUsers));

//     $stmt = $db->prepare("SELECT id, firstname, lastname, email, location, zip, birthdate, country, phone, variations FROM privacypros.users WHERE email IN ($placeholders)");
//     $stmt->bind_param($types, ...$payingUsers);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     $outputData = [];

//     while ($row = $result->fetch_assoc()) {
//         $outputData[] = array(
//             "user" => $row,
//             "subscription" => findSubscription($row['email'], $paidSubscriptions),
//         );
//     }
//     echo "<pre>";
//     echo json_encode($outputData, JSON_PRETTY_PRINT);
//     echo "</pre>";
//     file_put_contents('paying_users.json', json_encode($outputData, JSON_PRETTY_PRINT));
//     echo "✅ Data written to paying_users.json\n";

//     $stmt->close();
// } else {
//     echo "⚠️ No paying users found.\n";
// }

// $db->close();
$outputData = json_decode(file_get_contents(BASE_PATH . '/src/pages/controllers/paying_users.json'), true);
// define("DB_HOST", "teletype-news-db-do-user-12424917-0.c.db.ondigitalocean.com");
// define("DB_USER", "doadmin");
// define("DB_PASSWORD", "set-in-env");
// define("DB_NAME", "privacyduck");
// define('DB_PORT', 25060); // Important: number, not string
// $db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);

// if ($db->connect_error) {
//     die("Connection failed: " . $db->connect_error);
// }
$i=1;
$conn = getDBConnection();
foreach ($outputData as $userData) {
    if ($i>=2) break;
    // var_dump($userData);
    // $i++;
    // var_dump(1);
    $user = $userData['user'];
    $sub = $userData['subscription'];
    // var_dump(12);
    $stmt = $conn->prepare("INSERT INTO users (
        email, firstname, lastname, plan_id, plan_start, plan_end,
        city, zip, state, age, phone, address, role, contacts, created_at, pros_id
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    // var_dump(2);
    $address = $user['location'];
    preg_match('/^(.*),\s*([A-Z]{2})\s*(\d{5})$/', $address, $matches);
    // var_dump(3);
    $city = $matches[1] ?? '';
    $state = $matches[2] ?? '';
    $zip = $matches[3] ?? $user['zip'];
    // var_dump(4);
    var_dump($user['birthdate']);
    $birthdate = new DateTime($user['birthdate']=="--"?'1990-01-01':$user['birthdate']);
    $today = new DateTime();
    $age = $today->diff($birthdate)->y;
    // var_dump(5);
    $plan_start =  date("Y-m-d H:i:s", $sub['current_period_start']);
    $plan_end = date("Y-m-d H:i:s", $sub['current_period_end']);
    // var_dump(6);
    $email = $user['email'];
    $firstname = $user['firstname'];
    $lastname = $user['lastname'];
    $phone = $user['phone'];
    $pros_id = $user['id'];
    $role = 1;
    $created_at = date("Y-m-d H:i:s");
    // var_dump(7);
    // var_dump($created_at);
    
    $plan_id = 1;
    $contacts_array = [
        [
            "zip"=> $zip,
            "city"=> $city,
            "phone"=> $phone,
            "state"=> $state,
            "address"=> $address
        ]
    ];
    $contacts = json_encode($contacts_array);
    $stmt->bind_param("sssissssssssisss",
        $email, $firstname, $lastname,
        $plan_id, $plan_start, $plan_end,
        $city, $zip, $state,
        $age, $phone, $address,
        $role, $contacts, $created_at, $pros_id
    );
    
    $stmt->execute();
    $stmt->close();
}

