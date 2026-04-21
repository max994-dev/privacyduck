<div
    class="hidden sm:flex items-center space-x-[10px] py-[31px] pl-[18px] w-fit rounded-tl-[26px] rounded-tr-[26px] bg-[#FEFEFE] border border-b-0 border-[#F6F6F6]">
    <?php
    $data = [
        ["id" => "primary", "name" => "Primary Scan", "bg" => "bg-[#24A55631]", "text" => "text-[#24A556]", "size" => "text-[16px]"],
        ["id" => "face", "name" => "Face Scan", "bg" => "bg-transparent", "text" => "text-[#010205]", "size" => "text-[18px]"],
    ];
    foreach ($data as $value) {
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
            ["id" => "face_mobile", "name" => "Face Scan", "bg" => "bg-transparent", "text" => "text-[#010205]", "size" => "text-[12px]"],
        ];
        foreach ($data as $value) {
        ?>
            <div id="<?= $value["id"] ?>" class="py-[8px] text-center w-[110px] <?= $value["bg"] ?> rounded-full cursor-pointer transition-all duration-[0.2s]">
                <h1 class="font-medium leading-[140%] <?= $value["size"] ?> tracking-[-0.02em] <?= $value["text"] ?>"><?= $value["name"] ?></h1>
            </div>
        <?php
        }
        ?>
    </div>
</div>
<div id="databrokers_screenshot"
    class="px-[18px] sm:px-[43px] py-[15px] sm:py-[23px] rounded-br-[26px] rounded-bl-[26px] sm:rounded-tr-[26px] bg-[#FFFFFF80] border-r border-l border-b sm:border-t border-[#F6F6F6]">
</div>

<script>
    function databrokers_select() {
        const allTabs = ["primary", "face", "primary_mobile", "face_mobile"];
        const couple = {
            primary: "primary_mobile",
            face: "face_mobile"
        };
        const reverseCouple = {
            primary_mobile: "primary",
            face_mobile: "face"
        };

        function setDesktopActive(tabId) {
            ["primary", "face"].forEach((item) => {
                const el = document.getElementById(item);
                el.classList.remove("bg-[#24A55631]");
                el.classList.add("bg-transparent");
                el.querySelector("h1").classList.remove("text-[#24A556]", "text-[16px]");
                el.querySelector("h1").classList.add("text-[#010205]", "text-[18px]");
            });
            const active = document.getElementById(tabId);
            active.classList.remove("bg-transparent");
            active.classList.add("bg-[#24A55631]");
            active.querySelector("h1").classList.remove("text-[#010205]", "text-[18px]");
            active.querySelector("h1").classList.add("text-[#24A556]", "text-[16px]");
        }

        function setMobileActive(tabId) {
            ["primary_mobile", "face_mobile"].forEach((item) => {
                const el = document.getElementById(item);
                el.classList.remove("bg-[#24A55631]");
                el.classList.add("bg-transparent");
                el.querySelector("h1").classList.remove("text-[#24A556]");
                el.querySelector("h1").classList.add("text-[#010205]");
            });
            const active = document.getElementById(tabId);
            active.classList.remove("bg-transparent");
            active.classList.add("bg-[#24A55631]");
            active.querySelector("h1").classList.remove("text-[#010205]");
            active.querySelector("h1").classList.add("text-[#24A556]");
        }

        function loadPrimary() {
            $.get("/dashboard/content/databrokers/primary", data => {
                $("#databrokers_screenshot").html(data);
                if (typeof window.main_databrokers === "function") {
                    window.main_databrokers();
                }
                if (typeof window.main_google_results === "function") {
                    window.main_google_results();
                }
            });
        }

        function loadFace() {
            $.get("/dashboard/content/databrokers/face", data => {
                $("#databrokers_screenshot").html(data);
                if (typeof window.init_face_scan === "function") {
                    window.init_face_scan();
                }
            });
        }

        allTabs.forEach((mainitem) => {
            const element = document.getElementById(mainitem);
            if (!element) return;
            element.addEventListener("click", () => {
                if (["primary", "face"].includes(mainitem)) {
                    setDesktopActive(mainitem);
                    setMobileActive(couple[mainitem]);
                    if (mainitem === "primary") loadPrimary();
                    if (mainitem === "face") loadFace();
                } else {
                    setMobileActive(mainitem);
                    setDesktopActive(reverseCouple[mainitem]);
                    if (mainitem === "primary_mobile") loadPrimary();
                    if (mainitem === "face_mobile") loadFace();
                }
            });
        });

        loadPrimary();
    }
</script>