<?php
isReverseLogin();

$err = isset($_GET['err']) ? (string) $_GET['err'] : '';
$prefillEmail = '';
if (isset($_GET['email'])) {
    $raw = trim((string) $_GET['email']);
    if ($raw !== '' && filter_var($raw, FILTER_VALIDATE_EMAIL)) {
        $prefillEmail = $raw;
    }
}

$meta_title = 'PrivacyDuck - Account setup';
$meta_description = 'Complete your PrivacyDuck account.';
$meta_url = 'https://privacyduck.com/new_signup';
$meta_image = 'https://privacyduck.com/assets/pageSEO/landing.jpg';

include_once(BASEPATH . '/src/common/meta.php');
main_head_start(['slim' => true]);
?>
<meta name="robots" content="noindex, nofollow">
<style>
    body {
        font-family: system-ui, -apple-system, sans-serif;
        /* Soft brand-tinted gradient backdrop - more premium than flat #f8fafc.
           Uses fixed attachment so it doesn't repaint on scroll on long forms. */
        background:
            radial-gradient(60% 50% at 80% 0%, rgba(36, 165, 86, 0.07), transparent 60%),
            radial-gradient(50% 40% at 0% 100%, rgba(119, 178, 72, 0.06), transparent 60%),
            #f6f8f5;
        background-attachment: fixed;
        min-height: 100vh;
    }
    /* Brand-green focus glow scoped to the signup form - overrides main.css's
       black-border rule that would otherwise hijack focus styling. */
    #ns-signup-form input[type="text"],
    #ns-signup-form input[type="email"],
    #ns-signup-form input[type="tel"],
    #ns-signup-form input[type="password"],
    #ns-signup-form input[type="date"],
    #ns-signup-form select,
    #ns-signup-form textarea {
        transition: border-color 200ms cubic-bezier(0.16, 1, 0.3, 1), box-shadow 200ms cubic-bezier(0.16, 1, 0.3, 1);
    }
    #ns-signup-form input[type="text"]:focus,
    #ns-signup-form input[type="email"]:focus,
    #ns-signup-form input[type="tel"]:focus,
    #ns-signup-form input[type="password"]:focus,
    #ns-signup-form input[type="date"]:focus,
    #ns-signup-form select:focus,
    #ns-signup-form textarea:focus {
        border: 1px solid #24A556 !important;
        box-shadow: 0 0 0 4px rgba(36, 165, 86, 0.16) !important;
        outline: none !important;
        --tw-ring-color: transparent !important;
    }
    /* Card lift on focus-within so the active form feels alive. */
    #ns-signup-form {
        transition: box-shadow 250ms cubic-bezier(0.16, 1, 0.3, 1), transform 250ms cubic-bezier(0.16, 1, 0.3, 1);
    }
    #ns-signup-form:focus-within {
        box-shadow: 0 24px 60px -24px rgba(16, 24, 40, 0.22), 0 4px 12px -4px rgba(16, 24, 40, 0.06);
    }
    /* Trust-strip icons get a slow drifting underglow */
    .ns-trust-item { transition: transform 300ms cubic-bezier(0.16, 1, 0.3, 1); }
    .ns-trust-item:hover { transform: translateY(-2px); }
