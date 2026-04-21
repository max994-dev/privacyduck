<link href="/assets/css/pricing.css" rel="stylesheet">
<?php
function switches()
{ ?>
    <div class="flex justify-center">
        <div
            class="lg:px-[56px] py-[10px] px-[2px] sm:px-[10px] lg:py-[31px] rounded-[10px] sm:rounded-bl-[0px] sm:rounded-br-[0px] md:rounded-tl-[15px] md:rounded-tr-[15px] lg:rounded-tl-[26px] lg:rounded-tr-[26px] bg-[#FFFFFF33] backdrop-blur-md flex">
            <div class="flex bg-[#FFFFFF59] rounded-full shadow-md md:w-[200px] lg:w-[300px]" id="dashboard_plans_year">
                <?php foreach (
                    [
                        ["label" => "1 Year", "dataType" => "year_one", "dataPeople" => "1"],
                        ["label" => "2 Year", "dataType" => "year_two", "dataPeople" => "2"],
                    ] as $key => $value
                ) { ?>
                    <button
                        class="relative flex-1 
                                px-[13px] py-[7px] sm:px-[20px] sm:py-[14px]
                                text-[14px] md:text-[14px] sm:text-[16px] font-semibold
                                rounded-full transition-all duration-200
                                whitespace-nowrap"
                        data-type="<?= $value["dataType"] ?>"
                        data-people="<?= $value["dataPeople"] ?>">
                        <?= $value["label"] ?>
                        <?php
                        if ($value["dataType"] == "year_two") {
                        ?>
                            <span class="absolute -top-[14px] -right-[2px] bg-red-500 text-white text-[10px] sm:text-[14px] font-bold px-2 py-0.5 rounded-full shadow">
                                45% OFF
                                <span class="absolute -top-[9px] -left-[10px] bg-yellow-400 text-black text-[8px] font-bold px-1 py-0.5 rounded transform rotate-[-45deg] shadow">
                                    New
                                </span>
                            </span>
                        <?php } ?>
                    </button>
                <?php } ?>
            </div>
            <div class="flex bg-[#FFFFFF59] rounded-full shadow-md ml-[5px] sm:ml-[32px] md:w-[330px] lg:w-[500px]"
                id="people">
                <?php foreach (
                    [
                        ["label" => "Single", "dataType" => "single_type", "dataPeople" => "1"],
                        ["label" => "Couple", "dataType" => "couple_type", "dataPeople" => "2"],
                        ["label" => "Family", "dataType" => "family_type", "dataPeople" => "3"],
                    ] as $key => $value
                ) { ?>
                    <button
                        class="flex-1
                                px-[13px] py-[7px] sm:px-[20px] sm:py-[14px]
                                text-[14px] sm:text-[16px] 
                                rounded-full transition-all duration-800
                                whitespace-nowrap"
                        data-type="<?= $value["dataType"] ?>" data-people="<?= $value["dataPeople"] ?>">
                        <?= $value["label"] ?>
                    </button>
                <?php } ?>
            </div>
        </div>
    </div>
<?php }
function boards()
{ ?>
    <div
        class="mt-[20px] sm:mt-0 
                sm:px-[43px] px-[20px] py-[30px] sm:py-[60px] 
                rounded-[10px] sm:rounded-[26px] 
                xl:flex 
                bg-[#FFFFFF80] 
                items-center 
                lg:min-w-[1000px]">
        <div class="sm:flex items-center justify-between">
            <div class="sm:max-w-[252px] pb-[39px] text-center sm:text-left">
                <div id="current_plan"
                    class="hidden flex items-center justify-center bg-gradient-to-r from-[#77B248] to-[#24A556] w-[143px] h-[34px] rounded-full space-x-[6px]">
                    <?php require(BASEPATH . "/src/common/svgs/dashboard/sidebar/fixed_menu_protect_user.php"); ?>
                    <h1 class="text-[14px] text-white">Current Plan</h1>
                </div>
                <h2 id="title" class="mt-[30px] font-bold text-[40px] sm:text-[38px]"></h2>
                <h2 id="cond" class="mt-[8px] font-medium text-[15px]"></h2>
                <h2 id="price" class="mt-[32px] font-bold text-[36px] leading-[46px]"><span class="text-[16px]">/mo</span></h2>
                <h2 id="billed" class="mt-[24px] text-[15px] font-medium"></h2>
            </div>
            <div class="hidden xl:block ml-[58px] border-[1px] border-[#B5B7C080] h-[275px]"></div>
            <div class="flex justify-center sm:ml-[58px] sm:max-w-[309px]">
                <div>
                    <?php foreach (
                        [
                            "Remove unlimited aliases, previous names, and email addresses",
                            "Enhanced privacy tools like email and phone masking",
                            "Email, Chat, and Phone Support",
                            "Custom removal requests plus automated services",
                        ] as $key => $value
                    ) { ?>
                        <div class="flex gap-x-[6px] relative <?= $key != 0 ? " mt-[24px]" : "" ?>">
                            <img src="/src/common/svgs/dashboard/plans/check.svg" class="relative top-[5px] w-[24px] h-[24px]"
                                alt="check" />
                            <h2 class="font-medium text-[16px] ">
                                <?= $value ?>
                            </h2>
                        </div>
                    <?php } ?>
                    <label class="flex gap-x-[6px] mt-[24px] relative cursor-pointer items-start">
                        <input type="checkbox" id="pd_book_call_optin_plans"
                            class="relative top-[5px] w-[24px] h-[24px] shrink-0 rounded border-[#CDCDCD] text-[#24A556] focus:ring-[#24A556]" />
                        <span class="font-medium text-[16px] text-left">
                            <span class="text-[#24A556] font-semibold">Book call</span> — free onboarding (2:00–4:00 PM Pacific). After payment, you’ll schedule before adding your info.
                        </span>
                    </label>
                </div>
            </div>
            <div class="hidden xl:block ml-[58px] border-[1px] border-[#B5B7C080] h-[275px]"></div>
        </div>
        <div
            class="mt-[40px] sm:mt-[20px] xl:mt-0 xl:ml-[58px] xl:w-[320px] sm:flex justify-between items-center xl:block">
            <div class="text-center sm:text-left">
                <h2 id="subtitle" class="font-bold text-[22px] leading-[130%]"></h2>
                <h2 id="plan-content" class="font-medium text-[15px] leading-[130%] mt-[24px]"></h2>
            </div>
            <div class="mt-[30px] sm:mt-0 flex justify-center xl:mt-[47px]">
                <button id="plans_pay" class="bg-[#24A556] text-[16px] font-bold rounded-full text-center text-white py-[22px] w-[255px] flex justify-center">
                    Start Protection&nbsp;&nbsp;
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 12H19" stroke="white" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M12 5L19 12L12 19" stroke="white" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>

                </button>
            </div>
        </div>
    </div>
<?php }
?>
<div
    class="pb-[50px] px-[20px] justify-center flex">
    <div>
        <div class="sm:mt-[60px] text-[#010205]">
            <?php switches() ?>
            <?php boards() ?>
        </div>
    </div>
</div>
<script>
    function plans_init() {
        <?php require_once(BASEPATH . "/src/pages/Dashboard/plans/data.php") ?>
        let id = -1;
        let coupon="";
        var link = "";

        function handleChange(yearKey, peopleKey) {
            const plan = dashboard_plans_data[yearKey]?.[peopleKey];

            if (!plan) return;

            $("#title").text(plan.title);
            $("#cond").text(plan.cond);
            $("#price").html(plan.price);
            $("#billed").text(plan.billed);
            $("#subtitle").text(plan.subtitle);
            $("#plan-content").text(plan.content);
            id = plan.id;
            coupon = plan.coupon;
            link = plan.stripe_payment_link;
            if (id == "<?php echo $_SESSION["plan_id"] ?? ""; ?>") {
                $("#current_plan").removeClass("hidden");
                $("h2#title").addClass("mt-[30px]");
                $('#plans_pay').addClass("hidden");
            } else {
                $("#current_plan").addClass("hidden");
                $("h2#title").removeClass("mt-[30px]");
                $('#plans_pay').removeClass("hidden");
            }
        }

        const yearButtons = $("[data-type='year_one'], [data-type='year_two']");
        const peopleButtons = $("[data-type='single_type'], [data-type='couple_type'], [data-type='family_type']");
        yearButtons.click(function() {
            yearButtons.removeClass("bg-[#24A556] font-bold active");
            $(this).addClass("bg-[#24A556] font-bold active");
            const year = $(this).attr("data-type") === "year_one" ? "one" : "two";
            const activePeopleButton = $('#people button.active').attr("data-type").split("_").filter(Boolean).length > 0 && $('#people button.active').attr("data-type").split("_").filter(Boolean)[0];
            handleChange(year, activePeopleButton);
        });
        peopleButtons.click(function() {
            peopleButtons.removeClass("bg-[#24A556] font-bold active");
            $(this).addClass("bg-[#24A556] font-bold active");
            const people = $(this).attr("data-type").split("_").filter(Boolean).length > 0 && $(this).attr("data-type").split("_").filter(Boolean)[0];
            const activeYearButton = $('#dashboard_plans_year button.active').attr("data-type").split("_").filter(Boolean).length > 0 && $('#dashboard_plans_year button.active').attr("data-type").split("_").filter(Boolean)[1];
            handleChange(activeYearButton, people);
        });

        function init() {
            yearButtons.removeClass("bg-[#24A556] font-bold active");
            yearButtons[0].classList.add("bg-[#24A556]", "font-bold", "active");

            peopleButtons.removeClass("bg-[#24A556] font-bold active");
            peopleButtons[0].classList.add("bg-[#24A556]", "font-bold", "active");

            handleChange("one", "single");
        }
        init();
        //------------------------------------------------

        async function persistBookCallIntentFromPlans() {
            var $cb = $("#pd_book_call_optin_plans");
            if (!$cb.length) return true;
            var intent = $cb.is(":checked") ? 1 : 0;
            try {
                await $.post("/book_call_set_intent", { intent: intent });
                return true;
            } catch (e) {
                return false;
            }
        }

        $("#plans_pay").click(async function() {
            var okIntent = await persistBookCallIntentFromPlans();
            if (!okIntent) {
                if (typeof toastr !== "undefined") toastr.error("Could not save Book call preference. Please try again.");
                return;
            }
            window.open(link + "?prefilled_email=" + "<?php echo $_SESSION["email"] ?? ""; ?>" + `${coupon?"&prefilled_promo_code="+coupon:""}`, "_blank");
        });

        (function bookCallOptinPlans() {
            var $cb = $("#pd_book_call_optin_plans");
            if (!$cb.length) return;
            $cb.prop("checked", <?php echo !empty($_SESSION['pd_book_call_intent']) ? 'true' : 'false'; ?>);
            $cb.on("change", function() {
                $.post("/book_call_set_intent", { intent: this.checked ? 1 : 0 });
            });
        })();
    }
</script>