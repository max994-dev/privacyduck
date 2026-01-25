<?php
header('Content-Type: application/json'); // Return JSON to browser

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Invalid request method."]);
    exit;
}
$user_id = $_SESSION['work_user_id'] ?? '';
$conn = getDBConnection();
$stmt = $conn->prepare("SELECT * FROM mindmap WHERE user_id = ? and parent = -1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    echo json_encode([]);
}
else{
    $mindmap = $result->fetch_assoc();
    $stmt = $conn->prepare("SELECT * FROM mindmap WHERE parent = ?");
    $stmt->bind_param("i", $mindmap['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode([
        $mindmap,
        ...$result->fetch_all(MYSQLI_ASSOC)
    ]);
}