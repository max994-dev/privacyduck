<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/book_call_schema.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/book_call_time.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/book_call_mail.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/odoo_jsonrpc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/stripe_signup_sync.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['user_id']) || empty($_SESSION['isAuthenticated'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'Please sign in.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

$date = isset($_POST['date']) ? trim((string) $_POST['date']) : '';
$slot = isset($_POST['slot']) ? trim((string) $_POST['slot']) : '';

try {
    $resolved = book_call_resolve_slot($date, $slot);
    if ($resolved === null) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Invalid date or time. Choose a slot between 2:00 and 4:00 PM Pacific.']);
        exit;
    }

    [$startUtc, $endUtc, $displayPst] = $resolved;

    $conn = getDBConnection();
    book_call_ensure_table($conn);

    $userId = (int) $_SESSION['user_id'];
    $email = $_SESSION['email'] ?? '';
    $stmt = $conn->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$user) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'User not found.']);
        exit;
    }
    $email = $user['email'] ?: $email;
    $name = trim(($user['firstname'] ?? '') . ' ' . ($user['lastname'] ?? ''));
    if ($name === '') {
        $name = $email;
    }
    $phone = trim((string) ($user['phone'] ?? ''));

    $token = bin2hex(random_bytes(32));
    $now = date('Y-m-d H:i:s');

    $title = 'PrivacyDuck onboarding — ' . $name;
    $refSnippet = substr($token, 0, 16) . '…';
    $odooUid = odoo_authenticate();
    $odooEventId = null;
    $odooLeadId = null;
    if ($odooUid !== null) {
        $odooEventId = odoo_create_calendar_event_with_uid($odooUid, $title, $startUtc, $endUtc, $email);
        $odooLeadId = odoo_create_crm_lead_with_uid(
            $odooUid,
            $title,
            $name,
            $email,
            $phone,
            $startUtc,
            $endUtc,
            $displayPst,
            $refSnippet,
            $odooEventId
        );
    }

    $odooEventStr = $odooEventId !== null ? (string) $odooEventId : '';
    $odooLeadStr = $odooLeadId !== null ? (string) $odooLeadId : '';
    $ins = $conn->prepare('INSERT INTO book_calls (user_id, email, scheduled_start_utc, scheduled_end_utc, odoo_event_id, odoo_lead_id, verification_token, reminder_sent, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, 0, ?)');
    $ins->bind_param('isssssss', $userId, $email, $startUtc, $endUtc, $odooEventStr, $odooLeadStr, $token, $now);
    if (!$ins->execute()) {
        throw new RuntimeException($conn->error ?: 'Insert failed');
    }
    $ins->close();
    $conn->close();

    $verifyNote = '<p style="color:#555;font-size:14px;">Reference: <strong>' . htmlspecialchars(substr($token, 0, 16), ENT_QUOTES, 'UTF-8') . '…</strong></p>';
    // PHPMailer uses exceptions; SMTP failure must not look like “booking failed” after DB insert succeeded.
    try {
        book_call_send_confirmation($email, $name, $displayPst, $verifyNote);
    } catch (Throwable $mailErr) {
        error_log('book_call_submit confirmation email: ' . $mailErr->getMessage());
    }

    // Fallback: if webhook is delayed/missed, try syncing Stripe subscription now.
    try {
        $syncConn = getDBConnection();
        stripe_sync_privacyduck_subscription_for_email($syncConn, $email, true);
        $refresh = $syncConn->prepare('SELECT * FROM users WHERE id = ?');
        $refresh->bind_param('i', $userId);
        $refresh->execute();
        $freshUser = $refresh->get_result()->fetch_assoc();
        $refresh->close();
        $syncConn->close();
        if ($freshUser) {
            $hasActivePlan = !empty($freshUser['plan_id']) && !empty($freshUser['plan_end']);
            $isPlanValid = $hasActivePlan && (new DateTime() < new DateTime($freshUser['plan_end']));
            $_SESSION['plan_id'] = $freshUser['plan_id'] ?? null;
            $_SESSION['planable'] = $isPlanValid;
            $_SESSION['signup_complete'] = $isPlanValid;
        }
    } catch (Throwable $syncErr) {
        error_log('book_call_submit stripe fallback sync: ' . $syncErr->getMessage());
    }

    $_SESSION['pd_book_call_done'] = 1;
    unset($_SESSION['pd_book_call_intent']);

    echo json_encode(['ok' => true, 'redirect' => WEB_DOMAIN . '/dashboard']);
    exit;
} catch (Throwable $e) {
    error_log('book_call_submit: ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Could not save your booking. Please try again.']);
}
