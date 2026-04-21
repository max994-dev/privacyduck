<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/odoo_removal_sync.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

$provided = (string) ($_GET['key'] ?? '');
if ($provided === '' && isset($_SERVER['HTTP_X_PD_SYNC_KEY'])) {
    $provided = (string) $_SERVER['HTTP_X_PD_SYNC_KEY'];
}
if (!defined('ODOO_REMOVAL_SYNC_KEY') || ODOO_REMOVAL_SYNC_KEY === '' || !hash_equals((string) ODOO_REMOVAL_SYNC_KEY, $provided)) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Forbidden']);
    exit;
}

$cursor = max(0, (int) ($_GET['cursor'] ?? 0));
$limit = (int) ($_GET['limit'] ?? 200);
if ($limit < 1) $limit = 1;
if ($limit > 1000) $limit = 1000;

try {
    $conn = getDBConnection();
    // Auto-migrate legacy DBs before selecting this column.
    odoo_removal_ensure_columns($conn);

    $sql = "SELECT r.id, r.user_id, r.target_domain, r.step, r.site_url, r.removal_url, r.odoo_lead_id,
                   u.email, u.firstname, u.lastname, u.phone
            FROM results r
            JOIN users u ON u.id = r.user_id
            WHERE r.kind = 1 AND r.id > ?
            ORDER BY r.id ASC
            LIMIT ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new RuntimeException('Prepare failed: ' . $conn->error);
    }
    $stmt->bind_param('ii', $cursor, $limit);
    if (!$stmt->execute()) {
        throw new RuntimeException('Execute failed: ' . $stmt->error);
    }
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();
} catch (Throwable $e) {
    if (isset($stmt) && $stmt instanceof mysqli_stmt) {
        @$stmt->close();
    }
    if (isset($conn) && $conn instanceof mysqli) {
        @$conn->close();
    }
    error_log('odoo_removal_export: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'error' => 'Export failed',
        'details' => $e->getMessage(),
    ], JSON_UNESCAPED_SLASHES);
    exit;
}

$items = [];
$nextCursor = $cursor;
foreach ($rows as $r) {
    $rid = (int) $r['id'];
    if ($rid > $nextCursor) {
        $nextCursor = $rid;
    }
    $items[] = [
        'result_id' => $rid,
        'user_id' => (int) $r['user_id'],
        'email' => (string) ($r['email'] ?? ''),
        'firstname' => (string) ($r['firstname'] ?? ''),
        'lastname' => (string) ($r['lastname'] ?? ''),
        'phone' => (string) ($r['phone'] ?? ''),
        'target_domain' => (string) $r['target_domain'],
        'step' => (int) $r['step'],
        'status_label' => odoo_removal_status_label((int) $r['step']),
        'site_url' => (string) ($r['site_url'] ?? ''),
        'removal_url' => (string) ($r['removal_url'] ?? ''),
        'odoo_lead_id' => (string) ($r['odoo_lead_id'] ?? ''),
    ];
}

echo json_encode([
    'ok' => true,
    'cursor' => $cursor,
    'next_cursor' => $nextCursor,
    'count' => count($items),
    'has_more' => count($items) === $limit,
    'items' => $items,
], JSON_UNESCAPED_SLASHES);
exit;

