<?php
// Pricing page hero + 3 plan cards + trust strip.
// Replaces the previous single-card tab-switching layout (visible-by-default
// "Standard Protection $25/mo for 1 person", with other plans hidden behind
// Single/Couple/Family tabs). New layout shows all 3 person-plans side-by-side
// for a chosen term, with one year toggle controlling all cards.
//
// Data source: src/pages/Dashboard/plans/data.php (DB-backed).
// Year toggle JS reuses the same data object so server-rendered HTML and
// client-side swapped HTML are guaranteed in sync.

// Defaults shown on first render (1 year).
$pdDefaultYear = 'one';
$pdPersonOrder = ['single', 'couple', 'family'];

// Standard feature set for every plan. The Couple/Family plans inherit + extra.
$pdFeaturesBase = [
    'Remove unlimited aliases, previous names, and email addresses',
    'Enhanced privacy tools like email and phone masking',
    'Email, Chat, and Phone Support',
    'Custom removal requests plus automated services',
];
$pdFeaturesByPerson = [
    'single' => array_merge($pdFeaturesBase, []),
    'couple' => array_merge($pdFeaturesBase, ['Cover 2 people on one account']),
    'family' => array_merge($pdFeaturesBase, ['Cover up to 4 people on one account', 'Centralised family dashboard']),
];
$pdMostPopular = 'couple';
?>
<div class="bg-[url('/assets/image/desktop/pricing_bg.png')] bg-no-repeat bg-cover bg-center pb-[80px] px-[20px] pt-[145px]">
    <div class="max-w-[1200px] mx-auto">

        <!-- Heading + year toggle -->
        <div class="text-center">
            <h1 class="text-white font-semibold text-[32px] sm:text-[44px] lg:text-[52px] leading-[1.15] tracking-[-1px] max-w-[820px] mx-auto">
                Real people, removing your data, year after year
            </h1>
            <p class="mt-4 text-white/85 text-[16px] sm:text-[17px] leading-[1.6] max-w-[640px] mx-auto">
                One plan per household — single, couple, or family. Cancel any time. All plans include unlimited removal requests across 300+ data brokers.
            </p>
        </div>

        <!-- Year toggle -->
        <div class="mt-10 flex justify-center">
            <div class="inline-flex bg-white/15 backdrop-blur-md rounded-full p-1 shadow-md" id="pd-year-toggle" role="tablist" aria-label="Term length">
                <button type="button" data-year="one" class="pd-year-btn relative px-6 py-2.5 text-[14px] sm:text-[15px] font-semibold text-white rounded-full transition-colors bg-[#24A556]" aria-pressed="true">
                    1 Year
                </button>
                <button type="button" data-year="two" class="pd-year-btn relative px-6 py-2.5 text-[14px] sm:text-[15px] font-semibold text-white rounded-full transition-colors" aria-pressed="false">
                    2 Years
                    <span class="absolute -top-2.5 -right-2 bg-red-500 text-white text-[11px] font-bold px-2 py-0.5 rounded-full shadow">
                        Save 45%
                    </span>
                </button>
            </div>
        </div>

        <!-- 3 plan cards side-by-side -->
        <div class="mt-10 grid gap-6 md:grid-cols-3 max-w-[1100px] mx-auto" id="pd-plan-cards">
            <?php foreach ($pdPersonOrder as $person):
                $isMostPopular = ($person === $pdMostPopular);
                $features = $pdFeaturesByPerson[$person];
                $personLabels = ['single' => 'Single', 'couple' => 'Couple', 'family' => 'Family'];
                $personSubtitles = [
                    'single' => 'Just you',
                    'couple' => 'You + 1',
                    'family' => 'Up to 4 people',
                ];
            ?>
            <article class="pd-plan-card relative bg-white rounded-2xl p-7 sm:p-8 shadow-xl flex flex-col <?= $isMostPopular ? 'ring-2 ring-[#24A556] md:scale-[1.02]' : '' ?>" data-person="<?= $person ?>">
                <?php if ($isMostPopular): ?>
                <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-[#24A556] text-white text-[12px] font-bold uppercase tracking-wide px-4 py-1 rounded-full shadow-md">
                    Most popular
                </div>
                <?php endif; ?>

                <div>
                    <h3 class="font-bold text-[22px] text-[#010205]" data-bind="title"><?= htmlspecialchars($personLabels[$person]) ?></h3>
                    <p class="text-[14px] text-slate-500 mt-1" data-bind="subtitle"><?= htmlspecialchars($personSubtitles[$person]) ?></p>
                </div>

                <div class="mt-6 min-h-[88px]">
                    <div class="text-[#010205] flex items-baseline gap-1" data-bind="price">
                        <span class="font-bold text-[40px] leading-none">$—</span>
                        <span class="text-[15px] text-slate-500">/mo</span>
                    </div>
                    <div class="mt-2 text-[13px] text-slate-500" data-bind="billed">Loading…</div>
                </div>

                <ul class="mt-6 space-y-3 text-[14px] text-[#010205]/90 flex-1">
                    <?php foreach ($features as $feature): ?>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-[#24A556] mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span><?= htmlspecialchars($feature) ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <a href="#" data-bind="cta" class="mt-7 inline-flex justify-center items-center gap-2 rounded-full <?= $isMostPopular ? 'bg-[#24A556] hover:bg-[#1E8C49]' : 'bg-[#010205] hover:bg-[#374151]' ?> text-white font-semibold text-[15px] px-6 py-3.5 transition-colors">
                    Get started
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M5 12H19" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 5L19 12L12 19" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </article>
            <?php endforeach; ?>
        </div>

        <!-- Optional Book Call opt-in (preserved from previous flow) -->
        <div class="mt-8 max-w-[640px] mx-auto bg-white/10 backdrop-blur-md rounded-xl px-5 py-4 text-white">
            <label class="flex items-start gap-3 cursor-pointer">
                <input type="checkbox" id="pd_book_call_optin_pricing" class="mt-1 w-5 h-5 rounded border-white/30 text-[#24A556] focus:ring-[#24A556]" />
                <span class="text-[14px] leading-[1.55]">
                    <span class="font-semibold text-white">Add a free onboarding call</span> — after checkout, you’ll book a 30-minute slot (2–4 PM Pacific) before entering your details.
                </span>
            </label>
        </div>

        <!-- Trust strip -->
        <div class="mt-12 max-w-[1100px] mx-auto bg-white/10 backdrop-blur-md rounded-2xl px-6 py-5 sm:py-6">
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-y-4 gap-x-2 text-white text-center">
                <div class="flex flex-col items-center gap-1.5 sm:flex-row sm:gap-2 sm:justify-center">
                    <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span class="text-[13px] font-semibold whitespace-nowrap">256-bit TLS</span>
                </div>
                <div class="flex flex-col items-center gap-1.5 sm:flex-row sm:gap-2 sm:justify-center">
                    <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    <span class="text-[13px] font-semibold whitespace-nowrap">Stripe-secured</span>
                </div>
                <div class="flex flex-col items-center gap-1.5 sm:flex-row sm:gap-2 sm:justify-center">
                    <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span class="text-[13px] font-semibold whitespace-nowrap">UK GDPR aligned</span>
                </div>
                <div class="flex flex-col items-center gap-1.5 sm:flex-row sm:gap-2 sm:justify-center">
                    <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M9 7a3 3 0 11-6 0 3 3 0 016 0zm12 0a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="text-[13px] font-semibold whitespace-nowrap">US team since 2019</span>
                </div>
                <div class="flex flex-col items-center gap-1.5 sm:flex-row sm:gap-2 sm:justify-center col-span-2 sm:col-span-1">
                    <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    <span class="text-[13px] font-semibold whitespace-nowrap">We never sell data</span>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    (function () {
        'use strict';
        <?php require_once(BASEPATH . "/src/pages/Dashboard/plans/data.php") ?>

        var data = (typeof dashboard_plans_data !== 'undefined') ? dashboard_plans_data : {};
        var currentYear = 'one';
        var currentSessionPlanId = "<?php echo isset($_SESSION['plan_id']) ? (int) $_SESSION['plan_id'] : ''; ?>";
        var currentSessionEmail = "<?php echo htmlspecialchars($_SESSION['email'] ?? '', ENT_QUOTES); ?>";
        var isLoggedIn = <?php echo !empty($_SESSION['isAuthenticated']) ? 'true' : 'false'; ?>;

        function applyYear(year) {
            currentYear = year;
            var planSet = data[year] || {};
            document.querySelectorAll('.pd-plan-card').forEach(function (card) {
                var person = card.getAttribute('data-person');
                var plan = planSet[person];
                if (!plan) return;
                var priceEl = card.querySelector('[data-bind="price"]');
                if (priceEl) priceEl.innerHTML = plan.price.replace(/<span[^>]*>/, '<span class="text-[15px] text-slate-500">');
                var billedEl = card.querySelector('[data-bind="billed"]');
                if (billedEl) billedEl.textContent = plan.billed || '';
                var ctaEl = card.querySelector('[data-bind="cta"]');
                if (ctaEl) {
                    if (!isLoggedIn) {
                        ctaEl.setAttribute('href', '/login?next=/pricing');
                        return;
                    }
                    var link = plan.stripe_payment_link || '';
                    if (link) {
                        var sep = link.indexOf('?') >= 0 ? '&' : '?';
                        link += sep + 'prefilled_email=' + encodeURIComponent(currentSessionEmail);
                        if (plan.coupon) link += '&prefilled_promo_code=' + encodeURIComponent(plan.coupon);
                    }
                    ctaEl.setAttribute('href', link);
                    ctaEl.setAttribute('target', '_blank');
                    ctaEl.setAttribute('rel', 'noopener');
                    // If this plan matches user's current plan, soften the CTA.
                    if (String(plan.id) === currentSessionPlanId) {
                        ctaEl.textContent = 'Current plan';
                        ctaEl.classList.add('opacity-60', 'pointer-events-none');
                    }
                }
            });
        }

        // Wire toggle
        var toggleBtns = document.querySelectorAll('.pd-year-btn');
        toggleBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                toggleBtns.forEach(function (b) {
                    b.classList.remove('bg-[#24A556]');
                    b.setAttribute('aria-pressed', 'false');
                });
                btn.classList.add('bg-[#24A556]');
                btn.setAttribute('aria-pressed', 'true');
                applyYear(btn.getAttribute('data-year'));
            });
        });

        // Book Call intent persistence (preserved from previous behaviour)
        var bookCallCb = document.getElementById('pd_book_call_optin_pricing');
        if (bookCallCb) {
            bookCallCb.checked = <?php echo !empty($_SESSION['pd_book_call_intent']) ? 'true' : 'false'; ?>;
            bookCallCb.addEventListener('change', function () {
                if (typeof fetch === 'function') {
                    var fd = new FormData();
                    fd.append('intent', this.checked ? 1 : 0);
                    fetch('/book_call_set_intent', { method: 'POST', credentials: 'same-origin', body: fd });
                }
            });
        }

        // Initial render with default year.
        applyYear('one');
    })();
</script>
