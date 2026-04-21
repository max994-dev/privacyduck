<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/config.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/utils.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/database.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/mailer.php");

include_once($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php");

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
            if (email_verification_bypassed($email)) {
                $hasActivePlan = !empty($data["plan_id"]) && !empty($data["plan_end"]);
                $isPlanValid = $hasActivePlan && (new DateTime() < new DateTime($data["plan_end"]));
                $_SESSION["plan_id"] = $data["plan_id"];
                $_SESSION["user_id"] = $data["id"];
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
                $verificationCode = random_int(100000, 999999);
                $_SESSION["verify_code"] = $verificationCode;
                $_SESSION["email"] = $email;
                if (sendVerificationCodeEmail($email, $verificationCode, "PrivacyDuck.com", "Verification for Privacyduck.com")) {
                    echo json_encode([
                        "success" => "verify"
                    ]);
                }
            }
        }
    } catch (Throwable $e) {
        echo json_encode([
            "error" => $e->getMessage()
        ]);
    }
}
