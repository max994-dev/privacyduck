<?php
$meta_title = "Sites We Cover for Online Privacy Protection | PrivacyDuck";
$meta_description = "Remove personal information from major data brokers with PrivacyDuck. Protect your privacy and limit exposure across multiple online platforms safely.";
$meta_url = "https://privacyduck.com/";
$meta_image = "https://privacyduck.com/assets/pageSEO/landing.jpg";

include_once(BASEPATH . "/src/common/meta.php");
main_head_start();
main_head_end();
main_header("black");
// main_splash();
?>
<div class=" px-[16px] sm:pl-[80px] sm:pr-[48px] pt-[149px] pb-[70px] lg:pt-[128px] lg:pb-[130px] bg-[#FAFAFA]">
    <div>
        <a href="/" class="flex items-center space-x-[6px]">
            <?php require(BASEPATH . "/src/common/svgs/sitecover/small_sign.php"); ?>
            <h1 class="text-[#515665] text-[16px] font-medium leading-[180%]">Sites We Cover</h1>
        </a>
    </div>
    <div class="flex items-center justify-between mt-[23px] sm:mt-[42px] ">
        <div class="md:max-w-[755px]">
            <h2 class="font-semibold text-[32px] md:text-[72px] tracking-[-0.03em] leading-[110%] text-[#010205]">
                PrivacyDuck removes personal information from 413 websites.
            </h2>
            <h2 class="mt-[24px] md:mt-[48px] text-[#010205F2] text-[14px] sm:text-[16px] font-medium leading-[180%]">
                Because the data broker industry is growing by leaps and bounds and new people search websites appear all the time, we do everything possible to cover them on time. With PrivacyDuck you can be sure, your name, current and previous addresses, phone numbers, photos of your home, age won’t pop up on Google search any longer. Below you will find the list of websites we remove from.
            </h2>
        </div>
        <div class="relative hidden xl:ml-[60px] w-[490px] h-[490px] xl:flex items-center justify-center">
            <div class="absolute  w-[490px] h-[490px] rounded-full border bg-transparent  border-[#24A556]">
            </div>
            <div class="absolute  w-[411.44px] h-[411.44px] rounded-full border bg-transparent  border-[#24A556]">
            </div>
            <div class="absolute w-[341px] h-[341px] rounded-full border bg-transparent  border-[#24A556]">
            </div>
            <div class="absolute w-[276px] h-[276px] rounded-full border bg-transparent  border-[#24A556]">
            </div>
            <div>
                <?php require_once(BASEPATH . "/src/common/svgs/sitecover/bigduck.php"); ?>
            </div>
            <img src="/assets/image/desktop/turnduck.svg" style="transform: rotate(100deg) translateX(245px) rotate(-100deg);"
                class="absolute rounded-full"></img>
            <img src="/assets/image/desktop/turnduck.svg" style="transform: rotate(220deg) translateX(245px) rotate(-220deg);"
                class="absolute rounded-full"></img>
            <img src="/assets/image/desktop/turnduck.svg" style="transform: rotate(340deg) translateX(245px) rotate(-340deg);"
                class="absolute rounded-full"></img>
            <img src="/assets/image/desktop/turnduck.svg" style="transform: rotate(170deg) translateX(205.72px) rotate(-170deg);"
                class="absolute rounded-full"></img>
            <img src="/assets/image/desktop/turnduck.svg" style="transform: rotate(70deg) translateX(170.5px) rotate(-70deg);"
                class="absolute rounded-full"></img>
            <img src="/assets/image/desktop/turnduck.svg" style="transform: rotate(290deg) translateX(170.5px) rotate(-290deg);"
                class="absolute rounded-full"></img>

        </div>
    </div>
    <div class="mt-[64px] sm:w-[595px]">
        <div>
            <h2 class="font-semibold text-[32px] text-[#010205] leading-[130%] tracking-[-0.03em]">People-search sites</h2>
        </div>
        <div class="mt-[32px] grid grid-cols-1 sm:grid-cols-2 gap-y-[16px]">
            <?php
            require_once("datas.php");
            for ($i = 0; $i < count($datas); $i++) { ?>
                <p class="text-[20px] font-medium leading-[130%] tracking-[-0.03em] text-[#010205] <?php if ($i % 2 != 0) { ?>sm:flex sm:justify-end<?php } ?>"><?= $datas[$i] ?></p>
            <?php } ?>
        </div>
    </div>
    <div class="mt-[64px] pr-[32px] hidden xl:flex justify-between">
        <div class="w-[595px]">
            <div>
                <h2 class="font-semibold text-[32px] text-[#010205] leading-[130%] tracking-[-0.03em]">Mirror people-search sites</h2>
            </div>
            <div class="mt-[32px] grid grid-cols-1 xl:grid-cols-2 gap-y-[16px]">
                <?php
                require_once("mirrordata.php");
                for ($i = 0; $i < count($mirrordatas); $i++) { ?>
                    <p class="text-[20px] font-medium leading-[130%] tracking-[-0.03em] text-[#010205] <?php if ($i % 2 != 0) { ?>flex justify-end<?php } ?>"><?= $mirrordatas[$i] ?></p>
                <?php } ?>
            </div>
        </div>
        <div class="w-[331px] h-[318px] bg-[#E8FCE7] border-l-[5px] border-[#24A556] rounded-[15px]">
            <div class="px-[32px] py-[46px] gap-y-[10px]">
                <i class="fa-solid fa-circle-exclamation text-[#24A556] text-[24px]"></i>
                <h2 class="text-[16px] font-medium leading-[150%] text-[#010205]">These sites do not publish your information on their domains.
                    Instead, they redirect visitors to real people-search sites from the above list.
                    Removal of your information from real people-search sites will protect you from mirror sites as well.</h2>
            </div>
        </div>
    </div>
    <div class="mt-[64px] xl:hidden">
        <div>
            <div>
                <h2 class="font-semibold text-[24px] sm:text-[32px] text-[#010205] leading-[130%] tracking-[-0.03em]">Mirror people-search sites</h2>
            </div>
            <div class="mt-[32px] flex justify-between">
                <div class="flex flex-col gap-y-[16px]">
                    <?php for ($i = 0; $i < 34; $i++) { ?>
                        <p class="text-[20px] font-medium leading-[130%] tracking-[-0.03em] text-[#010205]">24counter.com</p>
                    <?php } ?>
                </div>
                <div>
                    <div class="w-[171px] sm:w-[331px] bg-[#E8FCE7] border-l-[5px] border-[#24A556] rounded-[15px]">
                        <div class="p-[16px] flex flex-col gap-y-[10px]">
                            <i class="fa-solid fa-circle-exclamation text-[#24A556] text-[24px]"></i>
                            <h2 class="text-[12px] font-medium leading-[150%] text-[#010205]">These sites do not publish your information on their domains.
                                Instead, they redirect visitors to real people-search sites from the above list.
                                Removal of your information from real people-search sites will protect you from mirror sites as well.</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php main_footer(); ?>
