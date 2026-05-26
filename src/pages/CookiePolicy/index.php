<?php
$meta_title = "Cookie Policy | PrivacyDuck";
$meta_description = "Every cookie PrivacyDuck sets — what it does, how long it lasts, who set it, and how to control it. UK GDPR + PECR compliant.";
$meta_url = "https://privacyduck.com/cookie-policy";
$meta_image = "https://privacyduck.com/assets/pageSEO/landing.jpg";

include_once(BASEPATH . "/src/common/meta.php");
main_head_start();
main_head_end();
main_header("black");
?>
<div class="px-[16px] sm:pl-[80px] sm:pr-[48px] pt-[149px] pb-[70px] lg:pt-[128px] lg:pb-0 bg-[#FAFAFA] leading-[1.6em]">
    <main class="px-6 py-12 max-w-5xl mx-auto">
        <!-- Title -->
        <div class="mb-10">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Cookie Policy</h1>
            <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-6 space-y-2 sm:space-y-0 text-sm text-gray-600">
                <div class="flex items-center space-x-2"><span class="font-medium">Effective Date:</span><span>May 26, 2026</span></div>
                <div class="flex items-center space-x-2"><span class="font-medium">Last Updated:</span><span>May 26, 2026</span></div>
            </div>
        </div>

        <div class="space-y-10">

            <!-- Intro -->
            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">What is a cookie?</h2>
                <p class="text-gray-700">
                    A cookie is a small text file a website stores on your device. We use cookies in three categories: <strong>Necessary</strong>, <strong>Analytics</strong>, and <strong>Functional</strong>. You control which we set via the cookie banner shown on first visit, or via the <strong>Cookie Settings</strong> link in the footer.
                </p>
                <p class="text-gray-700 mt-3">
                    You can also block or delete cookies in your browser settings. Blocking Necessary cookies will break login, payment, and security features.
                </p>
            </section>

            <!-- How we ask for consent -->
            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">How we ask for consent</h2>
                <p class="text-gray-700">
                    On your first visit you'll see a banner with three options: <strong>Accept all</strong>, <strong>Reject non-essential</strong>, <strong>Customize</strong>.
                    Necessary cookies are always set (they're required to operate the site). Analytics and Functional cookies are set only after you opt in.
                    Your choice is stored for 12 months, after which we re-prompt.
                </p>
            </section>

            <!-- Necessary -->
            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                    <span class="bg-[#24A556] text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold mr-3">N</span>
                    Necessary (always on)
                </h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left border border-gray-200">
                        <thead class="bg-gray-100 text-gray-900 font-semibold">
                            <tr><th class="px-4 py-2 border-b">Cookie</th><th class="px-4 py-2 border-b">Purpose</th><th class="px-4 py-2 border-b">Lifetime</th><th class="px-4 py-2 border-b">Set by</th></tr>
                        </thead>
                        <tbody class="text-gray-700">
                            <tr><td class="px-4 py-2 border-b font-mono">PHPSESSID</td><td class="px-4 py-2 border-b">Session identifier — keeps you logged in</td><td class="px-4 py-2 border-b">Session (cleared when browser closes)</td><td class="px-4 py-2 border-b">PrivacyDuck</td></tr>
                            <tr><td class="px-4 py-2 border-b font-mono">pd_cc</td><td class="px-4 py-2 border-b">Stores your cookie preferences</td><td class="px-4 py-2 border-b">12 months</td><td class="px-4 py-2 border-b">PrivacyDuck</td></tr>
                            <tr><td class="px-4 py-2 border-b font-mono">__stripe_mid</td><td class="px-4 py-2 border-b">Fraud prevention on payment pages</td><td class="px-4 py-2 border-b">1 year</td><td class="px-4 py-2 border-b">Stripe</td></tr>
                            <tr><td class="px-4 py-2 font-mono">__stripe_sid</td><td class="px-4 py-2">Fraud prevention on payment pages</td><td class="px-4 py-2">30 minutes</td><td class="px-4 py-2">Stripe</td></tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Analytics -->
            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                    <span class="bg-[#24A556] text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold mr-3">A</span>
                    Analytics (opt-in)
                </h2>
                <p class="text-gray-700 mb-4">Loaded only after you opt in via the banner. We use Google Tag Manager (container <span class="font-mono text-sm">GTM-P679J6HD</span>) to load Google Analytics 4 (stream <span class="font-mono text-sm">G-P6WKNFG8FS</span>).</p>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left border border-gray-200">
                        <thead class="bg-gray-100 text-gray-900 font-semibold">
                            <tr><th class="px-4 py-2 border-b">Cookie</th><th class="px-4 py-2 border-b">Purpose</th><th class="px-4 py-2 border-b">Lifetime</th><th class="px-4 py-2 border-b">Set by</th></tr>
                        </thead>
                        <tbody class="text-gray-700">
                            <tr><td class="px-4 py-2 border-b font-mono">_ga</td><td class="px-4 py-2 border-b">Distinguishes unique visitors</td><td class="px-4 py-2 border-b">2 years</td><td class="px-4 py-2 border-b">Google Analytics</td></tr>
                            <tr><td class="px-4 py-2 border-b font-mono">_ga_P6WKNFG8FS</td><td class="px-4 py-2 border-b">Session state for our GA4 stream</td><td class="px-4 py-2 border-b">2 years</td><td class="px-4 py-2 border-b">Google Analytics</td></tr>
                            <tr><td class="px-4 py-2 border-b font-mono">_gid</td><td class="px-4 py-2 border-b">Distinguishes visitors over 24 hours</td><td class="px-4 py-2 border-b">24 hours</td><td class="px-4 py-2 border-b">Google Analytics</td></tr>
                            <tr><td class="px-4 py-2 font-mono">_gat</td><td class="px-4 py-2">Throttles request rate</td><td class="px-4 py-2">1 minute</td><td class="px-4 py-2">Google Analytics</td></tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Functional -->
            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                    <span class="bg-[#24A556] text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold mr-3">F</span>
                    Functional (opt-in)
                </h2>
                <p class="text-gray-700 mb-4">Loaded only after you opt in. Used to power the Tawk.to live chat widget on every page.</p>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left border border-gray-200">
                        <thead class="bg-gray-100 text-gray-900 font-semibold">
                            <tr><th class="px-4 py-2 border-b">Cookie</th><th class="px-4 py-2 border-b">Purpose</th><th class="px-4 py-2 border-b">Lifetime</th><th class="px-4 py-2 border-b">Set by</th></tr>
                        </thead>
                        <tbody class="text-gray-700">
                            <tr><td class="px-4 py-2 border-b font-mono">__tawkuuid</td><td class="px-4 py-2 border-b">Persists your chat conversation across pages</td><td class="px-4 py-2 border-b">6 months</td><td class="px-4 py-2 border-b">Tawk.to</td></tr>
                            <tr><td class="px-4 py-2 border-b font-mono">TawkConnectionTime</td><td class="px-4 py-2 border-b">Tracks chat session start</td><td class="px-4 py-2 border-b">Session</td><td class="px-4 py-2 border-b">Tawk.to</td></tr>
                            <tr><td class="px-4 py-2 font-mono">Tawk_*</td><td class="px-4 py-2">Chat interface state (visitor preferences, message store)</td><td class="px-4 py-2">Session to 1 year</td><td class="px-4 py-2">Tawk.to</td></tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Withdrawal -->
            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Withdrawing consent</h2>
                <p class="text-gray-700 mb-3">
                    Click <strong>Cookie Settings</strong> in the footer of any page, toggle off Analytics or Functional, and click <strong>Save preferences</strong>.
                </p>
                <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-md p-4 mb-3">
                    <p class="text-gray-800 text-sm">
                        Cookies already set in your browser will remain until they expire. To remove them immediately, use your browser settings:
                    </p>
                </div>
                <ul class="list-disc list-inside space-y-1 text-gray-700 ml-4 text-sm">
                    <li><strong>Chrome:</strong> Settings → Privacy and security → Clear browsing data → Cookies and other site data</li>
                    <li><strong>Firefox:</strong> Settings → Privacy &amp; Security → Cookies and Site Data → Clear Data</li>
                    <li><strong>Safari:</strong> Settings → Privacy → Manage Website Data → Remove</li>
                    <li><strong>Edge:</strong> Settings → Cookies and site permissions → Manage and delete cookies and site data</li>
                </ul>
            </section>

            <!-- Third-party policies -->
            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Third-party cookie policies</h2>
                <p class="text-gray-700 mb-3">For details directly from each provider:</p>
                <ul class="list-disc list-inside space-y-1 text-gray-700 ml-4">
                    <li>Google: <a href="https://policies.google.com/technologies/cookies" target="_blank" rel="noopener" class="text-[#24A556] font-medium hover:underline">https://policies.google.com/technologies/cookies</a></li>
                    <li>Stripe: <a href="https://stripe.com/cookie-settings" target="_blank" rel="noopener" class="text-[#24A556] font-medium hover:underline">https://stripe.com/cookie-settings</a></li>
                    <li>Tawk.to: <a href="https://www.tawk.to/cookie-policy" target="_blank" rel="noopener" class="text-[#24A556] font-medium hover:underline">https://www.tawk.to/cookie-policy</a></li>
                </ul>
            </section>

            <!-- Updates -->
            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Updates</h2>
                <p class="text-gray-700">
                    Material changes to this policy will be posted here and trigger a re-prompt of the cookie banner so you can re-confirm your choices.
                </p>
            </section>

            <!-- Contact -->
            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Contact</h2>
                <p class="text-gray-700">
                    Questions about cookies or this policy:
                    <a href="mailto:privacy@privacyduck.com" class="text-[#24A556] font-medium hover:underline">privacy@privacyduck.com</a>.
                </p>
            </section>

        </div>
    </main>
</div>
<?php main_footer(); ?>
