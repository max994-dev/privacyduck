<?php
/**
 * "Notable brokers" card.
 *
 * Pins 12 recognizable broker brand names (MyLife, Spokeo, BeenVerified,
 * Whitepages, etc.) and shows their actual per-broker status for the
 * logged-in user. Goal: give the customer immediate brand recognition
 * + tangible proof that the service is operating against names they
 * actually care about, instead of just an abstract "413 brokers" count.
 *
 * Includer must have $conn + $_SESSION["user_id"] + $pdCounts (from
 * journey_panel.php) available.
 */

// Curated list of brokers a typical customer is likely to recognize.
// Order = customer-impact priority (people-search sites first, then
// background-check brands, then phone lookups).
$pdNotableSlugs = [
    'spokeocom'             => 'Spokeo',
    'beenverifiedcom'       => 'BeenVerified',
    'whitepagescomus'       => 'Whitepages',
    'mylifecom'             => 'MyLife',
    'inteliuscom'           => 'Intelius',
    'truthfindercom'        => 'TruthFinder',
    'peoplefinderscom'      => 'PeopleFinders',
    'truepeoplesearchcom'   => 'TruePeopleSearch',
    'instantcheckmatecom'   => 'InstantCheckmate',
    'radariscom'            => 'Radaris',
    'thatsthemcom'          => 'ThatsThem',
    '411com'                => '411.com',
];

$pdNotableData = [];
try {
    $userId = (int) $_SESSION['user_id'];
    $placeholders = implode(',', array_fill(0, count($pdNotableSlugs), '?'));
    $stmt = $conn->prepare(
        "SELECT target_domain, step, updated_at
         FROM results
         WHERE user_id = ? AND kind = 1
           AND target_domain IN ($placeholders)"
    );
    $types = 'i' . str_repeat('s', count($pdNotableSlugs));
    $params = array_merge([$userId], array_keys($pdNotableSlugs));
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    foreach ($stmt->get_result()->fetch_all(MYSQLI_ASSOC) as $r) {
        $pdNotableData[$r['target_domain']] = $r;
    }
    $stmt->close();
} catch (Throwable $e) {
    error_log('notable_brokers read failed: ' . $e->getMessage());
}

// Per-step label + color (small subset; full taxonomy lives in journey_panel)
function pd_notable_status(int $step): array {
    switch ($step) {
        case 2: return ['Removed',   '#1A7F40', '#E8F7EF'];
        case 1: return ['Processing', '#1D4FBF', '#E8F0FC'];
        case 5: return ['Needs info', '#0F2A5F', '#F4F8FC'];
        case 3:
        case 4: return ['Scheduled', '#5B5F66', '#F4F5F7'];
    }
    return ['Queued', '#5B5F66', '#F4F5F7'];
}
?>

<div class="mt-[24px] rounded-[24px] bg-white border border-[#F1F1F1] p-[24px] sm:p-[28px]">
    <div class="flex items-center justify-between mb-[18px] flex-wrap gap-[10px]">
        <div>
            <div class="text-[11px] font-semibold uppercase tracking-[0.12em] text-[#878C91]">Notable brokers</div>
            <h3 class="mt-[2px] text-[17px] sm:text-[18px] font-bold text-[#010205]">
                Removing your data from the sites people actually use
            </h3>
        </div>
        <span class="text-[12px] text-[#878C91]">
            <span class="font-semibold text-[#010205]"><?= number_format($pdCounts['total'] ?? 413) ?></span> total &middot;
            full list below
        </span>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-[10px] sm:gap-[12px]">
        <?php foreach ($pdNotableSlugs as $slug => $label):
            $row = $pdNotableData[$slug] ?? null;
            $step = $row ? (int) $row['step'] : 0;
            list($statusLabel, $textColor, $bgColor) = pd_notable_status($step);
            $isDone = ($step === 2);
        ?>
            <div class="flex items-center gap-[10px] rounded-[14px] border border-[#F1F1F1] bg-white px-[12px] py-[10px] hover:border-[#E5E7EB] transition-colors">
                <!-- Brand monogram (first letter in a colored circle).
                     Lightweight visual identity without needing actual broker logos. -->
                <div class="shrink-0 w-[34px] h-[34px] rounded-full flex items-center justify-center font-bold text-[14px]"
                     style="background:<?= $bgColor ?>; color:<?= $textColor ?>;">
                    <?= htmlspecialchars(strtoupper(substr($label, 0, 1)), ENT_QUOTES, 'UTF-8') ?>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-[13px] font-semibold text-[#010205] truncate"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="mt-[1px] flex items-center gap-[5px]">
                        <?php if ($isDone): ?>
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M5 13l4 4L19 7" stroke="<?= $textColor ?>" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        <?php endif; ?>
                        <span class="text-[11px] font-medium" style="color:<?= $textColor ?>;">
                            <?= htmlspecialchars($statusLabel, ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
