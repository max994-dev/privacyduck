<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/src/common/config.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/src/common/database.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/common/odoo_removal_sync.php");
header('Content-Type: application/json');

$conn = getDBConnection();
// TODO: verify admin session/role here

$user_id = (int)($_POST['user_id'] ?? 0);
$domain  = $_POST['target_domain'] ?? '';
$step    = (int)($_POST['step'] ?? 0);

$conn->begin_transaction(); // transaction safety [web:552]

try {
    $base = BASEPATH . "/assets/uploads/" . $user_id;
    foreach (['scan','google_scan','removal'] as $folder) {
        $dir = $base . "/" . $folder;
        if (!is_dir($dir)) mkdir($dir, 0755, true);
    }

    // Example for one uploaded field: removal_file
    if (!empty($_FILES['removal_file']['tmp_name'])) {
        // Validate type (use allowlist + finfo) [web:138][web:128]
        $target = $base."/removal/removal_{$domain}_{$user_id}.png";
        if (!move_uploaded_file($_FILES['removal_file']['tmp_name'], $target)) {
            throw new Exception("Upload failed");
        } // [web:127]
    }

    $stmt = $conn->prepare("UPDATE results SET step=? WHERE user_id=? AND kind=1 AND target_domain=?");
    $stmt->bind_param("iis", $step, $user_id, $domain);
    $stmt->execute();

    // Hybrid sync: push current status to Odoo immediately (pull cron remains source of reliability).
    try {
        odoo_sync_result_row_to_odoo($conn, $user_id, $domain);
    } catch (Throwable $syncErr) {
        error_log('odoo_removal_push_sync: ' . $syncErr->getMessage());
    }

    $conn->commit(); // [web:569]
    echo json_encode(["success"=>true]);
} catch (Throwable $e) {
    $conn->rollback(); // [web:569]
    http_response_code(500);
    echo json_encode(["success"=>false, "error"=>$e->getMessage()]);
}

