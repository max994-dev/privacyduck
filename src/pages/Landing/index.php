<?php
$meta_title = "Remove Personal Information Online | PrivacyDuck";
$meta_description = "Protect your family and business online with PrivacyDuck. Remove personal data from Google, people search sites & 500+ brokers. Start free scan now.";
$meta_url = "https://privacyduck.com/";
$meta_keywords = "remove employee data, employee privacy protection, delete employee info from google, business data removal, executive privacy";
$json_ld = [
    "@context" => "https://schema.org",
    "@graph" => [
        [
            "@type" => "Organization",
            "@id" => "https://privacyduck.com/#organization",
            "name" => "PrivacyDuck",
            "url" => "https://privacyduck.com",
            "logo" => [
                "@type" => "ImageObject",
                "url" => "https://privacyduck.com/assets/image/desktop/logo.svg"
            ],
            "description" => "US-based personal data removal service that removes your information from 400+ data brokers, people search sites, and Google. Protecting privacy since 2019.",
            "foundingDate" => "2019",
            "areaServed" => "US",
            "sameAs" => ["https://x.com/antisystemduck"],
            "contactPoint" => [
                "@type" => "ContactPoint",
                "contactType" => "customer support",
                "url" => "https://tawk.to/chat/6813761a7c6684190de59a7c/1iq60amh0"
            ]
        ],
        [
            "@type" => "WebSite",
            "@id" => "https://privacyduck.com/#website",
            "url" => "https://privacyduck.com",
            "name" => "PrivacyDuck",
            "publisher" => ["@id" => "https://privacyduck.com/#organization"]
        ]
    ]
];
include_once(BASEPATH . "/src/common/meta.php");
require_once __DIR__ . "/landing_header.php";
main_head_start();
?>
<link rel="preload" as="image" href="/assets/image/desktop/background.webp">
<link rel="preload" as="image" href="/assets/image/mobile/background.webp">

