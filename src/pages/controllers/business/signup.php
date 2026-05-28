<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/config.php");

// CSRF: state-mutating endpoint. Token comes from either
// <input name="csrf_token"> in the form OR the X-CSRF-Token header
// (utils.php injects it globally on jQuery.ajax/fetch).
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (function_exists('pd_csrf_require')) { pd_csrf_require(); }
}

include_once($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/mailer.php");

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

if (sendVerificationCodeEmail($email, $verificationCode, "PrivacyDuck.com", "Verification for Privacyduck.com")) {
    echo json_encode(["success" => "verify"]);
} else {
    echo json_encode(["error" => "Mailer error."]);
}
