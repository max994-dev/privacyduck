<div id="family_edit_intro">
    <h1 class="font-semibold text-[24px] text-[#010205]">Manage Family</h1>
    <div class="mt-[35px] flex flex-col">
        <?php require_once(BASEPATH . "/src/pages/Dashboard/Family/intro.php"); ?>
        <div class="mt-[32px] pt-[23px] px-[16px] sm:px-[42px] pb-[57px] bg-[#FFFFFF80] border border-[#F6F6F6] rounded-[26px] rounded-tl-none">
            <div class="flex flex-col space-y-[32px]">
                <h1 class="font-bold text-[24px] leading-[130%] text-[#010205]">Family Members</h1>
                <div class="flex flex-col bg-[#FBFBFB] border border-[#E7E6E6] rounded-[30px] h-[330px]">
                    <div class="flex px-[9px] sm:px-[30px] pt-[24px] items-center justify-between">
                        <h1 class="font-bold text-[#010205] leading-[130%] text-[14px] sm:text-[16px]" id="family_members_count">0 Members</h1>
                        <div class="flex h-[38px] max-w-[177px] sm:w-[231px] sm:max-w-none items-center space-x-[9px] bg-[#FDFDFD] rounded-[10px] border border-[#E7E7E7]">
                            <?php require_once(BASEPATH . "/src/common/svgs/dashboard/family/search.php"); ?>
                            <input class="flex-1 py-[3px] h-[18px] bg-transparent  text-[12px] tracking-[-0.01em] placeholder:text-[#B5B7C0] text-[#010205]" placeholder="Search">
                        </div>
                    </div>
                    <div id="family_members" class="h-[268px] mt-[16px] overflow-y-auto">
                        <?php
			if ($_SESSION["planable"] ?? false) {
   			    require(BASEPATH . "/src/pages/Dashboard/Family/members.php");
			} else {
			    require(BASEPATH . "/src/pages/Dashboard/Family/noplan_members.php");
			}
			?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="family_edit_detail">
    <div id="family_edit_waiting" class="hidden">
        <?php require(BASEPATH . "/src/pages/Dashboard/splash.php") ?>
    </div>
    <div id="family_edit_detail_content" class="hidden max-w-[600px]">
        <?php require(BASEPATH . "/src/pages/Dashboard/Family/detail.php"); ?>
    </div>
</div>
