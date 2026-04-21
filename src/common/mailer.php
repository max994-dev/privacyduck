<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php");
require_once __DIR__ . "/smtp_env.php";
require_once __DIR__ . "/smtp_relay_client.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!function_exists("sendSmtpHtmlEmail")) {
    function sendSmtpHtmlEmail(
        string $to,
        string $subject,
        string $htmlBody,
        ?string $fromName = null,
        ?string $fromAddress = null
    ): bool {
        $cfg = pd_smtp_config();
        if ($fromAddress === null || $fromAddress === '') {
            $fromAddress = $cfg['from_email'];
        }
        if ($fromName === null || $fromName === '') {
            $fromName = $cfg['from_name'];
        }

        $relayUrl = trim((string) ($cfg['relay_url'] ?? ''));
        if ($relayUrl !== '' && ($cfg['relay_secret'] ?? '') !== '') {
            $alt = strip_tags($htmlBody);
            if (pd_smtp_relay_send_html($to, $subject, $htmlBody, $fromAddress, $fromName, $alt, [])) {
                return true;
            }
            error_log('sendSmtpHtmlEmail: relay failed, falling back to direct SMTP');
        }

        $portsToTry = [$cfg['port']];
        if (!empty($cfg['port_fallback'])) {
            $portsToTry[] = (int) $cfg['port_fallback'];
        }
        $portsToTry = array_values(array_unique($portsToTry));

        $lastException = null;
        foreach ($portsToTry as $port) {
            try {
                $mail = new PHPMailer(true);
                pd_phpmailer_apply_smtp($mail, $cfg, $port);
                $mail->setFrom($fromAddress, $fromName);
                $mail->addAddress($to);
                $mail->Subject = $subject;
                $mail->isHTML(true);
                $mail->Body = $htmlBody;
                return $mail->send();
            } catch (Exception $e) {
                $lastException = $e;
                $msg = $e->getMessage();
                $isConnect = stripos($msg, 'connect') !== false
                    || stripos($msg, 'timeout') !== false
                    || stripos($msg, 'Could not instantiate mail function') !== false;
                if (!$isConnect || count($portsToTry) < 2) {
                    break;
                }
            }
        }

        if ($lastException !== null) {
            error_log('sendSmtpHtmlEmail failed: ' . $lastException->getMessage());
        }
        return false;
    }
}

if (!function_exists("sendVerificationCodeEmail")) {
    function sendVerificationCodeEmail(
        string $to,
        int $verificationCode,
        string $brand = "PrivacyDuck.com",
        string $fromName = "Verification for Privacyduck.com",
        ?string $subject = null
    ): bool {
        $mailBody = "
            <div style=\"font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 10px; background: #ffffff;\">
                <h1 style=\"color: #333;\">Your link verification code is:</h1>
                <hr>
                <div style=\"font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #2b6cb0; text-align: center; margin: 30px 0;\">
                {$verificationCode}
                </div>
                <hr>
                <p style=\"font-size: 14px; text-align: center; color: #888;\">
                    This code will expire in 10 minutes and can only be used once. Never share this code with anyone.
                </p>
                <p style=\"font-size: 14px; text-align: center; color: #888;\">{$brand}</p>
            </div>
        ";

        return sendSmtpHtmlEmail($to, $subject !== null && $subject !== '' ? $subject : "Your Verification Code", $mailBody, $fromName);
    }
}
