<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/utils.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/stripe_signup_sync.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_id']) || empty($_SESSION['isAuthenticated'])) {
    header('Location: ' . WEB_DOMAIN . '/login');
    exit;
}

// One more safety sync on direct /book-call access in case webhook was delayed.
if ((empty($_SESSION['planable']) || empty($_SESSION['plan_id'])) && !empty($_SESSION['email'])) {
    try {
        $syncConn = getDBConnection();
        stripe_sync_privacyduck_subscription_for_email($syncConn, (string) $_SESSION['email'], true);
        $refresh = $syncConn->prepare('SELECT plan_id, plan_end FROM users WHERE id = ?');
        $refresh->bind_param('i', $_SESSION['user_id']);
        $refresh->execute();
        $fresh = $refresh->get_result()->fetch_assoc();
        $refresh->close();
        $syncConn->close();
        if ($fresh) {
            $hasActivePlan = !empty($fresh['plan_id']) && !empty($fresh['plan_end']);
            $isPlanValid = $hasActivePlan && (new DateTime() < new DateTime($fresh['plan_end']));
            $_SESSION['plan_id'] = $fresh['plan_id'] ?? null;
            $_SESSION['planable'] = $isPlanValid;
            $_SESSION['signup_complete'] = $isPlanValid;
        }
    } catch (Throwable $e) {
        error_log('book_call_page stripe fallback sync: ' . $e->getMessage());
    }
}

if (empty($_SESSION['planable'])) {
    header('Location: ' . WEB_DOMAIN . '/dashboard');
    exit;
}

