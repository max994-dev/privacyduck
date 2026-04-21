<?php
header('Content-Type: application/json');
require_once BASEPATH . '/src/common/odoo_removal_sync.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method.']);
    exit;
}

$userId = (int) ($_SESSION['user_id'] ?? 0);
if ($userId <= 0) {
    http_response_code(401);
    echo json_encode(['error' => 'User not logged in.']);
    exit;
}

$targetDomain = trim((string) ($_POST['target_domain'] ?? ''));
$checked = (int) ($_POST['checked'] ?? 0) === 1 ? 1 : 0;
if ($targetDomain === '') {
    http_response_code(422);
    echo json_encode(['error' => 'Missing target domain.']);
    exit;
}

try {
    $conn = getDBConnection();
    odoo_removal_ensure_columns($conn);

    $checkedAt = $checked === 1 ? date('Y-m-d H:i:s') : null;
    $stmt = $conn->prepare("UPDATE results SET manual_checklist_done = ?, manual_checked_at = ? WHERE user_id = ? AND kind = 1 AND target_domain = ?");
    $stmt->bind_param('isis', $checked, $checkedAt, $userId, $targetDomain);
    $stmt->execute();
    $stmt->close();

    try {
        odoo_sync_result_row_to_odoo($conn, $userId, $targetDomain);
    } catch (Throwable $syncErr) {
        error_log('toggle_manual_removal odoo sync failed: ' . $syncErr->getMessage());
    }

    $conn->close();

    echo json_encode([
        'success' => true,
        'target_domain' => $targetDomain,
        'manual_checklist_done' => $checked,
        'manual_checked_at' => $checkedAt,
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
exit;
