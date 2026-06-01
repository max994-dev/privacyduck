<?php
/**
 * Left nav for /new_dashboard. Sections:
 *   1. Logo + collapse button
 *   2. Personal/Work segmented pill
 *   3. Primary nav (auth-required: Dashboard, Family, Plans, Concierge, ...)
 *   4. Section divider
 *   5. Secondary nav (public marketing pages: Features, Business, FAQ, ...)
 *   6. Upgrade card (unpaid only)
 *
 * Active-state highlight: any nav item whose href matches the current
 * REQUEST_URI gets a green-tinted background + green icon/text. Makes the
 * sidebar self-orienting -- you can always see "I'm on the Plans page".
 */
$dashboardSidebarHelpDesk = 'https://tawk.to/chat/6813761a7c6684190de59a7c/1iq60amh0';

// Normalize current path so we can compare to nav hrefs. Strip query string
// and trailing slashes so '/new_dashboard/plans?x=1' matches 'plans'.
$pdCurrentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$pdCurrentPath = rtrim($pdCurrentPath, '/');
function pd_nav_is_active(string $href, string $currentPath): bool {
    if ($href === '' || $href === '/') {
        return $currentPath === '/new_dashboard' || $currentPath === '';
    }
    // strip leading slash for relative compare
    $h = '/new_dashboard' . $href;
    return $currentPath === $h || strpos($currentPath, $h . '/') === 0;
}