if (empty($_SESSION['pd_book_call_intent'])) {
    header('Location: ' . WEB_DOMAIN . '/dashboard');
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/book_call_time.php';

$dates = book_call_available_dates(21);
$slots = book_call_slot_labels();
$tz = book_call_tz();
$nowPt = new DateTime('now', $tz);
$todayPt = $nowPt->format('Y-m-d');
$nowMinutesPt = ((int) $nowPt->format('G')) * 60 + (int) $nowPt->format('i');
$slotStartMinutes = [
    '2:00 PM' => 14 * 60,
    '2:30 PM' => 14 * 60 + 30,
    '3:00 PM' => 15 * 60,
    '3:30 PM' => 15 * 60 + 30,
];

$meta_title = 'Book your onboarding call | PrivacyDuck';
$meta_description = 'Schedule a PrivacyDuck onboarding call between 2:00 and 4:00 PM Pacific Time.';
$meta_url = 'https://privacyduck.com/book-call';
$meta_image = 'https://privacyduck.com/assets/pageSEO/landing.jpg';

include_once BASEPATH . '/src/common/meta.php';
main_head_start();
main_head_end();
?>
<div class="min-h-screen bg-[#fafafa] flex flex-col">
    <div class="h-[20px] sm:h-[24px]"></div>
    <div class="flex justify-center px-4 pb-12">
        <div class="w-full max-w-[560px] bg-white rounded-[24px] shadow-[0px_4px_24px_rgba(0,0,0,0.06)] border border-[#F0F0F0] p-6 sm:p-10">
            <a href="/" class="inline-block"><img src="/assets/image/desktop/logo2.svg" alt="PrivacyDuck" class="h-9 w-auto" /></a>
            <h1 class="mt-6 text-[26px] sm:text-[32px] font-bold text-[#010205] leading-tight">Book your onboarding call</h1>
            <p class="mt-3 text-[15px] text-[#4B4B4E] leading-relaxed">
                Complimentary call for new members. Available times are <strong>2:00–4:00 PM Pacific</strong> (PST/PDT). Your call is added to our calendar and synced to our team (Odoo when configured).
            </p>

            <form id="book-call-form" class="mt-8 space-y-5">
                <div>
                    <label for="bc-date" class="block text-[14px] font-medium text-[#010205] mb-2">Date</label>
                    <select name="date" id="bc-date" required
                        class="w-full h-[48px] px-4 rounded-[10px] border border-[#00000040] text-[#010205] bg-white">
                        <?php foreach ($dates as $d) {
                            $tz = book_call_tz();
                            $pretty = (new DateTime($d . ' 12:00:00', $tz))->format('l, M j, Y');
                        ?>
                            <option value="<?= htmlspecialchars($d, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($pretty, ENT_QUOTES, 'UTF-8') ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div>
                    <label for="bc-slot" class="block text-[14px] font-medium text-[#010205] mb-2">Time (Pacific)</label>
                    <select name="slot" id="bc-slot" required
                        class="w-full h-[48px] px-4 rounded-[10px] border border-[#00000040] text-[#010205] bg-white">
                        <?php foreach ($slots as $s) { ?>
                            <option value="<?= htmlspecialchars($s, ENT_QUOTES, 'UTF-8') ?>" data-start-min="<?= (int) ($slotStartMinutes[$s] ?? 0) ?>">
                                <?= htmlspecialchars($s, ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div id="bc-error" class="hidden text-[14px] text-red-600"></div>
                <button type="submit" id="bc-submit"
                    class="w-full h-[52px] rounded-full bg-[#24A556] text-white font-semibold text-[16px] hover:opacity-95 flex items-center justify-center gap-2">
                    <span id="bc-submit-label">Confirm call</span>
                    <span id="bc-submit-loading" class="hidden inline-flex items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-opacity=".3" stroke-width="3"></circle>
                            <path d="M22 12a10 10 0 0 0-10-10" stroke="currentColor" stroke-width="3" stroke-linecap="round"></path>
                        </svg>
                        Processing...
                    </span>
                </button>
            </form>

            <form action="/book_call_skip" method="post" class="mt-4">
                <button type="submit" class="w-full text-center text-[14px] text-[#9B9B9C] hover:text-[#010205] py-2">
                    Skip for now — go to my dashboard
                </button>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
var BOOK_CALL_TODAY_PT = <?= json_encode($todayPt) ?>;
var BOOK_CALL_NOW_MINUTES_PT = <?= (int) $nowMinutesPt ?>;

function refreshBookCallSlots() {
    var selectedDate = $('#bc-date').val();
    var isTodayPt = selectedDate === BOOK_CALL_TODAY_PT;
    var hasEnabled = false;
    $('#bc-slot option').each(function() {
        var startMin = parseInt($(this).data('start-min') || '0', 10);
        var disabled = isTodayPt && startMin < BOOK_CALL_NOW_MINUTES_PT;
        $(this).prop('disabled', disabled).toggle(!disabled);
        if (!disabled) hasEnabled = true;
    });
    var $err = $('#bc-error');
    if (!hasEnabled) {
        $err.removeClass('hidden').text('No times left for today. Please select another date.');
    } else if (($err.text() || '').indexOf('No times left for today') !== -1) {
        $err.addClass('hidden').text('');
    }
    var $selected = $('#bc-slot option:selected');
    if (!$selected.length || $selected.prop('disabled')) {
        $('#bc-slot option:not(:disabled):first').prop('selected', true);
    }
}

$('#bc-date').on('change', refreshBookCallSlots);
refreshBookCallSlots();

$('#book-call-form').on('submit', function(e) {
    e.preventDefault();
    var $err = $('#bc-error');
    var $btn = $('#bc-submit');
    var $label = $('#bc-submit-label');
    var $loading = $('#bc-submit-loading');
    $err.addClass('hidden').text('');
    $btn.prop('disabled', true).addClass('opacity-70 cursor-not-allowed');
    $label.addClass('hidden');
    $loading.removeClass('hidden');
    $.post('/book_call_submit', {
        date: $('#bc-date').val(),
        slot: $('#bc-slot').val()
    }, function(res) {
        if (res.ok && res.redirect) {
            window.location.href = res.redirect;
        } else {
            $err.removeClass('hidden').text(res.error || 'Something went wrong.');
        }
    }, 'json').fail(function(xhr) {
        var msg = 'Request failed.';
        try {
            var j = JSON.parse(xhr.responseText);
            if (j.error) msg = j.error;
        } catch (e) {}
        $err.removeClass('hidden').text(msg);
    }).always(function() {
        $btn.prop('disabled', false).removeClass('opacity-70 cursor-not-allowed');
        $loading.addClass('hidden');
        $label.removeClass('hidden');
    });
});
</script>
<?php
no_footer();
