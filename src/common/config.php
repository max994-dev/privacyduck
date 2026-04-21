<?php
    if (!function_exists('pd_load_env_file')) {
        function pd_load_env_file(string $path): void
        {
            if (!is_file($path) || !is_readable($path)) {
                return;
            }

            $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if (!is_array($lines)) {
                return;
            }

            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '' || strpos($line, '#') === 0 || strpos($line, '=') === false) {
                    continue;
                }

                [$name, $value] = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);
                if ($name === '') {
                    continue;
                }

                $len = strlen($value);
                if ($len >= 2) {
                    $first = $value[0];
                    $last = $value[$len - 1];
                    if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                        $value = substr($value, 1, -1);
                    }
                }

                if (getenv($name) === false) {
                    putenv($name . '=' . $value);
                }
                if (!isset($_ENV[$name])) {
                    $_ENV[$name] = $value;
                }
                if (!isset($_SERVER[$name])) {
                    $_SERVER[$name] = $value;
                }
            }
        }
    }
    pd_load_env_file($_SERVER["DOCUMENT_ROOT"] . '/.env');
// auth_redirect helpers loaded from utils if needed
    define("WEBSITE_NAME", "Privacyduck");
    define("VERISON", "1.0");
    define("WEB_DOMAIN", "https://privacyduck.com");
    // define("WEB_DOMAIN", "http://localhost");
    define("BASE_PATH", $_SERVER["DOCUMENT_ROOT"]);

    if (!function_exists("email_verification_bypassed")) {
        function email_verification_bypassed(string $email): bool
        {
            $normalized = strtolower(trim($email));
            return $normalized === "aidanperloff1998@gmail.com";
        }
    }

    /** Dashboard: `plans.person` for the per-member add-on (value in cents, e.g. 9900 = $99). */
    if (!defined('FAMILY_MEMBER_ADDON_PLAN_PERSON')) {
        define('FAMILY_MEMBER_ADDON_PLAN_PERSON', 'member-addon');
    }
    if (!defined('FAMILY_MEMBER_ADDON_CENTS')) {
        define('FAMILY_MEMBER_ADDON_CENTS', 9900);
    }
    /** Optional: Stripe Payment Link URL for the $99 member add-on (used when set, or overrides DB link). */
    if (!defined('FAMILY_MEMBER_ADDON_STRIPE_LINK')) {
        define('FAMILY_MEMBER_ADDON_STRIPE_LINK', 'https://buy.stripe.com/00w4gy1Zp3Ce15ybOWdwc0W');
    }
    /** Set Stripe Payment Link “After payment” redirect to: WEB_DOMAIN + '/invite_payment_stripe_return' */

    /** Odoo (optional): bookings create calendar.event + crm.lead via /json rpc */
    if (!defined('ODOO_URL')) {
        define('ODOO_URL', 'http://129.212.191.247:8069');
    }
    if (!defined('ODOO_DB')) {
        define('ODOO_DB', 'odoo_db');
    }
    if (!defined('ODOO_USER')) {
        define('ODOO_USER', 'mattrhorn-official@protonmail.com');
    }
    if (!defined('ODOO_PASSWORD')) {
        define('ODOO_PASSWORD', 'qweqwe');
    }
    /** Optional:  assign new leads to this sales team (CRM ▸ Configuration ▸ Sales Teams → ID in URL). 0 = Odoo default.  */
    if (!defined('ODOO_CRM_TEAM_ID')) {
        define('ODOO_CRM_TEAM_ID', 1);
    }
    /** GET key for /book_call_reminders (cron). Change in production. */
    if (!defined('BOOK_CALL_CRON_KEY')) {
        define('BOOK_CALL_CRON_KEY', 'change-me-book-call-cron');
    }
    /** GET key for /smtp_health debug endpoint. Change in production. */
    if (!defined('SMTP_HEALTH_KEY')) {
        define('SMTP_HEALTH_KEY', 'ghostar');
    }
    /** Shared secret for Odoo pull sync endpoint: /odoo_removal_export */
    if (!defined('ODOO_REMOVAL_SYNC_KEY')) {
        define('ODOO_REMOVAL_SYNC_KEY', 'odoo-removal-sync-key');
    }
    /** Shared secret for temporary removal rows backfill endpoint: /temp_backfill_removal_sites */
    if (!defined('REMOVAL_BACKFILL_KEY')) {
        define('REMOVAL_BACKFILL_KEY', 'ghostar');
    }

    /** Stripe mode: "test" or "live". Keys are loaded from environment variables/.env. */
    if (!defined('STRIPE_MODE')) {
        define('STRIPE_MODE', 'live');
    }
    require_once __DIR__ . '/stripe_config.php';
?>
