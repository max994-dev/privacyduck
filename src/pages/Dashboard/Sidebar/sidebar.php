<div
    class="flex flex-col w-[307px] h-screen overflow-hidden border-t border-b border-r bg-white rounded-tr-[30px] rounded-br-[30px]">

    <!-- Logo + hide (desktop) -->
    <div class="pt-[32px] pl-[39px] pr-[16px] flex items-start justify-between gap-2 shrink-0">
        <a href="/"><img src="/assets/image/desktop/logo3.svg" alt="logo" /></a>
        <button type="button" id="dashboard-desktop-sidebar-hide"
            class="hidden xl:inline-flex items-center justify-center w-9 h-9 rounded-full text-[#4B4B4E] hover:bg-[#FAFAFA] transition-colors shrink-0"
            aria-label="Hide sidebar">
            <i class="fa-solid fa-angles-left text-[14px]" aria-hidden="true"></i>
        </button>
    </div>

    <!-- Tabs -->
    <div class="flex justify-center mt-[34px]">
        <div>
            <div class="flex bg-[#FAFAFA] rounded-full w-[211px] h-[36px]">
                <?php
                $data = [
                    ["data_type" => "dashboard_sidebar_personal", "data_people" => "2", "label" => "Personal", "href"=>""],
                    ["data_type" => "dashboard_sidebar_work", "data_people" => "23", "label" => "Work", "href"=>"/work"],
                ];
                foreach ($data as $item) { ?>
                    <a data-link href="<?= '/dashboard' . $item['href'] ?>"
                        class="flex rounded-full justify-center items-center w-[116px] h-[36px] transition-all whitespace-nowrap duration-200 font-medium leading-[140%] text-[14px] text-[#010205]"
                        data-type="<?= $item['data_type']; ?>" data-people="<?= $item['data_people']; ?>">
                        <?= $item['label']; ?>
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Sidebar Links -->
    <div class="mt-[41px] px-[39px] flex-1 overflow-y-auto">
        <div id="sidebar" class="flex flex-col space-y-[32px]">
            <?php
            $sidebarNavItems = [
                ["href" => "", "svg" => "key", "label" => "Dashboard"],
                ["href" => "/family", "svg" => "couple_people", "label" => "Manage Family", "sub_label" => "Add a family member", "sub_svg" => "sub_plus"],
                ["href" => "/plans", "svg" => "plan", "label" => "Plans"],
                ["href" => "/custom", "svg" => "message_question", "label" => "Custom removals", "plan_only" => true],
                ["href" => "/concierge", "svg" => "concierge", "label" => "Privacy Concierge"],
                ["href" => "/editinfo", "svg" => "edit_your_info", "label" => "Edit your info"],
                ["href" => "/account", "svg" => "fixed_menu_account", "label" => "Account"],
            ];
            foreach ($sidebarNavItems as $item) {
                if (!empty($item["plan_only"]) && empty($_SESSION["planable"])) {
                    continue;
                }
            ?>
                <div>
                    <a data-link href="<?= '/dashboard' . $item['href'] ?>" class="flex space-x-[14px] items-center">
                        <?php require BASEPATH . "/src/common/svgs/dashboard/sidebar/" . $item['svg'] . ".php"; ?>
                        <h1 class="text-[#4B4B4E] text-[18px] font-medium tracking-[-0.01em]"><?= $item['label'] ?></h1>
                    </a>
                    <div class="max-h-[70px] overflow-y-auto relative">
                        <?php
                        if ($item['href'] == "/family") {
                            $conn = getDBConnection();
                            $sql = "
                            SELECT users.firstname, users.lastname
                            FROM family
                            JOIN users ON users.id = family.invite_id
                            WHERE family.core_id = ?
                        ";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("i", $_SESSION["user_id"]);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if ($result->num_rows > 0) {
                                $data = $result->fetch_all(MYSQLI_ASSOC);
                                $count = count($data);
                                for ($i = 0; $i < $count; $i++) {
                        ?>
                                    <a data-link href="/dashboard/family" class="cursor-pointer mt-[10px] flex justify-end">
                                        <h1 class="capitalize text-[#4B4B4E] text-[14px] font-bold tracking-[-0.01em] py-[3px]"><?= $data[$i]['firstname'] . " " . $data[$i]['lastname'] ?></h1>
                                    </a>
                        <?php
                                }
                            }
                        }
                        ?>
                    </div>
                    <?php if (isset($item['sub_label'])) { ?>
                        <a data-link href="/dashboard/family" onclick="showModal()" class="cursor-pointer mt-[10px] space-x-[12px] items-center flex justify-end">
                            <h1 class="text-[#4B4B4E] text-[14px] font-bold tracking-[-0.01em] py-[3px]"><?= $item['sub_label'] ?></h1>
                            <?php require(BASEPATH . "/src/common/svgs/dashboard/sidebar/" . $item['sub_svg'] . ".php"); ?>
                        </a>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Footer -->
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
                        href="/dashboard/plans"
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
        if(planable){
            window.show_family_modal = true;
        }
    }
</script>