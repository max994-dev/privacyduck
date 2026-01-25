<?php
    if (isset($_SESSION["planable"])&&$_SESSION["planable"]){
        $conn = getDBConnection();
        $sql = "UPDATE custom_removal SET status = 3 WHERE user_id = ? AND state = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_SESSION["user_id"]);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        echo "success";
    }
?>