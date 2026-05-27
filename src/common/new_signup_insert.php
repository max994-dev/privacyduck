<?php

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/stripe_signup_sync.php';

/**
 * @return array{ok:true,data:array<string,string>}|array{ok:false,error:string}
 */
function pd_new_signup_parse_profile_from_post(array $post): array
{
    $firstname = trim((string) ($post['firstname'] ?? ''));
    $lastname = trim((string) ($post['lastname'] ?? ''));
    $country = trim((string) ($post['country'] ?? ''));
    $address = trim((string) ($post['address'] ?? ''));
    $city = trim((string) ($post['city'] ?? ''));
    $state = trim((string) ($post['state'] ?? ''));
    $zip = trim((string) ($post['zip'] ?? ''));
    $nameVariations = trim((string) ($post['name_variations'] ?? ''));
    $birthDate = trim((string) ($post['birth_date'] ?? ''));
    $phone = trim((string) ($post['phone'] ?? ''));
    $phoneCountry = trim((string) ($post['phone_country'] ?? 'US'));

    $allowedCountries = ['US', 'UK', 'CA', 'EU'];
    $allowedPhoneCc = ['US', 'CA', 'FR', 'UK', 'DE', 'ES', 'IT', 'NL', 'SE'];

    if ($firstname === '' || (function_exists('mb_strlen') ? mb_strlen($firstname) : strlen($firstname)) > 200) {
        return ['ok' => false, 'error' => 'Please enter a valid first name.'];
    }
    if ($lastname === '' || (function_exists('mb_strlen') ? mb_strlen($lastname) : strlen($lastname)) > 200) {
        return ['ok' => false, 'error' => 'Please enter a valid last name.'];
    }
    if (!in_array($country, $allowedCountries, true)) {
        return ['ok' => false, 'error' => 'Please select your country.'];
    }
    if ($address === '' || (function_exists('mb_strlen') ? mb_strlen($address) : strlen($address)) > 500) {
        return ['ok' => false, 'error' => 'Please enter your full street address.'];
    }
    if ($city === '' || (function_exists('mb_strlen') ? mb_strlen($city) : strlen($city)) > 120) {
        return ['ok' => false, 'error' => 'Please enter your city.'];
    }
    if ($state === '' || (function_exists('mb_strlen') ? mb_strlen($state) : strlen($state)) > 120) {
        return ['ok' => false, 'error' => 'Please enter your state or region.'];
    }
    if ($zip === '' || strlen($zip) > 32) {
        return ['ok' => false, 'error' => 'Please enter a valid postal / ZIP code.'];
    }
    if ((function_exists('mb_strlen') ? mb_strlen($nameVariations) : strlen($nameVariations)) > 4000) {
        return ['ok' => false, 'error' => 'Name and address variations are too long (max 4000 characters).'];
    }
    if ($birthDate === '') {
        return ['ok' => false, 'error' => 'Please enter your date of birth.'];
    }
    try {
        $dob = new DateTime($birthDate);
    } catch (Exception $e) {
        return ['ok' => false, 'error' => 'Please enter a valid date of birth.'];
    }
    $today = new DateTime('today');
    if ($dob > $today) {
        return ['ok' => false, 'error' => 'Date of birth cannot be in the future.'];
    }
    if ($dob < (new DateTime())->modify('-120 years')) {
        return ['ok' => false, 'error' => 'Please enter a realistic date of birth.'];
    }
    $digits = preg_replace('/\D+/', '', $phone) ?? '';
    if (strlen($digits) < 7 || strlen($digits) > 20) {
        return ['ok' => false, 'error' => 'Please enter a valid phone number.'];
    }
    if (!in_array($phoneCountry, $allowedPhoneCc, true)) {
        return ['ok' => false, 'error' => 'Please select a phone country code.'];
    }

    return [
        'ok' => true,
        'data' => [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'country' => $country,
            'address' => $address,
            'city' => $city,
            'state' => $state,
            'zip' => $zip,
            'name_variations' => $nameVariations,
            'birth_date' => $dob->format('Y-m-d'),
            'phone' => $phone,
            'phone_country' => $phoneCountry,
        ],
    ];
}

