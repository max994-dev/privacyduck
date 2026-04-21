<?php

include_once($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/mailer.php");

$email = $_POST['email'];
header('Content-Type: application/json');

$conn = getDBConnection();

$stmt = $conn->prepare("SELECT id FROM workUsers WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode([
        "error" => "User not found!"
    ]);
} else {
    try {
        if (isset($_COOKIE['work_info'])&&$_COOKIE['work_info'] == $email) {
            $stmt = $conn->prepare("SELECT * FROM workUsers WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            $_SESSION["work_user_id"] = $data["id"];
            $_SESSION["work_role"] = $data["role"];
            $_SESSION["work_mindmap_limit"] = $data["member_limit"];
            $_SESSION["work_email"] = $email;
            $_SESSION["work_phone"] = $data["phone"];
            $_SESSION["work_fullName"] = $data["firstname"] . " " . $data["lastname"];
            $_SESSION["work_isAuthenticated"] = true;
            $stmt = $conn->prepare("SELECT * FROM mindmap WHERE user_id = ? and parent = -1");
            $stmt->bind_param("i", $_SESSION["work_user_id"]);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0){
                $mindmap = $result->fetch_assoc();
                $_SESSION["mindmap_name"] = $mindmap["mindmapname"];
            }
            else $_SESSION["mindmap_name"] = "";
            setcookie("work_info", $email, time() + 60 * 60 * 24 * 10, "/");
            echo json_encode([
                "success" => "prelogin"
            ]);
        } else {
            $verificationCode = random_int(100000, 999999);
            $_SESSION["verify_code"] = $verificationCode;
            $_SESSION["work_email"] = $email;
            if (sendVerificationCodeEmail($email, $verificationCode, "PrivacyDuck.com", "Verification for Privacyduck.com")) {
                echo json_encode([
                    "success" => "verify"
                ]);
            }
        }
    } catch (Throwable $e) {
        echo json_encode([
            "error" => $e->getMessage()
        ]);
    }
}
