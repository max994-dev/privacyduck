<?php
    if (isset($_SESSION["planable"])&&$_SESSION["planable"]){
        $page = isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0? (int) $_GET["page"]: 1;
        header('Content-Type: application/json');
        $conn = getDBConnection();
        $sql = "SELECT * FROM custom_messages WHERE user_id = ? ORDER BY time DESC LIMIT 10 OFFSET ?";
        $stmt = $conn->prepare($sql);
        $offset = ($page-1)*10;
        $stmt->bind_param("ii", $_SESSION["user_id"], $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
        echo json_encode($messages);
    }
?>