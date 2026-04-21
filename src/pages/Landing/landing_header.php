<?php
/**
 * Logged-out header for redesigned long-form landing (e.g. /new) — matches Figma nav.
 */
function landing_logout_header($x = "white")
{
    $dark = ($x === "black");
    $menuId = $dark ? "landing-menu-toggle-dark" : "landing-menu-toggle-light";
    $path = trim(parse_url($_SERVER["REQUEST_URI"] ?? "/", PHP_URL_PATH), "/");
    $featHref = ($path === "new") ? "/new#features" : "/#features";
    $faqHref = ($path === "new") ? "/new#np-faq" : "/#np-faq";
    $helpDeskUrl = "https://tawk.to/chat/6813761a7c6684190de59a7c/1iq60amh0";

    $textMain = $dark ? "text-[#010205]" : "text-white";
    $textMuted = $dark ? "text-[#010205]/80" : "text-white/90";
    $logo = $dark ? "logo2.svg" : "logo.svg";
    $logInClass = $dark
        ? "text-[#010205] font-semibold text-[14px] px-3 py-2.5 hover:text-[var(--np-brand,#24A556)]"
        : "text-white font-semibold text-[14px] px-3 py-2.5 hover:opacity-90";
    $getStartedClass = "font-semibold text-[14px] bg-gradient-to-r from-[#77B248] to-[#24A556] rounded-full text-white px-6 py-2.5 shadow-[0px_4px_4px_0px_#24A5561A] hover:opacity-95 transition-opacity inline-flex items-center justify-center";
    ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .np-landing-header-nav {
            font-family: Poppins, ui-sans-serif, system-ui, sans-serif;
        }
        .np-top-overlay {
            background: transparent;
            border-bottom: 0;
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
            transition: background-color 0.25s ease, border-color 0.25s ease, backdrop-filter 0.25s ease, -webkit-backdrop-filter 0.25s ease;
        }

        /* Force fully transparent top bar before scroll */
        .np-header-wrapper-white:not(.np-header-scrolled) .np-top-overlay,
        .np-header-wrapper-black:not(.np-header-scrolled) .np-top-overlay {
            background: transparent !important;
            border-bottom-color: transparent !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
        }

        /* Past hero: frosted bar for readability */
        .np-header-wrapper-white.np-header-scrolled .np-top-overlay {
            background: rgba(0, 0, 0, 0.20);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .np-header-wrapper-black.np-header-scrolled .np-top-overlay {
            background: rgba(250, 250, 247, 0.95);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        @media (prefers-reduced-motion: reduce) {
            .np-top-overlay {
                transition: none;
            }
        }
    </style>
    <input type="checkbox" id="<?php echo $menuId; ?>" class="hidden peer" />
    <div class="np-top-overlay fixed w-full h-[96px] z-20 pointer-events-none"></div>
    <div class="py-[5px] fixed w-full h-[96px] z-20 <?php echo $textMain; ?> flex items-center">
        <div class="w-full px-5 md:px-10 lg:px-20 xl:px-[100px] flex justify-between items-center lg:hidden">
            <a href="/"><img src="/assets/image/desktop/<?php echo $dark ? "logo2.svg" : "logo.svg"; ?>" alt="PrivacyDuck" class="h-8 w-auto" /></a>
            <label for="<?php echo $menuId; ?>" class="cursor-pointer">
                <svg width="40" height="18" viewBox="0 0 40 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <line y1="1" x2="40" y2="1" stroke="var(--np-brand,#24A556)" stroke-width="2" />
                    <line y1="9" x2="40" y2="9" stroke="var(--np-brand,#24A556)" stroke-width="2" />
                    <line y1="17" x2="40" y2="17" stroke="var(--np-brand,#24A556)" stroke-width="2" />
                </svg>
            </label>
        </div>
        <div class="px-5 md:px-10 lg:px-20 xl:px-[100px] h-full w-full items-center justify-between gap-6 hidden lg:flex <?php echo $textMain; ?>">
            <a href="/" class="shrink-0 flex items-center"><img src="/assets/image/desktop/<?php echo htmlspecialchars($logo, ENT_QUOTES, "UTF-8"); ?>" alt="PrivacyDuck" class="h-8 w-auto" /></a>
            <nav class="np-landing-header-nav min-w-0 flex-1 flex justify-center px-2 xl:px-4">
                <ul class="flex flex-wrap items-center justify-center gap-x-4 gap-y-2 text-sm font-medium <?php echo $textMuted; ?>">
                    <li><a href="<?php echo htmlspecialchars($featHref, ENT_QUOTES, "UTF-8"); ?>" class="hover:text-[var(--np-brand,#24A556)] <?php echo $dark ? "" : "hover:text-white"; ?>">Features</a></li>
                    <li><a href="/business" class="font-bold underline decoration-1 underline-offset-2 hover:text-[var(--np-brand,#24A556)] <?php echo $dark ? "" : "hover:text-white"; ?>">Business</a></li>
                    <li><a href="/pricing" class="hover:text-[var(--np-brand,#24A556)] <?php echo $dark ? "" : "hover:text-white"; ?>">Pricing</a></li>
                    <li><a href="/sites-we-cover" class="hover:text-[var(--np-brand,#24A556)] <?php echo $dark ? "" : "hover:text-white"; ?>">Sites We Cover</a></li>
                    <li><a href="/personalized-service" class="hover:text-[var(--np-brand,#24A556)] <?php echo $dark ? "" : "hover:text-white"; ?>">Personalized Service</a></li>
                    <li><a href="/family" class="hover:text-[var(--np-brand,#24A556)] <?php echo $dark ? "" : "hover:text-white"; ?>">Family</a></li>
                    <li><a href="<?php echo htmlspecialchars($faqHref, ENT_QUOTES, "UTF-8"); ?>" class="hover:text-[var(--np-brand,#24A556)] <?php echo $dark ? "" : "hover:text-white"; ?>">FAQ</a></li>
                    <li><a href="<?php echo htmlspecialchars($helpDeskUrl, ENT_QUOTES, "UTF-8"); ?>" target="_blank" rel="noopener noreferrer" class="hover:text-[var(--np-brand,#24A556)] <?php echo $dark ? "" : "hover:text-white"; ?>">Help Desk</a></li>
                </ul>
            </nav>
            <div class="flex flex-row items-center gap-1 sm:gap-2 shrink-0">
                <a href="/login" class="<?php echo htmlspecialchars($logInClass, ENT_QUOTES, "UTF-8"); ?>">Log In</a>
                <a href="/new_signup" class="<?php echo htmlspecialchars($getStartedClass, ENT_QUOTES, "UTF-8"); ?>">Get Started</a>
            </div>
        </div>
    </div>

    <div class="<?php echo $textMain; ?> fixed top-0 left-0 w-full h-screen bg-white/95 backdrop-blur-xl z-30 opacity-0 invisible peer-checked:opacity-100 peer-checked:visible transition-opacity duration-300 lg:hidden">
        <div class="py-[24px] w-full h-[96px] border-b border-black/10">
            <div class="px-[24px] flex justify-between items-center">
                <a href="/"><img src="/assets/image/desktop/logo2.svg" alt="PrivacyDuck" class="h-8 w-auto" /></a>
                <label for="<?php echo $menuId; ?>" class="cursor-pointer">
                    <img src="/assets/image/mobile/hugeicons_cancel_black.svg" class="w-[24px] h-[24px]" alt="Close menu" />
                </label>
            </div>
        </div>
        <div class="flex flex-col gap-4 text-[16px] font-medium py-[24px] px-[24px] text-[#010205]">
            <a href="<?php echo htmlspecialchars($featHref, ENT_QUOTES, "UTF-8"); ?>" class="hover:text-[var(--np-brand,#24A556)]">Features</a>
            <a href="/business" class="font-bold underline hover:text-[var(--np-brand,#24A556)]">Business</a>
            <a href="/pricing" class="hover:text-[var(--np-brand,#24A556)]">Pricing</a>
            <a href="/sites-we-cover" class="hover:text-[var(--np-brand,#24A556)]">Sites We Cover</a>
            <a href="/personalized-service" class="hover:text-[var(--np-brand,#24A556)]">Personalized Service</a>
            <a href="/family" class="hover:text-[var(--np-brand,#24A556)]">Family</a>
            <a href="<?php echo htmlspecialchars($faqHref, ENT_QUOTES, "UTF-8"); ?>" class="hover:text-[var(--np-brand,#24A556)]">FAQ</a>
            <a href="<?php echo htmlspecialchars($helpDeskUrl, ENT_QUOTES, "UTF-8"); ?>" target="_blank" rel="noopener noreferrer" class="hover:text-[var(--np-brand,#24A556)]">Help Desk</a>
        </div>
        <div class="px-[24px] pb-8 flex flex-col gap-3">
            <a href="/new_signup" class="block w-full text-center font-semibold text-[14px] bg-gradient-to-r from-[#77B248] to-[#24A556] text-white rounded-full py-3 shadow-[0px_4px_4px_0px_#24A5561A]">Get Started</a>
            <a href="/login" class="block w-full text-center text-[#010205] font-semibold py-3 border-2 border-[var(--np-brand,#24A556)] rounded-full text-[var(--np-brand,#24A556)]">Log In</a>
        </div>
    </div>
    <?php
}

function landing_main_header($x = "white")
{
    $path = trim(parse_url($_SERVER["REQUEST_URI"] ?? "/", PHP_URL_PATH), "/");
    $isNewLanding = ($path === "" || $path === "new");
    if ($isNewLanding) {
        landing_logout_header($x);
        return;
    }

    if (isset($_SESSION["isAuthenticated"]) && $_SESSION["isAuthenticated"] === true) {
        main_header($x);
    } else {
        landing_logout_header($x);
    }
}
