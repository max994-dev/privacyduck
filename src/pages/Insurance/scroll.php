<script src="assets/js/canvas-bg-2.js" defer></script>
<div class="pb-[120px] relative">
    <div class="pt-[101px] px-[16px] flex justify-center lg:block  lg:px-[80px] z-[5] relative">
        <div class="lg:flex lg:justify-between items-center">
            <h2
                class="flex max-w-[327px] font-semibold text-[32px] leading-[110%] lg:justify-start lg:text-[48px] lg:leading-[130%] tracking-[-0.03em] items-center text-[#010205]">
                How Our Restoration Process Works
            </h2>
            <div class="flex mt-[48px] flex-col max-w-[557px] lg:mt-0">
                <p class="text-[16px] font-medium leading-[180%] text-[#878C91]">
                    Our proven 4-step process ensures your identity is restored quickly and completely.
                </p>
                <a href="/signup/"
                    class="hidden mt-[28px] lg:flex justify-center items-center px-[32px] py-[16px] bg-green-600 text-white font-bold text-[16px] leading-[140%] rounded-full hover:bg-green-700 w-fit">
                    Get Started
                    <svg class="ml-[38px]" width="25" height="24" viewBox="0 0 25 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.5 12H19.5" stroke="white" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M12.5 5L19.5 12L12.5 19" stroke="white" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <div class="flex flex-col w-full px-[16px] sm:hidden mt-[47px]">
        <a href="#"
            class="inline-flex text-center justify-center items-center gap-[42px] px-10 py-4 bg-green-600 text-white text-[16px] leading-[140%] tracking-[2%] font-bold rounded-full hover:bg-green-700">
            Get Started
            <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5.5 12H19.5" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M12.5 5L19.5 12L12.5 19" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </a>
    </div>

    <div class="relative max-w-4xl mx-auto mt-[77px] lg:mt-[80px] min-h-screen">
        <!-- Timeline line -->
        <div
            class="absolute w-[1px] left-[26.5px] sm:left-[5%] lg:left-[50%] top-[-15px] bottom-[-15px] bg-gray-300 z-0">
            <div id="timeline-fill"
                class="absolute rounded-[3px] top-[-15px] left-[-2px] w-[3px] bg-green-500 transition-all duration-700 ease-in-out"
                style="height: 15;"></div>
            <object id="duck" type="image/svg+xml" data="assets/image/desktop/duck2.svg"
                class="absolute left-[3px] transform -translate-x-1/2 bg-[#FAFAFA] rounded-full z-10 transition-all duration-700"
                style="top: 0; width: 32px; height: 33px;"></object>
        </div>

        <!-- Cards -->
        <div class="space-y-8 lg:space-y-28">
            <div class="relative flex items-center overflow-hidden">
                <div class="w-1/2 hidden lg:block"></div>
                <div class="w-[294px] w-[95%] lg:w-1/2 pl-[55px] sm:pl-[96px] lg:pl-[60px] text-left">
                    <div class="timeline-card w-full lg:w-[381px] h-[280px] bg-[url('/assets/image/desktop/restoration/1.png')] bg-cover bg-center bg-no-repeat p-5 rounded-[20px]  opacity-10 transition-opacity duration-[1500ms]"
                        data-index="0">
                        <div class="relative sm:pl-[24px] py-[32px]">
                            <lottie-player class="absolute top-[-26px] left-[17px]"
                                src="/assets/image/desktop/signup.json" background="transparent" speed="1" loop
                                autoplay style="width: 80px; height: 96px;"></lottie-player>
                            <h3 class="font-semibold text-[#010205] text-[24px] sm:text-[26px] leading-[150%] tracking-[-0.03em] mt-[56px]"
                                style="font-family: 'Plus Jakarta Sans', sans-serif;">Initial Assessment</h3>
                            <p class="text-[#878C91] font-medium text-[14px] mt-[16px] leading-[160%]"
                                style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                We conduct a comprehensive analysis of your situation to understand the scope of
                                the identity theft and create a personalized restoration plan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative flex items-center overflow-hidden">
                <div class="order-2 pl-[55px] sm:pl-[96px] lg:pl-[0px] lg:order-1 w-[95%] lg:w-[381px]  text-left">
                    <div class="timeline-card w-full h-[280px] bg-[url('/assets/image/desktop/restoration/2.png')] bg-cover bg-center bg-no-repeat rounded-[20px] p-5  opacity-10 transition-opacity duration-[1500ms]"
                        data-index="1">
                        <div class="relative pt-[26px] sm:pl-[24px] sm:py-[32px]">
                            <lottie-player class="absolute top-[-11px] left-[13px]"
                                src="/assets/image/desktop/search.json" background="transparent" speed="1" loop
                                autoplay style="width: 80px; height: 96px;"></lottie-player>
                            <h3 class="font-semibold text-[#010205] text-[24px] sm:text-[26px] leading-[150%] tracking-[-0.03em] mt-[56px]"
                                style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                Documentation & Filing
                            </h3>
                            <p class="text-[#878C91] font-medium text-[14px] mt-[7px] sm:mt-[20px] leading-[160%]"
                                style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                Our experts prepare and file all necessary documentation with credit bureaus,
                                financial institutions, and government agencies.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="hidden lg:block lg:order-2 w-1/2"></div>
            </div>

            <div class="relative flex items-center overflow-hidden">
                <div class="w-1/2 hidden lg:block"></div>
                <div class="w-[294px] w-[95%] lg:w-1/2 pl-[55px] sm:pl-[96px] lg:pl-[60px] text-left">
                    <div class="timeline-card w-full lg:w-[381px] h-[280px] bg-[url('/assets/image/desktop/restoration/3.png')] bg-cover bg-center bg-no-repeat p-5 rounded-[20px]  opacity-10 transition-opacity duration-[1500ms]"
                        data-index="2">
                        <div class="relative sm:pl-[24px] py-[32px]">
                            <lottie-player class="absolute top-[-2px] left-[19px]"
                                src="/assets/image/desktop/realtime.json" background="transparent" speed="1" loop
                                autoplay style="width: 80px; height: 96px;"></lottie-player>
                            <h3 class="font-semibold text-[#010205] text-[24px] sm:text-[26px] leading-[150%] tracking-[-0.03em] mt-[57px]"
                                style="font-family: 'Plus Jakarta Sans', sans-serif;">Active Restoration
                            </h3>
                            <p class="text-[#878C91] font-medium text-[14px] mt-[20px] leading-[160%]"
                                style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                We actively work to remove fraudulent accounts,
                                restore your credit score, and recover any stolen funds or assets.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative flex items-center overflow-hidden">
                <div class="order-2 pl-[55px] sm:pl-[96px] lg:pl-[0px] lg:order-1 w-[95%] lg:w-[381px]  text-left">
                    <div class="timeline-card w-full h-[280px] bg-[url('/assets/image/desktop/restoration/4.png')] bg-cover bg-center bg-no-repeat rounded-[20px] p-5  opacity-10 transition-opacity duration-[1500ms]"
                        data-index="3">
                        <div class="relative sm:pl-[24px] py-[32px]">
                            <lottie-player class="absolute top-0 left-[18px]"
                                src="/assets/image/desktop/mornitoring.json" background="transparent" speed="1" loop
                                autoplay style="width: 80px; height: 96px;"></lottie-player>
                            <h3 class="font-semibold text-[#010205] text-[24px] sm:text-[26px] leading-[150%] tracking-[-0.03em] mt-[57px]"
                                style="font-family: 'Plus Jakarta Sans', sans-serif;">Ongoing Protection
                            </h3>
                            <p class="text-[#878C91] font-medium text-[14px] mt-[20px] leading-[160%]"
                                style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                We provide continuous monitoring and protection services to prevent future 
                                identity theft incidents.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="hidden lg:block lg:order-2 w-1/2"></div>
            </div>
        </div>
    </div>
