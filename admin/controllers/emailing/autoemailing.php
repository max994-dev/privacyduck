<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php");
require_once $_SERVER["DOCUMENT_ROOT"] . "/src/common/smtp_env.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/src/common/smtp_relay_client.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json'); // Return JSON to browser
$email = "hello@privacyduck.com";
$verificationCode = random_int(100000, 999999);

$conn = getDBConnection();

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    echo json_encode(["status" => "warning", "message" => "No users found to email."]);
    exit;
}
$userData = $result->fetch_assoc();
$name = $userData["firstname"];
$user_id = $userData["id"];

$isAdmin = $_SESSION['admin']['isAdminAuthenticated'] ?? '';

if (!$isAdmin) {
    echo json_encode(["status" => "error", "message" => "Admin not authenticated."]);
    exit;
}

$conn = getDBConnection();
$stmt = $conn->prepare("SELECT * FROM users WHERE (plan_id IS NULL OR (plan_id > 0 AND plan_end < NOW())) AND (last_emailing_time IS NULL OR last_emailing_time < NOW() - INTERVAL 3 DAY)");
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    echo json_encode(["status" => "warning", "message" => "No users found to email."]);
    exit;
}
try {
    if ($result->num_rows == 0) {
        echo json_encode(["status" => "warning", "message" => "No results found for user."]);
        exit;
    }
    $stmt = $conn->prepare("SELECT * FROM results WHERE user_id = ? And step=2 And kind=0 Limit 3");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $scan_results = [];
    while ($row = $result->fetch_assoc()) {
        $scan_results[] = $row;
    }
    $len = count($scan_results);
    $smtpCfg = pd_smtp_config();
    $mail = new PHPMailer(true);
    pd_phpmailer_apply_smtp($mail, $smtpCfg, (int) $smtpCfg['port']);

    $mail->setFrom($smtpCfg['from_email'], $smtpCfg['from_name']);
    $mail->addAddress($email);
    $mail->Subject = 'Privacyduck.com';
    $mail->AddEmbeddedImage($_SERVER['DOCUMENT_ROOT'] . "/assets/image/desktop/duck.png", 'myimage');
    foreach ($scan_results as $key => $value) {
        $path = "/assets/uploads/".$user_id."/scan/scan_".$value['target_domain']."_".$user_id.".png";
        $mail->AddEmbeddedImage($_SERVER['DOCUMENT_ROOT'] . $path, 'myimage'.$key);
    }
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    $images = "";
    for ($i = 0; $i < count($scan_results); $i++) {
        $images .= "<img src='cid:myimage$i' alt='Scan $i' style='width: 100%; height: auto;'>";
    }
    $mail->Body = "
        <div style=\"margin: 0 !important;
            padding: 0 !important;
            background-color: #f4f7fa;
            line-height: 1.6;
            color: #333333;\">
            <div style=\"max-width: 600px;
                margin: 0 auto;
                background-color: #ffffff;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);\"
            >
                <div style=\"background: linear-gradient(135deg, #9e5ad6 0%, #24A556 100%);
                    padding: 40px 30px;
                    text-align: center;
                    color: white;\"
                >
                    <div style=\"font-size: 32px;
                        display:flex;
                        font-weight: bold;
                        margin-bottom: 10px;
                        letter-spacing: -1px;\">
                        <div style=\"
                            display:flex;
                            align-items: center;
                            margin: 0 auto;
                        \">
                            <img src=\"cid:myimage\" alt=\"Privacyduck\" style=\"width: 50px; height: 50px; margin-right: 10px;\"> Privacyduck
                        </div>
                    </div>
                    <p style=\"font-size: 16px;
                        opacity: 0.9;
                        margin: 0;\">
                        Your Privacy, Our Priority
                    </p>
                </div>
                <div style=\"padding: 40px 30px;\">
                    <div style=\"font-size: 18px;
                        margin-bottom: 20px;
                        color: #2c3e50;\">
                        Hi $name!,
                    </div>
                    <div style=\"font-size: 16px;
                        line-height: 1.7;
                        margin-bottom: 30px;
                        color: #555555;\">
                        We hope you've been enjoying the basic privacy protection that Privacyduck provides. Your digital
                        privacy matters, and we're committed to helping you stay safe online.
                        <br><br>
                        While our free tier offers essential privacy features, we wanted to let you know about the enhanced
                        protection available with Privacyduck Premium.
                    </div>
                    <div style=\"background-color: #f8fafc;
                        border-radius: 8px;
                        padding: 25px;
                        margin: 30px 0;\">
                        <div style=\"font-size: 20px;
                            font-weight: bold;
                            color: #2c3e50;
                            margin-bottom: 20px;
                            text-align: center;\">
                            🔒 Advanced Privacy Protection
                        </div>
                        <div style=\"display: flex;
                            align-items: flex-start;
                            margin-bottom: 15px;
                            padding: 10px 0;\">
                            <div style=\"
                                font-size:12px;
                                width:24px;
                                height:24px;
                                background-color: #24a556;
                                border-radius: 50%;
                                margin-right: 15px;
                                text-align:center;
                                color:white;
                                font-weight:bold;
                                flex-shrink: 0;\">
                                <span style=\"padding-top:5px;\">✓</span>
                            </div>
                            <div style=\"font-size: 15px;
                                color: #555555;
                                line-height: 1.5;\">
                                <strong>Advanced Tracker Blocking</strong><br>
                                Block 99.9% of trackers across all websites and apps
                            </div>
                        </div>
                        <div style=\"display: flex;
                            align-items: flex-start;
                            margin-bottom: 15px;
                            padding: 10px 0;\">
                            <div style=\"
                                font-size:12px;
                                width:24px;
                                height:24px;
                                background-color: #24a556;
                                border-radius: 50%;
                                margin-right: 15px;
                                text-align:center;
                                color:white;
                                font-weight:bold;
                                flex-shrink: 0;\">
                                <span style=\"padding-top:5px;\">✓</span>
                            </div>
                            <div style=\"font-size: 15px;
                                color: #555555;
                                line-height: 1.5;\">
                                <strong>Real-time Data Breach Alerts</strong><br>
                                Get instant notifications if your data appears in breaches
                            </div>
                        </div>
                        <div style=\"display: flex;
                            align-items: flex-start;
                            margin-bottom: 15px;
                            padding: 10px 0;\">
                            <div style=\"
                                font-size:12px;
                                width:24px;
                                height:24px;
                                background-color: #24a556;
                                border-radius: 50%;
                                margin-right: 15px;
                                text-align:center;
                                color:white;
                                font-weight:bold;
                                flex-shrink: 0;\">
                                <span style=\"padding-top:5px;\">✓</span>
                            </div>
                            <div style=\"font-size: 15px;
                                color: #555555;
                                line-height: 1.5;\">
                                <strong>VPN Protection</strong><br>
                                Secure browsing with military-grade encryption
                            </div>
                        </div>
                        <div style=\"display: flex;
                            align-items: flex-start;
                            margin-bottom: 15px;
                            padding: 10px 0;\">
                            <div style=\"
                                font-size:12px;
                                width:24px;
                                height:24px;
                                background-color: #24a556;
                                border-radius: 50%;
                                margin-right: 15px;
                                text-align:center;
                                color:white;
                                font-weight:bold;
                                flex-shrink: 0;\">
                                <span style=\"padding-top:5px;\">✓</span>
                            </div>
                            <div style=\"font-size: 15px;
                                color: #555555;
                                line-height: 1.5;\">
                                <strong>Identity Monitoring</strong><br>
                                24/7 monitoring of your personal information online
                            </div>
                        </div>
                        <div style=\"display: flex;
                            align-items: flex-start;
                            margin-bottom: 15px;
                            padding: 10px 0;\">
                            <div style=\"
                                font-size:12px;
                                width:24px;
                                height:24px;
                                background-color: #24a556;
                                border-radius: 50%;
                                margin-right: 15px;
                                text-align:center;
                                color:white;
                                font-weight:bold;
                                flex-shrink: 0;\">
                                <span style=\"padding-top:5px;\">✓</span>
                            </div>
                            <div style=\"font-size: 15px;
                                color: #555555;
                                line-height: 1.5;\">
                                <strong>Priority Support</strong><br>
                                Get help from our privacy experts within 2 hours
                            </div>
                        </div>
                    </div>
                    $images
                    <div style=\"background-color: #e8f4fd;
                        border: 2px solid #24A556;
                        border-radius: 8px;
                        padding: 20px;
                        margin: 25px 0;
                        text-align: center;\">
                        <div style=\"background-color: #ff6b6b;
                            color: white;
                            padding: 8px 16px;
                            border-radius: 20px;
                            font-size: 12px;
                            font-weight: bold;
                            display: inline-block;
                            margin-bottom: 15px;\">
                            LIMITED TIME OFFER
                        </div>
                        <div style=\"font-size: 28px;
                            font-weight: bold;
                            color: #24A556;
                            margin-bottom: 5px;\">
                            HAPPY
                        </div>
                        <div style=\"font-size: 14px;
                            color: #666666;
                            margin-bottom: 10px;\">
                            15% off promo code
                        </div>
                        <p style=\"margin: 0; font-size: 14px; color: #666;\">
                            Usually $ 13.38/month • Cancel anytime
                        </p>
                    </div>
                    <div style=\" text-align: center; margin: 40px 0;\">
                        <a href='https://buy.stripe.com/5kQ5kC5bBegS01u6uCdwc0M?prefilled_email=" . urlencode($email) . "&prefilled_promo_code=ARTDECO' style=\"display: inline-block;
                            background: linear-gradient(135deg, #73f1a4 0%, #24A556 100%);
                            color: white !important;
                            text-decoration: none;
                            padding: 16px 32px;
                            border-radius: 6px;
                            font-size: 16px;
                            font-weight: bold;
                            transition: all 0.3s ease;
                            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);\">
                            Upgrade to Premium Now
                        </a>
                        <p style=\"font-size: 14px; color: #666; margin-top: 15px;\">
                            30-day money-back guarantee • No commitment
                        </p>
                    </div>
                    <div style=\"font-size: 14px; color: #777; text-align: center; margin-top: 30px;\">
                        Questions? Simply reply to this email or visit our
                        <a href='' style=\"color: #667eea;\">Help Center</a>
                    </div>
                </div>

                <div style=\"background-color: #2c3e50;
                            color: #ecf0f1;
                            padding: 30px;
                            text-align: center;
                            font-size: 14px;\">
                    <p style=\"margin: 0;\">
                        © 2025 Privacyduck. All rights reserved.<br>
                        123 Privacy Street, Secure City, SC 12345
                    </p>
                    <div style=\"font-size: 12px;
                        color: #95a5a6;
                        margin-top: 15px;\">
                        Don't want to receive these emails?
                        <a style=\"color: #95a5a6;\" href='#'>
                            Unsubscribe here
                        </a>
                    </div>
                </div>
            </div>
        </div>
        ";
    $relayUrl = trim((string) ($smtpCfg['relay_url'] ?? ''));
    if ($relayUrl !== '' && ($smtpCfg['relay_secret'] ?? '') !== '') {
        $inline = [
            [
                'cid' => 'myimage',
                'path' => $_SERVER['DOCUMENT_ROOT'] . '/assets/image/desktop/duck.png',
                'mime' => 'image/png',
            ],
        ];
        foreach ($scan_results as $key => $value) {
            $inline[] = [
                'cid' => 'myimage' . $key,
                'path' => $_SERVER['DOCUMENT_ROOT'] . '/assets/uploads/' . $user_id . '/scan/scan_' . $value['target_domain'] . '_' . $user_id . '.png',
                'mime' => 'image/png',
            ];
        }
        if (pd_smtp_relay_send_html(
            $email,
            'Privacyduck.com',
            $mail->Body,
            $smtpCfg['from_email'],
            $smtpCfg['from_name'],
            strip_tags($mail->Body),
            $inline
        )) {
            echo json_encode(["status" => "success", "message" => "Email sent successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Relay send failed."]);
        }
    } elseif ($mail->send()) {
        echo json_encode(["status" => "success", "message" => "Email sent successfully."]);
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
