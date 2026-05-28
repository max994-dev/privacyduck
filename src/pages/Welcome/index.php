<?php
/**
 * Welcome / "What Happens Next" page.
 *
 * Shown once to a user after their first successful payment, before they
 * land on the dashboard. Walks them through the 4-phase removal journey
 * with plain-English explanations of what PrivacyDuck is doing for them
 * at each stage.
 *
 * Reached via route '/welcome' (added to index.php). Sets
 * $_SESSION['pd_welcome_seen'] = 1 on first view so subsequent dashboard
 * loads skip this page. User can also opt in to see it again via a link
 * at the bottom of the page (handy for support / refreshing memory).
 *
 * Auth: requires a logged-in user. Bounce to /new_signin if not authenticated.
 * No payment gate on view (so unpaid users who somehow reach this URL
 * still see the same journey, with a "ready to start" CTA pointing at
 * /pricing).
 */

if (empty($_SESSION['isAuthenticated'])) {
    header('Location: ' . WEB_DOMAIN . '/new_signin');
    exit;
}

$_SESSION['pd_welcome_seen'] = 1;

$isPlanable = !empty($_SESSION['planable']);
$firstName = htmlspecialchars((string)($_SESSION['firstname'] ?? $_SESSION['fullName'] ?? 'there'), ENT_QUOTES, 'UTF-8');

$meta_title = 'Welcome to PrivacyDuck';
$meta_description = 'Your data removal journey starts now. Here\'s exactly what happens next.';
$meta_url = 'https://privacyduck.com/welcome';

