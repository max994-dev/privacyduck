<style>
    @keyframes slideInFromTop {
        0% {
            transform: translateY(-100px);
        }

        100% {
            transform: translateY(0px);
        }
    }
</style>
<div class="relative overflow-hidden rounded-br-[32px] rounded-bl-[32px] bg-[#020609] lg:h-screen bg-cover bg-center bg-no-repeat">
    <div class="pt-[120px] md:pt-[173px] px-[16px] md:px-[24px] lg:pl-[80px] md:pr-0 flex flex-col lg:flex-row justify-between">
        <div class="flex flex-col">
            <div class="flex items-center space-x-[6px] text-center sm:text-left">
                <a href="/" class="flex items-center space-x-[6px]">
                    <h2 class="text-[#FFFFFFE5] text-[14px] sm:text-[16px] font-medium leading-[180%]">Home</h2>
                </a>
                <a href="#" class="flex items-center space-x-[6px]">
                    <?php require(BASEPATH . "/src/common/svgs/insurance/small_sign.php"); ?>
                    <h2 class="text-[#FFFFFFE5] text-[14px] sm:text-[16px] font-medium leading-[180%]">Insurance Protection</h2>
                </a>
            </div>
            <div class="mt-[40px] sm:mt-[60px] md:mt-[90px] flex flex-col gap-[24px] sm:gap-[32px] md:gap-[48px] max-w-[576px]">
                <div>
                    <div class="flex flex-col sm:flex-row gap-[8px] sm:gap-[16px] text-center sm:text-left">
                        <div class="flex items-center gap-[2px] justify-center sm:justify-start">
                            <?php require(BASEPATH . "/src/common/svgs/restoration/star.php"); ?>
                            <?php require(BASEPATH . "/src/common/svgs/restoration/star.php"); ?>
                            <?php require(BASEPATH . "/src/common/svgs/restoration/star.php"); ?>
                            <?php require(BASEPATH . "/src/common/svgs/restoration/star.php"); ?>
                            <?php require(BASEPATH . "/src/common/svgs/restoration/star.php"); ?>
                        </div>
                        <h2 class="font-semibold text-[#FFFFFF] text-[14px] sm:text-[16px] leading-[160%] tracking-[0.03em]">
                            Trusted by 50,000+ customers
                        </h2>
                    </div>
                    <div class="mt-[16px] sm:mt-[24px]">
                        <h2 class="font-semibold text-[#FFFFFF] text-[32px] sm:text-[48px] md:text-[60px] lg:text-[72px] leading-[110%] tracking-[0.03em] text-center sm:text-left">
                            Check Your
                        </h2>
                        <div class="relative flex justify-center sm:justify-normal items-center text-[#FFFFFF] text-[32px] sm:text-[48px] md:text-[60px] lg:text-[72px] font-semibold leading-[110%] tracking-[-0.03em] text-center sm:text-left">
                            <h1>Risk Label</h1>
                        </div>
                        <p class="mt-[16px] sm:mt-[24px] text-[#FFFFFFCC] font-medium text-[16px] sm:text-[18px] leading-[150%] tracking-[-0.02em] text-center sm:text-left">
                            When identity theft strikes, we're here to restore your peace of mind. Expert restoration services that get your life back on track.
                        </p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-[12px] sm:gap-[16px] items-center">
                    <button onclick="window.location.href='/signup'" class="flex items-center justify-center space-x-[12px] w-full sm:w-[335px] h-[56px] rounded-full bg-gradient-to-r from-[#77B248] to-[#24A556] hover:from-[#24A556] hover:to-[#24A556] transition-all duration-300">
                        <h2 class="font-bold text-[16px] sm:text-[18px] leading-[140%] tracking-[-0.02em] text-[white]">Add Insurance to My Plan</h2>
                        <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.5 12H19.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M12.5 5L19.5 12L12.5 19" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <button class="px-[24px] sm:px-[32px] flex items-center justify-center border-[1px] border-[#FFFFFF] text-[#FFFFFF] font-medium text-[14px] sm:text-[16px] leading-[140%] tracking-[-0.02em] rounded-full h-[56px] w-full sm:w-auto hover:bg-[#26A556] hover:text-white transition-all duration-300">
                        Learn How It Works
                    </button>
                </div>
            </div>
        </div>
        <div class="mt-[40px] lg:mt-[40px] pt-[23px] pb-[40px] lg:pb-0 sm:pl-[10px] sm:pr-[10px] lg:pr-[130px] relative">
            <div class="relative flex flex-col gap-[20px] sm:gap-[24px] pt-[24px] sm:pt-[32px] pl-[16px] sm:pl-[18px] pr-[16px] sm:pr-[14px] pb-[24px] sm:pb-[32px] bg-white rounded-[20px] sm:rounded-[30px] shadow-[0px_4px_10px_0px_#24A55642]">
                <img class="w-[120px] sm:w-[150px] lg:w-[200px] h-[120px] sm:h-[150px] lg:h-[202px] absolute top-[200px] sm:top-[220px] lg:top-[234px] right-[-60px] sm:right-[-80px] lg:right-[-112.22px] z-[10] hidden xl:block animate-pulse" style="animation: slideInFromTop 3s ease-in-out;" src="/assets/image/desktop/restoration/restoration_progress.png" alt="restoration privacy services in USA">
                <div class="flex flex-col gap-[12px] sm:gap-[16px]">
                    <h2 class="font-medium text-[#010205] text-[24px] sm:text-[28px] lg:text-[32px] text-center sm:text-left">Scanning In Progress</h2>
                    <div class="flex flex-col sm:flex-row gap-[8px] sm:gap-[6px] items-center">
                        <div class="relative w-full sm:w-[300px] lg:w-[421px] h-[24px] sm:h-[28px] lg:h-[31px] rounded-full bg-[#E8FCE7]">
                            <div class="absolute top-0 left-0 w-[60%] sm:w-[200px] lg:w-[285px] h-[24px] sm:h-[28px] lg:h-[31px] rounded-full bg-gradient-to-r from-[#24A556] to-[#77B24B]"></div>
                        </div>
                        <h2 class="font-extrabold text-[#24A556] text-[12px] sm:text-[14px] leading-[140%] tracking-[0.02em]">+127 points</h2>
                    </div>
                </div>
                <div class="flex flex-col gap-[4px] text-center sm:text-left">
                    <h2 class="align-bottom font-bold text-[20px] sm:text-[22px] lg:text-[24px] leading-[36px] sm:leading-[40px] lg:leading-[46px] text-[#010205]">$10.75<span class="text-[14px] sm:text-[16px]">/mo</span></h2>
                    <h2 class="font-medium text-[12px] sm:text-[14px] text-[#878C91]">Billed annually ($129.00/year)</h2>
                </div>
                <div class="flex flex-col">
                    <div class="flex flex-col gap-[10px] sm:gap-[13px]">
                        <h2 class="pl-[6px] text-[12px] sm:text-[14px] text-[#010205] font-medium text-center sm:text-left">Select Plan</h2>
                        <div class="flex flex-col sm:flex-row gap-[8px] sm:gap-[13px]">
                            <div class="flex justify-center items-center w-full sm:w-[115px] h-[40px] sm:h-[34px] rounded-full bg-[#24A556]">
                                <h2 class="font-medium text-[14px] text-white leading-[140%] tracking-[-0.02em]">Single</h2>
                            </div>
                            <div class="flex justify-center items-center w-full sm:w-[115px] h-[40px] sm:h-[34px] rounded-full bg-[#CDCDCD29]">
                                <h2 class="font-medium text-[14px] text-[#878C91] leading-[140%] tracking-[-0.02em]">Couple</h2>
                            </div>
                            <div class="flex justify-center items-center w-full sm:w-[115px] h-[40px] sm:h-[34px] rounded-full bg-[#CDCDCD29]">
                                <h2 class="font-medium text-[14px] text-[#878C91] leading-[140%] tracking-[-0.02em]">Family</h2>
                            </div>
                        </div>
                    </div>
                    <div class="mt-[20px] sm:mt-[26px] pl-[6px] flex flex-col gap-[10px] sm:gap-[13px]">
                        <h2 class="font-semibold text-[#010205] text-[14px] sm:text-[15px] text-center sm:text-left">1 Year, 1 Person</h2>
                        <div class="flex flex-col gap-[8px] sm:gap-[10px]">
                            <div class="flex items-start gap-[8px]">
                                <?php require(BASEPATH . "/src/common/svgs/restoration/check.php"); ?>
                                <h2 class="font-medium text-[11px] sm:text-[12px] text-[#010205] leading-[140%]">
                                    Remove unlimited aliases, previous names
                                </h2>
                            </div>
                            <div class="flex items-start gap-[8px]">
                                <?php require(BASEPATH . "/src/common/svgs/restoration/check.php"); ?>
                                <h2 class="font-medium text-[11px] sm:text-[12px] text-[#010205] leading-[140%]">
                                    Enhanced privacy tools like email and phone masking
                                </h2>
                            </div>
                            <div class="flex items-start gap-[8px]">
                                <?php require(BASEPATH . "/src/common/svgs/restoration/check.php"); ?>
                                <h2 class="font-medium text-[11px] sm:text-[12px] text-[#010205] leading-[140%]">
                                    Email, Chat, and Phone Support
                                </h2>
                            </div>
                            <div class="flex items-start gap-[8px]">
                                <?php require(BASEPATH . "/src/common/svgs/restoration/check.php"); ?>
                                <h2 class="font-medium text-[11px] sm:text-[12px] text-[#010205] leading-[140%]">
                                    Custom removal requests plus automated services
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="mt-[20px] sm:mt-[29px] flex justify-center items-center pr-[3px] gap-[6px] sm:gap-[8px]">
                        <div class="flex items-center gap-[2px] sm:gap-[3px]">
                            <?php require(BASEPATH . "/src/common/svgs/restoration/bluestar.php"); ?>
                            <?php require(BASEPATH . "/src/common/svgs/restoration/bluestar.php"); ?>
                            <?php require(BASEPATH . "/src/common/svgs/restoration/bluestar.php"); ?>
                            <?php require(BASEPATH . "/src/common/svgs/restoration/bluestar.php"); ?>
                            <?php require(BASEPATH . "/src/common/svgs/restoration/bluehalfstar.php"); ?>
                        </div>
                        <h2 class="font-semibold text-[#010205] text-[11px] sm:text-[12px] leading-[160%] tracking-[0.03em]">
                            4.7 stars on Google Review
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>