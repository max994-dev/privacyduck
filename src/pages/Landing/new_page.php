<?php
$meta_title = "Remove Personal Information Online | PrivacyDuck";
$meta_description = "Erase your digital footprint. PrivacyDuck removes your personal info from Google, people search sites & 500+ data brokers. Try a free scan today.";
$meta_url = "https://privacyduck.com/";
$meta_keywords = "remove employee data, employee privacy protection, delete employee info from google, business data removal, executive privacy";
include_once(BASEPATH . "/src/common/meta.php");
require_once __DIR__ . "/landing_header.php";

main_head_start();
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    .np-ph { background: #e5e2da; border-radius: 28px; }
    :root { --np-brand: #77B248; --np-ink: #141414; }
    .np-brand-bg { background: var(--np-brand); }
    .np-brand-text { color: var(--np-brand); }
    .np-ink-bg { background: var(--np-ink); }
    .np-brand-border { border-color: var(--np-brand); }
    .np-green { color: var(--np-brand); }
    .np-bg-green { background-color: var(--np-brand); }
    /* Bottom CTA: deeper green left → brand (avoid washed-out lime on the left) */
    .np-bottom-cta-banner {
        background: linear-gradient(90deg, #7fb04a 0%, #72a843 18%, var(--np-brand) 48%, #5f9236 78%, #4a7529 100%);
    }
    .np-landing { font-family: Inter, system-ui, sans-serif; }
    .np-poppins { font-family: Poppins, Inter, system-ui, sans-serif; }
    .np-dm { font-family: 'DM Sans', Inter, system-ui, sans-serif; }
    .np-plan-card {
        border: 1.5px solid #e5e7eb;
        border-radius: 20px;
        background: #fff;
        transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
    }
    .np-plan-card--active {
        border-color: var(--np-brand);
        box-shadow: 0 0 0 1px color-mix(in srgb, var(--np-brand) 40%, transparent);
    }
    .np-plan-card:hover {
        transform: translateY(-1px);
    }
    .np-fight-chip {
        transition: background-color .25s ease, color .25s ease, border-color .25s ease, transform .2s ease;
    }
    .np-fight-chip:hover {
        transform: translateY(-1px);
    }
    .np-fight-chip.is-active {
        background: var(--np-brand);
        color: #fff;
        border-color: var(--np-brand);
    }
    .np-fight-chip.is-active .np-chip-dot {
        background: rgba(255,255,255,.30);
        color: #fff;
    }
    .np-fight-chip:not(.is-active) .np-chip-dot {
        background: var(--np-brand);
        color: #fff;
    }
    .np-fight-phone-img {
        transition: opacity .28s ease, transform .38s ease;
    }
    .np-fight-phone-img.is-switching {
        opacity: .15;
        transform: scale(.98) translateY(4px);
    }
    /* Orbiting ducks — radii match ring centerlines: 380px box, rings inset 0 / 28 / 56 / 82 → r ≈ 189 / 161 / 133 / 107 */
    @keyframes np-fight-orbit0 {
        from { transform: rotate(0deg) translateX(189px) rotate(0deg); }
        to { transform: rotate(360deg) translateX(189px) rotate(-360deg); }
    }
    @keyframes np-fight-orbit1 {
        from { transform: rotate(120deg) translateX(189px) rotate(-120deg); }
        to { transform: rotate(480deg) translateX(189px) rotate(-480deg); }
    }
    @keyframes np-fight-orbit2 {
        from { transform: rotate(240deg) translateX(189px) rotate(-240deg); }
        to { transform: rotate(600deg) translateX(189px) rotate(-600deg); }
    }
    @keyframes np-fight-orbit3 {
        from { transform: rotate(60deg) translateX(161px) rotate(-60deg); }
        to { transform: rotate(420deg) translateX(161px) rotate(-420deg); }
    }
    @keyframes np-fight-orbit4 {
        from { transform: rotate(200deg) translateX(133px) rotate(-200deg); }
        to { transform: rotate(560deg) translateX(133px) rotate(-560deg); }
    }
    @keyframes np-fight-orbit5 {
        from { transform: rotate(310deg) translateX(107px) rotate(-310deg); }
        to { transform: rotate(670deg) translateX(107px) rotate(-670deg); }
    }
    .np-fight-o0 { animation: np-fight-orbit0 9s linear infinite; transform-origin: center center; will-change: transform; }
    .np-fight-o1 { animation: np-fight-orbit1 8s linear infinite; transform-origin: center center; will-change: transform; }
    .np-fight-o2 { animation: np-fight-orbit2 7.6s linear infinite; transform-origin: center center; will-change: transform; }
    .np-fight-o3 { animation: np-fight-orbit3 9s linear infinite; transform-origin: center center; will-change: transform; }
    .np-fight-o4 { animation: np-fight-orbit4 8s linear infinite; transform-origin: center center; will-change: transform; }
    .np-fight-o5 { animation: np-fight-orbit5 11s linear infinite; transform-origin: center center; will-change: transform; }
    #np-faq [aria-expanded="true"] {
        background-color: #ffffff !important;
        color: #1b2b2f !important;
    }
    #np-faq .faq_title {
        color: #1b2b2f !important;
        background: transparent !important;
    }
    #np-faq #accordion-collapse {
        border-color: rgba(2, 6, 9, .14);
    }
    #np-faq h1 {
        border-color: rgba(2, 6, 9, .10);
    }
    /* Star reviews: Flickity — viewport inset so arrows don’t cover cards; dots visible below */
    .np-star-reviews-wrap {
        overflow: visible;
    }
    .np-star-reviews-carousel {
        /* Horizontal gutters: prev/next sit in padding, not over cards */
        padding-left: max(12px, 2.75rem);
        padding-right: max(12px, 2.75rem);
        padding-bottom: 0.25rem;
        position: relative;
    }
    @media (min-width: 640px) {
        .np-star-reviews-carousel {
            padding-left: 3rem;
            padding-right: 3rem;
        }
    }
    .np-star-reviews-carousel:focus {
        outline: none;
    }
    .np-star-reviews-carousel .flickity-viewport {
        overflow: hidden !important;
    }
    .np-star-reviews-carousel .flickity-prev-next-button {
        width: 38px;
        height: 38px;
        border-radius: 9999px;
        background: rgba(255, 255, 255, 0.98);
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.14);
        color: #010205;
    }
    .np-star-reviews-carousel .flickity-prev-next-button:hover {
        background: #fff;
        color: #010205;
    }
    .np-star-reviews-carousel .flickity-prev-next-button:disabled {
        opacity: 0.35;
    }
    .np-star-reviews-carousel .flickity-prev-next-button.previous {
        left: max(4px, 0.25rem);
    }
    .np-star-reviews-carousel .flickity-prev-next-button.next {
        right: max(4px, 0.25rem);
    }
    .np-star-reviews-dots-row {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
        padding: 0 max(12px, 2.75rem);
        margin-top: 0.25rem;
    }
    @media (min-width: 640px) {
        .np-star-reviews-dots-row {
            padding-left: 3rem;
            padding-right: 3rem;
        }
    }
    .np-star-dot {
        width: 8px;
        height: 8px;
        padding: 0;
        border: 0;
        border-radius: 9999px;
        background: #010205;
        opacity: 0.22;
        cursor: pointer;
        transition: opacity 0.2s ease, transform 0.2s ease, background-color 0.2s ease;
    }
    .np-star-dot:hover {
        opacity: 0.45;
    }
    .np-star-dot.is-active {
        opacity: 1;
        background: var(--np-brand);
        transform: scale(1.2);
    }
    #np-carousel-slides {
        position: relative;
        min-height: 150px;
    }
    .np-slide {
        position: absolute;
        inset: 0;
        opacity: 0;
        transform: translateX(18px);
        transition: opacity .32s ease, transform .32s ease;
        pointer-events: none;
    }
    .np-slide--active {
        position: relative;
        opacity: 1;
        transform: translateX(0);
        pointer-events: auto;
    }
    .np-carousel-bar {
        transition: background-color .24s ease, transform .24s ease;
        transform-origin: left center;
    }
    #np-carousel-prev, #np-carousel-next {
        transition: transform .2s ease, background-color .2s ease, border-color .2s ease;
    }
    #np-carousel-prev:hover, #np-carousel-next:hover {
        transform: translateY(-1px) scale(1.03);
        border-color: rgba(255, 255, 255, .85);
    }
    #np-faq [id$="-body"] {
        overflow: hidden;
        max-height: 0;
        opacity: 0;
        transition: max-height .3s ease, opacity .25s ease, padding-top .2s ease, padding-bottom .2s ease;
    }
    #np-faq button[aria-expanded="true"] .icon-plus { display: none; }
    #np-faq button[aria-expanded="true"] .icon-minus { display: inline; }
    #np-faq button[aria-expanded="false"] .icon-plus { display: inline; }
    #np-faq button[aria-expanded="false"] .icon-minus { display: none; }
    #np-faq button {
        transition: background-color .2s ease, color .2s ease;
    }
    #np-faq button:hover {
        background: rgba(119, 178, 72, .06);
    }
    #np-faq [aria-expanded="true"] .faq_title {
        color: #0f172a !important;
    }
    #np-faq [id$="-body"] > div {
        max-width: 940px;
    }
    @media (prefers-reduced-motion: reduce) {
        .np-slide,
        .np-carousel-bar,
        #np-carousel-prev,
        #np-carousel-next,
        #np-faq [id$="-body"],
        #np-faq button {
            transition: none !important;
        }
        .np-fight-o0, .np-fight-o1, .np-fight-o2, .np-fight-o3, .np-fight-o4, .np-fight-o5 {
            animation: none !important;
            will-change: auto !important;
        }
    }
    .np-typewriter-wrap {
    min-width: 9.5ch; /* enough room for "Phone Number" */
}

