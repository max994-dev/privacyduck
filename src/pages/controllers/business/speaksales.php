<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/security.php';

// CSRF: state-mutating endpoint. Token comes from either
// <input name="csrf_token"> in the form OR the X-CSRF-Token header
// (utils.php injects it globally on jQuery.ajax/fetch).
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (function_exists('pd_csrf_require')) { pd_csrf_require(); }
}

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