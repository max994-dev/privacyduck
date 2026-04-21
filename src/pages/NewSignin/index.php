<?php
isReverseLogin();

$meta_title = 'PrivacyDuck — Sign in';
$meta_description = 'Sign in to PrivacyDuck.';
$meta_url = 'https://privacyduck.com/new_signin';
$meta_image = 'https://privacyduck.com/assets/pageSEO/landing.jpg';

include_once(BASEPATH . '/src/common/meta.php');
main_head_start();
?>
<meta name="robots" content="noindex, nofollow">
<style>
    body { font-family: system-ui, -apple-system, sans-serif; background: #f8fafc; }
    body.ns-signin-loading { overflow: hidden; }
    #ns-loading-overlay {
        background: rgba(15, 23, 42, 0.35);
        backdrop-filter: blur(2px);
    }
    .ns-spinner {
        width: 44px;
        height: 44px;
        border: 3px solid rgba(5, 150, 105, 0.2);
        border-top-color: rgb(5, 150, 105);
        border-radius: 50%;
        animation: ns-spin 0.65s linear infinite;
    }
    @keyframes ns-spin { to { transform: rotate(360deg); } }
    @media (prefers-reduced-motion: reduce) {
        .ns-spinner { animation-duration: 1.4s; }
    }
</style>
<?php
main_head_end();
?>
<div class="mx-auto max-w-lg px-4 py-12">
    <div class="mb-8 text-center">
        <a href="/new" class="inline-block"><img class="mx-auto h-9 w-auto" src="/assets/image/desktop/logo4.svg" alt="PrivacyDuck"></a>
        <h1 class="mt-6 text-xl font-semibold text-slate-900">Sign in</h1>
        <p class="mt-2 text-sm text-slate-600">Use your email and password, or request a login code.</p>
    </div>

    <div id="new-signin-err" class="hidden mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"></div>
    <div id="new-signin-ok" class="hidden mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900"></div>

    <div class="space-y-4 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <div>
            <label class="block text-xs font-medium text-slate-700">Email <span class="text-red-600">*</span></label>
            <input id="ns-email" type="email" autocomplete="email"
                class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600" />
        </div>
        <div>
            <div class="flex items-center justify-between gap-2">
                <label class="block text-xs font-medium text-slate-700">Password <span class="text-red-600">*</span></label>
                <button type="button" id="ns-reset-password"
                    class="text-xs font-semibold text-emerald-700 hover:underline focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 rounded">
                    Reset password
                </button>
            </div>
            <div class="relative mt-1">
                <input
                    id="ns-password"
                    type="password"
                    autocomplete="current-password"
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
                    </svg>
                </button>
            </div>
        </div>

        <button type="button" id="ns-btn-password"
            class="mt-2 w-full rounded-lg bg-emerald-600 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
            Login with password
        </button>

        <div class="flex items-center gap-3 py-2">
            <div class="h-px flex-1 bg-slate-200"></div>
            <span class="text-xs font-medium uppercase tracking-wide text-slate-500">Or</span>
            <div class="h-px flex-1 bg-slate-200"></div>
        </div>

        <button type="button" id="ns-btn-code"
            class="w-full rounded-lg border-2 border-emerald-600 py-2.5 text-sm font-semibold text-emerald-700 hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
            Email me login code
        </button>

        <p class="text-center text-sm text-slate-600 pt-2">
            Don&apos;t have an account?
            <a href="/new_signup" class="font-semibold text-emerald-700 hover:underline">Sign up</a>
        </p>
    </div>
</div>

<div id="ns-loading-overlay" class="hidden fixed inset-0 z-[200] flex items-center justify-center" role="status" aria-live="polite" aria-busy="false" aria-hidden="true">
    <div class="rounded-2xl bg-white px-8 py-6 shadow-xl flex flex-col items-center gap-4">
        <div class="ns-spinner" aria-hidden="true"></div>
        <p class="text-sm font-medium text-slate-700 m-0">Signing you in…</p>
    </div>
