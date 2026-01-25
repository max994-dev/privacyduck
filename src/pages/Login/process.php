<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/config.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/utils.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/database.php");

include_once($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = $_POST['email'];
header('Content-Type: application/json');

$conn = getDBConnection();

$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode([
        "error" => "Invalid user!"
    ]);
} else {
    try {
        if (isset($_COOKIE['info']) && $_COOKIE['info'] == $email) {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            if($data["role"] < 1){
                echo json_encode([
                    "error" => "Invalid user!"
                ]);
                exit;
            }
            $hasActivePlan = !empty($data["plan_id"]) && !empty($data["plan_end"]);
            $isPlanValid = $hasActivePlan && (new DateTime() < new DateTime($data["plan_end"]));
            $_SESSION["plan_id"] = $data["plan_id"];
            $_SESSION["user_id"] = $data["id"];
	    // workaround: treat users with a plan as "signup complete"2026
	    $_SESSION["signup_complete"] = !empty($data["plan_id"]) ? 1 : 0;
            $_SESSION["planable"] = $isPlanValid;
            $_SESSION["isAuthenticated"] = true;
            $_SESSION["email"] = $email;
            $_SESSION["fullName"] = $data["firstname"] . " " . $data["lastname"];
            setcookie("info", $email, time() + 60 * 60 * 24 * 10, "/");
            echo json_encode([
                "success" => "prelogin"
            ]);
        } else {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            if($data["role"] < 1){
                echo json_encode([
                    "error" => "Invalid user!"
                ]);
                exit;
            }
            $verificationCode = random_int(100000, 999999);
            $_SESSION["verify_code"] = $verificationCode;
            $_SESSION["email"] = $email;
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'mail1.privacypros.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'support';
            $mail->Password = '27!Soldisonosoldi';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('support@privacypros.com', 'Verification for Privacyduck.com');
            $mail->addAddress($email);
            $mail->Subject = 'Your Verification Code';
            $mail->isHTML(true);
            $mail->Body = "
                <div style=\"font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 10px; background: #ffffff;\">
                    <h1 style=\"color: #333;\">Your link verification code is:</h1>
                    <hr>
                    <div style=\"font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #2b6cb0; text-align: center; margin: 30px 0;\">
                    " . $verificationCode . "
                    </div>
                    <hr>
                    <p style=\"font-size: 14px; text-align: center; color: #888;\">
                        This code will expire in 10 minutes and can only be used once. Never share this code with anyone.
                    </p>
                    <p style=\"font-size: 14px; text-align: center; color: #888;\">PrivacyDuck.com</p>
                </div>
            ";
            if ($mail->send()) {
                echo json_encode([
                    "success" => "verify"
                ]);
            }
        }
    } catch (Exception $e) {
        echo json_encode([
            "error" => $e->getMessage()
        ]);
    }
}
