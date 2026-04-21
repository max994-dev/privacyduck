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

$contacts = $_POST["contacts"] ?? [];
if(empty($contacts["phone"])){
    echo json_encode(["error" => "Phone number is required."]);
    exit;
}
try {
    $conn = getDBConnection(); // returns mysqli connection

    // 1️⃣ Read current contacts
    $stmt = $conn->prepare("SELECT contacts FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result(); // mysqli correct way
    $row = $result->fetch_assoc();

    $currentContacts = $row ? json_decode($row['contacts'], true) : [];
    if(is_array($currentContacts) && count($currentContacts) > 4){
        echo json_encode(["error" => "You can only add four addresses."]);
        exit;
    }
    // 2️⃣ Add new contact to array
    $currentContacts[] = $contacts; // Add whole contact object

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
