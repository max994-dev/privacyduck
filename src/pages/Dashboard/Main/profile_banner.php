<?php
/**
 * Profile-completeness banner.
 *
 * Required-by-broker fields: birth_date, city, state, zip, address, phone.
 * Without all of them, the removal pipeline marks every queued row
 * step=5 (missing_pii) and dispatches nothing -- so the user pays for
 * a service that silently does nothing for them.
 *
 * This banner is rendered at the top of /dashboard and /new_dashboard.
 * If everything's complete, the banner does NOT render. If anything's
 * missing, the user sees an urgent prompt + one-click jump to the
 * profile-edit page.
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

// Each field has a user-friendly label + reason it matters.
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

if (!empty($pdMissing)):
?>
<div class="mb-[24px] rounded-[20px] border border-[#FFD4A8] bg-gradient-to-r from-[#FFF7E8] to-[#FFEFD2] p-[20px] sm:p-[24px] flex flex-col sm:flex-row items-start sm:items-center gap-[16px]">
    <div class="shrink-0 w-[48px] h-[48px] rounded-full bg-[#F59E0B] flex items-center justify-center">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 9v4M12 17h.01M4.93 19h14.14a2 2 0 001.74-3L13.74 4a2 2 0 00-3.48 0L3.19 16a2 2 0 001.74 3z"
                stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>
    <div class="flex-1 min-w-0">
        <h2 class="text-[16px] sm:text-[18px] font-bold text-[#92400E] leading-[1.3]">
            Removals are paused until your profile is complete
        </h2>
        <p class="mt-[6px] text-[13px] sm:text-[14px] text-[#92400E]/85 leading-[1.55]">
            Data brokers verify identity using your full profile before they&rsquo;ll process an opt-out request. We&rsquo;re missing:
            <strong>
                <?= htmlspecialchars(implode(', ', array_values($pdMissing)), ENT_QUOTES, 'UTF-8'); ?>
            </strong>.
            Add these and removals start within minutes &mdash; no other action needed.
        </p>
    </div>
    <a href="/new_dashboard/account" class="shrink-0 inline-flex items-center gap-2 rounded-full bg-[#92400E] hover:bg-[#78350F] text-white font-semibold text-[14px] px-[20px] py-[10px] transition-colors whitespace-nowrap">
        Complete profile
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </a>
</div>
<?php endif; ?>
