<?php
/**
 * Weekly removal digest.
 *
 * Cron-callable PHP script. For each paid user who's had any kind=1
 * step transition in the last 7 days, builds an HTML summary of:
 *   - how many sites we removed them from this week
 *   - which brokers specifically (top 10 by name, "+ N more" rest)
 *   - how many are in progress
 *   - what's blocking any pending removal (e.g., missing PII)
 *   - cumulative removed total since they joined
 *
 * Sends via pd_smtp_relay_send_html(). No marketing claim, no upsell —
 * service-related transactional email. Honors a per-user
 * users.weekly_digest_opt_out flag (added if needed below).
 *
 * Schedule: cron weekly. Recommended Saturday 09:00 local server time:
 *   0 9 * * 6  curl -fsS "https://privacyduck.com/internal/weekly-digest?key=$DIGEST_KEY"
 * (with DIGEST_KEY set on the host's environment matching PD_DIGEST_KEY
 * in /var/www/html/.env)
 *
 * Auth: requires ?key=<PD_DIGEST_KEY> query param matching env. Refuses
 * if env not set. Refuses access from any non-trusted source.
 *
 * Idempotency: tracks `users.last_digest_sent_at` (column added on
 * demand). Skips any user already sent within the last 6 days. Safe to
 * run more frequently than weekly (will simply skip recent recipients).
 *
 * Dry-run: append &dry=1 to render the list of who would receive but
 * NOT send any email. Use to spot-check before scheduling.
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/smtp_env.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/smtp_relay_client.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/database.php';

header('Content-Type: text/plain; charset=utf-8');

// --- auth ---
$expectedKey = trim((string) getenv('PD_DIGEST_KEY'));
$suppliedKey = (string) ($_GET['key'] ?? '');
if ($expectedKey === '') {
    http_response_code(503);
    echo "weekly digest disabled: PD_DIGEST_KEY env not set on this server\n";
    exit;
}
if (!hash_equals($expectedKey, $suppliedKey)) {
    http_response_code(403);
    echo "forbidden\n";
    exit;
}

$dryRun = !empty($_GET['dry']);
$limit  = max(1, min(10000, (int) ($_GET['limit'] ?? 1000)));
$forceUser = isset($_GET['user_id']) ? (int) $_GET['user_id'] : 0;
echo "weekly_digest start " . date('Y-m-d H:i:s') . " dry=" . ($dryRun ? 'yes' : 'no') . "\n";

$conn = getDBConnection();

// --- schema migration: add columns we depend on if missing ---
$mig = $conn->query("SHOW COLUMNS FROM users LIKE 'last_digest_sent_at'");
if ($mig && $mig->num_rows === 0) {
    $conn->query("ALTER TABLE users ADD COLUMN last_digest_sent_at DATETIME NULL AFTER last_emailing_time");
    echo "  migrated users + last_digest_sent_at column\n";
}
$mig = $conn->query("SHOW COLUMNS FROM users LIKE 'weekly_digest_opt_out'");
if ($mig && $mig->num_rows === 0) {
    $conn->query("ALTER TABLE users ADD COLUMN weekly_digest_opt_out TINYINT(1) NOT NULL DEFAULT 0 AFTER last_digest_sent_at");
    echo "  migrated users + weekly_digest_opt_out column\n";
}
// results.updated_at: required so "this week" actually means "this week".
// On first add we set ON UPDATE CURRENT_TIMESTAMP so every future step
// transition through `UPDATE results SET step = ...` stamps the row.
// Existing rows are backfilled to a far past sentinel so the FIRST digest
// run doesn't tell every existing user we "removed N sites this week"
// when in fact those removals are lifetime. After that, only genuinely
// recent transitions appear in the 7-day window.
$mig = $conn->query("SHOW COLUMNS FROM results LIKE 'updated_at'");
if ($mig && $mig->num_rows === 0) {
    $conn->query("ALTER TABLE results ADD COLUMN updated_at DATETIME NOT NULL "
               . "DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
    // backfill to a sentinel far before the 7-day window so existing data
    // doesn't fire on the first digest run
    $conn->query("UPDATE results SET updated_at = '2000-01-01 00:00:00'");
    echo "  migrated results + updated_at column (backfilled to sentinel)\n";
}

// --- candidate users: paid + has had a step transition in last 7 days ---
$where = "u.plan_id IS NOT NULL AND u.plan_id > 0 AND u.plan_end > NOW() "
       . "AND COALESCE(u.weekly_digest_opt_out, 0) = 0 "
       . "AND (u.last_digest_sent_at IS NULL OR u.last_digest_sent_at < NOW() - INTERVAL 6 DAY) ";
if ($forceUser > 0) {
    $where = "u.id = " . $forceUser;  // bypass all gating for a manual test send
}

// 7-day window filter applied at the JOIN level so SUM() counts only
// rows that changed in the window, not lifetime totals. For the force
// test-send case (?user_id=N) we drop the date filter and the HAVING
// gate entirely -- the goal is "render whatever exists for this user
// so we can verify the email", not "respect normal cadence".
$windowDays = 7;
$dateJoin = $forceUser > 0
    ? ''
    : "AND r.updated_at >= NOW() - INTERVAL $windowDays DAY";
$having = $forceUser > 0
    ? ''
    : "HAVING done_week > 0 OR failed_week > 0 OR missing_pii_week > 0";
$q = "SELECT u.id, u.email, u.firstname,
             SUM(r.step = 2) AS done_week,
             SUM(r.step = 3) AS failed_week,
             SUM(r.step = 5) AS missing_pii_week
      FROM users u
      JOIN results r ON r.user_id = u.id AND r.kind = 1 $dateJoin
      WHERE $where
      GROUP BY u.id, u.email, u.firstname
      $having
      LIMIT $limit";

$result = $conn->query($q);
if (!$result) {
    echo "candidate query failed: " . $conn->error . "\n";
    $conn->close();
    exit;
}

$sent = 0;
$skipped = 0;
$errors = 0;
while ($u = $result->fetch_assoc()) {
    $userId = (int) $u['id'];
    $email  = (string) $u['email'];
    $first  = (string) ($u['firstname'] ?? '');
    $done   = (int) $u['done_week'];
    $failed = (int) $u['failed_week'];
    $blocked = (int) $u['missing_pii_week'];

    // top 10 brokers done this week (by name), filtered to the same
    // 7-day window the candidate query used. For ?user_id=N test sends
    // we drop the date filter so the rendered email isn't empty.
    $top = [];
    $totalDone = 0;
    if ($forceUser > 0) {
        $st = $conn->prepare(
            "SELECT target_domain FROM results
             WHERE user_id = ? AND kind = 1 AND step = 2
             ORDER BY id DESC LIMIT 100"
        );
        $st->bind_param("i", $userId);
    } else {
        $st = $conn->prepare(
            "SELECT target_domain FROM results
             WHERE user_id = ? AND kind = 1 AND step = 2
               AND updated_at >= NOW() - INTERVAL ? DAY
             ORDER BY updated_at DESC LIMIT 100"
        );
        $st->bind_param("ii", $userId, $windowDays);
    }
    $st->execute();
    foreach ($st->get_result()->fetch_all(MYSQLI_ASSOC) as $row) {
        $totalDone++;
        if (count($top) < 10) {
            $top[] = (string) $row['target_domain'];
        }
    }
    $st->close();

    // cumulative removed all-time
    $st = $conn->prepare("SELECT COUNT(*) AS n FROM results WHERE user_id = ? AND kind = 1 AND step = 2");
    $st->bind_param("i", $userId);
    $st->execute();
    $allTimeDone = (int) ($st->get_result()->fetch_assoc()['n'] ?? 0);
    $st->close();

    // build email HTML (no scary corporate template -- a plain readable
    // recap; mirrors the brand color from /assets/css)
    $greeting = $first !== '' ? "Hi $first,\n" : "Hi,\n";
    $topList  = '';
    if (!empty($top)) {
        $topList = '<ul style="padding-left:18px; margin:8px 0;">';
        foreach ($top as $d) {
            $topList .= '<li style="margin-bottom:4px;">' . htmlspecialchars($d, ENT_QUOTES, 'UTF-8') . '</li>';
        }
        $topList .= '</ul>';
        if ($totalDone > 10) {
            $topList .= '<p style="color:#5B5F66; font-size:13px; margin:8px 0;">'
                      . '+' . ($totalDone - 10) . ' more this week.</p>';
        }
    }
    $blockedLine = '';
    if ($blocked > 0) {
        $blockedLine = '<p style="margin:18px 0; padding:12px 16px; background:#FFF7E8; '
                     . 'border:1px solid #FFD4A8; border-radius:8px; color:#92400E; font-size:14px;">'
                     . '<strong>Action needed:</strong> ' . $blocked . ' broker'
                     . ($blocked === 1 ? '' : 's') . " can't process your removal until "
                     . 'your profile is complete. <a href="' . WEB_DOMAIN
                     . '/new_dashboard/account" style="color:#92400E; text-decoration:underline;">'
                     . 'Add missing info</a>.</p>';
    }
    $failedLine = '';
    if ($failed > 0) {
        $failedLine = '<p style="color:#5B5F66; font-size:13px; margin:8px 0;">'
                    . $failed . ' broker' . ($failed === 1 ? '' : 's')
                    . " rejected the first attempt this week — we'll retry on the next 90-day sweep.</p>";
    }

    $html = '<!doctype html><html><body style="font-family:Helvetica,Arial,sans-serif; '
          . 'color:#010205; max-width:600px; margin:0 auto; padding:24px;">'
          . '<h2 style="color:#24A556; font-size:22px; margin:0 0 16px;">Your weekly privacy update</h2>'
          . '<p style="font-size:15px; line-height:1.6; margin:0 0 12px;">' . nl2br(htmlspecialchars($greeting, ENT_QUOTES, 'UTF-8')) . '</p>'
          . '<p style="font-size:15px; line-height:1.6; margin:0 0 16px;">'
          . 'This week we removed your personal data from '
          . '<strong style="color:#24A556;">' . $totalDone . ' data broker site'
          . ($totalDone === 1 ? '' : 's') . '</strong>.</p>'
          . $topList
          . $blockedLine
          . $failedLine
          . '<p style="font-size:14px; line-height:1.55; margin:24px 0 16px; color:#5B5F66;">'
          . 'Cumulative removals since you joined: <strong>' . $allTimeDone . '</strong> sites.</p>'
          . '<p style="text-align:center; margin:28px 0;">'
          . '<a href="' . WEB_DOMAIN . '/new_dashboard" '
          . 'style="background:#24A556; color:#fff; padding:12px 28px; border-radius:9999px; '
          . 'text-decoration:none; font-weight:600; display:inline-block;">View full dashboard</a></p>'
          . '<hr style="border:none; border-top:1px solid #ECECEC; margin:32px 0 16px;">'
          . '<p style="font-size:12px; color:#878C91; line-height:1.5;">'
          . 'You are receiving this because you have an active PrivacyDuck subscription. '
          . 'These are service updates, not marketing. '
          . '<a href="' . WEB_DOMAIN . '/new_dashboard/account?unsub_digest=1" '
          . 'style="color:#878C91;">Stop weekly updates</a>.'
          . '</p>'
          . '</body></html>';

    $altBody = "$greeting\nThis week we removed your data from $totalDone data broker site"
             . ($totalDone === 1 ? '' : 's') . ".\n\n"
             . (empty($top) ? '' : "Including: " . implode(', ', $top) . "\n\n")
             . "Cumulative removals: $allTimeDone\n\n"
             . "Full dashboard: " . WEB_DOMAIN . "/new_dashboard\n";

    if ($dryRun) {
        echo "  DRY user_id=$userId email=$email done_week=$done failed=$failed missing_pii=$blocked all_time=$allTimeDone\n";
        $skipped++;
        continue;
    }

    $smtpCfg = pd_smtp_config();
    $fromEmail = (string) ($smtpCfg['from_email'] ?? 'hello@privacyduck.com');
    $fromName  = (string) ($smtpCfg['from_name'] ?? 'PrivacyDuck');

    $ok = pd_smtp_relay_send_html(
        $email,
        'Your weekly privacy update — ' . $totalDone . ' new removals',
        $html,
        $fromEmail,
        $fromName,
        $altBody
    );

    if ($ok) {
        $upd = $conn->prepare("UPDATE users SET last_digest_sent_at = NOW() WHERE id = ?");
        $upd->bind_param("i", $userId);
        $upd->execute();
        $upd->close();
        $sent++;
        echo "  SENT  user_id=$userId email=$email done=$totalDone\n";
    } else {
        $errors++;
        echo "  ERROR user_id=$userId email=$email — send failed\n";
    }
}

$conn->close();
echo "\nweekly_digest done. sent=$sent skipped=$skipped errors=$errors\n";
