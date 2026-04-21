<?php
header('Content-Type: application/json'); // Return JSON to browser

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Invalid request method."]);
    exit;
}

$mindmap_name = $_POST['mindmap_name'] ?? '';
$x = $_POST['x'] ?? 300;
$y = $_POST['y'] ?? 200;
$user_id = $_SESSION["work_user_id"];

if (empty($mindmap_name)) {
    echo json_encode(["error" => "Missing name of map."]);
    exit;
}
$conn = getDBConnection();
$stmt = $conn->prepare("SELECT * FROM mindmap WHERE parent = ? and user_id = ?");
$parent = -1;
$stmt->bind_param("is", $parent, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo json_encode(["error" => "Map already exists"]);
    exit;
}
$stmt = $conn->prepare("SELECT * FROM mindmap WHERE mindmapname = ?");
$stmt->bind_param("s", $mindmap_name);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo json_encode(["error" => "Name is repeated!"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO mindmap (mindmapname, parent, user_id, x, y) VALUES (?, ?, ?, ?, ?)");
$parent = -1;
$stmt->bind_param("sisii", $mindmap_name, $parent, $user_id, $x, $y);
$_SESSION["mindmap_name"] = $mindmap_name;
if ($stmt->execute()) {
    echo json_encode(["success" => "Map created successfully!", "id" => $stmt->insert_id]);
} else {
    echo json_encode(["error" => "Failed to create map."]);
}