function pd_new_signup_age_from_birthdate(string $birthYmd): int
{
    try {
        $dob = new DateTime($birthYmd);
        $now = new DateTime('today');

        return max(0, (int) $dob->diff($now)->y);
    } catch (Exception $e) {
        return 0;
    }
}

/**
 * Create a password-based user from the new signup flow (no face photo).
 * $profile: output of pd_new_signup_parse_profile_from_post()['data'], or null for legacy minimal row.
 *
 * UK GDPR Art. 7(1) audit trail params:
 *   $consentVersion       - effective date of the privacy policy version the
 *                           user accepted (e.g. '2026-05-26'). NULL for legacy.
 *   $policyConsentAt      - DATETIME string (Y-m-d H:i:s) when the user
 *                           accepted the policy. NULL for legacy.
 *   $marketingConsentAt   - DATETIME string when the user opted in to
 *                           marketing emails. NULL when they did not opt in.
 *
 * @param array<string,string>|null $profile
 * @return array<string,mixed>|null
 */
function pd_insert_new_signup_user(
    string $email,
    string $passwordHash,
    int $agreeMarketing,
    ?array $profile = null,
    ?string $consentVersion = null,
    ?string $policyConsentAt = null,
    ?string $marketingConsentAt = null
): ?array {
    $conn = getDBConnection();
    $stmt = $conn->prepare('SELECT id FROM users WHERE LOWER(TRIM(email)) = LOWER(?)');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $stmt->close();
        $conn->close();

        return null;
    }
    $stmt->close();

    if ($profile !== null && $profile !== []) {
        $firstname = $profile['firstname'];
        $lastname = $profile['lastname'];
        $phone = $profile['phone'];
        $address = $profile['address'];
        $city = $profile['city'];
        $state = $profile['state'];
        $zip = $profile['zip'];
        $age = pd_new_signup_age_from_birthdate($profile['birth_date']);
        $country = $profile['country'];
        $nameVariations = $profile['name_variations'];
        $phoneCountry = $profile['phone_country'];
    } else {
        $firstname = 'Member';
        $lastname = 'User';
        $phone = '';
        $address = '';
        $city = '';
        $state = '';
        $zip = '';
        $age = 0;
        $country = '';
        $nameVariations = '';
        $phoneCountry = '';
    }

    $contacts = [[
        'city' => $city,
        'state' => $state,
        'phone' => $phone,
        'zip' => $zip,
        'address' => $address,
        'country' => $country,
        'name_variations' => $nameVariations,
        'phone_country' => $phoneCountry,
        'marketing_opt_in' => $agreeMarketing ? 1 : 0,
    ]];
    $contactsJson = json_encode($contacts);
    $createdAt = date('Y-m-d H:i:s');
    $url = '';

    $stmt = $conn->prepare(
        'INSERT INTO users (email, firstname, lastname, phone, city, zip, state, age, address, contacts, role, created_at, password, url, consent_policy_version, policy_consent_at, marketing_consent_at) '
        . 'VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->bind_param(
        'sssssssisssssssss',
        $email,
        $firstname,
        $lastname,
        $phone,
        $city,
        $zip,
        $state,
        $age,
        $address,
        $contactsJson,
        $createdAt,
        $passwordHash,
        $url,
        $consentVersion,
        $policyConsentAt,
        $marketingConsentAt
    );

    if (!$stmt->execute()) {
        $stmt->close();
        $conn->close();

        return null;
    }
    $userId = (int) $conn->insert_id;
    $stmt->close();

    stripe_sync_privacyduck_subscription_for_email($conn, $email, true);

    $stmt = $conn->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    $conn->close();

    return $data ?: null;
}