$newDashboardSiteNavRows = [
    ['href' => '/#features', 'svg' => 'key', 'label' => 'Features'],
    ['href' => '/business', 'svg' => 'people', 'label' => 'Business'],
    ['href' => '/sites-we-cover', 'svg' => 'couple_people', 'label' => 'Sites we cover'],
    ['href' => '/personalized-service', 'svg' => 'concierge', 'label' => 'Personalized service'],
    ['href' => '/#faq', 'svg' => 'message_question', 'label' => 'FAQ'],
    ['href' => $dashboardSidebarHelpDesk, 'svg' => 'concierge', 'label' => 'Help desk', 'external' => true],
    ['href' => '/blog', 'svg' => 'edit_your_info', 'label' => 'Blog'],
    ['href' => '/policy', 'svg' => 'fixed_menu_account', 'label' => 'Privacy policy'],
    ['href' => '/insurance', 'svg' => 'plan', 'label' => 'Insurance'],
    ['href' => '/restoration', 'svg' => 'cog', 'label' => 'Restoration'],
];
?>
<div
    class="flex flex-col w-[307px] h-screen overflow-hidden border-t border-b border-r bg-white rounded-tr-[30px] rounded-br-[30px]">

    <div class="pt-[32px] pl-[39px] pr-[16px] flex items-start justify-between gap-2 shrink-0">
        <a href="/"><img src="/assets/image/desktop/logo3.svg" alt="logo" /></a>
        <button type="button" id="dashboard-desktop-sidebar-hide"
            class="hidden xl:inline-flex items-center justify-center w-9 h-9 rounded-full text-[#4B4B4E] hover:bg-[#FAFAFA] transition-colors shrink-0"
            aria-label="Hide sidebar">
            <i class="fa-solid fa-angles-left text-[14px]" aria-hidden="true"></i>
        </button>
    </div>

    <div class="flex justify-center mt-[34px]">
        <div>
            <div class="flex bg-[#FAFAFA] rounded-full w-[211px] h-[36px]">
                <?php
                $data = [
                    ['data_type' => 'dashboard_sidebar_personal', 'data_people' => '2', 'label' => 'Personal', 'href' => ''],
                    ['data_type' => 'dashboard_sidebar_work', 'data_people' => '23', 'label' => 'Work', 'href' => '/work'],
                ];
                foreach ($data as $item) { ?>
                    <a data-link href="<?= '/new_dashboard' . $item['href'] ?>"
                        class="flex rounded-full justify-center items-center w-[116px] h-[36px] transition-all whitespace-nowrap duration-200 font-medium leading-[140%] text-[14px] text-[#010205]"
                        data-type="<?= $item['data_type']; ?>" data-people="<?= $item['data_people']; ?>">
                        <?= $item['label']; ?>
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="mt-[28px] px-[28px] flex-1 overflow-y-auto">
        <div id="sidebar" class="flex flex-col space-y-[6px]">
            <?php
            $sidebarNavItems = [
                ['href' => '', 'svg' => 'key', 'label' => 'Dashboard'],
                ['href' => '/family', 'svg' => 'couple_people', 'label' => 'Manage Family', 'sub_label' => 'Add a family member', 'sub_svg' => 'sub_plus'],
                ['href' => '/plans', 'svg' => 'plan', 'label' => 'Plans'],
                ['href' => '/custom', 'svg' => 'message_question', 'label' => 'Custom removals', 'plan_only' => true],
                ['href' => '/face', 'svg' => 'fixed_menu_user', 'label' => 'Face Removal', 'plan_only' => true],
                ['href' => '/concierge', 'svg' => 'concierge', 'label' => 'Privacy Concierge'],
                ['href' => '/editinfo', 'svg' => 'edit_your_info', 'label' => 'Edit your info'],
                ['href' => '/account', 'svg' => 'fixed_menu_account', 'label' => 'Account'],
            ];
            foreach ($sidebarNavItems as $item) {
                // Render EVERY nav item regardless of plan status. Plan-only
                // items are still tagged with a lock icon for unpaid users
                // so they can see what's behind the upgrade. JS routing in
                // NewDashboard/index.php handles the "unpaid clicked locked
                // feature" case (toastr + redirect to /plans).
                $isLocked = !empty($item['plan_only']) && empty($_SESSION['planable']);
            ?>
                <div>
                    <?php $isActive = pd_nav_is_active($item['href'], $pdCurrentPath); ?>
                    <a data-link href="<?= '/new_dashboard' . $item['href'] ?>"
                       class="group flex space-x-[12px] items-center px-[12px] py-[10px] rounded-[10px] transition-colors <?= $isActive ? 'bg-[#E8F7EF]' : 'hover:bg-[#F4F8F6]' ?>"
                       <?= $isLocked ? 'title="Upgrade to unlock"' : '' ?>>
                        <?php require BASEPATH . '/src/common/svgs/dashboard/sidebar/' . $item['svg'] . '.php'; ?>
                        <h1 class="text-[16px] tracking-[-0.01em] transition-colors flex-1 <?= $isActive ? 'text-[#1A7F40] font-semibold' : ($isLocked ? 'text-[#878C91] font-medium' : 'text-[#4B4B4E] font-medium group-hover:text-[#24A556]') ?>"><?= $item['label'] ?></h1>
                        <?php if ($isLocked): ?>
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" class="text-[#9CA3AF]" aria-hidden="true">
                                <rect x="5" y="11" width="14" height="9" rx="2" stroke="currentColor" stroke-width="2"/>
                                <path d="M8 11V7a4 4 0 1 1 8 0v4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        <?php endif; ?>
                    </a>
                    <div class="max-h-[70px] overflow-y-auto relative">
                        <?php
                        // Session-cached: family roster per user is small + rarely
                        // changes. Was previously a DB query on EVERY page load.
                        // Now: cache in session, refresh max once per 5 minutes.
                        if ($item['href'] === '/family') {
                            $cacheKey = 'pd_family_roster';
                            $cacheTtl = 300;  // 5 min
                            $cached = $_SESSION[$cacheKey] ?? null;
                            if (!$cached || (time() - ($cached['t'] ?? 0)) > $cacheTtl) {
                                $conn = getDBConnection();
                                $stmt = $conn->prepare(
                                    'SELECT users.firstname, users.lastname FROM family
                                     JOIN users ON users.id = family.invite_id
                                     WHERE family.core_id = ?'
                                );
                                $stmt->bind_param('i', $_SESSION['user_id']);
                                $stmt->execute();
                                $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                                $stmt->close();
                                $cached = ['t' => time(), 'data' => $data];
                                $_SESSION[$cacheKey] = $cached;
                            }
                            foreach (($cached['data'] ?? []) as $member) {
                        ?>
                                    <a data-link href="/new_dashboard/family" class="cursor-pointer mt-[10px] flex justify-end">
                                        <h1 class="capitalize text-[#4B4B4E] text-[14px] font-bold tracking-[-0.01em] py-[3px]"><?= htmlspecialchars($member['firstname'] . ' ' . $member['lastname'], ENT_QUOTES, 'UTF-8') ?></h1>
                                    </a>
                        <?php
                            }
                        }
                        ?>
                    </div>
                    <?php if (isset($item['sub_label'])) { ?>
                        <a data-link href="/new_dashboard/family" onclick="showModal()" class="cursor-pointer mt-[10px] space-x-[12px] items-center flex justify-end">
                            <h1 class="text-[#4B4B4E] text-[14px] font-bold tracking-[-0.01em] py-[3px]"><?= $item['sub_label'] ?></h1>
                            <?php require BASEPATH . '/src/common/svgs/dashboard/sidebar/' . $item['sub_svg'] . '.php'; ?>
                        </a>
                    <?php } ?>
                </div>
            <?php } ?>

            <!-- Section divider + label between primary nav and the
                 marketing-page links. Makes the visual hierarchy clear. -->
            <div class="pt-[20px] pb-[4px] px-[12px]">
                <div class="border-t border-[#F1F1F1]"></div>
                <div class="mt-[14px] text-[10px] font-semibold uppercase tracking-[0.12em] text-[#878C91]">
                    PrivacyDuck
                </div>
            </div>

            <?php foreach ($newDashboardSiteNavRows as $row) {
                $isExt = !empty($row['external']);
                $href = $row['href'];
                $svg = $row['svg'];
                $label = $row['label'];
            ?>
                <div>
                    <a href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>"
                        class="group flex space-x-[12px] items-center px-[12px] py-[8px] rounded-[10px] hover:bg-[#F4F8F6] transition-colors"
                        <?php if ($isExt) { ?>target="_blank" rel="noopener noreferrer"<?php } ?>>
                        <?php require BASEPATH . '/src/common/svgs/dashboard/sidebar/' . $svg . '.php'; ?>
                        <h1 class="text-[#5B5F66] group-hover:text-[#24A556] text-[14px] font-medium tracking-[-0.01em] transition-colors flex-1"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></h1>
                        <?php if ($isExt) { ?><svg width="11" height="11" viewBox="0 0 24 24" fill="none" class="text-[#9CA3AF]" aria-hidden="true"><path d="M14 4h6m0 0v6m0-6L10 14M6 6h4M6 18h12v-8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg><?php } ?>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php
    if (!isset($_SESSION['planable']) || !$_SESSION['planable']) {
    ?>
        <div class="flex justify-center mt-[40px] mb-[30px] px-[28px]">
            <div class="rounded-[20px] w-full h-[150px] bg-gradient-to-b from-[#77B248] to-[#24A556]">
                <div class="flex justify-center mt-[26px]">
                    <h1 class="max-w-[183px] text-center text-[14px] font-semibold text-white ">
                        Upgrade to PRO to get access to all Features!
                    </h1>
                </div>
                <div class="mt-[26px] flex justify-center items-center">
                    <a
                        data-link
                        href="/new_dashboard/plans"
                        class="cursor-pointer flex justify-center items-center bg-white shadow-[2px_4px_4px_#4F2AEA2B] text-[#24A556] font-semibold w-[203px] h-[40px] rounded-full">
                        Get Pro Now!
                    </a>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
</div>

<script>
    function showModal() {
        var planable = "<?= $_SESSION['planable'] ?>";
        if (planable) {
            window.show_family_modal = true;
        }
    }
</script>
