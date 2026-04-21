<div class="px-[16px] ">
    <div class="rounded-[30px] bg-white px-[16px] sm:px-[80px] pb-[32px] sm:pb-[82px] pt-[82px]">
        <div class="flex flex-col justify-center items-center">
            <fieldset>
                <legend class="text-[16px] sm:text-[24px] font-bold leading-[130%] tracking-[-0.03em]">The Ultimate Privacy Solution</legend>
            </fieldset>
            <h2 class="hidden sm:block mt-[24px] text-center font-semibold text-[48px] leading-[130%] tracking-[-0.03em] text-[#010205] max-w-[620px]">One Platform. One Service. Full Transparency</h2>
            <div class="sm:hidden flex flex-col items-center mt-[24px]">
                <h2 class="text-[32px] font-semibold  leading-[130%] tracking-[-0.03em] text-[#010205] max-w-[620px]">One Platform.</h2>
                <h2 class="text-[32px] font-semibold  leading-[130%] tracking-[-0.03em] text-[#010205] max-w-[620px]">One Service.</h2>
                <h2 class="text-[32px] font-semibold  leading-[130%] tracking-[-0.03em] text-[#010205] max-w-[620px]">Full Transparency</h2>
            </div>
            <h2 class="mt-[16px] sm:mt-[33px] text-[18px] sm:text-[24px] font-bold leading-[120%] text-[#878C91]">
                PrivacyDuck brings everything you need to erase your online footprint into one easy-to-use platform
            </h2>
            <h2 class="text-[18px] sm:text-[24px] font-bold leading-[120%] text-[#878C91]">
                From real-time tracking to custom data removals, designed for total transparency and peace of mind
            </h2>
        </div>
        <div class="mt-[60px] flex flex-col space-y-[70px]">
            <?php
            $datas = [
                [
                    "title" => "Real-Time Privacy Dashboard",
                    "subtitle" => "Know exactly how exposed you are, anytime",
                    "content" => "Your personal dashboard shows your privacy risk score and “Removal Progress”, a simple number that tracks how much personal data we’ve removed.
                                Check back weekly or monthly to see how your exposure score drops as our system deletes your personal information online.",
                    "image" => "ultimate1",
                    "alt" => "Real-Time Privacy Dashboard"
                ],
                // [
                //     "title" => "Primary, Custom, Face Scan",
                //     "subtitle" => "Check in on our progress anytime, at a glace",
                //     "content" => "PrivacyDuck makes things easy. Your “Removal Progress” is a simple number to represent how xposed you are online . Check back to see how it drops each week or month as our fights for your privacy scores more deletion victories.",
                //     "image"=>"ultimate2"
                // ],
                [
                    "title" => "Custom Removal Requests",
                    "subtitle" => "Some sites need special handling",
                    "content" => "PrivacyDuck gives you access to custom data removal tools, letting you target high-risk listings, 
                                    exposed images, or duplicate data.No guessing, no stress, just clear updates on every case.",
                    "image" => "ultimate3",
                    "alt" => "Custom Removal Requests"
                ],
                [
                    "title" => "Add Family Members",
                    "subtitle" => "Share your privacy journey with loved ones",
                    "content" => "Easily add spouses, kids, or parents to your account. 
                            You’ll manage everything from a single dashboard, keeping track of each member’s exposure and removal progress.This is family-wide digital privacy, simplified",
                    "image" => "ultimate4",
                    "alt" => "Add Family Members"
                ]
            ];
            foreach ($datas as $data) {
            ?>
                <div class="flex flex-col lg:flex lg:flex-row lg:justify-between items-center">
                    <div class="<?php echo array_search($data, $datas) % 2 == 0 ? "lg:order-1" : "lg:order-2" ?> flex flex-col max-w-[341px] sm:max-w-[542px]">
                        <h2 class="text-[24px] sm:text-[38px] font-semibold leading-[120%] tracking-[-0.03em] text-[#010205]"><?php echo $data["title"]; ?></h2>
                        <h2 class="mt-[16px] sm:mt-[33px] text-[18px] sm:text-[24px] font-bold leading-[120%] text-[#878C91]"><?php echo $data["subtitle"]; ?></h2>
                        <p class="mt-[24px] sm:mt-[24px] text-[16px] font-semibold leading-[150%] text-[#878C91]"><?php echo $data["content"]; ?></p>
                    </div>
                    <img class="<?php echo array_search($data, $datas) % 2 == 0 ? "lg:order-2" : "lg:order-1" ?> mt-[24px] lg:mt-0 w-[341px] h-[190px] sm:w-[550px] sm:h-[307px]" src="/assets/image/desktop/landing/ultimate/<?php echo $data["image"]; ?>.png" alt="<?php echo $data["alt"]; ?>">
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>