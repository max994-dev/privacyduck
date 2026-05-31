<?php
// Profile-completeness banner: only renders for PAID users with one or
// more empty PII fields. Frames the prompt as additive ("unlocks more
// brokers") -- removal IS running regardless. Silent no-op when
// complete or for unpaid users.
require_once BASEPATH . '/src/pages/Dashboard/Main/profile_banner.php';

// Removal-count computation has moved INTO journey_panel.php where the
// data is actually consumed. Previously this file ran a duplicate
// GROUP BY at the top + journey_panel ran the same one again -- two
// full table scans per dashboard load against an unindexed 875K-row
// `results` table (~2-5 seconds total). Composite index
// idx_results_user_kind_step was added the same day; even with the
// index, running the query twice is wasteful when journey_panel is
// the only consumer. Removed the upfront query entirely.

$pdIsPaid = !empty($_SESSION['plan_id']) && !empty($_SESSION['planable']);

// Scan-count card (free users only): how many places we found their data.
// For paid users this is replaced by the journey panel below, which gives
// them genuine progress numbers rather than just an exposure scare metric.
$pdScanCount = 0;
if (!$pdIsPaid) {
    $pdScanPath = BASEPATH . '/assets/uploads/' . $_SESSION['user_id'] . '/scan';
    if (is_dir($pdScanPath)) {
        $pdScanCount = max(0, count(scandir($pdScanPath)) - 2);
    }
}
?>
<!-- Greeting bar. Above-the-fold = no data-reveal: the IntersectionObserver
     occasionally doesn't fire for elements that are already in view at
     pageload (depends on browser caching state + observer init timing),
     leaving the greeting + scan card stuck at opacity:0. Empty 250px
     gap at the top of the dashboard until first scroll. Always-visible
     is the safer default for landing content. -->
