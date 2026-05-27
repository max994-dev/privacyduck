<?php

/**
 * PrivacyDuck Stripe (main account). Set STRIPE_MODE in config.php ("test" | "live").
 *
 * When STRIPE_MODE is "test": use test Payment Links/prices in Stripe; DB `stripe_price_id` is often live-only.
 * Point Stripe test webhooks at this site and set STRIPE_WEBHOOK_SECRET_TEST to the test signing secret.
 */
if (!defined('STRIPE_MODE')) {
    define('STRIPE_MODE', 'test');
}

/** Publishable key (live) - pk_live_… */
if (!defined('STRIPE_PUBLISHABLE_KEY_LIVE')) {
    define('STRIPE_PUBLISHABLE_KEY_LIVE', 'pk_live_51NnPaLCqUk2FODuHsjoSwco3FniR1031fy4tXQT8ebrY7IkaLy0wChdhmBdSoB3MeUt25FsEQdXOYAwWBJk4ZfUD00UMCUKYaV');
}
/** Secret key (live) - sk_live_… */
if (!defined('STRIPE_SECRET_KEY_LIVE')) {
    define('STRIPE_SECRET_KEY_LIVE', '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');
}
/** Webhook signing secret (live Dashboard → Webhooks → this endpoint) */
if (!defined('STRIPE_WEBHOOK_SECRET_LIVE')) {
    define('STRIPE_WEBHOOK_SECRET_LIVE', 'whsec_qp5kgRq4Lvj4DV31dwBo3H5imiWIQvvs');
}

/** Publishable key (test) - pk_test_… */
if (!defined('STRIPE_PUBLISHABLE_KEY_TEST')) {
    define('STRIPE_PUBLISHABLE_KEY_TEST', 'pk_test_51NnPaLCqUk2FODuHFKKs4aYRhos4gvUVpcLkIPUyUYQtKqiLDtjEwbMcbmr25NolQ4dosbAcoC5EdGRUYz4okLBI00V8d4qRVj');
}
/** Secret key (test) - sk_test_… */
if (!defined('STRIPE_SECRET_KEY_TEST')) {
    define('STRIPE_SECRET_KEY_TEST', 'sk_test_51NnPaLCqUk2FODuHtNQscSDsITgLluZBeKbyAGnKsnBJeOtDkH58gLEMear3nxKBxieiPYOMWG6UjwdIv8Cd0byp00tLcqA3u6');
}
/**
 * Webhook signing secret (test mode). Replace with the secret from Stripe test-mode webhook endpoint.
 * (Previously commented next to live secret in stripeWebHook.php - verify in Dashboard if signatures fail.)
 */
if (!defined('STRIPE_WEBHOOK_SECRET_TEST')) {
    define('STRIPE_WEBHOOK_SECRET_TEST', 'whsec_cHYXKI7XQnNMaAiz0q95Appsvvh4rEKP');
}

/*
 * Older repo comments also referenced a second Stripe account (not used by STRIPE_MODE):
 *   sk_test_51RfUwfH7IMPWhKBfU8k7UjIxTEIGCTHj6TRMDxmAgXkhmJkh5Fb8NlCvOLIWjcIt7iq4kVl7aSf2PfZuQZrysVPN00qApWto6m
 *   pk_test_51RfUwfH7IMPWhKBfEedKSCoA0qbhIGXscCjrG56fOtYVXKb4vfztZOMTG2ECZAzdYyGS7dhPtl0Ji6cE5rZ7Ldlx00pQ4oNxZG
 * Pair those together only if you intentionally use that other Stripe account.
 */

/**
 * Legacy `privacypros.php` export uses a different Stripe account - not switched by STRIPE_MODE.
 */
if (!defined('STRIPE_PRIVACYPROS_SECRET_KEY')) {
    define('STRIPE_PRIVACYPROS_SECRET_KEY', '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');
}

function pd_stripe_is_test(): bool
{
    return strtolower((string) STRIPE_MODE) === 'test';
}

function pd_stripe_publishable_key(): string
{
    return pd_stripe_is_test() ? STRIPE_PUBLISHABLE_KEY_TEST : STRIPE_PUBLISHABLE_KEY_LIVE;
}

function pd_stripe_secret_key(): string
{
    return pd_stripe_is_test() ? STRIPE_SECRET_KEY_TEST : STRIPE_SECRET_KEY_LIVE;
}

function pd_stripe_webhook_signing_secret(): string
{
    return pd_stripe_is_test() ? STRIPE_WEBHOOK_SECRET_TEST : STRIPE_WEBHOOK_SECRET_LIVE;
}

function pd_stripe_bootstrap(): void
{
    \Stripe\Stripe::setApiKey(pd_stripe_secret_key());
    \Stripe\Stripe::setApiVersion('2023-08-16');
}

/** PRO annual ($299.99) hosted checkout URL; optional prefilled_email query param. */
function pd_pro_plan_stripe_payment_link(string $prefilledEmail = ''): string
{
    $base = defined('PRO_PLAN_STRIPE_PAYMENT_LINK')
        ? (string) PRO_PLAN_STRIPE_PAYMENT_LINK
        : 'https://buy.stripe.com/14AfZg7jJa0CbKcf18dwc0Y';
    $email = trim($prefilledEmail);
    if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $sep = str_contains($base, '?') ? '&' : '?';
        return $base . $sep . 'prefilled_email=' . rawurlencode($email);
    }
    return $base;
}

/** Apply config payment link to standard 1-year PRO plan rows (value 29999 cents). */
function pd_plan_apply_pro_stripe_link(array &$plan): void
{
    $link = pd_pro_plan_stripe_payment_link();
    if ($link === '') {
        return;
    }
    $value = (int) ($plan['value'] ?? 0);
    $isProAnnual = $value === 29999
        || ((($plan['year'] ?? '') === 'one') && (($plan['person'] ?? '') === 'single'));
    if ($isProAnnual) {
        $plan['stripe_payment_link'] = $link;
        $plan['stripe_payment_link_etc'] = $link;
    }
}
