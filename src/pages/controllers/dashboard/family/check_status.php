<?php
    if (!isset($_SESSION["planable"]) || $_SESSION["planable"] == 0) {
        http_response_code(500);
        die(json_encode(["error" => "Planable error!"]));
    }
    header('Content-Type: application/json');
    if (isset($_SESSION["invite_requirePayment"])) unset($_SESSION["invite_requirePayment"]);
    if (isset($_SESSION["invite_pay_verified"])) unset($_SESSION["invite_pay_verified"]);
    if (isset($_SESSION["invite_count"])) unset($_SESSION["invite_count"]);
    if (isset($_SESSION["invite_price"])) unset($_SESSION["invite_price"]);
    $requirePayment = 1;
    $email = htmlspecialchars($_POST["email"]);

    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT * FROM plans WHERE id = ?");
    $stmt->bind_param("i", $_SESSION["plan_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    if ($data["person"] == "family"){
        $stmt = $conn->prepare("SELECT * FROM family WHERE core_id = ? And status = 0");
        $stmt->bind_param("i", $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result->num_rows;
        if ($count<5) $requirePayment = 0;
    }
    if ($data["person"] == "couple"){
        $stmt = $conn->prepare("SELECT * FROM family WHERE core_id = ? And status = 0");
        $stmt->bind_param("i", $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result->num_rows;
        if ($count<1) $requirePayment = 0;
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        echo json_encode(["status" => 0, "requirePayment" => $requirePayment]); //invite
    } else {
        $data = $result->fetch_assoc();

        $stmt = $conn->prepare("SELECT * FROM family WHERE core_id = ? And invite_id = ?");
        $stmt->bind_param("ii", $_SESSION["user_id"], $data["id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo json_encode(["status" => -1, "requirePayment" => 0]);  //exist
            $_SESSION["invite_requirePayment"] = 0;
            exit;
        }

        $hasActivePlan = !empty($data["plan_id"]) && !empty($data["plan_end"]);
        $isPlanValid = $hasActivePlan && (new DateTime() < new DateTime($data["plan_end"]));
        if ($isPlanValid == TRUE) {
            echo json_encode(["status" => 1, "requirePayment" => 0]); //included
            $requirePayment = 0;
        }
        else echo json_encode(["status" => 2, "requirePayment" => $requirePayment]); //not included
    }
    $_SESSION["invite_requirePayment"] = $requirePayment;
?>