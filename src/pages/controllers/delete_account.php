<?php

/**
 * Account deletion — does ALL of this in order:
 *
 *   1.  Reauthenticate: require password + typed "DELETE" confirmation
 *       (CSRF + session already checked above this).
 *   2.  Cancel every active Stripe subscription linked to the user's
 *       email via the customers + subscriptions tables. Best-effort:
 *       if Stripe is unreachable we LOG the failure but continue --
 *       the user's local data must still be erased even if Stripe is
 *       down. Stripe customer ID is NOT deleted (keeps historical
 *       billing data intact, retains charge dispute capability).
 *   3.  Inside a DB transaction:
 *       a. Mark subscriptions rows status='cancelled' (don't hard
 *          delete; we want to keep churn / cohort history).
 *       b. DELETE results (broker work rows) — about 413 per user.
 *       c. DELETE family (where user is core_id).
 *       d. DELETE custom_removal.
 *       e. Anonymize users row: replace every PII column with a
 *          neutral marker, NULL out plan_id/plan_end, set role=-1.
 *          The row itself stays for FK integrity with any historical
 *          tables we missed.
 *   4.  Recursively delete /assets/uploads/{user_id}/ (ID photos,
 *       profile pictures). Path-guarded to never escape the
 *       /assets/uploads/ root.
 *   5.  Audit-log to PHP error log: who/what/when/stripe outcome.
 *   6.  session_destroy().
 *
 * Why anonymize instead of DELETE FROM users:
 *   - GDPR Art. 17 is satisfied by erasure OR irreversible anonymization
 *   - DELETE would break FKs on any historical table we didn't catch
 *   - Leaves an auditable trail (role=-1, deleted_at) for support/legal
 *
 * Confirmation email is NOT sent from here (one mailer breakage would
 * block deletion). Caller logs success; if you want a confirmation
 * email, queue one via the weekly digest mechanism or add it as a
 * separate non-blocking call here.
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/security.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
if (!function_exists('pd_stripe_bootstrap')) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/config.php';
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(["error" => "Method not allowed"]);
    exit;
}
if (function_exists('pd_csrf_require')) { pd_csrf_require(); }

header('Content-Type: application/json');

if (!isset($_SESSION['isAuthenticated']) || $_SESSION['isAuthenticated'] !== true
    || empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Not authenticated"]);
    exit;
}

// ------------------------------------------------------------------
// 1. Re-auth gate (password + typed confirmation)
// ------------------------------------------------------------------
$password     = isset($_POST['password'])     ? (string) $_POST['password']     : '';
$confirmation = isset($_POST['confirmation']) ? (string) $_POST['confirmation'] : '';
if ($password === '') {
    http_response_code(400);
    echo json_encode(["error" => "Password is required"]);
    exit;
}
if (strtoupper(trim($confirmation)) !== 'DELETE') {
    http_response_code(400);
    echo json_encode(["error" => "Type DELETE in the confirmation box"]);
    exit;
}

$user_id = (int) $_SESSION['user_id'];

try {
    $conn = getDBConnection();

    $stmt = $conn->prepare("SELECT email, password, firstname, lastname FROM users WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$user) {
        http_response_code(404);
        echo json_encode(["error" => "User not found"]);
        exit;
    }
    $stored = (string) ($user['password'] ?? '');
    if ($stored === '' || !password_verify($password, $stored)) {
        // Don't leak whether user exists, but the auth check above
        // already proved they do (it's their own session).
        http_response_code(403);
        echo json_encode(["error" => "Incorrect password"]);
        exit;
    }
    $email = (string) ($user['email'] ?? '');

    // ----------------------------------------------------------------
    // 2. Cancel Stripe subscriptions (best-effort, never blocks deletion)
    // ----------------------------------------------------------------
    $stripeCanceled = [];
    $stripeErrors   = [];
    try {
        pd_stripe_bootstrap();

        // Pull every subscription linked to ANY customers row whose
        // email matches the user. A user could in theory have multiple
        // customer rows (re-checkout creates new Stripe customers if
        // the email casing differs). Collect them all to be safe.
        $cStmt = $conn->prepare("SELECT id, stripe_customer_id FROM customers WHERE LOWER(TRIM(email)) = LOWER(TRIM(?))");
        $cStmt->bind_param("s", $email);
        $cStmt->execute();
        $customerRows = $cStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $cStmt->close();

        foreach ($customerRows as $cRow) {
            $cid = (int) ($cRow['id'] ?? 0);
            if ($cid <= 0) continue;
            $sStmt = $conn->prepare(
                "SELECT stripe_subscription_id FROM subscriptions
                 WHERE customer_id = ? AND status NOT IN ('cancelled','canceled')"
            );
            $sStmt->bind_param("i", $cid);
            $sStmt->execute();
            $subRows = $sStmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $sStmt->close();

            foreach ($subRows as $sRow) {
                $subId = (string) ($sRow['stripe_subscription_id'] ?? '');
                if ($subId === '') continue;
                try {
                    // Immediate cancellation. If you want "cancel at period end"
                    // semantics, replace with:
                    //   \Stripe\Subscription::update($subId, ['cancel_at_period_end' => true]);
                    \Stripe\Subscription::cancel($subId);
                    $stripeCanceled[] = $subId;
                } catch (\Stripe\Exception\InvalidRequestException $e) {
                    // Already cancelled or doesn't exist -- treat as success.
                    $msg = $e->getMessage();
                    if (stripos($msg, 'No such subscription') !== false
                        || stripos($msg, 'already canceled') !== false
                        || stripos($msg, 'already cancelled') !== false) {
                        $stripeCanceled[] = $subId . ' (already)';
                    } else {
                        $stripeErrors[] = ['sub' => $subId, 'error' => $msg];
                        error_log("delete_account: stripe cancel invalid_request sub=$subId user_id=$user_id: $msg");
                    }
                } catch (\Throwable $e) {
                    $stripeErrors[] = ['sub' => $subId, 'error' => $e->getMessage()];
                    error_log("delete_account: stripe cancel failed sub=$subId user_id=$user_id: " . $e->getMessage());
                }
            }
        }
    } catch (\Throwable $e) {
        error_log("delete_account: stripe stage init failed user_id=$user_id: " . $e->getMessage());
        $stripeErrors[] = ['error' => 'stripe init: ' . $e->getMessage()];
    }

    // ----------------------------------------------------------------
    // 3. Local DB cleanup (transactional)
    // ----------------------------------------------------------------
    // Make sure 'deleted_at' column exists for audit. Cheap idempotent
    // check using SHOW COLUMNS pattern from odoo_removal_sync.
    $r = $conn->query("SHOW COLUMNS FROM users LIKE 'deleted_at'");
    if ($r && $r->num_rows === 0) {
        $conn->query("ALTER TABLE users ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL");
    }
    if ($r) $r->free();

    $conn->begin_transaction();
    try {
        // 3a. Mark subscriptions cancelled (preserves history)
        $stmt = $conn->prepare(
            "UPDATE subscriptions s
             JOIN customers c ON c.id = s.customer_id
             SET s.status = 'cancelled'
             WHERE LOWER(TRIM(c.email)) = LOWER(TRIM(?))"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->close();

        // 3b. Delete results (broker work rows, 413+ per user)
        $stmt = $conn->prepare("DELETE FROM results WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        // 3c. Delete family records where user is the core/owner
        $stmt = $conn->prepare("DELETE FROM family WHERE core_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        // 3d. Delete custom_removal
        $stmt = $conn->prepare("DELETE FROM custom_removal WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        // 3e. Anonymize users row. Reserved placeholder email pattern:
        //     deleted_<user_id>_<timestamp>@deleted.local
        //     -- guaranteed unique (UNIQUE INDEX on email) and clearly
        //     marked. .local TLD is reserved per RFC 6762, never
        //     routable, never accidentally emailed.
        $deletedEmail = 'deleted_' . $user_id . '_' . time() . '@deleted.local';
        $stmt = $conn->prepare(
            "UPDATE users SET
                email = ?,
                firstname = 'Deleted',
                lastname  = 'User',
                password  = '',
                phone     = '',
                address   = '',
                city      = '',
                state     = '',
                zip       = '',
                birth_date = NULL,
                age        = NULL,
                contacts   = '[]',
                plan_id    = NULL,
                plan_start = NULL,
                plan_end   = NULL,
                planedAt   = NULL,
                role       = -1,
                deleted_at = NOW()
             WHERE id = ?"
        );
        $stmt->bind_param("si", $deletedEmail, $user_id);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
    } catch (\Throwable $e) {
        $conn->rollback();
        throw $e;
    }

    // ----------------------------------------------------------------
    // 4. Wipe uploaded files (filesystem). Path-guarded.
    // ----------------------------------------------------------------
    $uploadsBase = realpath($_SERVER['DOCUMENT_ROOT'] . '/assets/uploads');
    $uploadsDir  = $_SERVER['DOCUMENT_ROOT'] . '/assets/uploads/' . $user_id;
    if ($uploadsBase !== false && is_dir($uploadsDir)) {
        $real = realpath($uploadsDir);
        // Defense in depth: refuse anything outside /assets/uploads/.
        // A symlink trick or a weird $user_id ('..') would otherwise
        // let us walk the filesystem. Numeric cast above also helps.
        if ($real !== false && strpos($real, $uploadsBase . DIRECTORY_SEPARATOR) === 0) {
            try {
                $it = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($real, FilesystemIterator::SKIP_DOTS),
                    RecursiveIteratorIterator::CHILD_FIRST
                );
                foreach ($it as $entry) {
                    if ($entry->isDir()) {
                        @rmdir($entry->getPathname());
                    } else {
                        @unlink($entry->getPathname());
                    }
                }
                @rmdir($real);
            } catch (\Throwable $e) {
                error_log("delete_account: file wipe partial-failure user_id=$user_id: " . $e->getMessage());
            }
        }
    }

    // ----------------------------------------------------------------
    // 5. Audit log
    // ----------------------------------------------------------------
    error_log(sprintf(
        'ACCOUNT_DELETED user_id=%d orig_email=%s anonymized_to=%s stripe_canceled=%d stripe_errors=%d',
        $user_id, $email, $deletedEmail,
        count($stripeCanceled), count($stripeErrors)
    ));

    @closeDBConnection($conn);
    // Destroy session AFTER the response has been built. We still
    // echo() below; PHP buffers so this ordering is fine.
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    @session_destroy();

    echo json_encode([
        "success" => true,
        "message" => "Account permanently deleted",
        "stripe_canceled_count" => count($stripeCanceled),
        "stripe_error_count" => count($stripeErrors),
    ]);

} catch (Throwable $e) {
    if (isset($conn) && $conn) {
        @$conn->close();
    }
    http_response_code(500);
    error_log('delete_account: ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
    echo json_encode([
        "error" => "Account deletion failed. Please contact support."
    ]);
}
