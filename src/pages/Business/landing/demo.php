<div class="px-[16px] py-[60px]">
    <div id="c" class="relative bg-[#FFCF500F] rounded-[30px] flex justify-between pt-[82px] pr-[150px] pl-[80px]">
        <div class="absolute top-[288px] left-[502px] z-[10] ">
            <?php require(BASEPATH . '/src/common/svgs/business/landing/center_duck.php') ?>
        </div>
        <div id="a" class="relative max-w-[500px] xl:w-[626px] xl:max-w-none flex flex-col z-[10]">
            <h2 class="font-bold text-[24px] leading-[130%] tracking-[-0.03em] text-[#00530F] align-middle">
                The #1 personal data removal service
            </h2>
            <h2 class="mt-[24px] font-semibold text-[48px] leading-[130%] tracking-[-0.03em] text-[#010205] align-middle xl:whitespace-nowrap">
                Why PrivacyDuck Enterprise?
            </h2>
            <p class="mt-[33px] font-medium text-[18px] leading-[150%] text-[#878C91]">
                When your team’s personal data is exposed online, your business becomes an easy target. From phishing to doxing,
                attackers use public employee data to bypass security systems and launch tailored cyberattacks.
                PrivacyDuck Enterprise protects your company by continuously removing sensitive personal
                information from hundreds of public sites, search engines, and data brokers, reducing the risk of breaches,
                identity theft, and insider threats — making us your trusted business data privacy provider.
            </p>
            <div class="flex justify-center mt-[66px]">
                <img src="/assets/image/desktop/business/landing/demo_mindmap.png" class="w-[542px] h-[352px]" alt="business data privacy provider" />
            </div>
            <p class="mt-[32px] font-medium text-[18px] leading-[150%] text-[#878C91]">
                Backed by CCPA and GDPR-compliant processes and real-time monitoring, we help you minimize
                your human attack surface and stay compliant without slowing your business down.
            </p>
            <h2 class="mt-[24px] font-semibold text-[32px] leading-[130%] tracking-[-0.03em] text-[#010205] align-middle xl:whitespace-nowrap">
                Who’s Most at Risk?
            </h2>
            <div class="mt-[44px]">
                <?php
                $datas = [
                    [
                        "title" => "Executives and Board Members – ",
                        "content" => "High-level staff are top targets for attackers. Cybercriminals use leaked personal details to impersonate them or launch direct attacks, often combining physical threats with digital ones. 
                                PrivacyDuck helps protect executive data from staying online too long, reducing the chance of becoming a high-value target through strategic online data removal for executives.",
                        "icon" => "board_member"
                    ],
                    [
                        "title" => "IT Admins and Managers – ",
                        "content" => "These users control access to internal systems, making them a favorite target for hackers. 
                        If their personal data is exposed, social engineering attacks can slip past even the best technical defenses. 
                        Our privacy software helps reduce exposure before it becomes a breach.",
                        "icon" => "managers"
                    ],
                    [
                        "title" => "Employees with Access to Sensitive Data – ",
                        "content" => "Even junior employees can be used as entry points. Whether it’s login credentials, internal tools, or customer info, 
                                    leaked PII opens the door to phishing, fraud, or worse. With enterprise-grade data protection, 
                                    PrivacyDuck limits how much attackers can find.",
                        "icon" => "employees"
                    ]
                ];
                foreach ($datas as $index => $data) {
                ?>
                    <div class="flex items-center <?php echo $index == count($datas) - 1 ? "pt-[30px]" : "py-[30px]" ?> border-t border-[#FFCF50] gap-[18px]">
                        <?php require(BASEPATH . '/src/common/svgs/business/landing/' . $data['icon'] . '.php') ?>
                        <div class="flex flex-1">
                            <p class="text-[16px] leading-[150%] text-[#878C91] font-medium">
                                <span class="text-[#010205] font-semibold whitespace-nowrap"><?= $data['title'] ?></span>
                                <?= $data['content'] ?>
                            </p>
                        </div>
                    </div>
                <?php
                }
                ?>

            </div>
            <p class="mt-[32px] text-[18px] leading-[150%] text-[#878C91] font-medium">
                <span class="text-[#010205] font-semibold whitespace-nowrap">The Bottom Line : </span>
                Your employees' personal data is everywhere, and that makes your company vulnerable. Attackers don’t need to
                breach your systems if they can breach your people.
                PrivacyDuck Enterprise gives you control over your human attack surface.
                With real-time removal, automated monitoring, and GDPR-compliant workflows,
                we help you secure what most enterprise security platforms ignore: your team’s PII.
                Our employee privacy protection solution ensures it’s not just protection it’s prevention.
            </p>
            <div class="h-[116.91px]"></div>
        </div>

        <div class="relative w-[384px] z-[12]">
            <div id="b" class="sticky top-[24px]">
                <div class="px-[30px] py-[24px] bg-[#00530F] border border-[#F6F6F63B] shadow-[0px_4px_30px_0px_#00530F7D] rounded-[15px]">
                    <div class="flex justify-center">
                        <img class="w-[207px] h-[58px]" src="/assets/image/desktop/business/landing/demo_logo.png" />
                    </div>
                    <div class="mt-[32px]">
                        <h2 class="font-bold text-[24px] align-middle text-white">Request a demo</h2>
                        <div class="mt-[16px] flex flex-col gap-[16px]">
                            <?php
                            $datas = [
                                ["name" => "First Name *", "id" => "demo_first_name", "placeholder" => "John"],
                                ["name" => "Last Name *", "id" => "demo_last_name", "placeholder" => "Mayes"],
                                ["name" => "Business Email *", "id" => "demo_email", "placeholder" => "example@example.com"],
                                ["name" => "Company *", "id" => "demo_company", "placeholder" => "Company"],
                                ["name" => "How did you hear about Us? *", "id" => "demo_hear", "placeholder" => "Text"],
                            ];
                            foreach ($datas as $data) {
                            ?>
                                <?php if ($data['id'] == "demo_hear") { ?>
                                    <div class="flex flex-col gap-[6px]">
                                        <h2 class="font-medium text-[14px] leading-[20px] text-[#FFFFFF]"><?= $data['name'] ?></h2>
                                        <textarea id="<?= $data['id'] ?>" placeholder="<?= $data['placeholder'] ?>" class="demo_input h-[111px] px-[14px] py-[10px] text-[#FFCF50] w-full border border-[#FFCF5040] rounded-[8px] bg-[#FFCF501A] 
                                    placeholder:text-[#FFCF5099] placeholder:text-[16px] placeholder:leading-[24px]"></textarea>
                                    </div>
                                <?php } else { ?>
                                    <div class="flex flex-col gap-[6px]">
                                        <h2 class="font-medium text-[14px] leading-[20px] text-[#FFFFFF]"><?= $data['name'] ?></h2>
                                        <input type="text" id="<?= $data['id'] ?>" placeholder="<?= $data['placeholder'] ?>" class="demo_input text-white w-full border border-[#FFCF5040] rounded-[8px] bg-[#FFCF501A]" />
                                    </div>
                                <?php } ?>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="mt-[32px]">
                        <button id="book_demmo_btn" class="w-full h-[56px] flex justify-center items-center gap-[16px] shadow-[0px_4px_4px_0px_#FFCF5026] bg-[#FFCF50] rounded-full">
                            <h2 class="text-[#00530F] font-bold text-[16px] leading-[140%] tracking-[-0.02em]">Book a Demo</h2>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 12H19" stroke="#00530F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12 5L19 12L12 19" stroke="#00530F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function demo_init() {
        $("#book_demmo_btn").click(function() {
            $("#book_demmo_btn").html(window.loadingHtml4);
            $("#book_demmo_btn").attr("disabled", true);
            const data = {
                "business_first_name": $("#demo_first_name").val().trim(),
                "business_last_name": $("#demo_last_name").val().trim(),
                "business_email": $("#demo_email").val().trim(),
                "business_company": $("#demo_company").val().trim(),
                "business_hear": $("#demo_hear").val().trim()
            };
            $.post("/business/speaksalesProcess", data, function(res) {
                if (res.error) {
                    toastr.error(res.error);
                } else {
                    toastr.success("Demo Booked Successfully");
                }
                $("#book_demmo_btn").html(`<h2 class="text-[#00530F] font-bold text-[16px] leading-[140%] tracking-[-0.02em]">Book a Demo</h2>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 12H19" stroke="#00530F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12 5L19 12L12 19" stroke="#00530F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>`);
                $("#book_demmo_btn").attr("disabled", false);
            });
        });
    }
    demo_init();
</script>