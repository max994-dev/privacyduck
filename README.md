# PrivacyDuck

PrivacyDuck is a PHP web application for personal data removal services, account management, billing, and admin operations.

## Tech Stack

- PHP (monolith routing from `index.php`)
- MySQL (via `mysqli`)
- Composer dependencies:
  - `stripe/stripe-php`
  - `phpmailer/phpmailer`

## Requirements

- PHP 8.0+ (recommended)
- Composer
- MySQL-compatible database
- Web server (Apache/Nginx) or PHP built-in server for local development

## Quick Start

1. Clone the repo.
2. Install dependencies:
   - `composer install`
3. Create your local env file:
   - copy `.env.example` to `.env`
4. Fill `.env` with real credentials (see Environment Variables below).
5. Point your web root to this project directory (or run the PHP dev server).

## Environment Variables

The app loads env vars from `/.env` in `src/common/config.php`.

Required database vars:

- `DB_HOST`
- `DB_USER`
- `DB_PASSWORD`
- `DB_NAME`
- `DB_PORT` (example: `3306` or provider-specific)

Required Stripe vars:

- `STRIPE_MODE` (`live` or `test`)
- `STRIPE_PUBLISHABLE_KEY_LIVE`
- `STRIPE_SECRET_KEY_LIVE`
- `STRIPE_WEBHOOK_SECRET_LIVE`
- `STRIPE_PUBLISHABLE_KEY_TEST`
- `STRIPE_SECRET_KEY_TEST`
- `STRIPE_WEBHOOK_SECRET_TEST`
- `STRIPE_PRIVACYPROS_SECRET_KEY` (legacy flow key; keep set if that flow is used)

## Local Development

### Option A: PHP built-in server

From project root:

- `php -S localhost:8000`

Then open:

- `http://localhost:8000`

### Option B: Apache/Nginx

- Set document root to this project root.
- Ensure requests route through `index.php`.
- Ensure PHP has access to `vendor/` and can read `/.env`.

## Routing

Main routes are defined in `index.php` (for example `/`, `/pricing`, `/login`, `/stripe/webhook`, etc.).

## Stripe Setup

1. Put all Stripe secrets only in `.env` (never in tracked PHP files).
2. Set `STRIPE_MODE=test` for testing, `STRIPE_MODE=live` for production.
3. Configure your webhook endpoint in Stripe:
   - endpoint: `https://<your-domain>/stripe/webhook`
4. Use the matching webhook signing secret:
   - test mode -> `STRIPE_WEBHOOK_SECRET_TEST`
   - live mode -> `STRIPE_WEBHOOK_SECRET_LIVE`

## Security Notes

- `.env` is gitignored and must never be committed.
- Do not hardcode API keys, DB passwords, or webhook secrets in code.
- If a secret was ever committed, rotate it immediately (Stripe keys, DB password, etc.).

## Deployment Notes

- Install dependencies in the deploy target: `composer install --no-dev --optimize-autoloader`
- Provide production `.env` values on the server.
- Make sure PHP can write to required runtime directories (for example `storage/logs` if webhook logging is enabled).

## Common Issues

- **Push blocked by GitHub secret scanning**  
  Remove secrets from tracked files and from commits being pushed (history may need cleanup), then retry.

- **Stripe signature verification fails**  
  Wrong webhook secret for the current mode (`test` vs `live`) or wrong endpoint in Stripe dashboard.

- **Database connection fails**  
  Re-check `DB_*` values and network/firewall access from app host to DB host/port.

## Project Structure (High Level)

- `index.php` - app entrypoint and route map
- `src/common/config.php` - core config + `.env` loader
- `src/common/database.php` - DB connection helpers
- `src/common/stripe_config.php` - Stripe key selection/bootstrap
- `src/pages/` - page views and controllers
- `admin/` - admin-side pages/controllers

## Maintenance Tips

- Keep `.env.example` updated when new env vars are introduced.
- Validate no secrets are present before pushing:
  - `git grep -n "sk_live_\\|sk_test_\\|pk_live_\\|pk_test_\\|whsec_\\|AVNS_"`
