<?php
    include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/config.php");
    include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/database.php");

    include_once($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php");

    pd_stripe_bootstrap();

    function initializeProductsAndPrices(){
        $conn = getDBConnection();

        $productName = "Privacyduck Removal Subscription";

        $stmt = $conn->prepare("SELECT id FROM products WHERE name = ?");
        $stmt->bind_param("s", $productName);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows === 0) {
            try {
                $product = \Stripe\Product::create([
                    'name' => $productName,
                    'type' => 'service'
                ]);
                $created_at = date("Y-m-d H:i:s");
                $stmt = $conn->prepare("INSERT INTO products (name, stripe_product_id, created_at) VALUES (?, ?, ?)");
                $stmt->bind_param("sss",$productName, $product->id, $created_at);
                $stmt->execute();
                $productId = $conn->insert_id;
                $stmt = $conn->prepare("SELECT * FROM plans");
                $stmt->execute();
                $pricingPlans = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

                foreach($pricingPlans as $plan) {
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

                }

                return $productId;
            } catch(Exception $e) {
                return false;
            }
        }

        return $result->fetch_assoc()["id"];
    }
?>