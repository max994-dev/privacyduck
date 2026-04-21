<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/config.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/utils.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/database.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/mailer.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php");

header('Content-Type: application/json'); // Return JSON to browser

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Invalid request method."]);
    exit;
}

$email = $_POST['email'] ?? '';
$fullname = $_POST['fullname'] ?? '';

if (empty($email) || empty($fullname)) {
    echo json_encode(["error" => "Missing email or fullname."]);
    exit;
}

$conn = getDBConnection();
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo json_encode(["error" => "Email already exists"]);
    exit;
}
$verificationCode = random_int(100000, 999999);
$_SESSION["verify_code"] = $verificationCode;
$_SESSION["email"] = $email;
$_SESSION["fullName"] = $fullname;

if (email_verification_bypassed($email)) {
    echo json_encode(["success" => "verify"]);
} elseif (sendVerificationCodeEmail($email, $verificationCode, "PrivacyDuck.com", "Verification for Privacyduck.com")) {
    echo json_encode(["success" => "verify"]);
} else {
    echo json_encode(["error" => "Mailer error."]);
}