@media (min-width: 640px) {
    .np-typewriter-wrap {
        min-width: 12.5ch; /* enough room for "Personal Information" */
    }
}
</style>
<?php
main_head_end();
?>
<div class="np-landing overflow-x-hidden">

<div id="np-white-header" class="np-header-wrapper np-header-wrapper-white">
    <?php landing_main_header(); ?>
</div>
<div id="np-black-header" class="hidden np-header-wrapper np-header-wrapper-black">
    <?php landing_main_header("black"); ?>
</div>

<main>
    <!-- 1 Hero -->
    <section class="new-landing-section relative min-h-[min(88svh,820px)] lg:min-h-[72svh] flex flex-col lg:flex-row bg-[#1a2820] text-white pt-[104px] overflow-hidden" data-header="white">
        <!-- Mobile / tablet: full-bleed photo + read overlay -->
        <div class="absolute inset-0 z-0 lg:hidden" aria-hidden="true">
            <img
                src="/assets/image/desktop/landing/new/hero_new.jpg"
                alt=""
                class="absolute inset-0 w-full h-full object-cover"
            />
            <div class="absolute inset-0 bg-gradient-to-b from-[#1a2820]/90 via-[#1a2820]/82 to-[#1a2820]/94"></div>
        </div>
        <div class="absolute inset-0 z-0 hidden lg:block w-[100%] right-0 top-0 bottom-0">
            <img
                src="/assets/image/desktop/landing/new/hero_new.jpg"
                alt=""
                class="h-full w-full object-cover lg:rounded-l-[40px]"
                style="min-height:520px"
            />
        </div>
        <div class="relative z-10 flex-1 flex flex-col justify-center px-5 md:px-10 lg:px-20 xl:px-[100px] py-10 sm:py-12 pb-28 lg:pb-12 max-w-[960px]">
    <h1
    class="np-poppins font-semibold text-[32px] sm:text-[48px] lg:text-[56px] leading-[1.08] tracking-[-0.02em]"
    aria-label="Remove your Phone Number from 300+ data brokers."
>
    <span class="whitespace-nowrap">
        Real people removing your
        <span class="relative inline-block whitespace-nowrap np-typewriter-wrap align-baseline leading-none">
           <!-- <svg
                aria-hidden="true"
                viewBox="0 0 418 42"
                class="absolute left-0 top-2/3 h-[0.58em] w-full -translate-y-1/2"
                preserveAspectRatio="none"
                style="fill: rgb(119 178 72 / 0.35);"
            >
                <path d="M203.371.916c-26.013-2.078-76.686 1.963-124.73 9.946L67.3 12.749C35.421 18.062 18.2 21.766 6.004 25.934 1.244 27.561.828 27.778.874 28.61c.07 1.214.828 1.121 9.595-1.176 9.072-2.377 17.15-3.92 39.246-7.496C123.565 7.986 157.869 4.492 195.942 5.046c7.461.108 19.25 1.696 19.17 2.582-.107 1.183-7.874 4.31-25.75 10.366-21.992 7.45-35.43 12.534-36.701 13.884-2.173 2.308-.202 4.407 4.442 4.734 2.654.187 3.263.157 15.593-.78 35.401-2.686 57.944-3.488 88.365-3.143 46.327.526 75.721 2.23 130.788 7.584 19.787 1.924 20.814 1.98 24.557 1.332l.066-.011c1.201-.203 1.53-1.825.399-2.335-2.911-1.31-4.893-1.604-22.048-3.261-57.509-5.556-87.871-7.36-132.059-7.842-23.239-.254-33.617-.116-50.627.674-11.629.54-42.371 2.494-46.696 2.967-2.359.259 8.133-3.625 26.504-9.81 23.239-7.825 27.934-10.149 28.304-14.005.417-4.348-3.529-6-16.878-7.066Z"></path>
            </svg>-->

            <span
                id="np-typewriter"
                class="relative z-[1]"
                data-default="Phone Number"
            >Phone Number</span>
        </span>
    </span>

    <br class="block" />

    <span>
        from everywhere it appears. 
    </span>
