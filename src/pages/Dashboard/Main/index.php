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
<!-- Greeting bar. Bigger, more confident heading + a status pill that
     gives the page weight (was previously a small h1 floating alone
     above the bright journey panel, which felt orphaned). -->
<div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-[12px]" data-reveal="fade">
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
        <?php else: ?>
            <button onclick="navigateTo('/dashboard/plans')"
                class="pd-btn-press pd-shine inline-flex items-center bg-gradient-to-r from-[#77B248] to-[#24A556] px-[16px] py-[8px] rounded-full gap-[6px] text-white font-semibold text-[13px] sm:text-[14px]">
                <?php require(BASEPATH . "/src/common/svgs/dashboard/sidebar/fixed_menu_protect_user.php"); ?>
                <span>Protect Yourself</span>
            </button>
        <?php endif; ?>
    </div>
</div>

<?php if (!$pdIsPaid): ?>
    <!-- UNPAID USERS: exposure-scare card with the scan count + CTA. Drives
         conversion. Paid users skip this entirely and go straight to the
         journey panel, which has real numbers instead of a 0 placeholder. -->
    <div class="mt-[16px] lg:mt-[42px]" data-reveal data-reveal-delay="60">
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
// Removal Journey panel: the SINGLE source of truth for removal progress.
// Renders for everyone -- unpaid users see "Waiting to start", paid users
// see live counts + recent activity + phase indicator. Replaces all of
// the previous status pills.
require_once(BASEPATH . "/src/pages/Dashboard/Main/journey_panel.php");
?>

<?php if (!$pdIsPaid): ?>
    <!-- Donut chart: kept for UNPAID users as a visual exposure indicator
         (they haven't started removal, so it's a "look how much there is"
         shock visual, not a progress meter). Paid users see only the
         journey panel above, which has the accurate live percentage. -->
    <div class="hidden lg:block">
        <?php require_once(BASEPATH . "/src/pages/Dashboard/Main/progress/index.php"); ?>
    </div>
    <div class="block lg:hidden">
        <?php require_once(BASEPATH . "/src/pages/Dashboard/Main/progress/mobile.php"); ?>
    </div>
<?php endif; ?>

<div data-reveal data-reveal-delay="120"
    class="pd-card-lift relative mt-[24px] rounded-[24px] bg-white border border-[#F1F1F1] overflow-hidden">
    <?php require_once(BASEPATH . "/src/pages/Dashboard/Main/detail_item.php"); ?>
</div>
<div class="mt-[24px]" data-reveal data-reveal-delay="180">
    <?php require_once(BASEPATH . "/src/pages/Dashboard/Main/databrokers/index.php"); ?>
</div>
<div class="mt-[24px] rounded-[24px] bg-white border border-[#F1F1F1] overflow-hidden" data-reveal data-reveal-delay="240">
    <?php require_once(BASEPATH . "/src/pages/Dashboard/Main/result_sites.php"); ?>
</div>

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
