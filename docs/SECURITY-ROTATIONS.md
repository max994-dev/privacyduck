# Credential rotation runbook

Every credential listed here has been committed to git history at some
point, sent over chat, or otherwise leaked to a third party. They WILL
remain valid until you rotate them. Treat this list as a P0 checklist.

For each rotation: change the secret at the source, update the consuming
service, restart it, then verify the service still works before deleting
the old secret from the source.

---

## 1. DigitalOcean managed MySQL — `doadmin` password

**Old:** `AVNS_I2CkbNcVv-bhA-W7Ej9` (in git, see `src/common/database.php`
history). **Was used by:** privacyduck.com PHP, Windows VPS removal
pipeline.

**Steps**

1. DO console -> Databases -> `teletype-news-db-...` -> Users & Databases
   -> Reset `doadmin` password. Note the new value.
2. Update `.env` on the **PHP web VPS** (path: `/var/www/html/.env` if
   present, else create it):

   ```
   DB_PASSWORD=<new>
   ```

   `src/common/database.php` already reads this env. After the file is in
   place, restart php-fpm:
   `systemctl restart php8.2-fpm` (adjust version).

3. Update `.env` on the **Windows VPS removal box** (`C:\wonderful\removal\.env`):

   ```
   DB_PASSWORD=<new>
   ```

4. Restart pd-removal:
   `python C:\wonderful\pd_control\pd_services.py restart pd-removal`

5. Smoke-test: `curl https://privacyduck.com/login` (returns HTML), check
   `pd-removal.out.log` for connect errors.

6. Remove the inline fallback from `src/common/database.php` (the
   `$_pd_db_pass_default = "AVNS_..."` line) and commit. Until that line
   is gone, the old password is still valid as a fallback.

---

## 2. TwoCaptcha API key

**Old:** `c1f41f9edead3997c405c3a31d00687c` (hardcoded across ~200 broker
scripts in `C:\wonderful\removal\sites\`). **Billing impact:** every
captcha solved against this key bills the account.

**Steps**

1. Log into 2captcha.com -> Profile -> API key -> regenerate.
2. Append to `C:\wonderful\removal\.env`:

   ```
   TWOCAPTCHA_API_KEY=<new>
   ```

3. The new `lib/captcha.py` reads this env. Broker scripts that still
   inline the old key will keep using the old key until they're migrated.
   Migrate the highest-volume brokers first:

   ```python
   # in a broker .py file:
   # OLD: apiKey = "c1f41f9edead..."; solver = TwoCaptcha(apiKey)
   # NEW:
   from lib.captcha import get_solver
   solver = get_solver()
   ```

4. Restart pd-removal once any broker is migrated.

5. After all brokers migrated, 2captcha portal -> revoke the old key.

---

## 3. SmartProxy ISP credentials

**Old:** user `sp1vj8y5du`, password `o2ajgcB~6lLHMc22ep`. Same story as
the TwoCaptcha key — copy-pasted into every broker that uses a proxy.

**Steps**

1. SmartProxy dashboard -> users -> reset the password (or create a new
   sub-user and rotate over).
2. Add to `.env`:

   ```
   SMARTPROXY_USER=<new>
   SMARTPROXY_PASSWORD=<new>
   SMARTPROXY_ENDPOINT=isp.smartproxy.com
   SMARTPROXY_PORTS=10001,10002,...
   ```

3. `lib/proxies.py` reads these. Migrate brokers from inline creds to
   `from lib.proxies import get_proxy_extension` over time.
4. Once all migrated, retire the old SmartProxy user.

---

## 4. SMTP confirmation mailbox

**Old:** `confirmation@privacypros.com / privacypros123`. Was inline in
old `lib/email_sender.py` (now removed). Still valid on the mail server.

**Steps**

1. Log into mail1.privacypros.com webmail -> account settings -> change
   password.
2. Update `.env` on the Windows VPS:

   ```
   CONFIRMATION_EMAIL_PASSWORD=<new>
   ```

3. Restart **both** services that use it:

   ```
   python C:\wonderful\pd_control\pd_services.py restart pd-email-bot
   python C:\wonderful\pd_control\pd_services.py restart pd-removal
   ```

   (pd-removal uses it indirectly via `donotcallgov.py` and other email-
   based brokers; pd-email-bot logs into the inbox.)

4. Tail `pd-email-bot.err.log` for a clean login.

---

## 5. PimEyes account

Username `support@privacypros.com` + the password in `.env`
(`PIMEYES_PASSWORD`). Lower priority — not in git, only on the box, only
readable by Administrators after the icacls hardening. Rotate at PimEyes
on a normal schedule (every 90 days).

---

## 6. Windows VPS Administrator password

**Old:** `3Io9CycuUYb1P1186ekQT5FT`. Shared via chat.

**Steps**

1. RDP into the box -> Computer Management -> Local Users -> Administrator
   -> set new password.
2. Update `C:\temp\pd_relay_pass` on your workstation (the file that the
   `pd_relay_ssh.py` helper reads).
3. Re-verify with: `python C:\temp\pd_relay_ssh.py "hostname"`

---

## 7. id_rsa1 + SSH passphrase

**Old:** key file on your workstation + passphrase `aireoqkwkr` (in chat).
The key authenticates as root to privacyduck.com.

**Steps**

1. On the web VPS as root: edit `~/.ssh/authorized_keys`, delete the line
   matching the current id_rsa1 fingerprint
   (`ssh-keygen -lf ~/.ssh/id_rsa1.pub`).
2. Generate a fresh key on your workstation:
   `ssh-keygen -t ed25519 -f ~/.ssh/pd_new -N "<new passphrase>" -C "ops@privacyduck"`
3. Add its `.pub` to the VPS `authorized_keys`.
4. Update `pd_ssh.py` `KEY_PATH` to point at the new key.
5. Verify, then delete the old `id_rsa1` from disk.

---

## 8. Panel token (pd_control_panel)

**Old:** `U94YRLbt4VYMAiLJN28ltOC9zNaAFNYd` (in chat). Grants web access
to `http://144.126.136.20:8889/?token=<token>`.

**Steps**

1. RDP in, edit `C:\wonderful\pd_control\.env`, set:

   ```
   PD_PANEL_TOKEN=<new 24-byte base64url-ish>
   ```

   (Or delete the file and re-run `install.bat` step [3/4] to mint one.)

2. Restart: `sc stop pd-control-panel && sc start pd-control-panel`
3. Use the new URL.

---

## Verification after any rotation

```
curl -s -o /dev/null -w 'HTTP %{http_code}\n' https://privacyduck.com/
python C:\wonderful\pd_control\pd_services.py status
tail -20 C:\wonderful\pd_control\logs\pd-removal.out.log
```

All three should be healthy. If pd-removal won't start, check
`pd-removal.err.log` for `Access denied` (wrong DB password) or
`Authentication failed` (wrong SMTP password).
