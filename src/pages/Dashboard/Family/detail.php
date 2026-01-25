<div class="flex items-center space-x-[7px] cursor-pointer" onclick="editFamilyMemberInfoClose()">
    <?php require(BASEPATH . "/src/common/svgs/dashboard/editinfo/small_symbol.php") ?>
    <h1 id="family_member_name" class="text-[24px] text-[#010205] font-semibold"></h1>
</div>
<div class="mt-[28px] mx-auto grid grid-cols-1 ">
    <div class="bg-[#FFFFFFE3] rounded-[30px] border border-[#F6F6F6] px-[32px] py-[34px]">
        <div class="flex items-center gap-[8px] rounded-[15px] bg-[#E8FCE7] border border-[#F6F6F63B] px-[16px] py-[13px] shadow-[0px_4px_4px_0px_#F6F6F626]">
            <i class="fa-solid fa-circle-exclamation text-[#24A556]"></i>
            <h1 class="font-medium text-[10px] leading-[120%] text-[#7E7E7E]">The more information you provide, the more PrivacyDuck can find and remove for you.
                This allows us to filter out all irrelevant results and exclude people with the same name and/or location as you.</h1>
        </div>
        <div class="mt-[10px] grid grid-cols-1">
            <?php
            $datas = [
                ["name" => "First Name *", "type" => "text", "id" => "family_edit_firstname", "placeholder" => "Enter First Name", "size" => "full"],
                ["name" => "Last Name *", "type" => "text", "id" => "family_edit_lastname", "placeholder" => "Enter Last Name", "size" => "full"],
                ["name" => "Phone Number *", "type" => "text", "id" => "family_edit_phone", "placeholder" => "Enter Phone Number", "size" => "full"],
                ["name" => "City *", "type" => "text", "id" => "family_edit_city", "placeholder" => "Enter City", "size" => "full"],
            ];
            function searchIndex($array, $value)
            {
                foreach ($array as $index => $item) {
                    if ($item === $value) {
                        return $index;
                    }
                }
                return -1;
            }

            foreach ($datas as $data) {
                if ($data["size"] == "full") {
            ?>
                    <div class="flex flex-col mt-[<?php echo in_array(searchIndex($datas, $data), [0]) ? "0px" : "16px"; ?>] sm:mt-[<?php echo in_array(searchIndex($datas, $data), [0]) ? "0px" : "16px"; ?>]">
                        <label for="<?= $data["id"]; ?>" class="font-medium text-[14px] leading-[20px] text-[#010205]"><?= $data["name"]; ?></label>
                        <input readonly type="<?= $data["type"]; ?>" id="<?= $data["id"]; ?>" placeholder="<?= $data["placeholder"]; ?>"
                            class="mt-[6px] h-[48px] px-[14px] rounded-[8px] border border-[#00000040]">
                        <div class="hidden mt-[6px] text-[14px] leading-[20px]" id="family_invalid_<?= $data["id"]; ?>"></div>
                    </div>
            <?php }
            } ?>
        </div>
        <div class="mt-[16px] grid grid-cols-2 gap-[26px]">
            <?php
            $datas = [
                ["name" => "Zip *", "type" => "text", "id" => "family_edit_zip", "placeholder" => "Enter Zip", "size" => "half"],
                ["name" => "State *", "type" => "text", "id" => "family_edit_state", "placeholder" => "Enter State", "size" => "half"],
            ];
            foreach ($datas as $data) {
            ?>
                <div class="flex flex-col">
                    <label for="<?= $data["id"]; ?>" class="font-medium text-[14px] leading-[20px] text-[#010205]"><?= $data["name"]; ?></label>
                    <input readonly type="<?= $data["type"]; ?>" id="<?= $data["id"]; ?>" placeholder="<?= $data["placeholder"]; ?>"
                        class="mt-[6px] h-[48px] px-[14px] rounded-[8px] border border-[#00000040]">
                    <div class="hidden mt-[6px] text-[14px] leading-[20px]" id="family_invalid_<?= $data["id"]; ?>"></div>
                </div>
            <?php } ?>
        </div>
        <div class="grid grid-cols-1">
            <?php
            $datas = [
                ["name" => "Address *", "type" => "text", "id" => "family_edit_address", "placeholder" => "Enter Address", "size" => "full"],
                ["name" => "Email *", "type" => "email", "id" => "family_edit_email", "placeholder" => "Enter Email", "size" => "full"],
            ];
            foreach ($datas as $data) {
                if ($data["size"] == "full") {
            ?>
                    <div class="flex flex-col mt-[16px]">
                        <label for="<?= $data["id"]; ?>" class="font-medium text-[14px] leading-[20px] text-[#010205]"><?= $data["name"]; ?></label>
                        <input readonly <?php if ($data["type"] == "email") echo 'readonly'; ?> type="<?= $data["type"]; ?>" id="<?= $data["id"]; ?>" placeholder="<?= $data["placeholder"]; ?>"
                            class="mt-[6px] h-[48px] px-[14px] rounded-[8px] border border-[#00000040]">
                        <div class="hidden mt-[6px] text-[14px] leading-[20px]" id="family_invalid_<?= $data["id"]; ?>"></div>
                    </div>
            <?php }
            } ?>
        </div>
    </div>
</div>
<script>
    function editFamilyMemberInfoClose() {
        document.getElementById("family_edit_intro").classList.remove("hidden");
        document.getElementById("family_edit_detail_content").classList.add("hidden");
        memberTable();
    }
</script>