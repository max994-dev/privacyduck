<?php
    include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/config.php");
    include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/utils.php");
    include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/database.php");
    
    header('Content-Type: application/json'); // Return JSON to browser

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(["error" => "Invalid request method."]);
        exit;
    }

    $email = $_POST['email'] ?? '';
    $data = $_POST['data'] ?? '{}';
    $url = $_POST['url'] ?? '';

    if (empty($email) || $data == "{}") {
        echo json_encode(["error" => "Missing email or fullname."]);
        exit;
    }

    $conn = getDBConnection();
    $stmt = $conn->prepare("INSERT INTO special (email, data, url) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $data, $url);
    $stmt->execute();

    echo json_encode(["success" => "User created successfully."]);
?>