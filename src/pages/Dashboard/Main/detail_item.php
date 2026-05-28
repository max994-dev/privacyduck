<?php
/**
 * "What data brokers expose about you" card.
 *
 * Renders inside the wrapping <div> defined in Main/index.php. The old
 * version had:
 *  - A floating "Highly sensitive info" pseudo-element ribbon with
 *    hardcoded positioning that drifted on resize.
 *  - Duplicate "Name, Name, Age, Age" pills (copy/paste bug in
 *    seed data).
 *  - Two separate `<?php $data = [...] ?>` arrays that should have
 *    been one.
 *  - Red/pink + green badges with no visual hierarchy.
 *
 * Rewritten: integrated header pill (not floating), single dedup'd
 * data list, consistent palette (brand green for "we cover this",
 * neutral gray for items currently exposed by brokers).
 */

$pdExposedFields = [
    ['name' => 'Name',           'icon' => 'mini_name_white'],
    ['name' => 'Age / DOB',      'icon' => 'mini_123_white'],
    ['name' => 'Address',        'icon' => 'mini_pastadress_white'],
    ['name' => 'Past Addresses', 'icon' => 'mini_pastadress_white'],
    ['name' => 'Phone',          'icon' => 'mini_phone_white'],
    ['name' => 'Email',          'icon' => 'mini_email_white'],
    ['name' => 'Relatives',      'icon' => 'mini_relatives_white'],
    ['name' => 'Marital Status', 'icon' => 'mini_marital_white'],
    ['name' => 'Occupation',     'icon' => 'mini_occupation_white'],
    ['name' => 'Social Media',   'icon' => 'mini_social_white'],
    ['name' => 'Photos',         'icon' => 'mini_photos_white'],
    ['name' => 'Property Value', 'icon' => 'mini_name_white'],
];
?>
<div class="px-[20px] sm:px-[28px] lg:px-[40px] py-[20px] sm:py-[24px]">
    <!-- Integrated header (replaces the floating "Highly sensitive info"
         pseudo-element ribbon that the old version had). -->
    <div class="flex items-center justify-between flex-wrap gap-[12px] mb-[16px]">
        <div class="flex items-center gap-[10px]">
            <span class="inline-flex items-center gap-[6px] rounded-full bg-[#F4F8FC] text-[#0F2A5F] px-[12px] py-[5px] text-[11px] font-semibold uppercase tracking-[0.06em]">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 1l9 4v6c0 5-3.5 9-9 11-5.5-2-9-6-9-11V5l9-4z"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Sensitive data we cover
            </span>
        </div>
    </div>

    <h2 class="text-[#010205] text-[15px] sm:text-[18px] font-bold leading-[1.35]">
        Data brokers expose these details about you.
        <a href="/dashboard/plans" class="text-[#24A556] hover:text-[#1F8B47] underline ml-1">
            Remove them all
        </a>
    </h2>
    <p class="text-[#5B5F66] text-[12px] sm:text-[13px] leading-[1.55] mt-[6px] max-w-[680px]">
        Each broker exposes a different subset of your personal information.
        PrivacyDuck submits opt-out requests for every category below.
    </p>

    <div class="mt-[18px] flex flex-wrap gap-[8px]">
        <?php foreach ($pdExposedFields as $field): ?>
            <div class="flex items-center gap-[6px] px-[12px] py-[7px] rounded-full bg-[#24A556] text-white text-[12px] font-medium leading-[1] tracking-[-0.01em]">
                <img src="/assets/image/desktop/icons/<?= htmlspecialchars($field['icon'], ENT_QUOTES, 'UTF-8') ?>.svg"
                     alt="" aria-hidden="true" width="14" height="14" />
                <span><?= htmlspecialchars($field['name'], ENT_QUOTES, 'UTF-8') ?></span>
            </div>
        <?php endforeach; ?>
    </div>
</div>
