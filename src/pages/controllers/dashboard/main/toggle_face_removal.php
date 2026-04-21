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

$checked = (int) ($_POST['checked'] ?? 0) === 1 ? 1 : 0;

try {
    $conn = getDBConnection();
    odoo_removal_ensure_columns($conn);

    $stmt = $conn->prepare("UPDATE users SET face_manual_removed = ? WHERE id = ?");
    $stmt->bind_param('ii', $checked, $userId);
    $stmt->execute();
    $stmt->close();

    $conn->close();

    echo json_encode([
        'success' => true,
        'face_manual_removed' => $checked,
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
exit;

