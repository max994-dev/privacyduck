<div
    class="hidden sm:flex items-center space-x-[10px] py-[31px] pl-[18px] w-fit rounded-tl-[26px] rounded-tr-[26px] bg-[#FEFEFE] border border-b-0 border-[#F6F6F6]">
    <?php
    $data = [
        ["id" => "primary", "name" => "Primary Scan", "bg" => "bg-[#24A55631]", "text" => "text-[#24A556]", "size" => "text-[16px]"],
        ["id" => "custom", "name" => "Custom Scan", "bg" => "bg-transparent", "text" => "text-[#010205]", "size" => "text-[18px]"],
        ["id" => "face", "name" => "Face Scan", "bg" => "bg-transparent", "text" => "text-[#010205]", "size" => "text-[18px]"],
    ];
    foreach ($data as $key => $value) {
    ?>
        <div id="<?= $value["id"] ?>" class="py-[16px] text-center w-[160px] <?= $value["bg"] ?> rounded-full cursor-pointer transition-all duration-[0.2s]">
            <h1 class="font-medium leading-[140%] <?= $value["size"] ?> tracking-[-0.02em] <?= $value["text"] ?>"><?= $value["name"] ?></h1>
        </div>
    <?php
    }
    ?>
</div>
<div
    class="sm:hidden flex justify-center items-center py-[16px] w-full rounded-tl-[26px] rounded-tr-[26px] bg-[#FEFEFE] border border-b-0 border-[#F6F6F6]">
    <div class="flex items-center space-x-[10px] bg-[#F6F6F69E] rounded-full">
        <?php
        $data = [
            ["id" => "primary_mobile", "name" => "Primary Scan", "bg" => "bg-[#24A55631]", "text" => "text-[#24A556]", "size" => "text-[12px]"],
            ["id" => "custom_mobile", "name" => "Custom Scan", "bg" => "bg-transparent", "text" => "text-[#010205]", "size" => "text-[12px]"],
            ["id" => "face_mobile", "name" => "Face Scan", "bg" => "bg-transparent", "text" => "text-[#010205]", "size" => "text-[12px]"],
        ];
        foreach ($data as $key => $value) {
        ?>
            <div id="<?= $value["id"] ?>" class="py-[8px] text-center w-[94px] <?= $value["bg"] ?> rounded-full cursor-pointer transition-all duration-[0.2s]">
                <h1 class="font-medium leading-[140%] <?= $value["size"] ?> tracking-[-0.02em] <?= $value["text"] ?>"><?= $value["name"] ?></h1>
            </div>
        <?php
        }
        ?>
    </div>
</div>
<div id="databrokers_screenshot"
    class="px-[18px] sm:px-[43px] py-[15px] sm:py-[23px] rounded-br-[26px] rounded-bl-[26px] sm:rounded-tr-[26px] bg-[#FFFFFF80]  border-r border-l border-b sm:border-t border-[#F6F6F6]">
</div>

