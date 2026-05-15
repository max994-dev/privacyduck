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

$meta_title = 'PrivacyDuck — Account setup';
$meta_description = 'Complete your PrivacyDuck account.';
$meta_url = 'https://privacyduck.com/new_signup';
$meta_image = 'https://privacyduck.com/assets/pageSEO/landing.jpg';

include_once(BASEPATH . '/src/common/meta.php');
main_head_start(['slim' => true]);
?>
<meta name="robots" content="noindex, nofollow">
<style>
    body { font-family: system-ui, -apple-system, sans-serif; background: #f8fafc; }
</style>
<?php
main_head_end();
?>
<div class="mx-auto max-w-2xl px-4 py-10">
    <div class="mb-8 text-center">
        <a href="/new" class="inline-block"><img class="mx-auto h-9 w-auto" src="/assets/image/desktop/logo4.svg" alt="PrivacyDuck"></a>
        <h1 class="mt-6 text-xl font-semibold text-slate-900">Create your account</h1>
        <p class="mt-2 text-sm text-slate-600">Enter the personal details we use for removals (similar to our legacy signup). We will email you a code to verify your address. If you already paid through Stripe, your plan will be linked automatically.</p>
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

    <form id="ns-signup-form" method="post" action="<?= htmlspecialchars(WEB_DOMAIN . '/new_signup_process', ENT_QUOTES, 'UTF-8'); ?>" class="relative space-y-4 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
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
            <p class="mt-1 text-xs text-slate-500">At least 8 characters.</p>
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
                    I agree to the <a href="/policy" class="font-semibold text-emerald-700 hover:underline">Privacy Policy</a> and
                    <a href="/policy" class="font-semibold text-emerald-700 hover:underline">Terms of Service</a>.
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
                    I agree to receive marketing emails from PrivacyDuck.
                </span>
            </label>
        </div>

        <button type="submit"
            class="mt-4 w-full rounded-lg bg-emerald-600 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
            Sign up
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