</style>
<?php
main_head_end();
?>
<div class="mx-auto max-w-2xl px-4 py-10">
    <div class="mb-8 text-center" data-reveal="fade">
        <a href="/new" class="inline-block"><img class="mx-auto h-9 w-auto" src="/assets/image/desktop/logo4.svg" alt="PrivacyDuck"></a>
        <h1 class="mt-6 text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Create your <span class="pd-gradient-text">PrivacyDuck</span> account</h1>
        <p class="mt-2 text-sm text-slate-600 max-w-md mx-auto">Enter the personal details we use for removals. We will email you a code to verify your address. If you already paid through Stripe, your plan will be linked automatically.</p>
    </div>

    <!-- Trust strip -->
    <div class="mb-6 rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm" data-reveal data-reveal-delay="120">
        <div class="flex flex-wrap gap-3 sm:gap-5 items-center justify-center text-xs text-slate-700">
            <div class="ns-trust-item flex items-center gap-1.5">
                <svg class="w-4 h-4 text-[#24A556]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <span class="font-semibold">256-bit TLS</span>
            </div>
            <div class="ns-trust-item flex items-center gap-1.5">
                <svg class="w-4 h-4 text-[#24A556]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span class="font-semibold">UK GDPR aligned</span>
            </div>
            <div class="ns-trust-item flex items-center gap-1.5">
                <svg class="w-4 h-4 text-[#24A556]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                <span class="font-semibold">We never sell your data</span>
            </div>
            <div class="ns-trust-item flex items-center gap-1.5">
                <svg class="w-4 h-4 text-[#24A556]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="font-semibold">US team since 2019</span>
            </div>
        </div>
    </div>

    <?php if ($err !== ''): ?>
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <div id="ns-loading-overlay" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-[1px]" style="display: none;" aria-hidden="true" aria-busy="false">
        <div class="mx-4 flex min-w-[240px] flex-col items-center rounded-xl bg-white px-8 py-6 shadow-lg">
            <svg class="h-8 w-8 animate-spin text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="mt-4 text-center text-sm font-medium text-slate-800">Creating your session…</p>
            <p class="mt-1 text-center text-xs text-slate-500">Sending verification email if needed.</p>
        </div>
    </div>

    <form id="ns-signup-form" data-reveal="scale" data-reveal-delay="180" method="post" action="<?= htmlspecialchars(WEB_DOMAIN . '/new_signup_process', ENT_QUOTES, 'UTF-8'); ?>" class="relative space-y-4 rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
        <?= pd_csrf_field(); ?>
        <fieldset class="space-y-4 border-0 p-0 m-0">
            <legend class="text-sm font-semibold text-slate-800">Personal information</legend>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-xs font-medium text-slate-700">First name <span class="text-red-600">*</span></label>
                    <input name="firstname" type="text" required autocomplete="given-name" maxlength="200"
                        class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700">Last name <span class="text-red-600">*</span></label>
                    <input name="lastname" type="text" required autocomplete="family-name" maxlength="200"
                        class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600" />
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-700">Country <span class="text-red-600">*</span></label>
                <select name="country" required class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600">
                    <option value="US" selected>United States</option>
                    <option value="UK">United Kingdom</option>
                    <option value="CA">Canada</option>
                    <option value="EU">European Union</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-700">Street address <span class="text-red-600">*</span></label>
                <input name="address" type="text" required autocomplete="street-address" maxlength="500"
                    placeholder="Street, apartment, suite, etc."
                    class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600" />
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-xs font-medium text-slate-700">City <span class="text-red-600">*</span></label>
                    <input name="city" type="text" required autocomplete="address-level2" maxlength="120"
                        class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700">State / province / region <span class="text-red-600">*</span></label>
                    <input name="state" type="text" required autocomplete="address-level1" maxlength="120"
                        class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600" />
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-700">ZIP / postal code <span class="text-red-600">*</span></label>
                <input name="zip" type="text" required autocomplete="postal-code" maxlength="32"
                    class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600" />
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-700">Name &amp; address variations <span class="text-slate-500 font-normal">(optional)</span></label>
                <textarea name="name_variations" rows="3" maxlength="4000" placeholder="Up to five alternate spellings, maiden names, or address variants, separated by commas."
                    class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600"></textarea>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-700">Date of birth <span class="text-red-600">*</span></label>
                <input name="birth_date" type="date" required max="<?= htmlspecialchars(date('Y-m-d'), ENT_QUOTES, 'UTF-8'); ?>"
                    class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600" />
            </div>
        </fieldset>

        <div class="border-t border-slate-200 pt-4 space-y-4">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Account</p>
        <div>
            <label class="block text-xs font-medium text-slate-700">Email <span class="text-red-600">*</span></label>
            <input name="email" type="email" required autocomplete="email"
                value="<?= htmlspecialchars($prefillEmail, ENT_QUOTES, 'UTF-8'); ?>"
                class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600" />
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <div>
                <label class="block text-xs font-medium text-slate-700">Phone country <span class="text-red-600">*</span></label>
                <select name="phone_country" required class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600">
                    <option value="US">United States (+1)</option>
                    <option value="CA">Canada (+1)</option>
                    <option value="UK">United Kingdom</option>
                    <option value="FR">France</option>
                    <option value="DE">Germany</option>
                    <option value="ES">Spain</option>
                    <option value="IT">Italy</option>
                    <option value="NL">Netherlands</option>
                    <option value="SE">Sweden</option>
                </select>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs font-medium text-slate-700">Phone number <span class="text-red-600">*</span></label>
                <input name="phone" type="tel" required autocomplete="tel" inputmode="tel"
                    placeholder="Include area code"
                    class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600" />
            </div>
        </div>

        <div>
            <label class="block text-xs font-medium text-slate-700">Password <span class="text-red-600">*</span></label>
            <div class="relative mt-1">
                <input
                    id="ns-password"
                    name="password"
                    type="password"
                    required
                    autocomplete="new-password"
                    minlength="8"
                    aria-describedby="ns-pw-strength-label"
                    class="w-full rounded-md border border-slate-300 px-3 pr-10 py-2 text-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600"
                />
                <button
                    type="button"
                    id="ns-toggle-password"
                    class="absolute inset-y-0 right-0 flex items-center px-3 text-sm text-slate-500 hover:text-slate-700"
                    aria-label="Show or hide password"
                >
                    <svg id="ns-eye-open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    <svg id="ns-eye-closed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a21.77 21.77 0 0 1 5.06-6.87" />
                        <path d="M9.9 4.24A10.94 10.94 0 0 1 12 4c7 0 11 8 11 8a21.77 21.77 0 0 1-3.2 4.64" />
                        <path d="M1 1l22 22" />
                        <path d="M10.73 10.73a2 2 0 0 0 2.83 2.83" />
                    </svg>
                </button>
            </div>
            <!-- Strength meter: 4 segments + label. Updated by JS below. -->
            <div class="mt-2">
                <div class="flex gap-1" aria-hidden="true">
                    <div data-ns-pw-bar="1" class="h-1 flex-1 rounded-full bg-slate-200 transition-colors"></div>
                    <div data-ns-pw-bar="2" class="h-1 flex-1 rounded-full bg-slate-200 transition-colors"></div>
                    <div data-ns-pw-bar="3" class="h-1 flex-1 rounded-full bg-slate-200 transition-colors"></div>
                    <div data-ns-pw-bar="4" class="h-1 flex-1 rounded-full bg-slate-200 transition-colors"></div>
                </div>
                <p id="ns-pw-strength-label" class="mt-1 text-xs text-slate-500" aria-live="polite">
                    At least 8 characters. Mix upper + lower + digits + symbols for a stronger password.
                </p>
            </div>
        </div>

        <div>
            <label class="block text-xs font-medium text-slate-700">Retype password <span class="text-red-600">*</span></label>
            <div class="relative mt-1">
                <input
                    id="ns-password-confirm"
                    name="password_confirm"
                    type="password"
                    required
                    autocomplete="new-password"
                    minlength="8"
                    class="w-full rounded-md border border-slate-300 px-3 pr-10 py-2 text-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600"
                />
                <button
                    type="button"
                    id="ns-toggle-password-confirm"
                    class="absolute inset-y-0 right-0 flex items-center px-3 text-sm text-slate-500 hover:text-slate-700"
                    aria-label="Show or hide retype password"
                >
                    <svg id="ns-eye-open-confirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    <svg id="ns-eye-closed-confirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a21.77 21.77 0 0 1 5.06-6.87" />
                        <path d="M9.9 4.24A10.94 10.94 0 0 1 12 4c7 0 11 8 11 8a21.77 21.77 0 0 1-3.2 4.64" />
                        <path d="M1 1l22 22" />
                        <path d="M10.73 10.73a2 2 0 0 0 2.83 2.83" />
                    </svg>
                </button>
            </div>
            <p id="ns-pw-match-hint" class="mt-1 text-xs text-slate-500"></p>
        </div>
        </div>

        <div class="mt-2 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
            <label class="flex items-start gap-3">
                <input
                    type="checkbox"
                    name="agree_terms"
                    value="1"
                    required
                    class="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-600"
                />
                <span class="text-sm text-slate-700 leading-relaxed">
                    I confirm I have read the <a href="/policy" target="_blank" rel="noopener" class="font-semibold text-emerald-700 hover:underline">Privacy Policy</a> and
                    <a href="/cookie-policy" target="_blank" rel="noopener" class="font-semibold text-emerald-700 hover:underline">Cookie Policy</a>.
                </span>
            </label>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white px-4 py-3">
            <label class="flex items-start gap-3">
                <input
                    type="checkbox"
                    name="agree_marketing"
                    value="1"
                    class="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-600"
                />
                <span class="text-sm text-slate-700 leading-relaxed">
                    I'd like to receive product updates, tips, and special offers by email. I can unsubscribe at any time.
                </span>
            </label>
        </div>

        <button type="submit"
            class="pd-btn-press pd-shine mt-4 w-full rounded-xl border border-emerald-700 bg-gradient-to-r from-[#77B248] to-[#24A556] py-3 text-sm font-semibold text-white shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 inline-flex items-center justify-center gap-2">
            Create my account
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12H19M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        </button>
        <p class="text-center text-sm text-slate-600">
            Already have an account?
            <a href="/new_signin" class="font-semibold text-emerald-700 hover:underline">Sign in</a>
        </p>
    </form>