<script>
    function databrokers_select() {
        const data = ["primary", "custom", "face", "primary_mobile", "custom_mobile", "face_mobile"];
        const couple = {
            primary:"primary_mobile",
            custom:"custom_mobile",
            face:"face_mobile"
        }
        const reverse_couple = {
            primary_mobile:"primary",
            custom_mobile:"custom",
            face_mobile:"face"
        }
        data.map((mainitem) => {
            const element = document.getElementById(mainitem);
            element.addEventListener("click", () => {
                if (["primary", "custom", "face"].includes(mainitem)) {
                    ["primary", "custom", "face"].forEach((item) => {
                        document.getElementById(item).classList.remove("bg-[#24A55631]");
                        document.getElementById(item).classList.add("bg-transparent");
                        document.getElementById(item).querySelector("h1").classList.remove("text-[#24A556]");
                        document.getElementById(item).querySelector("h1").classList.add("text-[#010205]");
                        document.getElementById(item).querySelector("h1").classList.remove("text-[16px]");
                        document.getElementById(item).querySelector("h1").classList.add("text-[18px]");
                        
                        document.getElementById(couple[item]).classList.remove("bg-[#24A55631]");
                        document.getElementById(couple[item]).classList.add("bg-transparent");
                        document.getElementById(couple[item]).querySelector("h1").classList.remove("text-[#24A556]");
                        document.getElementById(couple[item]).querySelector("h1").classList.add("text-[#010205]");
                    });
                    element.classList.remove("bg-transparent");
                    element.classList.add("bg-[#24A55631]");
                    element.querySelector("h1").classList.remove("text-[#010205]");
                    element.querySelector("h1").classList.add("text-[#24A556]");
                    element.querySelector("h1").classList.remove("text-[18px]");
                    element.querySelector("h1").classList.add("text-[16px]");

                    document.getElementById(couple[mainitem]).classList.remove("bg-transparent");
                    document.getElementById(couple[mainitem]).classList.add("bg-[#24A55631]");
                    document.getElementById(couple[mainitem]).querySelector("h1").classList.remove("text-[#010205]");
                    document.getElementById(couple[mainitem]).querySelector("h1").classList.add("text-[#24A556]");
                } else {
                    ["primary_mobile", "custom_mobile", "face_mobile"].forEach((item) => {
                        document.getElementById(item).classList.remove("bg-[#24A55631]");
                        document.getElementById(item).classList.add("bg-transparent");
                        document.getElementById(item).querySelector("h1").classList.remove("text-[#24A556]");
                        document.getElementById(item).querySelector("h1").classList.add("text-[#010205]");

                        document.getElementById(reverse_couple[item]).classList.remove("bg-[#24A55631]");
                        document.getElementById(reverse_couple[item]).classList.add("bg-transparent");
                        document.getElementById(reverse_couple[item]).querySelector("h1").classList.remove("text-[#24A556]");
                        document.getElementById(reverse_couple[item]).querySelector("h1").classList.add("text-[#010205]");
                        document.getElementById(reverse_couple[item]).querySelector("h1").classList.remove("text-[16px]");
                        document.getElementById(reverse_couple[item]).querySelector("h1").classList.add("text-[18px]");
                    });
                    element.classList.remove("bg-transparent");
                    element.classList.add("bg-[#24A55631]");
                    element.querySelector("h1").classList.remove("text-[#010205]");
                    element.querySelector("h1").classList.add("text-[#24A556]");

                    document.getElementById(reverse_couple[mainitem]).classList.remove("bg-transparent");
                    document.getElementById(reverse_couple[mainitem]).classList.add("bg-[#24A55631]");
                    document.getElementById(reverse_couple[mainitem]).querySelector("h1").classList.remove("text-[#010205]");
                    document.getElementById(reverse_couple[mainitem]).querySelector("h1").classList.add("text-[#24A556]");
                    document.getElementById(reverse_couple[mainitem]).querySelector("h1").classList.remove("text-[18px]");
                    document.getElementById(reverse_couple[mainitem]).querySelector("h1").classList.add("text-[16px]");
                }
                if (mainitem == "primary" || mainitem == "primary_mobile") {
                    $.get("/dashboard/content/databrokers/primary", data => {
                        $("#databrokers_screenshot").html(data);
                        main_databrokers();
                        main_google_results();
                    });
                }
                if (mainitem == "custom" || mainitem == "custom_mobile") {
                    toastr.info("Custom Scan is not available yet.Coming Soon")
                    // $.get("/dashboard/content/databrokers/custom", data => {
                    //     $("#databrokers_screenshot").html(data);
                    // });
                }
                if (mainitem == "face" || mainitem == "face_mobile") {
                    toastr.info("Face Scan is not available yet.Coming Soon")
                    // $.get("/dashboard/content/databrokers/face", data => {
                    //     $("#databrokers_screenshot").html(data);
                    // });
                }
            });
        });
        $.get("/dashboard/content/databrokers/primary", data => {
            $("#databrokers_screenshot").html(data);
            main_databrokers();
            main_google_results();
        });
    }
</script>