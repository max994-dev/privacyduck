<?php
/**
 * Removal Journey panel -- the actual dashboard.
 *
 * Components:
 *   1. Hero: donut chart (SVG) showing % removed of 413 + big done number + ETA
 *   2. Stat row: Removed | Processed (24h) | Scheduled | Needs attention
 *   3. 14-day activity bar chart (SVG): visual proof of recent throughput
 *   4. Recent removals feed (last 8 broker state changes with timestamps)
 *
 * All data live from `results` table. Single broker = single target_domain
 * row. Total = 413 (canonical broker count after May 2026 normalization).
 *
 * Requires $conn from dashboard_bootstrap.php. Renders nothing if user
 * has no rows (paid-but-not-yet-planned new account).
 */

$pdUserId = (int)($_SESSION["user_id"] ?? 0);

// --- Single SQL pass for counts + 24h activity (uses idx_results_user_kind_step) ---
$pdCounts = ['done' => 0, 'queued' => 0, 'in_flight' => 0, 'failed' => 0,
             'not_impl' => 0, 'missing_pii' => 0, 'total' => 0, 'done_24h' => 0];
$pdDaily = array_fill(0, 14, 0);  // last 14 days, oldest first
$pdRecent = [];
$pdPlanedAt = null;

try {
    // planedAt is in session already (set at signup); avoid a DB roundtrip.
    $pdPlanedAt = $_SESSION['planedAt'] ?? null;
    if (!$pdPlanedAt) {
        // First-time fallback: pull + cache for the rest of the session
        $stmt = $conn->prepare("SELECT planedAt FROM users WHERE id = ?");
        $stmt->bind_param("i", $pdUserId);
        $stmt->execute();
        $pdPlanedAt = $stmt->get_result()->fetch_assoc()['planedAt'] ?? null;
        $stmt->close();
        $_SESSION['planedAt'] = $pdPlanedAt;
    }

    // SINGLE QUERY for step counts + 24h count + 14-day daily aggregation.
    // Uses idx_results_user_kind_step. Returns one row per (step, day)
    // bucket; we fold into the data structures in PHP.
    $stmt = $conn->prepare(
        "SELECT step,
                CASE WHEN updated_at >= CURDATE() - INTERVAL 13 DAY
                     THEN DATE(updated_at) ELSE NULL END AS d,
                COUNT(*) AS n
         FROM results
         WHERE user_id = ? AND kind = 1
         GROUP BY step, d"
    );
    $stmt->bind_param("i", $pdUserId);
    $stmt->execute();
    $daily = [];
    foreach ($stmt->get_result()->fetch_all(MYSQLI_ASSOC) as $r) {
        $n = (int) $r['n'];
        $step = (int) $r['step'];
        $pdCounts['total'] += $n;
        switch ($step) {
            case 0: $pdCounts['queued']     += $n; break;
            case 1: $pdCounts['in_flight']  += $n; break;
            case 2: $pdCounts['done']       += $n; break;
            case 3: $pdCounts['failed']     += $n; break;
            case 4: $pdCounts['not_impl']   += $n; break;
            case 5: $pdCounts['missing_pii']+= $n; break;
        }
        // Per-day done count (only step=2 within the 14-day window)
        if ($step === 2 && $r['d']) {
            $daily[$r['d']] = ($daily[$r['d']] ?? 0) + $n;
            // Roll up "today" into done_24h (close enough to a sliding 24h)
            if ($r['d'] === date('Y-m-d') || $r['d'] === date('Y-m-d', strtotime('-1 day'))) {
                // Only count rows from last 24h precisely
            }
        }
    }
    $stmt->close();
    for ($i = 13; $i >= 0; $i--) {
        $d = date('Y-m-d', strtotime("-$i days"));
        $pdDaily[13 - $i] = $daily[$d] ?? 0;
    }
    // done_24h as a sliding 24h, separate small query (different time
    // boundary from CURDATE())
    $stmt = $conn->prepare(
        "SELECT COUNT(*) AS n FROM results
         WHERE user_id = ? AND kind = 1 AND step = 2
           AND updated_at > NOW() - INTERVAL 24 HOUR"
    );
    $stmt->bind_param("i", $pdUserId);
    $stmt->execute();
    $pdCounts['done_24h'] = (int)($stmt->get_result()->fetch_assoc()['n'] ?? 0);
    $stmt->close();

    // Recent activity (last 8 rows that moved). Cheap with the index.
    // Pull site_url + removal_url so we can show the actual broker URL
    // under the slug name in the feed.
    $stmt = $conn->prepare(
        "SELECT id, target_domain, step, updated_at, site_url, removal_url
         FROM results
         WHERE user_id = ? AND kind = 1 AND step IN (1, 2, 3, 4, 5)
         ORDER BY updated_at DESC LIMIT 8"
    );
    $stmt->bind_param("i", $pdUserId);
    $stmt->execute();
    $pdRecent = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} catch (Throwable $e) {
    error_log('journey_panel read failed: ' . $e->getMessage());
}

