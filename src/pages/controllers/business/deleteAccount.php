<?php
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Invalid request method."]);
    exit;
}
$user_id = $_SESSION['work_user_id'] ?? '';
$conn = getDBConnection();
$stmt = $conn->prepare("SELECT * FROM workUsers WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    echo json_encode(["error" => "User not found!"]);
    exit;
}
$stmt = $conn->prepare("UPDATE workUsers SET delete_flag = ? WHERE id = ?");
$flag = 1;
$stmt->bind_param("ii", $flag, $user_id);
if ($stmt->execute()) {
    echo json_encode(["success" => "User deleted successfully!"]);
} else {
    echo json_encode(["error" => "Failed to delete user."]);
}
