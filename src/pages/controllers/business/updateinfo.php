<?php
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Invalid request method."]);
    exit;
}
if (!isset($_SESSION['work_user_id']) || $_SESSION['work_user_id'] == null) {
    echo json_encode(["error" => "User not found!"]);
    exit;
}
if (!isset($_POST['firstname']) || !isset($_POST['lastname']) || !isset($_POST['phone'])) {
    echo json_encode(["error" => "Invalid request parameters."]);
    exit;
}
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$phone = $_POST['phone'];
$city = $_POST['city'] ?? "";
$state = $_POST['state'] ?? "";
$zip = $_POST['zip'] ?? "";
$address = $_POST['address'] ?? "";
$user_id = $_SESSION['work_user_id'];
$conn = getDBConnection();
$stmt = $conn->prepare("SELECT * FROM workUsers WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    echo json_encode(["error" => "User not found!"]);
    exit;
}
$stmt = $conn->prepare("UPDATE workUsers SET firstname = ?, lastname = ?, phone = ?, city = ?, state = ?, zip = ?, address = ? WHERE id = ?");
$stmt->bind_param("sssssssi", $firstname, $lastname, $phone, $city, $state, $zip, $address, $user_id);
$stmt->execute();
echo json_encode(["success" => "Account updated successfully!"]);
