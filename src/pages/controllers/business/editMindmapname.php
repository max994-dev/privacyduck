<?php
    header('Content-Type: application/json');
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(["error" => "Invalid request method."]);
        exit;
    }
    $mindmap_name = $_POST['mindmap_name'] ?? '';
    $user_id = $_SESSION['work_user_id'] ?? '';
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT * FROM mindmap WHERE user_id = ? and parent = -1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        echo json_encode(["error" => "Map not found!"]);
        exit;
    }
    $stmt = $conn->prepare("UPDATE mindmap SET mindmapname = ? WHERE user_id = ? and parent = -1");
    $stmt->bind_param("si", $mindmap_name, $user_id);
    if ($stmt->execute()) {
        $_SESSION['mindmap_name'] = $mindmap_name;
        echo json_encode(["success" => "Map name updated successfully!"]);
    } else {
        echo json_encode(["error" => "Failed to update map name."]);
    }