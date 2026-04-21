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
main_head_start();
?>
<meta name="robots" content="noindex, nofollow">
<style>
    body { font-family: system-ui, -apple-system, sans-serif; background: #f8fafc; }
</style>
<?php
main_head_end();
?>
<div class="mx-auto max-w-lg px-4 py-12">
    <div class="mb-8 text-center">
        <a href="/new" class="inline-block"><img class="mx-auto h-9 w-auto" src="/assets/image/desktop/logo4.svg" alt="PrivacyDuck"></a>
        <h1 class="mt-6 text-xl font-semibold text-slate-900">Create your account</h1>
        <p class="mt-2 text-sm text-slate-600">Create your account with email and password. If you already paid through Stripe, your plan will be linked automatically.</p>
    </div>

    <?php if ($err !== ''): ?>
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" action="<?= htmlspecialchars(WEB_DOMAIN . '/new_signup_process', ENT_QUOTES, 'UTF-8'); ?>" class="space-y-4 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <div>
            <label class="block text-xs font-medium text-slate-700">Face Photo <span class="text-red-600">*</span></label>
            <div class="mt-1">
                <label for="ns-face-photo"
                    class="group block w-full max-w-[220px] mx-auto overflow-hidden rounded-xl border border-slate-300 bg-slate-50 hover:bg-slate-100 focus-within:ring-1 focus-within:ring-emerald-600 cursor-pointer">
                    <div style="aspect-ratio: 1 / 1;" class="w-full flex items-center justify-center relative">
                        <img id="ns-face-preview-img" alt="Face preview" class="hidden absolute inset-0 w-full h-full object-cover" />
                        <div id="ns-face-preview-placeholder" class="p-6 text-center">
                            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-white text-slate-500 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 3a4 4 0 0 1 4 4c0 1.66-1.34 3-3 3h-2c-1.66 0-3-1.34-3-3a4 4 0 0 1 4-4z" />
                                    <path d="M4 21a8 8 0 0 1 16 0" />
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-emerald-700">Choose a face photo</p>
                            <p class="mt-1 text-xs text-slate-500">Square preview. Max 5MB.</p>
                        </div>
                        <div class="pointer-events-none absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/55 to-transparent px-4 py-3 opacity-0 group-hover:opacity-100 transition-opacity">
                            <p class="text-xs font-semibold text-white">Click to change</p>
                        </div>
                    </div>
                </label>
                <input
                    name="face_photo"
                    id="ns-face-photo"
                    type="file"
                    accept="image/*"
                    required
                    class="hidden"
                />
            </div>
            <p class="mt-1 text-xs text-slate-500">Upload a clear face image for PimEyes/manual removal work.</p>
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-700">Email <span class="text-red-600">*</span></label>
            <input name="email" type="email" required autocomplete="email"
                value="<?= htmlspecialchars($prefillEmail, ENT_QUOTES, 'UTF-8'); ?>"
                class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600" />
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
        var faceInput = document.getElementById('ns-face-photo');
        var faceImg = document.getElementById('ns-face-preview-img');
        var facePlaceholder = document.getElementById('ns-face-preview-placeholder');

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

        if (faceInput && faceImg && facePlaceholder) {
            faceInput.addEventListener('change', function() {
                var f = faceInput.files && faceInput.files[0] ? faceInput.files[0] : null;
                if (!f) return;
                if (!f.type || f.type.indexOf('image/') !== 0) return;
                var url = URL.createObjectURL(f);
                faceImg.src = url;
                faceImg.classList.remove('hidden');
                facePlaceholder.classList.add('hidden');
            });
        }
    })();
</script>
<?php
no_footer();
