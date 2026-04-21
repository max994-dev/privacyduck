
<div id="family_info_modal"
    class="hidden fixed inset-0 bg-[#00000040] px-[16px] border border-[#F6F6F63A] backdrop-blur-md flex items-center justify-center z-[2000] animate-[opacity_0.5s_ease-out]">
    <div class="relative bg-white rounded-[30px] shadow-[0px_4px_4px_#0206091A] w-full max-w-[1174px]">
        <!-- Close Button -->
        <button onclick="add_info_close_modal()" id="closeModal"
            class="hidden xl:block absolute top-[16px] right-[30px] font-bold text-gray-500 hover:text-red-500">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M19 5L5 19M5 5L19 19" stroke="#FFFFFF" stroke-width="1.5"
                    stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>
        <button onclick="add_info_close_modal()" id="closeModal"
            class="absolute top-[16px] right-[30px] font-bold text-gray-500 hover:text-red-500 xl:hidden">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M19 5L5 19M5 5L19 19" stroke="#010205" stroke-width="1.5"
                    stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>

        <div class="flex flex-col xl:flex-row xl:items-stretch">
            <!-- Form Section -->
            <div class="px-[16px] sm:px-[30px] py-[56px] w-full xl:w-[60%] ">
                <div class=" max-h-[80vh] overflow-y-auto">
                    <h1 class="font-bold text-[24px] sm:text-[32px] text-[#010205]">Add a Family Member</h1>

                    <!-- Half Inputs Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-[24px] mt-[32px]">
                        <?php
                        $datas = [
                            ["name" => "First Name *", "type" => "text", "id" => "first_name", "placeholder" => "Enter First Name", "size" => "half"],
                            ["name" => "Last Name *", "type" => "text", "id" => "last_name", "placeholder" => "Enter Last Name", "size" => "half"],
                            ["name" => "City *", "type" => "text", "id" => "city", "placeholder" => "Enter City", "size" => "half"],
                            ["name" => "State *", "type" => "text", "id" => "state", "placeholder" => "Enter State", "size" => "half"],
                            ["name" => "Phone Number *", "type" => "text", "id" => "phone", "placeholder" => "Enter Phone Number", "size" => "half"],
                            ["name" => "Zip *", "type" => "text", "id" => "zip", "placeholder" => "Enter Zip", "size" => "half"],
                            ["name" => "Address *", "type" => "text", "id" => "address", "placeholder" => "Enter Address", "size" => "full"],
                            ["name" => "Email *", "type" => "email", "id" => "email", "placeholder" => "Enter Email", "size" => "full"],
                        ];
                        if (!function_exists('searchIndex')) {
			    function searchIndex($array, $value) {
    				    foreach ($array as $index => $item) {
           				 if ($item === $value) return $index;
        			    }
       				    return -1;
    			    }
			}

                        foreach ($datas as $data) {
                            if ($data["size"] == "half") {
                        ?>
                                <div class="flex flex-col mt-[<?php echo in_array(searchIndex($datas, $data), [0]) ? "0px" : "16px"; ?>] sm:mt-[<?php echo in_array(searchIndex($datas, $data), [0, 1]) ? "0px" : "24px"; ?>]">
                                    <label for="<?= $data["id"]; ?>" class="font-medium text-[16px] text-[#3F3F3F]"><?= $data["name"]; ?></label>
                                    <input type="<?= $data["type"]; ?>" id="<?= $data["id"]; ?>" placeholder="<?= $data["placeholder"]; ?>"
                                        class="mt-[8px] h-[48px] px-[16px] rounded-[10px] border border-[#E7E7E7]">
                                    <div class="hidden mt-[6px] text-[14px] leading-[20px]" id="family_invalid_<?= $data["id"]; ?>"></div>
                                </div>
                        <?php }
                        } ?>
                    </div>

                    <!-- Full Inputs -->
                    <div class="grid grid-cols-1 mt-[24px]">
                        <?php
                        foreach ($datas as $data) {
                            if ($data["size"] == "full") {
                        ?>
                                <div class="flex flex-col mt-[<?= searchIndex($datas, $data) == 6 ? "0px" : "24px" ?>]">
                                    <label for="<?= $data["id"]; ?>" class="font-medium text-[16px] text-[#3F3F3F]"><?= $data["name"]; ?></label>
                                    <input <?php if ($data["id"] == "email") echo "onkeypress='checkStatus()' onchange='checkStatus()'"; ?> type="<?= $data["type"]; ?>" id="<?= $data["id"]; ?>" placeholder="<?= $data["placeholder"]; ?>"
                                        class="mt-[8px] h-[48px] px-[16px] rounded-[10px] border border-[#E7E7E7]">
                                    <div class="hidden mt-[6px] text-[14px] leading-[20px]" id="family_invalid_<?= $data["id"]; ?>"></div>
                                </div>
                        <?php }
                        } ?>
                    </div>

                    <div class="mt-[32px] xl:hidden bg-[url('/assets/image/desktop/section5.png')] bg-cover bg-center py-[40px] rounded-[30px] justify-center items-center">
                        <div class="flex flex-col px-[32px]">
                            <h1 class="font-semibold text-[24px] sm:text-[36px] text-white">Payment Details</h1>
                            <div class="mt-[20px] flex flex-col gap-[16px]">
                                <div class="flex flex-wrap gap-x-[30px] sm:justify-between">
                                    <span class="text-[16px] sm:text-[20px] text-[#FFFFFFCC]" id="mobile_family_payment_line_title">Additional family member</span>
                                    <span class="text-[18px] sm:text-[24px] text-[#FFFFFFCC]" id="mobile_family_payment_price_exp_value"></span>
                                </div>
                                <div class="flex flex-wrap gap-x-[30px] sm:justify-between" id="mobile_family_payment_price_discount">
                                    <span class="text-[16px] sm:text-[20px] text-[#24A556]" id="mobile_family_payment_price_dis_cond"></span>
                                    <span class="text-[18px] sm:text-[24px] text-[#24A556]" id="mobile_family_payment_price_dis_value"></span>
                                </div>
                            </div>
                            <div class="mt-[54px] flex flex-col gap-[16px]">
                                <div class="flex flex-wrap gap-x-[30px] sm:justify-between">
                                    <span class="text-[18px] sm:text-[20px] text-[#FFFFFFCC]">To Pay</span>
                                    <span class="text-[18px] sm:text-[24px] text-[#FFFFFFCC]" id="mobile_family_payment_price_real_value"></span>
                                </div>
                                <p class="text-[16px] sm:text-[18px] text-[#FFFFFFCC]"><strong>Note:</strong> You’ll be charged if the invite is redeemed. Charges will be prorated as per your billing cycle. You can cancel this invite anytime on the Manage Family page.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Discount Note -->
                    <div class="flex items-center gap-[5px] mt-[26px]">
                        <?php require(BASEPATH . "/src/common/svgs/dashboard/family/close.php"); ?>
                        <p class="font-semibold text-[14px] text-[#3F3F3F]">
                            Add members to paid plans & get <span class="font-extrabold text-[#24A556]">UP TO 30% OFF</span> all your plans!
                        </p>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end items-center mt-[26px] gap-[27px]">
                        <span onclick="add_info_close_modal()" class="cursor-pointer text-[14px] font-semibold underline text-[#010205]">Cancel</span>
                        <button onclick="inviteMember()"
                            class="w-[172px] h-[44px] rounded-full bg-[#24A556] text-[16px] font-bold text-white">Add a Member</button>
                    </div>
                </div>
            </div>

            <!-- Right Image Section -->
            <div class="hidden xl:flex py-[40px] flex-1 bg-[url('/assets/image/desktop/section5.png')] bg-cover bg-center rounded-r-[30px] justify-center items-center">
                <div class="flex flex-col px-[32px]">
                    <h1 class="font-semibold text-[36px] text-white">Payment Details</h1>
                    <div class="mt-[20px] flex flex-col gap-[16px]">
                        <div class="flex justify-between">
                            <span class="text-[20px] text-[#FFFFFFCC]" id="family_payment_line_title">Additional family member</span>
                            <span class="text-[24px] text-[#FFFFFFCC]" id="family_payment_price_exp_value"></span>
                        </div>
                        <div class="flex justify-between hidden" id="family_payment_price_discount">
                            <span class="text-[20px] text-[#24A556]" id="family_payment_price_dis_cond"></span>
                            <span class="text-[24px] text-[#24A556]" id="family_payment_price_dis_value"></span>
                        </div>
                    </div>
                    <div class="mt-[54px] flex flex-col gap-[16px]">
                        <div class="flex justify-between">
                            <span class="text-[20px] text-[#FFFFFFCC]">To Pay</span>
                            <span class="text-[24px] text-[#FFFFFFCC]" id="family_payment_price_real_value"></span>
                        </div>
                        <p class="text-[18px] text-[#FFFFFFCC]"><strong>Note:</strong> You’ll be charged if the invite is redeemed. Charges will be prorated as per your billing cycle. You can cancel this invite anytime on the Manage Family page.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function add_payment_close_modal() {
        document.getElementById("payment_modal").classList.add("hidden");
    }

    function add_payment_show_modal() {
        document.getElementById("payment_modal").classList.remove("hidden");
    }

    function checkStatus() {
        let text = $("#email").val().trim();
        let pattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        let $feedback = $("#family_invalid_email");

        if (!pattern.test(text)) {
            $feedback
                .removeClass("hidden text-[#24A556] text-[#AB4522] text-[#C00000]")
                .addClass("text-[#C00000]")
                .text("Email is invalid");
        } else {
            $feedback
                .removeClass("hidden text-[#24A556] text-[#AB4522] text-[#C00000]")
                .html("<i class='fa fa-spinner fa-spin text-[#24A556]'></i>");

            $.post("/check_status", {
                    email: text
                })
                .then(res => {
                    $feedback.removeClass("text-[#24A556] text-[#AB4522] text-[#C00000]");
                    if (res.status === 0) {
                        $feedback
                            .addClass("text-[#24A556]")
                            .text("Invitation Available");
                    } else if (res.status === 1) {
                        $feedback
                            .addClass("text-[#edaf2a]")
                            .text("This email already has an active plan. You can still invite them; a one-time add-on fee applies.");
                    } else if (res.status === 2) {
                        $feedback
                            .addClass("text-[#edaf2a]")
                            .text("Invitation Available. But User already exists. So follow information will be ignored.");
                    } else if (res.status === -1) {
                        $feedback
                            .addClass("text-[#C00000]")
                            .text("Not available. You have already invited this email");
                    } else {
                        $feedback
                            .addClass("text-[#C00000]")
                            .text("Unexpected server response.");
                    }
                })
                .catch(() => {
                    $feedback
                        .removeClass("text-[#24A556] text-[#AB4522] text-[#C00000]")
                        .addClass("text-[#C00000]")
                        .text("Error checking email status.");
                });
        }
    }

    function invite_member() {
        $.post("/invite_member", {
            first_name: $("#first_name").val().trim(),
            last_name: $("#last_name").val().trim(),
            contacts: [{
                city: $("#city").val().trim(),
                state: $("#state").val().trim(),
                phone: $("#phone").val().trim(),
                zip: $("#zip").val().trim(),
                address: $("#address").val().trim(),
            }],
            email: $("#email").val().trim(),
        }).then(res => {
            add_payment_close_modal();
            add_info_close_modal();
            memberTable();
        })
    }

    function inviteMember() {
        const validate_data = [{
                id: "first_name",
                name: "First Name"
            },
            {
                id: "last_name",
                name: "Last Name"
            },
            {
                id: "city",
                name: "City"
            },
            {
                id: "state",
                name: "State"
            },
            {
                id: "phone",
                name: "Phone"
            },
            {
                id: "zip",
                name: "Zip"
            },
            {
                id: "address",
                name: "Address"
            },
            {
                id: "email",
                name: "Email"
            },
        ];
        for (let i = 0; i < validate_data.length; i++) {
            if ($("#" + validate_data[i].id).val() == "") {
                $("#family_invalid_" + validate_data[i].id).removeClass("hidden");
                $("#family_invalid_" + validate_data[i].id).removeClass("text-[#24A556] text-[#AB4522] text-[#C00000]");
                $("#family_invalid_" + validate_data[i].id).html(validate_data[i].name + " is required");
                $("#family_invalid_" + validate_data[i].id).addClass("text-[#C00000]");
                return;
            } else {
                $("#family_invalid_" + validate_data[i].id).addClass("hidden");
            }
        }
        let text = $("#email").val().trim();
        let pattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        let result = pattern.test(text);
        if (!result) {
            $("#family_invalid_email").removeClass("hidden");
            $("#family_invalid_email").removeClass("text-[#24A556] text-[#AB4522] text-[#C00000]");
            $("#family_invalid_email").html("Email is invalid");
            $("#family_invalid_email").addClass("text-[#C00000]");
        } else {
            $("#family_invalid_email").removeClass("hidden");
            $("#family_invalid_email").removeClass("text-[#24A556] text-[#AB4522] text-[#C00000]");
            $("#family_invalid_email").html("<i class='fa fa-spinner fa-spin text-[#24A556]'></i>");
            // Open synchronously on click so the browser does not block Stripe as a popup.
            var stripeCheckoutWindow = window.open("about:blank", "_blank");
            if (!stripeCheckoutWindow) {
                toastr.error("Please allow pop-ups for this site to open checkout.");
                $("#family_invalid_email").html("Allow pop-ups, then try again.");
                $("#family_invalid_email").addClass("text-[#C00000]");
                return;
            }
            $.post("/check_status", {
                email: $("#email").val().trim(),
            }, null, "json")
            .done(function(res) {
                $("#family_invalid_email").removeClass("text-[#24A556] text-[#AB4522] text-[#C00000]");
                if (res.status == 0) {
                    $("#family_invalid_email").html("Invitation Available");
                    $("#family_invalid_email").addClass("text-[#24A556]");
                } else if (res.status == 1) {
                    $("#family_invalid_email").html("This email already has an active plan. You can still invite them; a one-time add-on fee applies.");
                    $("#family_invalid_email").addClass("text-[#AB4522]");
                } else if (res.status == 2) {
                    $("#family_invalid_email").html("Invitation Available. But User already exists. So follow information will be ignored.");
                    $("#family_invalid_email").addClass("text-[#AB4522]");
                } else if (res.status == -1) {
                    stripeCheckoutWindow.close();
                    $("#family_invalid_email").html("Not available. You have already invited this email");
                    $("#family_invalid_email").addClass("text-[#C00000]");
                    return;
                }
                if (res.requirePayment) {
                    $.get("/get_discount_price", {}, function(gp) {
                        if (gp.error) {
                            stripeCheckoutWindow.close();
                            toastr.error(gp.error);
                            return;
                        }
                        const d = gp.data || {};
                        const link = d.stripe_payment_link || d.stripe_payment_link_etc;
                        if (!link) {
                            stripeCheckoutWindow.close();
                            toastr.error("Checkout link is missing.");
                            return;
                        }
                        window.invite_plan_id = gp.id;
                        $.post("/invite_payment_begin_checkout", {}, function() {
                            $.post("/invite_payment_save_pending", {
                                first_name: $("#first_name").val().trim(),
                                last_name: $("#last_name").val().trim(),
                                email: $("#email").val().trim(),
                                return_after_pay: "/dashboard/family?invite_paid=1",
                                contacts: [{
                                    city: $("#city").val().trim(),
                                    state: $("#state").val().trim(),
                                    phone: $("#phone").val().trim(),
                                    zip: $("#zip").val().trim(),
                                    address: $("#address").val().trim(),
                                }],
                            }, function() {
                                add_info_close_modal();
                                var _sep = link.indexOf("?") >= 0 ? "&" : "?";
                                stripeCheckoutWindow.location.href = link + _sep + "prefilled_email=" + encodeURIComponent("<?php echo $_SESSION["email"] ?? ""; ?>");
                            }, "json").fail(function(xhr) {
                                stripeCheckoutWindow.close();
                                var msg = "Could not save invite.";
                                try {
                                    var j = JSON.parse(xhr.responseText);
                                    if (j && j.error) msg = j.error;
                                } catch (e) {}
                                toastr.error(msg);
                            });
                        }, "json").fail(function() {
                            stripeCheckoutWindow.close();
                            toastr.error("Could not start checkout.");
                        });
                    }, "json").fail(function() {
                        stripeCheckoutWindow.close();
                        toastr.error("Could not load checkout.");
                    });
                } else {
                    stripeCheckoutWindow.close();
                    invite_member();
                }
            })
            .fail(function() {
                stripeCheckoutWindow.close();
                $("#family_invalid_email").removeClass("text-[#24A556] text-[#AB4522] text-[#C00000]");
                $("#family_invalid_email").addClass("text-[#C00000]").text("Error checking email status.");
            });
        }

    }
</script>
