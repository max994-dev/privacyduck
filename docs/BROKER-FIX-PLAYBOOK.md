# Broker scraping fix playbook

Status as of 2026-05-28. Pipeline currently has **302 real broker scripts**
+ **112 stubs** = 414 total. Of the 302 real, **175 (~58%)** succeed in
production, **116 (~38%)** are at 0% success, and ~11 are mixed.

This document tells you which broken brokers can be fixed how, in what
priority order, with what effort.

## How to read this

Each broker falls into one of these buckets based on the audit:

| Bucket | Count | What it means | Realistic fix |
|---|---:|---|---|
| `FIXABLE_OBVIOUS` | 4 | Page loads, script's selectors match (substring) what's on the page today. Likely a flow / captcha / consent-banner issue, not selectors. | 30-60 min per broker: run the script + capture screenshots at each step, identify which step breaks, fix that step. |
| `FIXABLE_NONTRIV` | 10 | Form is there, partial selector match. Flow changed materially OR new fields added. | 1-2 hours per broker. |
| `BLOCKED_NOW` | 64 (total) / 4 (batch 1) | Even real Chromium gets 403 / Cloudflare. The broker actively blocks our IP range or fingerprint. | Try residential proxy provider (smartproxy plan may need upgrade). Or use a different anti-bot bypass (FlareSolverr, ZenRows, ScraperAPI). Not a "selectors fix". |
| `PAGE_EMPTY` | 3 | Page loads but DOM has no inputs. JS-heavy SPA — content mounts after user interaction (e.g. click a tab first) | Add explicit "click the right tab/button" step before form-fill. |
| `PAGE_EMPTY_NO_FORM` | 2 | Modern privacy widgets (Ketch, OneTrust SPA, Demandbase). No traditional `<form>` element — interactions via JS. | Rewrite to interact with the widget specifically. ~2-3 hours per broker. |
| `GONE` | 1 | Broker decommissioned the opt-out URL entirely (404). | Find new URL via Google + their privacy policy page, OR mark as dead. |
| `NO_URL` | 15 | Broker script has no `page.get()` URL — was email-based but SMTP path is broken. | Audit SMTP relay status. If can't fix, switch to web-based opt-out. |

## Batch 1 — 24 brokers with `SELECTORS_MISS/PARTIAL`

(All from the production "0% success rate" bucket. Recon data lives in
`C:/temp/broker_recon/<name>.json` and `<name>.jpg` screenshot.)

### `FIXABLE_OBVIOUS` (4) — start here for quickest wins

| Broker | Form state today | Likely root cause | Fix |
|---|---|---|---|
| `grassrootsanalyticscom` | 13 inputs, 8 buttons, 2 forms — 8/10 script tokens match | Script's form-fill step probably works; submission may fail. | Run the script with screenshots at each step. Look at logged exception. |
| `notariescaliforniacom` | 1 input visible, search-only? Title is "California Notaries" | This is a SEARCH page, not an opt-out. The script may be looking for the wrong page. | Open the broker site, find their actual data-removal URL (not the homepage). |
| `thatsthemcom` | 6 visible inputs, clean form. 6/8 tokens match. | Likely a consent banner blocking interaction, or post-submit page changed. | Run with screenshots; expect a small flow tweak. |
| `truthfindercom` | 7 inputs, but uses `name="firstName"` not `id`. Multi-step UI ("View User Data Tools" → "Delete My User Data" → form). | Both selectors AND flow changed since the script was written. | Rewrite to navigate the multi-step flow. ~1-2 hrs. |

### `FIXABLE_NONTRIV` (10) — second tier

**Two shared backends** — fix one, fix both:

| Pair | Backend | Action |
|---|---|---|
| `infotracercom` + `ndbcom` | infotracer.com/optout — 3 inputs (`fname/lname/city`) + state select | Script SHOULD work via substring selectors. Run it; the failure is likely in the second stage (search results → email confirm) or captcha. |
| `northcarolinawarrantorg` + `ohioarrestwarrantorg` | members.verifyrecords.com/customer/opt-out — 3 inputs + 4 buttons | Same as above. There are probably ~10-15 other state-arrest brokers using this same Verifyrecords backend (per the stubs we generated). Fixing this unlocks a cluster. |
| `searchquarrycom` + `staterecordsorg` + `recordsfindercom` | Similar Verifyrecords platform (different URL paths) | Likely one template fix covers all three. |
| `openpeoplesearchcom` + `spydialercom` | Both have 2 inputs + remove-link pattern. `spydialercom` had 0 visible inputs (likely JS-mounts on tab change) | Add a "click View Removal Process" preamble step. |
| `bvdinfocom` | Alchemer survey (113 fields, multi-page) | Largest fix. Recommend dropping or proxying via Alchemer's API. |

