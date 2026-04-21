<?php
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
                        Speak with Sales
                    </h1>
                </div>
                <div class="mt-[32px] flex justify-center items-center">
                    <h1 class="text-center text-[16px] leading-[24px] px-[16px] sm:max-w-[554px]">
                    We sent an email to <span class="font-bold"> hello@privacyduck.com</span>. 
                    Please enter the verification code in the email to confirm your address and proceed. 
                    If you don’t see the email, check your spam folder.
                    </h1>
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