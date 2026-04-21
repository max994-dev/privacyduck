<?php

require_once __DIR__ . '/config.php';

function pd_user_has_valid_plan(array $user): bool
{
    if (empty($user['plan_id']) || empty($user['plan_end'])) {
        return false;
    }
    try {
        return (new DateTime()) < (new DateTime($user['plan_end']));
    } catch (Exception $e) {
        return false;
    }
}

function pd_user_profile_incomplete(array $user): bool
{
    foreach (['firstname', 'lastname', 'city', 'state', 'phone', 'zip', 'address'] as $f) {
        if (!isset($user[$f]) || trim((string) $user[$f]) === '') {
            return true;
        }
    }
    return false;
}

function pd_apply_user_session_from_row(array $data, string $email): void
{
    $hasActivePlan = !empty($data['plan_id']) && !empty($data['plan_end']);
    $isPlanValid = $hasActivePlan && (new DateTime() < new DateTime($data['plan_end']));
    $_SESSION['plan_id'] = $data['plan_id'];
    $_SESSION['user_id'] = $data['id'];
    $_SESSION['signup_complete'] = !empty($data['plan_id']) ? 1 : 0;
    $_SESSION['planable'] = $isPlanValid;
    $_SESSION['isAuthenticated'] = true;
    $_SESSION['email'] = $email;
    $_SESSION['fullName'] = trim(($data['firstname'] ?? '') . ' ' . ($data['lastname'] ?? ''));
    if ($_SESSION['fullName'] === '') {
        $_SESSION['fullName'] = $email;
    }
    setcookie('info', $email, time() + 60 * 60 * 24 * 10, '/');
}

function pd_new_landing_post_auth_redirect_url(array $user): string
{
    $base = WEB_DOMAIN;
    if (!pd_user_has_valid_plan($user)) {
        return $base . '/pricing';
    }
    if (pd_user_profile_incomplete($user)) {
        return $base . '/dashboard/editinfo';
    }
    return $base . '/dashboard';
}
