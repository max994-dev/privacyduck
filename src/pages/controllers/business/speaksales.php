<?php
    header('Content-Type: application/json');
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(["error" => "Invalid request method."]);
        exit;
    }
    $first_name = $_POST['business_first_name'] ?? '';
    $last_name = $_POST['business_last_name'] ?? '';
    $work_email = $_POST['business_email'] ?? '';
    $direct_phone = $_POST['business_phone'] ?? '';
    $message = $_POST['business_message'] ?? '';
    $company = $_POST['business_company'] ?? '';
    $hear = $_POST['business_hear'] ?? '';
    if(empty($first_name) || empty($last_name) || empty($work_email)){
        echo json_encode(["error" => "Missing required fields."]);
        exit;
    }
    $conn = getDBConnection();
    $stmt = $conn->prepare("INSERT INTO speaksales (firstname, lastname, email, phone, message, company, hear, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, now())");
    $stmt->bind_param("sssssss", $first_name, $last_name, $work_email, $direct_phone, $message, $company, $hear);
    if ($stmt->execute()) {
        echo json_encode(["success" => "Submitted successfully!"]);
    } else {
        echo json_encode(["error" => "Failed to submit."]);
    }