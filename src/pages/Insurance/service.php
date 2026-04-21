<style>
    .card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }

    .card-expanded {
        width: 600px;
    }

    .card-collapsed {
        width: 256px;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .card-collapsed:hover {
        transform: translateY(-2px) scale(1.02);
    }

    .card-content {
        transition: opacity 0.3s ease-in-out;
    }

    .card-collapsed .expanded-content {
        opacity: 0;
        pointer-events: none;
    }

    .card-collapsed .card-img1 {
        opacity: 0;
        pointer-events: none;
    }

    .card-collapsed .card-img2 {
        opacity: 1;
        pointer-events: none;
    }

    .card-collapsed .start-button {
        opacity: 0;
        pointer-events: none;
    }

    .card-expanded .expanded-content {
        opacity: 1;
        pointer-events: auto;
    }

    .card-expanded .card-img1 {
        opacity: 1;
        pointer-events: auto;
    }

    .card-expanded .card-img2 {
        opacity: 0;
        pointer-events: auto;
    }

    .card-expanded .start-button {
        opacity: 1;
        pointer-events: auto;
    }
</style>
<div class="bg-[#FAFAFA] p-[16px] ">
    <div class="bg-white rounded-[28px] flex flex-col items-center pt-[65px] pb-[100px]">
        <div class="flex flex-col items-center gap-[32px] px-[16px]">
            <h2 class="font-semibold text-[#010205] text-[24px] text-center sm:text-left sm:text-[48px] leading-[130%] tracking-[-0.03em]">
                Complete Restoration Services
            </h2>
            <p class="font-medium text-[#878C91] text-[18px] leading-[140%] text-center max-w-[550px]">
                Our expert team handles every aspect of your restoration process,
                so you can focus on getting back to your life.
            </p>
        </div>
        <div class="mt-[50px] hidden xl:flex gap-[42px] xl:max-w-[1200px]">
            <!-- Card 1 -->
            <div id="card1" class="relative card card-expanded bg-[#FBFFFD] h-[434px]  rounded-[20px] shadow-[0_10px_20px_#9B9B9C24] overflow-hidden">
                <img src="/assets/image/desktop/restoration/rapid.png" class="card-img1 absolute w-[278px] h-[549px] top-[44px] right-[-16px]" alt="restoration privacy services in USA" />
                <img src="/assets/image/desktop/restoration/rapid.png" class="card-img2 absolute w-[154px] h-[305px] bottom-[-159px] right-[-11px]" alt="restoration privacy services in USA" />
                <div class="pt-[35px] pl-[24px] pr-[17px] pb-[29px] h-full flex flex-col justify-between">
                    <div>
                        <h2 class="text-[24px] leading-[110%] tracking-[-0.03em] font-semibold text-[#010205]">Rapid Response</h2>
                        <p class="font-medium text-[#010205A3] text-[16px] leading-[150%] mt-[27px]  max-w-[304px]">
                            We begin working on your case within 24 hours, ensuring
                            fast action when time matters most. Our dedicated team is available around the clock to respond
                            to emergencies and start the restoration process immediately.
                        </p>
                    </div>
                    <div>
                        <button class="start-button flex items-center justify-center space-x-[12px] w-[223px] h-[67px] rounded-full bg-gradient-to-r from-[#77B248] to-[#24A556]  hover:bg-none hover:bg-[#24A556]">
                            <h2 class="font-bold text-[16px] leading-[140%] tracking-[-0.02em] text-[white]">Start Free Trial</h2>
                            <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 12.5H19" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12 5.5L19 12.5L12 19.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div id="card2" class="relative card card-expanded bg-[#FBFFFD] h-[434px]  rounded-[20px] shadow-[0_10px_20px_#9B9B9C24] overflow-hidden">
                <img src="/assets/image/desktop/restoration/expert.png" class="card-img1 absolute w-[335px] h-[366.61px] top-[80px] right-[-35px]" alt="expert team" />
                <img src="/assets/image/desktop/restoration/expert.png" class="card-img2 absolute w-[185px] h-[202.46px] bottom-[-15.46px] right-[-13px]" alt="restoration privacy services in USA" />
                <div class="pt-[35px] pl-[24px] pr-[17px] pb-[29px] h-full flex flex-col justify-between">
                    <div>
                        <h2 class="text-[24px] leading-[110%] tracking-[-0.03em] font-semibold text-[#010205]">Expert Team</h2>
                        <p class="font-medium text-[#010205A3] text-[16px] leading-[150%] mt-[27px] max-w-[304px]">
                            Certified restoration specialists with years of experience handling
                            complex identity theft cases. Our team includes former law enforcement,
                            cybersecurity experts, and legal professionals.
                        </p>
                    </div>
                    <div>
                        <button class="start-button flex items-center justify-center space-x-[12px] w-[223px] h-[67px] rounded-full bg-gradient-to-r from-[#77B248] to-[#24A556]  hover:bg-none hover:bg-[#24A556]">
                            <h2 class="font-bold text-[16px] leading-[140%] tracking-[-0.02em] text-[white]">Start Free Trial</h2>
                            <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 12.5H19" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12 5.5L19 12.5L12 19.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div id="card3" class="relative card card-expanded bg-[#FBFFFD] h-[434px]  rounded-[20px] shadow-[0_10px_20px_#9B9B9C24] overflow-hidden">
                <img src="/assets/image/desktop/restoration/protection.png" class="card-img1 absolute w-[437px] h-[244px] top-[161px] right-[-137px]" alt="protection" />
                <img src="/assets/image/desktop/restoration/protection.png" class="card-img2 absolute w-[240px] h-[134px] bottom-[-19px] right-[8px]" alt="protection" />
                <div class="pt-[35px] pl-[24px] pr-[17px] pb-[29px] h-full flex flex-col justify-between">
                    <div>
                        <h2 class="text-[24px] leading-[110%] tracking-[-0.03em] font-semibold text-[#010205]">
                            Full Protection
                        </h2>
                        <p class="font-medium text-[#010205A3] text-[16px] leading-[150%] mt-[27px]  max-w-[304px]">
                            Comprehensive monitoring and protection services to prevent future incidents from occurring.
                            We provide ongoing security
                            measures and real-time alerts to keep your identity safe.
                        </p>
                    </div>
                    <div>
                        <button class="start-button flex items-center justify-center space-x-[12px] w-[223px] h-[67px] rounded-full bg-gradient-to-r from-[#77B248] to-[#24A556]  hover:bg-none hover:bg-[#24A556]">
                            <h2 class="font-bold text-[16px] leading-[140%] tracking-[-0.02em] text-[white]">Start Free Trial</h2>
                            <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 12.5H19" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12 5.5L19 12.5L12 19.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-[50px] xl:hidden flex flex-col gap-[42px] sm:max-w-[1200px]">
            <!-- Card 1 -->
            <div class="relative w-[300px] sm:w-[600px] bg-[#FBFFFD] h-[434px]  rounded-[20px] shadow-[0_10px_20px_#9B9B9C24] overflow-hidden">
                <img src="/assets/image/desktop/restoration/rapid.png" class="card-img1 hidden sm:block absolute w-[278px] h-[549px] top-[44px] right-[-16px]" alt="restoration privacy services in USA" />
                <div class="pt-[35px] pl-[24px] pr-[17px] pb-[29px] h-full flex flex-col justify-between">
                    <div>
                        <h2 class="text-[24px] leading-[110%] tracking-[-0.03em] font-semibold text-[#010205]">Rapid Response</h2>
                        <p class="font-medium text-[#010205A3] text-[16px] leading-[150%] mt-[27px]  max-w-[304px]">
                            We begin working on your case within 24 hours, ensuring
                            fast action when time matters most. Our dedicated team is available around the clock to respond
                            to emergencies and start the restoration process immediately.
                        </p>
                    </div>
                    <div>
                        <button class="start-button flex items-center justify-center space-x-[12px] w-[223px] h-[67px] rounded-full bg-gradient-to-r from-[#77B248] to-[#24A556]  hover:bg-none hover:bg-[#24A556]">
                            <h2 class="font-bold text-[16px] leading-[140%] tracking-[-0.02em] text-[white]">Start Free Trial</h2>
                            <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 12.5H19" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12 5.5L19 12.5L12 19.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="relative w-[300px] sm:w-[600px] bg-[#FBFFFD] h-[434px]  rounded-[20px] shadow-[0_10px_20px_#9B9B9C24] overflow-hidden">
                <img src="/assets/image/desktop/restoration/expert.png" class="card-img1 hidden sm:block absolute w-[335px] h-[366.61px] top-[80px] right-[-35px]" alt="expert team" />
                <div class="pt-[35px] pl-[24px] pr-[17px] pb-[29px] h-full flex flex-col justify-between">
                    <div>
                        <h2 class="text-[24px] leading-[110%] tracking-[-0.03em] font-semibold text-[#010205]">Expert Team</h2>
                        <p class="font-medium text-[#010205A3] text-[16px] leading-[150%] mt-[27px] max-w-[304px]">
                            Certified restoration specialists with years of experience handling
                            complex identity theft cases. Our team includes former law enforcement,
                            cybersecurity experts, and legal professionals.
                        </p>
                    </div>
                    <div>
                        <button class="start-button flex items-center justify-center space-x-[12px] w-[223px] h-[67px] rounded-full bg-gradient-to-r from-[#77B248] to-[#24A556]  hover:bg-none hover:bg-[#24A556]">
                            <h2 class="font-bold text-[16px] leading-[140%] tracking-[-0.02em] text-[white]">Start Free Trial</h2>
                            <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 12.5H19" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12 5.5L19 12.5L12 19.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="relative w-[300px] sm:w-[600px] bg-[#FBFFFD] h-[434px]  rounded-[20px] shadow-[0_10px_20px_#9B9B9C24] overflow-hidden">
                <img src="/assets/image/desktop/restoration/protection.png" class="card-img1 hidden sm:block absolute w-[437px] h-[244px] top-[161px] right-[-137px]" alt="protection" />
                <div class="pt-[35px] pl-[24px] pr-[17px] pb-[29px] h-full flex flex-col justify-between">
                    <div>
                        <h2 class="text-[24px] leading-[110%] tracking-[-0.03em] font-semibold text-[#010205]">
                            Full Protection
                        </h2>
                        <p class="font-medium text-[#010205A3] text-[16px] leading-[150%] mt-[27px]  max-w-[304px]">
                            Comprehensive monitoring and protection services to prevent future incidents from occurring.
                            We provide ongoing security
                            measures and real-time alerts to keep your identity safe.
                        </p>
                    </div>
                    <div>
                        <button class="start-button flex items-center justify-center space-x-[12px] w-[223px] h-[67px] rounded-full bg-gradient-to-r from-[#77B248] to-[#24A556]  hover:bg-none hover:bg-[#24A556]">
                            <h2 class="font-bold text-[16px] leading-[140%] tracking-[-0.02em] text-[white]">Start Free Trial</h2>
                            <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 12.5H19" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12 5.5L19 12.5L12 19.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-[50px] flex flex-col sm:flex-row justify-center items-center gap-[16px]">
            <div class="flex items-center gap-[2px]">
                <?php require(BASEPATH . "/src/common/svgs/restoration/star.php"); ?>
                <?php require(BASEPATH . "/src/common/svgs/restoration/star.php"); ?>
                <?php require(BASEPATH . "/src/common/svgs/restoration/star.php"); ?>
                <?php require(BASEPATH . "/src/common/svgs/restoration/star.php"); ?>
                <?php require(BASEPATH . "/src/common/svgs/restoration/star.php"); ?>
            </div>
            <h2 class="font-medium text-center sm:text-left text-[#010205] text-[16px] leading-[160%] tracking-[-0.03em]">
                Trusted by 50,000+ customers&nbsp;&nbsp;●&nbsp;&nbsp;15+ Years Experience&nbsp;&nbsp;●&nbsp;&nbsp;24/7 Emergency Hotline&nbsp;&nbsp;●&nbsp;&nbsp;Real-time Monitoring&nbsp;&nbsp;
            </h2>
        </div>
    </div>
</div>

<script>
    const cards = document.querySelectorAll('.card');

    function expandCard(targetCard) {
        cards.forEach(card => {
            if (card === targetCard) {
                card.classList.remove('card-collapsed');
                card.classList.add('card-expanded');
            } else {
                card.classList.remove('card-expanded');
                card.classList.add('card-collapsed');
            }
        });
    }

    // Add click event listeners to all cards
    cards.forEach(card => {
        card.addEventListener('click', () => {
            expandCard(card);
        });
    });

    // Initialize with first card expanded
    expandCard(document.getElementById('card1'));
</script>