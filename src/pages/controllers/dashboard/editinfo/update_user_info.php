<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Invalid request method."]);
    exit;
}

$user_id = $_SESSION["user_id"] ?? null;
$email = $_SESSION['email'];
if (!$user_id || !$email) {
    echo json_encode(["error" => "User not logged in."]);
    exit;
}



$firstname = $_POST['first_name'] ?? '';
$lastname = $_POST['last_name'] ?? '';
$contacts = $_POST["contacts"] ?? '[{"city":"","state":"","phone":"","zip":"","address":""}]';
$contacts = json_decode($contacts, true);

$city = $contacts[0]["city"] ?? '';
$state = $contacts[0]["state"] ?? '';
$phone = $contacts[0]["phone"] ?? '';
$zip = $contacts[0]["zip"] ?? '';
$address = $contacts[0]["address"] ?? '';

if (empty($firstname) || empty($lastname)) {
    echo json_encode(["error" => "Missing firstname or lastname."]);
    exit;
}


if (!isset($_FILES['file'])) {
    $filename = "";
}
else {
    $uploadDir = BASEPATH . "/assets/uploads/" . basename("specialinfo");
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $uploadedFile = $_FILES['file'];
    $ext = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);
    $filename = "img_" . $email . "." . $ext;
    $targetFile = $uploadDir . "/" . $filename;

    if (!move_uploaded_file($uploadedFile['tmp_name'], $targetFile)) {
        http_response_code(500);
        echo json_encode(["error" => "Failed to move uploaded file"]);
        exit;
    }
}

try {
    $conn = getDBConnection();

    // Prepare contacts as JSON string
    $json_contacts = json_encode($contacts);

    $stmt = $conn->prepare("UPDATE users SET firstname = ?, lastname = ?, phone = ?, city = ?, zip = ?, state = ?, address = ?, contacts = ?, url = ? WHERE id = ?");
    $stmt->bind_param("sssssssssi", $firstname, $lastname, $phone, $city, $zip, $state, $address, $json_contacts, $filename, $user_id);
    $stmt->execute();

    $_SESSION["fullName"] = $firstname . " " . $lastname;
    $_SESSION["signup_complete"] = 1;
    unset($_SESSION["needs_profile_info"]);

    echo json_encode(["success" => "success"]);

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
