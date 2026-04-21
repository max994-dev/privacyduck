<?php
    if (!isset($_SESSION["planable"]) || $_SESSION["planable"] == 0) {
        http_response_code(500);
        die(json_encode(["error" => "Planable error!"]));
    }
    if (!isset($_SESSION["invite_requirePayment"])) {
        http_response_code(500);
        die(json_encode(["error" => "Invite require payment error!"]));
    }
    if (isset($_SESSION["invite_requirePayment"]) && $_SESSION["invite_requirePayment"] == 1 &&  (!isset($_SESSION["invite_pay_verified"]) || $_SESSION["invite_pay_verified"] == 0)) {
        http_response_code(500);
        die(json_encode(["error" => "Invite payment verification error!"]));
    }
    $first_name = htmlspecialchars($_POST["first_name"]);
    $last_name = htmlspecialchars($_POST["last_name"]);
    $contacts = $_POST["contacts"] ?? [];
    $city = $contacts[0]["city"] ?? '';
    $state = $contacts[0]["state"] ?? '';
    $phone = $contacts[0]["phone"] ?? '';
    $zip = $contacts[0]["zip"] ?? '';
    $address = $contacts[0]["address"] ?? '';
    $email = htmlspecialchars($_POST["email"]);
    $conn = getDBConnection();

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $included = 0;
    $invite_id = -1;
    if ($result->num_rows == 0) {
        $contactsJson = json_encode($contacts);
        $stmt = $conn->prepare("INSERT INTO users (email, firstname, lastname, city, state, phone, zip, address, contacts) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $email, $first_name, $last_name, $city, $state, $phone, $zip, $address, $contactsJson);
        $stmt->execute();
        $invite_id = $stmt->insert_id;
    } else {
        $data = $result->fetch_assoc();
        $hasActivePlan = !empty($data["plan_id"]) && !empty($data["plan_end"]);
        $isPlanValid = $hasActivePlan && (new DateTime() < new DateTime($data["plan_end"]));
        if ($isPlanValid) $included = 1;
        $invite_id = $data["id"];
    }

    $stmt = $conn->prepare("SELECT * FROM family WHERE core_id = ? And invite_id = ?");
    $stmt->bind_param("ii", $_SESSION["user_id"], $invite_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        if (isset($_SESSION["invite_pay_verified"])) unset($_SESSION["invite_pay_verified"]);
        if (isset($_SESSION["invite_pay_verified_at"])) unset($_SESSION["invite_pay_verified_at"]);
        if (isset($_SESSION["addon_invitee_email"])) unset($_SESSION["addon_invitee_email"]);
        echo "repeat";
        exit;
    }

    $status = $included;
    $display_status = $included==1?"Included":"Invite";
    $stmt = $conn->prepare("INSERT INTO family (core_id, invite_id, status, display_status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $_SESSION["user_id"], $invite_id, $status, $display_status);
    $stmt->execute();
    echo "success";
    if (isset($_SESSION["invite_requirePayment"])) unset($_SESSION["invite_requirePayment"]);
    if (isset($_SESSION["invite_pay_verified"])) unset($_SESSION["invite_pay_verified"]);
    if (isset($_SESSION["invite_pay_verified_at"])) unset($_SESSION["invite_pay_verified_at"]);
    if (isset($_SESSION["addon_invitee_email"])) unset($_SESSION["addon_invitee_email"]);
    if (isset($_SESSION["invite_count"])) unset($_SESSION["invite_count"]);
    if (isset($_SESSION["invite_price"])) unset($_SESSION["invite_price"]);
?>