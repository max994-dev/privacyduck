<?php

/**
 * Allowed booking window: 14:00–16:00 America/Los_Angeles (PST/PDT).
 */
function book_call_tz(): DateTimeZone
{
    return new DateTimeZone('America/Los_Angeles');
}

/**
 * @return string[] ISO date keys (Y-m-d) for next $days days (including today if slots remain)
 */
function book_call_available_dates(int $days = 21): array
{
    $tz = book_call_tz();
    $out = [];
    $now = new DateTime('now', $tz);
    $labels = book_call_slot_labels();
    for ($i = 0; $i < $days; $i++) {
        $d = (clone $now)->modify("+{$i} days")->format('Y-m-d');
        $hasAnyFutureSlot = false;
        foreach ($labels as $slotLabel) {
            if (book_call_resolve_slot($d, $slotLabel) !== null) {
                $hasAnyFutureSlot = true;
                break;
            }
        }
        if ($hasAnyFutureSlot) {
            $out[] = $d;
        }
    }
    return $out;
}

/**
 * Fixed 30-minute slots starting 2:00 PM PT until 4:00 PM PT (last slot 3:30–4:00).
 *
 * @return string[] labels like "2:00 PM"
 */
function book_call_slot_labels(): array
{
    return ['2:00 PM', '2:30 PM', '3:00 PM', '3:30 PM'];
}

/**
 * @param string $dateYmd Y-m-d in PT calendar sense
 * @param string $slotLabel one of book_call_slot_labels()
 * @return array{0: string, 1: string, 2: string} [startUtc, endUtc, displayPst]
 */
function book_call_resolve_slot(string $dateYmd, string $slotLabel): ?array
{
    $labels = book_call_slot_labels();
    if (!in_array($slotLabel, $labels, true)) {
        return null;
    }
    $tz = book_call_tz();
    $idx = array_search($slotLabel, $labels, true);
    $minutesFrom2pm = $idx * 30;
    $startHour = 14 + intdiv($minutesFrom2pm, 60);
    $startMin = $minutesFrom2pm % 60;
    $start = new DateTime($dateYmd . sprintf(' %02d:%02d:00', $startHour, $startMin), $tz);
    $end = (clone $start)->modify('+30 minutes');
    if ((int) $end->format('G') > 16 || ((int) $end->format('G') === 16 && (int) $end->format('i') > 0)) {
        return null;
    }
    $now = new DateTime('now', $tz);
    if ($start < $now) {
        return null;
    }
    $startUtc = clone $start;
    $startUtc->setTimezone(new DateTimeZone('UTC'));
    $endUtc = clone $end;
    $endUtc->setTimezone(new DateTimeZone('UTC'));
    $display = $start->format('l, F j, Y \a\t g:i A T');
    return [
        $startUtc->format('Y-m-d H:i:s'),
        $endUtc->format('Y-m-d H:i:s'),
        $display,
    ];
}
