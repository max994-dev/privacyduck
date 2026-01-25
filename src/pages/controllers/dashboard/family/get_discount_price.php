<?php
if (!isset($_SESSION["planable"]) || $_SESSION["planable"] == 0) {
    http_response_code(500);
    die(json_encode(["error" => "Planable error!"]));
}
header('Content-Type: application/json');
if (isset($_SESSION["plan_id"])) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT * FROM plans WHERE id = ?");
    $stmt->bind_param("i", $_SESSION["plan_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $expected_members = $data["member_number"];
    if ($expected_members == 0) {
        echo json_encode(["error" => "You can't invite members. Please update your plan to family or couple plan first!"]);
        die();
    }
    $stmt = $conn->prepare("SELECT * FROM family WHERE core_id = ? And status = 0");
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;

    $remain_members = $expected_members - $count;
    $exceeded_members = $count-$expected_members;
    if ($remain_members < 1) {
        $person = "invite-" . ($exceeded_members >= 4 ? 4 : ($exceeded_members+1));
        $stmt = $conn->prepare("SELECT * FROM plans WHERE person = ?");
        $stmt->bind_param("s", $person);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        echo json_encode(["success" => "pay", "data" => $data]);
        $_SESSION["invite_count"] = $count;
        $_SESSION["invite_price"] = $data["price"];
    }else{
        echo json_encode(["success" =>"free"]);
        die();
    }
} else {
    echo json_encode(["error" => "You can't invite members. Please select a couple or family plan first!"]);
}
