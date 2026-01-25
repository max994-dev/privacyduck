<div class="bg-[#FAFAFA] py-[50px] sm:py-[100px] pl-[15px] pr-[15px] sm:pl-[27px] sm:pr-[43px]">
    <div class="min-h-[400px] sm:h-[586px] bg-[#020609] rounded-[20px] sm:rounded-[30px] flex flex-col justify-center items-center p-[20px] sm:p-0">
        <div class="flex flex-col justify-center items-center gap-[20px] sm:gap-[32px]">
            <h2 class="font-semibold text-[24px] sm:text-[32px] leading-[110%] lg:justify-start lg:text-[48px] lg:leading-[130%] tracking-[-0.03em] text-white text-center">
                To Start Your Claim
            </h2>
            <p class="max-w-[688px] text-center text-[#FFFFFFE5] text-[16px] sm:text-[20px] leading-[150%]">
                If you're an Privacyduck member and may have been a victim
                of identity fraud, here's how you can start your insurance claim process
            </p>
        </div>
        <div class="mt-[30px] sm:mt-[60px] px-[15px] sm:px-[34.5px] py-[20px] sm:py-[36px] bg-[#FFFFFF3D] bg-blur-[30px] rounded-[15px] sm:rounded-[20px] gap-[10px] flex flex-col sm:flex-row justify-center">
            <?php
            $data = [
                [
                    "number" => "1",
                    "title" => "Contact Privacyduck Support",
                    "content" => "Contact Privacyduck Support from inside your mobile or desktop app. 
                                    If you received an Privacyduck alert about suspicious activity, 
                                    contact Support using the number provided on the fraud alert.",
                ],
                [
                    "number" => "2",
                    "title" => "Secure Your Policy",
                    "content" => "Secure a copy of your insurance policy. 
                                    Privacyduck will also walk you through how you can directly 
                                    make a claim with our insurance provider.",
                ],
                [
                    "number" => "3",
                    "title" => "Prepare Documentation",
                    "content" => "Prepare a detailed proof of loss to also file with your claim. 
                                    File within 60 days of you first learning about the fraudulent incident.",
                ]
            ];
            foreach ($data as $item) {
            ?>
                <div class="flex flex-col gap-[15px] sm:gap-[20px] max-w-[348px] w-full sm:w-auto">
                    <div class="flex justify-center">
                        <div class="w-[150px] sm:w-[197px] h-[28px] sm:h-[31px] bg-gradient-to-r from-[#77B248] to-[#24A556] restoration-claim flex justify-center items-center">
                            <h2 class="text-[16px] sm:text-[18px] leading-[140%] text-white font-bold tracking-[-0.02em]"><?php echo $item["number"]; ?></h2>
                        </div>
                    </div>
                    <h2 class="text-[18px] sm:text-[20px] leading-[140%] text-white font-semibold tracking-[-0.02em] text-center sm:text-left">
                        <?php echo $item["title"]; ?>
                    </h2>
                    <p class="text-[14px] sm:text-[16px] leading-[140%] text-[#FFFFFFCC] tracking-[-0.02em] text-center sm:text-left">
                        <?php echo $item["content"]; ?>
                    </p>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="mt-[30px] sm:mt-[50px] px-[15px] sm:px-[85px] flex flex-col lg:flex-row lg:justify-between items-start lg:items-center gap-[30px]">
        <div class="flex flex-col w-full lg:w-auto">
            <h2 class="text-[#000000] font-bold text-[20px] sm:text-[24px] leading-[140%] tracking-[-0.02em]">
                Need Help with Your Claim?
            </h2>
            <p class="mt-[20px] sm:mt-[29px] font-medium text-[14px] sm:text-[16px] leading-[150%] text-[#878C91] max-w-[559px]">
                Our restoration experts can help you navigate the insurance claim process
                and ensure you have all the necessary documentation for a successful claim.
            </p>
            <div class="flex flex-col sm:flex-row gap-[12px] sm:gap-[16px] mt-[20px] sm:mt-[26px]">
                <button class="flex items-center justify-center space-x-[12px] w-full sm:w-[257px] h-[50px] sm:h-[56px] rounded-full bg-gradient-to-r from-[#77B248] to-[#24A556] hover:bg-none hover:bg-[#24A556]">
                    <h2 class="font-bold text-[14px] sm:text-[16px] leading-[140%] tracking-[-0.02em] text-white">Get Claim Assistance</h2>
                    <svg width="20" height="21" class="sm:w-[24px] sm:h-[25px]" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 12.5H19" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M12 5.5L19 12.5L12 19.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
                <button class="flex items-center justify-center space-x-[12px] w-full sm:w-[233px] h-[50px] sm:h-[56px] rounded-full border border-[#26A556]">
                    <h2 class="font-medium text-[14px] sm:text-[16px] leading-[140%] tracking-[-0.02em] text-[#24A556]">Download Claim Guide</h2>
                </button>
            </div>
        </div>
        <div class="bg-[#FBFFFD] border border-[#F6F6F63B] rounded-[20px] sm:rounded-[30px] shadow-[0px_10px_20px_#9B9B9C24] w-full mt-[30px] lg:mt-0 max-w-[564.5px]">
            <div class="py-[20px] sm:py-[32px] pl-[15px] sm:pl-[18px] pr-[15px] sm:pr-[18px] flex flex-col gap-[20px] sm:gap-[24px]">
                <div class="flex gap-[10px] sm:gap-[13px]">
                    <?php require(BASEPATH . "/src/common/svgs/restoration/duck.php"); ?>
                    <div class="flex flex-col gap-[2px]">
                        <h2 class="font-semibold text-[#010205] text-[16px] sm:text-[18px]">Claim Checklist</h2>
                        <h2 class="text-[#878C91] text-[12px] sm:text-[14px]">Essential documents needed</h2>
                    </div>
                </div>
                <div class="flex flex-col gap-[8px] sm:gap-[10px] pl-[40px] sm:pl-[52px]">
                    <div class="flex gap-[6px] sm:gap-[8px] items-center">
                        <?php require(BASEPATH . "/src/common/svgs/restoration/check.php"); ?>
                        <h2 class="text-[14px] sm:text-[16px] text-[#878C91]">
                            Government-issued ID
                        </h2>
                    </div>
                    <div class="flex gap-[6px] sm:gap-[8px] items-center">
                        <?php require(BASEPATH . "/src/common/svgs/restoration/check.php"); ?>
                        <h2 class="text-[14px] sm:text-[16px] text-[#878C91]">
                            Police report (if filed)
                        </h2>
                    </div>
                    <div class="flex gap-[6px] sm:gap-[8px] items-center">
                        <?php require(BASEPATH . "/src/common/svgs/restoration/check.php"); ?>
                        <h2 class="text-[14px] sm:text-[16px] text-[#878C91]">
                            Fraud documentation
                        </h2>
                    </div>
                    <div class="flex gap-[6px] sm:gap-[8px] items-center">
                        <?php require(BASEPATH . "/src/common/svgs/restoration/check.php"); ?>
                        <h2 class="text-[14px] sm:text-[16px] text-[#878C91]">
                            Insurance policy details
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>