// --- Derived values ---
$pdTotal       = max(1, $pdCounts['total']);  // never divide by zero
$pdDonePct     = (int) round(($pdCounts['done'] * 100) / $pdTotal);
$pdRemaining   = $pdCounts['total'] - $pdCounts['done'];
$pdAvg7Day     = array_sum(array_slice($pdDaily, 7)) / 7.0;  // avg over last 7 days
$pdEtaDays     = ($pdAvg7Day > 0 && $pdRemaining > 0)
                   ? (int) ceil($pdRemaining / $pdAvg7Day)
                   : null;

// Phase indicator (kept from old design but tightened)
$pdPhaseNum = 0;
$pdPhaseLabel = 'Waiting to start';
$pdPhaseDesc = 'Add a plan to begin your removal journey.';
if (!empty($_SESSION['planable']) && $pdPlanedAt) {
    try {
        $days = (int) (new DateTime())->diff(new DateTime($pdPlanedAt))->format('%a');
        if      ($days < 1)  { $pdPhaseNum = 1; $pdPhaseLabel = 'Day 1';
                               $pdPhaseDesc = 'Hitting the highest-traffic brokers first.'; }
        elseif  ($days < 3)  { $pdPhaseNum = 2; $pdPhaseLabel = 'First 72 hours';
                               $pdPhaseDesc = 'Working through people-search sites.'; }
        elseif  ($days < 30) { $pdPhaseNum = 3; $pdPhaseLabel = 'Week 1-4';
                               $pdPhaseDesc = 'Submitting to long-tail brokers; confirmations rolling in.'; }
        else                 { $pdPhaseNum = 4; $pdPhaseLabel = 'Active monitoring';
                               $pdPhaseDesc = 'Re-sweep every 90 days to catch re-appearances.'; }
    } catch (Exception $e) {}
}

// Per-step label + color for the recent activity feed
function pd_step_meta(int $step): array {
    switch ($step) {
        case 2: return ['Removed',                    '#24A556'];
        case 1: return ['In progress now',            '#3B82F6'];
        case 3: return ['Retrying',                   '#3B82F6'];
        case 4: return ['Retrying',                   '#3B82F6'];
        case 5: return ['Broker requested more info', '#2563EB'];
    }
    return ['Scheduled', '#878C91'];
}

// Derive a human-readable host from the broker slug + the optional site_url
// column. "californiaarrestsorg" -> "californiaarrests.org". Prefers
// site_url if populated since some brokers have non-standard host shapes.
function pd_broker_host(string $slug, ?string $siteUrl): string {
    if ($siteUrl) {
        $h = parse_url($siteUrl, PHP_URL_HOST);
        if ($h) return ltrim($h, '.');
    }
    foreach (['com','org','net','edu','gov','info','biz','io','ai','tv','co','us'] as $sfx) {
        $len = strlen($sfx);
        if (strlen($slug) > $len && substr($slug, -$len) === $sfx) {
            return substr($slug, 0, -$len) . '.' . $sfx;
        }
    }
    return $slug;
}

// Google's favicon service. Free, no API key, ~50ms latency, fine for
// per-row icons. sz=64 = 32x32 retina-ready.
function pd_broker_favicon_url(string $host): string {
    return 'https://www.google.com/s2/favicons?domain=' . rawurlencode($host) . '&sz=64';
}

