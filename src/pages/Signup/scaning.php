<div class="flex justify-center mt-[48px]">
    <div class="flex rounded-full bg-[#FAFAFA] items-center">
        <div class=" px-[13px] py-[7px] sm:px-[24.5px] sm:py-[13px] text-[10px] sm:text-[18px] leading-[140%] font-medium text-[#010205] rounded-full transition-all whitespace-nowrap duration-200"
            data-type="one" data-people="1">
            1.Enter your information
        </div>
        <div class=" px-[13px] py-[7px] sm:px-[24.5px] sm:py-[13px] text-[10px] sm:text-[16px] leading-[140%] font-medium text-[#24A556] rounded-full transition-all whitespace-nowrap duration-200 bg-[#24A55630] active"
            data-type="two" data-people="2">
            2.Scan data brokers
        </div>
        <div class=" px-[13px] py-[7px] sm:px-[24.5px] sm:py-[13px] text-[10px] sm:text-[18px] leading-[140%] font-medium text-[#010205] rounded-full transition-all whitespace-nowrap duration-200 "
            data-type="three" data-people="3">
            See results
        </div>
    </div>
</div>
<div class="px-[16px] mt-[40px] sm:mt-[48px]">
    <h1 class="text-[24px] sm:text-[36px] text-[#010205] font-semibold leading-[110%] tracking-[-0.03em]"><span class="text-[#24A556]"
            id="progress-count">0</span> out of 301 websites</h1>
</div>
<div class="px-[16px] mt-[24px] sm:mt-[48px]">
    <h1 class="text-[32px] sm:text-[56px] text-[#010205] font-bold leading-[110%] tracking-[-0.03em]">Scanning...</h1>
    <h1
        class="max-w-[324px] sm:max-w-none mt-[24px] sm:mt-[33px] text-[16px] sm:text-[18px] text-[#010205] leading-[130%]">
        We’re searching for your personal information on people-search sites.</h1>
    <div class="flex items-center mt-[32px]">
        <div class="relative w-[310px] sm:w-[421px] h-[45px] rounded-full bg-[#E8FCE7] overflow-hidden">
            <div id="progress-bar"
                class="absolute top-0 left-0 h-full bg-[#24A556] rounded-l-full transition-all duration-700"
                style="width: 0%;"></div>
        </div>
        <h1 id="progress-text" class="ml-[5px] sm:ml-[17px] text-[16px] text-[#24A556] font-extrabold leading-[140%]">
            0%</h1>
    </div>
    <div class="mt-[56px] max-w-[358px] sm:max-w-[639px] relative overflow-y-auto overflow-x-hidden">
        <div id="accordion-collapse" data-accordion="collapse" class=" w-full border-b border-black divide-y" onclick="toggleCollapse()">
            <!-- FAQ 1 -->
            <h1 class="transition-colors border-black" id="faq1-heading">
                <button type="button"
                    class="flex items-center justify-between w-full px-5 py-[25px] text-left font-semibold text-black leading-[150%] tracking-[3%] text-[18px]"
                    data-accordion-target="#faq1-body" aria-expanded="false" aria-controls="faq1-body">
                    <span>Why provide your location?</span>
                    <span class="text-2xl">
                        <span class="icon-plus">+</span>
                        <span class="icon-minus">−</span>
                    </span>
                </button>
            </h1>
            <div id="faq1-body" class="hidden border-t border-t-transparent " aria-labelledby="faq1-heading">
                <div class="py-2 px-[24px] text-base text-[#878C91] text-[14px] leading-[180%]">
                    We need your name and location to search for your profiles on people-search sites. The results of
                    our scan will display the profiles of the person with the name and location you provided.
                </div>
            </div>

            <!-- FAQ 2 -->
            <h1 class="transition-colors border-t border-black" id="faq2-heading">
                <button type="button"
                    class="flex items-center justify-between w-full px-5 py-5 text-left font-semibold text-black leading-[150%] tracking-[3%] text-[16px]"
                    data-accordion-target="#faq2-body" aria-expanded="false" aria-controls="faq2-body">
                    <span>What do people-search sites reveal about you?</span>
                    <span class="text-2xl">
                        <span class="icon-plus">+</span>
                        <span class="icon-minus">−</span>
                    </span>
                </button>
            </h1>
            <div id="faq2-body" class="hidden border-t border-t-transparent" aria-labelledby="faq2-heading">
                <div class="py-2 px-[24px] text-base text-[#878C91] text-[14px] leading-[180%]">
                    Search sites expose your sensitive personal information such as your name, age, home address, phone
                    number, email addresses, your family members, other people associated with you, your income range,
                    credit score range, political preferences, criminal records, and much more.
                </div>
            </div>

            <!-- FAQ 3 -->
            <h1 class="transition-colors border-t border-black" id="faq3-heading">
                <button type="button"
                    class="flex items-center justify-between w-full px-5 py-[27px] text-left font-semibold text-black leading-[150%] tracking-[3%] text-[18px]"
                    data-accordion-target="#faq3-body" aria-expanded="false" aria-controls="faq3-body">
                    <span>Why is information exposure a problem?</span>
                    <span class="text-2xl">
                        <span class="icon-plus">+</span>
                        <span class="icon-minus">−</span>
                    </span>
                </button>
            </h1>
            <div id="faq3-body" class="hidden border-t border-t-transparent" aria-labelledby="faq3-heading">
                <div class="py-2 px-[24px] text-base text-[#878C91] text-[14px] leading-[180%]">
                    Personal data is widely used in criminal and fraudulent schemes. Your information exposed on
                    people-search sites puts you and your family at risk of identity theft, stalking, online harassment,
                    and even home attacks.
                </div>
            </div>
        </div>
    </div>
    <div class="h-[60px] mt-[60px]  sm:hidden">
        <div class="flex justify-center ">
            <h1 class="text-[14px] text-[#010205] leading-[20px]">©PrivacyDuck 2025</h1>
        </div>
        <div class="flex justify-center mt-[28px] pb-[8px]">
            <div class="bg-[black] h-[5px] w-[134px]"></div>
        </div>
    </div>
</div>
<script>
    toggleCollapse();
    let progress = 0;
    const total = 301;
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    const progressCount = document.getElementById('progress-count');
    function pro(time) {
        const interval = setTimeout(() => {
            const increment = Math.round(Math.random() * 2);
            progress += increment;

            if (progress >= total) {
                progress = total;
            }

            const percent = Math.round((progress / total)*100);
            const count = Math.floor(progress);

            progressBar.style.width = `${percent}%`;
            progressText.textContent = `${percent}%`;
            progressCount.textContent = `${count}`;
            if (progress !== total) {
                const times = Math.round(Math.random() * 100);
                pro(times);
            } else {
                $.get("/result",{
                    fullname:"<?php echo $_GET["fullname"]; ?>",
                    city:"<?php echo $_GET["city"]; ?>"
                }, function (data, status) {
                    $('#content').html(data);
                })
            }
        }, time);
    }
    pro(200);
</script>