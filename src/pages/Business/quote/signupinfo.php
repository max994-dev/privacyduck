<?php
isBusinessReverseLogin();
$meta_title = "PrivacyDuck - Business-Speak with sales";
$meta_description = "Protect your privacy with PrivacyDuck. We remove your personal data from the internet and safeguard your online presence. Get started today!";
$meta_url = "https://privacyduck.com/";
$meta_image = "https://privacyduck.com/assets/pageSEO/landing.jpg";

include_once(BASEPATH . "/src/common/meta.php");
main_head_start();
main_head_end();
?>
<div class="flex bg-white justify-center">
    <div class="lg:w-1/2">
        <div class="h-[96px]"></div>
        <div class="flex justify-center items-center min-h-[calc(100vh-192px)]">
            <div>
                <div class="flex justify-center">
                    <a href="/business">
                        <div class="relative flex flex-col">
                            <div class="flex items-center gap-[8px]">
                                <h1 style="font-family: 'Alatsi', sans-serif;" class="text-[42px] tracking-[-0.02em] uppercase text-[#010205]">Privacy<label class="text-[#FFCF50]" style="font-family: 'Alatsi', sans-serif;">Duck</label></h1>
                                <?php require(BASEPATH . '/src/common/svgs/business/landing/duck.php'); ?>
                            </div>
                            <h1 style="font-family: 'Alatsi', sans-serif;" class="relative top-[-20px] text-[32px] tracking-[-0.02em] text-[#010205]">BUSINESS</h1>
                        </div>
                    </a>
                </div>
                <div class="text-center mt-[32px]">
                    <h1 class="font-bold text-[32px] sm:text-[40px] leading-[38px] text-[#010205]">
                        Sign Up
                    </h1>
                </div>
                <div class="text-center mt-[32px]">
                    <h1 class="text-[16px] leading-[24px] text-[#010205]">
                        Create Organization Account
                    </h1>
                </div>
                <div class="mt-[32px] flex flex-col items-center gap-[16px]">
                    <?php
                    $datas = [
                        [
                            "title" => "First Name",
                            "placeholder" => "Enter your first name",
                            "id" => "business_first_name"
                        ],
                        [
                            "title" => "Last Name",
                            "placeholder" => "Enter your last name",
                            "id" => "business_last_name"
                        ],
                        [
                            "title" => "Work Email",
                            "placeholder" => "Enter your email",
                            "id" => "business_email"
                        ],
                        [
                            "title" => "Direct Phone",
                            "placeholder" => "Enter your phone",
                            "id" => "business_phone"
                        ]
                    ];
                    foreach ($datas as $data) {
                    ?>
                        <div>
                            <h5 class="font-medium text-[#9D9D9D] leading-[20px] text-[14px]">
                                <?php echo $data["title"]; ?><span class="text-[#AB4522]">*</span>
                            </h5>
                            <input
                                id="<?php echo $data["id"]; ?>"
                                class="mt-[6px] rounded-[8px] w-[340px] sm:w-[360px] h-[44px] px-[14px] py-[10px] bg-[#FBFBFB] placeholder:text-[16px] placeholder:leading-[24px] placeholder:text-[#9D9D9D] text-[16px] leading-[24px] text-[#010205]"
                                placeholder="<?php echo $data["placeholder"]; ?>" />
                            <div
                                class="hidden text-[#AB4522] mt-[6px] text-[14px] leading-[20px]"
                                id="invalid_<?php echo $data["id"]; ?>">
                                <?php echo $data["title"]; ?> is incorrect
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="mt-[24px]">
                    <button id="business_send_disabled" disabled
                        class="w-[340px] sm:w-[360px] h-[44px] rounded-[8px]  bg-[#00530F80] but-shadow text-center text-[#FAFAFA] font-semibold 
                        leading-[24px] text-[16px]">
                        Sign Up</button>
                    <button id="business_send"
                        class="hidden w-[340px] sm:w-[360px] h-[44px] rounded-[8px]  bg-[#5AB87F] but-shadow text-center text-[#FAFAFA] font-semibold leading-[24px] text-[16px] 
                        hover:bg-gradient-to-r hover:from-[#77B248] hover:to-[#24A556] active:bg-none active:bg-[#24A556]">
                        Sign Up</button>
                </div>
                <div class="mt-[32px] flex justify-center items-center gap-[4px]">
                    <h1 class="text-[14px] leading-[20px] text-[#9D9D9D]">Already have an account?</h1>
                    <a href="<?= WEB_DOMAIN ?>/business/quote/login">
                        <h1 class="text-[14px] leading-[20px] text-[#FFCF50]">Log In</h1>
                    </a>
                </div>
            </div>
        </div>
        <div class="h-[96px] flex justify-center">
            <h1 class="mt-[60px] text-[14px] text-[#010205] leading-[20px]">©PrivacyDuck 2025</h1>
        </div>
    </div>
    <div
        class="hidden relative w-1/2 bg-[url('/assets/image/desktop/business/speaksales/bg.png')] bg-cover bg-center bg-no-repeat lg:flex justify-center items-center rounded-tl-[30px] rounded-bl-[30px]">
        <img src="/assets/image/desktop/business/speaksales/speaksales.png" class="w-[645.58px] h-[360px]" alt="img" />
        <img class="absolute w-[49.83px] h-[55px] bottom-[35px] right-[36px]" src="/assets/image/desktop/login_duck.svg"
            alt="mark" />
    </div>
    <script>
        let result = false;
        let agreed = false;
        const ids = [
            "business_first_name",
            "business_last_name",
            "business_email",
            "business_phone",
        ];

        function validation() {
            ids.forEach(id => {
                $("#" + id).on("input", function() {
                    if (id == "business_email") {
                        let text = $(this).val(); // Get the email input value
                        let pattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                        result = pattern.test(text);
                    }
                    if (!result) {
                        $("#business_email").addClass("valid-border");
                        $("#invalid_business_email").removeClass("hidden").addClass("flex");
                    } else {
                        $("#invalid_business_email").removeClass("flex").addClass("hidden");
                        $("#business_email").removeClass("valid-border");
                    }

                    if (result && ids.every(id => $("#" + id).val().trim() != "") ) {
                        $("#business_send_disabled").addClass("hidden");
                        $("#business_send").removeClass("hidden");
                    } else {
                        $("#business_send").addClass("hidden");
                        $("#business_send_disabled").removeClass("hidden");
                    }
                });
            });
        }

        // Keep the button click if you also want to validate when clicking "Send"
        $("#business_send").click(function() {
            const data = {
                "firstname": $("#business_first_name").val().trim(),
                "lastname": $("#business_last_name").val().trim(),
                "email": $("#business_email").val().trim(),
                "phone": $("#business_phone").val().trim(),
            };
            $.post("/business/signupprocess", data, function(res) {
                    if (res["error"]) {
                        $("#business_email").addClass("valid-border");
                        $("#invalid_business_email").removeClass("hidden").addClass("flex");
                        $("#invalid_business_email").html(res["error"]);
                    } else if (res["success"] == "verify") {
                        window.open("<?= WEB_DOMAIN ?>/business/verify", "_self");
                    }
                });
            });
        validation();
    </script>
</div>
<?php
no_footer();
?>