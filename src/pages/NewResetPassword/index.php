<?php
isReverseLogin();

if (empty($_SESSION['password_reset_allowed']) || empty($_SESSION['password_reset_email'])
    || !is_string($_SESSION['password_reset_email'])) {
    header('Location: ' . WEB_DOMAIN . '/new_signin');
    exit;
}

$resetEmail = $_SESSION['password_reset_email'];

$meta_title = 'PrivacyDuck — Reset password';
$meta_description = 'Set a new password for your PrivacyDuck account.';
$meta_url = 'https://privacyduck.com/new_reset_password';
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
        <h1 class="mt-6 text-xl font-semibold text-slate-900">Reset password</h1>
        <p class="mt-2 text-sm text-slate-600">Choose a new password for <span class="font-medium text-slate-800"><?= htmlspecialchars($resetEmail); ?></span>.</p>
    </div>

    <div id="nrp-err" class="hidden mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"></div>

    <div class="space-y-4 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <div>
            <label class="block text-xs font-medium text-slate-700">New password <span class="text-red-600">*</span></label>
            <div class="relative mt-1">
                <input
                    id="nrp-password"
                    type="password"
                    autocomplete="new-password"
                    class="w-full rounded-md border border-slate-300 px-3 pr-10 py-2 text-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600"
                />
                <button
                    type="button"
                    id="nrp-toggle-password"
                    class="absolute inset-y-0 right-0 flex items-center px-3 text-sm text-slate-500 hover:text-slate-700"
                    aria-label="Show or hide password"
                >
                    <svg id="nrp-eye-open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    <svg id="nrp-eye-closed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a21.77 21.77 0 0 1 5.06-6.87" />
                        <path d="M9.9 4.24A10.94 10.94 0 0 1 12 4c7 0 11 8 11 8a21.77 21.77 0 0 1-3.2 4.64" />
                        <path d="M1 1l22 22" />
                    </svg>
                </button>
            </div>
            <p class="mt-1 text-xs text-slate-500">At least 8 characters.</p>
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-700">Confirm password <span class="text-red-600">*</span></label>
            <input
                id="nrp-password-confirm"
                type="password"
                autocomplete="new-password"
                class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600"
            />
        </div>

        <button type="button" id="nrp-btn-save"
            class="mt-2 w-full rounded-lg bg-emerald-600 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
            Update password
        </button>

        <p class="text-center text-sm text-slate-600 pt-2">
            <a href="/new_signin" class="font-semibold text-emerald-700 hover:underline">Back to sign in</a>
        </p>
    </div>
</div>
<script>
(function() {
    function apiUrl(path) {
        return path.indexOf('/') === 0 ? path : '/' + path;
    }
    function goTo(u) {
        if (!u) return;
        if (u.indexOf('http') === 0) {
            window.location.href = u;
            return;
        }
        window.location.href = u.charAt(0) === '/' ? u : '/' + u;
    }
    var err = document.getElementById('nrp-err');
    function showErr(msg) {
        err.textContent = msg || '';
        err.classList.toggle('hidden', !msg);
    }
    document.getElementById('nrp-btn-save').addEventListener('click', function() {
        var p = document.getElementById('nrp-password').value || '';
        var c = document.getElementById('nrp-password-confirm').value || '';
        showErr('');
        if (p.length < 8) {
            showErr('Password must be at least 8 characters.');
            return;
        }
        if (p !== c) {
            showErr('Passwords do not match.');
            return;
        }
        fetch(apiUrl('/new_reset_password_save'), {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
            body: new URLSearchParams({ password: p, password_confirm: c }),
            credentials: 'same-origin'
        }).then(function(res) {
            var ct = res.headers.get('content-type') || '';
            if (!ct.includes('application/json')) {
                return res.text().then(function(t) {
                    throw new Error(t ? t.slice(0, 200) : 'Server error');
                });
            }
            return res.json().then(function(data) {
                if (data && data.error) {
                    throw new Error(data.error);
                }
                if (!res.ok) {
                    throw new Error('Request failed (' + res.status + ').');
                }
                return data;
            });
        }).then(function(res) {
            if (res.redirect) {
                goTo(res.redirect);
            }
        }).catch(function(e) {
            showErr(e.message || 'Request failed.');
        });
    });

    (function() {
        var btn = document.getElementById('nrp-toggle-password');
        var input = document.getElementById('nrp-password');
        var open = document.getElementById('nrp-eye-open');
        var closed = document.getElementById('nrp-eye-closed');
        if (!btn || !input || !open || !closed) return;
        btn.addEventListener('click', function() {
            var isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            open.classList.toggle('hidden', !isPassword);
            closed.classList.toggle('hidden', isPassword);
        });
    })();
})();
</script>
<?php
no_footer();

