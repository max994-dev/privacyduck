<?php
/**
 * Profile-completeness banner.
 *
 * Reads users.{birth_date, city, state, zip, address, phone} and renders
 * an upsell-to-completeness banner if anything's missing. New policy
 * (May 2026): removal is NEVER paused for incomplete PII once a user
 * is paid. The pipeline runs every broker it can with whatever info
 * exists; only the specific brokers that genuinely need a missing
 * field will skip. So the banner now frames the prompt as additive
 * ("unlocks more brokers") rather than blocking ("paused").
 *
 * Includer must have $conn and $_SESSION["user_id"] available.
 */

$pdProfileFields = [];
try {
    $pbStmt = $conn->prepare(
        "SELECT birth_date, city, state, zip, address, phone FROM users WHERE id = ?"
    );
    $pbStmt->bind_param("i", $_SESSION["user_id"]);
    $pbStmt->execute();
    $pdProfileFields = $pbStmt->get_result()->fetch_assoc() ?: [];
    $pbStmt->close();
} catch (Throwable $e) {
    error_log('profile_banner read failed: ' . $e->getMessage());
}

// Each field has a user-friendly label.
$pdRequired = [
    'birth_date' => 'Date of birth',
    'city'       => 'City',
    'state'      => 'State',
    'zip'        => 'ZIP code',
    'address'    => 'Street address',
    'phone'      => 'Phone number',
];

$pdMissing = [];
foreach ($pdRequired as $key => $label) {
    $val = $pdProfileFields[$key] ?? null;
    if ($val === null || $val === '' || $val === '0000-00-00') {
        $pdMissing[$key] = $label;
    }
}

// Only render for paid users (free users have a different prompt path)
// and only when something's missing.
$pdShowBanner = !empty($pdMissing) && !empty($_SESSION['planable']);
if ($pdShowBanner):
?>
<div class="mb-[24px] rounded-[20px] border border-[#DCE7F2] bg-gradient-to-r from-[#F4F8FC] to-[#EFF5FB] p-[18px] sm:p-[22px] flex flex-col sm:flex-row items-start sm:items-center gap-[16px]">
    <div class="shrink-0 w-[44px] h-[44px] rounded-full bg-[#2563EB]/10 flex items-center justify-center">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 2v4M12 18v4M2 12h4M18 12h4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"
                stroke="#2563EB" stroke-width="2" stroke-linecap="round"/>
        </svg>
    </div>
    <div class="flex-1 min-w-0">
        <h2 class="text-[15px] sm:text-[17px] font-bold text-[#0F2A5F] leading-[1.3]">
            Removal is running. Add a few details to unlock more brokers.
        </h2>
        <p class="mt-[5px] text-[13px] sm:text-[14px] text-[#0F2A5F]/75 leading-[1.55]">
            Some data brokers require additional info before they&rsquo;ll process an opt-out
            (typically date of birth + a full address). We&rsquo;re still missing:
            <strong class="text-[#0F2A5F]">
                <?= htmlspecialchars(implode(', ', array_values($pdMissing)), ENT_QUOTES, 'UTF-8'); ?></strong>.
            Adding these lets us submit to every broker that needs them &mdash; not just the ones that work with name + email.
        </p>
    </div>
    <a href="/new_dashboard/account" class="shrink-0 inline-flex items-center gap-2 rounded-full bg-[#2563EB] hover:bg-[#1D4FBF] text-white font-semibold text-[14px] px-[20px] py-[10px] transition-colors whitespace-nowrap">
        Add details
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </a>
</div>
<?php endif; ?>
