<div class="flex relative mt-[32px] justify-between 2xl:justify-evenly">
    <div
        class="rounded-[30px] flex justify-center items-center bg-[#FEFEFE] border border-[#F6F6F6] w-[223px] h-[301px]">
        <div class="w-[187px]">
            <h1 class="text-[#010205] font-bold text-[18px]">Removal Progress</h1>
            <div class="flex justify-center items-center">
                <div class="relative mt-[10px] w-[170.59px] h-[170.59px] overflow-visible">
                    <svg viewBox="0 0 200 200" class="w-full h-full transform rotate-90 overflow-visible">
                        <path d="" id="bg-arc" stroke="#E9EDF0" stroke-width="10" fill="none" stroke-linecap="round" />
                        <path d="" id="progress-arc" stroke="#E9EDF0" stroke-width="30" fill="none"
                            stroke-linecap="round" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center text-lg font-semibold text-gray-700">
                        <?php if (isset($_SESSION["planable"]) && $_SESSION["planable"]) { ?>
                            <div id="progress_risk" class="text-center text-[#C00000] font-bold">
                                <h1 id="progress_percent-label" class="text-[30px]">0</h1>
                                <h1 id="progress_risk-label" class="text-[14px]">High Risk</h1>
                            </div>
                        <?php } else { ?>
                            <div class="text-center text-[#E9EDF0] font-bold">
                                <h1 id="percent-label" class="text-[30px]">0</h1>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php if (!$_SESSION["plan_id"] || !$_SESSION['planable']) { ?>
                <div class="flex items-center mt-[10px]">
                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 2.29639C13.3261 2.29639 14.5979 2.82317 15.5355 3.76085C16.4732 4.69853 17 5.9703 17 7.29639V10.2964C17.7956 10.2964 18.5587 10.6125 19.1213 11.1751C19.6839 11.7377 20 12.5007 20 13.2964V19.2964C20 20.092 19.6839 20.8551 19.1213 21.4177C18.5587 21.9803 17.7956 22.2964 17 22.2964H7C6.20435 22.2964 5.44129 21.9803 4.87868 21.4177C4.31607 20.8551 4 20.092 4 19.2964V13.2964C4 12.5007 4.31607 11.7377 4.87868 11.1751C5.44129 10.6125 6.20435 10.2964 7 10.2964V7.29639C7 5.9703 7.52678 4.69853 8.46447 3.76085C9.40215 2.82317 10.6739 2.29639 12 2.29639ZM12 14.2964C11.4954 14.2962 11.0094 14.4868 10.6395 14.8299C10.2695 15.173 10.0428 15.6432 10.005 16.1464L10 16.2964C10 16.6919 10.1173 17.0786 10.3371 17.4075C10.5568 17.7364 10.8692 17.9928 11.2346 18.1441C11.6001 18.2955 12.0022 18.3351 12.3902 18.258C12.7781 18.1808 13.1345 17.9903 13.4142 17.7106C13.6939 17.4309 13.8844 17.0745 13.9616 16.6866C14.0387 16.2986 13.9991 15.8965 13.8478 15.531C13.6964 15.1656 13.44 14.8532 13.1111 14.6334C12.7822 14.4137 12.3956 14.2964 12 14.2964ZM12 4.29639C11.2044 4.29639 10.4413 4.61246 9.87868 5.17507C9.31607 5.73768 9 6.50074 9 7.29639V10.2964H15V7.29639C15 6.50074 14.6839 5.73768 14.1213 5.17507C13.5587 4.61246 12.7956 4.29639 12 4.29639Z"
                            fill="#24A556" />
                    </svg>
                    <h1 class="font-bold text-[12px] leading-[130%] underline text-[#010205]">Start Removal</h1>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="rounded-[30px] justify-center items-center bg-[#FEFEFE] border border-[#F6F6F6] w-[calc(100%-253px)] h-[301px] ml-[24px]">
        <div class="px-[18px] pt-[18px] w-full">
            <div class="flex items-center justify-between items-center">
                <h1 class="text-[#010205] font-bold text-[18px] tracking-[0.01em]">How Exposed Are You</h1>
                <div class="flex items-center space-x-[16px]">
                    <h1 class="text-[#010205] tracking-[0.01em] text-[14px] text-outline">
                        Projected(Pro)</h1>
                    <svg width="40" height="4" viewBox="0 0 40 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M-0.00634766 2H40.0062" stroke="#77B248" stroke-width="3" stroke-dasharray="7 7" />
                    </svg>
                    <h1 class="text-[#010205] tracking-[0.01em] text-[14px] text-outline">
                        Historical</h1>
                    <svg width="39" height="3" viewBox="0 0 39 3" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 1.5L39 1.5" stroke="url(#paint0_linear_957_6776)" stroke-width="3" />
                        <defs>
                            <linearGradient id="paint0_linear_957_6776" x1="1" y1="1.99998" x2="38.5" y2="1.49999"
                                gradientUnits="userSpaceOnUse">
                                <stop stop-color="#77B248" />
                                <stop offset="1" stop-color="#24A556" />
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
            </div>
            <div class="chart-container w-full h-[202px] mt-[32px]">
                <canvas id="exposureChart"></canvas>
            </div>
        </div>
    </div>
</div>
<div id="main_progress" class="flex mt-[32px] justify-between space-x-[20px]">
    <?php foreach (
        [
            ["svg" => "removal", "id" => "main_removal"],
            ["svg" => "risk", "id" => "main_risk"],
            ["svg" => "requests", "id" => "main_request"],
        ] as $key => $value
    ) {
    ?>
        <div id="<?php echo $value["id"]; ?>" class="px-[18px] py-[16px] flex-1  bg-[#FEFEFE] border border-[#F6F6F6] rounded-[30px]">
            <h1 class="#010205 text-[18px] font-bold align-middle"></h1>
            <div class="flex items-center mt-[24px] mb-[17px]">
                <?php require(BASEPATH . "/src/common/svgs/dashboard/main/" . $value["svg"] . ".php"); ?>
                <div class="ml-[16px] ">
                    <h1 class="text-[32px] text-[#010205] font-semibold"></h1>
                    <!-- <div class="text-[12px] text-[#010205] tracking-[-0.01em] flex items-center space-x-[5px]">
                        <i class="fa-solid fa-arrow-up text-[20px]"></i>
                        <h1><span class="text-[#24A556]"></span>this month</h1>
                    </div> -->
                </div>
            </div>
        </div>
    <?php
    } ?>
</div>

<script>
    window.removal_progress = parseInt(<?php
                                        $conn = getDBConnection();
                                        //google scan start
                                        $main_stmt = $conn->prepare("SELECT * FROM results WHERE user_id = ? AND kind=1");
                                        $main_stmt->bind_param("i", $_SESSION["user_id"]);
                                        $main_stmt->execute();
                                        $main_result = $main_stmt->get_result();
                                        $data = $main_result->fetch_all(MYSQLI_ASSOC);
                                        $count = count(array_filter($data, function ($item) {
                                            return $item["step"] >= 2;
                                        }));
                                        echo $count;
                                        ?>)

    function main_animate_progress(main_status = "init", increasement = 0) {
        const totalCount = parseInt(<?php
                                        $conn = getDBConnection();
                                        //google scan start
                                        $main_stmt = $conn->prepare("SELECT * FROM results WHERE user_id = ? AND kind=1");
                                        $main_stmt->bind_param("i", $_SESSION["user_id"]);
                                        $main_stmt->execute();
                                        $main_result = $main_stmt->get_result();
                                        $data = $main_result->fetch_all(MYSQLI_ASSOC);
                                        echo count($data);
                                        ?>);
        window.totalcount = totalCount;
        window.removal_progress += increasement;
        let removal_progress = totalCount?window.removal_progress/totalCount*100:0;
        const radius = 90;
        const center = 100;
        const startAngle = 120;
        const sweepAngle = 300;

        const bgArc = document.getElementById("bg-arc");
        const bgArcMobile = document.getElementById("bg-arc-mobile");
        const fgArc = document.getElementById("progress-arc");
        const fgArcMobile = document.getElementById("progress-arc-mobile");
        const label = document.getElementById("progress_percent-label");
        const labelMobile = document.getElementById("progress_percent-label-mobile");

        function polarToCartesian(cx, cy, r, angleDeg) {
            const angleRad = (angleDeg - 90) * Math.PI / 180.0;
            return {
                x: cx + r * Math.cos(angleRad),
                y: cy + r * Math.sin(angleRad)
            };
        }

        function describeArc(cx, cy, r, startAngle, endAngle) {
            const start = polarToCartesian(cx, cy, r, endAngle);
            const end = polarToCartesian(cx, cy, r, startAngle);
            const largeArcFlag = endAngle - startAngle <= 180 ? "0" : "1";
            return [
                "M", start.x, start.y,
                "A", r, r, 0, largeArcFlag, 0, end.x, end.y
            ].join(" ");
        }

        bgArc.setAttribute("d", describeArc(center, center, radius, startAngle, startAngle + sweepAngle));
        bgArcMobile.setAttribute("d", describeArc(center, center, radius, startAngle, startAngle + sweepAngle));

        function animateProgress(to_progress) {
            let progress = 0;
            const planable = <?php echo isset($_SESSION["planable"]) && $_SESSION["planable"] ? "true" : "false"; ?>;
            if (planable) {
                if (main_status === "init") {
                    const interval = setInterval(() => {
                        if (progress > to_progress) {
                            clearInterval(interval);
                            return;
                        }
                        const endAngle = startAngle + (sweepAngle * progress / 100);
                        const path = describeArc(center, center, radius, startAngle, endAngle);
                        fgArc.setAttribute("d", path);
                        fgArcMobile.setAttribute("d", path);
                        if (label) label.textContent = `${progress}%`;
                        if (labelMobile) labelMobile.textContent = `${progress}%`;
                        if (progress < 25) {
                            $("#progress_risk").removeClass("text-[#FFB200]").addClass("text-[#C00000]");
                            $("#progress_risk_mobile").removeClass("text-[#FFB200]").addClass("text-[#C00000]");

                            $("#progress_risk-label").text("High Risk");
                            $("#progress_risk-label_mobile").text("High Risk");

                            fgArc.setAttribute("stroke", "#C00000");
                            fgArcMobile.setAttribute("stroke", "#C00000");
                        }else if(progress < 75){
                            $("#progress_risk").removeClass("text-[#C00000]").addClass("text-[#FFB200]");
                            $("#progress_risk_mobile").removeClass("text-[#C00000]").addClass("text-[#FFB200]");

                            $("#progress_risk-label").text("Medium Risk");
                            $("#progress_risk-label_mobile").text("Medium Risk");

                            fgArc.setAttribute("stroke", "#FFB200");
                            fgArcMobile.setAttribute("stroke", "#FFB200");
                        }else{
                            $("#progress_risk").removeClass("text-[#FFB200]").addClass("text-[#24A556]");
                            $("#progress_risk_mobile").removeClass("text-[#FFB200]").addClass("text-[#24A556]");

                            $("#progress_risk-label").text("Low Risk");
                            $("#progress_risk-label_mobile").text("Low Risk");

                            fgArc.setAttribute("stroke", "#24A556");
                            fgArcMobile.setAttribute("stroke", "#24A556");
                        }
                        progress++;
                    }, 20);
                } else {
                    const endAngle = startAngle + (sweepAngle * to_progress / 100);
                    const path = describeArc(center, center, radius, startAngle, endAngle);
                    fgArc.setAttribute("d", path);
                    fgArcMobile.setAttribute("d", path);
                    if (label) label.textContent = `${Math.round(to_progress)}%`;
                    if (labelMobile) labelMobile.textContent = `${Math.round(to_progress)}%`;
                    if (Math.round(to_progress) < 25) {
                            $("#progress_risk").removeClass("text-[#FFB200]").addClass("text-[#C00000]");
                            $("#progress_risk_mobile").removeClass("text-[#FFB200]").addClass("text-[#C00000]");
                            $("#progress_risk-label").text("High Risk");
                            $("#progress_risk-label_mobile").text("High Risk");
                            fgArcMobile.setAttribute("stroke", "#C00000");
                            fgArc.setAttribute("stroke", "#C00000");
                        }else if(Math.round(to_progress) < 75){
                            $("#progress_risk").removeClass("text-[#C00000]").addClass("text-[#FFB200]");
                            $("#progress_risk_mobile").removeClass("text-[#C00000]").addClass("text-[#FFB200]");
                            $("#progress_risk-label").text("Medium Risk");
                            $("#progress_risk-label_mobile").text("Medium Risk");
                            fgArcMobile.setAttribute("stroke", "#FFB200");
                            fgArc.setAttribute("stroke", "#FFB200");
                        }else{
                            $("#progress_risk").removeClass("text-[#FFB200]").addClass("text-[#24A556]");
                            $("#progress_risk_mobile").removeClass("text-[#FFB200]").addClass("text-[#24A556]");
                            $("#progress_risk-label").text("Low Risk");
                            $("#progress_risk-label_mobile").text("Low Risk");
                            fgArcMobile.setAttribute("stroke", "#24A556");
                            fgArc.setAttribute("stroke", "#24A556");
                        }
                }
            } else {
                const interval = setInterval(() => {
                    if (progress > 0) {
                        clearInterval(interval);
                        return;
                    }
                    const endAngle = startAngle + (sweepAngle * progress / 100);
                    const path = describeArc(center, center, radius, startAngle, endAngle);
                    fgArc.setAttribute("d", path);
                    fgArcMobile.setAttribute("d", path);
                    if (label) label.textContent = `${progress}%`;
                    if (labelMobile) labelMobile.textContent = `${progress}%`;
                    progress++;
                }, 20);
            }
        }

        animateProgress(removal_progress);
    }

    function main_chart() {
        const labels = ['May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr'];
        const ctx = document.getElementById('exposureChart').getContext('2d');
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
                            if (value === 0.2) return 'Initial Scrub';
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

    function main_progress() {
        const data = [{
            title: "Your Removal",
            value: `${window.removal_progress}`,
            percentage: "0",
            icon: "fa-arrow-up",
            id: "main_removal"
        }, {
            title: "Privacy Risk Score",
            value: `${(100-(window.removal_progress/window.totalcount*100).toFixed(2)).toFixed(2)}`,
            percentage: "0",
            icon: "fa-arrow-down",
            id: "main_risk"
        }, {
            title: "Requests Completed",
            value: `${window.totalcount}`,
            percentage: "0",
            icon: "fa-check",
            id: "main_request"
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