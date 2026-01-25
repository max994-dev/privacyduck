<div class="py-[60px] px-[80px]">
    <div class="flex flex-col items-center">
        <h2 class="text-center max-w-[742px] text-[42px] leading-[130%] font-semibold text-[#010205] tracking-[-0.03em]">
            PrivacyDuck Turns Compliance Into Business Growth
        </h2>
        <p class="mt-[32px] max-w-[884px] text-[#010205CC] text-[18px] leading-[150%] text-center">
            Get enterprise-grade privacy protection without slowing down your team. PrivacyDuck combines visibility, automation, 
            and collaboration to simplify GDPR compliance and data removal.
        </p>
        <div class="mt-[71px] flex grid grid-cols-4 gap-[10px]">
            <?php
            $datas = [
                [
                    "image" => "visibility",
                    "title" => "Full Data Visibility",
                    "description" => "Map and monitor personal data in real time. Our platform provides cross-functional data visibility, empowering your legal, 
                                    IT, and compliance teams to act faster and smarter."
                ],
                [
                    "image" => "truth",
                    "title" => "One Privacy Hub",
                    "description" => "Say goodbye to fragmented tools. PrivacyDuck offers a centralized privacy management system where security, 
                                legal, and privacy teams can sync and streamline every data workflow."
                ],
                [
                    "image" => "easiest",
                    "title" => "Easiest to Use",
                    "description" => "We built PrivacyDuck for usability. With intuitive compliance software, no steep learning curve, 
                                    and automated features, your team can focus on strategy, not busywork."
                ],
                [
                    "image" => "instant",
                    "title" => "Instant ROI",
                    "description" => "From automated opt-outs to real-time dashboards, PrivacyDuck turns privacy 
                                    efforts into measurable wins. Cut down manual tasks, reduce risks, and get compliance ROI from day one."
                ],
            ];
            foreach ($datas as $data) {
            ?>
                <div class="flex flex-col items-center">
                    <img src="/assets/image/desktop/business/landing/growth_<?= $data["image"]; ?>.gif" class="w-[150px] h-[150px]" alt="<?= $data["title"]; ?>">
                    <h2 class="mt-[24px] text-[24px] leading-[130%] font-bold text-[#000000] tracking-[-0.03em]"><?= $data["title"]; ?></h2>
                    <p class="mt-[23px] text-[#010205] text-[14px] font-medium leading-[150%] text-center"><?= $data["description"]; ?></p>
                </div>
            <?php
            }
            ?>
        </div>
        <button class="bg-[#00530F] mt-[71px] rounded-full gap-[24px] w-[251px] h-[56px] flex items-center justify-center">
            <h2 class="text-[16px] font-bold text-[#FFCF50] leading-[140%] tracking-[-0.02em]"
                style="font-family: 'Manrope', sans-serif;">
                Get A Quote
            </h2>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5 12H19" stroke="#FFCF50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M12 5L19 12L12 19" stroke="#FFCF50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>
    </div>
</div>