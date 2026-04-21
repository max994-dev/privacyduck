<div id="edit_intro">
    <h1 class="text-[24px] text-[#010205] font-semibold">Edit Your Info</h1>
    <h1 class="mt-[16px] text-[16px] text-[#9D9D9D] font-medium">Here you can check, edit and add you details</h1>
    <?php require(BASEPATH . "/src/pages/Dashboard/EditInfo/protectcard.php"); ?>
    <?php require(BASEPATH . "/src/pages/Dashboard/EditInfo/members.php"); ?>
</div>
<div id="edit_detail">
    <div id="edit_waiting" class="hidden">
        <?php require(BASEPATH . "/src/pages/Dashboard/splash.php") ?>
    </div>
    <div id="edit_detail_content" class="hidden">
        <?php require(BASEPATH . "/src/pages/Dashboard/EditInfo/detail.php"); ?>
    </div>
</div>