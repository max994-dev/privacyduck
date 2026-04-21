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

try {
    $conn = getDBConnection(); // mysqli connection

    // 1️⃣ Read current contacts
    $stmt = $conn->prepare("SELECT contacts FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $currentContacts = $row ? json_decode($row['contacts'], true) : [];

    // 2️⃣ Remove contact at position
    unset($currentContacts[$pos]);
    $currentContacts = array_values($currentContacts); // Reindex array

    // 3️⃣ Save back to DB as JSON
    $newContactsJson = json_encode($currentContacts);

    $updateStmt = $conn->prepare("UPDATE users SET contacts = ?, phone = ?, city = ?, zip = ?, state = ?, address = ? WHERE id = ?");
    $updateStmt->bind_param("ssssssi", $newContactsJson, $currentContacts[0]["phone"], $currentContacts[0]["city"], $currentContacts[0]["zip"], $currentContacts[0]["state"], $currentContacts[0]["address"], $user_id);
    $updateStmt->execute();

    echo json_encode(["success" => true, "contacts" => $currentContacts]);

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
