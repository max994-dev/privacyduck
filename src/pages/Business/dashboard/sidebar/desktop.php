<div
    class="w-[307px] h-screen overflow-hidden border-t border-b border-r bg-[#00530F] rounded-tr-[30px] rounded-br-[30px] hidden xl:flex flex-col">

    <!-- Logo + Tabs -->
    <div class="pt-[40px] pl-[39px]">
        <a href="/business">
            <div class="relative flex flex-col">
                <div class="flex items-center gap-[8px]">
                    <h1 style="font-family: 'Alatsi', sans-serif;" class="text-[28px] tracking-[-0.02em] uppercase text-white">
                        Privacy<label class="text-[#FFCF50]" style="font-family: 'Alatsi', sans-serif;">Duck</label>
                    </h1>
                    <?php require(BASEPATH . '/src/common/svgs/business/landing/duck.php'); ?>
                </div>
                <h1 style="font-family: 'Alatsi', sans-serif;" class="relative top-[-10px] uppercase text-[20px] tracking-[-0.02em] text-white">
                    Enterprice
                </h1>
            </div>
        </a>
    </div>

    <!-- Tabs -->
    <div class="flex justify-center mt-[34px]">
        <div>
            <div class="flex bg-white/10 rounded-full w-[211px] h-[36px]">
                <?php
                $data = [
                    ["data_type" => "business_sidebar_personal", "data_people" => "2", "label" => "Personal", "href" => "/dashboard"],
                    ["data_type" => "business_sidebar_work", "data_people" => "23", "label" => "Work", "href" => "/business/dashboard"],
                ];
                foreach ($data as $item) { ?>
                    <a href="<?= $item['href'] ?>"
                        class="flex justify-center items-center rounded-full w-[116px] h-[36px] transition-all whitespace-nowrap duration-200 font-medium leading-[140%] text-[14px] text-white"
                        data-type="<?= $item['data_type']; ?>" data-people="<?= $item['data_people']; ?>">
                        <?= $item['label']; ?>
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Sidebar Links -->
    <div class="mt-[48px] px-[39px] flex-1 overflow-y-auto">
        <div id="sidebar" class="flex flex-col space-y-[32px]">
            <?php foreach (
                [
                    ["href" => "", "svg" => "apps", "label" => "Apps"],
                    ["href" => "/support", "svg" => "support", "label" => "Business Support"],
                    ["href" => "/speaksales", "svg" => "sales", "label" => "Speak With Sales"],
                    ["href" => "/settings", "svg" => "settings", "label" => "Bisiness Settings", "sub_label" => [
                        ["label" => "General Settings", "href" => "/general", "svg" => "general_setting"],
                        ["label" => "Account info", "href" => "/account", "svg" => "account_setting"],
                        // ["label" => "Security", "href" => "/security", "svg" => "security_setting"],
                        // ["label" => "Team Management", "href" => "/team", "svg" => "team_setting"],
                        // ["label" => "Billing", "href" => "/billing", "svg" => "billing_setting"],
                        // ["label" => "Integrations", "href" => "/integrations", "svg" => "integrations_setting"],
                        // ["label" => "Notifications", "href" => "/notifications", "svg" => "notifications_setting"],
                        // ["label" => "Privacy", "href" => "/privacy", "svg" => "privacy_setting"],
                        // ["label" => "API Keys", "href" => "/apikeys", "svg" => "apikey_setting"],
                    ]],
                ] as $item
            ) { ?>
                <div>
                    <a data-link href="<?= '/business/dashboard' . $item['href'] ?>" class="flex space-x-[14px] items-center">
                        <?php require BASEPATH . "/src/common/svgs/business/sidebar/" . $item['svg'] . ".php"; ?>
                        <h1 class="text-white text-[18px] font-medium tracking-[-0.01em]"><?= $item['label'] ?></h1>
                    </a>
                    <?php
                    if (isset($item['sub_label'])) {
                    ?>
                        <div id="sub_sidebar" class="flex flex-col pl-[53px]">
                            <?php foreach ($item['sub_label'] as $sub_item) { ?>
                                <a data-link href="<?= '/business/dashboard/settings' . $sub_item['href'] ?>" class="flex space-x-[14px] items-center mt-[16px]">
                                    <?php require BASEPATH . "/src/common/svgs/business/setting/" . $sub_item['svg'] . ".php"; ?>
                                    <h1 class="text-white text-[14px] font-medium tracking-[-0.01em]"><?= $sub_item['label'] ?></h1>
                                </a>
                            <?php } ?>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <?php if ($item['label'] == "Apps") { ?>
                    <a href="">
                        <div class="w-full border border-[#FFFFFF33]">
                        </div>
                    </a>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>