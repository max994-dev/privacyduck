# SSL Cert Renewal Runbook

Last successful renewal: **2026-05-28**. Cert valid until **2026-08-26**.
Next renewal due **2026-07-28** (per ARI hint from Let's Encrypt — gives
a 30-day cushion before actual expiry).

## Why this is manual (not auto)

The PD VPS has an extremely restrictive DO Cloud Firewall: only outbound
TCP 53 (DNS) and TCP 8787 to `144.126.136.20` (the Windows VPS / SMTP
relay) are allowed. Inbound TCP 80 is also blocked. This blocks every
standard cert-renewal path:

| challenge | needs | status |
|---|---|---|
| HTTP-01 | port 80 inbound | ❌ blocked at DO firewall |
| DNS-01 | Network Solutions login | ❌ credentials unknown |
| TLS-ALPN-01 | special certbot plugin or `acme.sh` | ✅ works via tunnel below |

The renewal works by **temporarily repurposing the `pd-smtp-relay`
service on the Windows VPS** as a CONNECT proxy. That gives the PD VPS
a tunnel to Let's Encrypt's API on port 8787. SMTP is unavailable
during the ~5 minute renewal window.

## Pre-renewal check

```bash
# From this dev box (Windows admin machine):
PYTHONIOENCODING=utf-8 python C:/temp/pd_ssh.py "openssl x509 -noout -enddate -in /etc/letsencrypt/live/privacyduck.com/fullchain.pem"
```

If `notAfter` is more than 21 days out, don't renew yet.

## Step 1 — Stage CONNECT proxy on Windows VPS

The `connect_proxy.py` file should already exist at `C:\temp\connect_proxy.py`
on the Windows VPS (uploaded during the May 2026 incident). If missing,
re-upload from `scripts/cert/connect_proxy.py` in this repo:

```bash
python C:/temp/pd_relay_sftp.py upload scripts/cert/connect_proxy.py C:/temp/connect_proxy.py
```

## Step 2 — Swap `pd-smtp-relay` to run the proxy

```bash
# Verbatim commands (PowerShell on Windows VPS)
PYTHONIOENCODING=utf-8 python C:/temp/pd_relay_ssh.py "powershell -Command \"\
\$nssm='C:\wonderful\pd_control\nssm.exe'; \
& \$nssm stop pd-smtp-relay; Start-Sleep 2; \
& \$nssm set pd-smtp-relay AppParameters 'C:\temp\connect_proxy.py'; \
& \$nssm set pd-smtp-relay AppDirectory 'C:\temp'; \
& \$nssm start pd-smtp-relay; Start-Sleep 4; \
Get-Service pd-smtp-relay | Format-Table Name, Status -AutoSize\""
```

Verify the tunnel from PD VPS:

```bash
PYTHONIOENCODING=utf-8 python C:/temp/pd_ssh.py "curl -x http://144.126.136.20:8787 -sS -o /dev/null -w 'HTTP=%{http_code}\n' https://acme-v02.api.letsencrypt.org/directory"
```

Should return `HTTP=200` within ~500ms.

## Step 3 — Stop nginx + renew cert

```bash
PYTHONIOENCODING=utf-8 python C:/temp/pd_ssh.py "systemctl stop nginx && \
HTTPS_PROXY=http://144.126.136.20:8787 HTTP_PROXY=http://144.126.136.20:8787 \
/opt/acme.sh/acme.sh --renew -d privacyduck.com --ecc --home /opt/acme.sh --force 2>&1 | tail -20"
```

For first-time issuance (not renewal), use `--issue --alpn -d privacyduck.com -d www.privacyduck.com --server letsencrypt --keylength ec-384 --accountemail hello@privacyduck.com` instead of `--renew`.

## Step 4 — Install cert to nginx path + restart

```bash
PYTHONIOENCODING=utf-8 python C:/temp/pd_ssh.py "/opt/acme.sh/acme.sh --install-cert -d privacyduck.com --ecc --home /opt/acme.sh \
  --fullchain-file /etc/letsencrypt/live/privacyduck.com/fullchain.pem \
  --key-file /etc/letsencrypt/live/privacyduck.com/privkey.pem; \
systemctl start nginx && systemctl is-active nginx"
```

## Step 5 — Restore `pd-smtp-relay` to original SMTP script

```bash
PYTHONIOENCODING=utf-8 python C:/temp/pd_relay_ssh.py "powershell -Command \"\
\$nssm='C:\wonderful\pd_control\nssm.exe'; \
& \$nssm stop pd-smtp-relay; Start-Sleep 2; \
& \$nssm set pd-smtp-relay AppParameters 'C:\wonderful\smtp_relay\mail_relay_server.py'; \
& \$nssm set pd-smtp-relay AppDirectory 'C:\wonderful\smtp_relay'; \
& \$nssm start pd-smtp-relay; Start-Sleep 3; \
Get-Service pd-smtp-relay | Format-Table Name, Status -AutoSize\""
```

## Step 6 — Verify

```bash
# From any internet-connected machine:
curl -sS -I https://privacyduck.com/ | head -3
echo | openssl s_client -servername privacyduck.com -connect privacyduck.com:443 2>/dev/null | openssl x509 -noout -dates
```

Expect `HTTP/1.1 200 OK` and `notAfter` ~90 days from today.

## How to make this go away (real fix)

Three permanent solutions, in order of cleanliness:

1. **Open port 80 inbound at the DO Cloud Firewall.** Then HTTP-01 works
   directly. Standard `certbot.timer` auto-renews. No tunnel needed.
2. **Move DNS from Network Solutions to Cloudflare** (or DigitalOcean
   DNS). Use the `dns-cloudflare` certbot plugin for fully automated
   DNS-01 renewals via API. Survives any port-blocking config.
3. **Set up a permanent CONNECT proxy on a different port of the Windows
   VPS** (not 8787 which conflicts with SMTP). Requires DO firewall
   change to allow PD VPS → Windows VPS:NEW_PORT.

Any of those needs DO panel access. If you find someone with credentials
or escalate to support, the no-credential workaround above can be
retired.

## Files referenced

- `scripts/cert/connect_proxy.py` — the CONNECT proxy code (canonical copy here)
- `scripts/cert/renew.sh` — convenience wrapper that runs steps 2-5 in sequence (optional)
- Windows VPS: `C:\temp\connect_proxy.py` (deployed copy)
- PD VPS: `/opt/acme.sh/` (acme.sh installation + state)
