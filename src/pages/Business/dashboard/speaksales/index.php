<div class="flex grid-cols-1 md:grid-cols-2 gap-[32px]">
    <div class="flex flex-col justify-between bg-white rounded-[26px] p-[24px] ">
        <div class="flex flex-col">
            <h1 class="font-bold text-[24px] text-[#010205]">Why Talk to Our Sales Team?</h1>
            <div class="mt-[32px] flex flex-col gap-[24px]">
                <?php
                $datas = [
                    [
                        "icon" => "solution",
                        "title" => "Customized Solutions",
                        "content" => "Get a tailored solution that fits your specific business requirements and scale"
                    ],
                    [
                        "icon" => "pricing",
                        "title" => " Enterprise Pricing",
                        "content" => "Access volume discounts and flexible payment terms for enterprise accounts"
                    ],
                    [
                        "icon" => "support",
                        "title" => "Implementation Support",
                        "content" => "Get dedicated onboarding and implementation assistance from our experts"
                    ],
                    [
                        "icon" => "roi",
                        "title" => "ROI Analysis",
                        "content" => "Understand the potential return on investment for your specific use case"
                    ]
                ];
                foreach ($datas as $data) {
                ?>
                    <div class="flex flex-col gap-[8px] p-[24px] bg-[#F4B91F1A] rounded-[15px] backdrop-blur-[40px] shadow-[0_4px_4px_0_#F6F6F626]
                    border-l-[5px] border-[#FFCF50]">
                        <div class="flex items-center gap-[8px]">
                            <?php require(BASEPATH . "/src/common/svgs/business/speaksales/" . $data['icon'] . ".php"); ?>
                            <h1 class="text-[18px] text-[#00530F] font-bold leading-[150%]"><?= $data['title']; ?></h1>
                        </div>
                        <h1 class="text-[16px] text-[#010205] font-medium leading-[150%]"><?= $data['content']; ?></h1>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
        <div class="mt-[42px]">
            <!-- <div class="flex items-center gap-[8px]">
                <?php require(BASEPATH . "/src/common/svgs/business/speaksales/phone.php"); ?>
                <h1 class="text-[#9B9B9C] text-[16px] tracking-[-0.02em]">(+1) 775 443 3727</h1>
            </div> -->
            <div class="flex items-center gap-[8px]">
                <?php require(BASEPATH . "/src/common/svgs/business/speaksales/message.php"); ?>
                <h1 class="text-[#9B9B9C] text-[16px] tracking-[-0.02em]">hello@privacyduck.com
                </h1>
            </div>
        </div>
    </div>
    <div class="min-w-[550px] flex flex-col justify-between p-[24px] bg-[#00530F] rounded-[15px] border border-[#F6F6F63B] px-[30px] py-[24px]">
        <div class="mt-[32px]">
            <h1 class="font-bold text-[24px] align-middle text-white">Request a demo</h1>
            <div class="mt-[32px] flex flex-col gap-[20px]">
                <?php
                $datas = [
                    ["name" => "First Name *", "id" => "demo_first_name", "placeholder" => "John"],
                    ["name" => "Last Name *", "id" => "demo_last_name", "placeholder" => "Mayes"],
                    ["name" => "Business Email *", "id" => "demo_email", "placeholder" => "example@example.com"],
                    ["name" => "Company *", "id" => "demo_company", "placeholder" => "Company"],
                ];
                foreach ($datas as $data) {
                ?>
                    <div class="flex flex-col gap-[6px]">
                        <h1 class="font-medium text-[14px] leading-[20px] text-[#FFFFFF]"><?= $data['name'] ?></h1>
                        <input type="text" id="<?= $data['id'] ?>" placeholder="<?= $data['placeholder'] ?>" class="demo_input text-white w-full placeholder:text-[#FFCF5099] border border-[#FFCF5040] rounded-[8px] bg-[#FFCF501A]" />
                    </div>
                <?php
                }
                ?>
                <div class="flex flex-col gap-[6px]">
                    <h1 class="font-medium text-[14px] leading-[20px] text-[#FFFFFF]">Tell us about your needs*</h1>
                    <textarea id="demo_needs" placeholder="Describe your business requirements and goald..."
                        class="demo_input h-[111px] px-[14px] py-[10px] text-[#FFCF50] w-full border border-[#FFCF5040] rounded-[8px] bg-[#FFCF501A] 
                            placeholder:text-[#FFCF5099] placeholder:text-[16px] placeholder:leading-[24px]"></textarea>
                </div>
            </div>
        </div>
        <div class="flex justify-center">
            <div class="mt-[32px] flex flex-col items-center gap-[16px] w-[324px] ">
                <button class="w-full h-[56px] flex justify-center items-center gap-[16px] shadow-[0px_4px_4px_0px_#FFCF5026] bg-[#FFCF50] rounded-full">
                    <h1 class="text-[#00530F] font-bold text-[16px] leading-[140%] tracking-[-0.02em]">Book a Demo</h1>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 12H19" stroke="#00530F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M12 5L19 12L12 19" stroke="#00530F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
                <h1 class="text-[12px] leading-[14px] text-white text-center">By submitting, you agree to our <span class="text-[#FFCF50]">Privacy Policy</span> and <span class="text-[#FFCF50]">Privacy Policy</span>.</h1>
            </div>
        </div>
    </div>
</div>
<div class="mt-[32px] bg-white rounded-[26px] p-[24px]">
    <h1 class="font-bold text-[24px] text-[#010205]">Success Stories</h1>
    <div class="mt-[32px]">
        <div class="ml-[38px] flex items-center gap-[28px]">
            <button id="speak_prevSlide" class="text-[24px] text-[#0F3812]">&lt;</button>
            <button id="speak_nextSlide" class="text-[24px] text-[#0F3812]">&gt;</button>
        </div>
        <div class="swiper mySwiper min-w-[415px] mt-[22px]">
            <div class="swiper-wrapper">
                <div class="swiper-slide flex gap-[6px]">
                    <?php require BASEPATH . "/src/common/svgs/dashboard/work/coma.php"; ?>
                    <div>
                        <h1 class="text-[16px] leading-[140%] tracking-[-0.03em] text-[#010205] font-semibold">Using PrivacyDuck to remove my business data was a game changer. The process was quick and efficient,
                            and I felt secure knowing my information was being handled with care.</h1>
                        <div class="mt-[16px] flex items-center space-x-[6px]">
                            <?php require BASEPATH . "/src/common/svgs/dashboard/work/avatar.php"; ?>
                            <div>
                                <div class="font-bold text-[14px] leading-[180%] text-[#010205]">Michael Kaizer</div>
                                <div class="font-medium text-[12px] leading-[180%] text-[#010205CC]">CEO of Basecamp Corp</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide flex gap-[6px]">
                    <?php require BASEPATH . "/src/common/svgs/dashboard/work/coma.php"; ?>
                    <div>
                        <h1 class="text-[16px] leading-[140%] tracking-[-0.03em] text-[#010205] font-semibold">Using PrivacyDuck to remove my business data was a game changer. The process was quick and efficient,
                            and I felt secure knowing my information was being handled with care.</h1>
                        <div class="mt-[16px] flex items-center space-x-[6px]">
                            <?php require BASEPATH . "/src/common/svgs/dashboard/work/avatar.php"; ?>
                            <div>
                                <div class="font-bold text-[14px] leading-[180%] text-[#010205]">Michael Kaizer</div>
                                <div class="font-medium text-[12px] leading-[180%] text-[#010205CC]">CEO of Basecamp Corp</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation arrows -->

        </div>
    </div>
</div>

<script>
    function slide_init_speaksales() {
        new Swiper(".mySwiper", {
            loop: true,
            slidesPerView: 1,
            navigation: {
                nextEl: "#speak_nextSlide",
                prevEl: "#speak_prevSlide",
            },
        });
    }
</script>