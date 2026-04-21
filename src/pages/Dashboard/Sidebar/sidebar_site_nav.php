<?php
/**
 * Public / marketing links (aligned with landing + logged-in site header) for dashboard sidebars.
 */
$dashboardSidebarHelpDesk = "https://tawk.to/chat/6813761a7c6684190de59a7c/1iq60amh0";
$dashboardSidebarSiteLinks = [
    ["href" => "/", "label" => "Home"],
    ["href" => "/#features", "label" => "Features"],
    ["href" => "/business", "label" => "Business"],
    ["href" => "/pricing", "label" => "Pricing"],
    ["href" => "/sites-we-cover", "label" => "Sites we cover"],
    ["href" => "/personalized-service", "label" => "Personalized service"],
    ["href" => "/family", "label" => "Family (website)"],
    ["href" => "/#faq", "label" => "FAQ"],
    ["href" => $dashboardSidebarHelpDesk, "label" => "Help desk", "external" => true],
    ["href" => "/blog", "label" => "Blog"],
    ["href" => "/policy", "label" => "Privacy policy"],
    ["href" => "/insurance", "label" => "Insurance"],
    ["href" => "/restoration", "label" => "Restoration"],
];
?>
<div class="dashboard-site-nav mt-[28px] pt-[24px] border-t border-[#EEEEEE]">
    <p class="text-[11px] font-semibold uppercase tracking-[0.08em] text-[#9B9B9C] mb-[14px]">Website</p>
    <div class="flex flex-col space-y-[14px]">
        <?php foreach ($dashboardSidebarSiteLinks as $link) {
            $isExt = !empty($link["external"]);
            $cls = "text-[#4B4B4E] text-[15px] font-medium tracking-[-0.01em] hover:text-[#24A556] transition-colors";
        ?>
            <a href="<?= htmlspecialchars($link["href"], ENT_QUOTES, "UTF-8") ?>"
                class="<?= $cls ?>"
                <?php if ($isExt) { ?>target="_blank" rel="noopener noreferrer"<?php } ?>>
                <?= htmlspecialchars($link["label"], ENT_QUOTES, "UTF-8") ?>
            </a>
        <?php } ?>
    </div>
</div>
