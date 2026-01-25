<?php
if (!isset($_SESSION["siteCtn"]) || !isset($_SESSION["fullName"]) || !isset($_SESSION["profileCtn"])) {
    $_SESSION["fullName"] = $_GET["fullname"];
    $_SESSION["siteCtn"] = random_int(1, 20);
    $_SESSION["profileCtn"] = random_int($_SESSION["siteCtn"], 100);
} else {
    if ($_SESSION["fullName"] != $_GET["fullname"]) {
        $_SESSION["fullName"] = $_GET["fullname"];
        $_SESSION["siteCtn"] = random_int(1, 20);
        $_SESSION["profileCtn"] = random_int($_SESSION["siteCtn"], 100);
    }
}
?>
<div class="flex justify-center mt-[48px]">
    <div class="flex rounded-full bg-[#FAFAFA] items-center">
        <div class=" px-[13px] py-[7px] sm:px-[24.5px] sm:py-[13px] text-[10px] sm:text-[18px] leading-[140%] font-medium text-[#010205] rounded-full transition-all whitespace-nowrap duration-200"
            data-type="one" data-people="1">
            1.Enter your information
        </div>
        <div class="px-[13px] py-[7px] sm:px-[24.5px] sm:py-[13px] text-[10px] sm:text-[18px] leading-[140%] font-medium text-[#010205] rounded-full transition-all whitespace-nowrap duration-200 "
            data-type="two" data-people="2">
            2.Scan data brokers
        </div>
        <div class=" px-[13px] py-[7px] sm:px-[24.5px] sm:py-[13px] text-[10px] sm:text-[16px] leading-[140%] font-medium text-[#24A556] rounded-full transition-all whitespace-nowrap duration-200 bg-[#24A55630] active "
            data-type="three" data-people="3">
            See results
        </div>
    </div>
</div>
<div class="px-[16px] mt-[40px] sm:mt-[48px]">
    <h1 class="text-[24px] sm:text-[36px] text-[#010205] font-semibold leading-[110%] tracking-[-0.03em]"><span
            class="text-[#24A556]"><?php echo $_SESSION["profileCtn"] ?></span> profiles on&nbsp;<span
            class="text-[#24A556]"><?php echo $_SESSION["siteCtn"] ?></span>&nbsp;sites</h1>
    <h1
        class="max-w-[324px] sm:max-w-none mt-[24px] sm:mt-[33px] text-[16px] sm:text-[18px] text-[#010205] leading-[130%]">
        found for <?php echo htmlspecialchars($_GET["fullname"] ?? ''); ?>,
        <?php echo htmlspecialchars($_GET["city"] ?? ''); ?>
    </h1>
</div>
<div class="px-[16px] mt-[24px] sm:mt-[48px]">
    <h1 class="text-[32px] sm:text-[48px] text-[#010205] font-bold leading-[110%] tracking-[-0.03em]">See detailed
        results.</h1>
    <h1 class="text-[32px] sm:text-[48px] text-[#010205] font-bold leading-[110%] tracking-[-0.03em]">It’s free.
    </h1>
    <h1 class="max-w-[324px] sm:max-w-none sm:mt-[16px] text-[16px] sm:text-[18px] text-[#010205] leading-[130%]">
        You can start removing your profiles yourself or with us.</h1>
    <div class="mt-[32px] w-[340px] sm:w-[366px]">
        <h5 class="font-medium text-[#9D9D9D] leading-[20px] text-[14px]">Email&nbsp;<span
                class=" text-[#AB4522]">*</span></h5>
        <div class="flex justify-center">
            <input id="email"
                class="mt-[6px] rounded-[8px] w-[340px] sm:w-[360px] h-[44px] px-[14px] py-[10px] bg-[#FBFBFB] placeholder:text-[16px] placeholder:leading-[24px] placeholder:text-[#9D9D9D] text-[16px] leading-[24px] text-[#010205]"
                placeholder="Enter your email" />
        </div>
        <div class="hidden text-[#AB4522] mt-[6px] text-[14px] leading-[20px]" id="invalid">The email is
            incorrect</div>
        <div class="mt-[24px] flex justify-center">
            <button id="continue"
                class="w-[340px] sm:w-[360px] h-[44px] rounded-[8px]  bg-[#5AB87F] but-shadow text-center text-[#FAFAFA] font-semibold leading-[24px] text-[16px] hover:bg-gradient-to-r hover:from-[#77B248] hover:to-[#24A556] active:bg-none active:bg-[#24A556]">Continue</button>
        </div>
        <!-- <div class="mt-[16px] flex justify-center">
            <h1
                class="relative text-[#010205] text-[14px] leading-[24px] font-semibold before:content-[''] before:absolute before:right-[200%] before:top-[60%] before:w-[144.5px] before:h-[1px] before:bg-[#9B9B9C]
            after:content-[''] after:absolute after:left-[200%] after:top-[60%] after:w-[144.5px] after:h-[1px] after:bg-[#9B9B9C]">
                or</h1>
        </div>
        <div class="mt-[24px] flex justify-center">
            <button id="gmail"
                class="flex space-x-[10px] justify-center items-center border border-[#24A556] w-[340px] sm:w-[360px] h-[44px] rounded-[8px]  but-shadow text-center text-[#010205] font-semibold leading-[24px] text-[16px]">
                <img src="/assets/image/desktop/gmail.svg" alt="gmail" />
                <h1>Continue with Google</h1>
            </button>
        </div>
        <div class="mt-[24px]">
            <h1 class="text-[12px] leading-[150%] text-[#010205]">
                By signing up or logging in, you acknowledge and agree to PrivacyDuck’s <span
                    class="text-[#24A556] underline">Terms of Services</span> and <spanspan
                    class="text-[#24A556] underline">Privacy Policy</span>
            </h1>
        </div> -->
    </div>
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

    $("#continue").click(function () {
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

            $.post("/signupProcess", {
                email: $("#email").val().trim(),
                fullname: "<?php echo $_SESSION["fullName"] ?>"
            }, function (res) {
                if (res["error"]) {
                    $("#email").addClass("valid-border");
                    $("#invalid").html(res["error"]);
                    $("#invalid").removeClass("hidden").addClass("flex");
                }
                else if (res["success"] == "verify") {
                    window.open("<?= WEB_DOMAIN ?>/verify", "_self");
                }
            });
        }
    });
</script>