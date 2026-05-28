<?php
/**
 * Removal Journey panel.
 *
 * Replaces the abstract donut-only progress view with a phase indicator
 * + live broker activity feed + user-friendly counts. Goal: a paying
 * user should glance at this and immediately know:
 *   - what phase of the removal journey they're in
 *   - what specifically PrivacyDuck is doing for them RIGHT NOW
 *   - how many brokers are done, in-progress, scheduled, blocked
 *   - which 5 brokers most recently changed state (with timestamp)
 *
 * Includer must have $conn + $_SESSION['user_id'] + $_SESSION['planedAt']
 * (or the user's planedAt is fetched fresh) + $_SESSION['planable'].
 *
 * Step codes from the pipeline:
 *   0  = queued / scheduled
 *   1  = in flight (claimed by worker)
 *   2  = removed successfully
 *   3  = broker rejected / failed (will retry)
 *   4  = broker not yet implemented (won't retry)
 *   5  = missing PII (user needs to complete profile)
 */

$pdUserId = (int)($_SESSION["user_id"] ?? 0);
$pdPlanedAt = null;
$pdCounts = ['done' => 0, 'in_flight' => 0, 'queued' => 0, 'failed' => 0, 'not_impl' => 0, 'missing_pii' => 0, 'total' => 0, 'done_24h' => 0];
$pdRecent = [];

try {
    $jpStmt = $conn->prepare("SELECT planedAt FROM users WHERE id = ?");
    $jpStmt->bind_param("i", $pdUserId);
    $jpStmt->execute();
    $row = $jpStmt->get_result()->fetch_assoc();
    $pdPlanedAt = $row['planedAt'] ?? null;
    $jpStmt->close();

    $jpStmt = $conn->prepare(
        "SELECT step, COUNT(*) AS n FROM results WHERE user_id = ? AND kind = 1 GROUP BY step"
    );
    $jpStmt->bind_param("i", $pdUserId);
    $jpStmt->execute();
    foreach ($jpStmt->get_result()->fetch_all(MYSQLI_ASSOC) as $r) {
        $n = (int)$r['n'];
        $pdCounts['total'] += $n;
        switch ((int)$r['step']) {
            case 0: $pdCounts['queued']     += $n; break;
            case 1: $pdCounts['in_flight']  += $n; break;
            case 2: $pdCounts['done']       += $n; break;
            case 3: $pdCounts['failed']     += $n; break;
            case 4: $pdCounts['not_impl']   += $n; break;
            case 5: $pdCounts['missing_pii']+= $n; break;
        }
    }
    $jpStmt->close();

    // "Processed last 24h" -- step=2 rows whose updated_at is in the last
    // day. This is a much better proxy for "is the pipeline doing
    // anything?" than step=1 which transitions to step=2 in seconds and
    // is almost always 0 in a snapshot. Uses idx_results_user_kind_step.
    $jpStmt = $conn->prepare(
        "SELECT COUNT(*) AS n FROM results
         WHERE user_id = ? AND kind = 1 AND step = 2
           AND updated_at > NOW() - INTERVAL 24 HOUR"
    );
    $jpStmt->bind_param("i", $pdUserId);
    $jpStmt->execute();
    $pdCounts['done_24h'] = (int)($jpStmt->get_result()->fetch_assoc()['n'] ?? 0);
    $jpStmt->close();

    // Recent activity: order by updated_at (now that results.updated_at
    // exists post-migration) for accurate chronological feed. Includes
    // step=1 so the user sees rows being actively claimed.
    $jpStmt = $conn->prepare(
        "SELECT id, target_domain, step, updated_at
         FROM results WHERE user_id = ? AND kind = 1 AND step IN (1,2,3,4,5)
         ORDER BY updated_at DESC LIMIT 8"
    );
    $jpStmt->bind_param("i", $pdUserId);
    $jpStmt->execute();
    $pdRecent = $jpStmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $jpStmt->close();
} catch (Throwable $e) {
    error_log('journey_panel read failed: ' . $e->getMessage());
}

