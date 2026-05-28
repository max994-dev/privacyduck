<?php
require BASEPATH . "/src/pages/Dashboard/sites_data.php";
?>
<div class="py-[16px] md:px-[41px] md:py-[30px]">
    <div class="md:flex items-center justify-between gap-[16px]">
        <div>
            <h1 class="text-[#010205] text-[18px] sm:text-[20px] leading-[130%] font-bold tracking-[-0.01em]">
                All broker sites
            </h1>
            <p class="text-[#5B5F66] text-[12px] sm:text-[13px] mt-[2px]">
                Search or filter to see status of any specific broker.
            </p>
        </div>
        <div class="flex items-center gap-[10px] mt-[15px] md:mt-0">
            <!-- Search input. Higher-contrast placeholder, brand focus ring. -->
            <div class="bg-[#F4F5F7] focus-within:bg-white focus-within:ring-2 focus-within:ring-[#24A556] rounded-[10px] px-[12px] py-[8px] items-center flex w-[200px] md:w-[240px] h-[40px] transition-all">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle cx="11" cy="11" r="7" stroke="#5B5F66" stroke-width="2"/>
                    <path d="M21 21l-4.3-4.3" stroke="#5B5F66" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <input onchange="setSearchOptions({search: this.value})" value="<?php echo isset($_GET["search"]) ? htmlspecialchars($_GET["search"], ENT_QUOTES, 'UTF-8') : "" ?>"
                       placeholder="Search broker name..."
                       class="ml-[9px] bg-transparent border-none outline-none w-full placeholder:text-[13px] placeholder:text-[#878C91] text-[13px] text-[#010205]" />
            </div>
            <div class="relative flex items-center justify-center bg-[#F4F5F7] hover:bg-[#ECEDEF] rounded-[10px] w-[150px] h-[40px] px-[12px] cursor-pointer transition-colors" id="dropdownButton">
                <div class="flex items-center gap-[6px]">
                    <span class="text-[#5B5F66] text-[12px] font-medium">Sort by:</span>
                    <span id="selectedOption" class="text-[#010205] font-semibold text-[12px]">Name</span>
                    <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div id="dropdownMenu" class="hidden absolute right-0 top-[110%] w-full bg-white rounded-md shadow-lg z-10 border border-[#F1F1F1]">
                    <ul class="py-1 text-[12px] text-[#010205]">
                        <li><a href="#" data-value="Name" class="dropdown-item block px-4 py-2 hover:bg-[#F4F5F7]" onclick="setSearchOptions({sort: 'target_domain'})">Name</a></li>
                        <li><a href="#" data-value="Status" class="dropdown-item block px-4 py-2 hover:bg-[#F4F5F7]" onclick="setSearchOptions({sort: 'step'})">Status</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-[24px]">
        <div class="overflow-x-auto">
            <table id="exposureTable" class="min-w-full">
                <thead>
                    <tr class="border-b border-[#F1F1F1]">
                        <th class="text-left text-[11px] font-semibold text-[#878C91] tracking-[0.06em] uppercase pb-[10px]">Broker</th>
                        <th class="text-left text-[11px] font-semibold text-[#878C91] tracking-[0.06em] uppercase pb-[10px] hidden sm:table-cell">Status</th>
                        <th class="text-left text-[11px] font-semibold text-[#878C91] tracking-[0.06em] uppercase pb-[10px] hidden md:table-cell">Screenshot</th>
                        <th class="text-right text-[11px] font-semibold text-[#878C91] tracking-[0.06em] uppercase pb-[10px]"></th>
                    </tr>
                </thead>
                <tbody id="results-table" class="divide-y divide-[#F1F1F1]">
                </tbody>
            </table>
            <p id="results-empty" class="hidden py-[40px] text-center text-[14px] text-[#5B5F66]">
                No brokers match your search. Try a different term.
            </p>
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

    function escapeHtml(s) {
        return String(s || "").replace(/[&<>"']/g, ch => ({
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            "\"": "&quot;",
            "'": "&#39;"
        }[ch]));
    }

    if (typeof window.toggleCustomManualRemoval !== "function") {
        window.toggleCustomManualRemoval = function(targetDomain, checked) {
            $.post("/toggle_manual_removal", {
                target_domain: targetDomain,
                checked: checked ? 1 : 0
            }, function(res) {
                if (res && res.success) {
                    if (typeof toastr !== "undefined") {
                        toastr.success(checked ? "Checked and synced to Odoo." : "Unchecked.");
                    }
                    main_table();
                    return;
                }
                if (typeof toastr !== "undefined") {
                    toastr.error((res && res.error) ? res.error : "Could not update checklist item.");
                }
                main_table();
            }, "json").fail(function(xhr) {
                let msg = "Could not update checklist item.";
                try {
                    const parsed = JSON.parse(xhr.responseText || "{}");
                    if (parsed.error) msg = parsed.error;
                } catch (e) {}
                if (typeof toastr !== "undefined") {
                    toastr.error(msg);
                }
                main_table();
            });
        };
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
            const arrayOfDivs = sites.map(site => {
                const rawDomain = String(site.target_domain || "");
                const safeDomainAttr = rawDomain.replace(/'/g, "\\'");
                const logo = logos[rawDomain] || "https://t1.gstatic.com/faviconV2?client=SOCIAL&type=FAVICON&fallback_opts=TYPE,SIZE,URL&url=" + url(rawDomain) + "&size=128";
                const displayDomain = realUrl[rawDomain] || rawDomain;
                const removalScreenshot = `/assets/uploads/${site.user_id}/removal/removal_${rawDomain}_${site.user_id}.png`;
                const manualDone = Number(site.manual_checklist_done || 0) === 1;
                const step = site.step || 0;
                return {
                    logo,
                    target_domain: displayDomain,
                    rawDomain,
                    safeDomainAttr,
                    src: removalScreenshot,
                    link: websites[rawDomain] || url(rawDomain),
                    manualDone,
                    step
                };
            });

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
                            `;
                tableBody.appendChild(tr);
            });
            arrayOfDivs.forEach(row => {
                const tr = document.createElement("tr");
                const planable = '<?php echo $_SESSION["planable"]; ?>';
                const statusLabel = status_label[row.step] || "Not yet removed";
                const statusClass = status_color[row.step] || "text-[#C00000]";
                const statusPillBg = row.step >= 2 ? "bg-[#ECFFF1] border-[#BFE7C7]" : (row.step === 1 ? "bg-[#FFF7E6] border-[#FFE1A6]" : "bg-[#FFF0F0] border-[#FFC0C0]");
                const websiteUrl = row.link ? String(row.link) : "";
                const favicon = websiteUrl
                    ? `https://www.google.com/s2/favicons?domain=${encodeURIComponent(websiteUrl)}&sz=64`
                    : row.logo;

                const screenshotHtml = (String(planable) && String(planable) !== "0")
                    ? (
                        row.step === 2
                            ? `<img src="${row.src}" class="border border-[#24A556] cursor-pointer w-[72px] h-[40px] rounded-[8px] object-cover" onclick="showFullImage(this.src)">`
                            : (row.step === 3
                                ? `<span class="text-[12px] font-semibold text-[#9B9B9C]">-</span>`
                                : `<span class="text-[12px] font-semibold text-[#9B9B9C]">-</span>`)
                    )
                    : `<a href="/dashboard/plans"><span class="text-[12px] font-semibold text-[#9B9B9C]">-</span></a>`;

                const rowCard = `
                    <label class="flex items-center justify-between gap-[10px] rounded-[12px] border ${row.manualDone ? 'border-[#BFE7C7] bg-[#ECFFF1]' : 'border-[#E8E8E8] bg-white'} px-[10px] py-[10px]">
                        <div class="flex items-center gap-[10px] min-w-0">
                            <img src="${favicon}" alt="logo" class="w-[36px] h-[36px] rounded-full object-cover border border-[#D6D6D6] bg-white"
                                 onerror="this.outerHTML='<div class=&quot;w-[36px] h-[36px] rounded-full bg-[#EAF5ED] flex items-center justify-center text-[#24A556] border border-[#D6D6D6]&quot;><i class=&quot;fa-solid fa-globe&quot;></i></div>'">
                            <div class="min-w-0">
                                <div class="text-[13px] font-semibold text-[#010205] truncate">${escapeHtml(row.target_domain || '')}</div>
                                <div class="mt-[2px] flex items-center gap-[8px]">
                                    <span class="inline-flex items-center px-[8px] py-[2px] rounded-full border ${statusPillBg} text-[12px] font-semibold ${statusClass}">
                                        ${escapeHtml(statusLabel)}
                                    </span>
                                    ${row.manualDone ? `
                                        <span class="text-[12px] font-semibold text-[#24A556]">
                                            ✓ manually removed.
                                        </span>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-[10px] shrink-0">
                            ${screenshotHtml}
                            <span style="cursor:pointer;"><?php require(BASEPATH . "/src/common/svgs/dashboard/main/table_dot.php"); ?></span>
                        </div>
                    </label>
                `;
                tr.innerHTML = `
                        <td class="py-[8px]">${rowCard}</td>
                    `;
                tableBody.appendChild(tr);
            });
            if (beforeCnt + arrayOfDivs.length < total) {
                Array(total - (beforeCnt + arrayOfDivs.length)).fill(0).forEach(() => {
                    const tr = document.createElement("tr");
                    tr.innerHTML = `
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