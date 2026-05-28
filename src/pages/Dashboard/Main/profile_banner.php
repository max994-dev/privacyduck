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
<div class="mb-[20px] rounded-[16px] border border-[#D6EFE0] bg-[#F4FBF6] p-[16px] sm:p-[18px] flex flex-col sm:flex-row items-start sm:items-center gap-[14px]">
    <div class="shrink-0 w-[40px] h-[40px] rounded-full bg-[#24A556]/15 flex items-center justify-center">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M9 12l2 2 4-4M12 22a10 10 0 100-20 10 10 0 000 20z" stroke="#1A7F40" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>
    <div class="flex-1 min-w-0">
        <h2 class="text-[14px] sm:text-[16px] font-bold text-[#1A7F40] leading-[1.3]">
            Removal is running. Complete your profile to unlock <strong><?= count($pdMissing) ?></strong> more broker<?= count($pdMissing) === 1 ? '' : 's' ?>.
        </h2>
        <p class="mt-[3px] text-[12px] sm:text-[13px] text-[#1A7F40]/85 leading-[1.5]">
            Missing: <strong><?= htmlspecialchars(implode(', ', array_values($pdMissing)), ENT_QUOTES, 'UTF-8'); ?></strong>.
            Brokers with strict identity checks need these to process your opt-out.
        </p>
    </div>
    <a href="/new_dashboard/account" class="shrink-0 inline-flex items-center gap-2 rounded-full bg-[#24A556] hover:bg-[#1F8B47] text-white font-semibold text-[13px] sm:text-[14px] px-[18px] py-[9px] transition-colors whitespace-nowrap">
        Complete profile
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </a>
</div>
<?php endif; ?>