// Format a time-ago string from a SQL datetime
function pd_time_ago(?string $iso): string {
    if (!$iso) return '';
    $t = strtotime($iso);
    if (!$t) return '';
    $d = time() - $t;
    if ($d < 60)        return $d . 's ago';
    if ($d < 3600)      return (int)($d / 60) . 'm ago';
    if ($d < 86400)     return (int)($d / 3600) . 'h ago';
    if ($d < 86400 * 7) return (int)($d / 86400) . 'd ago';
    return date('M j', $t);
}

// SVG donut math. Reduced from r=70 (160px diameter) -> r=58 (140px) to
// keep the hero card less tall when sitting beside the much shorter
// chart and stats cards.
$donutR = 58;
$donutCirc = 2 * M_PI * $donutR;
$donutOffset = $donutCirc * (1 - $pdDonePct / 100);

// 14-day bar chart math
$pdDailyMax = max(1, max($pdDaily));
?>

<!-- items-start so cards size to their content. Inline grid-template-columns
     bypasses Tailwind purge issues: `lg:col-span-2` was getting tree-shaken
     out of the CSS build because tailwind.config didn't see this file
     correctly, leaving the hero squished to 1/4 width. Explicit grid-template
     here works without needing the build to know about it. -->
<div id="pd-journey-panel"
     class="mt-[24px] grid gap-[16px] items-start"
     style="grid-template-columns: 1fr;"
     data-pd-desktop-grid="2fr 1fr 1fr">

    <!-- HERO: 2fr (50% of the 4-fr total). Tighter padding so the card
         isn't gratuitously tall when there's no extra pills to show. -->
    <div class="rounded-[24px] bg-white border border-[#F1F1F1] p-[20px] sm:p-[24px] flex flex-col sm:flex-row items-center gap-[16px] sm:gap-[24px]">
        <div class="flex-1 min-w-0 text-center sm:text-left order-2 sm:order-1">
            <div class="text-[11px] font-semibold uppercase tracking-[0.14em] text-[#24A556]">
                Phase <?= $pdPhaseNum ?: '-' ?> &middot; <?= htmlspecialchars($pdPhaseLabel, ENT_QUOTES, 'UTF-8') ?>
            </div>
            <h2 class="mt-[4px] text-[32px] sm:text-[38px] font-bold text-[#010205] leading-[1.05] tabular-nums">
                <span data-pd-count="done"><?= number_format($pdCounts['done']) ?></span>
                <span class="text-[#9CA3AF] font-semibold text-[20px] sm:text-[24px]">/&nbsp;<?= number_format($pdCounts['total']) ?></span>
            </h2>
            <div class="mt-[2px] text-[13px] text-[#5B5F66] font-medium">
                broker sites where your data has been removed
            </div>
            <p class="mt-[4px] text-[12px] sm:text-[13px] text-[#5B5F66] leading-[1.45]">
                <?= htmlspecialchars($pdPhaseDesc, ENT_QUOTES, 'UTF-8') ?>
            </p>
            <!-- Phase progress stepper: 4 horizontal dots connected by a
                 line. Filled dot = phase reached/passed. Outlined = future.
                 The connecting line is brand green up to the current phase,
                 light gray beyond. Gives an immediate visual answer to
                 "where am I in the journey?" -->
            <div class="mt-[14px] flex items-center gap-0 max-w-[420px] mx-auto sm:mx-0">
                <?php
                $phases = [
                    ['n' => 1, 'label' => 'Day 1'],
                    ['n' => 2, 'label' => '72 hours'],
                    ['n' => 3, 'label' => 'Week 1-4'],
                    ['n' => 4, 'label' => 'Monitoring'],
                ];
                foreach ($phases as $i => $p):
                    $isCurrent = ($p['n'] === $pdPhaseNum);
                    $isPast = ($p['n'] < $pdPhaseNum);
                    $isReached = $isCurrent || $isPast;
                ?>
                    <div class="flex flex-col items-center flex-1 shrink-0 relative">
                        <?php if ($i > 0): ?>
                            <div class="absolute right-1/2 top-[7px] w-full h-[2px] -translate-y-1/2 <?= $isReached ? 'bg-[#24A556]' : 'bg-[#E5E7EB]' ?>"></div>
                        <?php endif; ?>
                        <div class="relative z-10 w-[14px] h-[14px] rounded-full <?= $isCurrent ? 'bg-[#24A556] ring-4 ring-[#E8F7EF]' : ($isPast ? 'bg-[#24A556]' : 'bg-white border-2 border-[#E5E7EB]') ?>"></div>
                        <div class="mt-[5px] text-[9px] sm:text-[10px] font-semibold uppercase tracking-wide <?= $isReached ? 'text-[#1A7F40]' : 'text-[#9CA3AF]' ?> whitespace-nowrap"><?= $p['label'] ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-[10px] flex flex-wrap gap-[8px] justify-center sm:justify-start">
                <span class="inline-flex items-center gap-[6px] rounded-full bg-[#E8F7EF] text-[#1A7F40] px-[12px] py-[6px] text-[12px] font-semibold">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
                        <path d="M12 6v6l4 2" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2.4"/>
                    </svg>
                    <span data-pd-count="done_24h"><?= number_format($pdCounts['done_24h']) ?></span>&nbsp;removed in last 24h
                </span>
                <?php if ($pdEtaDays !== null && $pdEtaDays > 0): ?>
                    <span class="inline-flex items-center gap-[6px] rounded-full bg-[#F4F8FC] text-[#0F2A5F] px-[12px] py-[6px] text-[12px] font-semibold">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12l5 5L20 7" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        ETA ~<?= $pdEtaDays ?> day<?= $pdEtaDays === 1 ? '' : 's' ?> at current pace
                    </span>
                <?php elseif ($pdCounts['done'] >= $pdCounts['total'] && $pdCounts['total'] > 0): ?>
                    <span class="inline-flex items-center gap-[6px] rounded-full bg-[#E8F7EF] text-[#1A7F40] px-[12px] py-[6px] text-[12px] font-semibold">
                        All brokers complete &mdash; in monitoring
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Supporting donut (140px). Sits beside the big number/text
             on the right; on mobile (flex-col) it moves above. -->
        <div class="relative shrink-0 order-1 sm:order-2">
            <svg width="140" height="140" viewBox="0 0 140 140" class="-rotate-90">
                <circle cx="70" cy="70" r="<?= $donutR ?>"
                        fill="none" stroke="#F3F4F6" stroke-width="12"/>
                <circle id="pd-donut-progress" cx="70" cy="70" r="<?= $donutR ?>"
                        fill="none" stroke="#24A556" stroke-width="12"
                        stroke-linecap="round"
                        stroke-dasharray="<?= $donutCirc ?>"
                        stroke-dashoffset="<?= $donutOffset ?>"
                        style="transition: stroke-dashoffset 800ms ease-out;"/>
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                <div class="text-[26px] font-bold text-[#010205] leading-none tabular-nums" data-pd-pct><?= $pdDonePct ?>%</div>
                <div class="text-[9px] uppercase tracking-[0.1em] text-[#878C91] font-semibold mt-[2px]">complete</div>
            </div>
        </div>
    </div>

    <!-- 14-DAY ACTIVITY BAR CHART (25% width). Padding tightened so the
         chart breathes against the smaller column width. -->
    <div class="rounded-[24px] bg-white border border-[#F1F1F1] p-[20px] flex flex-col">
        <div class="flex items-baseline justify-between gap-[8px]">
            <div class="min-w-0">
                <div class="text-[11px] font-semibold uppercase tracking-[0.12em] text-[#878C91]">Last 14 days</div>
                <div class="mt-[2px] text-[20px] font-bold text-[#010205] leading-none whitespace-nowrap">
                    <?= number_format(array_sum($pdDaily)) ?>
                    <span class="text-[12px] font-medium text-[#5B5F66]">removed</span>
                </div>
            </div>
            <div class="text-[11px] text-[#878C91] whitespace-nowrap">avg <?= number_format($pdAvg7Day, 1) ?>/day</div>
        </div>
        <svg viewBox="0 0 280 110" preserveAspectRatio="none" class="w-full h-[100px] mt-[12px] flex-1">
            <?php foreach ($pdDaily as $i => $count):
                $barHeight = max(2, ($count / $pdDailyMax) * 100);
                $x = $i * 20 + 2;
                $y = 105 - $barHeight;
                $isToday = ($i === 13);
                $fill = $count > 0 ? '#24A556' : '#E5E7EB';
            ?>
                <rect x="<?= $x ?>" y="<?= $y ?>" width="16" height="<?= $barHeight ?>"
                      rx="3" fill="<?= $fill ?>"
                      <?= $isToday ? 'opacity="1"' : 'opacity="' . (0.55 + 0.45 * ($i / 13)) . '"' ?>>
                    <title><?= date('M j', strtotime("-" . (13 - $i) . " days")) ?>: <?= $count ?> removed</title>
                </rect>
            <?php endforeach; ?>
        </svg>
        <div class="mt-[8px] flex justify-between text-[9px] text-[#878C91] font-medium uppercase tracking-wide">
            <span><?= date('M j', strtotime('-13 days')) ?></span>
            <span>today</span>
        </div>
    </div>

    <!-- STATS COLUMN (25% width, 2x2 grid inside). MOVED here from a
         full-width row below per UX request -- now sits at the right end
         of the chart row so all summary info is on one line. Each cell
         compact: smaller padding, smaller number font to fit the column. -->
    <div class="grid grid-cols-2 gap-[10px] content-start">
        <div class="rounded-[16px] bg-white border border-[#F1F1F1] p-[14px]">
            <div class="text-[10px] text-[#878C91] font-semibold uppercase tracking-[0.06em]">Removed</div>
            <div class="mt-[4px] text-[22px] font-bold text-[#24A556] leading-none tabular-nums" data-pd-count="done"><?= number_format($pdCounts['done']) ?></div>
            <div class="mt-[4px] text-[10px] text-[#878C91] leading-tight">of <?= number_format($pdCounts['total']) ?></div>
        </div>
        <div class="rounded-[16px] bg-white border border-[#F1F1F1] p-[14px]">
            <div class="text-[10px] text-[#878C91] font-semibold uppercase tracking-[0.06em]">Last 24h</div>
            <div class="mt-[4px] text-[22px] font-bold text-[#3B82F6] leading-none tabular-nums" data-pd-count="done_24h"><?= number_format($pdCounts['done_24h']) ?></div>
            <div class="mt-[4px] text-[10px] text-[#878C91] leading-tight">new removals</div>
        </div>
        <div class="rounded-[16px] bg-white border border-[#F1F1F1] p-[14px]">
            <div class="text-[10px] text-[#878C91] font-semibold uppercase tracking-[0.06em]">Scheduled</div>
            <div class="mt-[4px] text-[22px] font-bold text-[#010205] leading-none tabular-nums" data-pd-count="queued"><?= number_format($pdCounts['queued'] + $pdCounts['failed'] + $pdCounts['not_impl']) ?></div>
            <div class="mt-[4px] text-[10px] text-[#878C91] leading-tight">in pipeline</div>
        </div>
        <div class="rounded-[16px] bg-white border border-[#F1F1F1] p-[14px]">
            <div class="text-[10px] text-[#878C91] font-semibold uppercase tracking-[0.06em]">Needs info</div>
            <div class="mt-[4px] text-[22px] font-bold <?= $pdCounts['missing_pii'] > 0 ? 'text-[#2563EB]' : 'text-[#010205]' ?> leading-none tabular-nums" data-pd-count="missing_pii"><?= number_format($pdCounts['missing_pii']) ?></div>
            <div class="mt-[4px] text-[10px] text-[#878C91] leading-tight">awaiting profile</div>
        </div>
    </div>

    <!-- Recent-activity mini-feed REMOVED. The full paginated broker
         table (notable_brokers + result_sites below this panel) covers
         the same information without duplication. -->

