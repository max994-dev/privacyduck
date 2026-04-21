<?php
require_once BASEPATH . '/src/common/odoo_removal_sync.php';

$faceImage = '';
$faceRemovalDone = false;

$conn = getDBConnection();
odoo_removal_ensure_columns($conn);

$stmt = $conn->prepare("SELECT url, face_manual_removed FROM users WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();
$conn->close();

if ($row && !empty($row["url"])) {
    $faceImage = "/assets/uploads/specialinfo/" . rawurlencode((string) $row["url"]);
}

if ($row && isset($row["face_manual_removed"])) {
    $faceRemovalDone = ((int) $row["face_manual_removed"]) === 1;
}

$faceStatusLabel = $faceRemovalDone ? "Finished" : "Not started";
$faceStatusClass = $faceRemovalDone ? "text-[#24A556]" : "text-[#A67200]";
$faceStatusBgClass = $faceRemovalDone ? "bg-[#F2FBF5] border-[#CFEAD9]" : "bg-[#FFF9EE] border-[#F5DEB0]";
$faceStatusDescription = $faceRemovalDone
    ? "Face removal has been completed for this uploaded image."
    : "Face removal is still pending for this uploaded image.";
?>

<div class="flex items-start justify-between gap-[12px]">
    <div>
        <h1 class="font-bold text-[18px] sm:text-[22px] leading-[130%] text-[#010205]">Face Scan</h1>
        <p class="mt-[6px] text-[13px] text-[#5C5C5E]">Use your uploaded face image for face-search opt-out and manual face removal workflows.</p>
    </div>
</div>

<div class="mt-[14px] rounded-[16px] border border-[#E8E8E8] bg-white p-[16px] md:p-[22px]">
    <div class="flex flex-col md:flex-row gap-[14px] items-start">
        <div class="w-full md:w-[220px] shrink-0">
            <?php if ($faceImage !== ''): ?>
                <img src="<?= htmlspecialchars($faceImage, ENT_QUOTES, 'UTF-8') ?>" alt="Face"
                    class="w-full max-w-[220px] rounded-[14px] object-cover border border-[#D6D6D6]" />
                <p class="mt-[8px] text-[12px] text-[#7E7E7E] font-medium">Your uploaded face image</p>
            <?php else: ?>
                <div class="w-full max-w-[220px] h-[220px] rounded-[14px] border border-dashed border-[#CFCFCF] bg-[#FAFAFA] flex items-center justify-center text-[#9B9B9C]">
                    No face image uploaded yet.
                </div>
                <p class="mt-[8px] text-[12px] text-[#7E7E7E] font-medium">Add one via <a href="/dashboard/editinfo" class="text-[#24A556] underline underline-offset-[2px]">Edit your info</a>.</p>
            <?php endif; ?>
        </div>
        <div class="flex-1 min-w-0">
            <div id="face_status_card" class="rounded-[12px] border p-[14px] <?= $faceStatusBgClass ?>">
                <div class="min-w-0">
                    <h3 class="text-[14px] font-semibold text-[#010205]">Face removal status</h3>
                    <div class="mt-[8px] inline-flex items-center gap-[7px] rounded-full bg-white/80 px-[10px] py-[5px] border border-white">
                        <span id="face_pending_icon" class="<?= $faceRemovalDone ? 'hidden' : 'inline-flex' ?>">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 7V12L15 14" stroke="#A67200" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <circle cx="12" cy="12" r="9" stroke="#A67200" stroke-width="2" />
                            </svg>
                        </span>
                        <span id="face_done_icon" class="<?= $faceRemovalDone ? 'inline-flex' : 'hidden' ?>">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M7 12L10.4 15.4L17 8.8" stroke="#24A556" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                                <circle cx="12" cy="12" r="9" stroke="#24A556" stroke-width="2" />
                            </svg>
                        </span>
                        <span id="face_removal_label" class="text-[12px] font-semibold <?= $faceStatusClass ?>"><?= $faceStatusLabel ?></span>
                    </div>
                    <p id="face_status_description" class="mt-[8px] text-[13px] text-[#5C5C5E]"><?= htmlspecialchars($faceStatusDescription, ENT_QUOTES, 'UTF-8') ?></p>
                </div>
            </div>
        </div>
    </div>
</div>