<?php
$user_id = $_SESSION['user_id'];
header('Content-Type: application/json');
try {
    $conn = getDBConnection();
    $stmt = $conn->prepare("UPDATE users SET role = -1 WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM results WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    session_destroy();
    echo json_encode([
        "success" => "success"
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "error" => $e->getMessage()
    ]);
}
