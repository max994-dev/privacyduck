<div class="pt-[70px] pb-[40px] px-[16px] lg:px-[35px]">
    <div class="py-[30px] sm:py-[100px] bg-[url('/assets/image/desktop/milestone_bg.png')] bg-cover bg-center bg-no-repeat rounded-[30px] flex justify-center items-center">
        <div class="flex flex-col items-center px-[10px]">
            <h2 class="w-[309px] sm:w-auto text-center word-wrap font-semibold text-[32px] lg:text-[48px] leading-[130%] tracking-[-0.03em] align-middle text-white">Save up to 30% off all your plans!</h2>
            <h2 class="w-[309px] sm:w-auto mt-[32px] text-[16px] sm:text-[20px] leading-[150%] text-[#FFFFFFE5] text-center">The more you add the greater your discount on all plans</h2>
            <div class="mt-[60px] bg-[#FFFFFF3D] bg-blur-[30px] rounded-[20px] lg:w-[810px]">
                <div class="p-[13px] pb-[20px]">
                    <div class="flex items-center space-x-[5px]">
                        <?php require(BASEPATH . "/src/common/svgs/dashboard/family/close.php"); ?>
                        <h2 class="flex-1 font-semibold text-[18px] leading-[140%] tracking-[-0.02em]  text-white">Add members to paid plans & get <span class="font-[800] text-[#24A556]">UP TO 30% OFF</span> all your plans!</h2>
                    </div>
                    <div class="mt-[20px] flex justify-center space-x-[4px]">
                        <?php
                        $datas = [
                            ["number" => "2", "discount" => 20],
                            ["number" => "3", "discount" => 25],
                            ["number" => "4+", "discount" => 30]
                        ];
                        foreach ($datas as $data) { ?>
                            <div class="flex flex-col items-center gap-[19px]">
                                <h2 class="font-semibold text-[14px] md:text-[20px] leading-[140%] tracking-[-0.02em] text-[#FFFFFF]"><?php echo $data["number"]; ?> paid plans</h2>
                                <div class="w-[98px] md:w-[197px] h-[31.5px] breadcrumb2 bg-gradient-to-r from-[#77B248] to-[#24A556] relative flex justify-center items-center">
                                    <h2 class="font-bold text-[16px] md:text-[18px] leading-[140%] tracking-[-0.02em] text-white"><?php echo $data["discount"]; ?>% OFF</h2>
                                </div>
                            </div>
                        <?php }
                        ?>
                    </div>
                </div>
            </div>
            <button onclick="window.location.href='/signup'" class="mt-[60px] flex items-center justify-center space-x-[12px] w-[335px] h-[56px] rounded-full bg-gradient-to-r from-[#77B248] to-[#24A556]  hover:bg-none hover:bg-[#24A556]">
                <h2 class="font-bold text-[18px] leading-[140%] tracking-[-0.02em] text-[white]">Start Your Free Scan</h2>
                <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.5 12H19.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M12.5 5L19.5 12L12.5 19" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    </div>
</div>