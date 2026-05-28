"""Tiny HTTP CONNECT proxy.

Listens on port 8787. Accepts standard `CONNECT host:port HTTP/1.1`
requests (the protocol every HTTPS client uses when behind a proxy),
opens a TCP socket to the target, and bridges bytes in both directions.

Used as a one-shot escape hatch so certbot on PD VPS (which has
extremely tight outbound firewall -- only TCP 8787 to this Windows VPS
and TCP 53 to anywhere) can talk to acme-v02.api.letsencrypt.org for
the cert renewal handshake. Replaces the pd-smtp-relay service on the
same port for the duration of cert issuance; original service is
restarted afterward.

Single-file, stdlib-only. Daemon threads. Closes connections after
60s of idle. Logs to stdout.
"""
import socket
import threading
import select
import sys
import time

LISTEN_PORT = 8787
CONNECT_TIMEOUT = 15
IDLE_TIMEOUT = 60
BUF = 65536


def log(msg):
    sys.stdout.write(f"[{time.strftime('%H:%M:%S')}] {msg}\n")
    sys.stdout.flush()


def bridge(a, b):
    """Bidirectionally pipe bytes between two sockets until one closes
    or idle timeout expires."""
    while True:
        try:
            r, _, _ = select.select([a, b], [], [], IDLE_TIMEOUT)
        except Exception:
            return
        if not r:
            return  # idle
        for s in r:
            try:
                data = s.recv(BUF)
            except Exception:
                return
            if not data:
                return
            target = b if s is a else a
            try:
                target.sendall(data)
            except Exception:
                return


def handle(client, peer):
    target = None
    try:
        client.settimeout(10)
        req = b""
        while b"\r\n\r\n" not in req:
            chunk = client.recv(4096)
            if not chunk:
                return
            req += chunk
            if len(req) > 16384:
                client.send(b"HTTP/1.1 414 Request Too Large\r\n\r\n")
                return
        client.settimeout(None)

        first = req.split(b"\r\n", 1)[0].decode("ascii", "ignore")
        log(f"{peer} {first[:120]}")
        parts = first.split()
        if len(parts) < 2 or parts[0].upper() != "CONNECT":
            client.send(b"HTTP/1.1 405 Method Not Allowed\r\n\r\n")
            return

        host_port = parts[1]
        if ":" not in host_port:
            client.send(b"HTTP/1.1 400 Bad Request\r\n\r\n")
            return
        host, port_s = host_port.rsplit(":", 1)
        try:
            port = int(port_s)
        except ValueError:
            client.send(b"HTTP/1.1 400 Bad Request\r\n\r\n")
            return

        try:
            target = socket.create_connection((host, port), timeout=CONNECT_TIMEOUT)
        except Exception as e:
            log(f"  connect fail {host}:{port} - {e}")
            client.send(b"HTTP/1.1 502 Bad Gateway\r\n\r\n")
            return

        client.send(b"HTTP/1.1 200 Connection Established\r\nConnection: close\r\n\r\n")
        bridge(client, target)
    except Exception as e:
        log(f"  err {peer}: {e}")
    finally:
        for s in (client, target):
            if s is not None:
                try:
                    s.shutdown(socket.SHUT_RDWR)
                except Exception:
                    pass
                try:
                    s.close()
                except Exception:
                    pass


def main():
    srv = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    srv.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
    srv.bind(("0.0.0.0", LISTEN_PORT))
    srv.listen(50)
    log(f"CONNECT proxy listening on 0.0.0.0:{LISTEN_PORT}")
    try:
        while True:
            client, addr = srv.accept()
            peer = f"{addr[0]}:{addr[1]}"
            t = threading.Thread(target=handle, args=(client, peer), daemon=True)
            t.start()
    except KeyboardInterrupt:
        log("shutdown")
    finally:
        srv.close()


if __name__ == "__main__":
    main()
