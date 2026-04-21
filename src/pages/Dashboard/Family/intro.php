<div class="pt-[23px] px-[16px] sm:px-[42px] pb-[57px] bg-[#FFFFFF80] border border-[#F6F6F6] rounded-[26px] rounded-tl-none">
    <div class="hidden lg:flex flex-col space-y-[32px]">
        <div class="flex justify-between items-center">
            <h1 class="font-bold text-[24px] text-[#010205]">Add your family Members</h1>
            <fieldset>
                <legend>
                    <a href="" class="font-bold text-[14px] leading-[130%]">
                        Learn more about PrivacyDuck for Family
                    </a>
                </legend>
            </fieldset>
        </div>
        <div class="flex gap-[39px] items-end">
            <div class="flex items-center space-x-[43px]">
                <img class="w-[150px] h-[150px]" src="/assets/image/desktop/family/invite.png" alt="invite">
                <div class="flex flex-1 flex-col justify-between w-[239px]">
                    <h1 class="mb-[30px] font-medium text-[16px] leading-[140%] tracking-[-0.02em] text-[#3F3F3F] ">Add your family members. They’ll receive an email and can choose if they’d like to join your PrivacyDuck for Family.</h1>
                    <?php
                    if (isset($_SESSION["planable"]) && $_SESSION["planable"]) {
                    ?>
                        <button onclick="add_info_show_modal()" id="family_invite_button" class="w-full flex items-center justify-center h-[36px] rounded-full text-center bg-[#24A556] text-[14px] leading-[140%] tracking-[-0.02em] font-medium text-[#FAFAFA]">Add Members</button>
                    <?php
                    } else {
                    ?>
                        <button disabled class="w-full h-[36px] rounded-full text-center bg-gray-400 text-[14px] leading-[140%] tracking-[-0.02em] font-medium text-[#010205]">Add Members</button>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div class="px-[13px] pt-[20px] flex-1 pb-[30px] bg-[#E4FBF1]">
                <div class="flex items-center space-x-[5px]">
                    <?php require(BASEPATH . "/src/common/svgs/dashboard/family/close.php"); ?>
                    <h1 class="font-semibold text-[14px] leading-[140%] tracking-[-0.02em] text-[#3F3F3F]">Add members to paid plans & get <span class="font-[800] text-[#24A556]">UP TO 30% OFF</span> all your plans!</h1>
                </div>
                <div class="mt-[24px] flex space-x-[4px] grid grid-cols-3">
                    <?php
                    $datas = [
                        ["number" => "2", "discount" => 20],
                        ["number" => "3", "discount" => 25],
                        ["number" => "4+", "discount" => 30]
                    ];
                    foreach ($datas as $data) { ?>
                        <div class="flex flex-col gap-[15px]">
                            <h1 class="font-semibold text-[16px] leading-[140%] tracking-[-0.02em] text-[#3F3F3F]"><?php echo $data["number"]; ?> paid plans</h1>
                            <div class="breadcrumb bg-[#FAFAFA] relative flex justify-center items-center w-full h-[31.5px] max-w-[280px]">
                                <h1 class="font-bold text-[14px] leading-[140%] tracking-[-0.02em] text-[#626262]"><?php echo $data["discount"]; ?>% OFF</h1>
                            </div>
                        </div>
                    <?php }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="flex lg:hidden flex-col">
        <div class="px-[13px] pt-[20px] flex-1 pb-[30px] bg-[#E4FBF1]">
            <div class="flex items-center space-x-[5px]">
                <?php require(BASEPATH . "/src/common/svgs/dashboard/family/close.php"); ?>
                <h1 class="font-semibold text-[14px] leading-[140%] tracking-[-0.02em] text-[#3F3F3F]">Add members to paid plans & get <span class="font-[800] text-[#24A556]">UP TO 30% OFF</span> all your plans!</h1>
            </div>
            <div class="mt-[24px] flex space-x-[4px] grid grid-cols-3">
                <?php
                $datas = [
                    ["number" => "2", "discount" => 20],
                    ["number" => "3", "discount" => 25],
                    ["number" => "4+", "discount" => 30]
                ];
                foreach ($datas as $data) { ?>
                    <div class="flex flex-col gap-[15px]">
                        <h1 class="font-semibold text-[16px] leading-[140%] tracking-[-0.02em] text-[#3F3F3F]"><?php echo $data["number"]; ?> paid plans</h1>
                        <div class="breadcrumb bg-[#FAFAFA] relative flex justify-center items-center w-full h-[31.5px] max-w-[280px]">
                            <h1 class="font-bold text-[14px] leading-[140%] tracking-[-0.02em] text-[#626262]"><?php echo $data["discount"]; ?>% OFF</h1>
                        </div>
                    </div>
                <?php }
                ?>
            </div>
        </div>
        <fieldset class="mt-[16px]">
            <legend>
                <a href="" class="font-bold text-[14px] leading-[130%]">
                    Learn more about PrivacyDuck for Family
                </a>
            </legend>
        </fieldset>
        <div class="mt-[32px]">
            <h1 class="font-bold text-[24px] text-[#010205]">Add Members</h1>
            <div class="flex items-center mt-[16px]">
                <img class="w-[161px] h-[128px]" src="/assets/image/desktop/family/invite.png" alt="invite">
                <div class="flex flex-1 flex-col justify-between w-[166px] sm:w-[239px]">
                    <h1 class="mb-[30px] font-medium text-[12px] sm:text-[16px] leading-[140%] tracking-[-0.02em] text-[#3F3F3F] ">Add your family members. They’ll receive an email and can choose if they’d like to join your PrivacyDuck for Family.</h1>
                    <button onclick="add_info_show_modal()" class="w-full h-[36px] rounded-full text-center bg-[#24A556] text-[14px] leading-[140%] tracking-[-0.02em] font-medium text-[#FAFAFA]">Add Members</button>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function add_info_close_modal() {
        document.getElementById("family_info_modal").classList.add("hidden");
    }

    function add_info_show_modal() {
        $("#family_invite_button").html(window.loadingHtml2);
        $("#family_invite_button").prop("disabled", true);
        $.get("/get_discount_price", {}, function(res) {
            if (res.error) {
                toastr.error(res.error)
                $("#family_invite_button").html("Add Members");
                $("#family_invite_button").prop("disabled", false);
                return;
            } else {
                document.getElementById("family_info_modal").classList.remove("hidden");
                $("#family_invite_button").html("Add Members");
                $("#family_invite_button").prop("disabled", false);
                if (res.success == "free") {
                    $("#family_payment_line_title").text("Additional family member");
                    $("#mobile_family_payment_line_title").text("Additional family member");
                    $("#family_payment_price_exp_value").html("Free");
                    $("#mobile_family_payment_price_exp_value").html("Free");
                    $("#family_payment_price_discount").addClass("hidden");
                    $("#mobile_family_payment_price_discount").addClass("hidden");
                    $("#family_payment_price_real_value").html("Free");
                    $("#mobile_family_payment_price_real_value").html("Free");
                    window.family_stripe_link = "free";
                } else {
                    window.family_stripe_link = res.data.stripe_payment_link_etc || res.data.stripe_payment_link;
                    const final_value = parseFloat(res.data.value) / 100;
                    const lineTitle = res.data.price || "Additional family member";
                    $("#family_payment_line_title").text(lineTitle);
                    $("#mobile_family_payment_line_title").text(lineTitle);
                    $("#family_payment_price_exp_value").html("$" + final_value.toFixed(2));
                    $("#mobile_family_payment_price_exp_value").html("$" + final_value.toFixed(2));
                    $("#family_payment_price_discount").addClass("hidden");
                    $("#mobile_family_payment_price_discount").addClass("hidden");
                    $("#family_payment_price_real_value").html("$" + final_value.toFixed(2));
                    $("#mobile_family_payment_price_real_value").html("$" + final_value.toFixed(2));
                }
            }
        }, "json").fail(function() {
            toastr.error("Could not open add-member form. Check your plan or try again.");
            $("#family_invite_button").html("Add Members");
            $("#family_invite_button").prop("disabled", false);
        });
    }
</script>
