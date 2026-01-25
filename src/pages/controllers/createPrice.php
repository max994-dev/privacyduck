<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php");
// \Stripe\Stripe::setApiKey('sk_test_51NnPaLCqUk2FODuHtNQscSDsITgLluZBeKbyAGnKsnBJeOtDkH58gLEMear3nxKBxieiPYOMWG6UjwdIv8Cd0byp00tLcqA3u6');
$stripe = new \Stripe\StripeClient('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');
$price = $stripe->prices->update(
    "price_1Ri362CqUk2FODuHdQsPFsij",
    ['unit_amount' => 18900]
);