<link href="/assets/css/landing.css" rel="stylesheet">
<link href="/assets/css/landingMobileAnimation.css" rel="stylesheet">
<?php /* DM Sans already loaded by main_head_start() consolidated font request */ ?>
<style>
    .call-button {
        position: fixed;
        bottom: 120px;
        /* distance from bottom */
        right: 30px;
        /* distance from right */
        background-color: rgb(255, 255, 255);
        /* green call button */
        color: white;
        font-size: 20px;
        /* icon size */
        padding: 12px;
        border-radius: 50%;
        /* makes it circular */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        text-decoration: none;
        z-index: 1000;
        /* stays above all content */
        transition: background-color 0.2s ease;
    }

    .call-button:hover {
        background-color: #218838;
        /* darker green on hover */
    }

    @media (min-width: 768px) {
        .call-button {
            font-size: 28px;
            padding: 16px;
            bottom: 200px;
            right: 30px;
        }
    }
    :root { --np-brand: #77B248; }
    .np-bg-green { background-color: var(--np-brand); }
</style>
<?php
main_head_end();
?>
<!-- Floating Phone Call Button -->
<!-- <a href="tel:+17754433727" class="call-button" aria-label="Call Us">
    📞
</a> -->

<div id="white-header">
    <?php
    landing_main_header();
    ?>
</div>
<div id="black-header" class="hidden">
    <?php
    landing_main_header("black");
    ?>
</div>
<?php
// main_splash();
?>
<div class="landing-section relative min-h-[min(88svh,820px)] lg:min-h-[72svh] flex flex-col lg:flex-row bg-[#1a2820] text-white pt-[104px] overflow-hidden" data-header="white">
    <div class="absolute inset-0 z-0 lg:hidden" aria-hidden="true">
        <img src="/assets/image/desktop/landing/new/hero_new.jpg" alt="" class="absolute inset-0 w-full h-full object-cover" />
        <div class="absolute inset-0 bg-gradient-to-b from-[#1a2820]/90 via-[#1a2820]/82 to-[#1a2820]/94"></div>
    </div>
    <div class="absolute inset-0 z-0 hidden lg:block w-[100%] right-0 top-0 bottom-0">
        <img src="/assets/image/desktop/landing/new/hero_new.jpg" alt="" class="h-full w-full object-cover lg:rounded-l-[40px]" style="min-height:520px" />
    </div>
    <div class="relative z-10 flex-1 flex flex-col justify-center px-5 md:px-10 lg:px-20 xl:px-[100px] py-10 sm:py-12 pb-28 lg:pb-12 max-w-[960px]">
        <h1 class="font-semibold text-[32px] sm:text-[48px] lg:text-[56px] leading-[1.08] tracking-[-0.02em]">
            Real people removing your phone number from everywhere it appears.
        </h1>
        <p class="mt-5 sm:mt-6 text-white/90 text-[15px] sm:text-[17px] leading-[165%] max-w-[560px]">
            Our US based professional opt-out team is dedicated to thoroughly removing your details such as name, contact information, relatives, and other identifiable data from google to help protect your privacy and limit the misuse of your data online.
        </p>
        <form action="/new_signup" method="get" class="mt-8 sm:mt-10 flex flex-col sm:flex-row w-full max-w-[520px] gap-3 sm:gap-2 sm:items-stretch" aria-label="Sign up with your email">
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
</div>
<div class="flex flex-col text-[#010205] bg-[#FAFAFA]">
    <div class="landing-section" data-header="dark">
        <section class="bg-[#F5F5F0] border-t border-black/[0.06]">
            <div class="max-w-[1200px] mx-auto px-5 md:px-10 py-12 md:py-16 flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="font-semibold text-[#010205] text-[26px] sm:text-[32px] lg:text-[36px] leading-[120%] tracking-[-0.02em]">
                        Remove your data from 400+ data brokers
                    </h2>
                    <p class="mt-3 text-[#010205]/70 text-[15px] sm:text-[17px]">
                        Most services cover a limited set of sources. PrivacyDuck uses real local workers to remove your data across more sources, including the ones other services miss. We cover 400+ people search sites, data brokers, and other databases in our sweep, including customized deletion from google.
                    </p>
                </div>
                <a href="/new_signup" class="inline-flex justify-center items-center rounded-full np-bg-green text-white font-semibold text-[16px] px-10 py-4 hover:opacity-95 w-full sm:w-auto shrink-0 shadow-md">
                    Start removing now
                </a>
            </div>
        </section>
    </div>
    <div class="landing-section" data-header="white">
        <section class="bg-white px-5 md:px-10 py-16 md:py-20" id="why-privacyduck">
            <div class="max-w-[960px] mx-auto">
                <h2 class="font-semibold text-[#010205] text-[26px] sm:text-[32px] lg:text-[40px] leading-[1.2] tracking-[-0.02em]">
                    Real people working to keep you, your family, and your team off data broker sites
                </h2>
                <div class="mt-6 space-y-5 text-[#010205]/85 text-[15px] sm:text-[17px] leading-[170%]">
                    <p>
                        Look up your own name on Google and you'll see what we mean. Data brokers have your phone number, your address, your relatives - and they sell it to anyone who asks. That's how stalkers find people. It's how phishing emails get scary specific. It's how identity theft starts.
                    </p>
                    <p>
                        PrivacyDuck is real people doing the slow, tedious work of getting your data taken down. We don't sell you software and walk away. Our US team files the opt-outs by hand, week after week, and we keep doing it because brokers keep re-uploading your info. If you want to protect your family online, this is what actually works.
                    </p>
                    <p>
                        Companies use us for employee data removal in the USA - cleaning up the public information that fuels spear-phishing campaigns. Founders, board members, and other high-visibility staff use our executive privacy service in the USA for ongoing monitoring.
                    </p>
                </div>
            </div>
        </section>
    </div>
    <!-- Features: 3 alternating image+text rows explaining what PrivacyDuck does.
         Ported from /new because the original landing didn't clearly state the
         service. This block carries id="features" (was on ultimate.php; moved
         here because this section is the canonical "Features"). -->
    <div class="landing-section bg-[#F5F5F0]" data-header="dark">
        <section class="px-5 md:px-10 py-16 md:py-24 scroll-mt-[120px]" id="features">
            <div class="max-w-[1200px] mx-auto space-y-20 md:space-y-28">

                <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
                    <div>
                        <h2 class="font-semibold text-[#010205] text-[26px] sm:text-[32px] lg:text-[36px] leading-[120%]">Protect Your Privacy &amp; Anonymity</h2>
                        <p class="mt-6 text-[#010205]/85 text-[15px] sm:text-[17px] leading-[175%]">
                            Keep your data safe from cyberstalkers, hackers, and unwanted tracking. We remove harmful content, secure your anonymity, and prevent employers or malicious actors from accessing your personal records.
                        </p>
                    </div>
                    <div class="w-[80%] max-w-[520px] mx-auto aspect-[3/2] overflow-hidden rounded-[28px] order-first lg:order-last">
                        <img src="/assets/image/desktop/landing/new/img1.jpg" alt="" loading="lazy" class="w-full h-full object-cover object-center" />
                    </div>
                </div>

                <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
                    <div class="w-[80%] max-w-[520px] mx-auto aspect-[3/2] overflow-hidden rounded-[28px] order-first">
                        <img src="/assets/image/desktop/landing/new/img2.jpg" alt="" loading="lazy" class="w-full h-full object-cover object-center" />
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
                        <img src="/assets/image/desktop/landing/new/img3.jpg" alt="" loading="lazy" class="w-full h-full object-cover object-center" />
                    </div>
                </div>

            </div>
        </section>
    </div>

    <div class="landing-section" data-header="dark">
        <?php
        require("ultimate.php");
        ?>
    </div>
    <div class="landing-section" data-header="white">
        <?php
        require("digital2.php");
        ?>
    </div>
    <div class="landing-section" data-header="dark">
        <?php
        require("hastle.php");
        ?>
    </div>
    <div class="landing-section" data-header="white">
        <?php
        require("journey.php");
        ?>
    </div>

    <!-- How We Do It: 4-step timeline porting in the process narrative from /new.
         Helps contract evaluators / new visitors understand the concrete
         operational flow (vs the marketing fluff above). -->
    <div class="landing-section bg-white" data-header="dark">
        <section class="px-5 md:px-10 py-16 md:py-24">
            <div class="max-w-[1280px] mx-auto">
                <h2 class="font-bold text-[#010205] text-[36px] sm:text-[48px] lg:text-[56px] leading-[1.1] tracking-[-0.02em]">How We Do It</h2>
                <p class="mt-4 text-[#010205]/75 text-[18px] sm:text-[20px] max-w-[820px]">A step-by-step breakdown of what happens after you sign up.</p>
                <?php
                $pdSteps = [
                    ['t' => 'First 24 hours', 'h' => 'Deleting common data brokers',       'b' => 'We start by removing your data from the highest-traffic brokers like Acxiom and Spokeo — the ones most likely to surface in a Google search of your name.'],
                    ['t' => '48 hours',       'h' => 'Removing from people-search sites',  'b' => 'Next we work through the long tail of people-finding sites (BeenVerified, FastBackgroundCheck, WhitePages and 300+ others) — the ones that resell your address and phone number.'],
                    ['t' => '72 hours',       'h' => 'Removing from genetic databases',    'b' => 'We file removal requests at genetic data sites such as 23andMe and Ancestry — places that can leak family relationships and health data.'],
                    ['t' => 'Rest of the year','h' => 'Continuous sweeping',               'b' => 'Brokers re-upload your data on a quarterly cycle. We monitor and re-submit removal requests as soon as your information reappears, every year you stay subscribed.'],
                ];
                ?>
                <div class="mt-12 grid md:grid-cols-2 xl:grid-cols-4 gap-6 xl:gap-5">
                    <?php foreach ($pdSteps as $idx => $s): ?>
                        <article class="rounded-2xl border border-[#E5E7EB] bg-white p-6 md:p-7 shadow-sm">
                            <div class="relative flex items-center gap-2.5 pb-5">
                                <span class="w-2.5 h-2.5 rounded-full bg-brand shrink-0"></span>
                                <span class="text-[15px] font-bold text-brand leading-7 uppercase tracking-wide"><?= htmlspecialchars($s['t'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <?php if ($idx < count($pdSteps) - 1): ?>
                                    <span class="hidden xl:block absolute left-[170px] right-[-26px] top-[5px] h-px bg-gray-900/15"></span>
                                <?php endif; ?>
                            </div>
                            <h3 class="text-[#111827] font-bold text-[22px] sm:text-[24px] md:text-[26px] leading-[1.25] md:min-h-[74px]">
                                <?= htmlspecialchars($s['h'], ENT_QUOTES, 'UTF-8'); ?>
                            </h3>
                            <p class="mt-4 text-[#6B7280] text-[16px] sm:text-[17px] md:text-[18px] leading-[1.6] md:min-h-[140px]">
                                <?= htmlspecialchars($s['b'], ENT_QUOTES, 'UTF-8'); ?>
                            </p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </div>

    <div class="landing-section" data-header="dark">
        <?php
        require("mobile_animation.php");
        ?>
    </div>
    <div class="landing-section" data-header="dark">
        <?php
        require("testimonial.php");
        ?>
    </div>
    <div class="landing-section scroll-mt-[120px]" data-header="dark" id="np-pricing">
        <section class="bg-white px-5 md:px-10 pb-16 pt-6 md:pt-10">
            <div class="max-w-[960px] mx-auto text-center mb-10">
                <span class="inline-block rounded-full np-bg-green text-white text-sm font-semibold px-5 py-2">Pricing</span>
                <h2 class="mt-6 font-semibold text-[#010205] text-[28px] sm:text-[34px] lg:text-[44px] leading-[1.15] tracking-[-0.02em]">
                    The Right Price For You, Whoever You Are
                </h2>
                <p class="mt-4 text-[#010205]/80 text-[15px] sm:text-[17px] leading-[155%]">
                    Our subscription covers 300+ sites and continues to delete your data from data broker sites year after year
                </p>
            </div>
            <div class="max-w-[960px] mx-auto grid md:grid-cols-2 gap-6 md:gap-8" style="font-family: 'DM Sans', system-ui, sans-serif;">
                <article class="rounded-[20px] border-2 border-[#77B248] bg-white p-6 sm:p-8 md:p-10 flex flex-col shadow-[0_0_0_1px_rgba(119,178,72,0.2)]">
                    <div class="text-[#77B248] font-bold text-[34px] sm:text-[40px] md:text-[52px] leading-none sm:leading-[1.05] flex items-center justify-center">
                        PRO
                    </div>
                    <p class="mt-2 text-[#141414] font-normal text-[18px] sm:text-[21px] leading-[30px] sm:leading-[38px] min-h-[90px]">
                        Get your data deleted from the internet, right away!
                    </p>
                    <div class="rounded-[14px] mt-5 px-1 py-2 flex items-end">
                        <span class="font-bold text-[30px] sm:text-[38px] leading-[1.2]">$299.99</span>
                        <span class="ml-2 font-normal text-[13px] sm:text-[16px] leading-[20px] pb-[4px]">/year</span>
                    </div>
                    <a href="/pricing" class="mt-5 inline-flex rounded-full np-bg-green text-white font-semibold px-8 py-4 min-h-[64px] hover:opacity-95 w-full justify-center items-center">
                        Get Started Now
                    </a>
                    <p class="mt-7 font-bold text-[#141414]">Plan includes:</p>
                    <ul class="mt-4 space-y-3 text-[15px] text-[#141414]/90 text-left">
                        <li class="flex gap-2"><span class="text-[#77B248] font-bold">✓</span> 300+ Sites Opted Out</li>
                        <li class="flex gap-2"><span class="text-[#77B248] font-bold">✓</span> Dark Web Monitoring &amp; Privacy Concierge Support Included</li>
                        <li class="flex gap-2"><span class="text-[#77B248] font-bold">✓</span> Custom Support Through Our Concierge</li>
                    </ul>
                </article>
                <article class="rounded-[20px] border border-slate-200 bg-white p-6 sm:p-8 md:p-10 flex flex-col">
                    <div class="text-[#77B248] font-bold text-[30px] sm:text-[36px] md:text-[50px] leading-none sm:leading-[1.05] flex items-center justify-center text-center">
                        ENTERPRISE
                    </div>
                    <p class="mt-2 text-[#141414] font-normal text-[18px] sm:text-[21px] leading-[30px] sm:leading-[38px] min-h-[90px]">
                        Dedicated support and employee / employer protection for your company.
                    </p>
                    <div class="rounded-[14px] mt-5 px-1 py-2 flex items-end">
                        <span class="font-bold text-[30px] sm:text-[38px] leading-[1.2]">Custom</span>
                    </div>
                    <a href="/business" class="mt-5 inline-flex rounded-full np-bg-green text-white font-semibold px-8 py-4 min-h-[64px] hover:opacity-95 w-full justify-center items-center">
                        Contact Us Now
                    </a>
                    <p class="mt-7 font-bold text-[#141414]">Plan includes:</p>
                    <ul class="mt-4 space-y-3 text-[15px] text-[#141414]/90 text-left">
                        <li class="flex gap-2"><span class="text-[#77B248] font-bold">✓</span> Priority support</li>
                        <li class="flex gap-2"><span class="text-[#77B248] font-bold">✓</span> Unlimited Team Members</li>
                        <li class="flex gap-2"><span class="text-[#77B248] font-bold">✓</span> Custom Solutions</li>
                        <li class="flex gap-2"><span class="text-[#77B248] font-bold">✓</span> Special Enterprise Dashboard</li>
                    </ul>
                </article>
            </div>
        </section>
    </div>
    <div class="landing-section" data-header="dark">
        <?php
        require("faq.php");
        ?>
    </div>
    <div class="landing-section" data-header="white">
        <?php
        require("digital.php"); ?>
    </div>
</div>
<?php include($_SERVER["DOCUMENT_ROOT"] . "/src/pages/Landing/inbound.php"); ?>
<div class="landing-section" data-header="dark">
    <?php
    main_footer();
    ?>
</div>
<script>
    function landing_init() {
        const buttonCouple = {
            "but1": 0,
            "but2": 1,
            "but3": 2,
            "but4": 3,
            "but5": 4,
            "but6": 5,
        }

        const butSize = [152, 142, 135, 135, 95, 126, ]
        const cards = document.querySelectorAll('.timeline-card');
        const fill = document.getElementById('timeline-fill');
        const duck = document.getElementById('duck');
        let timeline_init = false

        let visibleIndexes = new Set();

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                const card = entry.target;
                const index = parseInt(card.getAttribute('data-index'));
                const cardBottom = entry.boundingClientRect.bottom;

                const viewportHeight = window.innerHeight;

                const isCardVisible = cardBottom < viewportHeight;
                if (cardBottom >= 0 && !timeline_init) {
                    timeline_init = true;
                }
                if (timeline_init) {
                    if (isCardVisible) {
                        visibleIndexes.add(index);
                        card.classList.remove('opacity-10');
                        card.classList.add('opacity-100');
                    } else {
                        visibleIndexes.delete(index);
                        card.classList.remove('opacity-100');
                        card.classList.add('opacity-10');
                    }
                }

                const maxIndex = visibleIndexes.size ? Math.max(...visibleIndexes) : -1;
                if (maxIndex >= 0) {
                    const lastCard = document.querySelector(`.timeline-card[data-index="${maxIndex}"]`);
                    const timelineParent = fill && fill.parentElement;
                    if (lastCard && timelineParent) {
                        const bottom = lastCard.getBoundingClientRect().bottom + window.scrollY;
                        const timelineTop = timelineParent.getBoundingClientRect().top + window.scrollY;
                        fill.style.height = `${bottom - timelineTop + 15}px`;
                        duck.style.top = `${bottom - timelineTop}px`;
                    }
                } else {
                    duck.style.top = '0px';
                    fill.style.height = '15px';
                }
            });
        }, {
            threshold: 1 // Adjust sensitivity
        });
        cards.forEach(card => observer.observe(card));



        const mobile_cube = document.getElementById('mobile_cube');
        const faces = {
            front: mobile_cube.querySelector('.mobile_front'),
            right: mobile_cube.querySelector('.mobile_right'),
            back: mobile_cube.querySelector('.mobile_back'),
            left: mobile_cube.querySelector('.mobile_left')
        };
        // const images = ['1.webp', '2.webp', '3.webp', '4.webp', '5.webp', '6.webp'];
        const images = ['1.png', '2.png', '3.png', '4.png', '5.png', '6.png'];
        const imgpos = [2, 3, 4, 5, 0, 1, 2, 3, 4, 5, 0, 1];



        faces.front.style.backgroundImage = `url('/assets/image/desktop/${images[0]}')`;
        faces.right.style.backgroundImage = `url('/assets/image/desktop/${images[1]}')`;
        faces.back.style.backgroundImage = `url('/assets/image/desktop/${images[2]}')`;
        faces.left.style.backgroundImage = `url('/assets/image/desktop/${images[5]}')`;

        let isDragging = false;
        let startX = 0;
        let currentRotation = 0;
        let autoRotateInterval;
        let virtualRotation;
        let virtualpos = 0;
        let finalCurrentRotation;
        let startCurrentRotation;
        let isScrolling = false;

        function buttonfocus() {
            const butpos = Math.abs(currentRotation) / 90 % 6;

            for (let index = 0; index < 6; index++) {
                const ids = `but${index + 1}`

                if (index === butpos) {
                    document.getElementById(ids).focus({
                        preventScroll: true
                    });
                } else {
                    document.getElementById(ids).blur();
                }
            }
        }


        function updateFaceImages() {
            currentIndex = Math.abs(currentRotation / 90) % 12;
            if (currentIndex % 4 === 1) {
                faces.left.style.backgroundImage = `url('/assets/image/desktop/${images[imgpos[currentIndex]]}')`;
            }
            if (currentIndex % 4 === 2) {
                faces.front.style.backgroundImage = `url('/assets/image/desktop/${images[imgpos[currentIndex]]}')`;
            }
            if (currentIndex % 4 === 3) {
                faces.right.style.backgroundImage = `url('/assets/image/desktop/${images[imgpos[currentIndex]]}')`;
            }
            if (currentIndex % 4 === 0) {
                faces.back.style.backgroundImage = `url('/assets/image/desktop/${images[imgpos[currentIndex]]}')`;
            }
            buttonfocus()
        }

        function updateCube() {
            mobile_cube.style.transform = `rotateY(${currentRotation}deg)`;

        }

        function buttonCircle() {
            stopAutoRotate();
            mobile_cube.classList.remove("mobile_transition");
            mobile_cube.classList.add("mobile_transition_fast");
            for (let index = startCurrentRotation; index >= finalCurrentRotation; index -= 90) {
                currentRotation = index;
                updateCube();
                updateFaceImages();
            }
            startCurrentRotation = 0;
            finalCurrentRotation = 0;
            mobile_cube.classList.remove("mobile_transition_fast");
            mobile_cube.classList.add("mobile_transition");
            startAutoRotate()
        }

        function updateCubeDuringDrag(pos) {
            if (pos < virtualpos - 90) {
                virtualpos = virtualpos - 90;
                currentRotation = currentRotation - 90
                updateFaceImages();
            }
            let deltapos = pos - virtualpos;
            if (virtualpos > pos && pos > (-180 + virtualpos) && pos % 90 === 0) {

                currentRotation = currentRotation + pos - virtualpos;
                virtualpos = pos;
                updateFaceImages();
                deltapos = 0;
            }
            mobile_cube.style.transform = `rotateY(${currentRotation + deltapos}deg)`;
        }

        function rotateRight() {
            currentRotation -= 90;
            updateCube();
            updateFaceImages();
        }

        function startAutoRotate() {
            if (!isDragging) {
                autoRotateInterval = setInterval(() => {
                    rotateRight();
                }, 3000); // Rotate every 3 seconds
            }
        }

        function stopAutoRotate() {
            clearInterval(autoRotateInterval);
        }

        function isCubeInViewport() {
            const cubeRect = mobile_cube.getBoundingClientRect();
            const windowHeight = window.innerHeight;
            // Check if the bottom of the cube is in the viewport
            return cubeRect.top < windowHeight && cubeRect.bottom >= 0;
        }

        window.addEventListener('scroll', () => {
            if (isCubeInViewport()) {
                if (!isScrolling) {
                    startAutoRotate(); // Start rotating when cube is in view
                    isScrolling = true;
                }
            } else {
                if (isScrolling) {
                    stopAutoRotate(); // Stop rotating when cube is out of view
                    isScrolling = false;
                }
            }
        }, { passive: true });
        //mouse event
        function startDrag(e) {
            isDragging = true;
            startX = e.clientX || e?.touches[0]?.clientX;
            mobile_cube.classList.remove('mobile_transition');
        }

        let duringDragRaf = 0;
        let duringDragLastX = 0;
        function duringDrag(e) {
            if (!isDragging) return;
            duringDragLastX = e.clientX || e?.touches?.[0]?.clientX;
            if (duringDragRaf) return;
            duringDragRaf = requestAnimationFrame(() => {
                duringDragRaf = 0;
                if (!isDragging) return;
                const delta = duringDragLastX - startX;
                virtualRotation = delta * 0.5;
                updateCubeDuringDrag(virtualRotation);
            });
        }

        function endDrag() {
            if (!isDragging) return;
            isDragging = false;
            mobile_cube.classList.add("mobile_transition");
            currentRotation = currentRotation + virtualRotation - virtualpos;
            if ((Math.abs(currentRotation) + 90) % 90 > 45) {
                currentRotation = currentRotation - (90 - Math.abs(currentRotation) % 90)
            } else {
                currentRotation = currentRotation + (Math.abs(currentRotation) + 90) % 90
            }
            virtualpos = 0;
            updateFaceImages();
            updateCube();
        }
        // Mouse events
        mobile_cube.addEventListener('mouseover', stopAutoRotate);
        mobile_cube.addEventListener('mouseleave', startAutoRotate);
        mobile_cube.addEventListener('mousedown', startDrag);
        window.addEventListener('mousemove', duringDrag);
        window.addEventListener('mouseup', endDrag);

        mobile_cube.addEventListener('touchstart', startDrag, { passive: true });
        window.addEventListener('touchmove', duringDrag, { passive: true });
        window.addEventListener('touchend', endDrag, { passive: true });

        document.addEventListener('visibilitychange', function() {
            if (isScrolling) {
                if (document.hidden) {
                    stopAutoRotate();
                } else {
                    startAutoRotate();
                }
            }
        });

        buttonfocus()

        // Function to handle button focus behavior
        const butgroups = document.getElementById("button-mobile-group");
        const buttons = butgroups.querySelectorAll("button"); // Select all buttons

        // Function to handle button focus behavior
        function handleFocusBehavior(event) {
            const focusedButton = event.currentTarget;
            const currentid = focusedButton.getAttribute('id')
            const width = window.innerWidth;
            if (width < 640) {
                const currentButPos = buttonCouple[currentid];
                if (currentButPos == 0) {
                    butgroups.style.transform = `translateX(-10px)`;
                    return
                }
                const totalsum = butSize.slice(0, 6).reduce((acc, val) => acc + val, 0);
                if (currentButPos == 5) {
                    butgroups.style.transform = `translateX(-${totalsum - width}px)`;
                    return
                }
                const upsum = butSize.slice(0, currentButPos).reduce((acc, val) => acc + val, 0);
                const beforesum = butSize.slice(currentButPos, 6).reduce((acc, val) => acc + val, 0);
                if (beforesum > width) {
                    butgroups.style.transform = `translateX(-${upsum - 20}px)`;
                } else {
                    butgroups.style.transform = `translateX(-${totalsum - width - 30}px)`;
                }
            }
            // Keep focus on the button clicked
            const rotationpos = Math.abs(currentRotation) / 90 % 6;
            const moveDegree = (buttonCouple[currentid] + 6 - rotationpos) % 6 * (-90);
            startCurrentRotation = currentRotation;
            finalCurrentRotation = currentRotation + moveDegree;
            buttonCircle();


        }
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 640) {
                butgroups.style.transform = `translateX(0px)`;
            }
        });

        // Attach the focus behavior to each button
        buttons.forEach(button => {
            button.addEventListener('click', handleFocusBehavior);
            button.addEventListener('mouseover', stopAutoRotate);
            button.addEventListener('mouseleave', startAutoRotate);
            button.addEventListener('touchstart', stopAutoRotate);
            button.addEventListener('touchend', startAutoRotate); // use touchend instead of touchleave
        });

        $("#freeScan").click(function() {
            const name = $("#freeScanName").val();
            const baseUrl = `${window.location.origin}/signup`;
            const url = new URL(baseUrl);
            if (name) {
                url.searchParams.set("fullname", name);
            }
            window.location.href = url.toString();
        });
    }

    function auto_landing_header() {
        const white_header = document.getElementById("white-header");
        const black_header = document.getElementById("black-header");
        const sections = document.querySelectorAll(".landing-section");

        function updateHeader() {
            let scrollPos = window.scrollY; // current scroll from top

            // Find the section that has just reached/passed the top
            let currentSection = null;
            sections.forEach(section => {
                if (scrollPos >= section.offsetTop - 30) {
                    currentSection = section;
                }
            });

            if (currentSection) {
                const theme = currentSection.getAttribute("data-header");
                if (theme === "dark") {
                    white_header.classList.add("hidden");
                    black_header.classList.remove("hidden");
                } else {
                    black_header.classList.add("hidden");
                    white_header.classList.remove("hidden");
                }
            }
        }

        let headerRaf = 0;
        const onHeaderScroll = () => {
            if (headerRaf) return;
            headerRaf = requestAnimationFrame(() => {
                headerRaf = 0;
                updateHeader();
            });
        };
        window.addEventListener("scroll", onHeaderScroll, { passive: true });
        window.addEventListener("load", updateHeader); // run on first load
    }
    auto_landing_header();
    landing_init();
</script>