</div>

<script>
/* Responsive grid: swap grid-template-columns based on viewport width.
   Doing this in JS instead of via Tailwind classes because the lg:col-span-*
   variants kept getting purged from the CSS build for this file, leaving
   the hero squished to 1/4 width. */
(function () {
    var panel = document.getElementById('pd-journey-panel');
    if (!panel) return;
    var desktop = panel.getAttribute('data-pd-desktop-grid') || '2fr 1fr 1fr';
    function apply() {
        panel.style.gridTemplateColumns = (window.innerWidth >= 1024) ? desktop : '1fr';
    }
    apply();
    window.addEventListener('resize', apply);
})();

/* Live-polling for the dashboard. Polls /api/journey_status every 8s and
   smoothly animates: donut stroke-dashoffset + all numeric counts + the
   recent activity feed. Page Visibility API pause-when-hidden, 30s
   backoff on error. */
(function () {
    var panel = document.getElementById('pd-journey-panel');
    if (!panel) return;

    var INTERVAL_OK  = 8000;
    var INTERVAL_ERR = 30000;
    var DONUT_R = <?= $donutR ?>;
    var DONUT_CIRC = 2 * Math.PI * DONUT_R;
    var nextDelay = INTERVAL_OK;
    var inflight = false;
    var lastRecentSig = '';

    function fmtNum(n) { return (Number(n) || 0).toLocaleString(); }

    function setText(sel, value) {
        var els = panel.querySelectorAll(sel);
        for (var i = 0; i < els.length; i++) els[i].textContent = value;
    }

    function setDonut(pct) {
        var c = document.getElementById('pd-donut-progress');
        if (!c) return;
        c.setAttribute('stroke-dashoffset', String(DONUT_CIRC * (1 - pct / 100)));
    }

    function escapeHTML(s) {
        return String(s == null ? '' : s).replace(/[<>&"]/g, function (c) {
            return ({'<':'&lt;', '>':'&gt;', '&':'&amp;', '"':'&quot;'})[c];
        });
    }

    function timeAgoFromISO(iso) {
        if (!iso) return '';
        var t = Date.parse(iso.replace(' ', 'T') + 'Z');
        if (isNaN(t)) return '';
        var d = Math.floor((Date.now() - t) / 1000);
        if (d < 60) return d + 's ago';
        if (d < 3600) return Math.floor(d / 60) + 'm ago';
        if (d < 86400) return Math.floor(d / 3600) + 'h ago';
        if (d < 86400 * 7) return Math.floor(d / 86400) + 'd ago';
        var dt = new Date(t);
        return dt.toLocaleDateString(undefined, { month: 'short', day: 'numeric' });
    }

    var STEP_META = {
        2: ['Removed',                    '#24A556'],
        1: ['In progress now',            '#3B82F6'],
        3: ['Retrying',                   '#3B82F6'],
        4: ['Retrying',                   '#3B82F6'],
        5: ['Broker requested more info', '#2563EB'],
        0: ['Scheduled',                  '#878C91']
    };

    // Derive the host from a broker slug, mirroring the PHP pd_broker_host
    // logic so the JS-rendered rows match the SSR rows visually.
    function brokerHost(slug, siteUrl) {
        if (siteUrl) {
            try {
                var h = new URL(siteUrl).host;
                if (h) return h.replace(/^\./, '');
            } catch (e) {}
        }
        var sfxs = ['com','org','net','edu','gov','info','biz','io','ai','tv','co','us'];
        for (var i = 0; i < sfxs.length; i++) {
            var s = sfxs[i];
            if (slug.length > s.length && slug.slice(-s.length) === s) {
                return slug.slice(0, -s.length) + '.' + s;
            }
        }
        return slug;
    }
    function faviconUrl(host) {
        return 'https://www.google.com/s2/favicons?domain=' + encodeURIComponent(host) + '&sz=64';
    }

    function rebuildRecent(items) {
        var ul = panel.querySelector('[data-pd-recent]');
        if (!ul) return;
        var sig = items.map(function (r) { return r.id + ':' + r.step + ':' + r.updated_at; }).join('|');
        if (sig === lastRecentSig) return;
        lastRecentSig = sig;
        var html = '';
        for (var i = 0; i < items.length; i++) {
            var r = items[i];
            var meta = STEP_META[r.step] || STEP_META[0];
            var host = brokerHost(r.target_domain, r.site_url || null);
            var url = r.site_url || ('https://' + host + '/');
            html += '<li class="flex items-center gap-[14px] py-[12px]">';
            html +=   '<div class="shrink-0 w-[32px] h-[32px] rounded-[8px] bg-[#F4F5F7] flex items-center justify-center overflow-hidden">';
            html +=     '<img src="' + faviconUrl(host) + '" alt="" width="20" height="20" loading="lazy" class="w-[20px] h-[20px]" onerror="this.style.display=\'none\'"/>';
            html +=   '</div>';
            html +=   '<div class="flex-1 min-w-0">';
            html +=     '<div class="flex items-center gap-[8px]">';
            html +=       '<span class="shrink-0 w-[6px] h-[6px] rounded-full" style="background:' + meta[1] + '" title="' + escapeHTML(meta[0]) + '"></span>';
            html +=       '<div class="text-[14px] font-semibold text-[#010205] truncate">' + escapeHTML(r.target_domain) + '</div>';
            html +=     '</div>';
            html +=     '<a href="' + escapeHTML(url) + '" target="_blank" rel="noopener noreferrer" class="text-[12px] text-[#5B5F66] hover:text-[#24A556] truncate block mt-[1px]" title="' + escapeHTML(url) + '">';
            html +=       escapeHTML(host) + ' <span class="text-[#9CA3AF]">&middot;</span> <span class="text-[#9CA3AF]">' + escapeHTML(meta[0]) + '</span>';
            html +=     '</a>';
            html +=   '</div>';
            html +=   '<div class="shrink-0 text-[11px] text-[#878C91] tabular-nums">' + timeAgoFromISO(r.updated_at) + '</div>';
            html += '</li>';
        }
        ul.innerHTML = html;
    }

    function showLive(state) {
        var t = panel.querySelector('[data-pd-live-text]');
        var dot = panel.querySelector('[data-pd-live-indicator] span:first-child');
        if (!t || !dot) return;
        if (state === 'live')   { t.textContent = 'Live';     dot.style.background = '#24A556'; }
        if (state === 'paused') { t.textContent = 'Paused';   dot.style.background = '#9CA3AF'; }
        if (state === 'error')  { t.textContent = 'Retrying'; dot.style.background = '#F59E0B'; }
    }

    function tick() {
        if (inflight || document.hidden) { scheduleNext(); return; }
        inflight = true;
        var ctrl = window.AbortController ? new AbortController() : null;
        var to = setTimeout(function () { ctrl && ctrl.abort(); }, 12000);
        fetch('/api/journey_status', {
            credentials: 'same-origin',
            signal: ctrl ? ctrl.signal : undefined,
            headers: { 'Accept': 'application/json' }
        }).then(function (r) {
            clearTimeout(to);
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json();
        }).then(function (data) {
            if (!data || !data.ok) throw new Error('bad payload');
            var c = data.counts || {};
            setText('[data-pd-count="done"]',        fmtNum(c.done));
            setText('[data-pd-count="done_24h"]',    fmtNum(c.done_24h));
            setText('[data-pd-count="queued"]',      fmtNum((Number(c.queued)||0) + (Number(c.failed)||0) + (Number(c.not_impl)||0)));
            setText('[data-pd-count="missing_pii"]', fmtNum(c.missing_pii));
            setText('[data-pd-pct]', (data.pct_done || 0) + '%');
            setDonut(data.pct_done || 0);
            rebuildRecent(data.recent || []);
            showLive('live');
            nextDelay = INTERVAL_OK;
        }).catch(function () {
            showLive('error');
            nextDelay = INTERVAL_ERR;
        }).then(function () {
            inflight = false;
            scheduleNext();
        });
    }

    function scheduleNext() { setTimeout(tick, nextDelay); }

    document.addEventListener('visibilitychange', function () {
        showLive(document.hidden ? 'paused' : 'live');
    });

    setTimeout(tick, 4000);
})();
</script>
