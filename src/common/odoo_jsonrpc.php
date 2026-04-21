<?php

/**
 * Odoo JSON-RPC (Odoo 14+). Set ODOO_* in config.php. If URL empty, sync is skipped.
 */
function odoo_jsonrpc_call(string $url, string $service, string $method, array $args)
{
    $payload = [
        'jsonrpc' => '2.0',
        'method' => 'call',
        'params' => [
            'service' => $service,
            'method' => $method,
            'args' => $args,
        ],
        'id' => random_int(1, 999999999),
    ];
    $ch = curl_init(rtrim($url, '/') . '/jsonrpc');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 25,
    ]);
    $raw = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    if ($raw === false || $raw === '') {
        error_log('odoo_jsonrpc: ' . $err);
        return null;
    }
    $decoded = json_decode($raw, true);
    if (!is_array($decoded)) {
        error_log('odoo_jsonrpc: invalid JSON');
        return null;
    }
    if (isset($decoded['error'])) {
        error_log('odoo_jsonrpc error: ' . json_encode($decoded['error']));
        return null;
    }
    return $decoded['result'] ?? null;
}

function odoo_is_configured(): bool
{
    return defined('ODOO_URL') && ODOO_URL !== ''
        && defined('ODOO_DB') && ODOO_DB !== ''
        && defined('ODOO_USER') && ODOO_USER !== ''
        && defined('ODOO_PASSWORD');
}

function odoo_authenticate(): ?int
{
    if (!odoo_is_configured()) {
        return null;
    }
    $uid = odoo_jsonrpc_call(ODOO_URL, 'common', 'authenticate', [ODOO_DB, ODOO_USER, ODOO_PASSWORD, []]);
    if (!is_int($uid) && !is_numeric($uid)) {
        return null;
    }
    return (int) $uid;
}

function odoo_execute_kw(int $uid, string $model, string $method, array $args): mixed
{
    return odoo_jsonrpc_call(ODOO_URL, 'object', 'execute_kw', [
        ODOO_DB,
        $uid,
        ODOO_PASSWORD,
        $model,
        $method,
        $args,
    ]);
}

function odoo_normalize_create_id(mixed $create): ?int
{
    if (is_int($create)) {
        return $create;
    }
    if (is_numeric($create)) {
        return (int) $create;
    }
    return null;
}

function odoo_create_calendar_event_with_uid(int $uid, string $title, string $startUtc, string $stopUtc, string $partnerEmail): ?int
{
    $vals = [
        'name' => $title,
        'start' => $startUtc,
        'stop' => $stopUtc,
        'allday' => false,
    ];
    if ($partnerEmail !== '') {
        $vals['description'] = 'Customer email: ' . $partnerEmail;
    }
    $create = odoo_execute_kw($uid, 'calendar.event', 'create', [$vals]);

    return odoo_normalize_create_id($create);
}

/**
 * Create a calendar.event in Odoo. Returns event id (int) or null.
 */
function odoo_create_calendar_event(string $title, string $startUtc, string $stopUtc, string $partnerEmail): ?int
{
    $uid = odoo_authenticate();
    if ($uid === null) {
        return null;
    }

    return odoo_create_calendar_event_with_uid($uid, $title, $startUtc, $stopUtc, $partnerEmail);
}

/**
 * Create a CRM lead (crm.lead) for a booked onboarding call. Returns lead id or null.
 */
function odoo_create_crm_lead_with_uid(
    int $uid,
    string $title,
    string $contactName,
    string $email,
    string $phone,
    string $startUtc,
    string $endUtc,
    string $whenLocalLabel,
    string $referenceSnippet,
    ?int $linkedCalendarEventId = null
): ?int {
    $lines = [
        'PrivacyDuck — member booked an onboarding call.',
        '',
        'When (Pacific): ' . $whenLocalLabel,
        'Start (UTC): ' . $startUtc,
        'End (UTC): ' . $endUtc,
        '',
        'Contact: ' . $contactName,
        'Email: ' . $email,
    ];
    if ($phone !== '') {
        $lines[] = 'Phone: ' . $phone;
    }
    $lines[] = '';
    $lines[] = 'Reference: ' . $referenceSnippet;
    if ($linkedCalendarEventId !== null && $linkedCalendarEventId > 0) {
        $lines[] = 'Calendar event id: ' . $linkedCalendarEventId;
    }

    $vals = [
        'name' => $title,
        'email_from' => $email,
        'contact_name' => $contactName,
        'description' => implode("\n", $lines),
        'type' => 'opportunity',
    ];
    if ($phone !== '') {
        $vals['phone'] = $phone;
    }
    if (defined('ODOO_CRM_TEAM_ID') && (int) ODOO_CRM_TEAM_ID > 0) {
        $vals['team_id'] = (int) ODOO_CRM_TEAM_ID;
    }

    $create = odoo_execute_kw($uid, 'crm.lead', 'create', [$vals]);

    return odoo_normalize_create_id($create);
}

function odoo_create_crm_lead_for_booking(
    string $title,
    string $contactName,
    string $email,
    string $phone,
    string $startUtc,
    string $endUtc,
    string $whenLocalLabel,
    string $referenceSnippet,
    ?int $linkedCalendarEventId = null
): ?int {
    $uid = odoo_authenticate();
    if ($uid === null) {
        return null;
    }

    return odoo_create_crm_lead_with_uid(
        $uid,
        $title,
        $contactName,
        $email,
        $phone,
        $startUtc,
        $endUtc,
        $whenLocalLabel,
        $referenceSnippet,
        $linkedCalendarEventId
    );
}
