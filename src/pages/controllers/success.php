<?php
    include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/config.php");
    include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/mailer.php");
 
    include_once($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php");

    if(!isset($_SESSION['email'])) {
        http_response_code(500);
        die(json_encode(["error" => "Invalid Request!"]));
    }

    $verificationCode = random_int(100000, 999999);
    $_SESSION["verify_code"] = $verificationCode;

    $email = $_SESSION["email"];
    if (sendVerificationCodeEmail($email, $verificationCode, "Mattrhorn.com", "Verification for Mattrhorn.com")) {
        echo "success";
    } else {
        echo "error";
    }
?>