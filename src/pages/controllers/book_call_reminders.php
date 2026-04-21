<?php

/**
 * Cron: GET /book_call_reminders?key=BOOK_CALL_CRON_KEY
 * Sends reminder emails ~24h before each scheduled call.
 */

include_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/book_call_schema.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/book_call_mail.php';

header('Content-Type: text/plain; charset=utf-8');

$key = $_GET['key'] ?? '';
if (!hash_equals(BOOK_CALL_CRON_KEY, $key)) {
    http_response_code(403);
    echo 'Forbidden';
    exit;
}

$conn = getDBConnection();
book_call_ensure_table($conn);

$now = new DateTime('now', new DateTimeZone('UTC'));
$from = (clone $now)->modify('+23 hours');
$to = (clone $now)->modify('+25 hours');

$sql = 'SELECT bc.id, bc.email, bc.scheduled_start_utc, bc.verification_token, u.firstname, u.lastname
    FROM book_calls bc
    JOIN users u ON u.id = bc.user_id
    WHERE bc.reminder_sent = 0
    AND bc.scheduled_start_utc >= ?
    AND bc.scheduled_start_utc <= ?';
$stmt = $conn->prepare($sql);
$fs = $from->format('Y-m-d H:i:s');
$ts = $to->format('Y-m-d H:i:s');
$stmt->bind_param('ss', $fs, $ts);
$stmt->execute();
$res = $stmt->get_result();
$sent = 0;
$tz = new DateTimeZone('America/Los_Angeles');
while ($row = $res->fetch_assoc()) {
    $dt = new DateTime($row['scheduled_start_utc'], new DateTimeZone('UTC'));
    $dt->setTimezone($tz);
    $when = $dt->format('l, F j, Y \a\t g:i A T');
    $name = trim(($row['firstname'] ?? '') . ' ' . ($row['lastname'] ?? ''));
    if ($name === '') {
        $name = $row['email'];
    }
    if (book_call_send_reminder($row['email'], $name, $when)) {
        $up = $conn->prepare('UPDATE book_calls SET reminder_sent = 1 WHERE id = ?');
        $id = (int) $row['id'];
        $up->bind_param('i', $id);
        $up->execute();
        $up->close();
        $sent++;
    }
}
$stmt->close();
$conn->close();

echo 'OK reminders_sent=' . $sent;
