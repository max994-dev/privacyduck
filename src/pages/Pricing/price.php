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
//
// Removed from previous iteration:
//  - "Add a free onboarding call" checkbox bar (ugly, tacked-on, distracted
//    from the primary conversion). Can re-add later inside the checkout flow.
//  - The slide.php / faq.php / digital.php includes below the cards (removed
//    in Pricing/index.php, not here).

$pdPersonOrder = ['single', 'couple', 'family'];

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
$pdPersonLabels    = ['single' => 'Single', 'couple' => 'Couple', 'family' => 'Family'];
$pdPersonSubtitles = ['single' => 'Just you', 'couple' => 'You + 1 partner', 'family' => 'Up to 4 people'];
$pdMostPopular = 'couple';
?>
<section class="relative isolate bg-[#0B1014] text-white overflow-hidden">
    <!-- Soft glow accents -->
    <div aria-hidden="true" class="pointer-events-none absolute inset-0">
        <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[800px] h-[800px] rounded-full bg-[#24A556]/10 blur-[150px]"></div>
        <div class="absolute top-1/3 right-0 w-[400px] h-[400px] rounded-full bg-[#24A556]/8 blur-[120px]"></div>
    </div>

    <div class="relative max-w-[1200px] mx-auto px-5 sm:px-8 pt-[160px] pb-24 sm:pt-[200px] sm:pb-32">

        <!-- Heading -->
        <div class="text-center max-w-[760px] mx-auto">
            <span class="inline-block rounded-full bg-white/10 text-white/90 text-[12px] font-semibold uppercase tracking-wider px-4 py-1.5">Pricing</span>
            <h1 class="mt-5 font-semibold text-white text-[40px] sm:text-[56px] lg:text-[64px] leading-[1.05] tracking-[-0.02em]">
                Real people, removing your data,<br class="hidden sm:block"> year after year
            </h1>
            <p class="mt-6 text-white/75 text-[16px] sm:text-[18px] leading-[1.6] max-w-[600px] mx-auto">
                One plan per household. Cancel any time. Every plan includes unlimited removal requests across 400+ data brokers.
            </p>
        </div>

        <!-- Year toggle -->
        <div class="mt-12 flex justify-center">
            <div class="inline-flex bg-white/10 backdrop-blur-md rounded-full p-1.5 border border-white/15" id="pd-year-toggle" role="tablist" aria-label="Term length">
                <button type="button" data-year="one" class="pd-year-btn relative px-7 py-3 text-[14px] sm:text-[15px] font-semibold rounded-full transition-all duration-200 bg-white text-[#0B1014]" aria-pressed="true">
                    1 Year
                </button>
                <button type="button" data-year="two" class="pd-year-btn relative px-7 py-3 text-[14px] sm:text-[15px] font-semibold rounded-full transition-all duration-200 text-white/80 hover:text-white" aria-pressed="false">
                    2 Years
                    <span class="absolute -top-3 -right-3 bg-gradient-to-r from-red-500 to-orange-500 text-white text-[10px] font-bold uppercase tracking-wide px-2 py-0.5 rounded-full shadow-lg whitespace-nowrap">
                        Save 45%
                    </span>
                </button>
            </div>
        </div>

        <!-- 3 plan cards side-by-side -->
        <div class="mt-14 grid gap-6 md:grid-cols-3 max-w-[1080px] mx-auto items-stretch" id="pd-plan-cards">
            <?php foreach ($pdPersonOrder as $person):
                $isPopular = ($person === $pdMostPopular);
                $features  = $pdFeaturesByPerson[$person];
            ?>
            <article
                class="pd-plan-card relative bg-white text-[#0B1014] rounded-3xl p-7 sm:p-8 flex flex-col <?= $isPopular ? 'ring-2 ring-[#24A556] shadow-2xl shadow-[#24A556]/20 md:-translate-y-3' : 'shadow-xl' ?>"
                data-person="<?= $person ?>"
            >
                <?php if ($isPopular): ?>
                <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-[#24A556] text-white text-[11px] font-bold uppercase tracking-[0.08em] px-4 py-1.5 rounded-full shadow-lg whitespace-nowrap">
                    Most popular
                </div>
                <?php endif; ?>

                <!-- Plan header -->
                <div>
                    <h3 class="font-bold text-[24px] sm:text-[26px] tracking-[-0.01em]"><?= htmlspecialchars($pdPersonLabels[$person]) ?></h3>
                    <p class="text-[14px] text-slate-500 mt-1"><?= htmlspecialchars($pdPersonSubtitles[$person]) ?></p>
                </div>

                <!-- Price block -->
                <div class="mt-6 pb-6 border-b border-slate-100">
                    <div class="flex items-baseline gap-1.5">
                        <span class="pd-price-amount font-bold text-[44px] sm:text-[52px] leading-none tracking-[-0.02em]" data-bind="price-amount">$-</span>
                        <span class="text-[15px] text-slate-500 font-medium">/mo</span>
                    </div>
                    <div class="mt-2 text-[13px] text-slate-500" data-bind="billed">&nbsp;</div>
                </div>

                <!-- Feature list -->
                <ul class="mt-6 space-y-3.5 text-[14px] text-slate-700 flex-1">
                    <?php foreach ($features as $feature): ?>
                    <li class="flex items-start gap-2.5">
                        <span class="mt-0.5 inline-flex items-center justify-center shrink-0 w-5 h-5 rounded-full bg-[#24A556]/15">
                            <svg class="w-3 h-3 text-[#24A556]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                        <span><?= htmlspecialchars($feature) ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <!-- CTA -->
                <a
                    href="#"
                    data-bind="cta"
                    class="<?= $isPopular ? 'bg-[#24A556] hover:bg-[#1E8C49] text-white' : 'bg-[#0B1014] hover:bg-[#1F2937] text-white' ?> mt-7 inline-flex justify-center items-center gap-2 rounded-full font-semibold text-[15px] px-6 py-3.5 transition-colors shadow-sm"
                >
                    Get started
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 5L19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </article>
            <?php endforeach; ?>
        </div>

        <!-- Onboarding call opt-in (kept; restyled to match the new dark hero) -->
        <div class="mt-10 max-w-[640px] mx-auto">
            <label for="pd_book_call_optin_pricing"
                   class="group flex items-center gap-4 px-5 py-4 rounded-2xl bg-white/[0.06] border border-white/15 hover:bg-white/[0.10] hover:border-white/25 transition-colors cursor-pointer">
                <input type="checkbox"
                       id="pd_book_call_optin_pricing"
                       class="peer sr-only" />
                <span aria-hidden="true"
                      class="shrink-0 inline-flex items-center justify-center w-6 h-6 rounded-md border border-white/40 bg-white/5
                             peer-checked:bg-[#24A556] peer-checked:border-[#24A556] transition-colors">
                    <svg class="w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </span>
                <span aria-hidden="true"
                      class="shrink-0 inline-flex items-center justify-center w-10 h-10 rounded-full bg-[#24A556]/15 text-[#24A556]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.95.68l1.5 4.5a1 1 0 01-.5 1.21l-2.26 1.13a11 11 0 005.52 5.52l1.13-2.26a1 1 0 011.21-.5l4.5 1.5a1 1 0 01.68.95V19a2 2 0 01-2 2A16 16 0 013 5z"/></svg>
                </span>
                <span class="flex-1 text-white">
                    <span class="block font-semibold text-[15px]">Add a free 30-min onboarding call</span>
                    <span class="block text-[13px] text-white/70 mt-0.5">After checkout, pick a slot (2–4 PM Pacific). We walk through setup live with you.</span>
                </span>
            </label>
        </div>

        <!-- Trust strip -->
        <div class="mt-16 max-w-[980px] mx-auto">
            <div class="flex flex-wrap items-center justify-center gap-x-8 gap-y-4 text-white/75 text-[13px] sm:text-[14px]">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#24A556]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span class="font-medium">256-bit TLS</span>
                </div>
                <span class="hidden sm:inline text-white/20">·</span>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#24A556]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    <span class="font-medium">Stripe-secured payments</span>
                </div>
                <span class="hidden sm:inline text-white/20">·</span>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#24A556]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span class="font-medium">UK GDPR aligned</span>
                </div>
                <span class="hidden sm:inline text-white/20">·</span>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#24A556]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M9 7a3 3 0 11-6 0 3 3 0 016 0zm12 0a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="font-medium">US team since 2019</span>
                </div>
                <span class="hidden sm:inline text-white/20">·</span>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#24A556]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    <span class="font-medium">We never sell your data</span>
                </div>
            </div>
        </div>

    </div>
</section>

<script>
    (function () {
        'use strict';
        <?php require_once(BASEPATH . "/src/pages/Dashboard/plans/data.php") ?>

        var data = (typeof dashboard_plans_data !== 'undefined') ? dashboard_plans_data : {};
        var currentSessionPlanId = "<?php echo isset($_SESSION['plan_id']) ? (int) $_SESSION['plan_id'] : ''; ?>";
        var currentSessionEmail = "<?php echo htmlspecialchars($_SESSION['email'] ?? '', ENT_QUOTES); ?>";
        var isLoggedIn = <?php echo !empty($_SESSION['isAuthenticated']) ? 'true' : 'false'; ?>;

        // Extract just the dollar-amount portion of the DB price string.
        // DB stores e.g. '$25.00<span class="text-[16px]">/mo</span>' so we keep
        // only the amount and let our card markup render the "/mo" suffix.
        function extractAmount(priceHtml) {
            if (!priceHtml) return '';
            // Strip everything from the first '<' onward.
            var i = priceHtml.indexOf('<');
            return (i >= 0 ? priceHtml.slice(0, i) : priceHtml).trim();
        }

        function applyYear(year) {
            var planSet = data[year] || {};
            document.querySelectorAll('.pd-plan-card').forEach(function (card) {
                var person = card.getAttribute('data-person');
                var plan = planSet[person];
                if (!plan) return;
                var amountEl = card.querySelector('[data-bind="price-amount"]');
                if (amountEl) amountEl.textContent = extractAmount(plan.price);
                var billedEl = card.querySelector('[data-bind="billed"]');
                if (billedEl) billedEl.textContent = plan.billed || '';
                var ctaEl = card.querySelector('[data-bind="cta"]');
                if (!ctaEl) return;
                if (!isLoggedIn) {
                    ctaEl.setAttribute('href', '/new_signin?next=/pricing');
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
                if (String(plan.id) === currentSessionPlanId) {
                    ctaEl.firstChild && (ctaEl.firstChild.nodeValue = 'Current plan ');
                    ctaEl.classList.add('opacity-60', 'pointer-events-none');
                }
            });
        }

        var toggleBtns = document.querySelectorAll('.pd-year-btn');
        toggleBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                toggleBtns.forEach(function (b) {
                    b.classList.remove('bg-white', 'text-[#0B1014]');
                    b.classList.add('text-white/80');
                    b.setAttribute('aria-pressed', 'false');
                });
                btn.classList.remove('text-white/80');
                btn.classList.add('bg-white', 'text-[#0B1014]');
                btn.setAttribute('aria-pressed', 'true');
                applyYear(btn.getAttribute('data-year'));
            });
        });

        applyYear('one');
    })();
</script>
