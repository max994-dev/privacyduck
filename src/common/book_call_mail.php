<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/mailer.php';

function book_call_send_confirmation(string $to, string $name, string $whenLocalPst, string $verifyNoteHtml): bool
{
    $subject = 'PrivacyDuck — your onboarding call is booked';
    $body = '
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 24px; border: 1px solid #e0e0e0; border-radius: 10px; background: #ffffff;">
        <h1 style="color: #010205; font-size: 20px;">Hi ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ',</h1>
        <p style="color: #333; line-height: 1.5;">Your PrivacyDuck onboarding call is confirmed.</p>
        <p style="font-size: 18px; font-weight: bold; color: #24A556;">' . htmlspecialchars($whenLocalPst, ENT_QUOTES, 'UTF-8') . '</p>
        <p style="color: #666; font-size: 14px;">Calls are scheduled between <strong>2:00 PM and 4:00 PM Pacific Time</strong>.</p>
        ' . $verifyNoteHtml . '
        <p style="color: #888; font-size: 13px; margin-top: 24px;">PrivacyDuck</p>
    </div>';
    return sendSmtpHtmlEmail($to, $subject, $body);
}

function book_call_send_reminder(string $to, string $name, string $whenLocalPst): bool
{
    $subject = 'Reminder: PrivacyDuck onboarding call';
    $body = '
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 24px; border: 1px solid #e0e0e0; border-radius: 10px; background: #ffffff;">
        <h1 style="color: #010205; font-size: 20px;">Hi ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ',</h1>
        <p style="color: #333; line-height: 1.5;">This is a reminder about your upcoming PrivacyDuck onboarding call:</p>
        <p style="font-size: 18px; font-weight: bold; color: #24A556;">' . htmlspecialchars($whenLocalPst, ENT_QUOTES, 'UTF-8') . '</p>
        <p style="color: #666; font-size: 14px;">We look forward to speaking with you.</p>
        <p style="color: #888; font-size: 13px; margin-top: 24px;">PrivacyDuck</p>
    </div>';
    return sendSmtpHtmlEmail($to, $subject, $body);
}
