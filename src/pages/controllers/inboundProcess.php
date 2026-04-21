<?php
$email = $_POST['email'];
$firstName = $_POST['firstName'] ?? "John";
$lastName = $_POST['lastName'] ?? "Doe";
header('Content-Type: application/json');
try {
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        $createdAt = date("Y-m-d H:i:s");
        $stmt = $conn->prepare("INSERT INTO users (email, firstname, lastname, created_at) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $email, $firstName, $lastName, $createdAt);
        $stmt->execute();
        echo json_encode([
            "success" => "success"
        ]);
    }else{
        $data = $result->fetch_assoc();
        $hasActivePlan = !empty($data["plan_id"]) && !empty($data["plan_end"]);
        $isPlanValid = $hasActivePlan && (new DateTime() < new DateTime($data["plan_end"]));
        if($isPlanValid){
            echo json_encode([
                "warning" => "You have already an active plan"
            ]);
        }else{
            echo json_encode([
                "success" => "success"
            ]);
        }
    }
} catch (PDOException $e) {
    echo json_encode([
        "error" => $e->getMessage()
    ]);
}