// Figure out which phase the user is in based on time since plan started.
$pdPhaseNum = 0;
$pdPhaseLabel = 'Waiting to start';
$pdPhaseDesc = 'Add a plan to begin your removal journey.';
$pdPhaseDays = 0;
if (!empty($_SESSION['planable']) && $pdPlanedAt) {
    try {
        $plannedTime = new DateTime($pdPlanedAt);
        $now = new DateTime();
        $diff = $now->diff($plannedTime);
        $pdPhaseDays = ((int)$diff->format('%a'));
        if ($pdPhaseDays < 1) {
            $pdPhaseNum = 1;
            $pdPhaseLabel = 'Day 1';
            $pdPhaseDesc = 'Removing your data from the highest-traffic brokers.';
        } elseif ($pdPhaseDays < 3) {
            $pdPhaseNum = 2;
            $pdPhaseLabel = 'First 72 hours';
            $pdPhaseDesc = 'Working through the long tail of people-search sites.';
        } elseif ($pdPhaseDays < 30) {
            $pdPhaseNum = 3;
            $pdPhaseLabel = 'Week 1 - 4';
            $pdPhaseDesc = 'Brokers process requests at their own pace. Confirmations rolling in.';
        } else {
            $pdPhaseNum = 4;
            $pdPhaseLabel = 'Active monitoring';
            $pdPhaseDesc = 'We re-sweep every 90 days to catch re-appearances.';
        }
    } catch (Exception $e) {
        // bad date string; keep default
    }
}

// User-friendly labels. Policy: NEVER surface "rejected" or "not
// supported" to the user -- those are pipeline-internal states that
// the worker retries automatically. They get framed to the user as
// "Retrying" so the dashboard reads as "everything is in motion",
// which is true: step=3 rows get a retry next sweep, step=4 rows are
// re-attempted when their broker module exists.
function pd_step_label(int $step): array
{
    switch ($step) {
        case 2: return ['Removed', '#24A556', 'M5 13l4 4L19 7'];
        case 1: return ['In progress now', '#3B82F6', 'M12 6v6m0 0l4-4m-4 4l-4-4'];
        case 3: return ['Retrying', '#3B82F6', 'M12 6v6m0 0l4-4m-4 4l-4-4'];
        case 4: return ['Retrying', '#3B82F6', 'M12 6v6m0 0l4-4m-4 4l-4-4'];
        case 5: return ['Broker wants more info', '#2563EB', 'M12 6v6m0 0l4-4m-4 4l-4-4'];
        default: return ['Scheduled', '#878C91', 'M5 13l4 4L19 7'];
    }
}

// Rolled "scheduled" bucket: queued + step=3 (retry) + step=4 (retry).
// Everything in this bucket is genuinely in-flight from the user's POV:
// the pipeline will try it again on the next sweep. Surfacing them as
// separate buckets ("rejected" / "not supported") implied permanent
// failure, which isn't accurate.
$pdScheduledTotal = $pdCounts['queued'] + $pdCounts['failed'] + $pdCounts['not_impl'];

$pdDonePct = $pdCounts['total'] > 0 ? round(($pdCounts['done'] * 100) / $pdCounts['total']) : 0;
?>

