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
                <span id="results-summary">Loading…</span>
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
    <!-- Status filter chips. Click to filter the list by status; counts
         come from the same /get_results endpoint so they always match the
         visible total. The chip styling matches the inline row badges below
         (same color per status) so the connection is visually obvious. -->
    <div id="status-filter-chips" class="mt-[18px] flex flex-wrap gap-[8px]">
        <button type="button" data-chip="" class="pd-chip pd-chip-active inline-flex items-center gap-[6px] px-[12px] h-[32px] rounded-full text-[12px] font-semibold border transition-colors">
            <span>All</span>
            <span class="pd-chip-count text-[11px] opacity-75" data-count-key="all">0</span>
        </button>
        <button type="button" data-chip="0" class="pd-chip inline-flex items-center gap-[6px] px-[12px] h-[32px] rounded-full text-[12px] font-semibold border transition-colors">
            <span class="w-[6px] h-[6px] rounded-full bg-[#C00000]"></span>
            <span>Not yet</span>
            <span class="pd-chip-count text-[11px] opacity-75" data-count-key="0">0</span>
        </button>
        <button type="button" data-chip="1" class="pd-chip inline-flex items-center gap-[6px] px-[12px] h-[32px] rounded-full text-[12px] font-semibold border transition-colors">
            <span class="w-[6px] h-[6px] rounded-full bg-[#FFA500]"></span>
            <span>Ongoing</span>
            <span class="pd-chip-count text-[11px] opacity-75" data-count-key="1">0</span>
        </button>
        <button type="button" data-chip="2" class="pd-chip inline-flex items-center gap-[6px] px-[12px] h-[32px] rounded-full text-[12px] font-semibold border transition-colors">
            <span class="w-[6px] h-[6px] rounded-full bg-[#24A556]"></span>
            <span>Sent</span>
            <span class="pd-chip-count text-[11px] opacity-75" data-count-key="2">0</span>
        </button>
        <button type="button" data-chip="3" class="pd-chip inline-flex items-center gap-[6px] px-[12px] h-[32px] rounded-full text-[12px] font-semibold border transition-colors">
            <span class="w-[6px] h-[6px] rounded-full bg-[#9B9B9C]"></span>
            <span>Not found</span>
            <span class="pd-chip-count text-[11px] opacity-75" data-count-key="3">0</span>
        </button>
    </div>
    <style>
        .pd-chip { background:#F4F5F7; color:#5B5F66; border-color:transparent; }
        .pd-chip:hover { background:#ECEDEF; }
        .pd-chip-active { background:#E8F7EF !important; color:#1A7F40 !important; border-color:#BFE7C7 !important; }
        .pd-chip-active .pd-chip-count { opacity:1; }
    </style>

    <div class="mt-[16px]">
        <div class="overflow-x-auto">
            <!-- Single-column table: each row's <td> contains a full card
                 (logo + name + status + screenshot + actions). The JS
                 row-builder below assumes one column; DataTables threw
                 "Incorrect column count" when this header had multiple
                 <th>s. Single header matches the JS reality. -->
            <table id="exposureTable" class="min-w-full">
                <thead>
                    <tr>
                        <th class="text-left text-[11px] font-semibold text-[#878C91] tracking-[0.06em] uppercase pb-[10px]">Broker site &middot; status &middot; screenshot</th>
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
        search: "",
        status: ""   // '' = all, '0'..'3' = step value (Not yet / Ongoing / Sent / Not found)
    };

    function initSearchOptions_and_events() {
        const urlParams = new URLSearchParams(window.location.search);
        setSearchOptions({
            current: urlParams.get("current") || 1,
            pageSize: urlParams.get("pageSize") || 10,
            sort: urlParams.get("sort") || "target_domain",
            search: urlParams.get("search") || "",
            status: urlParams.get("status") || ""
        });
        // Reflect initial status in the chip UI.
        applyChipActiveState(searchOptions.status || "");

        // Chip click handlers. Live across re-renders since chips are static.
        document.querySelectorAll('#status-filter-chips .pd-chip').forEach(btn => {
            btn.addEventListener('click', () => {
                const v = btn.getAttribute('data-chip') || "";
                setSearchOptions({ status: v });
                applyChipActiveState(v);
            });
        });
    }

    function applyChipActiveState(value) {
        document.querySelectorAll('#status-filter-chips .pd-chip').forEach(btn => {
            if ((btn.getAttribute('data-chip') || "") === (value || "")) {
                btn.classList.add('pd-chip-active');
            } else {
                btn.classList.remove('pd-chip-active');
            }
        });
    }

    function updateChipCounts(counts) {
        if (!counts) return;
        document.querySelectorAll('#status-filter-chips .pd-chip-count').forEach(el => {
            const key = el.getAttribute('data-count-key');
            const v = counts[key];
            if (typeof v === 'number') el.textContent = v.toLocaleString();
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
        if (searchOptions.status) {
            urlParams.set("status", searchOptions.status);
        } else {
            urlParams.delete("status");
        }
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

        // "24countercom" -> "24counter.com" ; "verifyrecordscom" -> "verifyrecords.com"
        // Most slugs in the DB are <name> + "com" concatenated. A few have
        // explicit display overrides via realUrl[] (e.g. "across33com" -> "udp.33across.com").
        function prettyName(raw) {
            if (!raw) return "";
            if (raw.endsWith("com")) {
                const stem = raw.slice(0, -3);
                if (stem) return stem + ".com";
            }
            return raw;
        }

        // Icon + label per step. Returns {icon, label, textColor, bgColor, borderColor}.
        function statusMeta(step) {
            switch (step) {
                case 2: return { label: "Request sent", textColor: "#1A7F40", bgColor: "#ECFFF1", borderColor: "#BFE7C7",
                    icon: '<svg width="10" height="10" viewBox="0 0 24 24" fill="none"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>' };
                case 1: return { label: "In progress", textColor: "#9B6B00", bgColor: "#FFF7E6", borderColor: "#FFE1A6",
                    icon: '<svg width="10" height="10" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2.5"/><path d="M12 7v5l3 2" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>' };
                case 3: return { label: "Not found", textColor: "#5B5F66", bgColor: "#F4F5F7", borderColor: "#E5E7EB",
                    icon: '<svg width="10" height="10" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2.5"/><path d="M8 12h8" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>' };
                default: return { label: "Not yet removed", textColor: "#B00020", bgColor: "#FFF0F0", borderColor: "#FFC0C0",
                    icon: '<svg width="10" height="10" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2.5"/></svg>' };
            }
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
            // Update the filter chip counts + the "X of Y" summary line.
            updateChipCounts(res.status_counts || null);
            const summaryEl = document.getElementById("results-summary");
            if (summaryEl) {
                const total = res.total || 0;
                if (total === 0) {
                    summaryEl.textContent = "No brokers match your filters.";
                } else {
                    const start = Math.min(total, (searchOptions.current - 1) * searchOptions.pageSize + 1);
                    const end   = Math.min(total, start + searchOptions.pageSize - 1);
                    summaryEl.textContent = `Showing ${start.toLocaleString()}-${end.toLocaleString()} of ${total.toLocaleString()} brokers`;
                }
            }
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
                const meta = statusMeta(row.step);
                const websiteUrl = row.link ? String(row.link) : "";
                const favicon = websiteUrl
                    ? `https://www.google.com/s2/favicons?domain=${encodeURIComponent(websiteUrl)}&sz=64`
                    : row.logo;
                const niceName = prettyName(row.target_domain || "");
                // Friendly host string for the subtitle (strip scheme + trailing slash).
                const hostShown = websiteUrl
                    ? websiteUrl.replace(/^https?:\/\//, "").replace(/\/$/, "")
                    : niceName;

                const screenshotHtml = (String(planable) && String(planable) !== "0")
                    ? (
                        row.step === 2
                            ? `<img src="${row.src}" class="border border-[#24A556] cursor-pointer w-[72px] h-[40px] rounded-[8px] object-cover hover:opacity-90 transition-opacity" onclick="showFullImage(this.src)" title="View screenshot">`
                            : `<span class="text-[11px] text-[#9B9B9C]">—</span>`
                    )
                    : `<a href="/dashboard/plans" class="text-[11px] text-[#9B9B9C] hover:text-[#24A556]">—</a>`;

                // External link to the broker site itself (only when we have a URL).
                const externalLink = websiteUrl
                    ? `<a href="${escapeHtml(websiteUrl)}" target="_blank" rel="noopener noreferrer"
                          class="opacity-0 group-hover:opacity-100 transition-opacity inline-flex items-center justify-center w-[28px] h-[28px] rounded-[8px] text-[#878C91] hover:text-[#010205] hover:bg-[#F4F5F7]"
                          title="Open broker site"
                          onclick="event.stopPropagation()">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M14 4h6m0 0v6m0-6L10 14M9 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>` : "";

                // Hide the URL subtitle when it's just the niceName again
                // (e.g. "24counter.com" / "24counter.com" — pure noise).
                // Keep it when it's actually different (subdomains, paths
                // like "udp.33across.com/udp_opt_out").
                const showHost = hostShown && hostShown.toLowerCase() !== niceName.toLowerCase();

                const rowCard = `
                    <label class="group flex items-center justify-between gap-[14px] ${row.manualDone ? 'bg-[#F4FBF6]' : 'bg-white'} px-[16px] py-[14px] hover:bg-[#FAFBFC] transition-colors cursor-pointer">
                        <div class="flex items-center gap-[14px] min-w-0 flex-1">
                            <img src="${favicon}" alt="" class="w-[36px] h-[36px] rounded-[8px] object-cover border border-[#EAECEF] bg-white shrink-0"
                                 onerror="this.outerHTML='<div class=&quot;w-[36px] h-[36px] rounded-[8px] bg-[#EAF5ED] flex items-center justify-center text-[#24A556] border border-[#EAECEF] shrink-0&quot;><i class=&quot;fa-solid fa-globe text-[14px]&quot;></i></div>'">
                            <div class="min-w-0 flex-1 flex items-center gap-[14px]">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-[8px] min-w-0">
                                        <span class="text-[14px] font-semibold text-[#010205] truncate">${escapeHtml(niceName)}</span>
                                        ${row.manualDone ? `<span class="shrink-0 inline-flex items-center gap-[3px] px-[6px] py-[1px] rounded-full bg-[#E8F7EF] text-[#1A7F40] text-[10px] font-semibold">
                                            <svg width="9" height="9" viewBox="0 0 24 24" fill="none"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            manually removed
                                        </span>` : ''}
                                    </div>
                                    ${showHost ? `<div class="mt-[2px] text-[11px] text-[#878C91] truncate">${escapeHtml(hostShown)}</div>` : ''}
                                </div>
                                <span class="hidden sm:inline-flex shrink-0 items-center gap-[5px] px-[9px] py-[3px] rounded-full text-[11px] font-semibold whitespace-nowrap"
                                      style="color:${meta.textColor}; background:${meta.bgColor};">
                                    ${meta.icon}
                                    ${escapeHtml(meta.label)}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-[6px] shrink-0">
                            <span class="sm:hidden inline-flex shrink-0 items-center gap-[5px] px-[8px] py-[2px] rounded-full text-[11px] font-semibold whitespace-nowrap"
                                  style="color:${meta.textColor}; background:${meta.bgColor};">
                                ${meta.icon}
                            </span>
                            ${externalLink}
                            ${screenshotHtml}
                            <span style="cursor:pointer;" class="p-[6px] rounded-[8px] text-[#878C91] hover:bg-[#F1F2F4] hover:text-[#010205] transition-colors"><?php require(BASEPATH . "/src/common/svgs/dashboard/main/table_dot.php"); ?></span>
                        </div>
                    </label>
                `;
                tr.innerHTML = `<td class="p-0">${rowCard}</td>`;
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
                lengthChange: true,
                lengthMenu: [10, 25, 50, 100],
                ordering: false,
                displayStart: (searchOptions.current - 1) * searchOptions.pageSize,
                pagingType: "simple_numbers",
                searching: false,
                info: true,
                language: {
                    lengthMenu: "Show _MENU_ per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ brokers",
                    infoEmpty: "No brokers to show",
                    paginate: {
                        previous: "<",
                        next: ">"
                    }
                }
            });
            // Sync the page-size dropdown back into searchOptions so the
            // server-side pagination follows along.
            table.off('length.dt').on('length.dt', function (e, settings, len) {
                searchOptions.pageSize = len;
                searchOptions.current = 1;
                main_table();
            });
            table.off('page.dt').on('page.dt', function() {
                const info = table.page.info();
                searchOptions.current = info.page + 1;
                main_table();
            });
        }, "json")

    }
</script>