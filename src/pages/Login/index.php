<?php
isReverseLogin();
$meta_title = "PrivacyDuck - Login";
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
                    <a href="/"><img class="w-[220px] h-[45px]" src="/assets/image/desktop/logo2.svg" alt="logo" /></a>
                </div>
                <div class="text-center mt-[32px]">
                    <h1 class="font-bold text-[32px] sm:text-[40px] leading-[38px] text-[#010205]">Verify Your Email</h1>
                </div>
                <div class="mt-[32px]">
                    <div>
                        <h5 class="font-medium text-[#9D9D9D] leading-[20px] text-[14px]">Email&nbsp;<span
                                class=" text-[#AB4522]">*</span></h5>
                        <input id="email"
                            class="mt-[6px] rounded-[8px] w-[340px] sm:w-[360px] h-[44px] px-[14px] py-[10px] bg-[#FBFBFB] placeholder:text-[16px] placeholder:leading-[24px] placeholder:text-[#9D9D9D] text-[16px] leading-[24px] text-[#010205]"
                            placeholder="Enter your email" />
                        <div class="hidden text-[#AB4522] mt-[6px] text-[14px] leading-[20px]" id="invalid">The email is
                            incorrect</div>
                    </div>
                </div>
                <div class="mt-[24px]">
                    <button id="send"
                        class="w-[340px] sm:w-[360px] h-[44px] rounded-[8px]  bg-[#5AB87F] but-shadow text-center text-[#FAFAFA] font-semibold leading-[24px] text-[16px] hover:bg-gradient-to-r hover:from-[#77B248] hover:to-[#24A556] active:bg-none active:bg-[#24A556]">Send
                        Email</button>
                </div>
                <div class="mt-[32px] flex justify-center">
                    <h5 class="text-[14px] leading-[20px] text-[#9D9D9D]">Don't have an account?&nbsp;&nbsp;<a href="/signup"
                            class="font-semibold text-[#24A556]">Sign up</a></h5>
                </div>
            </div>
        </div>
        <div class="h-[96px] flex justify-center">
            <h1 class="mt-[60px] text-[14px] text-[#010205] leading-[20px]">©PrivacyDuck 2025</h1>
        </div>
    </div>
    <div
        class="hidden relative w-1/2 bg-[url('/assets/image/desktop/login.png')] bg-cover lg:flex justify-center items-center rounded-tl-[30px] rounded-bl-[30px]">
        <img src="/assets/image/desktop/privacyduckfight.png" class="w-[645.58px] h-[360px]" alt="img" />
        <img class="absolute w-[49.83px] h-[55px] bottom-[35px] right-[36px]" src="/assets/image/desktop/login_duck.svg"
            alt="mark" />
    </div>
    <script>
        $("#email").on("input", function () {
            let text = $(this).val(); // Get the email input value
            let pattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            let result = pattern.test(text);
            if (!result) {
                $("#email").addClass("valid-border");
                $("#invalid").removeClass("hidden").addClass("flex");
            } else {
                $("#invalid").removeClass("flex").addClass("hidden");
                $("#email").removeClass("valid-border");
            }
        });

        // Keep the button click if you also want to validate when clicking "Send"
        $("#send").click(function () {
            let text = $("#email").val().trim();
            // let pattern = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
            let pattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            let result = pattern.test(text);
            if (!result) {
                $("#email").addClass("valid-border");
                $("#invalid").removeClass("hidden").addClass("flex");
            } else {
                $("#invalid").removeClass("flex").addClass("hidden");
                $("#email").removeClass("valid-border");

                $.post("/loginProcess", {
                    email: $("#email").val().trim()
                }, function (res) {

                    if (res["error"]) {
                        $("#email").addClass("valid-border");
                        $("#invalid").removeClass("hidden").addClass("flex");
                        $("#invalid").html(res["error"]);
                    }
                    else if (res["success"]=="verify") {
                        window.open("<?= WEB_DOMAIN ?>/verify", "_self");
                    }else if(res["success"]=="prelogin"){
                        window.open("<?= WEB_DOMAIN ?>/dashboard", "_self");
                    }
                });
            }
        });
    </script>
</div>
<?php
no_footer();
?>