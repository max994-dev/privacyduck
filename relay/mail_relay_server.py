#!/usr/bin/env python3
"""
PrivacyDuck mail relay: receive JSON over HTTP, send via SMTP (Windows or Linux).

Setup on 144.126.136.20 (Windows):
  1. Install Python 3.10+ from python.org (check "Add to PATH").
  2. Open Windows Firewall inbound TCP on LISTEN_PORT (default 8787), restrict source to your web VPS IP.
  3. Set environment variables (PowerShell example):

     $env:RELAY_SECRET = "same-as-smtp_config.local.php relay_secret"
     $env:SMTP_HOST = "mail1.privacyduck.com"
     $env:SMTP_PORT = "587"
     $env:SMTP_USER = "hello@privacyduck.com"
     $env:SMTP_PASS = "your-mailbox-password"
     $env:LISTEN_HOST = "0.0.0.0"
     $env:LISTEN_PORT = "8787"
     $env:ALLOW_SOURCE_IPS = "143.198.135.211"

  4. Run:
     python mail_relay_server.py

POST http://144.126.136.20:8787/send
Headers: Authorization: Bearer <RELAY_SECRET>, Content-Type: application/json
Body: {"to","subject","html","from_email","from_name","text_plain"?, "inline_images"?:[{cid,mime,filename,data_base64}]}
"""

from __future__ import annotations

import base64
import json
import os
import smtplib
import ssl
import sys
from email import policy
from email.mime.image import MIMEImage
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from http.server import BaseHTTPRequestHandler, HTTPServer
from typing import Any


def _mime_subtype(mime: str) -> str:
    mime = (mime or "image/png").strip().lower()
    if "/" in mime:
        return mime.split("/", 1)[1] or "png"
    return mime or "png"


def _build_message(data: dict[str, Any]) -> MIMEMultipart:
    to_addr = (data.get("to") or "").strip()
    subject = (data.get("subject") or "").replace("\r", "").replace("\n", "")
    html = data.get("html") or ""
    from_email = (data.get("from_email") or "").strip()
    from_name = (data.get("from_name") or "").strip()
    plain = (data.get("text_plain") or "").strip() or " "

    if not to_addr or not from_email or "@" not in to_addr or len(to_addr) < 5:
        raise ValueError("invalid to/from")

    msg = MIMEMultipart("related")
    msg["Subject"] = subject
    msg["From"] = f"{from_name} <{from_email}>" if from_name else from_email
    msg["To"] = to_addr

    alt = MIMEMultipart("alternative")
    alt.attach(MIMEText(plain, "plain", "utf-8"))
    alt.attach(MIMEText(html, "html", "utf-8"))
    msg.attach(alt)

    for img in data.get("inline_images") or []:
        if not isinstance(img, dict):
            continue
        cid = (img.get("cid") or "").strip()
        b64 = img.get("data") or ""
        if not cid or not b64:
            continue
        raw = base64.b64decode(b64, validate=False)
        subtype = _mime_subtype(str(img.get("mime") or "image/png"))
        part = MIMEImage(raw, _subtype=subtype)
        part.add_header("Content-ID", f"<{cid}>")
        fname = (img.get("filename") or "image.png").replace("\r", "").replace("\n", "")
        part.add_header("Content-Disposition", "inline", filename=fname)
        msg.attach(part)

    return msg


def _send_smtp(msg: MIMEMultipart, envelope_from: str, to_addr: str) -> None:
    host = os.environ.get("SMTP_HOST", "").strip()
    port = int(os.environ.get("SMTP_PORT", "587"))
    user = os.environ.get("SMTP_USER", "").strip()
    password = os.environ.get("SMTP_PASS", "")
    if not host or not user:
        raise RuntimeError("SMTP_HOST and SMTP_USER required")

    timeout = int(os.environ.get("SMTP_TIMEOUT", "60"))
    ctx = ssl.create_default_context()
    body = msg.as_string(policy=policy.SMTP)

    if port == 465:
        with smtplib.SMTP_SSL(host, port, timeout=timeout, context=ctx) as smtp:
            smtp.login(user, password)
            smtp.sendmail(envelope_from, [to_addr], body)
        return

    with smtplib.SMTP(host, port, timeout=timeout) as smtp:
        smtp.ehlo()
        if port == 587:
            smtp.starttls(context=ctx)
            smtp.ehlo()
        smtp.login(user, password)
        smtp.sendmail(envelope_from, [to_addr], body)


