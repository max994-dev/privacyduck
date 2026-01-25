<?php
    header('Content-Type: application/json'); 
    $conn = getDBConnection();
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_GET["id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    foreach ($data as &$row) {
        $row['contacts'] = json_decode($row['contacts'], true);
    }
    echo json_encode($data);
?>