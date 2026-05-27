<?php
$meta_title = "Sites We Cover for Online Privacy Protection | PrivacyDuck";
$meta_description = "Remove personal information from major data brokers with PrivacyDuck. Protect your privacy and limit exposure across multiple online platforms safely.";
$meta_url = "https://privacyduck.com/";
$meta_image = "https://privacyduck.com/assets/pageSEO/landing.jpg";

include_once(BASEPATH . "/src/common/meta.php");
main_head_start();
main_head_end();
main_header("black");
// main_splash();
?>
<div class=" px-[16px] sm:pl-[80px] sm:pr-[48px] pt-[149px] pb-[70px] lg:pt-[128px] lg:pb-[130px] bg-[#FAFAFA]">
    <div>
        <a href="/" class="flex items-center space-x-[6px]">
            <?php require(BASEPATH . "/src/common/svgs/sitecover/small_sign.php"); ?>
            <h1 class="text-[#515665] text-[16px] font-medium leading-[180%]">Sites We Cover</h1>
        </a>
    </div>
    <div class="flex items-center justify-between mt-[23px] sm:mt-[42px] ">
        <div class="md:max-w-[755px]">
            <h2 class="font-semibold text-[32px] md:text-[72px] tracking-[-0.03em] leading-[110%] text-[#010205]">
                PrivacyDuck removes personal information from 413 websites.
            </h2>
            <h2 class="mt-[24px] md:mt-[48px] text-[#010205F2] text-[14px] sm:text-[16px] font-medium leading-[180%]">
                Because the data broker industry is growing by leaps and bounds and new people search websites appear all the time, we do everything possible to cover them on time. With PrivacyDuck you can be sure, your name, current and previous addresses, phone numbers, photos of your home, age won’t pop up on Google search any longer. Below you will find the list of websites we remove from.
            </h2>
        </div>
        <div class="relative hidden xl:ml-[60px] w-[490px] h-[490px] xl:flex items-center justify-center">
            <div class="absolute  w-[490px] h-[490px] rounded-full border bg-transparent  border-[#24A556]">
            </div>
            <div class="absolute  w-[411.44px] h-[411.44px] rounded-full border bg-transparent  border-[#24A556]">
            </div>
            <div class="absolute w-[341px] h-[341px] rounded-full border bg-transparent  border-[#24A556]">
            </div>
            <div class="absolute w-[276px] h-[276px] rounded-full border bg-transparent  border-[#24A556]">
            </div>
            <div>
                <?php require_once(BASEPATH . "/src/common/svgs/sitecover/bigduck.php"); ?>
            </div>
            <img src="/assets/image/desktop/turnduck.svg" style="transform: rotate(100deg) translateX(245px) rotate(-100deg);"
                class="absolute rounded-full"></img>
            <img src="/assets/image/desktop/turnduck.svg" style="transform: rotate(220deg) translateX(245px) rotate(-220deg);"
                class="absolute rounded-full"></img>
            <img src="/assets/image/desktop/turnduck.svg" style="transform: rotate(340deg) translateX(245px) rotate(-340deg);"
                class="absolute rounded-full"></img>
            <img src="/assets/image/desktop/turnduck.svg" style="transform: rotate(170deg) translateX(205.72px) rotate(-170deg);"
                class="absolute rounded-full"></img>
            <img src="/assets/image/desktop/turnduck.svg" style="transform: rotate(70deg) translateX(170.5px) rotate(-70deg);"
                class="absolute rounded-full"></img>
            <img src="/assets/image/desktop/turnduck.svg" style="transform: rotate(290deg) translateX(170.5px) rotate(-290deg);"
                class="absolute rounded-full"></img>

        </div>
    </div>
    <?php
    require_once("datas.php");
    require_once("mirrordata.php");
    $totalCount = count($datas) + count($mirrordatas);
    ?>

    <!-- Search bar -->
    <div class="mt-[48px] max-w-[640px]">
        <label for="pd-site-search" class="block text-[14px] font-semibold text-[#010205] mb-2">Search the list</label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-[#9B9B9C]" aria-hidden="true">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            <input
                type="text"
                id="pd-site-search"
                placeholder="Try &lsquo;spokeo&rsquo;, &lsquo;beenverified&rsquo;, &lsquo;whitepages&rsquo;&hellip;"
                aria-describedby="pd-site-count"
                class="w-full rounded-full border border-slate-300 pl-10 pr-4 py-3 text-[15px] focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/30"
            />
        </div>
        <p id="pd-site-count" class="mt-2 text-[13px] text-[#9B9B9C]">
            Showing <span id="pd-site-shown"><?= $totalCount ?></span> of <?= $totalCount ?> sites
        </p>
        <p id="pd-site-empty" class="mt-2 text-[14px] text-[#010205] hidden">
            We don&rsquo;t cover that site yet. Email <a href="mailto:hello@privacyduck.com" class="text-brand font-medium hover:underline">hello@privacyduck.com</a> and we&rsquo;ll look into adding it.
        </p>
    </div>

    <div class="mt-[64px] sm:w-[595px]" data-pd-section="people">
        <div>
            <h2 class="font-semibold text-[32px] text-[#010205] leading-[130%] tracking-[-0.03em]">People-search sites</h2>
        </div>
        <div class="mt-[32px] grid grid-cols-1 sm:grid-cols-2 gap-y-[16px]">
            <?php for ($i = 0; $i < count($datas); $i++) { ?>
                <p data-pd-site="<?= htmlspecialchars(strtolower($datas[$i])) ?>"
                   class="pd-site-row text-[20px] font-medium leading-[130%] tracking-[-0.03em] text-[#010205] <?php if ($i % 2 != 0) { ?>sm:flex sm:justify-end<?php } ?>"><?= $datas[$i] ?></p>
            <?php } ?>
        </div>
    </div>

    <!-- Mirror sites: desktop layout -->
    <div class="mt-[64px] pr-[32px] hidden xl:flex justify-between" data-pd-section="mirror-d">
        <div class="w-[595px]">
            <div>
                <h2 class="font-semibold text-[32px] text-[#010205] leading-[130%] tracking-[-0.03em]">Mirror people-search sites</h2>
            </div>
            <div class="mt-[32px] grid grid-cols-1 xl:grid-cols-2 gap-y-[16px]">
                <?php for ($i = 0; $i < count($mirrordatas); $i++) { ?>
                    <p data-pd-site="<?= htmlspecialchars(strtolower($mirrordatas[$i])) ?>"
                       class="pd-site-row text-[20px] font-medium leading-[130%] tracking-[-0.03em] text-[#010205] <?php if ($i % 2 != 0) { ?>flex justify-end<?php } ?>"><?= $mirrordatas[$i] ?></p>
                <?php } ?>
            </div>
        </div>
        <div class="w-[331px] h-[318px] bg-[#E8FCE7] border-l-[5px] border-brand rounded-[15px] shrink-0">
            <div class="px-[32px] py-[46px] gap-y-[10px]">
                <i class="fa-solid fa-circle-exclamation text-brand text-[24px]"></i>
                <h2 class="text-[16px] font-medium leading-[150%] text-[#010205] mt-2">These sites do not publish your information on their domains.
                    Instead, they redirect visitors to real people-search sites from the above list.
                    Removal of your information from real people-search sites will protect you from mirror sites as well.</h2>
            </div>
        </div>
    </div>

    <!-- Mirror sites: mobile / tablet layout (FIXED - was previously hard-coded
         loop of 34x "24counter.com" instead of the real $mirrordatas array). -->
    <div class="mt-[64px] xl:hidden" data-pd-section="mirror-m">
        <h2 class="font-semibold text-[24px] sm:text-[32px] text-[#010205] leading-[130%] tracking-[-0.03em]">Mirror people-search sites</h2>
        <div class="mt-[32px] grid grid-cols-1 sm:grid-cols-2 gap-y-[16px]">
            <?php for ($i = 0; $i < count($mirrordatas); $i++) { ?>
                <p data-pd-site="<?= htmlspecialchars(strtolower($mirrordatas[$i])) ?>"
                   class="pd-site-row text-[18px] sm:text-[20px] font-medium leading-[130%] tracking-[-0.03em] text-[#010205] <?php if ($i % 2 != 0) { ?>sm:flex sm:justify-end<?php } ?>"><?= $mirrordatas[$i] ?></p>
            <?php } ?>
        </div>
        <div class="mt-6 bg-[#E8FCE7] border-l-[5px] border-brand rounded-[15px]">
            <div class="p-[16px] flex flex-col gap-y-[10px]">
                <i class="fa-solid fa-circle-exclamation text-brand text-[24px]"></i>
                <h2 class="text-[14px] font-medium leading-[150%] text-[#010205]">These sites do not publish your information on their domains.
                    Instead, they redirect visitors to real people-search sites from the above list.
                    Removal of your information from real people-search sites will protect you from mirror sites as well.</h2>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        var input = document.getElementById('pd-site-search');
        var shownEl = document.getElementById('pd-site-shown');
        var emptyEl = document.getElementById('pd-site-empty');
        if (!input) return;

        var rows = Array.prototype.slice.call(document.querySelectorAll('.pd-site-row'));
        var sections = Array.prototype.slice.call(document.querySelectorAll('[data-pd-section]'));

        function applyFilter() {
            var q = input.value.trim().toLowerCase();
            // De-duplicate the visible count since desktop+mobile both render
            // the same mirror list (only one is visible at a time via CSS).
            var perSection = {};
            rows.forEach(function (row) {
                var name = row.getAttribute('data-pd-site') || '';
                var hit = q === '' || name.indexOf(q) !== -1;
                row.style.display = hit ? '' : 'none';
                var section = row.closest('[data-pd-section]');
                if (section) {
                    var key = section.getAttribute('data-pd-section');
                    perSection[key] = (perSection[key] || 0) + (hit ? 1 : 0);
                }
            });

            // Hide whole sections when no rows match (so the section header
            // disappears too, not just empty space).
            sections.forEach(function (section) {
                var key = section.getAttribute('data-pd-section');
                var visible = perSection[key] || 0;
                section.style.display = visible === 0 && q !== '' ? 'none' : '';
            });

            // Total = people + ONE of the mirror sections (they're duplicates).
            var people = perSection['people'] || 0;
            var mirror = perSection['mirror-d'] || perSection['mirror-m'] || 0;
            var totalShown = people + mirror;
            shownEl.textContent = totalShown;
            emptyEl.classList.toggle('hidden', !(q !== '' && totalShown === 0));
        }

        input.addEventListener('input', applyFilter);
    })();
</script>
<?php main_footer(); ?>
