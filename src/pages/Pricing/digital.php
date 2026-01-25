<?php
    $content = <<<EOT
        <div class="p-2 text-white">
                <div class="bg-[url('/assets/image/desktop/faqs-bg.png')] w-full h-fit bg-no-repeat bg-cover rounded-[30px]">
                    <div
                        class="flex flex-col md:flex-row items-center px-[14px] text-center sm:text-left sm:px-[64px] py-10 space-y-10 md:space-y-0 md:space-x-10">
                        <h2
                            class="font-semibold text-[30px] md:text-[50px] leading-[130%] tracking-[3%] items-center max-w-[70%] lg:max-w-[80%] ">
                            Your <span class="text-[#24A556] italic">digital
                                privacy</span> shouldn't be optional. Take back control today.</h2>
                        <div class="flex">
                            <a href="/dashboard"
                                class="inline-flex text-center items-center min-w-[200px] gap-2 px-10 py-3 bg-green-600 text-white text-[16px] leading-[140%] tracking-[2%] font-semibold rounded-full hover:bg-green-700">
                                Get Started
                                <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.5 12H19.5" stroke="white" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M12.5 5L19.5 12L12.5 19" stroke="white" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                            </a>
                        </div>
                    </div>
                </div>
            </div>
    EOT;

    echo $content;
?>
