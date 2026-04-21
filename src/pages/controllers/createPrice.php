<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/config.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php");
$stripe = new \Stripe\StripeClient(pd_stripe_secret_key());
$price = $stripe->prices->update(
    "price_1Ri362CqUk2FODuHdQsPFsij",
    ['unit_amount' => 18900]
);