</div>
<script>
    function restoration() {
        const cards = document.querySelectorAll('.timeline-card');
        const fill = document.getElementById('timeline-fill');
        const duck = document.getElementById('duck');
        let timeline_init = false

        let visibleIndexes = new Set();

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                const card = entry.target;
                const index = parseInt(card.getAttribute('data-index'));
                const cardBottom = entry.boundingClientRect.bottom;

                const viewportHeight = window.innerHeight;

                const isCardVisible = cardBottom < viewportHeight;
                if (cardBottom >= 0 && !timeline_init) {
                    timeline_init = true;
                }
                if (timeline_init) {
                    if (isCardVisible) {
                        visibleIndexes.add(index);
                        card.classList.remove('opacity-10');
                        card.classList.add('opacity-100');
                    } else {
                        visibleIndexes.delete(index);
                        card.classList.remove('opacity-100');
                        card.classList.add('opacity-10');
                    }
                }

                const maxIndex = Math.max(...[...visibleIndexes], -1);
                if (maxIndex >= 0) {
                    const lastCard = document.querySelector(`.timeline-card[data-index="${maxIndex}"]`);
                    const bottom = lastCard.getBoundingClientRect().bottom + window.scrollY;
                    const timelineTop = fill.parentElement.getBoundingClientRect().top + window.scrollY;
                    fill.style.height = `${bottom - timelineTop + 15}px`;
                    duck.style.top = `${bottom - timelineTop}px`;
                } else {
                    duck.style.top = '0px';
                    fill.style.height = '15px';
                }
            });
        }, {
            threshold: 1 // Adjust sensitivity
        });
        cards.forEach(card => observer.observe(card));
    }
    restoration();
</script>