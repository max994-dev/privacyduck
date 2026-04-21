<div
    class="bg-[url('/assets/image/mobile/background.png')] sm:bg-[url('/assets/image/desktop/background.png')]  bg-no-repeat bg-cover bg-center md:h-[875px]  px-[20px] pt-[200px] pb-[50px] flex flex-col justify-between">
    <div class="flex md:pl-16 md:pr-[48px] items-center pb-[20px] justify-between">
        <div class="flex flex-col max-w-[700px] ">
            <h1 class="leading-[110%] font-semibold text-[48px] tracking-[-1px] lg:text-[72px]">Take Back
                Control of
                Your Digital
                Identity</h1>
            <p class="text-[14px] italic md:text-[16px] md:leading-[180%] mt-[68px]">
                Your personal information is being sold without your consent. Delete your personal contact
                info
                from Google and 500+ Data Brokers that are exploiting you and leaving you exposed to identity
                theft.
            </p>
            <div class="relative mt-[68px] bg-[#FFFFFF1A] rounded-full w-[323px] h-[56px] flex items-center pl-[24px] justify-between">
                <input id="freeScanName" class="bg-transparent border-none outline-none max-w-[120px] placeholder:text-white placeholder:font-medium placeholder:text-[16px] leading-[140%] " placeholder="Full Name" />
                <button id="freeScan" class="freescan flex items-center text-white text-[16px] gap-[4px] font-bold leading-[140%] justify-center bg-[#24A556] rounded-full h-full px-[23px]">
                    <h2>Free Scan</h2>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 12H19" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M12 5L19 12L12 19" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </div>
        <!-- <div
            class="hidden md:block relative bg-[#00530F] rounded-[15px] shadow-[0px_4px_30px_0px_#00530F7D] px-[16px] py-[16px] relative border border-[#F6F6F63B]">
            <div class="mt-[34px] flex flex-col items-center">
                <div>
                    <div class="flex items-center gap-[8px]">
                        <h2 style="font-family: 'Alatsi', sans-serif;" class="text-[28px] tracking-[-0.02em] uppercase text-[#FFFFFF]">Privacy<label class="text-[#FFCF50]" style="font-family: 'Alatsi', sans-serif;">Duck</label></h2>
                        <?php require(BASEPATH . '/src/common/svgs/business/landing/duck.php'); ?>
                    </div>
                </div>
                <div class="mt-[32px]">
                    <h2 class="font-bold text-[24px] tracking-[-0.03em] text-[#FFFFFF]">Request a demo</h2>
                    <div class="mt-[16px] flex grid sm:grid-cols-2 gap-[16px]">
                        <?php
                        $datas = [
                            ["name" => "First Name *", "id" => "contact_landing_first_name", "placeholder" => "John"],
                            ["name" => "Last Name *", "id" => "contact_landing_last_name", "placeholder" => "Mayes"],
                            ["name" => "Business Email *", "id" => "contact_landing_email", "placeholder" => "example@example.com"],
                            ["name" => "Company *", "id" => "contact_landing_company", "placeholder" => "Company"],
                        ];
                        foreach ($datas as $data) {
                        ?>
                            <div class="flex flex-col gap-[6px]">
                                <h2 class="font-medium text-[14px] leading-[20px] text-[#FFFFFF]"><?= $data['name'] ?></h2>
                                <input type="text" id="<?= $data['id'] ?>" placeholder="<?= $data['placeholder'] ?>" class="demo_input text-[#FFCF50] xl:w-[235px]  border border-[#FFCF5040] rounded-[8px] bg-[#FFCF501A] 
                            placeholder:text-[#FFCF5099] placeholder:text-[16px] placeholder:leading-[24px]" />
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="mt-[16px] gap-[16px]">
                        <?php
                        $datas = [
                            ["name" => "How did you hear about Us? *", "id" => "contact_landing_hear", "placeholder" => "Text"],
                        ];
                        foreach ($datas as $data) {
                        ?>
                            <div class="flex flex-col gap-[6px]">
                                <h2 class="font-medium text-[14px] leading-[20px] text-[#FFFFFF]"><?= $data['name'] ?></h2>
                                <textarea id="<?= $data['id'] ?>" placeholder="<?= $data['placeholder'] ?>" class="demo_input h-[111px] px-[14px] py-[10px] text-[#FFCF50] w-full border border-[#FFCF5040] rounded-[8px] bg-[#FFCF501A] 
                            placeholder:text-[#FFCF5099] placeholder:text-[16px] placeholder:leading-[24px]"></textarea>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <button id="contact_landing_demo" disabled class="mt-[32px] w-[324px] h-[56px] flex justify-center items-center gap-[16px] bg-[#FFDC7ECC] rounded-full shadow-[0px_4px_4px_0px_#FFCF5026]">
                    <h2 class="font-bold text-[16px] tracking-[-0.02em] text-[#00530F] leading-[140%]">Book a Demo</h2>
                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 12.5h19" stroke="#00530F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M12 5.5L19 12.5L12 19.5" stroke="#00530F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </div> -->
    </div>
</div>

<!-- Thank You Modal -->
<!-- <div id="thankYouModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg border-2 border-green-300 max-w-md w-full p-6 relative">
        <button id="closeThankYouModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-2xl font-bold">
            ×
        </button>
        
        <div class="text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Thank You for Reaching Out!</h2>
            
            <p class="text-purple-600 mb-3">
                One of our Reputation Specialists will be in contact with you shortly - Check your inbox, and look out for a call from a (775) Area Code!
            </p>
            
            <p class="text-purple-600 mb-6">
                Or if you would like to speak to a Reputation Specialist Immediately
            </p>
            
            <button id="callNowBtn" class="bg-yellow-400 border-2 border-yellow-500 text-gray-800 font-bold py-3 px-8 rounded-full hover:bg-yellow-500 transition-colors">
                Call Now!
            </button>
        </div>
    </div>
</div> -->

<!-- <script>
    let result = false;
    const ids = [
        "contact_landing_first_name",
        "contact_landing_last_name",
        "contact_landing_email",
        "contact_landing_company",
        "contact_landing_hear"
    ];

    function validation() {
        ids.forEach(id => {
            $("#" + id).on("input", function() {
                if (id == "contact_landing_email") {
                    let text = $(this).val(); // Get the email input value
                    let pattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                    result = pattern.test(text);
                }
                if (result && ids.every(id => $("#" + id).val().trim() != "")) {
                    $("#contact_landing_demo").removeAttr("disabled");
                    $("#contact_landing_demo").removeClass("bg-[#FFDC7ECC]").addClass("bg-[#FFDC7E]");
                } else {
                    $("#contact_landing_demo").attr("disabled");
                    $("#contact_landing_demo").removeClass("bg-[#FFDC7E]").addClass("bg-[#FFDC7ECC]");
                }
            });
        });
    }

    // Keep the button click if you also want to validate when clicking "Send"
    $("#contact_landing_demo").click(function() {
        $("#contact_landing_demo").html(window.loadingHtml4);
        $("#contact_landing_demo").attr("disabled", true);
        const data = {
            "business_first_name": $("#contact_landing_first_name").val().trim(),
            "business_last_name": $("#contact_landing_last_name").val().trim(),
            "business_email": $("#contact_landing_email").val().trim(),
            "business_company": $("#contact_landing_company").val().trim(),
            "business_hear": $("#contact_landing_hear").val().trim()
        };
        $.post("/business/speaksalesProcess", data, function(res) {
            if (res.error) {
                toastr.error(res.error);
            } else {
                // Show the Thank You modal instead of toastr
                $("#thankYouModal").removeClass("hidden");
                toastr.success("Demo Booked Successfully");
                // Reset form
                $("#contact_landing_demo").html(`<h2 class="font-bold text-[16px] tracking-[-0.02em] text-[#00530F] leading-[140%]">Book a Demo</h2>
                <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 12.5H19" stroke="#00530F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M12 5.5L19 12.5L12 19.5" stroke="#00530F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>`);
                // Clear form fields
                ids.forEach(id => $("#" + id).val(""));
                $("#contact_landing_demo").attr("disabled", true);
                $("#contact_landing_demo").removeClass("bg-[#FFDC7E]").addClass("bg-[#FFDC7ECC]");
            }
        });
    });

    // Thank You Modal functionality
    $("#closeThankYouModal").click(function() {
        $("#thankYouModal").addClass("hidden");
    });

    // Close modal when clicking on overlay
    $("#thankYouModal").click(function(e) {
        if (e.target === this) {
            $("#thankYouModal").addClass("hidden");
        }
    });

    // Close modal with Escape key
    $(document).keyup(function(e) {
        if (e.keyCode === 27) { // Escape key
            $("#thankYouModal").addClass("hidden");
        }
    });

    // Call Now button functionality (you can customize this)
    $("#callNowBtn").click(function() {
        // You can add phone number or redirect logic here
        window.location.href = "tel:+17754433727"; // Replace with actual phone number
    });

    validation();
</script> -->