### `BLOCKED_NOW` (4) — won't yield to selector fixes

| Broker | Title | What to do |
|---|---|---|
| `dataaxlecom` | "Just a moment..." (Cloudflare) | Need stronger anti-bot bypass. Test SmartProxy residential, then ZenRows. |
| `radariscom` | "Attention Required! \| Cloudflare" | Same |
| `rocketreachco` | "Just a moment..." | Same |
| `socialcatfishcom` | "Just a moment..." | Same |

These four have HIGH usage demand (~110 attempts each per the production data). Worth investing in a proxy upgrade specifically to unblock them.

### `PAGE_EMPTY` (3) — need extra UI interaction

| Broker | Page | What needs to happen first |
|---|---|---|
| `digdevdirectcom` | "Access Denied" | URL may need different entry. Check broker's privacy page. |
| `freebackgroundcheckorg` | Has tab UI (`?tab=optout`) | Click the opt-out tab before the form mounts. |
| `pchcom` | Empty body, no buttons | Page may be cookie/JS gated. Investigate. |

### `PAGE_EMPTY_NO_FORM` (2)

| Broker | Widget | What to do |
|---|---|---|
| `epsiloncom` | 59 buttons (Ketch widget tabs), 0 inputs | Click the right Ketch tab via its data-attribute, then fill form. ~2 hrs. |
| `insideviewcom` | Demandbase Ketch widget | Same. Often shared widget across brokers, so once you have a Ketch helper, it works for both. |

### `GONE` (1)

| Broker | What | Action |
|---|---|---|
| `acbjcom` | OneTrust webform 404'd | Search ACBJ site for their new privacy/CCPA URL, or mark as deprecated. |

## Tools already built (in `C:/temp/`)

| File | What it does |
|---|---|
| `broker_recon.py` | Playwright recon — visits a broker URL, dumps form structure + screenshot to JSON. Easy to extend to other batches. |
| `broker_static_audit.py` | HTTP-only audit — probes 414 broker URLs, categorizes by reachability + selector heuristics. Can run weekly as a regression check. |
| `analyze_fixable.py` | Cross-references recon vs current script selectors, classifies brokers into FIXABLE / BLOCKED / GONE / etc. |
| `dehard_creds.py` | Already-applied script that moved TwoCaptcha + SmartProxy creds from inline literals to env. Idempotent. |

## Batches 2 and 3 (not yet recon'd)

| Batch | Count | Static verdict | Recommended approach |
|---|---:|---|---|
| 2 — `NO_URL` | 15 | Email-based brokers with no working URL. Most likely SMTP issue or `lib.email_sender` mis-configuration. | Audit SMTP relay first. Then for any that should be web-based, find the URL. |
| 3 — `BLOCKED` (full) | 64 | All return 403 / Cloudflare from a single IP. | **Don't fix one-by-one.** Switch proxy provider OR add residential-IP rotation. Once unblocked, re-run static audit to see how many also have selector issues. |

## What I won't do in a single session (and you shouldn't expect me to)

Fixing all 116 broken brokers is **2-4 person-weeks of dedicated work** if done well, plus ongoing maintenance because brokers regularly redesign their forms specifically to break scrapers. This is normally a **full-time role** at any data-removal company.

The path forward that actually scales:

1. **Treat broker maintenance as ongoing infra**, not a project. Hire 1 person (or rent vendor time) to run the recon weekly, triage breakages, fix the top 3-5 most-failing brokers per week.
2. **Consider partnering** with an existing data-broker-removal API (Onerep, DeleteMe API). They've already solved this for ~600 brokers and maintain it. You become the user-facing brand; they handle the scraping arms race. Standard partnership in this space.
3. **Mark broken brokers as `pending_maintenance` in the dashboard** so users see "we attempted 175 brokers, 116 are temporarily unavailable while we update the scraping" instead of "0% success on whitepages.com" with no explanation.

## Re-running this audit later

```bash
# from a workstation with Playwright installed:
python C:/temp/broker_static_audit.py        # ~10 min, reports status of all 414 URLs
python C:/temp/broker_recon.py               # ~5 min, full recon of batch 1
python C:/temp/analyze_fixable.py            # instant, classifies fixable vs not

# from the VPS:
python C:\temp\audit_sites.py                # file-level audit (syntax, structure)
python C:\temp\broker_outcomes.py            # current production success rates from DB
```

All the broker audit tools support being re-run; they're idempotent.
