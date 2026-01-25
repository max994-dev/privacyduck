<?php
    header('Content-Type: application/json');
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        echo json_encode(["error" => "Invalid request method."]);
        exit;
    }
    if(!isset($_SESSION['work_user_id'])||$_SESSION['work_user_id'] == null){
        echo json_encode(["error" => "User not found!"]);
        exit;
    }
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
    $row = $result->fetch_assoc();
    echo json_encode($row);