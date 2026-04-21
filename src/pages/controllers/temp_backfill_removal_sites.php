<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/pages/Dashboard/sites_data.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

$provided = (string) ($_GET['key'] ?? '');
if ($provided === '' && isset($_SERVER['HTTP_X_PD_BACKFILL_KEY'])) {
    $provided = (string) $_SERVER['HTTP_X_PD_BACKFILL_KEY'];
}
if (!defined('REMOVAL_BACKFILL_KEY') || REMOVAL_BACKFILL_KEY === '' || !hash_equals((string) REMOVAL_BACKFILL_KEY, $provided)) {
    http_response_code(403);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['ok' => false, 'error' => 'Forbidden']);
    exit;
}

@set_time_limit(60);
@ini_set('max_execution_time', '60');

$targetUserId = max(0, (int) ($_GET['user_id'] ?? 0));
$allUsers = ((string) ($_GET['all'] ?? '0')) === '1';
$limit = max(1, min(5000, (int) ($_GET['limit'] ?? 500)));
$cursor = max(0, (int) ($_GET['cursor'] ?? 0)); // user id cursor for all=1 mode
$batchSize = max(1, min(1000, (int) ($_GET['batch_size'] ?? 200)));
$stream = ((string) ($_GET['stream'] ?? '0')) === '1';
$progressEvery = max(1, min(100, (int) ($_GET['progress_every'] ?? 10)));
$confirmAll = ((string) ($_GET['confirm_all'] ?? '0')) === '1';

if ($allUsers && !$confirmAll) {
    http_response_code(400);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'ok' => false,
        'error' => 'Missing confirm_all=1 for all-users mode',
    ]);
    exit;
}

function pd_stream_event(string $event, array $payload): void
{
    echo 'event: ' . $event . "\n";
    echo 'data: ' . json_encode($payload, JSON_UNESCAPED_SLASHES) . "\n\n";
    @ob_flush();
    @flush();
}

function pd_backfill_user(mysqli_stmt $selExisting, mysqli_stmt $ins, array $siteKeys, array $websites, array $websitesUrl, array $u): array
{
    $uid = (int) $u['id'];
    $planable = (!empty($u['plan_id']) && !empty($u['plan_end']) && strtotime((string) $u['plan_end']) > time()) ? 1 : 0;

    $selExisting->bind_param('i', $uid);
    $selExisting->execute();
    $rows = $selExisting->get_result()->fetch_all(MYSQLI_ASSOC);
    $existing = [];
    foreach ($rows as $r) {
        $existing[(string) $r['target_domain']] = true;
    }

    $inserted = 0;
    foreach ($siteKeys as $domain) {
        if (isset($existing[$domain])) {
            continue;
        }
        $siteUrl = (string) ($websitesUrl[$domain] ?? '');
        $removalUrl = (string) ($websites[$domain] ?? '');
        $ins->bind_param('siiss', $domain, $uid, $planable, $siteUrl, $removalUrl);
        if ($ins->execute()) {
            $inserted++;
        }
    }

    return [
        'user_id' => $uid,
        'planable' => $planable,
        'before_count_kind1' => count($existing),
        'inserted' => $inserted,
        'after_count_kind1' => count($existing) + $inserted,
    ];
}