</h1>
            <p class="np-poppins mt-5 sm:mt-6 text-white/90 text-[15px] sm:text-[17px] leading-[165%] max-w-[560px]">
               Our US based professional opt-out team is dedicated to thoroughly removing your personal details such as name, contact information, relatives, and other identifiable data from google to help protect your privacy and limit the misuse of your data online.


            </p>
            <form action="/new_signup" method="get" class="mt-8 sm:mt-10 flex flex-col sm:flex-row w-full max-w-[520px] gap-3 sm:gap-2 sm:items-stretch"
                aria-label="Sign up with your email">
                <input
                    name="email"
                    type="email"
                    required
                    autocomplete="email"
                    inputmode="email"
                    placeholder="Enter your email"
                    class="flex-1 min-w-0 rounded-full bg-white/10 border border-white/25 text-white placeholder:text-white/55 px-5 py-3.5 text-[15px] focus:outline-none focus:ring-2 focus:ring-white/40 focus:border-white/35"
                />
                <button type="submit" class="w-full sm:w-auto justify-center rounded-full np-bg-green text-white font-semibold text-[15px] px-8 py-3.5 hover:opacity-95 inline-flex items-center gap-2 border-0 cursor-pointer shrink-0">
                    Sign Up
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" class="hidden sm:block" aria-hidden="true"><path d="M5 12H19M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                </button>
            </form>
        </div>
        <div class="absolute left-0 right-0 bottom-0 h-px bg-white/20 z-10"></div>
        <p class="absolute left-0 right-0 bottom-5 sm:bottom-6 z-10 text-center text-white/80 text-[11px] leading-snug sm:text-sm px-4">
            <span class="font-semibold">PrivacyDuck.com - </span><span class="block sm:inline">The trusted leader in personal data removal since 2019.</span>
        </p>
    </section>

    <!-- 2 CTA strip -->
    <section class="new-landing-section bg-[#F5F5F0] border-t border-black/[0.06]" data-header="dark">
        <div class="max-w-[1200px] mx-auto px-5 md:px-10 py-12 md:py-16 flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="font-semibold text-[#010205] text-[26px] sm:text-[32px] lg:text-[36px] leading-[120%] tracking-[-0.02em]">Remove your data from 400+ data brokers</h2><br>
                <p class="mt-3 text-[#010205]/70 text-[15px] sm:text-[17px]">Most services cover a limited set of sources. PrivacyDuck uses real local workers to remove your data across more sources, including the ones other services miss. We cover 400+ people search sites, data brokers, and other databases in our sweep, including cusotmized deletion from google..</p>
            </div>
            <a href="/new_signup" class="inline-flex justify-center items-center rounded-full np-bg-green text-white font-semibold text-[16px] px-10 py-4 hover:opacity-95 w-full sm:w-auto shrink-0 shadow-md">Start removing now</a>
        </div>
    </section>

    <!-- 3–4 Feature rows -->
    <section class="new-landing-section bg-[#F5F5F0] px-5 md:px-10 pb-16 md:pb-24" data-header="dark" id="features">
        <div class="max-w-[1200px] mx-auto space-y-20 md:space-y-28">
            <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
                <div>
                    <h2 class="font-semibold text-[#010205] text-[26px] sm:text-[32px] lg:text-[36px] leading-[120%]">Protect Your Privacy &amp; Anonymity</h2>
                    <p class="mt-6 text-[#010205]/85 text-[15px] sm:text-[17px] leading-[175%]">
                        Keep your data safe from cyberstalkers, hackers, and unwanted tracking. We remove harmful content, secure your anonymity, and prevent employers or malicious actors from accessing your personal records.
                    </p>
                </div>
                <div class="w-[80%] max-w-[520px] mx-auto aspect-[3/2] overflow-hidden rounded-[28px] order-first lg:order-last">
                    <img
                        src="/assets/image/desktop/landing/new/img1.jpg"
                        alt=""
                        class="w-full h-full object-cover object-center"
                    />
                </div>
            </div>
            <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
                <div class="w-[80%] max-w-[520px] mx-auto aspect-[3/2] overflow-hidden rounded-[28px] order-first">
                    <img
                        src="/assets/image/desktop/landing/new/img2.jpg"
                        alt=""
                        class="w-full h-full object-cover object-center"
                    />
                </div>
                <div>
                    <h2 class="font-semibold text-[#010205] text-[26px] sm:text-[32px] lg:text-[36px] leading-[120%]">Permanent Data Removal &amp; Identity Protection</h2>
                    <p class="mt-6 text-[#010205]/85 text-[15px] sm:text-[17px] leading-[175%]">
                        We ensure your information stays off hundreds of people-finding sites, reducing the risk of identity theft. With PrivacyDuck, you gain peace of mind knowing your private data is consistently removed and protected.
                    </p>
                </div>
                
            </div>
            <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
                <div>
                    <h2 class="font-semibold text-[#010205] text-[26px] sm:text-[32px] lg:text-[36px] leading-[120%]">Erase Public &amp; Genetic Records</h2>
                    <p class="mt-6 text-[#010205]/85 text-[15px] sm:text-[17px] leading-[175%] max-w-[480px]">
                        Remove yourself from public records, criminal databases, and genetic sites like Ancestry.com and 23andMe. Safeguard your future from discrimination and unauthorized data usage.
                    </p>
                </div>
                <div class="w-[80%] max-w-[520px] mx-auto aspect-[3/2] overflow-hidden rounded-[28px]">
                    <img
                        src="/assets/image/desktop/landing/new/img3.jpg"
                        alt=""
                        class="w-full h-full object-cover object-center"
                    />
                </div>
            </div>
        </div>
    </section>

    <!-- 5 Erase Public -->
    <!-- <section class="new-landing-section bg-[#F5F5F0] px-5 md:px-10 py-16 md:py-24" data-header="dark">
        <div class="max-w-[1200px] mx-auto grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            <div>
                <h2 class="font-semibold text-[#010205] text-[26px] sm:text-[32px] lg:text-[36px] leading-[120%]">Erase Public &amp; Genetic Records</h2>
                <p class="mt-6 text-[#010205]/85 text-[15px] sm:text-[17px] leading-[175%] max-w-[480px]">
                    Remove yourself from public records, criminal databases, and genetic sites like Ancestry.com and 23andMe. Safeguard your future from discrimination and unauthorized data usage.
                </p>
            </div>
            <div class="w-[80%] max-w-[520px] mx-auto aspect-[3/2] overflow-hidden rounded-[28px]">
                <img
                    src="/assets/image/desktop/landing/new/img3.jpg"
                    alt=""
                    class="w-full h-full object-cover object-center"
                />
            </div>
        </div>
    </section> -->

    <!-- 6 Featured testimonial -->
    <section class="new-landing-section bg-white px-5 md:px-10 pb-20 md:pb-28 mt-10" data-header="dark">
        <div class="max-w-[960px] mx-auto text-center">
            <h2 class="text-[26px] sm:text-[34px] lg:text-[40px] font-bold text-[#010205] leading-[1.2]">
                Everyone is changing their life with <span class="np-green">PrivacyDuck.</span>
            </h2>
            <p class="mt-4 font-semibold text-[#010205] text-[15px] sm:text-[17px]">Removals happen within the first 30 days.</p>
        </div>
        <div class="max-w-[980px] mx-auto mt-12 rounded-[20px] sm:rounded-[24px] np-bg-green text-white p-6 sm:p-8 md:p-12 shadow-xl">
            <div id="np-carousel-slides" class="min-h-[120px] sm:min-h-[140px]">
                <p class="text-[16px] sm:text-[18px] md:text-[22px] leading-[155%] sm:leading-[160%] font-medium np-slide np-slide--active text-left" data-idx="0">
                    “I trust PrivacyDuck to protect my privacy. Their commitment to privacy is evident in their actions, and I'm grateful for their service.”

                </p>
                <p class="text-[16px] sm:text-[18px] md:text-[22px] leading-[155%] sm:leading-[160%] font-medium np-slide text-left" data-idx="1">“PrivacyDuck excels at data removal. Their attention to detail and dedication have given me greater control over my online presence.”</p>
                <p class="text-[16px] sm:text-[18px] md:text-[22px] leading-[155%] sm:leading-[160%] font-medium np-slide text-left" data-idx="2">“PrivacyDuck has empowered me to take control of my privacy. Their service has given me the tools I need to make informed decisions.”</p>
                <p class="text-[16px] sm:text-[18px] md:text-[22px] leading-[155%] sm:leading-[160%] font-medium np-slide text-left" data-idx="3">“PrivacyDuck has shown me that privacy is about standing up for your rights. Their service has been a powerful reminder of the importance of privacy.”</p>
            </div>
            <p class="mt-5 sm:mt-6 text-white/90 text-sm">Anonymous User</p>
            <div class="mt-6 sm:mt-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
                <div class="flex gap-2 order-2 sm:order-1 min-w-0 w-[50%]" id="np-carousel-bars" aria-hidden="true">
                    <span class="np-carousel-bar h-1 flex-1 rounded-full bg-white/40"></span>
                    <span class="np-carousel-bar h-1 flex-1 rounded-full bg-white"></span>
                    <span class="np-carousel-bar h-1 flex-1 rounded-full bg-white"></span>
                    <span class="np-carousel-bar h-1 flex-1 rounded-full bg-white"></span>
                </div>
                <div class="flex gap-3 shrink-0 order-1 sm:order-2 justify-end sm:justify-start">
                    <button type="button" id="np-carousel-prev" class="w-11 h-11 sm:w-10 sm:h-10 rounded-full text-lg leading-none text-white hover:bg-white/10" aria-label="Previous">‹</button>
                    <button type="button" id="np-carousel-next" class="w-11 h-11 sm:w-10 sm:h-10 rounded-full text-lg leading-none text-white hover:bg-white/10" aria-label="Next">›</button>
                </div>
            </div>
        </div>
    </section>

    <!-- 7 Horizontal star reviews (Flickity: draggable, wrap, no scrollbar — same behavior as main landing testimonials) -->
    <section class="new-landing-section bg-white px-5 md:px-10 py-16 md:py-24 pb-20 md:pb-24 overflow-x-hidden" data-header="dark">
        <div class="max-w-[1200px] mx-auto np-star-reviews-wrap max-md:-mx-5 max-md:px-5">
            <?php
            $quotes = [
                'PrivacyDuck excels at data removal. Their attention to detail and dedication have given me greater control over my online presence.',
                'PrivacyDuck has empowered me to take control of my privacy. Their service has given me the tools and knowledge I need to make informed decisions about my personal data.',
                'PrivacyDuck has shown me that privacy is not just about being cautious; it\'s about standing up for your rights. Their service has been a powerful reminder of the importance of privacy.',
                'I\'m highly impressed with PrivacyDuck. They go above and beyond to remove my data, offering a personalized approach that truly sets them apart.',
                'So glad I chose PrivacyDuck. It\'s a small price to pay for peace of mind while knowing my online exposure is continuously monitored.',
                'PrivacyDuck gave me confidence again. Their support team explained every step and the removals started much faster than I expected.',
            ];
            ?>
            <div class="np-star-reviews-carousel py-2" id="np-star-reviews-carousel" aria-label="Customer reviews">
            <?php foreach ($quotes as $idx => $q): ?>
                <article class="w-[min(85vw,300px)] sm:w-[340px] md:w-[380px] mr-4 shrink-0 px-6 md:px-8 py-6 md:py-7 rounded-2xl border border-[#E5E7EB] bg-white shadow-sm" data-review-index="<?= (int) $idx; ?>">
                    <div class="text-[#EAB308] text-lg tracking-tight">★★★★★</div>
                    <p class="mt-4 text-[#010205] text-[14px] sm:text-[15px] leading-[170%]"><?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?></p>
                    <p class="mt-4 text-sm text-[#6B7280]">Anonymous User</p>
                </article>
            <?php endforeach; ?>
            </div>
            <div class="np-star-reviews-dots-row" id="np-star-reviews-dots" role="tablist" aria-label="Review slides">
                <?php foreach (array_keys($quotes) as $di): ?>
                    <button
                        type="button"
                        class="np-star-dot<?= (int) $di === 0 ? ' is-active' : ''; ?>"
                        id="np-star-dot-<?= (int) $di; ?>"
                        data-review-dot="<?= (int) $di; ?>"
                        role="tab"
                        aria-selected="<?= (int) $di === 0 ? 'true' : 'false'; ?>"
                        aria-controls="np-star-reviews-carousel"
                        aria-label="Go to review <?= (int) $di + 1; ?>"></button>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- 8 Pricing -->
    <section class="new-landing-section bg-white px-5 md:px-10 pb-16 pt-6 md:pt-10" data-header="dark" id="np-pricing">
        <div class="max-w-[960px] mx-auto text-center mb-10">
            <span class="inline-block rounded-full np-brand-bg text-white text-sm font-semibold px-5 py-2">Pricing</span>
            <h2 class="np-poppins mt-6 font-semibold text-[#010205] text-[28px] sm:text-[34px] lg:text-[44px] leading-[1.15] tracking-[-0.02em]">The Right Price For You, Whoever You Are</h2>
            <p class="np-poppins mt-4 text-[#010205]/80 text-[15px] sm:text-[17px] leading-[155%]">Our subscription covers 300+ sites and continues to delete your data from data broker sites year after year</p>
        </div>
        <div class="max-w-[960px] mx-auto grid md:grid-cols-2 gap-6 md:gap-8" style="font-family:'DM Sans', sans-serif;">
            <article class="np-plan-card np-plan-card--active p-6 sm:p-8 md:p-10 flex flex-col cursor-pointer" data-plan-card="pro" role="button" tabindex="0" aria-pressed="true">
                <div class="np-brand-text font-bold text-[34px] sm:text-[40px] md:text-[52px] leading-none sm:leading-[1.05] flex items-center justify-center">
                    PRO
                </div>
                <p class="mt-2 text-[#141414] font-normal text-[18px] sm:text-[21px] leading-[30px] sm:leading-[38px] min-h-[90px]">
                    Get your data deleted from the internet, right away!
                </p>
                <div class="rounded-[14px] mt-5 px-1 py-2 flex items-end">
                    <span class="font-bold text-[30px] sm:text-[38px] leading-[1.2]">$299.99</span>
                    <span class="ml-2 font-normal text-[13px] sm:text-[16px] leading-[20px] pb-[4px]">/year</span>
                </div>
                <a href="/pricing" class="mt-5 inline-flex rounded-full np-brand-bg text-white font-semibold px-8 py-4 min-h-[64px] hover:opacity-95 w-full justify-center">
                    Get Started Now
                </a>
                <p class="mt-7 font-bold text-[#141414]">Plan includes:</p>
                <ul class="mt-4 space-y-3 text-[15px] text-[#141414]/90">
                    <li class="flex gap-2"><span class="np-brand-text font-bold">✓</span> 300+ Sites Opted Out</li>
                    <li class="flex gap-2"><span class="np-brand-text font-bold">✓</span> Dark Web Monitoring &amp; Privacy Concierge Support Included</li>
                    <li class="flex gap-2"><span class="np-brand-text font-bold">✓</span> Custom Support Through Our Concierge</li>
                </ul>
            </article>

            <article class="np-plan-card p-6 sm:p-8 md:p-10 flex flex-col cursor-pointer" data-plan-card="enterprise" role="button" tabindex="0" aria-pressed="false">
                <div class="np-brand-text font-bold text-[30px] sm:text-[36px] md:text-[50px] leading-none sm:leading-[1.05] flex items-center justify-center text-center">
                    ENTERPRISE
                </div>
                <p class="mt-2 text-[#141414] font-normal text-[18px] sm:text-[21px] leading-[30px] sm:leading-[38px] min-h-[90px]">
                    Dedicated support and employee / employer protection for your company.
                </p>
                <div class="rounded-[14px] mt-5 px-1 py-2 flex items-end">
                    <span class="font-bold text-[30px] sm:text-[38px] leading-[1.2]">Custom</span>
                </div>
                <a href="/business" class="mt-5 inline-flex rounded-full np-brand-bg text-white font-semibold px-8 py-4 min-h-[64px] hover:opacity-95 w-full justify-center">
                    Contact Us Now
                </a>
                <p class="mt-7 font-bold text-[#141414]">Plan includes:</p>
                <ul class="mt-4 space-y-3 text-[15px] text-[#141414]/90">
                    <li class="flex gap-2"><span class="np-brand-text font-bold">✓</span> Priority support</li>
                    <li class="flex gap-2"><span class="np-brand-text font-bold">✓</span> Unlimited Team Members</li>
                    <li class="flex gap-2"><span class="np-brand-text font-bold">✓</span> Custom Solutions</li>
                    <li class="flex gap-2"><span class="np-brand-text font-bold">✓</span> Special Enterprise Dashboard</li>
                </ul>
            </article>
        </div>
    </section>

    <!-- 9 How we do it -->
    <section class="new-landing-section bg-white px-5 md:px-10 py-16 md:py-24" data-header="dark">
        <div class="max-w-[1280px] mx-auto">
            <h2 class="np-dm font-bold text-[#010205] text-[36px] sm:text-[48px] lg:text-[56px] leading-[1.1]">How We Do It</h2>
            <p class="np-dm mt-4 text-[#010205]/75 text-[18px] sm:text-[20px] max-w-[820px]">Here's a step-by-step breakdown of how we get the job done.</p>
            <?php
            $steps = [
                ['t' => 'First 24 Hours', 'h' => 'Deleting Common Data Brokers', 'b' => 'We first remove data from common data brokers such as Acxiom.'],
                ['t' => '48 Hours', 'h' => 'Remove From People Search Sites', 'b' => 'We then remove from people search sites such as fastbackgroundcheck.com.'],
                ['t' => '72 Hours', 'h' => 'Remove Data From Genetic Databases', 'b' => 'We remove data from genetic databases such as 23andMe.com.'],
                ['t' => 'Rest of Year', 'h' => 'Sweeping Up', 'b' => 'We continue to clean up any repopulated information over time.'],
            ];
            ?>
            <div class="mt-12 grid md:grid-cols-2 xl:grid-cols-4 gap-6 xl:gap-5">
                <?php foreach ($steps as $idx => $s): ?>
                    <article class="rounded-2xl border border-[#E5E7EB] bg-white p-6 md:p-7">
                        <div class="relative flex items-center gap-2.5 pb-5">
                            <span class="w-2.5 h-2.5 rounded-full np-bg-green shrink-0"></span>
                            <span class="text-[17px] font-bold np-green leading-7"><?= htmlspecialchars($s['t'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <?php if ($idx < count($steps) - 1): ?>
                                <span class="hidden xl:block absolute left-[170px] right-[-26px] top-[5px] h-px bg-gray-900/15"></span>
                            <?php endif; ?>
                        </div>
                        <h3 class="text-[#111827] font-bold text-[22px] sm:text-[24px] md:text-[26px] leading-[1.25] md:min-h-[74px]">
                            <?= htmlspecialchars($s['h'], ENT_QUOTES, 'UTF-8'); ?>
                        </h3>
                        <p class="mt-4 text-[#6B7280] text-[16px] sm:text-[17px] md:text-[18px] leading-[1.6] md:min-h-[116px]">
                            <?= htmlspecialchars($s['b'], ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- 9.5 Digital Identity Journey (existing PD section) -->
    <section class="new-landing-section bg-white" data-header="white">
        <?php require("journey.php"); ?>
    </section>

    <!-- 10 Comparison table -->
    <section class="new-landing-section bg-white px-5 md:px-10 pb-20 mt-10" data-header="dark">
        <div class="max-w-[1200px] mx-auto overflow-x-auto">
            <?php
            $rows = [
                ['Removes Basic People Search Sites', '✓', '✓', '✓'],
                ['Basic Data Removal', '✓', '✓', '✓'],
                ['Data Brokers Removed', '300+ Sites', '180 Sites', '50 Sites Removed'],
                ['Time until full removal', '1-2 weeks', '3 Months', '6 Months'],
                ['Exclusive offers', '✓', '✕', '✕'],
                ['Removes Genealogy sites', '✓', '✕', '✕'],
                ['Mobile and web access', '✓', '✓', '✕'],
            ];
            $formatCell = function ($v, $duck = false) {
                if ($v === '✓') {
                    $cls = $duck ? 'np-brand-text' : 'text-red-500';
                    return '<span class="' . $cls . ' font-bold text-[16px]">✓</span>';
                }
                if ($v === '✕') {
                    return '<span class="text-gray-300 font-bold text-[16px]">✕</span>';
                }
                $txtCls = $duck ? 'np-green font-bold' : 'text-[#111827]';
                return '<span class="' . $txtCls . ' text-[14px]">' . htmlspecialchars($v, ENT_QUOTES, 'UTF-8') . '</span>';
            };
            ?>
            <div class="min-w-[980px]">
                <div class="grid grid-cols-[290px_1fr_1fr_1fr] gap-4 items-start">
                    <div class="pt-4 border-t border-black/10 text-[#111827] font-bold text-[17px]">Comparison</div>
                    <div class="pt-4 border-t-2 border-[var(--np-brand)]">
                        <div class="h-9 w-36 mx-auto rounded-lg mb-3 flex items-center justify-center bg-white overflow-hidden">
                            <img
                                src="/assets/image/desktop/logo4.svg"
                                alt="PrivacyDuck"
                                class="h-5 w-auto max-w-[120px] object-contain"
                                onerror="this.outerHTML='<span class=&quot;text-xs font-bold text-[#111827]&quot;>PrivacyDuck</span>';"
                            />
                        </div>
                        <div class="text-center text-xs text-[#6B7280] leading-5 px-4">Partial Automation + Professional Opt-Out Team</div>
                    </div>
                    <div class="pt-4 border-t border-black/10">
                        <div class="h-9 w-32 mx-auto rounded-lg mb-3 flex items-center justify-center bg-white font-bold text-[#111827] lowercase">incogni</div>
                        <div class="text-center text-xs text-[#6B7280] leading-5 px-4">Automated &amp; Limited Removals</div>
                    </div>
                    <div class="pt-4 border-t border-black/10">
                        <div class="h-9 w-32 mx-auto rounded-lg mb-3 flex items-center justify-center bg-white overflow-hidden">
                            <img
                                src="/assets/image/desktop/logos/deletemelogoblueregistered.png"
                                alt="DeleteMe"
                                class="h-5 w-auto max-w-[120px] object-contain"
                                onerror="this.outerHTML='<span class=&quot;text-xs font-bold text-[#111827]&quot;>DeleteMe</span>';"
                            />
                        </div>
                        <div class="text-center text-xs text-[#6B7280] leading-5 px-4">Not inclusive of all necessary privacy elements.</div>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-[290px_1fr_1fr_1fr] gap-4 items-start">
                    <div class="rounded-xl border border-black/10 bg-white overflow-hidden">
                        <?php foreach ($rows as $idx => $r): ?>
                            <div class="h-14 px-4 flex items-center text-[14px] text-[#111827] <?= $idx > 0 ? 'border-t border-[#E5E7EB]' : ''; ?>">
                                <?= htmlspecialchars($r[0], ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="rounded-xl border-2 border-[var(--np-brand)] bg-white overflow-hidden shadow-[0_0_0_1px_rgba(119,178,72,.20)]">
                        <?php foreach ($rows as $idx => $r): ?>
                            <div class="h-14 px-4 flex items-center justify-center <?= $idx > 0 ? 'border-t border-[#E5E7EB]' : ''; ?>">
                                <?= $formatCell($r[1], true); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="rounded-xl border border-black/10 bg-white overflow-hidden shadow-[0_1px_2px_rgba(0,0,0,.05)]">
                        <?php foreach ($rows as $idx => $r): ?>
                            <div class="h-14 px-4 flex items-center justify-center <?= $idx > 0 ? 'border-t border-[#E5E7EB]' : ''; ?>">
                                <?= $formatCell($r[2], false); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="rounded-xl border border-black/10 bg-white overflow-hidden shadow-[0_1px_2px_rgba(0,0,0,.05)]">
                        <?php foreach ($rows as $idx => $r): ?>
                            <div class="h-14 px-4 flex items-center justify-center <?= $idx > 0 ? 'border-t border-[#E5E7EB]' : ''; ?>">
                                <?= $formatCell($r[3], false); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 11 FAQ -->
    <section class="new-landing-section bg-white px-5 md:px-10 py-16 md:py-24" data-header="dark" id="np-faq">
        <h2 class="max-w-[900px] mx-auto text-center font-semibold text-[#010205] text-[30px] sm:text-[38px] lg:text-[46px] leading-[1.18] tracking-[-0.01em]">
            Frequently Asked Questions About Personal Data Removal
        </h2>
        <div class="max-w-[1040px] mx-auto">
            <div id="accordion-collapse" data-accordion="collapse" class="mt-10 sm:mt-12 w-full border-t border-b border-black/20 divide-y divide-black/10 rounded-2xl bg-white/90 backdrop-blur-[1px]" onclick="toggleCollapse()">
                <?php
                $faqs = [
                    ['What is PrivacyDuck\'s main focus?', 'We focus on removing your personal information from data brokers, people-search sites, and related databases so your data is harder to misuse.'],
                    ['Can I request removal from a specific site or post?', 'Yes. Contact our team with the URL or site name and we can prioritize or add custom removal workflows where supported.'],
                    ['What should I do if I don\'t see a particular site on PrivacyDuck\'s list?', 'Our coverage evolves as new brokers appear. Reach out—many removals can still be handled even if a site is not listed yet.'],
                    ['Does PrivacyDuck delete information that appears on Google?', 'We address many sources that feed Google and guide you on Google removal tools where appropriate.'],
                    ['Will my name be removed from relatives\' and neighbors\' records?', 'Removal scope depends on how data is packaged on each broker; we work to minimize indirect exposure tied to your identity.'],
                    ['Can PrivacyDuck scrub my social media accounts?', 'We focus on broker and people-search exposure; social platform settings are best tightened directly on each platform—we can advise.'],
                ];

                $i = 1;
                foreach ($faqs as $faq):
                    $q = $faq[0];
                    $a = $faq[1];
                    $expanded = ($i === 1) ? 'true' : 'false';
                    ?>
                    <h1 class="transition-colors border-t border-black/10 first:border-t-0" id="faq<?= $i; ?>-heading">
                        <button type="button"
                            class="flex items-start sm:items-center justify-between gap-4 w-full px-5 sm:px-6 py-4 sm:py-5 text-left font-semibold text-[#1b2b2f] leading-[135%] text-[16px] sm:text-[19px] md:text-[22px]"
                            data-accordion-target="#faq<?= $i; ?>-body"
                            aria-expanded="<?= $expanded; ?>"
                            aria-controls="faq<?= $i; ?>-body">
                            <span class="faq_title text-[#1b2b2f] min-w-0 flex-1 pr-1">
                                <?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                            <span class="text-xl sm:text-2xl text-[#1b2b2f] shrink-0 leading-none mt-0.5 sm:mt-0 tabular-nums w-7 text-center">
                                <span class="icon-plus">+</span>
                                <span class="icon-minus">−</span>
                            </span>
                        </button>
                    </h1>
                    <div id="faq<?= $i; ?>-body" class="hidden border-t border-t-transparent" aria-labelledby="faq<?= $i; ?>-heading">
                        <div class="py-2 pb-5 sm:pb-6 px-5 sm:px-6 text-[#5B6470] text-[14px] sm:text-[15px] leading-[172%]">
                            <?= htmlspecialchars($a, ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    </div>
                    <?php
                    $i++;
                endforeach;
                ?>
            </div>
        </div>
    </section>

    <!-- 12 PrivacyDuck Fights -->
    <section class="new-landing-section bg-[#F8F8F8] px-5 md:px-10 py-16 md:py-24" data-header="dark">
        <h2 class="text-center font-bold text-[28px] sm:text-[36px] md:text-[42px] text-[#010205]">
            <span class="text-[#010205]">Privacy</span><span class="np-green">Duck</span><span class="text-[#010205]"> Fights for You</span>
        </h2>
    </section>

    <!-- 13 AI + Human -->
    <section class="new-landing-section bg-white px-5 md:px-10 py-16 md:py-28 overflow-x-visible" data-header="dark">
        <div class="max-w-[1200px] mx-auto grid lg:grid-cols-12 gap-12 items-center lg:overflow-visible">
            <div class="lg:col-span-3 flex flex-col gap-3" id="np-fight-chips" role="tablist" aria-label="Privacy risks">
                <?php
                $tags = [
                    ['Protect Privacy, Safety & Anonymity', true, 'fa-solid fa-shield-halved'],
                    ['Different Types of Sources', false, 'fa-solid fa-server'],
                    ['Your Privacy Concierge', false, 'fa-solid fa-user-tie'],
                    ['Constant Monitoring for Reappearances', false, 'fa-solid fa-eye'],
                    ['Real-Time Privacy Dashboard', false, 'fa-solid fa-chart-line'],
                    ['Face & Photo Removal', false, 'fa-solid fa-image-portrait'],
                ];
                foreach ($tags as $idx => [$label, $on, $iconClass]): ?>
                    <button
                        type="button"
                        class="np-fight-chip rounded-full px-4 py-3 text-sm font-semibold flex items-center gap-2 border border-[var(--np-brand)] <?= $on ? 'is-active' : 'bg-[#E8F5E9] text-[#1B5E20]'; ?>"
                        data-fight-chip="<?= $idx; ?>"
                        aria-selected="<?= $on ? 'true' : 'false'; ?>"
                        role="tab">
                        <span class="np-chip-dot w-5 h-5 shrink-0 rounded np-ph inline-flex items-center justify-center" aria-hidden="true">
                            <i class="<?= htmlspecialchars($iconClass, ENT_QUOTES, 'UTF-8'); ?> text-[11px]"></i>
                        </span>
                        <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                <?php endforeach; ?>
            </div>
            <div class="lg:col-span-4 flex justify-center overflow-visible min-w-0 z-0">
                <div class="relative flex items-center justify-center w-full min-h-[560px] max-w-[480px] mx-auto overflow-visible">
                    <!-- Concentric rings + circling ducks (wrapper carries transform; avoids img + Tailwind conflicts) -->
                    <div class="np-fight-orbit-layer absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-[min(90vw,380px)] h-[min(90vw,380px)] max-w-[380px] max-h-[380px] aspect-square pointer-events-none hidden sm:block overflow-visible" aria-hidden="true">
                        <div class="absolute inset-0 rounded-full border-2 border-[var(--np-brand)] border-opacity-50 opacity-50"></div>
                        <div class="absolute inset-[28px] rounded-full border-2 border-[var(--np-brand)] border-opacity-50 opacity-50"></div>
                        <div class="absolute inset-[56px] rounded-full border-2 border-[var(--np-brand)] border-opacity-50 opacity-50"></div>
                        <div class="absolute inset-[82px] rounded-full border-2 border-[var(--np-brand)] border-opacity-50 opacity-50"></div>
                        <div class="absolute left-1/2 top-1/2 w-0 h-0" style="transform: translate(-50%, -50%);">
                            <div class="np-fight-o0 absolute left-0 top-0 -ml-[18px] -mt-[18px] w-9 h-9">
                                <img src="/assets/image/desktop/turnduck.svg" alt="" class="block w-full h-full rounded-full opacity-50 pointer-events-none" width="36" height="36" />
                            </div>
                        </div>
                        <div class="absolute left-1/2 top-1/2 w-0 h-0" style="transform: translate(-50%, -50%);">
                            <div class="np-fight-o1 absolute left-0 top-0 -ml-[18px] -mt-[18px] w-9 h-9">
                                <img src="/assets/image/desktop/turnduck.svg" alt="" class="block w-full h-full rounded-full opacity-50 pointer-events-none" width="36" height="36" />
                            </div>
                        </div>
                        <div class="absolute left-1/2 top-1/2 w-0 h-0" style="transform: translate(-50%, -50%);">
                            <div class="np-fight-o2 absolute left-0 top-0 -ml-[18px] -mt-[18px] w-9 h-9">
                                <img src="/assets/image/desktop/turnduck.svg" alt="" class="block w-full h-full rounded-full opacity-50 pointer-events-none" width="36" height="36" />
                            </div>
                        </div>
                        <div class="absolute left-1/2 top-1/2 w-0 h-0" style="transform: translate(-50%, -50%);">
                            <div class="np-fight-o3 absolute left-0 top-0 -ml-[18px] -mt-[18px] w-9 h-9">
                                <img src="/assets/image/desktop/turnduck.svg" alt="" class="block w-full h-full rounded-full opacity-50 pointer-events-none" width="36" height="36" />
                            </div>
                        </div>
                        <div class="absolute left-1/2 top-1/2 w-0 h-0" style="transform: translate(-50%, -50%);">
                            <div class="np-fight-o4 absolute left-0 top-0 -ml-[18px] -mt-[18px] w-9 h-9">
                                <img src="/assets/image/desktop/turnduck.svg" alt="" class="block w-full h-full rounded-full opacity-50 pointer-events-none" width="36" height="36" />
                            </div>
                        </div>
                        <div class="absolute left-1/2 top-1/2 w-0 h-0" style="transform: translate(-50%, -50%);">
                            <div class="np-fight-o5 absolute left-0 top-0 -ml-[18px] -mt-[18px] w-9 h-9">
                                <img src="/assets/image/desktop/turnduck.svg" alt="" class="block w-full h-full rounded-full opacity-50 pointer-events-none" width="36" height="36" />
                            </div>
                        </div>
                    </div>
                    <div class="relative z-10 w-[260px] h-[520px] shrink-0 rounded-[36px] overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.08)] bg-transparent">
                        <img
                            src="/assets/image/desktop/landing/new/mobile%20app.png"
                            alt=""
                            id="np-fight-phone"
                            class="np-fight-phone-img absolute inset-0 w-full h-full object-cover object-top"
                        />
                    </div>
                </div>
            </div>
            <div class="lg:col-span-5">
                <h2 class="font-bold text-[#010205] text-[24px] sm:text-[28px] lg:text-[32px] leading-tight">How PrivacyDuck Goes Further</h2>
                <p class="mt-4 font-semibold text-[#010205] leading-relaxed">Real people, broader removals, and continuous monitoring designed to reduce your exposure online.</p>
                <p id="np-fight-copy" class="mt-6 text-[#6B7280] text-[16px] leading-[1.8]"></p>
                <ul id="np-fight-bullets" class="mt-6 space-y-4 text-[#6B7280] text-[15px] leading-relaxed">
                    <li class="flex gap-3"><span class="w-2 h-2 rounded-full np-bg-green shrink-0 mt-2"></span><span>Removes information from 3X more sources than competitors</span></li>
                    <li class="flex gap-3"><span class="w-2 h-2 rounded-full np-bg-green shrink-0 mt-2"></span><span>Combines AI efficiency with human verification for high removal success</span></li>
                    <li class="flex gap-3"><span class="w-2 h-2 rounded-full np-bg-green shrink-0 mt-2"></span><span>Prevents new listings with continuous monitoring</span></li>
                    <li class="flex gap-3"><span class="w-2 h-2 rounded-full np-bg-green shrink-0 mt-2"></span><span>Provides real-time status updates on every removal</span></li>
                </ul>
            </div>
        </div>
    </section>

    <!-- 14 Stats -->
    <section class="new-landing-section bg-white px-5 md:px-10 pb-20" data-header="dark">
        <div class="max-w-[1200px] mx-auto">
            <h2 class="font-bold text-[#010205] text-[28px] sm:text-[34px] lg:text-[40px] leading-tight max-w-[800px]">
                We work with our customers to ensure data removals are completed.
            </h2>
            <p class="mt-4 text-[#010205]/75 text-[15px] sm:text-[16px] max-w-[840px] leading-relaxed">
                We work with a variety of customers from around the world, including companies, business leaders, and other outstanding individuals.
            </p>
            <div class="mt-12 grid md:grid-cols-3 gap-6 md:items-stretch">
                <?php
                $cards = [
                    ['300+ Sites Checked', 'Sites checked for your information', 'PrivacyDuck checks and removes your data on 300+ websites'],
                    ['30,000+ Profiles Removed', "We're proud to have removed over 30,000 website profiles.", 'Our professionals have removed personal information on 30,000+ websites.'],
                    ['10,000+ customers', 'We have served 10,000+ customers', 'We have catered to the needs of over 10,000 satisfied customers with comprehensive solutions.'],
                ];
                foreach ($cards as $c): ?>
                    <div class="flex flex-col rounded-2xl bg-[#F6F6F6] p-8 min-h-[280px] md:min-h-[360px] h-full">
                        <div class="shrink-0">
                            <span class="inline-flex text-[#010205] mb-4" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 36 36" fill="none" aria-hidden="true">
                                    <g stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 3.5v3M8.2 7.8l2.5 2M27.8 7.8l-2.5 2M4.5 15.5h3M28.3 15.5h3.2M6.2 20.8l2.6-1M29.8 20.8l-2.6-1"/>
                                        <path d="M11.5 17.5 Q18 7 24.5 17.5 L25.2 25.2 H10.8 L11.5 17.5"/>
                                        <path d="M9.25 25.2h17.5v6.75H9.25z"/>
                                        <path d="M13.5 25.2v-3.5M22.5 25.2v-3.5"/>
                                    </g>
                                </svg>
                            </span>
                            <h3 class="font-bold text-xl text-[#010205]"><?= htmlspecialchars($c[0], ENT_QUOTES, 'UTF-8'); ?></h3>
                        </div>
                        <div class="flex-1 min-h-6" aria-hidden="true"></div>
                        <div class="shrink-0">
                            <p class="font-medium text-[#010205]/80 text-sm"><?= htmlspecialchars($c[1], ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="mt-4 text-[#6B7280] text-sm leading-relaxed"><?= htmlspecialchars($c[2], ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- 15 Bottom CTA: gradient + text; dashboard PNG includes dark bezel — clip-path trims it -->
    <section class="new-landing-section px-5 md:px-10 pb-8 md:pb-10" data-header="dark">
        <div class="max-w-[1360px] mx-auto rounded-[28px] sm:rounded-[32px] overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.12)] np-bottom-cta-banner text-white">
            <div class="flex flex-col lg:flex-row lg:items-stretch min-h-[240px] sm:min-h-[260px] lg:min-h-[280px] xl:min-h-[300px]">
                <div class="flex flex-col justify-center px-8 py-8 sm:px-9 sm:py-9 md:pl-10 md:pr-5 lg:w-[52%] xl:w-[54%] shrink-0">
                    <h2 class="font-bold text-white text-[1.45rem] sm:text-[1.7rem] lg:text-[2rem] xl:text-[2.125rem] leading-[1.15] tracking-tight">
                        Keep you and your family safe<br />Start using PrivacyDuck today
                    </h2>
                    <p class="mt-4 md:mt-5 text-white text-[14px] sm:text-[15px] font-normal leading-relaxed max-w-[30rem]">
                        PrivacyDuck is a great opportunity to ensure that your privacy is kept under guard, forever and always.
                    </p>
                    <div class="mt-6 md:mt-7 flex flex-wrap items-center gap-2.5 sm:gap-3">
                        <a href="/new_signup" class="inline-flex items-center justify-center rounded-full bg-white text-[#010205] font-bold text-[14px] px-8 py-2.5 min-h-[42px] hover:bg-white/95 transition-colors">
                            Get Started
                        </a>
                        <a href="mailto:hello@privacyduck.com" class="inline-flex items-center justify-center rounded-full border border-white border-solid bg-transparent text-white font-medium text-[14px] px-9 py-2.5 min-h-[42px] hover:bg-white/10 transition-colors">
                            Contact Our Team
                        </a>
                    </div>
                </div>
                <!-- Dashboard: full width of right column to banner edge — wider viewport shows more of image (no scale); clip-path trims PNG bezel -->
                <div class="relative flex-1 min-h-[220px] sm:min-h-[240px] lg:min-h-[300px] xl:min-h-[320px] min-w-0 overflow-hidden">
                    <div
                        class="relative z-[1] w-[min(100%,380px)] translate-x-[5%] sm:w-[min(100%,420px)] sm:translate-x-[8%]
                        lg:absolute lg:top-[15%] lg:bottom-0 lg:right-0 lg:left-0 lg:w-full lg:max-w-none lg:mx-0 lg:translate-x-0">
                        <img
                            src="/assets/image/desktop/landing/ultimate/ultimate1.png"
                            alt="PrivacyDuck dashboard preview"
                            width="2816"
                            height="1570"
                            class="block h-full w-full object-cover object-left object-top select-none pointer-events-none"
                            decoding="async"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/src/pages/Landing/inbound.php'; ?>
    <div class="new-landing-section landing-section" data-header="dark">
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/src/pages/Landing/footer_main_content.php'; ?>
    </div>
</main>
</div>

<script>
(function() {
    var starReviewsEl = document.querySelector('.np-star-reviews-carousel');
    if (starReviewsEl && typeof Flickity !== 'undefined') {
        var flktyReviews = new Flickity(starReviewsEl, {
            wrapAround: true,
            freeScroll: false,
            groupCells: 1,
            pageDots: false,
            prevNextButtons: true,
            cellAlign: 'left',
            contain: false,
            dragThreshold: 10
        });
        var reviewDots = document.querySelectorAll('#np-star-reviews-dots [data-review-dot]');
        function syncReviewDots() {
            var el = flktyReviews.selectedElement;
            var i = 0;
            if (el && el.getAttribute) {
                var raw = el.getAttribute('data-review-index');
                if (raw != null && raw !== '') {
                    var parsed = parseInt(raw, 10);
                    if (!isNaN(parsed)) i = parsed;
                }
            }
            reviewDots.forEach(function(btn, j) {
                var on = j === i;
                btn.classList.toggle('is-active', on);
                btn.setAttribute('aria-selected', on ? 'true' : 'false');
            });
        }
        reviewDots.forEach(function(btn) {
            btn.addEventListener('click', function() {
                var idx = parseInt(btn.getAttribute('data-review-dot'), 10);
                if (!isNaN(idx)) flktyReviews.select(idx);
            });
        });
        flktyReviews.on('select', syncReviewDots);
        syncReviewDots();
    }
    var slides = document.querySelectorAll('.np-slide');
    var bars = document.querySelectorAll('#np-carousel-bars span');
    var i = 0;
    function show(n) {
        i = (n + slides.length) % slides.length;
        slides.forEach(function(s, j) {
            s.classList.toggle('np-slide--active', j === i);
        });
        bars.forEach(function(b, j) {
            b.classList.toggle('bg-white/40', j === i);
            b.classList.toggle('bg-white', j !== i);
            b.style.transform = j === i ? 'scaleX(1)' : 'scaleX(.95)';
        });
    }
    var prev = document.getElementById('np-carousel-prev');
    var next = document.getElementById('np-carousel-next');
    if (prev) prev.addEventListener('click', function() { show(i - 1); });
    if (next) next.addEventListener('click', function() { show(i + 1); });
    show(0);

    var faqButtons = Array.prototype.slice.call(document.querySelectorAll('#np-faq [data-accordion-target]'));
    function syncAccordionPanel(btn) {
        var panelId = btn.getAttribute('aria-controls');
        var panel = panelId ? document.getElementById(panelId) : null;
        if (!panel) return;
        var expanded = btn.getAttribute('aria-expanded') === 'true';
        panel.classList.remove('hidden');
        if (expanded) {
            panel.style.display = 'block';
            panel.style.maxHeight = panel.scrollHeight + 'px';
            panel.style.opacity = '1';
        } else {
            panel.style.maxHeight = panel.scrollHeight + 'px';
            requestAnimationFrame(function() {
                panel.style.maxHeight = '0px';
                panel.style.opacity = '0';
            });
            setTimeout(function() {
                if (btn.getAttribute('aria-expanded') !== 'true') {
                    panel.style.display = 'none';
                }
            }, 320);
        }
    }
    function syncAllAccordionPanels() {
        faqButtons.forEach(syncAccordionPanel);
    }
    faqButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            setTimeout(syncAllAccordionPanels, 0);
        });
    });
    window.addEventListener('load', syncAllAccordionPanels);
    window.addEventListener('resize', function() {
        faqButtons.forEach(function(btn) {
            if (btn.getAttribute('aria-expanded') === 'true') {
                var panelId = btn.getAttribute('aria-controls');
                var panel = panelId ? document.getElementById(panelId) : null;
                if (panel) panel.style.maxHeight = panel.scrollHeight + 'px';
            }
        });
    });

    var planCards = Array.prototype.slice.call(document.querySelectorAll('[data-plan-card]'));
    function setActivePlan(card) {
        planCards.forEach(function(c) {
            var active = c === card;
            c.classList.toggle('np-plan-card--active', active);
            c.setAttribute('aria-pressed', active ? 'true' : 'false');
        });
    }
    planCards.forEach(function(card) {
        card.addEventListener('click', function(e) {
            if (e.target && e.target.closest('a')) return;
            setActivePlan(card);
        });
        card.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                setActivePlan(card);
            }
        });
    });

    var wh = document.getElementById('np-white-header');
    var bh = document.getElementById('np-black-header');
    var secs = document.querySelectorAll('.new-landing-section');
    function upd() {
        var y = window.scrollY;
        var cur = null;
        secs.forEach(function(s) {
            if (y >= s.offsetTop - 40) cur = s;
        });
        var t = cur && cur.getAttribute('data-header');
        if (t === 'dark') {
            wh.classList.add('hidden');
            bh.classList.remove('hidden');
        } else {
            bh.classList.add('hidden');
            wh.classList.remove('hidden');
        }
    }
    window.addEventListener('scroll', upd);
    window.addEventListener('load', upd);

    var heroSection = secs.length ? secs[0] : null;
    function updHeaderBg() {
        var y = window.scrollY || 0;
        var pastHero = false;
        if (heroSection) {
            pastHero = y >= heroSection.offsetTop + heroSection.offsetHeight;
        }
        if (wh) wh.classList.toggle('np-header-scrolled', pastHero);
        if (bh) bh.classList.toggle('np-header-scrolled', pastHero);
    }
    window.addEventListener('scroll', updHeaderBg);
    window.addEventListener('load', updHeaderBg);
    window.addEventListener('resize', updHeaderBg);
    updHeaderBg();

    var fightPhone = document.getElementById('np-fight-phone');
    var fightChipNodes = Array.prototype.slice.call(document.querySelectorAll('[data-fight-chip]'));
    var fightBullets = document.getElementById('np-fight-bullets');
    var fightCopy = document.getElementById('np-fight-copy');
    var fightTimer = null;
    var fightSwapToken = 0;
    var fightIndex = 0;
    var fightData = [
        {
            image: '/assets/image/desktop/1.png',
            copy: 'Public exposure can lead to spam, scams, junk mail, doxxing, cyberstalking, hacking risks, and even threats to your physical safety. PrivacyDuck helps reduce the information that makes you easier to find, contact, profile, or target.',
            bullets: []
        },
        {
            image: '/assets/image/desktop/2.png',
            copy: 'PrivacyDuck works to remove your information from public records, criminal records, people-search sites, data brokers, and other sources where your personal information appears online.',
            bullets: []
        },
        {
            image: '/assets/image/desktop/3.png',
            copy: 'PrivacyDuck gives you a real person at your service. Your privacy concierge helps manage requests, answer questions, and handle the removals that require human follow-up.',
            bullets: []
        },
        {
            image: '/assets/image/desktop/4.png',
            copy: 'Data reappears. When it does, we go after it again. PrivacyDuck continuously monitors for renewed exposure so listings, records, and profiles can be flagged and acted on quickly.',
            bullets: []
        },
        {
            image: '/assets/image/desktop/5.png',
            copy: 'Know exactly how exposed you are, anytime. Your personal dashboard shows your privacy risk score and removal progress, giving you a simple way to track what has been found, what is being worked on, and how your exposure changes as PrivacyDuck works to remove your personal information online.',
            bullets: []
        },
        {
            image: '/assets/image/desktop/6.png',
            copy: 'PrivacyDuck helps identify where your face and photos appear online and works to remove them from face-search databases and other sources exposing them. Ideal for unwanted photos, copied profile images, and visual exposure that standard removals miss.',
            bullets: []
        }
    ];

    function renderFight(index) {
        if (!fightChipNodes.length || !fightPhone || !fightBullets || !fightCopy) return;
        fightIndex = (index + fightData.length) % fightData.length;
        fightChipNodes.forEach(function(node, idx) {
            var active = idx === fightIndex;
            node.classList.toggle('is-active', active);
            if (!active) node.classList.add('bg-[#E8F5E9]', 'text-[#1B5E20]');
            else node.classList.remove('bg-[#E8F5E9]', 'text-[#1B5E20]');
            node.setAttribute('aria-selected', active ? 'true' : 'false');
        });
        var data = fightData[fightIndex];
        fightSwapToken += 1;
        var token = fightSwapToken;
        fightPhone.classList.add('is-switching');
        fightPhone.src = data.image;
        fightCopy.textContent = data.copy;
        fightBullets.innerHTML = data.bullets.map(function(text) {
            return '<li class="flex gap-3"><span class="w-2 h-2 rounded-full np-bg-green shrink-0 mt-2"></span><span>' + text + '</span></li>';
        }).join('');
        window.requestAnimationFrame(function() {
            if (token !== fightSwapToken) return;
            fightPhone.classList.remove('is-switching');
        });
    }

    function startFightAutoRotate() {
        stopFightAutoRotate();
        fightTimer = window.setInterval(function() {
            renderFight(fightIndex + 1);
        }, 3200);
    }
    function stopFightAutoRotate() {
        if (fightTimer) window.clearInterval(fightTimer);
        fightTimer = null;
    }

    fightChipNodes.forEach(function(node) {
        node.addEventListener('click', function() {
            var idx = Number(node.getAttribute('data-fight-chip') || 0);
            renderFight(idx);
            startFightAutoRotate();
        });
    });
    if (fightPhone) {
        fightPhone.addEventListener('mouseenter', stopFightAutoRotate);
        fightPhone.addEventListener('mouseleave', startFightAutoRotate);
    }
    if (fightChipNodes.length) {
        fightData.forEach(function(item) {
            var img = new Image();
            img.src = item.image;
        });
        renderFight(0);
        startFightAutoRotate();
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) stopFightAutoRotate();
            else startFightAutoRotate();
        });
    }
})();

