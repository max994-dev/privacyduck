#!/usr/bin/env python3
"""
Run this on YOUR LOCAL MACHINE (not the server).
Downloads all CDN libraries to ./pd-vendor/, then gives you the scp command.

Usage:
    python3 download_vendors.py
    # follow the scp instruction printed at the end
"""

import urllib.request, urllib.error, os, re, sys

OUT = "./pd-vendor"
UA  = "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36"

def fetch(url, dest, label=""):
    os.makedirs(os.path.dirname(dest), exist_ok=True)
    req = urllib.request.Request(url, headers={"User-Agent": UA})
    try:
        with urllib.request.urlopen(req, timeout=30) as r:
            data = r.read()
        with open(dest, "wb") as f:
            f.write(data)
        print(f"  ✓ {label or os.path.basename(dest)}  ({len(data)//1024}KB)")
        return data
    except Exception as e:
        print(f"  ✗ FAILED {label}: {e}", file=sys.stderr)
        return None

def fetch_text(url):
    req = urllib.request.Request(url, headers={"User-Agent": UA})
    with urllib.request.urlopen(req, timeout=30) as r:
        return r.read().decode("utf-8")

# ── JS / CSS libraries ──────────────────────────────────────────────────────
print("\n=== JS / CSS Libraries ===")

libs = [
    ("https://code.jquery.com/jquery-3.7.1.min.js",
     f"{OUT}/jquery/jquery-3.7.1.min.js",                                      "jQuery 3.7.1"),

    ("https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css",
     f"{OUT}/swiper/swiper-bundle.min.css",                                     "Swiper 11 CSS"),
    ("https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js",
     f"{OUT}/swiper/swiper-bundle.min.js",                                      "Swiper 11 JS"),

    ("https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js",
     f"{OUT}/chartjs/chart.umd.min.js",                                         "Chart.js 4.4.4"),

    ("https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css",
     f"{OUT}/flowbite/flowbite-2.3.0.min.css",                                 "Flowbite 2.3.0 CSS"),
    ("https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js",
     f"{OUT}/flowbite/flowbite-2.3.0.min.js",                                  "Flowbite 2.3.0 JS"),
    ("https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.5.2/flowbite.min.js",
     f"{OUT}/flowbite/flowbite-2.5.2.min.js",                                  "Flowbite 2.5.2 JS"),

    ("https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css",
     f"{OUT}/toastr/toastr.min.css",                                            "Toastr CSS"),
    ("https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js",
     f"{OUT}/toastr/toastr.min.js",                                             "Toastr JS"),

    ("https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css",
     f"{OUT}/datatables/jquery.dataTables.min.css",                            "DataTables 1.13.6 CSS"),
    ("https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js",
     f"{OUT}/datatables/jquery.dataTables.min.js",                             "DataTables 1.13.6 JS"),

    ("https://cdn.socket.io/4.7.2/socket.io.min.js",
     f"{OUT}/socketio/socket.io.min.js",                                        "Socket.io 4.7.2"),

    ("https://unpkg.com/flickity@2/dist/flickity.min.css",
     f"{OUT}/flickity/flickity.min.css",                                        "Flickity CSS"),
    ("https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js",
     f"{OUT}/flickity/flickity.pkgd.min.js",                                   "Flickity JS"),

    ("https://unpkg.com/gsap@3.12.5/dist/gsap.min.js",
     f"{OUT}/gsap/gsap.min.js",                                                 "GSAP 3.12.5"),

    ("https://unpkg.com/tabulator-tables@5.5.0/dist/css/tabulator.min.css",
     f"{OUT}/tabulator/tabulator.min.css",                                      "Tabulator 5.5.0 CSS"),
    ("https://unpkg.com/tabulator-tables@5.5.0/dist/js/tabulator.min.js",
     f"{OUT}/tabulator/tabulator.min.js",                                       "Tabulator 5.5.0 JS"),

    ("https://unpkg.com/@lottiefiles/lottie-player@2.0.8/dist/lottie-player.js",
     f"{OUT}/lottie/lottie-player.js",                                          "Lottie Player 2.0.8"),

    ("https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css",
     f"{OUT}/aos/aos.css",                                                      "AOS 2.3.4 CSS"),
    ("https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js",
     f"{OUT}/aos/aos.js",                                                       "AOS 2.3.4 JS"),
]

for url, dest, label in libs:
    fetch(url, dest, label)

# ── Font Awesome 6.5.0 (CSS + webfonts) ────────────────────────────────────
print("\n=== Font Awesome 6.5.0 ===")
fa_url = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
fa_data = fetch(fa_url, f"{OUT}/font-awesome/css/all.min.css", "Font Awesome CSS")

if fa_data:
    css_text = fa_data.decode("utf-8")
    font_files = list(set(re.findall(r'\.\./webfonts/(fa-[\w-]+\.woff2)', css_text)))
    print(f"  Downloading {len(font_files)} webfont files...")
    for fname in sorted(font_files):
        wf_url = f"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/webfonts/{fname}"
        fetch(wf_url, f"{OUT}/font-awesome/webfonts/{fname}", fname)
    # Rewrite CSS to use local paths
    new_css = css_text.replace("../webfonts/", "/assets/vendor/font-awesome/webfonts/")
    with open(f"{OUT}/font-awesome/css/all.min.css", "w") as f:
        f.write(new_css)
    print("  ✓ Rewrote webfont paths in CSS")

# ── Google Fonts (self-hosted) ──────────────────────────────────────────────
print("\n=== Google Fonts ===")
font_requests = [
    ("Plus+Jakarta+Sans:wght@400;500;600;700", "plus-jakarta-sans"),
    ("Manrope:wght@400;600;700",               "manrope"),
    ("Roboto:wght@400;500;700",                "roboto"),
    ("Alatsi",                                 "alatsi"),
    ("Poppins:wght@400;500;600;700",           "poppins"),
    ("DM+Sans:wght@400;700",                   "dm-sans"),
    ("Inter:wght@400;500;600;700",             "inter"),
]

all_faces = []
for query, slug in font_requests:
    gf_url = f"https://fonts.googleapis.com/css2?family={query}&display=swap"
    try:
        css = fetch_text(gf_url)
    except Exception as e:
        print(f"  ✗ Could not fetch {slug}: {e}", file=sys.stderr)
        continue
    font_urls = list(set(re.findall(r'url\((https://fonts\.gstatic\.com/[^\)]+)\)', css)))
    os.makedirs(f"{OUT}/fonts/{slug}", exist_ok=True)
    for fu in font_urls:
        fname = fu.split("/")[-1].split("?")[0]
        fetch(fu, f"{OUT}/fonts/{slug}/{fname}", f"{slug}/{fname}")
        css = css.replace(fu, f"/assets/vendor/fonts/{slug}/{fname}")
    all_faces += re.findall(r'@font-face\s*\{[^}]+\}', css, re.DOTALL)
    print(f"  ✓ {slug}: {len(font_urls)} files")

combined = "/* Self-hosted Google Fonts */\n\n" + "\n".join(all_faces)
with open(f"{OUT}/fonts/fonts.css", "w") as f:
    f.write(combined)
print(f"  ✓ fonts.css written ({len(combined)//1024}KB)")

# ── Done ────────────────────────────────────────────────────────────────────
print(f"""
All done! Files are in: {os.path.abspath(OUT)}/

Now upload to server:
    scp -r {OUT}/* YOUR_USER@YOUR_SERVER_IP:/var/www/html/assets/vendor/

Example (replace with your actual user/IP):
    scp -r {OUT}/* root@12.34.56.78:/var/www/html/assets/vendor/
""")
