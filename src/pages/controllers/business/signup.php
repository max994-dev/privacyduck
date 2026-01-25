<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json'); // Return JSON to browser

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Invalid request method."]);
    exit;
}

$email = $_POST['email'] ?? '';
$firstname = $_POST['firstname'] ?? '';
$lastname = $_POST['lastname'] ?? '';
$phone = $_POST['phone'] ?? '';

if (empty($email)) {
    echo json_encode(["error" => "Missing work email."]);
    exit;
}
$conn = getDBConnection();
$stmt = $conn->prepare("SELECT * FROM workUsers WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo json_encode(["error" => "Email already exists"]);
    exit;
}
$_SESSION["work_fullName"] = $firstname . " " . $lastname;
$_SESSION["work_email"] = $email;
$_SESSION["work_phone"] = $phone;

$verificationCode = random_int(100000, 999999);
$_SESSION["verify_code"] = $verificationCode;

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
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 10px; background: #ffffff;'>
            <h1 style='color: #333;'>Your link verification code is:</h1>
            <hr>
            <div style='font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #2b6cb0; text-align: center; margin: 30px 0;'>
            $verificationCode
            </div>
            <hr>
            <p style='font-size: 14px; text-align: center; color: #888;'>This code will expire in 10 minutes and can only be used once. Never share this code with anyone.</p>
            <p style='font-size: 14px; text-align: center; color: #888;'>PrivacyDuck.com</p>
        </div>
    ";
if ($mail->send()) {
    echo json_encode(["success" => "verify"]);
} else {
    echo json_encode(["error" => "Mailer error."]);
}
