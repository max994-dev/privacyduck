<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Invalid request method."]);
    exit;
}

$user_id = $_SESSION["user_id"] ?? null;
if (!$user_id) {
    echo json_encode(["error" => "User not logged in."]);
    exit;
}

$pos = $_POST["pos"] ?? null;
if (!is_numeric($pos)) {
    echo json_encode(["error" => "Invalid position."]);
    exit;
}
$pos = (int) $pos;

try {
    $conn = getDBConnection();

    // 1. Read current contacts
    $stmt = $conn->prepare("SELECT contacts FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $decoded = $row ? json_decode($row['contacts'] ?? '', true) : [];
    $currentContacts = is_array($decoded) ? $decoded : [];

    // 2. Remove contact at position
    if (array_key_exists($pos, $currentContacts)) {
        unset($currentContacts[$pos]);
        $currentContacts = array_values($currentContacts); // reindex
    }

    // 3. Save back to DB as JSON, mirroring the canonical contact into the legacy
    //    flat columns. If no contacts remain, blank them out instead of crashing on [0].
    $newContactsJson = json_encode($currentContacts);
    $primary = $currentContacts[0] ?? [
        "phone" => "", "city" => "", "zip" => "", "state" => "", "address" => "",
    ];
    $phone   = (string) ($primary["phone"]   ?? "");
    $city    = (string) ($primary["city"]    ?? "");
    $zip     = (string) ($primary["zip"]     ?? "");
    $state   = (string) ($primary["state"]   ?? "");
    $address = (string) ($primary["address"] ?? "");

    $updateStmt = $conn->prepare("UPDATE users SET contacts = ?, phone = ?, city = ?, zip = ?, state = ?, address = ? WHERE id = ?");
    $updateStmt->bind_param("ssssssi", $newContactsJson, $phone, $city, $zip, $state, $address, $user_id);
    $updateStmt->execute();
    $updateStmt->close();

    echo json_encode(["success" => true, "contacts" => $currentContacts]);
} catch (Throwable $e) {
    error_log('delete_user_address: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Failed to delete address."]);
}
?>
