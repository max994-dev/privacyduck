<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/odoo_jsonrpc.php';

function odoo_removal_status_label(int $step): string
{
    return match ($step) {
        1 => 'In progress',
        2 => 'Removed',
        3 => 'Not found',
        default => 'Pending',
    };
}

function odoo_removal_ensure_columns(mysqli $conn): void
{
    $r = $conn->query("SHOW COLUMNS FROM results LIKE 'odoo_lead_id'");
    if ($r && $r->num_rows === 0) {
        $conn->query("ALTER TABLE results ADD COLUMN odoo_lead_id VARCHAR(64) DEFAULT NULL");
    }
    if ($r) {
        $r->free();
    }
    $r = $conn->query("SHOW COLUMNS FROM results LIKE 'manual_checklist_done'");
    if ($r && $r->num_rows === 0) {
        $conn->query("ALTER TABLE results ADD COLUMN manual_checklist_done TINYINT(1) NOT NULL DEFAULT 0");
    }
    if ($r) {
        $r->free();
    }
    $r = $conn->query("SHOW COLUMNS FROM results LIKE 'manual_checked_at'");
    if ($r && $r->num_rows === 0) {
        $conn->query("ALTER TABLE results ADD COLUMN manual_checked_at DATETIME NULL DEFAULT NULL");
    }
    if ($r) {
        $r->free();
    }

    // Ensure per-user face removal tracking flag exists.
    $r = $conn->query("SHOW COLUMNS FROM users LIKE 'face_manual_removed'");
    if ($r && $r->num_rows === 0) {
        $conn->query("ALTER TABLE users ADD COLUMN face_manual_removed TINYINT(1) NOT NULL DEFAULT 0");
    }
    if ($r) {
        $r->free();
    }
}

function odoo_sync_result_row_to_odoo(mysqli $conn, int $userId, string $targetDomain): ?int
{
    if (!odoo_is_configured()) {
        return null;
    }

    odoo_removal_ensure_columns($conn);

    $stmt = $conn->prepare(
        "SELECT r.id, r.user_id, r.target_domain, r.step, r.site_url, r.removal_url, r.odoo_lead_id, r.manual_checklist_done, r.manual_checked_at,
                u.email, u.firstname, u.lastname, u.phone
         FROM results r
         JOIN users u ON u.id = r.user_id
         WHERE r.kind = 1 AND r.user_id = ? AND r.target_domain = ?
         LIMIT 1"
    );
    $stmt->bind_param('is', $userId, $targetDomain);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$row) {
        return null;
    }

    $uid = odoo_authenticate();
    if ($uid === null) {
        return null;
    }

    $email = (string) ($row['email'] ?? '');
    $first = (string) ($row['firstname'] ?? '');
    $last = (string) ($row['lastname'] ?? '');
    $name = trim($first . ' ' . $last);
    if ($name === '') {
        $name = $email !== '' ? $email : ('User #' . (int) $row['user_id']);
    }
    $phone = trim((string) ($row['phone'] ?? ''));
    $siteUrl = trim((string) ($row['site_url'] ?? ''));
    $removalUrl = trim((string) ($row['removal_url'] ?? ''));
    $step = (int) ($row['step'] ?? 0);
    $status = odoo_removal_status_label($step);
    $manualDone = (int) ($row['manual_checklist_done'] ?? 0) === 1;
    $manualCheckedAt = trim((string) ($row['manual_checked_at'] ?? ''));

    $title = 'Removal - ' . (string) $row['target_domain'] . ' - ' . $name;
    $description = implode("\n", [
        'PrivacyDuck removal tracking item',
        '',
        'Status: ' . $status . ' (step=' . $step . ')',
        'User ID: ' . (int) $row['user_id'],
        'Email: ' . $email,
        'Target Domain: ' . (string) $row['target_domain'],
        'Site URL: ' . ($siteUrl !== '' ? $siteUrl : '-'),
        'Removal URL: ' . ($removalUrl !== '' ? $removalUrl : '-'),
        'Manual Checklist: ' . ($manualDone ? 'Done' : 'Pending'),
        'Manual Checked At: ' . ($manualCheckedAt !== '' ? $manualCheckedAt : '-'),
        'PD Result ID: ' . (int) $row['id'],
        'Synced At (UTC): ' . gmdate('Y-m-d H:i:s'),
    ]);

    $vals = [
        'name' => $title,
        'email_from' => $email,
        'contact_name' => $name,
        'description' => $description,
        'type' => 'opportunity',
    ];
    if ($phone !== '') {
        $vals['phone'] = $phone;
    }
    if (defined('ODOO_CRM_TEAM_ID') && (int) ODOO_CRM_TEAM_ID > 0) {
        $vals['team_id'] = (int) ODOO_CRM_TEAM_ID;
    }

    $existingLeadId = trim((string) ($row['odoo_lead_id'] ?? ''));
    $leadId = null;
    if ($existingLeadId !== '' && ctype_digit($existingLeadId)) {
        $leadId = (int) $existingLeadId;
        $ok = odoo_execute_kw($uid, 'crm.lead', 'write', [[$leadId], $vals]);
        if (!$ok) {
            $leadId = null;
        }
    }

    if ($leadId === null) {
        $created = odoo_execute_kw($uid, 'crm.lead', 'create', [$vals]);
        $leadId = odoo_normalize_create_id($created);
    }

    if ($leadId !== null) {
        $upd = $conn->prepare('UPDATE results SET odoo_lead_id = ? WHERE id = ?');
        $leadStr = (string) $leadId;
        $rid = (int) $row['id'];
        $upd->bind_param('si', $leadStr, $rid);
        $upd->execute();
        $upd->close();
    }

    return $leadId;
}