</div>
<script>
    (function() {
        var pw = document.getElementById('ns-password');
        var pw2 = document.getElementById('ns-password-confirm');
        var hint = document.getElementById('ns-pw-match-hint');
        function setHint() {
            if (!hint || !pw || !pw2) return;
            if (!pw2.value) {
                hint.textContent = '';
                hint.classList.remove('text-red-500');
                hint.classList.remove('text-emerald-700');
                hint.classList.add('text-slate-500');
                return;
            }
            if (pw.value === pw2.value) {
                hint.textContent = 'Passwords match.';
                hint.classList.remove('text-red-500');
                hint.classList.remove('text-slate-500');
                hint.classList.add('text-emerald-700');
            } else {
                hint.textContent = 'Passwords do not match.';
                hint.classList.remove('text-emerald-700');
                hint.classList.add('text-red-500');
            }
        }

        if (pw && pw2) {
            pw.addEventListener('input', setHint);
            pw2.addEventListener('input', setHint);
            setHint();
        }

        // Password strength meter. Score 0-4 (none/weak/fair/good/strong) drives
        // colored segment count + label text. Algorithm: starts at 0, +1 for
        // length>=8, +1 for length>=12, +1 if mixed case, +1 if digits,
        // +1 if symbols, capped at 4.
        var pwBars = Array.prototype.slice.call(document.querySelectorAll('[data-ns-pw-bar]'));
        var pwLabel = document.getElementById('ns-pw-strength-label');
        var BAR_COLORS = ['bg-slate-200', 'bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-emerald-500'];
        var LABELS = [
            { text: 'At least 8 characters. Mix upper + lower + digits + symbols for a stronger password.', cls: 'text-slate-500' },
            { text: 'Weak - too short or one character class.',  cls: 'text-red-500' },
            { text: 'Fair - add more variety (case, digits, symbols).', cls: 'text-orange-500' },
            { text: 'Good - a bit longer or one more class would be great.', cls: 'text-yellow-600' },
            { text: 'Strong password.', cls: 'text-emerald-700' }
        ];
        function scorePassword(s) {
            if (!s) return 0;
            var score = 0;
            if (s.length >= 8)  score++;
            if (s.length >= 12) score++;
            if (/[a-z]/.test(s) && /[A-Z]/.test(s)) score++;
            if (/[0-9]/.test(s)) score++;
            if (/[^A-Za-z0-9]/.test(s)) score++;
            // Penalty if too short overall - never give >1 if length<8.
            if (s.length < 8) score = Math.min(score, 1);
            return Math.min(score, 4);
        }
        function paintStrength() {
            if (!pw || !pwLabel || pwBars.length === 0) return;
            var score = scorePassword(pw.value);
            pwBars.forEach(function (bar, i) {
                ['bg-slate-200','bg-red-500','bg-orange-500','bg-yellow-500','bg-emerald-500'].forEach(function (c) { bar.classList.remove(c); });
                bar.classList.add(i < score ? BAR_COLORS[score] : 'bg-slate-200');
            });
            ['text-slate-500','text-red-500','text-orange-500','text-yellow-600','text-emerald-700'].forEach(function (c) { pwLabel.classList.remove(c); });
            pwLabel.textContent = LABELS[score].text;
            pwLabel.classList.add(LABELS[score].cls);
        }
        if (pw) {
            pw.addEventListener('input', paintStrength);
            paintStrength();
        }

        function wireToggle(btnId, inputId, openId, closedId) {
            var btn = document.getElementById(btnId);
            var input = document.getElementById(inputId);
            var open = document.getElementById(openId);
            var closed = document.getElementById(closedId);
            if (!btn || !input || !open || !closed) return;

            btn.addEventListener('click', function() {
                var isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                open.classList.toggle('hidden', !isPassword);
                closed.classList.toggle('hidden', isPassword);
            });
        }

        wireToggle('ns-toggle-password', 'ns-password', 'ns-eye-open', 'ns-eye-closed');
        wireToggle('ns-toggle-password-confirm', 'ns-password-confirm', 'ns-eye-open-confirm', 'ns-eye-closed-confirm');

        var form = document.getElementById('ns-signup-form');
        var overlay = document.getElementById('ns-loading-overlay');
        if (form && overlay) {
            form.addEventListener('submit', function() {
                overlay.style.display = 'flex';
                overlay.setAttribute('aria-hidden', 'false');
                overlay.setAttribute('aria-busy', 'true');
            });
        }
    })();
</script>
<?php
no_footer(['skip_tawk' => true]);
