<div class="flex relative mt-[16px] justify-between">
    <div
        class="rounded-[30px] flex justify-center bg-[#FEFEFE] border border-[#F6F6F6] w-[160px] h-[184px]">
        <div class="w-[120px]">
            <h1 class="text-[#010205] font-bold text-[14px] tracking-[0.01em] whitespace-nowrap mt-[18px]">Removal Progress</h1>
            <div class="flex justify-center items-center">
                <div class="relative mt-[10px] w-[120px] h-[126px] overflow-visible">
                    <svg viewBox="0 0 200 200" class="w-full h-full transform rotate-90 overflow-visible">
                        <path d="" id="bg-arc-mobile" stroke="#E9EDF0" stroke-width="10" fill="none" stroke-linecap="round" />
                        <path d="" id="progress-arc-mobile" stroke="#E9EDF0" stroke-width="10" fill="none"
                            stroke-linecap="round" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center text-lg font-semibold text-gray-700">
                        <?php if (isset($_SESSION["planable"]) && $_SESSION["planable"]) { ?>
                            <div id="progress_risk_mobile" class="text-center text-[#C00000] font-bold">
                                <h1 id="progress_percent-label-mobile" class="text-[30px]">0</h1>
                                <h1 id="progress_risk-label_mobile" class="text-[14px]">High Risk</h1>
                            </div>
                        <?php } else { ?>
                            <div id="progress_percent_mobile" class="text-center text-[#E9EDF0] font-bold">
                                <h1 id="percent-label_mobile" class="text-[30px]">0</h1>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <!-- <div class="flex items-center mt-[4px]">
                <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M12 2.29639C13.3261 2.29639 14.5979 2.82317 15.5355 3.76085C16.4732 4.69853 17 5.9703 17 7.29639V10.2964C17.7956 10.2964 18.5587 10.6125 19.1213 11.1751C19.6839 11.7377 20 12.5007 20 13.2964V19.2964C20 20.092 19.6839 20.8551 19.1213 21.4177C18.5587 21.9803 17.7956 22.2964 17 22.2964H7C6.20435 22.2964 5.44129 21.9803 4.87868 21.4177C4.31607 20.8551 4 20.092 4 19.2964V13.2964C4 12.5007 4.31607 11.7377 4.87868 11.1751C5.44129 10.6125 6.20435 10.2964 7 10.2964V7.29639C7 5.9703 7.52678 4.69853 8.46447 3.76085C9.40215 2.82317 10.6739 2.29639 12 2.29639ZM12 14.2964C11.4954 14.2962 11.0094 14.4868 10.6395 14.8299C10.2695 15.173 10.0428 15.6432 10.005 16.1464L10 16.2964C10 16.6919 10.1173 17.0786 10.3371 17.4075C10.5568 17.7364 10.8692 17.9928 11.2346 18.1441C11.6001 18.2955 12.0022 18.3351 12.3902 18.258C12.7781 18.1808 13.1345 17.9903 13.4142 17.7106C13.6939 17.4309 13.8844 17.0745 13.9616 16.6866C14.0387 16.2986 13.9991 15.8965 13.8478 15.531C13.6964 15.1656 13.44 14.8532 13.1111 14.6334C12.7822 14.4137 12.3956 14.2964 12 14.2964ZM12 4.29639C11.2044 4.29639 10.4413 4.61246 9.87868 5.17507C9.31607 5.73768 9 6.50074 9 7.29639V10.2964H15V7.29639C15 6.50074 14.6839 5.73768 14.1213 5.17507C13.5587 4.61246 12.7956 4.29639 12 4.29639Z"
                        fill="#24A556" />
                </svg>
                <h1 class="font-bold text-[12px] leading-[130%] underline text-[#010205]">Start Removal</h1>
            </div> -->
        </div>
    </div>
</div>
<div class="mt-[16px] sm:mt-[31px] rounded-[30px] bg-[#FEFEFE] border border-[#F6F6F6] w-full px-[16px] py-[14px]">
    <?php foreach (
        [
            ["svg" => "removal_mobile", "id" => "main_removal_mobile"],
            ["svg" => "risk_mobile", "id" => "main_risk_mobile"],
            ["svg" => "requests_mobile", "id" => "main_request_mobile"],
        ] as $idx => $value
    ) {
        $borderClass = $idx < 2 ? "border-b border-[#F0F0F0]" : "";
    ?>
        <div id="<?php echo $value["id"]; ?>" class="flex items-center justify-between gap-[12px] py-[14px] <?php echo $borderClass; ?>">
            <h1 class="text-[#010205] text-[14px] font-bold leading-tight shrink min-w-0"></h1>
            <div class="flex items-center gap-[10px] shrink-0">
                <?php require(BASEPATH . "/src/common/svgs/dashboard/main/" . $value["svg"] . ".php"); ?>
                <h1 class="text-[22px] sm:text-[24px] text-[#010205] font-semibold tracking-[-0.01em] tabular-nums"></h1>
            </div>
        </div>
    <?php } ?>
</div>

<script>
    function main_progress_mobile() {
        const data = [{
            title: "Your Removal",
            value: `${window.removal_progress}`,
            percentage: "0",
            icon: "fa-arrow-up",
            id: "main_removal_mobile"
        }, {
            title: "Privacy Risk Score",
            value: `${(100 - (window.removal_progress / window.totalcount * 100)).toFixed(1)}`,
            percentage: "0",
            icon: "fa-arrow-down",
            id: "main_risk_mobile"
        }, {
            title: "Requests Completed",
            value: `${window.removal_progress}`,
            percentage: "0",
            icon: "fa-check",
            id: "main_request_mobile"
        }]
        for (let i = 0; i < data.length; i++) {
            const element = document.querySelector("#" + data[i].id);
            element.querySelector("h1").innerHTML = data[i].title;
            element.querySelectorAll("h1")[1].innerHTML = data[i].value;

            // element.querySelector("span").classList.remove("text-[#D0004B]");
            // element.querySelector("span").classList.remove("text-[#24A556]");
            // if (data[i].icon === "fa-arrow-down") element.querySelector("span").classList.add("text-[#D0004B]");
            // else element.querySelector("span").classList.add("text-[#24A556]");
            // element.querySelector("span").innerHTML = data[i].percentage + " %";

            // element.querySelector("i").classList.remove("fa-arrow-down");
            // element.querySelector("i").classList.remove("fa-arrow-up");
            // element.querySelector("i").classList.add(data[i].icon);
            // element.querySelector("i").classList.remove("text-[#D0004B]");
            // element.querySelector("i").classList.remove("text-[#24A556]");
            // if (data[i].icon === "fa-arrow-down") element.querySelector("i").classList.add("text-[#D0004B]");
            // else element.querySelector("i").classList.add("text-[#24A556]");
        }
    }
</script>