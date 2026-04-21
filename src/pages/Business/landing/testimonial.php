<div class="py-[60px] px-[35px]">
    <div class="h-[548px] flex justify-center items-center bg-[url('/assets/image/desktop/dashboard/work/work_bg.png')] bg-cover rounded-[30px] shadow-[0px_4px_4px_#00000040]">
        <div class="max-w-[1123px] flex gap-[13px] relative">
            <div class="relative t-[24px]">
                <?php require BASEPATH . "/src/common/svgs/business/landing/comma.php"; ?>
            </div>
            <div class="flex flex-col">
                <div class="flex gap-[28px]">
                    <button id="prev">
                        <?php require(BASEPATH . "/src/common/svgs/business/landing/prev.php") ?>
                    </button>
                    <button id="next">
                        <?php require(BASEPATH . "/src/common/svgs/business/landing/next.php") ?>
                    </button>
                </div>
                <div class="swiper mySwiper max-w-[1060px] mt-[22px]">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div>
                                <h2 class="text-[38px] leading-[160%] tracking-[-0.03em] text-white font-medium">
                                We used to manually request takedowns from data brokers, and it was slow and unreliable. 
                                PrivacyDuck’s employee data protection software now handles everything 
                                for us, and the visibility it gives our compliance team is unmatched.</h2>
                                <div class="mt-[48px] flex items-center space-x-[24px]">
                                    <?php require BASEPATH . "/src/common/svgs/business/landing/avatar.php"; ?>
                                    <div>
                                        <div class="font-bold text-[20px] leading-[180%] text-white">Raj S.</div>
                                        <div class="font-medium text-[16px] leading-[180%] text-[#FFFFFFCC]">CISO at FinTechCorp</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div>
                                <h2 class="text-[38px] leading-[160%] tracking-[-0.03em] text-white font-medium">
                                We needed a privacy compliance platform that covered both automated and manual removal workflows. 
                                PrivacyDuck delivered, it’s simple, effective, and fits seamlessly into our internal review process.</h2>
                                <div class="mt-[48px] flex items-center space-x-[24px]">
                                    <?php require BASEPATH . "/src/common/svgs/business/landing/avatar.php"; ?>
                                    <div>
                                        <div class="font-bold text-[20px] leading-[180%] text-white">Anne L.</div>
                                        <div class="font-medium text-[16px] leading-[180%] text-[#FFFFFFCC]">Privacy Officer at Meditrust Healthcare</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div>
                                <h2 class="text-[38px] leading-[160%] tracking-[-0.03em] text-white font-medium">
                                Trying to manage personal data exposure across hundreds of employees used to take hours each week. Now our dashboard shows who’s at risk, 
                                and the corporate data removal service handles it all.</h2>
                                <div class="mt-[48px] flex items-center space-x-[24px]">
                                    <?php require BASEPATH . "/src/common/svgs/business/landing/avatar.php"; ?>
                                    <div>
                                        <div class="font-bold text-[20px] leading-[180%] text-white">Mark D.</div>
                                        <div class="font-medium text-[16px] leading-[180%] text-[#FFFFFFCC]">Head of InfoSec at EduConnect</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div>
                                <h2 class="text-[38px] leading-[160%] tracking-[-0.03em] text-white font-medium">
                                One of our execs was being impersonated using old social content. PrivacyDuck’s scanning system flagged the exposure and got it taken down fast. 
                                We didn’t know we needed this kind of executive data removal software until we did.</h2>
                                <div class="mt-[48px] flex items-center space-x-[24px]">
                                    <?php require BASEPATH . "/src/common/svgs/business/landing/avatar.php"; ?>
                                    <div>
                                        <div class="font-bold text-[20px] leading-[180%] text-white">Lena M.</div>
                                        <div class="font-medium text-[16px] leading-[180%] text-[#FFFFFFCC]">Risk Manager at BrightWave Tech</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function slide_init() {
        new Swiper(".mySwiper", {
            loop: true,
            slidesPerView: 1,
            navigation: {
                nextEl: "#next",
                prevEl: "#prev",
            },
        });
    }
    slide_init();
</script>