<?php
/**
 * UK GDPR / PECR cookie consent banner.
 *
 * Renders the banner + preference modal, manages consent state in localStorage
 * (key `pdcc:v1`) plus a cookie (`pd_cc`) for future server-side awareness, and
 * conditionally loads non-essential third-party scripts (Google Tag Manager +
 * GA4, Tawk.to live chat) only after the user has explicitly consented to the
 * matching category.
 *
 * Categories:
 *   - necessary   (always on; session/auth/CSRF/Stripe payment cookies)
 *   - analytics   (GTM-P679J6HD + GA4 G-P6WKNFG8FS)
 *   - functional  (Tawk.to live chat)
 *
 * Designed to be invoked once near the top of <body> via main_head_end()
 * in src/common/utils.php. Re-open the preference panel from anywhere via
 *   window.pdConsent.open()
 * Read the current state via
 *   window.pdConsent.get()
 *
 * Google Consent Mode v2 defaults are declared to "denied" BEFORE GTM
 * loads, so even if a tag fires later it respects the user's choice.
 */
if (!function_exists('pd_cookie_banner_render')) {
    function pd_cookie_banner_render(): void
    {
        ?>
<!-- BEGIN PrivacyDuck cookie consent (UK GDPR + PECR) -->
<div id="pd-cc-root" aria-live="polite">
    <!-- Banner -->
    <div id="pd-cc-banner" role="dialog" aria-modal="false" aria-labelledby="pd-cc-banner-title"
         class="fixed bottom-4 left-4 right-4 sm:left-auto sm:right-6 sm:bottom-6 sm:max-w-md z-[9999] bg-white text-[#010205] rounded-2xl shadow-2xl border border-[#E5E7EB] p-5 hidden">
        <h2 id="pd-cc-banner-title" class="font-semibold text-[18px] leading-[1.3]">Cookies on PrivacyDuck</h2>
        <p class="mt-2 text-[14px] leading-[1.5] text-[#374151]">
            We use cookies to keep the site working, measure how people use it, and run live chat. You decide what's on.
            <a href="/cookie-policy" class="underline hover:no-underline">Read our cookie policy</a>.
        </p>
        <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:gap-3">
            <button type="button" id="pd-cc-accept" class="flex-1 rounded-full bg-[#24A556] hover:bg-[#1E8C49] text-white font-semibold text-[14px] px-4 py-2.5">Accept all</button>
            <button type="button" id="pd-cc-reject" class="flex-1 rounded-full border border-[#D1D5DB] hover:bg-[#F3F4F6] text-[#010205] font-semibold text-[14px] px-4 py-2.5">Reject non-essential</button>
            <button type="button" id="pd-cc-customize" class="flex-1 rounded-full border border-[#D1D5DB] hover:bg-[#F3F4F6] text-[#010205] font-semibold text-[14px] px-4 py-2.5">Customize</button>
        </div>
    </div>

    <!-- Preference modal -->
    <div id="pd-cc-modal" role="dialog" aria-modal="true" aria-labelledby="pd-cc-modal-title"
         class="fixed inset-0 z-[10000] hidden bg-black/40 backdrop-blur-sm items-center justify-center p-4">
        <div class="bg-white text-[#010205] w-full max-w-lg rounded-2xl shadow-2xl">
            <div class="px-6 pt-6 pb-4 border-b border-[#E5E7EB]">
                <h2 id="pd-cc-modal-title" class="font-semibold text-[20px]">Cookie preferences</h2>
                <p class="mt-1 text-[13px] text-[#6B7280]">Choose which cookies PrivacyDuck can set in your browser.</p>
            </div>
            <div class="px-6 py-4 space-y-4 max-h-[60vh] overflow-y-auto">
                <!-- Necessary (locked on) -->
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="font-semibold text-[15px]">Necessary</div>
                        <p class="mt-1 text-[13px] text-[#6B7280]">Required for login, account security, payment processing, and CSRF protection. Always on.</p>
                    </div>
                    <span class="inline-flex items-center shrink-0 select-none" aria-label="Necessary cookies are always on">
                        <span class="relative inline-block w-10 h-6 bg-[#24A556] rounded-full">
                            <span class="absolute top-1 right-1 w-4 h-4 bg-white rounded-full"></span>
                        </span>
                    </span>
                </div>
                <!-- Analytics -->
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="font-semibold text-[15px]">Analytics</div>
                        <p class="mt-1 text-[13px] text-[#6B7280]">Google Tag Manager + Google Analytics. Helps us see which pages are useful. No personal data sold.</p>
                    </div>
                    <label class="inline-flex items-center cursor-pointer shrink-0">
                        <input id="pd-cc-toggle-analytics" type="checkbox" class="sr-only pd-cc-toggle-input">
                        <span class="pd-cc-knob-wrap relative inline-block w-10 h-6 bg-[#D1D5DB] rounded-full transition-colors">
                            <span class="pd-cc-knob absolute top-1 left-1 w-4 h-4 bg-white rounded-full transition-transform"></span>
                        </span>
                    </label>
                </div>
                <!-- Functional -->
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="font-semibold text-[15px]">Functional (live chat)</div>
                        <p class="mt-1 text-[13px] text-[#6B7280]">Tawk.to live chat. Lets you message support directly from the site.</p>
                    </div>
                    <label class="inline-flex items-center cursor-pointer shrink-0">
                        <input id="pd-cc-toggle-functional" type="checkbox" class="sr-only pd-cc-toggle-input">
                        <span class="pd-cc-knob-wrap relative inline-block w-10 h-6 bg-[#D1D5DB] rounded-full transition-colors">
                            <span class="pd-cc-knob absolute top-1 left-1 w-4 h-4 bg-white rounded-full transition-transform"></span>
                        </span>
                    </label>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-[#E5E7EB] flex flex-col sm:flex-row gap-2 sm:justify-end">
                <button type="button" id="pd-cc-modal-reject" class="rounded-full border border-[#D1D5DB] hover:bg-[#F3F4F6] text-[#010205] font-semibold text-[14px] px-5 py-2.5">Reject non-essential</button>
                <button type="button" id="pd-cc-modal-save" class="rounded-full bg-[#24A556] hover:bg-[#1E8C49] text-white font-semibold text-[14px] px-5 py-2.5">Save preferences</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Toggle visuals (vanilla CSS so it works without Tailwind peer variants) */
    #pd-cc-modal .pd-cc-toggle-input:checked + .pd-cc-knob-wrap { background-color: #24A556; }
    #pd-cc-modal .pd-cc-toggle-input:checked + .pd-cc-knob-wrap .pd-cc-knob { transform: translateX(1rem); }
    #pd-cc-modal:not(.hidden) { display: flex; }
</style>

<script>
(function () {
    'use strict';
    var VERSION = 1;
    var STORAGE_KEY = 'pdcc:v' + VERSION;
    var COOKIE_NAME = 'pd_cc';
    var COOKIE_DAYS = 365;

    // Google Consent Mode v2 defaults — declared BEFORE any GTM/GA script loads
    // so even if a tag fires later it respects "denied" until we update.
    window.dataLayer = window.dataLayer || [];
    function gtag() { window.dataLayer.push(arguments); }
    gtag('consent', 'default', {
        'ad_storage': 'denied',
        'ad_user_data': 'denied',
        'ad_personalization': 'denied',
        'analytics_storage': 'denied',
        'functionality_storage': 'denied',
        'personalization_storage': 'denied',
        'security_storage': 'granted',
        'wait_for_update': 500
    });

    function readState() {
        try {
            var raw = localStorage.getItem(STORAGE_KEY);
            if (!raw) return null;
            var v = JSON.parse(raw);
            if (typeof v !== 'object' || v === null) return null;
            return v;
        } catch (e) { return null; }
    }

    function writeState(state) {
        state.v = VERSION;
        state.ts = Date.now();
        try { localStorage.setItem(STORAGE_KEY, JSON.stringify(state)); } catch (e) {}
        var maxAge = COOKIE_DAYS * 24 * 60 * 60;
        var b64 = '';
        try { b64 = btoa(JSON.stringify(state)); } catch (e) {}
        document.cookie = COOKIE_NAME + '=' + b64 + ';path=/;max-age=' + maxAge + ';samesite=Lax' + (location.protocol === 'https:' ? ';secure' : '');
    }

    function applyConsent(state) {
        // Update Google Consent Mode (in-flight if GTM/GA already loaded).
        gtag('consent', 'update', {
            'analytics_storage': state.analytics ? 'granted' : 'denied',
            'functionality_storage': state.functional ? 'granted' : 'denied'
        });
        if (state.analytics && !window.__pdGtmLoaded) loadGTM();
        if (state.functional && !window.__pdTawkLoaded) loadTawk();
        // Note: scripts already loaded cannot be UNLOADED in-flight; revoking
        // analytics/functional stops further data collection only after a
        // page reload. We surface this in the cookie policy.
    }

    function loadGTM() {
        window.__pdGtmLoaded = true;
        var s1 = document.createElement('script');
        s1.async = true;
        s1.src = 'https://www.googletagmanager.com/gtag/js?id=G-P6WKNFG8FS';
        document.head.appendChild(s1);
        (function (w, d, s, l, i) {
            w[l] = w[l] || []; w[l].push({ 'gtm.start': new Date().getTime(), event: 'gtm.js' });
            var f = d.getElementsByTagName(s)[0], j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true; j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-P679J6HD');
        gtag('js', new Date());
        gtag('config', 'G-P6WKNFG8FS');
    }

    function loadTawk() {
        window.__pdTawkLoaded = true;
        window.Tawk_API = window.Tawk_API || {};
        window.Tawk_LoadStart = new Date();
        var s1 = document.createElement('script');
        var s0 = document.getElementsByTagName('script')[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/6813761a7c6684190de59a7c/1iq60amh0';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    }

    function $(id) { return document.getElementById(id); }
    function showBanner() { var b = $('pd-cc-banner'); if (b) b.classList.remove('hidden'); }
    function hideBanner() { var b = $('pd-cc-banner'); if (b) b.classList.add('hidden'); }
    function openModal() {
        var current = readState() || { analytics: false, functional: false };
        $('pd-cc-toggle-analytics').checked = !!current.analytics;
        $('pd-cc-toggle-functional').checked = !!current.functional;
        $('pd-cc-modal').classList.remove('hidden');
    }
    function closeModal() { $('pd-cc-modal').classList.add('hidden'); }

    function decide(analytics, functional) {
        var state = { necessary: true, analytics: !!analytics, functional: !!functional };
        writeState(state);
        applyConsent(state);
        hideBanner();
        closeModal();
    }

    function attach() {
        $('pd-cc-accept').addEventListener('click', function () { decide(true, true); });
        $('pd-cc-reject').addEventListener('click', function () { decide(false, false); });
        $('pd-cc-customize').addEventListener('click', openModal);
        $('pd-cc-modal-save').addEventListener('click', function () {
            decide($('pd-cc-toggle-analytics').checked, $('pd-cc-toggle-functional').checked);
        });
        $('pd-cc-modal-reject').addEventListener('click', function () { decide(false, false); });

        var existing = readState();
        if (existing) applyConsent(existing);
        else showBanner();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', attach);
    } else {
        attach();
    }

    // Public API — wire a "Cookie settings" link in the footer to window.pdConsent.open()
    window.pdConsent = { open: openModal, get: readState, show: showBanner };
})();
</script>
<!-- END PrivacyDuck cookie consent -->
        <?php
    }
}
