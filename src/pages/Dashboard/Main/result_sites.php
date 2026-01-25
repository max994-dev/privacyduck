<?php
require BASEPATH . "/src/pages/Dashboard/sites_data.php";
?>
<div class="py-[16px] md:px-[41px] md:py-[30px]">
    <div class="md:flex items-center justify-between">
        <h1 class="w-[332px] pl-[18px] md:pl-0 text-[#010205] text-[22px] leading-[130%] font-semibold tracking-[-0.01em]">
            Websites that expose personal information</h1>
        <div class="flex items-center justify-between md:justify-normal space-x-[18px] mt-[15px] md:mt-0 pl-[18px] md:pl-0">
            <div class="bg-[#F9FBFF] rounded-[10px] px-[10px] py-[7px] items-center flex w-[200px] md:w-[231.4px] h-[38px]">
                <img src="/assets/image/desktop/icons/mini_zoom_in.svg" alt="zoom_in" />
                <input onchange="setSearchOptions({search: this.value})" value="<?php echo isset($_GET["search"]) ? $_GET["search"] : "" ?>" placeholder="Search"
                    class="ml-[9px] bg-transparent border-none outline-none placeholder:text-[12px] placeholder:text-[#B5B7C0] tracking-[-0.01em] poppins text-[12px] text-[#010205]" />
            </div>
            <div
                class="relative flex items-center justify-center bg-[#F9FBFF] rounded-[10px] inline-block text-left font-[Poppins] w-[120px] md:w-[165px] h-[38px] px-[10px] py-[7px] ">
                <div class="cursor-pointer flex items-center space-x-1" id="dropdownButton">
                    <span class="text-[#9B9B9C] text-[8px] md:text-[12px]">Short by :</span>
                    <span id="selectedOption" class="text-black font-semibold text-[8px] md:text-[12px]">Name</span>
                    <svg class="w-4 h-4 text-black" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z"
                            clip-rule="evenodd" />
                    </svg>
                </div>

                <div id="dropdownMenu" class="hidden absolute right-0 top-[100%] w-full bg-white rounded-md shadow-lg z-10">
                    <ul class="py-1 text-[8px] md:text-[12px] text-gray-700">
                        <li><a href="#" data-value="Name"
                                class="dropdown-item block px-4 py-2 hover:bg-gray-100" onclick="setSearchOptions({sort: 'target_domain'})">Name</a></li>
                        <li><a href="#" data-value="Step"
                                class="dropdown-item block px-4 py-2 hover:bg-gray-100" onclick="setSearchOptions({sort: 'step'})">Step</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-[43px]">
        <div class="overflow-x-auto">
            <table id="exposureTable" class="min-w-full ">
                <thead>
                    <tr>
                        <th class="text-left text-[8px] lg:text-[14px] font-medium text-[#B5B7C0] tracking-[-0.01em]">
                            Website</th>
                        <th class="text-left text-[8px] lg:text-[14px] font-medium text-[#B5B7C0] tracking-[-0.01em]">
                            Plan Coverage
                        </th>
                        <th class="text-left text-[8px] lg:text-[14px] font-medium text-[#B5B7C0] tracking-[-0.01em]">
                            Removal Screenshot
                        </th>
                        <th class="text-left text-[8px] lg:text-[14px] font-medium text-[#B5B7C0] tracking-[-0.01em]">
                            Removal Status
                        </th>
                        <th class="text-left text-[8px] lg:text-[14px] font-medium text-[#B5B7C0] tracking-[-0.01em]">
                            More Info</th>
                    </tr>
                </thead>
                <tbody id="results-table" class="divide-y ">
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    let searchOptions = {
        current: 1,
        pageSize: 10,
        sort: "target_domain",
        search: ""
    };

    function initSearchOptions_and_events() {
        const urlParams = new URLSearchParams(window.location.search);
        setSearchOptions({
            current: urlParams.get("current") || 1,
            pageSize: urlParams.get("pageSize") || 10,
            sort: urlParams.get("sort") || "target_domain",
            search: urlParams.get("search") || ""
        });

    }

    function setSearchOptions(data) {
        if (data) {
            searchOptions = {
                ...searchOptions,
                ...data
            };
            if (data["search"] || data["sort"]) {
                searchOptions.current = 1;
            }
            main_table();
        }
    }

    function setUrlForSearchOption() {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set("current", searchOptions.current);
        urlParams.set("pageSize", searchOptions.pageSize);
        urlParams.set("sort", searchOptions.sort);
        urlParams.set("search", searchOptions.search);
        window.history.pushState(null, "", "?" + urlParams.toString());
    }

    function main_dropdown() {
        const button = document.getElementById("dropdownButton");
        const menu = document.getElementById("dropdownMenu");
        const selectedOption = document.getElementById("selectedOption");

        button.addEventListener("click", () => {
            menu.classList.toggle("hidden");
        });

        window.addEventListener("click", (e) => {
            if (!button.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add("hidden");
            }
        });

        document.querySelectorAll(".dropdown-item").forEach(item => {
            item.addEventListener("click", (e) => {
                e.preventDefault();
                const value = item.getAttribute("data-value");
                selectedOption.textContent = value;
                menu.classList.add("hidden");
            });
        });
    }

    function highlight(text, search) {
        if (!search) return text;
        const regex = new RegExp(search, "gi");
        return text.replace(regex, match => `<span class="bg-yellow-100">${match}</span>`);
    }

    function main_table() {
        setUrlForSearchOption();
        const websites = <?php echo json_encode($websites); ?>;


        function url(name) {
            const names = name.split("com");
            return "https://" + names[0] + ".com";
        }

        const logos = {
            "achcoopcom": "/assets/image/desktop/logos/achcoopcom.avif",
            "across33com": "/assets/image/desktop/logos/across33com.svg",
            "adastradatacom": "https://t1.gstatic.com/faviconV2?client=SOCIAL&type=FAVICON&fallback_opts=TYPE,SIZE,URL&url=https://www.godaddy.com&size=128",
            "addresssearchcom": "/assets/image/desktop/logos/addresssearchcom.png",
        }
        const realUrl = {
            "across33com": "udp.33across.com",
        }
        $.get("/get_results", searchOptions, function(res) {
            const status_label = {
                0: "Not yet removed",
                1: "Ongoing",
                2: "Request sent",
                3: "Not Found",
            }
            const status_color = {
                0: "text-[#C00000]",
                1: "text-[#FFA500]",
                2: "text-[#24A556]",
                3: "text-[#24A556]",
            }
            if ($.fn.DataTable.isDataTable('#exposureTable')) {
                const dataTable = $('#exposureTable').DataTable();
                dataTable.clear().destroy(); // destroy previous instance
            }
            const sites = res.sites;
            const total = res.total;
            const arrayOfDivs = sites.map(site => ({
                logo: logos[site.target_domain] || "https://t1.gstatic.com/faviconV2?client=SOCIAL&type=FAVICON&fallback_opts=TYPE,SIZE,URL&url=" + url(site.target_domain) + "&size=128",
                target_domain: realUrl[site.target_domain] || site.target_domain,
                plan: "",
                src: `/assets/uploads/${site.user_id}/removal/removal_${site.target_domain}_${site.user_id}.png`,
                link: websites[site.target_domain] || url(site.target_domain),
                step: site.step || 0,
            }))

            const tableBody = document.querySelector("#exposureTable tbody");
            if (!tableBody) return;
            tableBody.innerHTML = "";
            if (searchOptions.current > Math.ceil(total / searchOptions.pageSize)) {
                searchOptions.current = Math.ceil(total / searchOptions.pageSize);
            }
            let beforeCnt = (searchOptions.current - 1) * searchOptions.pageSize;
            Array(beforeCnt).fill(0).forEach(() => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            `;
                tableBody.appendChild(tr);
            });
            arrayOfDivs.forEach(row => {
                const tr = document.createElement("tr");
                const img = {
                    0:`<lottie-player 
                                    src="/assets/image/desktop/family/pending.json" background="transparent" speed="1" loop
                                    autoplay style="width: 80px; height: 50px;"></lottie-player>`,
                    1:`<lottie-player 
                                    src="/assets/image/desktop/family/ongoing.json" background="transparent" speed="1" loop
                                    autoplay style="width: 80px; height: 80px;"></lottie-player>`,
                    2:`<img src="${row.src}" class="border border-[#24A556] cursor-pointer w-[80px] h-[40px]" onclick="showFullImage(this.src)">`,
                    3:`<h1 class="text-yellow-500">No information found</h1>`
                }
                const planable = '<?php echo $_SESSION["planable"]; ?>';
                tr.innerHTML = `
                        <td><img class="w-[40px] h-[40px]" src="${row.logo}" alt="logo"></td>
                        <td class="font-semibold text-[8px] lg:text-[14px] tracking-[0.01em] text-[#010205B5]">${highlight(url(row.target_domain), searchOptions.search)}</td>
                        <td class="font-semibold text-[8px] lg:text-[14px] tracking-[0.01em] text-[#010205B5]">
                            ${planable ? 
                            img[row.step] 
                            :
                            `<a href="/dashboard/plans"><lottie-player class="cursor-pointer" 
                                    src="/assets/image/desktop/family/lock.json" background="transparent" speed="1" loop
                                    autoplay style="width: 80px; height: 40px;"></lottie-player></a>` 
                            }
                        </td>
                        <td class="text-[8px] lg:text-[14px] tracking-[0.01em] ${status_color[row.step]}">${status_label[row.step]}</td>
                        <td><span style="cursor:pointer;"><?php require(BASEPATH . "/src/common/svgs/dashboard/main/table_dot.php"); ?></span></td>
                    `;
                tableBody.appendChild(tr);
            });
            if (beforeCnt + arrayOfDivs.length < total) {
                Array(total - (beforeCnt + arrayOfDivs.length)).fill(0).forEach(() => {
                    const tr = document.createElement("tr");
                    tr.innerHTML = `
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                `;
                    tableBody.appendChild(tr);
                });
            }
            const table = $('#exposureTable').DataTable({
                pageLength: searchOptions.pageSize,
                lengthChange: false,
                ordering: false,
                displayStart: (searchOptions.current - 1) * searchOptions.pageSize,
                pagingType: "simple_numbers",
                searching: false,
                language: {
                    paginate: {
                        previous: "<",
                        next: ">"
                    }
                }
            });
            table.off('page.dt').on('page.dt', function() {
                const info = table.page.info();
                searchOptions.current = info.page + 1;
                main_table();
            });
        }, "json")

    }
</script>