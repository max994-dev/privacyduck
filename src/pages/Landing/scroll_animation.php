<script src="assets/js/canvas-bg.js" defer></script>
<div class="pb-[120px] relative">
    <div class="pt-[70px] px-[16px] flex justify-center lg:block  lg:px-[80px] z-[5] relative">
        <div class="lg:flex lg:justify-between">
            <div
                class="flex font-semibold text-[32px] leading-[110%] lg:justify-start lg:text-[48px] lg:leading-[130%] tracking-[1px] items-center">
                How it Works?
            </div>
            <div class="flex mt-[48px] flex-col max-w-[557px] lg:mt-0">
                <p class="text-[16px] font-medium leading-[180%] text-[#878C91]">
                    <span class="font-bold">PrivacyDuck</span> makes it easy to remove your personal information online,
                    without lifting a finger.<br />
                    Here's how we clean up your digital footprint:

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
                    <div class="timeline-card w-full lg:w-[381px] h-[280px] bg-white p-5 rounded-[20px]  opacity-10 transition-opacity duration-[1500ms]"
                        data-index="0">
                        <div class="relative sm:pl-[24px] py-[32px]">
                            <lottie-player class="absolute top-[-26px] left-[17px]"
                                src="/assets/image/desktop/signup.json" background="transparent" speed="1" loop
                                autoplay style="width: 80px; height: 96px;"></lottie-player>
                            <h3 class="font-semibold text-[24px] sm:text-[26px] leading-[150%] tracking-[-1px] mt-[32px]"
                                style="font-family: 'Plus Jakarta Sans', sans-serif;">Sign Up & Submit<br />
                                Information</h3>
                            <p class="text-[#878C91] font-medium text-[14px] mt-[16px] leading-[160%]"
                                style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                Start by telling us what you'd like removed: home and work addresses, phone numbers, emails, photos,
                                or social media links.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative flex items-center overflow-hidden">
                <div class="order-2 pl-[55px] sm:pl-[96px] lg:pl-[0px] lg:order-1 w-[95%] lg:w-[381px]  text-left">
                    <div class="timeline-card w-full h-[280px] bg-white rounded-[20px] p-5  opacity-10 transition-opacity duration-[1500ms]"
                        data-index="1">
                        <div class="relative pt-[26px] sm:pl-[24px] sm:py-[32px]">
                            <lottie-player class="absolute top-[-25px] left-[13px]"
                                src="/assets/image/desktop/search.json" background="transparent" speed="1" loop
                                autoplay style="width: 80px; height: 96px;"></lottie-player>
                            <h3 class="font-semibold text-[24px] sm:text-[26px] leading-[150%] tracking-[-1px] mt-[35px]"
                                style="font-family: 'Plus Jakarta Sans', sans-serif;">Automated & Manual
                                Removal
                            </h3>
                            <p class="text-[#878C91] font-medium text-[14px] mt-[7px] sm:mt-[20px] leading-[160%]"
                                style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                Our system instantly begins removing your data from people-search sites.
                                A human team handles sites that require manual removal.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="hidden lg:block lg:order-2 w-1/2"></div>
            </div>

            <div class="relative flex items-center overflow-hidden">
                <div class="w-1/2 hidden lg:block"></div>
                <div class="w-[294px] w-[95%] lg:w-1/2 pl-[55px] sm:pl-[96px] lg:pl-[60px] text-left">
                    <div class="timeline-card w-full lg:w-[381px] h-[280px] bg-white p-5 rounded-[20px]  opacity-10 transition-opacity duration-[1500ms]"
                        data-index="2">
                        <div class="relative sm:pl-[24px] py-[32px]">
                            <lottie-player class="absolute top-[-2px] left-[19px]"
                                src="/assets/image/desktop/realtime.json" background="transparent" speed="1" loop
                                autoplay style="width: 80px; height: 96px;"></lottie-player>
                            <h3 class="font-semibold text-[24px] sm:text-[26px] leading-[150%] tracking-[-1px] mt-[57px]"
                                style="font-family: 'Plus Jakarta Sans', sans-serif;">Real-Time Tracking
                            </h3>
                            <p class="text-[#878C91] font-medium text-[14px] mt-[20px] leading-[160%]"
                                style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                PrivacyDuck protects your information in real time.
                                As soon as removals begin, your personal dashboard updates automatically, so you don't need to keep checking.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative flex items-center overflow-hidden">
                <div class="order-2 pl-[55px] sm:pl-[96px] lg:pl-[0px] lg:order-1 w-[95%] lg:w-[381px]  text-left">
                    <div class="timeline-card w-full h-[280px] bg-white rounded-[20px] p-5  opacity-10 transition-opacity duration-[1500ms]"
                        data-index="3">
                        <div class="relative sm:pl-[24px] py-[32px]">
                            <lottie-player class="absolute top-0 left-[18px]"
                                src="/assets/image/desktop/mornitoring.json" background="transparent" speed="1" loop
                                autoplay style="width: 80px; height: 96px;"></lottie-player>
                            <h3 class="font-semibold text-[24px] sm:text-[26px] leading-[150%] tracking-[-1px] mt-[57px]"
                                style="font-family: 'Plus Jakarta Sans', sans-serif;">Continuous Monitoring
                            </h3>
                            <p class="text-[#878C91] font-medium text-[14px] mt-[20px] leading-[160%]"
                                style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                Data brokers don’t give up easily. That’s why we offer ongoing monitoring, where we check for reappearances and remove your data again,
                                automatically.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="hidden lg:block lg:order-2 w-1/2"></div>
            </div>
        </div>
    </div>
</div>