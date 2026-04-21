<div
    class="w-full h-screen overflow-hidden border-t border-b border-r bg-[#00530F] rounded-tr-[30px] rounded-br-[30px] flex flex-col">

    <!-- Header -->
    <div class="py-[12px] pl-[16px]">
        <a href="/">
            <?php require(BASEPATH . "/src/common/svgs/dashboard/sidebar/duck.php") ?>
        </a>
    </div>

    <!-- Content and Scrollable Middle -->
    <div class="flex-1 flex flex-col px-[24px] pt-[24px] pb-[0] overflow-hidden">
        <!-- User Info -->
        <div class="flex items-center space-x-[4px]">
            <?php require(BASEPATH . "/src/common/svgs/dashboard/sidebar/circle_people.php") ?>
            <h1 class="font-medium pointer-events-none text-[18px] tracking-[-0.01em] text-[#4B4B4E]">
                <?php echo $_SESSION["fullName"]; ?>
            </h1>
        </div>

        <!-- Toggle Buttons -->
        <div class="flex justify-center mt-[32px]">
            <div class="flex bg-white/10 rounded-full w-[211px] h-[36px]">
                <?php
                $data = [
                    ["data_type" => "business_sidebar_mobile_personal", "data_people" => "2", "label" => "Personal"],
                    ["data_type" => "business_sidebar_mobile_work", "data_people" => "23", "label" => "Work"],
                ];
                foreach ($data as $item) { ?>
                    <button
                        class="flex-1 rounded-full w-[116px] h-[36px] transition-all whitespace-nowrap duration-200 font-medium leading-[140%] text-[14px] text-white"
                        data-type="<?= $item['data_type']; ?>" data-people="<?= $item['data_people']; ?>">
                        <?= $item['label']; ?>
                    </button>
                <?php } ?>
            </div>
        </div>

        <!-- Scrollable Sidebar -->
        <div class="mt-[30px] flex-1 overflow-y-auto">
            <div id="sidebar_mobile" class="flex flex-col space-y-[32px]">
                <?php foreach (
                    [
                        ["href" => "", "svg" => "key", "label" => "Dashboard"],
                        // ["href" => "/detail", "svg" => "cog", "label" => "Excruciating detail"],
                        ["href" => "/family", "svg" => "couple_people", "label" => "Manage family", "sub_label" => "Add a family member", "sub_svg" => "sub_plus_mobile"],
                        ["href" => "/plans", "svg" => "plan", "label" => "Plans"],
                        ["href" => "/custom", "svg" => "message_question", "label" => "Custom Removals"],
                        ["href" => "/concierge", "svg" => "concierge", "label" => "Privacy Concierge"],
                        ["href" => "/editinfo", "svg" => "edit_your_info", "label" => "Edit your info"]
                    ] as $item
                ) { ?>
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
                            <div class="mt-[10px] flex justify-end space-x-[12px] items-center">
                                <h1 class="text-[#4B4B4E] text-[14px] font-bold tracking-[-0.01em] py-[3px]"><?= $item['sub_label'] ?></h1>
                                <?php require(BASEPATH . "/src/common/svgs/dashboard/sidebar/" . $item['sub_svg'] . ".php"); ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
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