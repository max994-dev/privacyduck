<?php
isReverseLogin();

$meta_title = 'PrivacyDuck — Email login code';
$meta_description = 'Sign in with an emailed login code.';
$meta_url = 'https://privacyduck.com/new_signin_code';
$meta_image = 'https://privacyduck.com/assets/pageSEO/landing.jpg';

include_once(BASEPATH . '/src/common/meta.php');
main_head_start();
?>
<meta name="robots" content="noindex, nofollow">
<style>
    body { font-family: system-ui, -apple-system, sans-serif; background: #f8fafc; }
    body.nsc-signin-loading { overflow: hidden; }
    #nsc-loading-overlay {
        background: rgba(15, 23, 42, 0.35);
        backdrop-filter: blur(2px);
    }
    .nsc-spinner {
        width: 44px;
        height: 44px;
        border: 3px solid rgba(5, 150, 105, 0.2);
        border-top-color: rgb(5, 150, 105);
        border-radius: 50%;
        animation: nsc-spin 0.65s linear infinite;
    }
    @keyframes nsc-spin { to { transform: rotate(360deg); } }
    @media (prefers-reduced-motion: reduce) {
        .nsc-spinner { animation-duration: 1.4s; }
    }
</style>
<?php
main_head_end();
?>
<div class="mx-auto max-w-lg px-4 py-12">
    <div class="mb-8 text-center">
        <a href="/new" class="inline-block"><img class="mx-auto h-9 w-auto" src="/assets/image/desktop/logo4.svg" alt="PrivacyDuck"></a>
        <h1 class="mt-6 text-xl font-semibold text-slate-900">Email me login code</h1>
        <p class="mt-2 text-sm text-slate-600">Enter your email and we will send you a verification code.</p>
    </div>

    <div id="nsc-err" class="hidden mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"></div>

    <div class="space-y-4 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <div>
            <label class="block text-xs font-medium text-slate-700">Email <span class="text-red-600">*</span></label>
            <input id="nsc-email" type="email" autocomplete="email"
                class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600" />
        </div>

        <button type="button" id="nsc-btn-send"
            class="mt-2 w-full rounded-lg bg-emerald-600 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
            Send code
        </button>

        <p class="text-center text-sm text-slate-600 pt-2">
            <a href="/new_signin" class="font-semibold text-emerald-700 hover:underline">Back to sign in</a>
        </p>
    </div>
</div>

<div id="nsc-loading-overlay" class="hidden fixed inset-0 z-[200] flex items-center justify-center" role="status" aria-live="polite" aria-busy="false" aria-hidden="true">
    <div class="rounded-2xl bg-white px-8 py-6 shadow-xl flex flex-col items-center gap-4">
        <div class="nsc-spinner" aria-hidden="true"></div>
        <p class="text-sm font-medium text-slate-700 m-0">Sending code…</p>
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
    var err = document.getElementById('nsc-err');
    var loadingOverlay = document.getElementById('nsc-loading-overlay');
    function setLoading(on, message) {
        if (!loadingOverlay) return;
        var msgEl = loadingOverlay.querySelector('p');
        if (msgEl && message) {
            msgEl.textContent = message;
        } else if (msgEl && !on) {
            msgEl.textContent = 'Sending code…';
        }
        loadingOverlay.classList.toggle('hidden', !on);
        loadingOverlay.setAttribute('aria-busy', on ? 'true' : 'false');
        loadingOverlay.setAttribute('aria-hidden', on ? 'false' : 'true');
        document.body.classList.toggle('nsc-signin-loading', !!on);
    }
    function showErr(msg) {
        err.textContent = msg || '';
        err.classList.toggle('hidden', !msg);
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
    document.getElementById('nsc-btn-send').addEventListener('click', function() {
        var btn = document.getElementById('nsc-btn-send');
        var email = (document.getElementById('nsc-email').value || '').trim();
        showErr('');
        if (!email) {
            showErr('Please enter your email first.');
            return;
        }
        var label = btn.textContent;
        btn.disabled = true;
        btn.textContent = 'Sending...';
        var leaving = false;
        setLoading(true, 'Sending code…');
        postJson('/new_login_code', { email: email }).then(function(res) {
            if (res.success === 'verify') {
                leaving = true;
                goTo('/verify');
            } else if (res.redirect) {
                leaving = true;
                goTo(res.redirect);
            } else if (res.success === 'prelogin' && res.redirect) {
                leaving = true;
                goTo(res.redirect);
            } else if (res.error) {
                showErr(res.error);
            }
        }).catch(function(e) {
            showErr(e.message || 'Request failed.');
        }).finally(function() {
            btn.disabled = false;
            btn.textContent = label;
            if (!leaving) {
                setLoading(false);
            }
        });
    });
})();
</script>
<?php
no_footer();

