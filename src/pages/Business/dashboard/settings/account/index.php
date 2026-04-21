<div id="business_info_loading_content" class="h-[calc(100vh-146px)] mt-[72px] xl:mt-0 px-[23px] sm:px-[24px] xl:px-[48px] py-[32px] xl:py-[37px]">
    <?php require(BASEPATH . "/src/pages/Dashboard/splash.php") ?>
</div>
<div id="business_info_content">
    <div class="flex items-center space-x-[7px] cursor-pointer">
        <h1 id="edit_name" class="text-[24px] text-[#010205] font-semibold">Account Info</h1>
    </div>
    <div class="mt-[28px] mx-auto grid grid-cols-1 md:grid-cols-2 gap-[27px]" >
        <div class="bg-[#FFFFFFE3] rounded-[30px] border border-[#F6F6F6] px-[32px] py-[34px]">
            <div class="flex items-center gap-[8px] rounded-[15px] bg-[#FFCF501A] border border-[#F6F6F63B] px-[16px] py-[13px] shadow-[0px_4px_4px_0px_#F6F6F626]">
                <i class="fa-solid fa-circle-exclamation text-[#FFC52C]"></i>
                <h1 class="font-medium text-[10px] leading-[120%] text-[#7E7E7E]">
                    The more information you provide, the more PrivacyDuck can find and remove for you.
                    This allows us to filter out all irrelevant results and exclude people with the same name and/or location as you.
                </h1>
            </div>

            <div class="mt-[10px] grid grid-cols-1">
                <?php
                $datas = [
                    ["name" => "First Name *", "type" => "text", "id" => "business_edit_firstname", "placeholder" => "Enter First Name", "size" => "full"],
                    ["name" => "Last Name *", "type" => "text", "id" => "business_edit_lastname", "placeholder" => "Enter Last Name", "size" => "full"],
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
                            <input type="<?= $data["type"]; ?>" id="<?= $data["id"]; ?>" placeholder="<?= $data["placeholder"]; ?>"
                                class="mt-[6px] h-[48px] px-[14px] rounded-[8px] border border-[#00000040]">
                            <div class="hidden mt-[6px] text-[14px] leading-[20px]" id="business_invalid_<?= $data["id"]; ?>"></div>
                        </div>
                <?php }
                } ?>
            </div>
            <div class="mt-[16px] grid grid-cols-1 md:gap-[26px]">
                <div>
                    <div class="mt-[10px] grid grid-cols-1">
                        <?php
                        $datas = [
                            ["name" => "Phone Number *", "type" => "text", "id" => "business_edit_phone", "placeholder" => "Enter Phone Number", "size" => "full"],
                            ["name" => "City", "type" => "text", "id" => "business_edit_city", "placeholder" => "Enter City", "size" => "full"],
                        ];
                        foreach ($datas as $data) {
                            if ($data["size"] == "full") {
                        ?>
                                <div class="flex flex-col mt-[<?php echo in_array(searchIndex($datas, $data), [0]) ? "0px" : "16px"; ?>] sm:mt-[<?php echo in_array(searchIndex($datas, $data), [0]) ? "0px" : "16px"; ?>]">
                                    <label for="<?= $data["id"]; ?>" class="font-medium text-[14px] leading-[20px] text-[#010205]"><?= $data["name"]; ?></label>
                                    <input type="<?= $data["type"]; ?>" id="<?= $data["id"]; ?>" placeholder="<?= $data["placeholder"]; ?>"
                                        class="mt-[6px] h-[48px] px-[14px] rounded-[8px] border border-[#00000040]">
                                    <div class="hidden mt-[6px] text-[14px] leading-[20px]" id="business_invalid_<?= $data["id"]; ?>"></div>
                                </div>
                        <?php }
                        } ?>
                    </div>
                    <div class="mt-[16px] grid grid-cols-1 lg:grid-cols-2 gap-[12px] lg:gap-[26px]">
                        <?php
                        $datas = [
                            ["name" => "Zip", "type" => "text", "id" => "business_edit_zip", "placeholder" => "Enter Zip", "size" => "half"],
                            ["name" => "State", "type" => "text", "id" => "business_edit_state", "placeholder" => "Enter State", "size" => "half"],
                        ];
                        foreach ($datas as $data) {
                        ?>
                            <div class="flex flex-col">
                                <label for="<?= $data["id"]; ?>" class="font-medium text-[14px] leading-[20px] text-[#010205]"><?= $data["name"]; ?></label>
                                <input type="<?= $data["type"]; ?>" id="<?= $data["id"]; ?>" placeholder="<?= $data["placeholder"]; ?>"
                                    class="mt-[6px] h-[48px] px-[14px] rounded-[8px] border border-[#00000040]">
                                <div class="hidden mt-[6px] text-[14px] leading-[20px]" id="business_invalid_<?= $data["id"]; ?>"></div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="grid grid-cols-1">
                        <?php
                        $datas = [
                            ["name" => "Address", "type" => "text", "id" => "business_edit_address", "placeholder" => "Enter Address", "size" => "full"],
                        ];
                        foreach ($datas as $data) {
                            if ($data["size"] == "full") {
                        ?>
                                <div class="flex flex-col mt-[16px]">
                                    <label for="<?= $data["id"]; ?>" class="font-medium text-[14px] leading-[20px] text-[#010205]"><?= $data["name"]; ?></label>
                                    <input <?php if ($data["type"] == "email") echo 'readonly'; ?> type="<?= $data["type"]; ?>" id="<?= $data["id"]; ?>" placeholder="<?= $data["placeholder"]; ?>"
                                        class="mt-[6px] h-[48px] px-[14px] rounded-[8px] border border-[#00000040]">
                                    <div class="hidden mt-[6px] text-[14px] leading-[20px]" id="business_invalid_<?= $data["id"]; ?>"></div>
                                </div>
                        <?php }
                        } ?>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1">
                <?php
                $datas = [
                    ["name" => "Email *", "type" => "email", "id" => "business_edit_email", "placeholder" => "Enter Email", "size" => "full"],
                ];
                foreach ($datas as $data) {
                    if ($data["size"] == "full") {
                ?>
                        <div class="flex flex-col mt-[16px]">
                            <label for="<?= $data["id"]; ?>" class="font-medium text-[14px] leading-[20px] text-[#010205]"><?= $data["name"]; ?></label>
                            <input <?php if ($data["type"] == "email") echo 'readonly'; ?> type="<?= $data["type"]; ?>" id="<?= $data["id"]; ?>" placeholder="<?= $data["placeholder"]; ?>"
                                class="mt-[6px] h-[48px] px-[14px] rounded-[8px] border border-[#00000040]">
                            <div class="hidden mt-[6px] text-[14px] leading-[20px]" id="business_invalid_<?= $data["id"]; ?>"></div>
                        </div>
                <?php }
                } ?>
            </div>
            <div class="mt-[42px] flex justify-end items-center gap-[27px]">
                <button onclick="update_business_account_info()" type="submit" id="updateinfo_btn" class="w-[111px] h-[44px] flex justify-center items-center rounded-full bg-gradient-to-r from-[#77B248] to-[#24A556] font-bold text-[16px] leading-[140%] text-white">
                    Save
                </button>
            </div>
        </div>
        <div class="bg-[#FFFFFFE3] rounded-[30px] border border-[#F6F6F6] px-[32px] py-[34px] flex flex-col justify-between">
            <div>
                <div class="bg-[#FFCF501A] px-[16px] py-[12px] rounded-[15px] border border-[#F6F6F63B] shadow-[0px_4px_4px_0px_#F6F6F626]">
                    <div class="flex flex-col max-w-[90%]">
                        <h1 class="font-semibold text-[12px] leading-[120%] text-[#010205]">First name</h1>
                        <div class="mt-[10px]">
                            <h1 class="font-medium text-[10px] leading-[120%] text-[#7E7E7E]">Add more names if:</h1>
                            <ul class="mt-[3px] pl-[16px] font-medium text-[#7E7E7E] list-disc text-[10px] leading-[150%]">
                                <li>You go by a nickname or abbreviated name, e.g., William/Bill, Christina/Tina, T.J/Travis James, etc</li>
                                <li>There are spelling variations of your name e.g., Jon/John, Army/Aimee, Hailey/Haley, etc.</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="mt-[26px] bg-[#FFCF501A] px-[16px] py-[18px] rounded-[15px] border border-[#F6F6F63B] shadow-[0px_4px_4px_0px_#F6F6F626]">
                    <div class="flex flex-col max-w-[90%]">
                        <h1 class="font-semibold text-[12px] leading-[120%] text-[#010205]">Last name</h1>
                        <div class="mt-[10px]">
                            <h1 class="font-medium text-[10px] leading-[120%] text-[#7E7E7E]">Add more names if:</h1>
                            <ul class="mt-[3px] pl-[16px] font-medium text-[#7E7E7E] list-disc text-[10px] leading-[150%]">
                                <li>Your last name changed due to marriage or divorce.</li>
                                <li>Your last name is commonly misspelled, e.g., Smith/Smyth, etc.</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="mt-[53px] bg-[#FFCF501A] px-[16px] py-[18px] rounded-[15px] border border-[#F6F6F63B] shadow-[0px_4px_4px_0px_#F6F6F626]">
                    <div class="flex flex-col max-w-[90%]">
                        <h1 class="font-semibold text-[12px] leading-[120%] text-[#010205]">Address</h1>
                        <div class="mt-[10px]">
                            <h1 class="text-[#7E7E7E] font-medium text-[10px] leading-[120%]">
                                Enter as many of your past addresses as you can - people-search sites can have outdated databases and display your profile with the previous address instead of the current one.
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function get_business_account_info(type = "init") {
        if (type == "init") {
            $("#business_info_content").hide();
            $("#business_info_loading_content").show();
        }
        $.get("/business/account_info", function(data) {
            if (data.error) {
                toastr.error(data.error);
                return;
            }
            $("#business_edit_firstname").val(data.firstname);
            $("#business_edit_lastname").val(data.lastname);
            $("#business_edit_email").val(data.email);
            $("#business_edit_phone").val(data.phone);
            $("#business_edit_address").val(data.address);
            $("#business_edit_city").val(data.city);
            $("#business_edit_state").val(data.state);
            $("#business_edit_zip").val(data.zip);
            if (type == "init") {
                $("#business_info_content").show();
                $("#business_info_loading_content").hide();
            }
        })
    }

    function update_business_account_info() {
        $("#updateinfo_btn").html(window.loadingHtml);
        const invalid = false;
        const validLists = [
            "business_edit_firstname",
            "business_edit_lastname",
            "business_edit_email",
            "business_edit_phone"
        ];
        for (let i = 0; i < validLists.length; i++) {
            const element = validLists[i];
            if ($("#" + element).val().length == 0) {
                $("#business_invalid_" + element).text("This field is required");
                $("#business_invalid_" + element).removeClass("hidden");
                $("#business_invalid_" + element).addClass("text-[#C00000]");
                $("#" + element).removeClass("border-[#00000040]");
                $("#" + element).addClass("border-[#C00000]");
                invalid = true;
            } else {
                $("#business_invalid_" + element).addClass("hidden");
                $("#business_invalid_" + element).removeClass("text-[#C00000]");
                $("#" + element).removeClass("border-[#C00000]");
                $("#" + element).addClass("border-[#00000040]");
            }
        }
        if (!invalid) {
            $.post("/business/update_account_info", {
                firstname: $("#business_edit_firstname").val(),
                lastname: $("#business_edit_lastname").val(),
                email: $("#business_edit_email").val(),
                phone: $("#business_edit_phone").val(),
                address: $("#business_edit_address").val(),
                city: $("#business_edit_city").val(),
                state: $("#business_edit_state").val(),
                zip: $("#business_edit_zip").val()
            }, function(data) {
                if (data.error) {
                    toastr.error(data.error);
                    return;
                }
                toastr.success(data.success);
                get_business_account_info("update");
                $("#updateinfo_btn").html("Save");
            })
        }
    }
</script>