<div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-[12px]">
    <div>
        <div class="text-[12px] sm:text-[13px] font-semibold uppercase tracking-[0.12em] text-[#5B5F66]">
            Dashboard
        </div>
        <h1 class="mt-[4px] font-bold text-[24px] sm:text-[30px] md:text-[34px] text-[#010205] leading-[1.15]">
            Welcome, <?php echo htmlspecialchars((string) ($_SESSION["fullName"] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
        </h1>
    </div>
    <div class="flex items-center gap-[10px]">
        <?php if ($pdIsPaid): ?>
            <span class="inline-flex items-center gap-[8px] rounded-full bg-[#E8F7EF] text-[#1A7F40] px-[14px] py-[7px] text-[12px] sm:text-[13px] font-semibold whitespace-nowrap">
                <span class="w-[7px] h-[7px] rounded-full bg-[#24A556]"></span>
                Plan active &mdash; removal running
            </span>
        <?php endif; ?>
        <?php // Unpaid users: no button here. The sticky header in
              // NewDashboard/index.php ALREADY renders "Protect Yourself"
              // in the top-right; rendering it again here was a duplicate
              // (visible in the 2026-05-29 customer screenshot). For paid
              // users we keep the status pill -- it's the symmetric
              // confirmation that there's something happening. ?>
    </div>
</div>

<?php if (!$pdIsPaid): ?>
    <!-- UNPAID USERS: exposure-scare card with the scan count + CTA. Drives
         conversion. Paid users skip this entirely and go straight to the
         journey panel, which has real numbers instead of a 0 placeholder.
         No data-reveal -- same above-the-fold reason as the greeting bar. -->
    <div class="mt-[16px] lg:mt-[42px]">
        <div class="pd-card-lift rounded-[30px] bg-[#FEFEFE] border border-[#F6F6F6] sm:flex justify-center items-center lg:block">
            <div class="px-[8px] py-[18px] sm:px-[18px] lg:flex lg:justify-between items-center">
                <div class="flex space-x-[8px] items-center">
                    <i class="fa-solid fa-circle-exclamation text-[#C00000] text-[16px] sm:text-[24px]"></i>
                    <h1 class="text-[#010205] text-[12px] sm:text-[16px] font-medium">
                        We haven&rsquo;t started removals yet.
                    </h1>
                    <h1 onclick="navigateTo('/dashboard/plans')" class="cursor-pointer text-[#24A556] text-[12px] sm:text-[16px] font-bold underline">
                        Protect yourself
                    </h1>
                </div>
                <div class="flex space-x-[6px] sm:space-x-[10px] items-center mt-[16px] lg:mt-0">
                    <h1 class="sm:px-[14px] sm:py-[7.5px] sm:bg-[#C00000] text-[#C00000] sm:text-white rounded-full font-semibold text-[12px] sm:text-[20px]">
                        <label id="scan_count"><?= (int) $pdScanCount; ?></label>
                        <span class="text-[10px] sm:text-[12px]">Profiles Found</span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php
// Removal Journey panel: hero (donut + phase stepper) + 14-day chart +
// 4-stat grid. (Recent activity mini-feed removed -- the full
// paginated broker table below replaces it.)
// No outer section header -- the panel is self-titled "Phase X".
// mt-[16px] gives breathing room without the giant 200px gap we had.
?>
<div class="mt-[16px]">
    <?php require_once(BASEPATH . "/src/pages/Dashboard/Main/journey_panel.php"); ?>
</div>

<?php if ($pdIsPaid): ?>
    <!-- Recognizable broker brands -- quick visual proof for the 12
         names a customer would actually know (Spokeo, MyLife, etc). -->
    <div id="all-broker-sites" class="mt-[16px] scroll-mt-[24px]">
        <?php require_once(BASEPATH . "/src/pages/Dashboard/Main/notable_brokers.php"); ?>
    </div>

<?php
// Face removal moved to its own dedicated page at /new_dashboard/face
// (sidebar nav item: "Face Removal", plan-only). Used to be an inline
// card here but the user wanted it as a separate section so it shows
// up prominently in the nav rather than buried below the broker table.
?>
<?php endif; ?>

<?php
// progress/index.php + progress/mobile.php intentionally NOT included
// anymore. They rendered an old donut + 3 stat cards (Your Removal /
// Privacy Risk Score / Requests Completed) that duplicated info already
// shown by journey_panel above. When a paid user's session was stale
// (plan_id not yet refreshed from DB after Stripe webhook), this block
// would render the unpaid view AND leave the journey panel showing zero
// progress -- producing the "I paid but nothing started" confusion seen
// in the 2026-05-29 customer screenshot. dashboard_bootstrap now
// refreshes plan_id from the DB on every load, so the journey panel
// shows the correct paid state immediately.
?>

<!-- ALL BROKERS table. No data-reveal -- this is primary content and
     must be visible immediately. The reveal animation was hiding it
     (opacity:0 + translateY(28px) until IntersectionObserver fires)
     and several customers thought the page ended after notable_brokers. -->
<div class="mt-[16px] rounded-[24px] bg-white border border-[#F1F1F1] overflow-hidden">
    <?php require_once(BASEPATH . "/src/pages/Dashboard/Main/result_sites.php"); ?>
</div>

<!-- Reference info below the primary content. Also no data-reveal --
     same reason; the observer was leaving these stuck at opacity:0. -->
<div class="pd-card-lift relative mt-[16px] rounded-[24px] bg-white border border-[#F1F1F1] overflow-hidden">
    <?php require_once(BASEPATH . "/src/pages/Dashboard/Main/detail_item.php"); ?>
</div>

<?php
// Removed the "Primary Scan / Face Scan" tabbed widget that lived in
// /Main/databrokers/index.php. It was a vestigial UI from an earlier
// design -- everything it showed (broker scan results, screenshots,
// face-removal stub) is already covered, more clearly, by the journey
// panel + notable_brokers card + the All Broker Sites table above.
// The file is left in place in case anything else references it; it's
// just no longer wired into the main dashboard.
?>

<script>
    /* Legacy callback: progress-bar JS in progress/index.php still calls
       main_isRemoval() on each tick. For paid users the status pill is
       gone, so this is now a no-op for them. For unpaid users it still
       updates the scare-card label. */
    function main_isRemoval() {
        const planable = <?php echo $pdIsPaid ? 'true' : 'false'; ?>;
        if (!planable) {
            const el = document.getElementById('main_removal_status');
            if (el) el.classList.remove('hidden');
        }
    }

    function inc_scan_count() {
        const scan_count = document.getElementById('scan_count');
        if (!scan_count) return; /* paid users no longer have this element */
        scan_count.innerHTML = JSON.parse(`<?php
            $path = BASEPATH . '/assets/uploads/' . $_SESSION['user_id'] . '/scan';
            if (is_dir($path)) {
                echo json_encode(scandir($path));
            } else {
                echo "[]";
            }
        ?>`).filter(v => v.length > 3).length;
    }
</script>
