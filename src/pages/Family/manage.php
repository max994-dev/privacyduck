<div class="bg-white flex flex-col items-center pt-[40px] sm:pt-[120px] pb-[70px] px-[16px] sm:px-0 ">
    <div class="flex flex-col items-center">
        <fieldset>
            <legend class="text-[16px] sm:text-[24px] leading-[130%] tracking-[-0.03em] align-middle font-bold">Family</legend>
        </fieldset>
        <h2 class="mt-[24px] text-[#010205] text-[32px] sm:text-[48px] leading-[130%] tracking-[-0.03em] font-semibold text-center sm:max-w-[620px]">Manage all members easily on the Family page</h2>
    </div>
    <div class="mt-[32px] sm:mt-[60px] sm:px-[30px]">
        <div class="sm:mx-auto flex flex-col items-center xl:flex-row xl:items-stretch gap-[32px] xl:gap-[72px] ">
            <div class="order-2 xl:order-1 w-[343px] h-[244px] sm:w-[594px] sm:h-[422px] bg-[url('/assets/image/desktop/ex_family.png')] bg-cover bg-center bg-no-repeat sm:rounded-[20px] sm:shadow-[0px_0px_4px_0px_#D9D9D97A]">
            </div>
            <div class="order-1 xl:order-2 sm:w-[542px]">
                <p class="text-[#010205] font-medium text-[16px] leading-[150%] ">
                Identity theft often goes unnoticed until a child turns 18, only to find their credit 
                already negatively affected. With IdentityIQ credit and dark web monitoring, catch threats early, 
                remove kids personal info online, and secure their future before it’s too late.</p>
                <h2 class="text-[#010205] font-semibold text-[20px] leading-[150%] mt-[33px] sm:mt-[24px]">
                    All in one place:</h2>
                <?php
                $datas = [
                    "Send invitations",
                    "Сheck exposure statuses",
                    "Track removals progress",
                    "Upgrade members’ plans",
                    "And much more…"
                ];
                ?>
                <div class="flex flex-col gap-[12px] mt-[20px]">
                    <?php foreach ($datas as $data) { ?>
                        <div class="flex items-center gap-[4px]">
                            <?php require(BASEPATH . "/src/common/svgs/family/duck.php"); ?>
                            <p class="text-[#010205] font-medium text-[16px] leading-[150%]"><?= $data ?></p>
                        </div>
                    <?php } ?>
                </div>
                <button onclick="window.location.href='/signup'" class="hidden mt-[33px] xl:flex items-center justify-center space-x-[12px] w-[335px] h-[56px] rounded-full bg-gradient-to-r from-[#77B248] to-[#24A556]  hover:bg-none hover:bg-[#24A556]">
                    <h2 class="font-bold text-[18px] leading-[140%] tracking-[-0.02em] text-[white]">Start Your Free Scan</h2>
                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.5 12H19.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M12.5 5L19.5 12L12.5 19" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
            <button onclick="window.location.href='/signup'" class="xl:hidden order-3 mt-[33px] flex items-center justify-center space-x-[12px] w-[335px] h-[56px] rounded-full bg-gradient-to-r from-[#77B248] to-[#24A556]  hover:bg-none hover:bg-[#24A556]">
                <h2 class="font-bold text-[18px] leading-[140%] tracking-[-0.02em] text-[white]">Start Your Free Scan</h2>
                <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.5 12H19.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M12.5 5L19.5 12L12.5 19" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    </div>
</div>