include_once(BASEPATH . '/src/common/meta.php');
main_head_start();
?>
<style>
    body { background: #FAFAFA; }
    .pd-welcome-stage {
        background: linear-gradient(180deg, #FFFFFF 0%, #FFFFFF 100%);
        border: 1px solid #ECECEC;
    }
    .pd-stage-num {
        background: linear-gradient(135deg, #77B248 0%, #24A556 100%);
        color: white;
    }
    .pd-welcome-fade { opacity: 0; transform: translateY(14px); transition: opacity .6s cubic-bezier(.16,1,.3,1), transform .6s cubic-bezier(.16,1,.3,1); }
    .pd-welcome-fade.in { opacity: 1; transform: none; }
</style>
<?php main_head_end(); ?>

<div class="min-h-screen bg-[#FAFAFA] py-12 sm:py-16 px-4">
    <div class="max-w-[920px] mx-auto">

        <!-- Hero -->
        <div class="text-center pd-welcome-fade" data-fade>
            <div class="inline-flex items-center gap-2 rounded-full bg-[#24A55614] text-[#24A556] px-4 py-1.5 text-[13px] font-semibold mb-4">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <?php if ($isPlanable): ?>Plan active. Removals starting.<?php else: ?>Account created.<?php endif; ?>
            </div>
            <h1 class="text-[28px] sm:text-[40px] lg:text-[48px] font-bold tracking-tight text-[#010205] leading-[1.1]">
                Welcome, <?= $firstName; ?>. <br class="hidden sm:block">Here&rsquo;s exactly what happens next.
            </h1>
            <p class="mt-5 text-[16px] sm:text-[18px] text-[#5B5F66] max-w-[620px] mx-auto leading-[1.55]">
                Your information is on 400+ data broker sites right now. Over the next few weeks we&rsquo;ll work through every one of them. No software for you to install. No follow-up needed from you.
            </p>
        </div>

        <!-- 4-phase timeline -->
        <div class="mt-12 sm:mt-16 space-y-6">

            <!-- Phase 1 -->
            <div class="pd-welcome-stage rounded-2xl p-6 sm:p-8 flex items-start gap-5 pd-welcome-fade" data-fade data-fade-delay="100">
                <div class="pd-stage-num shrink-0 w-12 h-12 rounded-full flex items-center justify-center font-bold text-[18px]">1</div>
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                        <h2 class="text-[20px] sm:text-[22px] font-bold text-[#010205]">First 24 hours</h2>
                        <span class="text-[13px] font-semibold text-[#24A556] uppercase tracking-wide">In progress now</span>
                    </div>
                    <p class="mt-2 text-[15px] sm:text-[16px] text-[#5B5F66] leading-[1.6]">
                        We start with the highest-traffic data brokers &mdash; the ones most likely to surface when someone Googles your name. Acxiom, Spokeo, BeenVerified, WhitePages, FastPeopleSearch. Real US-based agents fill the opt-out forms by hand and submit the removal requests.
                    </p>
                </div>
            </div>

            <!-- Phase 2 -->
            <div class="pd-welcome-stage rounded-2xl p-6 sm:p-8 flex items-start gap-5 pd-welcome-fade" data-fade data-fade-delay="200">
                <div class="pd-stage-num shrink-0 w-12 h-12 rounded-full flex items-center justify-center font-bold text-[18px]">2</div>
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                        <h2 class="text-[20px] sm:text-[22px] font-bold text-[#010205]">48 to 72 hours</h2>
                        <span class="text-[13px] font-semibold text-[#878C91] uppercase tracking-wide">Coming up</span>
                    </div>
                    <p class="mt-2 text-[15px] sm:text-[16px] text-[#5B5F66] leading-[1.6]">
                        We work through the long tail of 300+ people-search sites (TruePeopleSearch, IDCrawl, Radaris, ThatsThem and more). Many require us to click a confirmation link they email back; our automated inbox bot handles that within minutes of each broker&rsquo;s reply.
                    </p>
                </div>
            </div>

            <!-- Phase 3 -->
            <div class="pd-welcome-stage rounded-2xl p-6 sm:p-8 flex items-start gap-5 pd-welcome-fade" data-fade data-fade-delay="300">
                <div class="pd-stage-num shrink-0 w-12 h-12 rounded-full flex items-center justify-center font-bold text-[18px]">3</div>
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                        <h2 class="text-[20px] sm:text-[22px] font-bold text-[#010205]">Week 1 &ndash; 4</h2>
                        <span class="text-[13px] font-semibold text-[#878C91] uppercase tracking-wide">Coming up</span>
                    </div>
                    <p class="mt-2 text-[15px] sm:text-[16px] text-[#5B5F66] leading-[1.6]">
                        Brokers process removal requests at their own pace &mdash; usually 5 to 30 days. As each one acknowledges or completes the removal, you&rsquo;ll see the count climb on your dashboard. We capture a screenshot of every confirmation as proof.
                    </p>
                </div>
            </div>

            <!-- Phase 4 -->
            <div class="pd-welcome-stage rounded-2xl p-6 sm:p-8 flex items-start gap-5 pd-welcome-fade" data-fade data-fade-delay="400">
                <div class="pd-stage-num shrink-0 w-12 h-12 rounded-full flex items-center justify-center font-bold text-[18px]">4</div>
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                        <h2 class="text-[20px] sm:text-[22px] font-bold text-[#010205]">Every 90 days, forever</h2>
                        <span class="text-[13px] font-semibold text-[#878C91] uppercase tracking-wide">Ongoing</span>
                    </div>
                    <p class="mt-2 text-[15px] sm:text-[16px] text-[#5B5F66] leading-[1.6]">
                        Data brokers re-upload your data on a quarterly cycle. We sweep again every 90 days &mdash; new removal requests filed for anything that resurfaced. As long as your plan is active, your data stays off.
                    </p>
                </div>
            </div>

        </div>

        <!-- Honest note + CTA -->
        <div class="mt-10 sm:mt-14 rounded-2xl border border-[#FFEFC6] bg-[#FFFBEB] p-6 sm:p-7 pd-welcome-fade" data-fade data-fade-delay="500">
            <h3 class="text-[16px] sm:text-[17px] font-bold text-[#92400E] flex items-center gap-2">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 9v4M12 17h.01M4.93 19h14.14a2 2 0 001.74-3L13.74 4a2 2 0 00-3.48 0L3.19 16a2 2 0 001.74 3z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                A few honest details
            </h3>
            <ul class="mt-3 space-y-2 text-[14px] sm:text-[15px] text-[#92400E]/90 leading-[1.6]">
                <li><strong>We need a few details from you</strong> &mdash; brokers verify identity using your date of birth, address, and phone. If anything&rsquo;s missing from your profile, we can&rsquo;t file the request. The dashboard will flag any gaps.</li>
                <li><strong>Not every broker complies on the first ask</strong> &mdash; some require multiple attempts. We keep trying. Some block our automation entirely; those are tracked separately in your dashboard so you can see exactly what&rsquo;s pending.</li>
                <li><strong>It&rsquo;s a process, not an instant fix</strong> &mdash; expect most major brokers to confirm within 2 weeks and the long tail within 4 to 6 weeks.</li>
            </ul>
        </div>

        <!-- Action -->
        <div class="mt-10 sm:mt-12 text-center pd-welcome-fade" data-fade data-fade-delay="600">
            <?php if ($isPlanable): ?>
                <a href="/new_dashboard" class="inline-flex items-center justify-center gap-2 rounded-full bg-gradient-to-r from-[#77B248] to-[#24A556] text-white font-semibold text-[15px] sm:text-[16px] px-8 py-4 shadow-[0_8px_20px_-8px_rgba(36,165,86,0.45)] hover:shadow-[0_12px_24px_-8px_rgba(36,165,86,0.55)] transition-shadow">
                    Take me to my dashboard
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
                <p class="mt-4 text-[13px] text-[#878C91]">You can come back to this page anytime from the dashboard.</p>
            <?php else: ?>
                <a href="/pricing" class="inline-flex items-center justify-center gap-2 rounded-full bg-gradient-to-r from-[#77B248] to-[#24A556] text-white font-semibold text-[15px] sm:text-[16px] px-8 py-4 shadow-[0_8px_20px_-8px_rgba(36,165,86,0.45)]">
                    Choose your plan to begin
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
                <p class="mt-4 text-[13px] text-[#878C91]">Your dashboard will start populating once your plan activates.</p>
            <?php endif; ?>
        </div>

    </div>
</div>

<script>
    // Cheap intro fade for the timeline cards.
    (function () {
        var els = document.querySelectorAll('[data-fade]');
        if (!('IntersectionObserver' in window)) {
            els.forEach(function (e) { e.classList.add('in'); });
            return;
        }
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (!e.isIntersecting) return;
                var d = parseInt(e.target.getAttribute('data-fade-delay') || '0', 10);
                setTimeout(function () { e.target.classList.add('in'); }, d);
                io.unobserve(e.target);
            });
        }, { threshold: 0.15 });
        els.forEach(function (e) { io.observe(e); });
    })();
</script>
</body>
</html>
