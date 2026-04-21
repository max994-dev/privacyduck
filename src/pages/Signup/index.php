
<?php
isReverseLogin();
$meta_title = "PrivacyDuck - Signup";
$meta_description = "Protect your privacy with PrivacyDuck. We remove your personal data from the internet and safeguard your online presence. Get started today!";
$meta_url = "https://privacyduck.com/";
$meta_image = "https://privacyduck.com/assets/pageSEO/landing.jpg";

include_once(BASEPATH . "/src/common/meta.php");
main_head_start();
?>
<link href="/assets/css/signup.css" rel="stylesheet">
<?php
main_head_end();
?>
<div class="xl:flex bg-white">
    <div class="xl:w-[calc(100vw-514px)]">
        <div class="hidden xl:block h-[29px]"></div>
        <div class="flex px-[16px] justify-center items-center overflow-y-auto">
            <div>
                <div class="flex justify-center">
                    <a href="/"><img class="w-[234px] mt-[51px] sm:mt-0 h-[45px]" src="/assets/image/desktop/logo4.svg"
                            alt="logo" /></a>
                </div>
                <div id="content"><?php require_once(BASEPATH . '/src/pages/Signup/info.php') ?></div>
            </div>
        </div>
    </div>
    <div
        class="hidden relative w-[514px] bg-[url('/assets/image/desktop/login.png')] bg-cover xl:flex items-center rounded-tl-[30px] rounded-bl-[30px] min-h-screen">
        <div class="ml-[79px]">
            <h1 class="font-bold text-[56px] leading-[110%] text-white">3758</h1>
            <h1 class="mt-[20px] text-white leading-[130%] text-[18px]">People found their profiles this week</h1>
            <h1 class="mt-[66px] font-bold text-[56px] leading-[110%] text-white">46</h1>
            <h1 class="mt-[20px] text-white leading-[130%] text-[18px]">Profiles are found for a person on average</h1>
        </div>
        <img class="absolute w-[49.83px] h-[55px] bottom-[35px] right-[36px]" src="/assets/image/desktop/login_duck.svg"
            alt="mark" />
    </div>
</div>
<?php
no_footer();
?>