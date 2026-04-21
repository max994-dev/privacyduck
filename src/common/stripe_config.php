<?php
if (!function_exists('pd_env')) {
    function pd_env(string $name, string $default = ''): string
    {
        $value = getenv($name);
        if ($value !== false) {
            return (string) $value;
        }
        if (isset($_ENV[$name])) {
            return (string) $_ENV[$name];
        }
        if (isset($_SERVER[$name])) {
            return (string) $_SERVER[$name];
        }
        return $default;
    }
}

if (!defined('STRIPE_MODE')) {
    define('STRIPE_MODE', pd_env('STRIPE_MODE', 'live'));
}

if (!defined('STRIPE_PUBLISHABLE_KEY_LIVE')) {
    define('STRIPE_PUBLISHABLE_KEY_LIVE', pd_env('STRIPE_PUBLISHABLE_KEY_LIVE'));
}
if (!defined('STRIPE_SECRET_KEY_LIVE')) {
    define('STRIPE_SECRET_KEY_LIVE', pd_env('STRIPE_SECRET_KEY_LIVE'));
}
if (!defined('STRIPE_WEBHOOK_SECRET_LIVE')) {
    define('STRIPE_WEBHOOK_SECRET_LIVE', pd_env('STRIPE_WEBHOOK_SECRET_LIVE'));
}

if (!defined('STRIPE_PUBLISHABLE_KEY_TEST')) {
    define('STRIPE_PUBLISHABLE_KEY_TEST', pd_env('STRIPE_PUBLISHABLE_KEY_TEST'));
}
if (!defined('STRIPE_SECRET_KEY_TEST')) {
    define('STRIPE_SECRET_KEY_TEST', pd_env('STRIPE_SECRET_KEY_TEST'));
}
if (!defined('STRIPE_WEBHOOK_SECRET_TEST')) {
    define('STRIPE_WEBHOOK_SECRET_TEST', pd_env('STRIPE_WEBHOOK_SECRET_TEST'));
}

if (!defined('STRIPE_PRIVACYPROS_SECRET_KEY')) {
    define('STRIPE_PRIVACYPROS_SECRET_KEY', pd_env('STRIPE_PRIVACYPROS_SECRET_KEY'));
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
