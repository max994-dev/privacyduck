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
                                <h1 style="font-family: 'Alatsi', sans-serif;" class="text-[42px] tracking-[-0.02em] uppercase text-[#010205]">
                                    Privacy<label class="text-[#FFCF50]" style="font-family: 'Alatsi', sans-serif;">Duck</label>
                                </h1>
                                <?php require(BASEPATH . '/src/common/svgs/business/landing/duck.php'); ?>
                            </div>
                            <h1 style="font-family: 'Alatsi', sans-serif;" class="relative top-[-20px] text-[32px] tracking-[-0.02em] text-[#010205]">
                                BUSINESS
                            </h1>
                        </div>
                    </a>
                </div>
                <div class="text-center mt-[32px]">
                    <h1 class="font-bold text-[32px] sm:text-[40px] leading-[38px] text-[#010205]">
                        Log In
                    </h1>
                </div>
                <a href="<?= WEB_DOMAIN ?>/business/quote/logininfo" id="gmail"
                    class="mt-[32px] flex space-x-[10px] justify-center items-center w-[340px] sm:w-[392px] h-[44px] rounded-[8px] shadow-[0px_2px_7px_#1018282B] bg-[#00530F]">
                    <h1 class="text-[#FFCF50] font-semibold text-[16px] leading-[24px]">
                        Log in with Email
                    </h1>
                </a>
                <div class="mt-[29px] flex justify-center">
                    <h1
                        class="relative text-[#010205] text-[14px] leading-[24px] font-semibold before:content-[''] before:absolute before:right-[200%] before:top-[60%] before:w-[177.5px] before:h-[2px] before:bg-[#EEEEEE]
            after:content-[''] after:absolute after:left-[200%] after:top-[60%] after:w-[177.5px] after:h-[2px] after:bg-[#EEEEEE]">
                        or</h1>
                </div>
                <div class="mt-[24px] flex flex-col items-center gap-[16px]">
                    <button id="gmail"
                        class="flex space-x-[8px] justify-center items-center w-[340px] sm:w-[392px] h-[44px] rounded-[8px] shadow-[0px_2px_7px_#1018282B]">
                        <img src="/assets/image/desktop/gmail.svg" class="w-[16px] h-[16px]" alt="gmail" />
                        <h1 class="text-[16px] leading-[24px] text-[#010205]">Log In with Google</h1>
                    </button>
                    <button id="gmail"
                        class="flex space-x-[8px] justify-center items-center w-[340px] sm:w-[392px] h-[44px] rounded-[8px] bg-[#1B1D1E] shadow-[0px_2px_7px_#1018282B]">
                        <img src="/assets/image/desktop/apple.svg" class="w-[24px] h-[24px]" alt="gmail" />
                        <h1 class="text-[16px] leading-[24px] text-[#FFFFFF]">Log In with Apple</h1>
                    </button>
                </div>
                <div class="mt-[32px] flex justify-center items-center gap-[4px]">
                    <h1 class="text-[14px] leading-[20px] text-[#9D9D9D]">Don't have an account?</h1>
                    <a href="<?= WEB_DOMAIN ?>/business/quote/signup">
                        <h1 class="text-[14px] leading-[20px] text-[#FFCF50]">Sign Up</h1>
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
</div>
<?php
no_footer();
?>