<style>
    [aria-expanded="true"] {
        background-color: #ffffff !important;
    }
</style>
<div class="sm:p-[16px]">
    <div class=" bg-white sm:rounded-[28px] flex flex-col justify-center">
        <div class="pt-[70px] pb-[56px] sm:py-[100px] px-[16px] sm:pl-[64px] sm:pr-[80px]  ">
            <div class="sm:hidden flex justify-center">
                <h1 class="text-[32px] leading-[130%] font-semibold tracking-[-1px] text-[#010205]">FAQs</h1>
            </div>
            <div class="sm:hidden flex justify-center mt-[16px]">
                <h1 class="text-[16px] leading-[180%] font-semibold text-[#878C91]">Real People, Real Transformations.
                </h1>
            </div>
            <h1 class="hidden sm:flex text-[32px] sm:text-[48px] leading-[130%] font-semibold tracking-[-1px] text-[#010205]">Need more
                information?</h1>
            <div id="accordion-collapse" data-accordion="collapse"
                class="mt-[50px] w-full border-t border-b border-black divide-y" onclick="toggleCollapse()">
                <!-- FAQ 1 -->
                <h1 class="transition-colors border-t border-black" id="faq1-heading">
                    <button type="button"
                        class="flex items-center justify-between w-full px-5 py-5 text-left font-semibold text-black leading-[150%] tracking-[3%] text-[16px]"
                        data-accordion-target="#faq1-body" aria-expanded="true" aria-controls="faq1-body">
                        <span>How does PrivacyCheck compare to Optery/DeleteMe/OneRep?</span>
                        <span class="text-2xl">
                            <span class="icon-plus">+</span>
                            <span class="icon-minus">−</span>
                        </span>
                    </button>
                </h1>
                <div id="faq1-body" class="hidden border-t border-t-transparent " aria-labelledby="faq1-heading">
                    <div class="py-2 px-[24px] text-base text-[#878C91] text-[14px] leading-[180%]">
                        We collect necessary user information like name, email, and usage data.
                    </div>
                </div>

                <!-- FAQ 2 -->
                <h1 class="transition-colors border-t border-black" id="faq2-heading">
                    <button type="button"
                        class="flex items-center justify-between w-full px-5 py-5 text-left font-semibold text-black leading-[150%] tracking-[3%] text-[16px]"
                        data-accordion-target="#faq2-body" aria-expanded="false" aria-controls="faq2-body">
                        <span>How quickly will my information be removed?</span>
                        <span class="text-2xl">
                            <span class="icon-plus">+</span>
                            <span class="icon-minus">−</span>
                        </span>
                    </button>
                </h1>
                <div id="faq2-body" class="hidden border-t border-t-transparent" aria-labelledby="faq2-heading">
                    <div class="py-2 px-[24px] text-base text-[#878C91] text-[14px] leading-[180%]">
                        Most requests are processed within a few days.
                    </div>
                </div>

                <!-- FAQ 3 -->
                <h1 class="transition-colors border-t border-black" id="faq3-heading">
                    <button type="button"
                        class="flex items-center justify-between w-full px-5 py-5 text-left font-semibold text-black leading-[150%] tracking-[3%] text-[16px]"
                        data-accordion-target="#faq3-body" aria-expanded="false" aria-controls="faq3-body">
                        <span>What happens if my information reappears?</span>
                        <span class="text-2xl">
                            <span class="icon-plus">+</span>
                            <span class="icon-minus">−</span>
                        </span>
                    </button>
                </h1>
                <div id="faq3-body" class="hidden border-t border-t-transparent" aria-labelledby="faq3-heading">
                    <div class="py-2 px-[24px] text-base text-[#878C91] text-[14px] leading-[180%]">
                        We monitor and send new removal requests automatically.
                    </div>
                </div>

                <!-- FAQ 4 -->
                <h1 class="transition-colors border-t border-black" id="faq4-heading">
                    <button type="button"
                        class="flex items-center justify-between w-full px-5 py-5 text-left font-semibold text-black leading-[150%] tracking-[3%] text-[16px]"
                        data-accordion-target="#faq4-body" aria-expanded="false" aria-controls="faq4-body">
                        <span>How long is my data stored?</span>
                        <span class="text-2xl">
                            <span class="icon-plus">+</span>
                            <span class="icon-minus">−</span>
                        </span>
                    </button>
                </h1>
                <div id="faq4-body" class="hidden border-t border-t-transparent" aria-labelledby="faq4-heading">
                    <div class="py-2 px-[24px] text-base text-[#878C91] text-[14px] leading-[180%]">
                        Your data is stored only as long as needed to complete your requests.
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
<script>
    function plans_faq_init() {
        window.initAccordions();
        toggleCollapse();
    }
    
</script>