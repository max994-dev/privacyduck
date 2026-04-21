<div class="flex items-start justify-between gap-[12px]">
    <div>
        <h1 class="font-bold text-[18px] sm:text-[22px] leading-[130%] text-[#010205]">Custom Scan</h1>
        <p class="mt-[6px] text-[13px] text-[#5C5C5E]">Use this checklist to track manual removals. Checked items sync to Odoo.</p>
    </div>
</div>

<div class="mt-[14px] rounded-[16px] border border-[#D7F0DF] bg-[#F8FFF9] p-[16px] md:p-[22px]">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-[12px]">
        <div class="min-w-0">
            <h2 class="text-[#010205] text-[18px] font-semibold">Manual removal checklist</h2>
            <p class="text-[13px] text-[#5C5C5E] mt-[4px]">Check off links as you complete manual removals.</p>
        </div>
    </div>
    <div id="custom_manual_removal_list" class="mt-[14px] space-y-[8px]"></div>
</div>

<script>
    (function() {
        function escapeHtml(s) {
            return String(s || "").replace(/[&<>"']/g, ch => ({
                "&": "&amp;",
                "<": "&lt;",
                ">": "&gt;",
                "\"": "&quot;",
                "'": "&#39;"
            }[ch]));
        }

        function computeRemovalLink(site) {
            const target = String(site && site.target_domain ? site.target_domain : "");
            if (site && site.removal_url) return String(site.removal_url);
            if (site && site.site_url) return String(site.site_url);
            if (!target) return "#";
            const normalized = target.includes(".") ? target : ("https://" + target.replace(/com$/, ".com"));
            return normalized.startsWith("http") ? normalized : ("https://" + normalized);
        }

        function computeWebsiteUrl(site) {
            const removalLink = computeRemovalLink(site);
            if (removalLink && removalLink !== "#") {
                return removalLink;
            }
            const target = String(site && site.target_domain ? site.target_domain : "");
            if (!target) return "";
            const normalized = target.includes(".") ? target : target.replace(/com$/, ".com");
            return normalized.startsWith("http") ? normalized : ("https://" + normalized);
        }

        function renderChecklist(sites) {
            const container = document.getElementById("custom_manual_removal_list");
            if (!container) return;
            if (!Array.isArray(sites) || sites.length === 0) {
                container.innerHTML = `<p class="text-[13px] text-[#777]">No removal links available yet.</p>`;
                return;
            }
            container.innerHTML = sites.map(site => {
                const checked = Number(site.manual_checklist_done || 0) === 1;
                const safeDomain = escapeHtml(site.target_domain || "");
                const removalLink = escapeHtml(computeRemovalLink(site));
                const websiteUrl = escapeHtml(computeWebsiteUrl(site));
                const logoThumb = websiteUrl
                    ? `<img src="https://www.google.com/s2/favicons?domain=${encodeURIComponent(websiteUrl)}&sz=64" alt="${safeDomain} logo" class="w-[36px] h-[36px] rounded-full object-cover border border-[#D6D6D6] bg-white" onerror="this.outerHTML='<div class=&quot;w-[36px] h-[36px] rounded-full bg-[#EAF5ED] flex items-center justify-center text-[#24A556] border border-[#D6D6D6]&quot;><i class=&quot;fa-solid fa-globe&quot;></i></div>'">`
                    : `<div class="w-[36px] h-[36px] rounded-full bg-[#EAF5ED] flex items-center justify-center text-[#24A556] border border-[#D6D6D6]"><i class="fa-solid fa-globe"></i></div>`;
                return `
                    <label class="flex items-center justify-between gap-[10px] rounded-[10px] border ${checked ? 'border-[#BFE7C7] bg-[#ECFFF1]' : 'border-[#E8E8E8] bg-white'} px-[10px] py-[8px]">
                        <div class="flex items-center gap-[10px] min-w-0">
                            <input type="checkbox" ${checked ? "checked" : ""} onchange="window.toggleCustomManualRemoval('${escapeHtml(site.target_domain || "")}', this.checked)"
                                class="h-4 w-4 accent-[#24A556] shrink-0">
                            ${logoThumb}
                            <span class="text-[13px] text-[#010205] truncate">${safeDomain}</span>
                            <a href="${removalLink}" target="_blank" rel="noopener noreferrer" class="text-[12px] text-[#24A556] underline shrink-0">Removal link</a>
                        </div>
                        <span class="text-[12px] font-semibold ${checked ? 'text-[#24A556]' : 'text-[#9B9B9C]'}">${checked ? '✓ Done' : 'Pending'}</span>
                    </label>
                `;
            }).join("");
        }

        function loadChecklist() {
            $.get("/get_results", {
                current: 1,
                pageSize: 500,
                sort: "target_domain",
                search: ""
            }, function(res) {
                renderChecklist((res && res.sites) ? res.sites : []);
            }, "json").fail(function() {
                const container = document.getElementById("custom_manual_removal_list");
                if (container) container.innerHTML = `<p class="text-[13px] text-[#C00000]">Could not load removal links.</p>`;
            });
        }

        window.toggleCustomManualRemoval = function(targetDomain, checked) {
            $.post("/toggle_manual_removal", {
                target_domain: targetDomain,
                checked: checked ? 1 : 0
            }, function(res) {
                if (res && res.success) {
                    if (typeof toastr !== "undefined") {
                        toastr.success(checked ? "Checked and synced to Odoo." : "Unchecked.");
                    }
                    loadChecklist();
                    return;
                }
                if (typeof toastr !== "undefined") {
                    toastr.error((res && res.error) ? res.error : "Could not update checklist item.");
                }
                loadChecklist();
            }, "json").fail(function(xhr) {
                let msg = "Could not update checklist item.";
                try {
                    const parsed = JSON.parse(xhr.responseText || "{}");
                    if (parsed.error) msg = parsed.error;
                } catch (e) {}
                if (typeof toastr !== "undefined") {
                    toastr.error(msg);
                }
                loadChecklist();
            });
        };

        window.init_custom_scan = function() {
            loadChecklist();
        };

        // Auto-init when loaded directly
        window.init_custom_scan();
    })();
</script>