def _normalize_client_ip(ip: str) -> str:
    """Map ::ffff:203.0.113.1 to IPv4 for allowlist matching."""
    ip = ip.strip()
    if ip.startswith("::ffff:") and "." in ip:
        return ip[7:]
    return ip


def _allowed_client(ip: str) -> bool:
    raw = os.environ.get("ALLOW_SOURCE_IPS", "").strip()
    if not raw:
        return True
    allowed = {x.strip() for x in raw.split(",") if x.strip()}
    return _normalize_client_ip(ip) in allowed


def _write_json(handler: BaseHTTPRequestHandler, status: int, payload: dict[str, Any]) -> None:
    body = json.dumps(payload).encode("utf-8")
    handler.send_response(status)
    handler.send_header("Content-Type", "application/json; charset=utf-8")
    handler.send_header("Content-Length", str(len(body)))
    handler.end_headers()
    handler.wfile.write(body)


class RelayHandler(BaseHTTPRequestHandler):
    server_version = "MailRelay/1.0"

    def log_message(self, fmt: str, *args: Any) -> None:
        sys.stderr.write("%s - - [%s] %s\n" % (self.client_address[0], self.log_date_time_string(), fmt % args))

    def do_POST(self) -> None:  # noqa: N802
        path_only = self.path.split("?", 1)[0].rstrip("/") or "/"
        if path_only != "/send":
            self.send_error(404, "Not Found")
            return

        client_ip = self.client_address[0]
        norm_ip = _normalize_client_ip(client_ip)
        if not _allowed_client(client_ip):
            sys.stderr.write("reject: client_ip %s (norm %s) not in ALLOW_SOURCE_IPS\n" % (client_ip, norm_ip))
            _write_json(
                self,
                403,
                {
                    "ok": False,
                    "error": "client_ip_not_allowed",
                    "client_ip": client_ip,
                    "hint": "Add this IP to ALLOW_SOURCE_IPS on the relay (web server public IPv4).",
                },
            )
            return

        secret = os.environ.get("RELAY_SECRET", "")
        auth = self.headers.get("Authorization", "")
        token = auth[7:].strip() if auth.startswith("Bearer ") else ""
        if not secret or token != secret:
            sys.stderr.write("reject: bad or missing Bearer token (check RELAY_SECRET vs PHP relay_secret)\n")
            _write_json(
                self,
                403,
                {"ok": False, "error": "invalid_bearer_token", "hint": "RELAY_SECRET must match relay_secret in smtp_config.local.php"},
            )
            return

        length = int(self.headers.get("Content-Length", "0") or "0")
        if length < 1 or length > 25 * 1024 * 1024:
            self.send_error(413, "Payload Too Large")
            return

        raw = self.rfile.read(length)
        try:
            data = json.loads(raw.decode("utf-8"))
        except (UnicodeDecodeError, json.JSONDecodeError):
            self.send_error(400, "Invalid JSON")
            return

        if not isinstance(data, dict):
            self.send_error(400, "Invalid body")
            return

        try:
            msg = _build_message(data)
            to_addr = (data.get("to") or "").strip()
            from_email = (data.get("from_email") or "").strip()
            _send_smtp(msg, from_email, to_addr)
        except Exception as e:
            sys.stderr.write("send error: %s\n" % e)
            self.send_response(500)
            self.send_header("Content-Type", "application/json")
            self.end_headers()
            self.wfile.write(json.dumps({"ok": False, "error": str(e)}).encode())
            return

        self.send_response(200)
        self.send_header("Content-Type", "application/json")
        self.end_headers()
        self.wfile.write(json.dumps({"ok": True}).encode())


def main() -> None:
    host = os.environ.get("LISTEN_HOST", "0.0.0.0")
    port = int(os.environ.get("LISTEN_PORT", "8787"))
    httpd = HTTPServer((host, port), RelayHandler)
    print("Mail relay listening on http://%s:%s/send" % (host, port), flush=True)
    httpd.serve_forever()


if __name__ == "__main__":
    main()
