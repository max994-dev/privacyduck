<?php
/**
 * /api/journey_status — same-origin AJAX endpoint that returns the
 * current Removal Journey panel state for the logged-in user.
 *
 * Used by JS on the dashboard to refresh counts + recent activity every
 * ~8 seconds without a full page reload — equivalent UX to SocketIO push
 * but without the operational pain of running a separate WebSocket server.
 *
 * Returns JSON:
 *   {
 *     ok: true,
 *     counts: {done, in_flight, queued, failed, not_impl, missing_pii, total},
 *     pct_done: <int 0-100>,
 *     recent: [{id, target_domain, step, label}, ...],   // last 5
 *     epoch: <unix ts>                                    // server time
 *   }
 *
 * GET-only — same WHERE/ORDER as journey_panel.php so the AJAX
 * representation matches the server-rendered initial state.
 *
 * Auth: session-only (read-only data, no CSRF needed for GET that doesn't
 * mutate state).
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/utils.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/database.php';

header('Content-Type: application/json');
header('Cache-Control: no-store');

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'auth required']);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'GET') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'GET only']);
    exit;
}

$userId = (int) $_SESSION['user_id'];
$counts = [
    'done' => 0, 'in_flight' => 0, 'queued' => 0,
    'failed' => 0, 'not_impl' => 0, 'missing_pii' => 0, 'total' => 0,
];
$recent = [];

try {
    $conn = getDBConnection();

    $stmt = $conn->prepare(
        "SELECT step, COUNT(*) AS n FROM results WHERE user_id = ? AND kind = 1 GROUP BY step"
    );
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    foreach ($stmt->get_result()->fetch_all(MYSQLI_ASSOC) as $r) {
        $n = (int) $r['n'];
        $counts['total'] += $n;
        switch ((int) $r['step']) {
            case 0: $counts['queued']     += $n; break;
            case 1: $counts['in_flight']  += $n; break;
            case 2: $counts['done']       += $n; break;
            case 3: $counts['failed']     += $n; break;
            case 4: $counts['not_impl']   += $n; break;
            case 5: $counts['missing_pii']+= $n; break;
        }
    }
    $stmt->close();

    $stmt = $conn->prepare(
        "SELECT id, target_domain, step
         FROM results WHERE user_id = ? AND kind = 1 AND step IN (1,2,3,4,5)
         ORDER BY id DESC LIMIT 5"
    );
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    foreach ($stmt->get_result()->fetch_all(MYSQLI_ASSOC) as $r) {
        $label = match ((int) $r['step']) {
            2 => 'Removed',
            1 => 'In progress now',
            3 => 'Broker rejected (will retry)',
            4 => 'Broker not yet supported',
            5 => 'Broker wants more info',
            default => 'Scheduled',
        };
        $recent[] = [
            'id' => (int) $r['id'],
            'target_domain' => $r['target_domain'],
            'step' => (int) $r['step'],
            'label' => $label,
        ];
    }
    $stmt->close();
    $conn->close();
} catch (Throwable $e) {
    error_log('get_journey_status: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'internal']);
    exit;
}

$pctDone = $counts['total'] > 0
    ? (int) round(($counts['done'] * 100) / $counts['total'])
    : 0;

echo json_encode([
    'ok' => true,
    'counts' => $counts,
    'pct_done' => $pctDone,
    'recent' => $recent,
    'epoch' => time(),
]);