try {
    $conn = getDBConnection();
    $siteKeys = array_keys($websites);
    $siteCount = count($siteKeys);

    $selExisting = $conn->prepare("SELECT target_domain FROM results WHERE user_id = ? AND kind = 1");
    if (!$selExisting) {
        throw new RuntimeException('Prepare existing rows failed: ' . $conn->error);
    }
    $ins = $conn->prepare(
        "INSERT INTO results (target_domain, user_id, kind, step, planable, site_url, removal_url)
         VALUES (?, ?, 1, 0, ?, ?, ?)"
    );
    if (!$ins) {
        throw new RuntimeException('Prepare insert failed: ' . $conn->error);
    }

    $report = [
        'ok' => true,
        'mode' => $allUsers ? 'all' : 'single',
        'expected_total_sites' => $siteCount,
        'cursor' => $cursor,
        'next_cursor' => $cursor,
        'users' => [],
        'summary' => [
            'processed_users' => 0,
            'inserted_rows' => 0,
            'already_complete_users' => 0,
        ],
    ];

    if ($stream) {
        header('Content-Type: text/event-stream; charset=utf-8');
        header('Cache-Control: no-cache, no-transform');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no');
        pd_stream_event('start', [
            'ok' => true,
            'message' => 'Backfill started',
            'expected_total_sites' => $siteCount,
            'mode' => $allUsers ? 'all' : 'single',
            'progress_every' => $progressEvery,
        ]);
    } else {
        header('Content-Type: application/json; charset=utf-8');
    }

    if ($allUsers) {
        // Hard cap per request to avoid gateway timeout / worker starvation.
        $processedTarget = min($limit, $batchSize, 50);
        $stmtUsers = $conn->prepare(
            "SELECT id, plan_id, plan_end
             FROM users
             WHERE id > ?
             ORDER BY id ASC
             LIMIT ?"
        );
        if (!$stmtUsers) {
            throw new RuntimeException('Prepare users failed: ' . $conn->error);
        }
        $stmtUsers->bind_param('ii', $cursor, $processedTarget);
        $stmtUsers->execute();
        $users = $stmtUsers->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmtUsers->close();

        foreach ($users as $u) {
            if (connection_aborted()) {
                break;
            }
            $row = pd_backfill_user($selExisting, $ins, $siteKeys, $websites, $websitesUrl, $u);
            $row['expected_total_sites'] = $siteCount;
            $report['users'][] = $row;

            $report['summary']['processed_users']++;
            $report['summary']['inserted_rows'] += (int) $row['inserted'];
            if ((int) $row['inserted'] === 0) {
                $report['summary']['already_complete_users']++;
            }
            $report['next_cursor'] = (int) $row['user_id'];

            if ($stream && ($report['summary']['processed_users'] % $progressEvery === 0)) {
                pd_stream_event('progress', [
                    'processed_users' => $report['summary']['processed_users'],
                    'inserted_rows' => $report['summary']['inserted_rows'],
                    'next_cursor' => $report['next_cursor'],
                    'last_user_id' => (int) $row['user_id'],
                ]);
            }
        }

        $report['has_more'] = count($users) === $processedTarget;
    } else {
        if ($targetUserId <= 0) {
            throw new RuntimeException('Missing user_id for single-user mode');
        }
        $stmtUser = $conn->prepare("SELECT id, plan_id, plan_end FROM users WHERE id = ? LIMIT 1");
        if (!$stmtUser) {
            throw new RuntimeException('Prepare single user failed: ' . $conn->error);
        }
        $stmtUser->bind_param('i', $targetUserId);
        $stmtUser->execute();
        $u = $stmtUser->get_result()->fetch_assoc();
        $stmtUser->close();
        if (!$u) {
            throw new RuntimeException('User not found');
        }

        $row = pd_backfill_user($selExisting, $ins, $siteKeys, $websites, $websitesUrl, $u);
        $row['expected_total_sites'] = $siteCount;
        $report['users'][] = $row;
        $report['summary']['processed_users'] = 1;
        $report['summary']['inserted_rows'] = (int) $row['inserted'];
        $report['summary']['already_complete_users'] = ((int) $row['inserted'] === 0) ? 1 : 0;
        $report['next_cursor'] = (int) $row['user_id'];
        $report['has_more'] = false;
    }

    $selExisting->close();
    $ins->close();
    $conn->close();

    if ($stream) {
        pd_stream_event('end', $report);
        exit;
    }

    echo json_encode($report, JSON_UNESCAPED_SLASHES);
    exit;
} catch (Throwable $e) {
    if (isset($selExisting) && $selExisting instanceof mysqli_stmt) {
        @$selExisting->close();
    }
    if (isset($ins) && $ins instanceof mysqli_stmt) {
        @$ins->close();
    }
    if (isset($conn) && $conn instanceof mysqli) {
        @$conn->close();
    }
    error_log('temp_backfill_removal_sites: ' . $e->getMessage());

    if ($stream) {
        pd_stream_event('error', [
            'ok' => false,
            'error' => 'Backfill failed',
            'details' => $e->getMessage(),
        ]);
        exit;
    }

    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'ok' => false,
        'error' => 'Backfill failed',
        'details' => $e->getMessage(),
    ], JSON_UNESCAPED_SLASHES);
    exit;
}

