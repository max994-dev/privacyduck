<div class="flex grid-cols-1 md:grid-cols-2 gap-[32px]">
    <div class="flex flex-col bg-white rounded-[26px] p-[24px] ">
        <h1 class="font-bold text-[24px] text-[#010205]">How can we help you?</h1>
        <div class="mt-[32px] flex flex-col gap-[24px]">
            <?php
            $datas = [
                [
                    "icon" => "phone",
                    "title" => "Priority Phone Support",
                    "content" => "Direct access to our business support specialists with guaranteed response times",
                    "href" => "",
                    // "onclick" => "show_phone()"
                    "onclick" => ""
                ],
                [
                    "icon" => "chat",
                    "title" => "Live Chat",
                    "content" => "Instant messaging with our support team for quick questions and guidance",
                    "href" => "https://tawk.to/chat/6813761a7c6684190de59a7c/1iq60amh0",
                ],
                [
                    "icon" => "training",
                    "title" => "Training Resources",
                    "content" => "Video tutorials, webinars, and certification programs for your team",
                    "href" => "",
                ],
                [
                    "icon" => "technical",
                    "title" => "Technical Support",
                    "content" => "Advanced technical assistance for integration and troubleshooting",
                    "href" => "",
                ],
                [
                    "icon" => "account",
                    "title" => "Account Management",
                    "content" => "Dedicated account managers for enterprise clients and strategic guidance",
                    "href" => "",
                ]
            ];
            foreach ($datas as $data) {
            ?>
                <?php if ($data['title'] == "Live Chat") {
                ?>
                    <a href="<?= $data['href']; ?>" target="_blank">
                        <div class="cursor-pointer flex flex-col gap-[8px] p-[24px] bg-[#FAFAFA] rounded-[15px] backdrop-blur-[40px] shadow-[0_4px_4px_0_#F6F6F626]
                    border-l-[5px] border-[#FFCF50]">
                            <div class="flex items-center gap-[8px]">
                                <?php require(BASEPATH . "/src/common/svgs/business/support/" . $data['icon'] . ".php"); ?>
                                <h1 class="text-[18px] text-[#010205] font-bold leading-[150%]"><?= $data['title']; ?></h1>
                            </div>
                            <h1 class="text-[16px] text-[#010205] font-medium leading-[150%]"><?= $data['content']; ?></h1>
                        </div>
                    </a>
                <?php } else { ?>
                    <div <?=isset($data['onclick']) ? 'onclick="'.$data['onclick'].'"' : ''?> id="<?php echo $data['icon']; ?>" class="cursor-pointer flex flex-col gap-[8px] p-[24px] bg-[#FAFAFA] rounded-[15px] backdrop-blur-[40px] shadow-[0_4px_4px_0_#F6F6F626]
                    border-l-[5px] border-[#FFCF50]">
                        <div class="flex items-center gap-[8px]">
                            <?php require(BASEPATH . "/src/common/svgs/business/support/" . $data['icon'] . ".php"); ?>
                            <h1 class="text-[18px] text-[#010205] font-bold leading-[150%]"><?= $data['title']; ?></h1>
                        </div>
                        <h1 class="text-[16px] text-[#010205] font-medium leading-[150%]"><?= $data['content']; ?></h1>
                    </div>
                <?php } ?>
            <?php
            }
            ?>

        </div>
    </div>
    <div class="flex flex-col p-[24px] bg-white rounded-[26px]">
        <h1 class="text-[24px] font-bold text-[#010205]">Our Business Customers Say</h1>
        <div class="mt-[32px] flex flex-col items-center gap-[16px]">
            <?php
            $custom_datas = [
                ["title" => "99.9%", "content" => "Uptime Guarantee"],
                ["title" => "<2h", "content" => "Average Response Time"],
                ["title" => "24/7", "content" => "Support Available"],
                ["title" => "98%", "content" => "Customer Satisfaction"],
            ];
            foreach ($custom_datas as $custom_data) {
            ?>
                <div class="flex flex-col w-full min-w-[415px] gap-[8px] px-[26px] py-[22px] bg-[#FAFAFA] rounded-[20px] border border-[#F6F6F6] text-center">
                    <h1 class="text-[42px] text-[#00530F] font-[800] tracking-[-0.01em]"><?= $custom_data['title']; ?></h1>
                    <h1 class="text-[16px] text-[#9B9B9C] font-medium "><?= $custom_data['content']; ?></h1>
                </div>
            <?php
            }
            ?>
        </div>
        <div class="mt-[24px] flex flex-col gap-[22px] p-[16px] bg-[#FAFAFA] rounded-[30px] border border-[#F6F6F6]">
            <div class="ml-[38px] flex items-center gap-[28px]">
                <button id="prevSlide" class="text-[24px] text-[#0F3812]">&lt;</button>
                <button id="nextSlide" class="text-[24px] text-[#0F3812]">&gt;</button>
            </div>
            <div class="swiper mySwiper max-w-[415px] mt-[22px]">
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
</div>

<script>
    function slide_init() {
        new Swiper(".mySwiper", {
            loop: true,
            slidesPerView: 1,
            navigation: {
                nextEl: "#nextSlide",
                prevEl: "#prevSlide",
            },
        });
    }

    function show_phone() {
        toastr.info("+1&nbsp;&nbsp;775&nbsp;443&nbsp;3727", {
            timeOut: 5000,
            positionClass: "toast-top-right",
        });
    }
</script>