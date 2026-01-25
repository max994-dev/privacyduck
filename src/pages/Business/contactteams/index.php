<div id="business_contactteam_modal"
    class="fixed inset-0 inset-0 bg-[#00000040] px-[16px] border-1 border-[#F6F6F63A] backdrop-blur-md flex items-center justify-center hidden z-[19] animate-[opacity_0.5s_ease-out]">
    <div
        class="relative bg-[#00530F] rounded-[15px] shadow-[0px_4px_30px_0px_#00530F7D] px-[8px] py-[16px] sm:px-[30px] sm:py-[32px] relative border border-[#F6F6F63B]">
        <button id="business_closecontactmodal" onclick="business_closecontactmodal()" class="absolute top-[32px] right-[30px] text-center font-bold text-gray-500 hover:text-red-500">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 5L5 19M5 5L19 19" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </button>
        <div class="mt-[34px] flex flex-col items-center">
            <div>
                <div class="flex items-center gap-[8px]">
                    <h1 style="font-family: 'Alatsi', sans-serif;" class="text-[28px] tracking-[-0.02em] uppercase text-[#FFFFFF]">Privacy<label class="text-[#FFCF50]" style="font-family: 'Alatsi', sans-serif;">Duck</label></h1>
                    <?php require(BASEPATH . '/src/common/svgs/business/landing/duck.php'); ?>
                </div>
                <h1 style="font-family: 'Alatsi', sans-serif;" class="relative top-[-10px] text-[20px] tracking-[-0.02em] text-[#FFFFFF] uppercase">Enterprice</h1>
            </div>
            <div class="mt-[32px]">
                <h1 class="font-bold text-[24px] tracking-[-0.03em] text-[#FFFFFF]">Request a demo</h1>
                <div class="mt-[16px] flex grid sm:grid-cols-2 gap-[16px]">
                    <?php
                    $datas = [
                        ["name" => "First Name *", "id" => "contact_demo_first_name", "placeholder" => "John"],
                        ["name" => "Last Name *", "id" => "contact_demo_last_name", "placeholder" => "Mayes"],
                        ["name" => "Business Email *", "id" => "contact_demo_email", "placeholder" => "example@example.com"],
                        ["name" => "Company *", "id" => "contact_demo_company", "placeholder" => "Company"],
                    ];
                    foreach ($datas as $data) {
                    ?>
                        <div class="flex flex-col gap-[6px]">
                            <h1 class="font-medium text-[14px] leading-[20px] text-[#FFFFFF]"><?= $data['name'] ?></h1>
                            <input type="text" id="<?= $data['id'] ?>" placeholder="<?= $data['placeholder'] ?>" class="demo_input text-[#FFCF50] w-[324px] sm:w-[295px] border border-[#FFCF5040] rounded-[8px] bg-[#FFCF501A] 
                            placeholder:text-[#FFCF5099] placeholder:text-[16px] placeholder:leading-[24px]" />
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <div class="mt-[16px] gap-[16px]">
                    <?php
                    $datas = [
                        ["name" => "How did you hear about Us? *", "id" => "contact_demo_hear", "placeholder" => "Text"],
                    ];
                    foreach ($datas as $data) {
                    ?>
                        <div class="flex flex-col gap-[6px]">
                            <h1 class="font-medium text-[14px] leading-[20px] text-[#FFFFFF]"><?= $data['name'] ?></h1>
                            <textarea id="<?= $data['id'] ?>" placeholder="<?= $data['placeholder'] ?>" class="demo_input h-[111px] px-[14px] py-[10px] text-[#FFCF50] w-full border border-[#FFCF5040] rounded-[8px] bg-[#FFCF501A] 
                            placeholder:text-[#FFCF5099] placeholder:text-[16px] placeholder:leading-[24px]"></textarea>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <button id="contact_book_demo" disabled class="mt-[32px] w-[324px] h-[56px] flex justify-center items-center gap-[16px] bg-[#FFDC7ECC] rounded-full shadow-[0px_4px_4px_0px_#FFCF5026]">
                <h1 class="font-bold text-[16px] tracking-[-0.02em] text-[#00530F] leading-[140%]">Book a Demo</h1>
                <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 12.5H19" stroke="#00530F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M12 5.5L19 12.5L12 19.5" stroke="#00530F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    </div>
</div>
<script>
    let result = false;
    const ids = [
        "contact_demo_first_name",
        "contact_demo_last_name",
        "contact_demo_email",
        "contact_demo_company",
        "contact_demo_hear"
    ];

    function business_closecontactmodal() {
        document.getElementById("business_contactteam_modal").classList.add("hidden");
    }

    function validation() {
        ids.forEach(id => {
            $("#" + id).on("input", function() {
                if (id == "contact_demo_email") {
                    let text = $(this).val(); // Get the email input value
                    let pattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                    result = pattern.test(text);
                }
                if (result && ids.every(id => $("#" + id).val().trim() != "")) {
                    $("#contact_book_demo").removeAttr("disabled");
                    $("#contact_book_demo").removeClass("bg-[#FFDC7ECC]").addClass("bg-[#FFDC7E]");
                } else {
                    $("#contact_book_demo").attr("disabled");
                    $("#contact_book_demo").removeClass("bg-[#FFDC7E]").addClass("bg-[#FFDC7ECC]");
                }
            });
        });
    }

    // Keep the button click if you also want to validate when clicking "Send"
    $("#contact_book_demo").click(function() {
        $("#contact_book_demo").html(window.loadingHtml4);
        $("#contact_book_demo").attr("disabled", true);
        const data = {
            "business_first_name": $("#contact_demo_first_name").val().trim(),
            "business_last_name": $("#contact_demo_last_name").val().trim(),
            "business_email": $("#contact_demo_email").val().trim(),
            "business_company": $("#contact_demo_company").val().trim(),
            "business_hear": $("#contact_demo_hear").val().trim()
        };
        $.post("/business/speaksalesProcess", data, function(res) {
            if(res.error){
                toastr.error(res.error);
            }else{
                document.getElementById("business_contactteam_notify_modal").classList.remove("hidden");
                document.getElementById("business_contactteam_modal").classList.add("hidden");
            }
            $("#contact_book_demo").html(`<h1 class="font-bold text-[16px] tracking-[-0.02em] text-[#00530F] leading-[140%]">Book a Demo</h1>
                <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 12.5H19" stroke="#00530F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M12 5.5L19 12.5L12 19.5" stroke="#00530F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>`);
            $("#contact_book_demo").attr("disabled", false);
        });
    });
    validation();
</script>