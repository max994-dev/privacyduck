<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
 // required before using $_SESSION [web:329]2026
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

$firstname = $_POST['first_name'] ?? '';
$lastname = $_POST['last_name'] ?? '';
$contactss = $_POST["contacts"] ?? [];

$city = $contactss[0]["city"] ?? '';
$state = $contactss[0]["state"] ?? '';
$phone = $contactss[0]["phone"] ?? '';
$zip = $contactss[0]["zip"] ?? '';
$address = $contactss[0]["address"] ?? '';

if (empty($firstname) || empty($lastname)) {
    echo json_encode(["error" => "Missing firstname or lastname."]);
    exit;
}

try {
    $conn = getDBConnection();
    $stmtt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmtt->bind_param("i", $user_id);
    $stmtt->execute();
    $result = $stmtt->get_result();
    $row = $result->fetch_assoc();
    $contacts = json_decode($row["contacts"], true) ?? [];
    $contacts[0]=[
        "city" => $city,
        "state" => $state,
        "phone" => $phone,
        "zip" => $zip,
        "address" => $address
    ];
    // Prepare contacts as JSON string
    $json_contacts = json_encode($contacts);

    $stmt = $conn->prepare("UPDATE users SET firstname = ?, lastname = ?, phone = ?, city = ?, zip = ?, state = ?, address = ?, contacts = ? WHERE id = ?");
    $stmt->bind_param("ssssssssi", $firstname, $lastname, $phone, $city, $zip, $state, $address, $json_contacts, $user_id);
    $stmt->execute();

    $_SESSION["fullName"] = $firstname . " " . $lastname;
    $_SESSION["signup_complete"] = 1; //2026
    echo json_encode(["success" => "success"]);

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
