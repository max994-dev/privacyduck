<div class="flex flex-col items-center xl:flex-row mt-[62px] py-[70px] sm:pl-[80px] sm:pr-[51px] bg-[white] xl:items-start">
    <div class="flex flex-col items-center justify-center xl:items-start xl:mr-[153px] px-[16px] sm:px-0">
        <fieldset>
            <legend class="font-bold text-[16px] sm:text-[24px] leading-[130%] tracking-[-0.03em]">PrivacyDuck Team</legend>
        </fieldset>
        <h2 class="mt-[24px] text-[#010205] text-[32px] sm:text-[48px] font-semibold leading-[130%] tracking-[-0.03em]">Meet Our Team</h2>
        <h2 class="mt-[32px] text-center text-[#878C91] text-[16px] sm:text-[18px] font-semibold leading-[150%] xl:max-w-[452px]">Complete the form below to send us a message. Our support team will promptly respond to your request.</h2>
    </div>
    <div class="flex flex-1 lg:min-w-[680px] mt-[32px] xl:mt-0 flex-wrap grid grid-cols-2 lg:grid-cols-3 gap-[32px]  px-[26px] sm:px-0 ">
        <?php
        $datas = [
            ["avatar" => "ceo", "name" => "Christopher Scott", "role" => "Founder & CEO"],
            ["avatar" => "cto", "name" => "Oleksandr Savchuk", "role" => "CTO"],
            ["avatar" => "backend", "name" => "Victor Paiva ", "role" => ""],
        ];
        foreach ($datas as $data) {
        ?>
            <div class="flex flex-col items-center bg-[#E8FCE766] min-w-[157px] sm:min-w-[224px] pt-[10px] pb-[45px]">
                <img class="w-[104px] h-[104px] rounded-full object-cover" src="/assets/image/desktop/personalized/team/<?= $data["avatar"] ?>.png" alt="">
                <h2 class="mt-[32px] font-bold text-[18px] text-[#010205] leading-[120%]"><?= $data["name"] ?></h2>
                <h2 class="text-[14px] font-medium text-[#24A556] leading-[25px]"><?= $data["role"] ?></h2>
                <div class="flex space-x-[16px]">
                    <i class="fa-brands fa-facebook text-[#878C91] w-[16px] h-[16px]"></i>
                    <i class="fa-brands fa-github text-[#878C91] w-[16px] h-[16px]"></i>
                    <i class="fa-brands fa-linkedin text-[#878C91] w-[16px] h-[16px]"></i>
                </div>
            </div>
        <?php
        }
        ?>

    </div>
</div>