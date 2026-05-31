<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/common/security.php';

// CSRF: state-mutating endpoint. Token comes from either
// <input name="csrf_token"> in the form OR the X-CSRF-Token header
// (utils.php injects it globally on jQuery.ajax/fetch).
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (function_exists('pd_csrf_require')) { pd_csrf_require(); }
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Invalid request method."]);
    exit;
}

$user_id = $_SESSION["user_id"] ?? null;
$email   = $_SESSION['email']   ?? null;
if (!$user_id || !$email) {
    echo json_encode(["error" => "User not logged in."]);
    exit;
}

$firstname = trim((string) ($_POST['first_name'] ?? ''));
$lastname  = trim((string) ($_POST['last_name']  ?? ''));
$contactsRaw = $_POST["contacts"] ?? '[{"city":"","state":"","phone":"","zip":"","address":""}]';
$contactsDecoded = json_decode($contactsRaw, true);
$contacts = is_array($contactsDecoded) && $contactsDecoded !== [] ? $contactsDecoded : [[
    "city" => "", "state" => "", "phone" => "", "zip" => "", "address" => "",
]];

$primary = $contacts[0];
$city    = (string) ($primary["city"]    ?? '');
$state   = (string) ($primary["state"]   ?? '');
$phone   = (string) ($primary["phone"]   ?? '');
$zip     = (string) ($primary["zip"]     ?? '');
$address = (string) ($primary["address"] ?? '');

// birth_date is optional in the request: absent = preserve existing, supplied
// + valid = update + recompute age. Brokers require DOB; if the user never
// provides one the removal pipeline marks their rows missing_pii.
$birthDateInput = trim((string) ($_POST['birth_date'] ?? ''));
$birthDateToSave = null;
$ageToSave = null;
if ($birthDateInput !== '') {
    try {
        $dt = new DateTime($birthDateInput);
        $today = new DateTime('today');
        if ($dt > $today || $dt < (new DateTime())->modify('-120 years')) {
            echo json_encode(["error" => "Please enter a realistic date of birth."]);
            exit;
        }
        $birthDateToSave = $dt->format('Y-m-d');
        $ageToSave = (int) $dt->diff($today)->y;
    } catch (Exception $e) {
        echo json_encode(["error" => "Invalid date of birth."]);
        exit;
    }
}

if ($firstname === '' || $lastname === '') {
    echo json_encode(["error" => "Missing firstname or lastname."]);
    exit;
}

$filename = "";

if (isset($_FILES['file']) && is_array($_FILES['file']) && ($_FILES['file']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
    $uploadedFile = $_FILES['file'];

    if (($uploadedFile['size'] ?? 0) > 5 * 1024 * 1024) {
        http_response_code(413);
        echo json_encode(["error" => "File too large"]);
        exit;
    }

    // MIME-based extension whitelist - never trust the client-provided extension.
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($uploadedFile['tmp_name']) ?: '';
    $extMap = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp',
    ];
    if (!isset($extMap[$mime])) {
        http_response_code(415);
        echo json_encode(["error" => "Unsupported file type"]);
        exit;
    }
    $ext = $extMap[$mime];

    $uploadDir = BASEPATH . "/assets/uploads/specialinfo";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Sanitize email to a safe filename token (no path traversal, no shell meta).
    $safeKey  = preg_replace('/[^A-Za-z0-9._-]/', '_', (string) $email);
    if ($safeKey === '' || $safeKey === null) {
        $safeKey = bin2hex(random_bytes(8));
    }
    $filename   = "img_" . $safeKey . "." . $ext;
    $targetFile = $uploadDir . "/" . $filename;

    if (!move_uploaded_file($uploadedFile['tmp_name'], $targetFile)) {
        http_response_code(500);
        echo json_encode(["error" => "Failed to move uploaded file"]);
        exit;
    }
    @chmod($targetFile, 0644);
}

try {
    $conn = getDBConnection();

    $json_contacts = json_encode($contacts);

    // Two UPDATE shapes: when birth_date is being set we also bump age, so
    // both legs stay in sync. Otherwise the existing columns are left
    // untouched. Keeping the bind_param matched to the SQL shape is more
    // explicit than building one query with conditional NULLs.
    if ($birthDateToSave !== null) {
        $stmt = $conn->prepare(
            "UPDATE users SET firstname = ?, lastname = ?, phone = ?, city = ?, zip = ?, state = ?, address = ?, contacts = ?, url = ?, birth_date = ?, age = ? WHERE id = ?"
        );
        $stmt->bind_param(
            "ssssssssssii",
            $firstname, $lastname, $phone, $city, $zip, $state,
            $address, $json_contacts, $filename, $birthDateToSave,
            $ageToSave, $user_id
        );
    } else {
        $stmt = $conn->prepare(
            "UPDATE users SET firstname = ?, lastname = ?, phone = ?, city = ?, zip = ?, state = ?, address = ?, contacts = ?, url = ? WHERE id = ?"
        );
        $stmt->bind_param(
            "sssssssssi",
            $firstname, $lastname, $phone, $city, $zip, $state,
            $address, $json_contacts, $filename, $user_id
        );
    }
    $stmt->execute();
    $stmt->close();

    // FACE REMOVAL re-dispatch: if a new face image was uploaded in
    // this request, ensure a kind=4 results row exists AND is reset
    // to step=0 so the Python pipeline picks up the new image.
    //   - If no kind=4 row yet -> insert one (matches dashboard_bootstrap)
    //   - If row exists -> reset step + step counters + update data JSON
    //     with the new filename so the broker uses the latest upload
    // $filename is non-empty only when a new image was uploaded this request.
    if ($filename !== '') {
        $faceTargets = ['pimeyescom' => ['https://pimeyes.com', 'https://pimeyes.com/en/opt-out-request-form']];
        $faceDataJson = json_encode([
            "face_filename" => $filename,
            "user_email"    => (string) $email,
        ]);
        foreach ($faceTargets as $slug => [$siteUrl, $removalUrl]) {
            $checkStmt = $conn->prepare(
                "SELECT id FROM results WHERE user_id = ? AND kind = 4 AND target_domain = ? LIMIT 1"
            );
            $checkStmt->bind_param("is", $user_id, $slug);
            $checkStmt->execute();
            $exists = $checkStmt->get_result()->fetch_assoc();
            $checkStmt->close();
            if ($exists) {
                $rid = (int) $exists['id'];
                $up = $conn->prepare("UPDATE results SET step = 0, data = ? WHERE id = ?");
                $up->bind_param("si", $faceDataJson, $rid);
                $up->execute();
                $up->close();
            } else {
                $ins = $conn->prepare(
                    "INSERT INTO results (target_domain, user_id, kind, step, planable, site_url, removal_url, data)
                     VALUES (?, ?, 4, 0, 1, ?, ?, ?)"
                );
                $ins->bind_param("sisss", $slug, $user_id, $siteUrl, $removalUrl, $faceDataJson);
                $ins->execute();
                $ins->close();
            }
        }
    }

    $_SESSION["fullName"] = $firstname . " " . $lastname;
    $_SESSION["signup_complete"] = 1;
    unset($_SESSION["needs_profile_info"]);

    echo json_encode(["success" => "success"]);
} catch (Throwable $e) {
    error_log('update_user_info: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Update failed."]);
}
?>
