<?php
$dashboardSidebarHelpDesk = 'https://tawk.to/chat/6813761a7c6684190de59a7c/1iq60amh0';
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
    class="w-full h-screen overflow-hidden border-t border-b border-r bg-white rounded-tr-[30px] rounded-br-[30px] flex flex-col">

    <div class="py-[12px] pl-[16px]">
        <a href="/">
            <?php require BASEPATH . '/src/common/svgs/dashboard/sidebar/duck.php' ?>
        </a>
    </div>

    <div class="flex-1 flex flex-col px-[24px] pt-[24px] pb-[0] overflow-hidden">
        <div class="flex items-center space-x-[4px]">
            <?php require BASEPATH . '/src/common/svgs/dashboard/sidebar/circle_people.php' ?>
            <h1 class="font-medium pointer-events-none text-[18px] tracking-[-0.01em] text-[#4B4B4E]">
                <?php echo $_SESSION['fullName']; ?>
            </h1>
        </div>

        <div class="flex justify-center mt-[32px]">
            <div class="flex bg-[#FAFAFA] rounded-full w-[211px] h-[36px]">
                <?php
                $data = [
                    ['data_type' => 'dashboard_sidebar_mobile_personal', 'data_people' => '2', 'label' => 'Personal', 'href' => ''],
                    ['data_type' => 'dashboard_sidebar_mobile_work', 'data_people' => '23', 'label' => 'Work', 'href' => '/work'],
                ];
                foreach ($data as $item) { ?>
                    <a data-link href="<?= '/new_dashboard' . $item['href'] ?>"
                        class="flex justify-center items-center rounded-full w-[116px] h-[36px] transition-all whitespace-nowrap duration-200 font-medium leading-[140%] text-[14px] text-[#010205]"
                        data-type="<?= $item['data_type']; ?>" data-people="<?= $item['data_people']; ?>">
                        <?= $item['label']; ?>
                    </a>
                <?php } ?>
            </div>
        </div>

        <div class="mt-[30px] flex-1 overflow-y-auto">
            <div id="sidebar_mobile" class="flex flex-col space-y-[32px]">
                <?php
                $sidebarMobileNavItems = [
                    ['href' => '', 'svg' => 'key', 'label' => 'Dashboard'],
                    ['href' => '/family', 'svg' => 'couple_people', 'label' => 'Manage Family', 'sub_label' => 'Add a family member', 'sub_svg' => 'sub_plus_mobile'],
                    ['href' => '/plans', 'svg' => 'plan', 'label' => 'Plans'],
                    ['href' => '/custom', 'svg' => 'message_question', 'label' => 'Custom removals', 'plan_only' => true],
                    ['href' => '/concierge', 'svg' => 'concierge', 'label' => 'Privacy Concierge'],
                    ['href' => '/editinfo', 'svg' => 'edit_your_info', 'label' => 'Edit your info'],
                    ['href' => '/account', 'svg' => 'fixed_menu_account', 'label' => 'Account'],
                ];
                foreach ($sidebarMobileNavItems as $item) {
                    if (!empty($item['plan_only']) && empty($_SESSION['planable'])) {
                        continue;
                    }
                ?>
                    <div>
                        <a data-link href="<?= '/new_dashboard' . $item['href'] ?>" class="flex space-x-[14px] items-center">
                            <?php require BASEPATH . '/src/common/svgs/dashboard/sidebar/' . $item['svg'] . '.php'; ?>
                            <h1 class="text-[#4B4B4E] text-[18px] font-medium tracking-[-0.01em]"><?= $item['label'] ?></h1>
                        </a>
                        <div class="max-h-[70px] overflow-y-auto relative">
                            <?php
                            if ($item['href'] === '/family') {
                                $conn = getDBConnection();
                                $sql = '
                            SELECT users.firstname, users.lastname
                            FROM family
                            JOIN users ON users.id = family.invite_id
                            WHERE family.core_id = ?
                        ';
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param('i', $_SESSION['user_id']);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($result->num_rows > 0) {
                                    $data = $result->fetch_all(MYSQLI_ASSOC);
                                    $count = count($data);
                                    for ($i = 0; $i < $count; $i++) {
                            ?>
                                        <a data-link href="/new_dashboard/family" class="cursor-pointer mt-[10px] flex justify-end">
                                            <h1 class="capitalize text-[#4B4B4E] text-[14px] font-bold tracking-[-0.01em] py-[3px]"><?= $data[$i]['firstname'] . ' ' . $data[$i]['lastname'] ?></h1>
                                        </a>
                            <?php
                                    }
                                }
                            }
                            ?>
                        </div>
                        <?php if (isset($item['sub_label'])) { ?>
                            <a data-link href="/new_dashboard/family" onclick="showModal()" class="cursor-pointer mt-[10px] flex justify-end space-x-[12px] items-center">
                                <h1 class="text-[#4B4B4E] text-[14px] font-bold tracking-[-0.01em] py-[3px]"><?= $item['sub_label'] ?></h1>
                                <?php require BASEPATH . '/src/common/svgs/dashboard/sidebar/' . $item['sub_svg'] . '.php'; ?>
                            </a>
                        <?php } ?>
                    </div>
                <?php } ?>

                <?php foreach ($newDashboardSiteNavRows as $row) {
                    $isExt = !empty($row['external']);
                ?>
                    <div>
                        <a href="<?= htmlspecialchars($row['href'], ENT_QUOTES, 'UTF-8') ?>"
                            class="flex space-x-[14px] items-center"
                            <?php if ($isExt) { ?>target="_blank" rel="noopener noreferrer"<?php } ?>>
                            <?php require BASEPATH . '/src/common/svgs/dashboard/sidebar/' . $row['svg'] . '.php'; ?>
                            <h1 class="text-[#4B4B4E] text-[18px] font-medium tracking-[-0.01em]"><?= htmlspecialchars($row['label'], ENT_QUOTES, 'UTF-8') ?></h1>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <?php
        if (!isset($_SESSION['planable']) || !$_SESSION['planable']) {
    ?>
        <div class="flex justify-center mt-[20px] mb-[30px] px-[24px]">
            <div class="rounded-[20px] w-full h-[150px] bg-gradient-to-b from-[#77B248] to-[#24A556]">
                <div class="flex justify-center mt-[26px]">
                    <h1 class="max-w-[183px] text-center text-[14px] font-semibold text-white ">
                        Upgrade to PRO to get access to all Features!
                    </h1>
                </div>
                <div class="mt-[26px] flex justify-center">
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
