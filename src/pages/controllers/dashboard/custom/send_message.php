<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        if (isset($_SESSION["planable"])&&$_SESSION["planable"]){
            if (isset($_POST["message"]) && $_POST["message"] != "") {
                $conn = getDBConnection();
                $sql = "
                    INSERT INTO custom_messages (user_id, message, time)
                    VALUES (?, ?, ?)
                ";
                $stmt = $conn->prepare($sql);
                $time = date("Y-m-d H:i:s");
                var_dump($time);
                $stmt->bind_param("iss", $_SESSION["user_id"], $_POST["message"], $time);
                $stmt->execute();
                $stmt->close();
                $conn->close();
                echo "success";
            }
        }
    }
?>