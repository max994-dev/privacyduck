<div class="pt-[70px] sm:pt-[104px] px-[18px] lg:px-[80px] pb-[99px] flex flex-col lg:flex-row justify-between">
        <div class="order-2 mt-[32px] lg:mt-0 lg:order-1 lg:max-w-[623px] flex flex-wrap justify-center lg:justify-normal gap-[16px]">
            <?php
            $data = [
                ["name" => "Name", "icon" => "mini_name_green"],
                ["name" => "Age", "icon" => "mini_123_green"],
                ["name" => "Address", "icon" => "mini_pastadress_green"],
                ["name" => "Social Media", "icon" => "mini_social_green"],
                ["name" => "Past Adress", "icon" => "mini_pastadress_green"],
                ["name" => "Email", "icon" => "mini_email_green"],
                ["name" => "Photos", "icon" => "mini_photos_green"],
                ["name" => "Relatives", "icon" => "mini_relatives_green"],
                ["name" => "Marital Status", "icon" => "mini_marital_green"],
                ["name" => "Occupation", "icon" => "mini_occupation_green"],
                ["name" => "Phone", "icon" => "mini_phone_green"],
                ["name" => "Property Value", "icon" => "mini_name_green"],
            ];
            foreach ($data as $key => $value) {
            ?>
                <div class="flex space-x-[6px] px-[14px] py-[7px] items-center bg-[#24A55626] rounded-full">
                    <img src="/assets/image/desktop/icons/<?= $value["icon"] ?>.svg" alt="<?= $value["icon"] ?>" />
                    <h2 class="font-medium text-[16px] leading-[130%] tracking-[-0.03em] text-[#24A556]">
                        <?= $value["name"] ?></h2>
                </div>
            <?php
            }
            ?>
        </div>
        <div class="order-1 lg:order-2 lg:max-w-[539px]">
            <h2 class="text-[#010205] font-medium text-[20px] leading-[150%] ">
            As soon as you sign up, you get access to premium suite of Personalized Privacy removal and tracking tools, 
            and a team ready to help you with any problem, included in the price.
            </h2>
        </div>
    </div>