(function () {
    var typewriter = document.getElementById('np-typewriter');
    if (!typewriter) return;

    var phrases = ['Phone Number', 'Address', 'Name', 'Personal Information'];
    var index = 0;
    var charIndex = phrases[0].length;
    var isDeleting = false;
    var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (prefersReducedMotion) {
        return; // leave the SEO-friendly default text as-is
    }

    function tick() {
        var currentPhrase = phrases[index];

        if (!isDeleting && charIndex < currentPhrase.length) {
            charIndex++;
            typewriter.textContent = currentPhrase.substring(0, charIndex);
            setTimeout(tick, 110);
            return;
        }

        if (!isDeleting && charIndex === currentPhrase.length) {
            isDeleting = true;
            setTimeout(tick, 1400);
            return;
        }

        if (isDeleting && charIndex > 0) {
            charIndex--;
            typewriter.textContent = currentPhrase.substring(0, charIndex);
            setTimeout(tick, 55);
            return;
        }

        isDeleting = false;
        index = (index + 1) % phrases.length;
        currentPhrase = phrases[index];
        charIndex = 0;
        setTimeout(tick, 220);
    }

    // Keep the initial "Phone Number" in the raw HTML for SEO / no-JS users,
    // then start animating after first paint.
    setTimeout(tick, 1200);
})();

</script>

<?php no_footer(); ?>
