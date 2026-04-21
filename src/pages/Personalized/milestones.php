<div class="px-[12.5px] sm:px-[35px] pt-[70px] bg-white">
        <div class="bg-[url('/assets/image/desktop/milestone_bg.png')] bg-cover bg-center bg-no-repeat rounded-[30px]">
            <div class="text-center min-w-[308px] sm:max-w-[688px] mx-auto pt-[91px]">
                <h2 class="text-[32px] sm:text-[48px] font-semibold text-white leading-[130%] tracking-[-0.03em]">Company Milestones</h2>
                <p class="mt-[32px] px-[16px]  text-[#FFFFFFE5] text-[16px] sm:text-[20px] leading-[150%]">Our founders, Name Surname, Name Surname, and Name Surname, created PrivacyDuck in 2020 when they realized the difficulty of navigating privacy issues in today’s interconnected and digital world.</p>
            </div>
            <div class="mt-[59px]">
                <div class="px-[12px] sm:px-[65px] py-[37px]">
                    <div class="flex items-center justify-between space-x-[5px]">
                        <?php for ($i = 0; $i < 4; $i++) { ?>
                            <div>
                                <h2 class="text-center text-[18px] sm:text-[32px] font-bold leading-[150%] tracking-[-0.03em] text-white">2020</h2>
                                <p class="max-w-[204px] text-center text-[#FFFFFFE5] text-[12px] leading-[160%] font-medium mt-[28px]">Our system automatically removes your data from 300+ websites. </p>
                            </div>
                        <?php } ?>
                    </div>
                    <div>
                        <div class="relative w-full  mx-auto h-20">
                            <!-- Background bar -->
                            <div class="absolute top-1/2 transform -translate-y-1/2 w-full h-0 border-t-2 border-dashed border-gray-300">
                                <div id="fillBar" class="absolute h-[4px] left-0 top-[-3px] bg-gradient-to-r from-green-500 to-green-300" style="width: 0%"></div>
                            </div>

                            <!-- Moving object (slider) -->
                            <img src="/src/common/svgs/personalized/duck.svg" id="slider" class="absolute bg-transparent top-1/2 transform -translate-y-1/2 w-[32px] h-[32px] shadow -translate-x-1/2 z-10 pointer-events-none"></img>

                            <!-- Dots -->
                            <div id="dotWrapper" class="absolute top-1/2 transform -translate-y-1/2 flex justify-between w-full">
                                <div class="dot w-4 h-4 bg-gray-400 rounded-full cursor-pointer z-20" data-index="1"></div>
                                <div class="dot w-4 h-4 bg-gray-400 rounded-full cursor-pointer z-20" data-index="2"></div>
                                <div class="dot w-4 h-4 bg-gray-400 rounded-full cursor-pointer z-20" data-index="3"></div>
                                <div class="dot w-4 h-4 bg-gray-400 rounded-full cursor-pointer z-20" data-index="4"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="mt-[10px] sm:mt-[91px] px-[20px] sm:px-[45px] flex flex-col items-center xl:flex-row xl:justify-between xl:items-start pb-[47px]">
                <div class="xl:max-w-[542px] font-medium text-[18px] text-center xl:text-left leading-[150%] text-white">
                    <h2>As digital tracking technology evolves, PrivacyDuck is committed to continuous innovation in order to provide people with the power to control their digital footprint.</h2>
                </div>
                <img class="w-[308px] h-[154px] sm:w-[668px] sm:h-[333px] mt-[32px] xl:mt-0" src="/assets/image/desktop/milestones.png" alt="digital tracking technology">
            </div>
        </div>
    </div>
</div>
<script>
    function personalized_dot_animation() {
        const fillBar = document.getElementById("fillBar");
        const slider = document.getElementById("slider");
        const dots = document.querySelectorAll(".dot");
        const dotWrapper = document.getElementById("dotWrapper");

        let dotPositions = [];
        let currentIndex = 0;

        function calculateDotPositions() {
            dotPositions = [];
            const wrapperRect = dotWrapper.getBoundingClientRect();

            dots.forEach(dot => {
                const rect = dot.getBoundingClientRect();
                const centerX = rect.left + rect.width / 2 - wrapperRect.left;
                dotPositions.push(centerX);
            });
        }

        function updateSliderAndBar(index) {
            const targetX = dotPositions[index];
            const percent = (index / (dots.length - 1)) * 100;

            gsap.to(slider, {
                x: targetX,
                duration: 0.5,
                ease: "power2.out"
            });

            gsap.to(fillBar, {
                width: `${percent}%`,
                duration: 0.5,
                ease: "power2.out"
            });

            dots.forEach((d, j) => {
                d.classList.toggle("active", j <= index);
                d.classList.toggle("bg-gray-400", j > index);
            });
        }

        function init() {
            calculateDotPositions();
            gsap.set(slider, { x: dotPositions[currentIndex] });
            gsap.set(fillBar, {
                width: `${(currentIndex / (dots.length - 1)) * 100}%`
            });
            dots.forEach((dot, i) => {
                dot.classList.toggle("active", i <= currentIndex);
                dot.classList.toggle("bg-gray-400", i > currentIndex);
            });
        }

        window.addEventListener("load", init);
        window.addEventListener("resize", () => {
            calculateDotPositions();
            updateSliderAndBar(currentIndex);
        });

        dots.forEach((dot, i) => {
            dot.addEventListener("click", () => {
                currentIndex = i;
                updateSliderAndBar(i);
            });
        });
    }

    personalized_dot_animation();
</script>