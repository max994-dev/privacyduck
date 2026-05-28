<?php
// Profile-completeness banner: only renders for PAID users with one or
// more empty PII fields. Frames the prompt as additive ("unlocks more
// brokers") -- removal IS running regardless. Silent no-op when
// complete or for unpaid users.
require_once BASEPATH . '/src/pages/Dashboard/Main/profile_banner.php';

// Compute removal counts ONCE up here so we don't double-fetch later.
// We need them both for the unpaid-user "scan count" badge AND for any
// conditional rendering below.
$pdConn = getDBConnection();
$pdMainStmt = $pdConn->prepare(
    "SELECT step, COUNT(*) AS n FROM results WHERE user_id = ? AND kind = 1 GROUP BY step"
);
$pdMainStmt->bind_param("i", $_SESSION["user_id"]);
$pdMainStmt->execute();
$pdMainCounts = ['queued' => 0, 'in_flight' => 0, 'done' => 0, 'failed' => 0, 'not_impl' => 0, 'missing_pii' => 0, 'total' => 0];
foreach ($pdMainStmt->get_result()->fetch_all(MYSQLI_ASSOC) as $pdR) {
    $pdN = (int) $pdR['n'];
    $pdMainCounts['total'] += $pdN;
    switch ((int) $pdR['step']) {
        case 0: $pdMainCounts['queued']     += $pdN; break;
        case 1: $pdMainCounts['in_flight']  += $pdN; break;
        case 2: $pdMainCounts['done']       += $pdN; break;
        case 3: $pdMainCounts['failed']     += $pdN; break;
        case 4: $pdMainCounts['not_impl']   += $pdN; break;
        case 5: $pdMainCounts['missing_pii']+= $pdN; break;
    }
}
$pdMainStmt->close();

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
<div class="flex items-center justify-between xl:justify-normal" data-reveal="fade">
    <h1 class="font-semibold text-[16px] sm:text-[20px] md:text-[24px] text-[#010205] pointer-events-none">
        Welcome, <?php echo htmlspecialchars((string) ($_SESSION["fullName"] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
    </h1>
    <?php if (!$pdIsPaid) { ?>
        <button onclick="navigateTo('/dashboard/plans')"
            class="pd-btn-press pd-shine flex xl:hidden items-center bg-gradient-to-r from-[#77B248] to-[#24A556] px-[8px] md:px-[14px] py-[6px] md:py-[5px] rounded-full space-x-[2px]">
            <?php require(BASEPATH . "/src/common/svgs/dashboard/sidebar/fixed_menu_protect_user.php"); ?>
            <h1 class="text-[10px] md:text-[14px] text-white font-semibold tracking-[0.01em]">Protect Yourself</h1>
        </button>
    <?php } ?>
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
    class="pd-card-lift relative mt-[32px] rounded-[30px] bg-white/50 border border-[#F6F6F6] after:content-['Highly&nbsp;sensitive&nbsp;info'] after:absolute after:top-[-11.5px] after:right-0 after:bg-[#24A556] after:w-[143px] after:h-[23px] after:text-center after:text-[10px] after:text-white after:font-semibold after:rounded-full after:flex after:justify-center after:items-center">
    <?php require_once(BASEPATH . "/src/pages/Dashboard/Main/detail_item.php"); ?>
</div>
<div class="mt-[32px]" data-reveal data-reveal-delay="180">
    <?php require_once(BASEPATH . "/src/pages/Dashboard/Main/databrokers/index.php"); ?>
</div>
<div class="mt-[32px] rounded-[30px] bg-[#FFFFFFE3] border border-[#F6F6F6]" data-reveal data-reveal-delay="240">
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
