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
    <?php foreach (
        [
            ["svg" => "removal_mobile", "id" => "main_removal_mobile"],
        ] as $key => $value
    ) {
    ?>
        <div id="<?php echo $value["id"]; ?>" class="bg-[#FEFEFE] border border-[#F6F6F6] rounded-[30px] ml-[20px] w-[160px] h-[184px] sm:flex-1">
            <div class="text-center">
                <h1 class="text-[#010205] text-[14px] font-bold align-middle mt-[18px]"></h1>
                <div class="flex justify-center mt-[13px]">
                    <?php require(BASEPATH . "/src/common/svgs/dashboard/main/" . $value["svg"] . ".php"); ?>
                </div>
                <h1 class="text-[24px] text-[#010205] font-semibold tracking-[-0.01em] mt-[6px] align-top"></h1>
                <!-- <div class="mt-[6px] text-[12px] text-[#010205] tracking-[-0.01em] flex items-center space-x-[5px] justify-center">
                    <i class="fa-solid fa-arrow-up text-[20px]"></i>
                    <h1><span class="text-[#24A556]"></span>this month</h1>
                </div> -->
            </div>
        </div>
    <?php
    } ?>
</div>
<div class="mt-[31px] rounded-[30px] bg-[#FEFEFE] border border-[#F6F6F6] w-full h-[205px]">
    <h1 class="text-[#010205] font-bold text-[18px] tracking-[0.01em] pl-[18px] pt-[18px]">How Exposed Are You</h1>
    <div class="chart-container w-full h-[118px] mt-[10px] pl-[6px]">
        <canvas id="exposureChart_mobile"></canvas>
    </div>
    <div class="flex items-center space-x-[16px] mt-[8px] pl-[18px]">
        <h1 class="text-[#010205] tracking-[0.01em] text-[8px] text-outline">
            Projected(Pro)</h1>
        <svg width="41" height="2" viewBox="0 0 41 2" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 1H40.0125" stroke="#77B248" stroke-width="2" stroke-dasharray="7 7" />
        </svg>
        <h1 class="text-[#010205] tracking-[0.01em] text-[8px] text-outline">
            Historical</h1>
        <svg width="40" height="2" viewBox="0 0 40 2" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0.0124512 1L39.0125 1" stroke="url(#paint0_linear_1228_11740)" stroke-width="2" />
            <defs>
                <linearGradient id="paint0_linear_1228_11740" x1="1.01245" y1="1.49998" x2="38.5124" y2="0.999988" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#77B248" />
                    <stop offset="1" stop-color="#24A556" />
                </linearGradient>
            </defs>
        </svg>

    </div>
</div>
<div id="main_progress" class="flex mt-[31px] justify-between space-x-[20px]">
    <?php foreach (
        [
            ["svg" => "risk_mobile", "id" => "main_risk_mobile"],
            ["svg" => "requests_mobile", "id" => "main_request_mobile"],
        ] as $key => $value
    ) {
    ?>
        <div id="<?php echo $value["id"]; ?>" class="bg-[#FEFEFE] border border-[#F6F6F6] rounded-[30px] w-[160px] h-[184px] sm:flex-1">
            <div class="text-center">
                <h1 class="text-[#010205] text-[14px] font-bold align-middle mt-[18px]"></h1>
                <div class="flex justify-center mt-[13px]">
                    <?php require(BASEPATH . "/src/common/svgs/dashboard/main/" . $value["svg"] . ".php"); ?>
                </div>
                <h1 class="text-[24px] text-[#010205] font-semibold tracking-[-0.01em] mt-[6px] align-top"></h1>
                <!-- <div class="mt-[6px] text-[12px] text-[#010205] tracking-[-0.01em] flex items-center space-x-[5px] justify-center">
                    <i class="fa-solid fa-arrow-up text-[20px]"></i>
                    <h1><span class="text-[#24A556]"></span>this month</h1>
                </div> -->
            </div>
        </div>
    <?php
    } ?>
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
            value: `${(100-(window.removal_progress/window.totalcount*100).toFixed(2)).toFixed(2)}`,
            percentage: "0",
            icon: "fa-arrow-down",
            id: "main_risk_mobile"
        }, {
            title: "Requests Completed",
            value: `${window.totalcount}`,
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

    function main_chart_mobile() {
        const labels = ['May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr'];
        const ctx = document.getElementById('exposureChart_mobile').getContext('2d');
        const data = {
            labels: labels,
            datasets: [{
                label: 'Projected (Pro)',
                data: [0.3, 0.5, 0.55, 0.6, 0.7, 0.68, 0.74, 0.8, 0.85, 0.86, 0.9, 0.92],
                borderColor: 'rgba(34,197,94,1)', // Tailwind green-500
                borderDash: [6, 6],
                tension: 0.4,
                fill: false,
                pointBackgroundColor: 'rgba(34,197,94,1)',
            }]
        };
        const customPlugin = {
            id: 'backgroundAndLinePlugin',
            beforeDraw: (chart) => {
                const {
                    ctx,
                    chartArea: {
                        left,
                        right,
                        top,
                        bottom
                    },
                    scales: {
                        x,
                        y
                    }
                } = chart;
                ctx.save();

                // 1. Background shaded areas with spacing
                const totalSteps = labels.length;
                const stepWidth = x.getPixelForValue(1) - x.getPixelForValue(0);
                const gap = 6; // gap in pixels between bars
                const barWidth = stepWidth - gap;

                labels.forEach((_, index) => {
                    let xStart;
                    // if(index==0){
                    //   xStart = x.getPixelForValue(index+1) - barWidth / 2;
                    // }else{
                    xStart = x.getPixelForValue(index) - barWidth / 2;
                    // }

                    ctx.fillStyle = '#E8FCE7';
                    ctx.fillRect(xStart, top, barWidth, bottom - top);
                });

                // 2. Draw solid green circle + line at "Initial Scrub"
                const startX = x.getPixelForValue(0);
                const yValue = y.getPixelForValue(0.1); // Initial Scrub

                // Horizontal green line
                ctx.beginPath();
                ctx.moveTo(startX, yValue);
                ctx.lineTo(right, yValue);
                ctx.strokeStyle = '#77B248';
                ctx.lineWidth = 2;
                ctx.stroke();

                // Solid green circle
                ctx.beginPath();
                ctx.arc(startX, yValue, 6, 0, 2 * Math.PI);
                ctx.fillStyle = 'rgba(34,197,94,1)';
                ctx.fill();

                ctx.restore();
            }
        };
        const options = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    min: 0,
                    max: 1,
                    ticks: {
                        callback: function(value) {
                            if (value === 0) return 'Initial Scrub';
                            if (value === 0.4) return 'Ramping Up';
                            if (value === 0.8) return 'Full Protection';
                            return '';
                        },
                        stepSize: 0.2,
                    },
                    grid: {
                        display: false
                    }
                },
                x: {
                    offset: true,
                    ticks: {
                        stepSize: 1,
                        callback: function(value, index, ticks) {
                            // Show labels only at half positions: 0.5, 1.5, 2.5, ...
                            return value % 2 === 0.5 ? this.getLabelForValue(value - 0.5) : '';
                        },
                        major: {
                            enabled: true
                        }
                    },
                    afterBuildTicks: function(scale) {
                        // Replace default ticks with custom tick positions (0.5, 2.5, ...)
                        const ticks = [];
                        for (let i = 0.5; i < labels.length - 0.5; i += 2) {
                            ticks.push({
                                value: i
                            });
                        }
                        scale.ticks = ticks;
                    },
                    grid: {
                        display: false
                    },
                    min: 0,
                    max: labels.length - 1,
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            },
            elements: {
                point: {
                    radius: 6,
                    borderWidth: 2,
                    backgroundColor: 'white',
                    borderColor: 'rgba(34,197,94,1)'
                },
                line: {
                    borderWidth: 2
                }
            }
        };

        new Chart(ctx, {
            type: 'line',
            data: data,
            options: options,
            plugins: [customPlugin]
        });
    }
</script>