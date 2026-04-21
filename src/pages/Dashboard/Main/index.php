<div class="flex items-center justify-between xl:justify-normal">
    <h1 class="font-semibold text-[16px] sm:text-[20px] md:text-[24px] text-[#010205] pointer-events-none">Welcome, <?php echo $_SESSION["fullName"]; ?>
    </h1>
    <?php if (!$_SESSION["plan_id"] || !$_SESSION['planable']) { ?>
        <button onclick="navigateTo('/dashboard/plans')"
            class="flex xl:hidden items-center bg-gradient-to-r from-[#77B248] to-[#24A556] px-[8px] md:px-[14px] py-[6px] md:py-[5px] rounded-full space-x-[2px]">
            <?php require(BASEPATH . "/src/common/svgs/dashboard/sidebar/fixed_menu_protect_user.php"); ?>
            <h1 class="text-[10px] md:text-[14px] text-white font-semibold tracking-[0.01em]">Protect Yourself</h1>
        </button>
    <?php } ?>
</div>
<div class="mt-[16px] lg:mt-[42px]">
    <div class="rounded-[30px] bg-[#FEFEFE] border border-[#F6F6F6] sm:flex justify-center items-center lg:block">
        <div class="px-[8px] py-[18px] sm:px-[18px] lg:flex lg:justify-between items-center">
            <div class="flex space-x-[6px] items-center">
                <i id="main_removal_status_icon" class="fa-solid fa-circle-exclamation text-[#C00000] text-[16px] sm:text-[24px]"></i>
                <h1 id="main_removal_status_label" class="text-[#010205] text-[12px] sm:text-[16px] font-medium">We haven’t started removals yet.</h1>
                <h1 id="main_removal_status" onclick="navigateTo('/dashboard/plans')" class="cursor-pointer text-[#24A556] text-[12px] sm:text-[16px] font-bold underline">Protect yourself</h1>
            </div>
            <div class="flex space-x-[6px] sm:space-x-[10px] items-center mt-[16px] lg:mt-0">
                <h1 class="sm:px-[14px] sm:py-[7.5px] sm:bg-[#C00000] text-[#C00000] sm:text-white rounded-full font-semibold text-[12px] sm:text-[20px]">
                    <label id="scan_count">
                        <?php
                        $path = BASEPATH . '/assets/uploads/' . $_SESSION['user_id'] . '/scan';
                        $conn = getDBConnection();
                        //google scan start
                        $main_stmt = $conn->prepare("SELECT * FROM results WHERE user_id = ? AND kind=1");
                        $main_stmt->bind_param("i", $_SESSION["user_id"]);
                        $main_stmt->execute();
                        $main_result = $main_stmt->get_result();
                        $data = $main_result->fetch_all(MYSQLI_ASSOC);
                        $count = count(array_filter($data, function ($item) {
                            return $item["step"] >= 2;
                        }));
                        if ($count == 301) {
                            echo 0;
                        } else {
                            if (is_dir($path)) {
                                $files = scandir($path);
                                echo count($files) - 2;
                            } else {
                                echo 0;
                            }
                        }
                        ?>
                    </label> <span class="text-[10px] sm:text-[12px]">Profiles Found</span>
                </h1>
                <h1 class="sm:px-[14px] sm:py-[7.5px] sm:bg-[#FAFAFA] text-[#010205] rounded-full font-semibold text-[12px] sm:text-[20px]">
                    0 <span class="text-[10px] sm:text-[12px]">Removals In Progress</span></h1>
                <h1 class="sm:px-[14px] sm:py-[7.5px] sm:bg-[#FAFAFA] text-[#010205] rounded-full font-semibold text-[12px] sm:text-[20px]">
                    0 <span class="text-[10px] sm:text-[12px]">Profiles Removed</span></h1>
            </div>
        </div>
    </div>
</div>
<div class="hidden lg:block">
    <?php require_once(BASEPATH . "/src/pages/Dashboard/Main/progress/index.php"); ?>
</div>
<div class="block lg:hidden">
    <?php require_once(BASEPATH . "/src/pages/Dashboard/Main/progress/mobile.php"); ?>
</div>
<div
    class="relative mt-[32px] rounded-[30px] bg-white/50 border border-[#F6F6F6] after:content-['Highly&nbsp;sensitive&nbsp;info'] after:absolute after:top-[-11.5px] after:right-0 after:bg-[#24A556] after:w-[143px] after:h-[23px] after:text-center after:text-[10px] after:text-white after:font-semibold after:rounded-full after:flex after:justify-center after:items-center">
    <?php require_once(BASEPATH . "/src/pages/Dashboard/Main/detail_item.php"); ?>
</div>
<div class="mt-[32px]">
    <?php require_once(BASEPATH . "/src/pages/Dashboard/Main/databrokers/index.php"); ?>
</div>
<div class="mt-[32px] rounded-[30px] bg-[#FFFFFFE3] border border-[#F6F6F6]">
    <?php require_once(BASEPATH . "/src/pages/Dashboard/Main/result_sites.php"); ?>
</div>

<script>
    function main_isRemoval() {
        const planable = <?php echo isset($_SESSION['planable']) && $_SESSION['planable'] ? 'true' : 'false'; ?>;
        if (planable) {
            const totalCount = Number(window.totalcount || 0);
            const completedCount = Number(window.removal_progress || 0);
            const completionPct = totalCount > 0 ? (completedCount / totalCount) * 100 : 0;
            if (completionPct >= 100) {
                $("#main_removal_status_label").html("Removal is complete. Enjoy your protected experience.");
                $("#main_removal_status_icon")
                    .removeClass("fa-circle-exclamation fa-spinner fa-spin text-[#C00000]")
                    .addClass("fa-circle-check text-[#24A556]");
            } else {
                $("#main_removal_status_label").html("Removal is ongoing, sit tight and enjoy our features");
                $("#main_removal_status_icon")
                    .removeClass("fa-circle-exclamation fa-circle-check text-[#C00000]")
                    .addClass("fa-spinner fa-spin text-[#24A556]");
            }
            $("#main_removal_status").addClass("hidden");
        }
    }

    function inc_scan_count() {
        const scan_count = document.getElementById('scan_count');
        if (scan_count) scan_count.innerHTML = JSON.parse(`<?php
                                                            $path = BASEPATH . '/assets/uploads/' . $_SESSION['user_id'] . '/scan';
                                                            if (is_dir($path)) {
                                                                $files = scandir($path);
                                                                echo json_encode($files);
                                                            } else {
                                                                echo "[]";
                                                            }
                                                            ?>`).filter(v => v.length > 3).length;
    }
</script>