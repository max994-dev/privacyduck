<?php
/**
 * Face Removal status card.
 *
 * Reads REAL pipeline status from results table (kind=4, target_domain='pimeyescom')
 * rather than the vanity `users.face_manual_removed` flag which was never
 * driven by anything other than an admin-clicked checkbox.
 *
 * Step values (results.step) map to user-facing status:
 *   0 -> Queued        (pending dispatch, will run on next pipeline tick)
 *   1 -> In progress   (worker is currently processing -- shouldn't sit here long)
 *   2 -> Done          (PimEyes opt-out submitted + face search complete)
 *   3 -> Needs retry   (broker raised; dashboard_bootstrap resets to 0 next load)
 *   5 -> Missing info  (broker wanted a field that wasn't in dataRow)
 *   no row             (no face image uploaded yet -- show upload prompt)
 *
 * Includer must have $conn (or we open one) and $_SESSION["user_id"] available.
 */
require_once BASEPATH . '/src/common/odoo_removal_sync.php';

$faceImage = '';
$faceStatusStep = -1; // -1 = no kind=4 row exists yet
$faceUpdatedAt  = null;

$_conn = isset($conn) && $conn instanceof mysqli ? $conn : getDBConnection();
odoo_removal_ensure_columns($_conn);

$stmt = $_conn->prepare("SELECT url, face_manual_removed FROM users WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$userRow = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($userRow && !empty($userRow["url"])) {
    $faceImage = "/assets/uploads/specialinfo/" . rawurlencode((string) $userRow["url"]);
}

// Manual override still respected (admin can mark a user as done from a
// support tool even if the automated pipeline hasn't reached them).
$manualOverrideDone = $userRow && ((int) ($userRow["face_manual_removed"] ?? 0)) === 1;

$stmt = $_conn->prepare(
    "SELECT step, updated_at FROM results
     WHERE user_id = ? AND kind = 4 AND target_domain = 'pimeyescom'
     ORDER BY id DESC LIMIT 1"
);
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$resRow = $stmt->get_result()->fetch_assoc();
$stmt->close();
if ($resRow) {
    $faceStatusStep = (int) $resRow["step"];
    $faceUpdatedAt  = $resRow["updated_at"] ?? null;
}

// Only close the connection if WE opened it.
if (!isset($conn)) {
    $_conn->close();
}

// Map step -> (label, text color, bg color, border color, description)
$displayStep = $manualOverrideDone ? 2 : $faceStatusStep;
switch ($displayStep) {
    case 2:
        $statusLabel = "Removed";
        $statusTextColor = "#1A7F40"; $statusBgColor = "#ECFFF1"; $statusBorderColor = "#BFE7C7";
        $statusDescription = "PimEyes opt-out submitted. Face indexed results have been requested for removal.";
        break;
    case 1:
        $statusLabel = "In progress";
        $statusTextColor = "#9B6B00"; $statusBgColor = "#FFF7E6"; $statusBorderColor = "#FFE1A6";
        $statusDescription = "Your face image is being processed right now.";
        break;
    case 3:
    case 5:
        $statusLabel = "Will retry";
        $statusTextColor = "#9B6B00"; $statusBgColor = "#FFF7E6"; $statusBorderColor = "#FFE1A6";
        $statusDescription = "Previous attempt didn't complete. We'll automatically retry on the next pipeline run.";
        break;
    case 0:
        $statusLabel = "Queued";
        $statusTextColor = "#1D4FBF"; $statusBgColor = "#E8F0FC"; $statusBorderColor = "#C5D7F5";
        $statusDescription = "Your face image is in the queue. Processing usually starts within a few minutes.";
        break;
    default:
        // step = -1, no dispatch row exists
        if ($faceImage !== '') {
            $statusLabel = "Not dispatched";
            $statusTextColor = "#5B5F66"; $statusBgColor = "#F4F5F7"; $statusBorderColor = "#E5E7EB";
            $statusDescription = "We have your face image but haven't queued it yet. Refresh in a few seconds.";
        } else {
            $statusLabel = "Awaiting upload";
            $statusTextColor = "#5B5F66"; $statusBgColor = "#F4F5F7"; $statusBorderColor = "#E5E7EB";
            $statusDescription = "Upload a clear photo of your face on the Edit Info page to begin face removal.";
        }
        break;
}
?>

<div class="flex items-start justify-between gap-[12px]">
    <div>
        <h3 class="font-bold text-[16px] sm:text-[18px] leading-[130%] text-[#010205]">Face Removal</h3>
        <p class="mt-[4px] text-[12px] sm:text-[13px] text-[#5B5F66]">
            We use your face image to request opt-out from PimEyes and other face-search services.
        </p>
    </div>
</div>

<div class="mt-[14px] rounded-[16px] border border-[#EAECEF] bg-white p-[16px] md:p-[20px]">
    <div class="flex flex-col md:flex-row gap-[16px] items-start">
        <div class="w-full md:w-[180px] shrink-0">
            <?php if ($faceImage !== ''): ?>
                <img src="<?= htmlspecialchars($faceImage, ENT_QUOTES, 'UTF-8') ?>" alt="Your face image"
                    class="w-full max-w-[180px] aspect-square rounded-[12px] object-cover border border-[#EAECEF]" />
                <p class="mt-[8px] text-[11px] text-[#878C91]">
                    Your uploaded face image.
                    <a href="/dashboard/editinfo" class="text-[#24A556] underline underline-offset-[2px] ml-[2px]">Change</a>
                </p>
            <?php else: ?>
                <div class="w-full max-w-[180px] aspect-square rounded-[12px] border border-dashed border-[#D6D6D6] bg-[#FAFAFA] flex flex-col items-center justify-center text-[#9B9B9C] gap-[6px]">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="12" cy="8" r="4" stroke="#9B9B9C" stroke-width="1.5"/>
                        <path d="M3 21c0-4 4-7 9-7s9 3 9 7" stroke="#9B9B9C" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <span class="text-[11px] text-center px-[6px]">No image yet</span>
                </div>
                <p class="mt-[8px] text-[11px] text-[#878C91]">
                    <a href="/dashboard/editinfo" class="text-[#24A556] underline underline-offset-[2px]">Upload</a>
                    one to begin.
                </p>
            <?php endif; ?>
        </div>
        <div class="flex-1 min-w-0">
            <span id="face_status_pill" class="inline-flex items-center gap-[6px] px-[10px] py-[4px] rounded-full text-[12px] font-semibold border"
                  style="color:<?= $statusTextColor ?>; background:<?= $statusBgColor ?>; border-color:<?= $statusBorderColor ?>;">
                <span class="w-[6px] h-[6px] rounded-full" style="background:<?= $statusTextColor ?>;"></span>
                <?= htmlspecialchars($statusLabel, ENT_QUOTES, 'UTF-8') ?>
            </span>
            <p id="face_status_description" class="mt-[10px] text-[13px] text-[#374151] leading-[1.5]">
                <?= htmlspecialchars($statusDescription, ENT_QUOTES, 'UTF-8') ?>
            </p>
            <?php if ($faceUpdatedAt): ?>
                <p class="mt-[6px] text-[11px] text-[#878C91]">
                    Last updated: <?= htmlspecialchars((string) $faceUpdatedAt, ENT_QUOTES, 'UTF-8') ?> (UTC)
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>
