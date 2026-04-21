<?php
header('Content-Type: application/json'); // Return JSON to browser

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Invalid request method."]);
    exit;
}
$email = $_POST['email'] ?? '';
$firstname = $_POST['firstname'] ?? '';
$lastname = $_POST['lastname'] ?? '';
$x = $_POST['x'] ?? 0;
$y = $_POST['y'] ?? 0;
if (empty($email)) {
    echo json_encode(["error" => "Missing email."]);
    exit;
}
if (empty($firstname)) {
    echo json_encode(["error" => "Missing firstname."]);
    exit;
}
if (empty($lastname)) {
    echo json_encode(["error" => "Missing lastname."]);
    exit;
}
$user_id = $_SESSION['work_user_id'];
$conn = getDBConnection();
$stmt = $conn->prepare("SELECT * FROM mindmap WHERE parent = ? and user_id = ?");
$parent = -1;
$stmt->bind_param("is", $parent, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    echo json_encode(["error" => "Map not found"]);
    exit;
}
$parent = $result->fetch_assoc()['id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    echo json_encode(["error" => "User not found"]);
    exit;
}
$child_user_id = $result->fetch_assoc()['id'];
$stmt = $conn->prepare("SELECT * FROM mindmap WHERE parent = ? and user_id = ?");
$stmt->bind_param("ii", $parent, $child_user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo json_encode(["error" => "User already included"]);
    exit;
}
$stmt = $conn->prepare("INSERT INTO mindmap (parent, user_id, firstname, lastname, x, y) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iissii", $parent, $child_user_id, $firstname, $lastname, $x, $y);
if ($stmt->execute()) {
    echo json_encode(["success" => "User added successfully!", "id" => $stmt->insert_id]);
} else {
    echo json_encode(["error" => "Failed to create map."]);
}