</div>

<script>
(function() {
    /** Same-origin paths only — do not use WEB_DOMAIN here: www vs apex breaks fetch() without CORS. */
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
    var err = document.getElementById('new-signin-err');
    var ok = document.getElementById('new-signin-ok');
    var loadingOverlay = document.getElementById('ns-loading-overlay');
    function setLoading(on, message) {
        if (!loadingOverlay) return;
        var msgEl = loadingOverlay.querySelector('p');
        if (msgEl && message) {
            msgEl.textContent = message;
        } else if (msgEl && !on) {
            msgEl.textContent = 'Signing you in…';
        }
        loadingOverlay.classList.toggle('hidden', !on);
        loadingOverlay.setAttribute('aria-busy', on ? 'true' : 'false');
        loadingOverlay.setAttribute('aria-hidden', on ? 'false' : 'true');
        document.body.classList.toggle('ns-signin-loading', !!on);
    }
    function showErr(msg) {
        err.textContent = msg || '';
        err.classList.toggle('hidden', !msg);
        if (msg) ok.classList.add('hidden');
    }
    function postJson(path, body) {
        return fetch(apiUrl(path), {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
            body: new URLSearchParams(body),
            credentials: 'same-origin'
        }).then(function(res) {
            var ct = res.headers.get('content-type') || '';
            if (!ct.includes('application/json')) {
                return res.text().then(function(t) {
                    throw new Error(t ? t.slice(0, 200) : 'Server error (' + res.status + ')');
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
        });
    }
    if (new URLSearchParams(window.location.search).get('reset') === '1') {
        ok.textContent = 'Your password was updated. You can sign in below.';
        ok.classList.remove('hidden');
    }
    document.getElementById('ns-btn-password').addEventListener('click', function() {
        var email = (document.getElementById('ns-email').value || '').trim();
        var password = document.getElementById('ns-password').value || '';
        showErr('');
        var leaving = false;
        setLoading(true, 'Signing you in…');
        postJson('/new_signin_password', { email: email, password: password }).then(function(res) {
            if (res.redirect) {
                leaving = true;
                goTo(res.redirect);
            } else if (res.success === 'verify') {
                leaving = true;
                goTo('/verify');
            } else if (res.success === 'reset' && res.redirect) {
                leaving = true;
                goTo(res.redirect);
            } else if (res.error) {
                showErr(res.error);
            }
        }).catch(function(e) {
            showErr(e.message || 'Request failed.');
        }).finally(function() {
            if (!leaving) {
                setLoading(false);
            }
        });
    });
    document.getElementById('ns-reset-password').addEventListener('click', function() {
        var btn = document.getElementById('ns-reset-password');
        var email = (document.getElementById('ns-email').value || '').trim();
        showErr('');
        if (!email) {
            showErr('Enter your email, then tap Reset password.');
            return;
        }
        var label = btn.textContent;
        btn.disabled = true;
        btn.textContent = 'Sending…';
        var leavingReset = false;
        setLoading(true, 'Sending reset link…');
        postJson('/new_forgot_password_code', { email: email }).then(function(res) {
            if (res.success === 'reset' && res.redirect) {
                leavingReset = true;
                goTo(res.redirect);
            } else if (res.error) {
                showErr(res.error);
            }
        }).catch(function(e) {
            showErr(e.message || 'Request failed.');
        }).finally(function() {
            btn.disabled = false;
            btn.textContent = label;
            if (!leavingReset) {
                setLoading(false);
            }
        });
    });
    document.getElementById('ns-btn-code').addEventListener('click', function() {
        setLoading(true, 'Loading…');
        goTo('/new_signin_code');
    });

    (function() {
        var btn = document.getElementById('ns-toggle-password');
        var input = document.getElementById('ns-password');
        var open = document.getElementById('ns-eye-open');
        var closed = document.getElementById('ns-eye-closed');
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

