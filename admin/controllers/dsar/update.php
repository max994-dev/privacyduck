<?php
header("Content-Type: application/json");

include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/database.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['admin']['isAdminAuthenticated'])) {
    http_response_code(401);
    echo json_encode(["error" => "Admin not authenticated"]);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    exit;
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
if ($id < 1) {
    echo json_encode(["error" => "Invalid id"]);
    exit;
}

$validStatuses = ['open', 'in_progress', 'completed', 'rejected', 'extended'];
$newStatus = trim((string) ($_POST['new_status'] ?? ''));
if ($newStatus !== '' && !in_array($newStatus, $validStatuses, true)) {
    echo json_encode(["error" => "Invalid status"]);
    exit;
}

$note = trim((string) ($_POST['note'] ?? ''));
if (strlen($note) > 2000) {
    echo json_encode(["error" => "Note too long (max 2000 characters)"]);
    exit;
}

if ($newStatus === '' && $note === '') {
    echo json_encode(["error" => "Provide a status change or a note"]);
    exit;
}

$conn = getDBConnection();

$stmt = $conn->prepare("SELECT id, status, staff_notes FROM dsar_requests WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    $conn->close();
    echo json_encode(["error" => "DSAR not found"]);
    exit;
}

$adminUser = (string) ($_SESSION['admin']['username'] ?? 'admin');
$ts = date('Y-m-d H:i');

// Build the audit-log line that gets prepended to staff_notes.
// Format: [YYYY-MM-DD HH:MM admin_username] (status: old -> new) note text
$logLine = "[$ts $adminUser]";
if ($newStatus !== '' && $newStatus !== $row['status']) {
    $logLine .= " (status: " . $row['status'] . " -> " . $newStatus . ")";
}
if ($note !== '') {
    $logLine .= " " . $note;
}
$logLine .= "\n";

$existingNotes = (string) ($row['staff_notes'] ?? '');
$newNotes = $logLine . ($existingNotes !== '' ? "\n" . $existingNotes : '');

// Update statement: status (if changed), staff_notes, completed_at (if newly completed/rejected).
$statusToSet = $newStatus !== '' ? $newStatus : $row['status'];
$completedAtClause = null;
if ($newStatus !== '' && $newStatus !== $row['status']) {
    if (in_array($newStatus, ['completed', 'rejected'], true)) {
        $completedAtClause = date('Y-m-d H:i:s');
    }
}

if ($completedAtClause !== null) {
    $stmt = $conn->prepare(
        "UPDATE dsar_requests SET status = ?, staff_notes = ?, completed_at = ? WHERE id = ?"
    );
    $stmt->bind_param('sssi', $statusToSet, $newNotes, $completedAtClause, $id);
} else {
    $stmt = $conn->prepare(
        "UPDATE dsar_requests SET status = ?, staff_notes = ? WHERE id = ?"
    );
    $stmt->bind_param('ssi', $statusToSet, $newNotes, $id);
}

if (!$stmt->execute()) {
    error_log('DSAR update failed: ' . $stmt->error);
    $stmt->close();
    $conn->close();
    echo json_encode(["error" => "Database error"]);
    exit;
}
$stmt->close();
$conn->close();

echo json_encode([
    "ok" => true,
    "id" => $id,
    "new_status" => $statusToSet,
]);
