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

## GitHub Actions deploy (VPS over SSH)

The workflow in `.github/workflows/deploy.yml` uses [appleboy/scp-action](https://github.com/appleboy/scp-action) and [appleboy/ssh-action](https://github.com/appleboy/ssh-action) with a **private** key stored in GitHub repository secrets (not the public key).

Repository secrets to configure:

- `VPS_HOST` - server hostname or IP
- `VPS_PORT` - SSH port (usually `22`)
- `VPS_USER` - SSH user (e.g. `root` or a deploy user)
- `VPS_SSH_KEY` - **full** private key text (from `id_ed25519` or `id_rsa`), including the `-----BEGIN ... PRIVATE KEY-----` and `-----END ...` lines, with newlines preserved
- `VPS_SSH_PASSPHRASE` - only if the private key is **password-protected**; if the key has no passphrase, create an empty secret or leave unused depending on your setup

**Why you see** `ssh.ParsePrivateKey: ssh: unmarshal error for field KdfName of type openSSHEncryptedPrivateKey` **or** `attempted methods [none]`:

1. **The client never successfully loaded the private key.** The Go SSH library inside `drone-scp` failed to parse `VPS_SSH_KEY` (wrong format, corrupted paste, or an encryption / KDF combination it does not handle well).
2. **Fix (most reliable for CI):** create a **new deploy key with no passphrase**, then paste the private key into `VPS_SSH_KEY` and the matching `.pub` line into the server’s `~/.ssh/authorized_keys` for `VPS_USER`.

   On your machine:

   - `ssh-keygen -t ed25519 -C "github-actions-deploy" -N "" -f github_deploy`  
   - Put the contents of `github_deploy` into the `VPS_SSH_KEY` secret.  
   - Put the contents of `github_deploy.pub` on the VPS in `~/.ssh/authorized_keys` (permissions: `~/.ssh` = `700`, `authorized_keys` = `600`).

3. **If you use a passphrase:** set `VPS_SSH_PASSPHRASE` to the **exact** passphrase. A mismatch looks like a bad key, not a wrong password, in some cases.

4. **Avoid:** pasting the **public** key (`.pub`) into `VPS_SSH_KEY`, or a truncated key, or a key with extra quotes/spaces on the first or last line.

5. **Still failing:** some very new OpenSSH private key formats are picky; using an unencrypted `ed25519` key (step 2) avoids most parser issues.

6. **You did everything “right” but it still failed:** common gotchas:
   - **Multiline secret + Docker:** passing `key:` straight into `appleboy/scp-action` can break PEM parsing (line breaks lost or Windows `CR` left in the file). This repo’s workflow writes the key to `.github_deploy_key` under the repo with `tr -d '\r'`, then uses `key_path` so the action reads a real file.
   - **Re-save the secret** if you edited the key in Notepad: paste the key again in GitHub (or use a `.pem` created on Linux / `ssh-keygen` only) so line endings are normal.

7. **OpenSSL / ssh-keygen said `string is too large`:** that is not “missing” the secret. It means the **value stored in** `VPS_SSH_KEY` is not a clean private key: often a **bad or truncated paste**, two keys **concatenated**, **.pub** content mixed in, or other text so the key bytes do not decode. **Regenerate a new deploy key** with `ssh-keygen -t ed25519 -N "" -f github_deploy` and paste the full contents of the **`github_deploy` file (private)** into the secret, and the single line from **`github_deploy.pub`** into `~/.ssh/authorized_keys` on the server.

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