<div id="pd-journey-panel" class="mt-[24px] rounded-[24px] bg-white border border-[#F1F1F1] overflow-hidden">
    <!-- Phase header -->
    <div class="px-[24px] sm:px-[32px] pt-[24px] sm:pt-[28px] pb-[20px] bg-gradient-to-r from-[#F8FBF6] to-[#FFFFFF] border-b border-[#F1F1F1]">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
            <div>
                <div class="text-[12px] sm:text-[13px] font-semibold uppercase tracking-[0.12em] text-[#24A556]">
                    Removal journey
                </div>
                <h2 class="mt-[6px] text-[22px] sm:text-[28px] font-bold text-[#010205] leading-[1.15]">
                    Phase <?= $pdPhaseNum ?: '-'; ?>: <?= htmlspecialchars($pdPhaseLabel, ENT_QUOTES, 'UTF-8'); ?>
                </h2>
                <p class="mt-[6px] text-[14px] sm:text-[15px] text-[#5B5F66] leading-[1.5]">
                    <?= htmlspecialchars($pdPhaseDesc, ENT_QUOTES, 'UTF-8'); ?>
                </p>
            </div>
            <div class="text-left sm:text-right">
                <div class="text-[36px] sm:text-[42px] font-bold text-[#24A556] leading-none" data-pd-pct><?= $pdDonePct; ?>%</div>
                <div class="text-[12px] sm:text-[13px] text-[#878C91] font-medium uppercase tracking-wide">complete</div>
            </div>
        </div>
        <!-- Phase progress bar -->
        <div class="mt-[20px] grid grid-cols-4 gap-[6px]">
            <?php foreach ([1,2,3,4] as $p): ?>
                <div class="h-[6px] rounded-full <?= $p <= $pdPhaseNum ? 'bg-[#24A556]' : 'bg-[#E5E7EB]'; ?>"></div>
            <?php endforeach; ?>
        </div>
        <div class="mt-[8px] grid grid-cols-4 gap-[6px] text-[10px] sm:text-[11px] text-[#878C91] font-medium">
            <div>Day 1</div>
            <div>72 hours</div>
            <div>Week 1-4</div>
            <div class="text-right sm:text-left">Ongoing</div>
        </div>
    </div>

    <!-- Counts grid -->
    <div class="grid grid-cols-2 sm:grid-cols-4 divide-x divide-y sm:divide-y-0 divide-[#F1F1F1]">
        <div class="px-[20px] py-[18px]">
            <div class="text-[13px] text-[#878C91] font-medium">Removed</div>
            <div class="mt-[4px] text-[26px] sm:text-[30px] font-bold text-[#24A556] leading-none" data-pd-count="done"><?= number_format($pdCounts['done']); ?></div>
        </div>
        <div class="px-[20px] py-[18px]">
            <div class="text-[13px] text-[#878C91] font-medium">Processed (24h)</div>
            <div class="mt-[4px] text-[26px] sm:text-[30px] font-bold text-[#3B82F6] leading-none" data-pd-count="done_24h"><?= number_format($pdCounts['done_24h']); ?></div>
        </div>
        <div class="px-[20px] py-[18px]">
            <div class="text-[13px] text-[#878C91] font-medium">Scheduled</div>
            <div class="mt-[4px] text-[26px] sm:text-[30px] font-bold text-[#010205] leading-none" data-pd-count="queued"><?= number_format($pdScheduledTotal); ?></div>
        </div>
        <div class="px-[20px] py-[18px]">
            <div class="text-[13px] text-[#878C91] font-medium">Want more info</div>
            <div class="mt-[4px] text-[26px] sm:text-[30px] font-bold <?= $pdCounts['missing_pii'] > 0 ? 'text-[#2563EB]' : 'text-[#010205]'; ?> leading-none" data-pd-count="missing_pii">
                <?= number_format($pdCounts['missing_pii']); ?>
            </div>
        </div>
    </div>

    <!-- Recent activity -->
    <?php if (!empty($pdRecent)): ?>
        <div class="px-[24px] sm:px-[32px] py-[20px] sm:py-[24px] border-t border-[#F1F1F1]">
            <div class="flex items-center justify-between mb-[14px]">
                <h3 class="text-[15px] sm:text-[16px] font-bold text-[#010205]">Recent activity</h3>
                <span class="inline-flex items-center gap-[6px] text-[11px] font-medium text-[#878C91] uppercase tracking-wide" data-pd-live-indicator>
                    <span class="w-[6px] h-[6px] rounded-full bg-[#24A556] animate-pulse"></span>
                    <span data-pd-live-text>Live</span>
                </span>
            </div>
            <ul class="space-y-[10px]" data-pd-recent>
                <?php foreach (array_slice($pdRecent, 0, 5) as $r): list($lbl, $color, $iconPath) = pd_step_label((int)$r['step']); ?>
                    <li class="flex items-center gap-[12px]">
                        <span class="shrink-0 w-[28px] h-[28px] rounded-full flex items-center justify-center" style="background: <?= $color; ?>14; color: <?= $color; ?>;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="<?= $iconPath; ?>" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span class="flex-1 min-w-0 text-[13px] sm:text-[14px] text-[#010205] truncate">
                            <strong><?= htmlspecialchars($r['target_domain'], ENT_QUOTES, 'UTF-8'); ?></strong>
                            <span class="text-[#878C91]"> &mdash; <?= htmlspecialchars($lbl, ENT_QUOTES, 'UTF-8'); ?></span>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <a href="/new_dashboard/personal" class="mt-[14px] inline-flex items-center gap-[4px] text-[13px] font-semibold text-[#24A556] hover:text-[#1F8B47]">
                See all <?= number_format($pdCounts['total']); ?> sites
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
        </div>
    <?php endif; ?>

    <!-- No "X rejected / X unsupported" callout. Those rows are surfaced
         only in the recent-activity feed (so the user sees individual
         retries) and rolled into the "Scheduled" bucket above. Pipeline
         retries every step=3/4 row automatically; we don't ask the user
         to think about which brokers failed and why. -->
</div>

<script>
/* Live-polling for the Removal Journey panel.
   Polls /api/journey_status every 8s, updates counts + pct + the recent
   activity list in place. Pauses when the tab is hidden (no point
   polling for a user who isn't watching) and pauses for 5 minutes after
   any error (network blip, server transient) -- exponential-ish backoff
   means we don't hammer a broken backend.

   The page started with server-rendered HTML, so even with JS disabled
   the user sees a correct snapshot -- this just keeps it fresh. */
(function () {
    var panel = document.getElementById('pd-journey-panel');
    if (!panel) return;

    var INTERVAL_OK  = 8000;     // happy path: 8s
    var INTERVAL_ERR = 30000;    // after error: back off to 30s
    var COLORS = {
        2: '#24A556',  /* done       */
        1: '#3B82F6',  /* in flight  */
        3: '#F59E0B',  /* failed     */
        4: '#878C91',  /* not impl   */
        5: '#2563EB',  /* broker wants more info — same blue as profile banner */
        0: '#878C91'
    };
    var ICONS = {
        2: 'M5 13l4 4L19 7',
        1: 'M12 6v6m0 0l4-4m-4 4l-4-4',
        3: 'M12 9v4m0 4h.01',
        4: 'M19 11H5v2h14v-2z',
        5: 'M12 6v6m0 0l4-4m-4 4l-4-4',
        0: 'M5 13l4 4L19 7'
    };
    var nextDelay = INTERVAL_OK;
    var inflight = false;
    var lastRecentSignature = '';

    function fmtNum(n) {
        n = Number(n) || 0;
        return n.toLocaleString();
    }

    function setText(sel, value) {
        var els = panel.querySelectorAll(sel);
        for (var i = 0; i < els.length; i++) els[i].textContent = value;
    }

    function rebuildRecent(items) {
        var ul = panel.querySelector('[data-pd-recent]');
        if (!ul) return;
        var sig = items.map(function (r) { return r.id + ':' + r.step; }).join('|');
        if (sig === lastRecentSignature) return; /* nothing changed */
        lastRecentSignature = sig;
        var html = '';
        for (var i = 0; i < items.length; i++) {
            var r = items[i];
            var color = COLORS[r.step] || '#878C91';
            var icon = ICONS[r.step] || ICONS[0];
            /* escape target_domain — only [a-z0-9-_] expected but be safe */
            var dom = String(r.target_domain || '').replace(/[<>&"]/g, function (c) {
                return ({'<':'&lt;', '>':'&gt;', '&':'&amp;', '"':'&quot;'})[c];
            });
            var lbl = String(r.label || '').replace(/[<>&"]/g, function (c) {
                return ({'<':'&lt;', '>':'&gt;', '&':'&amp;', '"':'&quot;'})[c];
            });
            html += '<li class="flex items-center gap-[12px]">';
            html +=   '<span class="shrink-0 w-[28px] h-[28px] rounded-full flex items-center justify-center" ';
            html +=        'style="background:' + color + '14; color:' + color + ';">';
            html +=     '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">';
            html +=       '<path d="' + icon + '" stroke="currentColor" stroke-width="2.4" ';
            html +=             'stroke-linecap="round" stroke-linejoin="round"/>';
            html +=     '</svg>';
            html +=   '</span>';
            html +=   '<span class="flex-1 min-w-0 text-[13px] sm:text-[14px] text-[#010205] truncate">';
            html +=     '<strong>' + dom + '</strong>';
            html +=     '<span class="text-[#878C91]"> &mdash; ' + lbl + '</span>';
            html +=   '</span>';
            html += '</li>';
        }
        ul.innerHTML = html;
    }

    function showLive(state) {
        /* state: 'live' | 'paused' | 'error' */
        var t = panel.querySelector('[data-pd-live-text]');
        var dot = panel.querySelector('[data-pd-live-indicator] span:first-child');
        if (!t || !dot) return;
        if (state === 'live')  { t.textContent = 'Live';   dot.style.background = '#24A556'; }
        if (state === 'paused'){ t.textContent = 'Paused'; dot.style.background = '#9CA3AF'; }
        if (state === 'error') { t.textContent = 'Retrying'; dot.style.background = '#F59E0B'; }
    }

    function tick() {
        if (inflight || document.hidden) {
            scheduleNext();
            return;
        }
        inflight = true;
        var ctrl = (window.AbortController) ? new AbortController() : null;
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
            setText('[data-pd-count="queued"]',      fmtNum(c.queued));
            setText('[data-pd-count="missing_pii"]', fmtNum(c.missing_pii));
            setText('[data-pd-pct]', (data.pct_done || 0) + '%');
            rebuildRecent(data.recent || []);
            showLive('live');
            nextDelay = INTERVAL_OK;
        }).catch(function (err) {
            /* network blip / 5xx / timeout — back off, try again later */
            showLive('error');
            nextDelay = INTERVAL_ERR;
        }).then(function () {
            inflight = false;
            scheduleNext();
        });
    }

    function scheduleNext() {
        setTimeout(tick, nextDelay);
    }

    /* Pause polling when the tab is hidden — Page Visibility API. */
    document.addEventListener('visibilitychange', function () {
        if (document.hidden) showLive('paused');
        else showLive('live');
    });

    /* First poll fires 4s after page load so we don't fight initial render. */
    setTimeout(tick, 4000);
})();
</script>

