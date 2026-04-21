<div class="px-[18px] lg:px-[43px] py-[20px] lg:py-[24px]">
        <div class="lg:flex items-center text-[#010205] text-[12px] sm:text-[18px] font-bold leading-[130%] flex-wrap">
            <h1>Data brokers we found you on expose the following details on people, if exist.</h1>
            <h1 class="underline text-[#24A556] mt-[5px] lg:mt-0 md:w-[177px]">Remove them NOW</h1>
        </div>
        <h1 class="text-[#B5B7C0] text-[10px] sm:text-[12px] tracking-[-0.01em] mt-[5px]">Over 400 people’s data points are
            available in data broker databases. The websites we found you on may expose the following
            details.</h1>
        <div class="mt-[21.5px] lg:mt-[24px]">
            <div class="flex items-center">
                <img src="/assets/image/desktop/icons/middle_lock.svg" alt="middle_lock" />
                <h1 class="text-[#010205] leading-[130%] font-bold text-[10px] sm:text-[12px]">DATA BROKERS RESULT</h1>
            </div>
            <div class="flex items-center mt-[12px] sm:max-w-[85%] space-x-[8px] space-y-[8px] flex-wrap">
                <div class="px-[6px] py-[7px] flex items-center rounded-full bg-[#FFD4D4] space-x-[6px] flex-wrap">
                    <?php
                    $data = [
                        ["name" => "Name", "icon" => "mini_name"],
                        ["name" => "Name", "icon" => "mini_name"],
                        ["name" => "Age", "icon" => "mini_123"],
                        ["name" => "Age", "icon" => "mini_123"],
                    ];
                    foreach ($data as $key => $value) {
                    ?>
                        <div class="flex space-x-[6px] px-[6px] py-[5px] items-center bg-[#FAFAFA] rounded-full">
                            <img src="/assets/image/desktop/icons/<?= $value["icon"] ?>.svg" alt="<?= $value["icon"] ?>" />
                            <h1 class="font-medium text-[12px] leading-[130%] tracking-[-0.03em] text-[#010205]">
                                <?= $value["name"] ?></h1>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <?php
                $data = [
                    ["name" => "Name", "icon" => "mini_name_white"],
                    ["name" => "Age", "icon" => "mini_123_white"],
                    ["name" => "Social Media", "icon" => "mini_social_white"],
                    ["name" => "Past Adress", "icon" => "mini_pastadress_white"],
                    ["name" => "Email", "icon" => "mini_email_white"],
                    ["name" => "Photos", "icon" => "mini_photos_white"],
                    ["name" => "Relatives", "icon" => "mini_relatives_white"],
                    ["name" => "Marital Status", "icon" => "mini_marital_white"],
                    ["name" => "Occupation", "icon" => "mini_occupation_white"],
                    ["name" => "Phone", "icon" => "mini_phone_white"],
                    ["name" => "Property Value", "icon" => "mini_name_white"],
                    ["name" => "Address", "icon" => "mini_pastadress_white"],
                ];
                foreach ($data as $key => $value) {
                ?>
                    <div class="flex space-x-[6px] px-[6px] py-[5px] items-center bg-[#24A556] rounded-full">
                        <img src="/assets/image/desktop/icons/<?= $value["icon"] ?>.svg" alt="<?= $value["icon"] ?>" />
                        <h1 class="font-medium text-[12px] leading-[130%] tracking-[-0.03em] text-white">
                            <?= $value["name"] ?></h1>
                    </div>
                <?php
                }
                ?>
                <div class="flex space-x-[6px] px-[6px] py-[5px] items-center bg-[#EEEEEE] rounded-full">
                    <img src="/assets/image/desktop/icons/mini_dot.svg" alt="mini_123_white" />
                </div>
            </div>
        </div>
    </div>