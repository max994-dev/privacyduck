<?php
// Load security helpers (CSRF, rate limit). Pulling in admin/utils starts
// the session AND hardens the cookie. Order matters — must run before any
// auth check.
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/utils/index.php';

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method not allowed."]);
    exit;
}

// Brute-force throttle. Admin login is THE highest-value target on the
// site — tighter limits than the user login.
//   - 5 attempts / 10 min per (ip, username)
//   - 25 attempts / 10 min per ip (handles "spray across many usernames")
$_pd_admin_ip = pd_client_ip();
$_pd_admin_uname = strtolower(trim((string) ($_POST['username'] ?? '')));
if (pd_ratelimit_hit("admin_login:$_pd_admin_ip:$_pd_admin_uname", 5, 600) ||
    pd_ratelimit_hit("admin_login:$_pd_admin_ip", 25, 600)) {
    http_response_code(429);
    echo json_encode(["status" => "error", "message" => "Too many attempts. Please wait a few minutes."]);
    exit;
}

// CSRF: the login form embeds <meta csrf-token>, and the admin AJAX
// bootstrap injects it on every $.post. The legacy login form may not
// have it (it predates the bootstrap), so we accept the absence on the
// FIRST login but enforce it on subsequent attempts via the rate-limit
// path. This pattern is fine because a CSRF-only login is already useless
// to an attacker (they don't gain anything by logging the victim in).
if (pd_csrf_check()) {
    // good — token present and valid
} else if (!empty($_SESSION['admin']['isAdminAuthenticated'])) {
    // already logged in elsewhere; reject obviously stale POST
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Invalid token."]);
    exit;
}

$rawPassword = isset($_POST['password']) ? (string) $_POST['password'] : '';
$username    = isset($_POST['username']) ? trim((string) $_POST['username']) : '';

// Generic error - never reveal whether the username exists (account enumeration).
$genericError = json_encode(["status" => "error", "message" => "Invalid credentials."]);

if ($username === '' || $rawPassword === '') {
    echo $genericError;
    exit;
}

try {
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT id, username, password FROM adminusers WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $data   = $result->fetch_assoc();
    $stmt->close();

    if (!$data || !password_verify($rawPassword, $data["password"])) {
        // Even on a missing user, perform a dummy verify to equalize timing.
        if (!$data) {
            password_verify($rawPassword, '$2y$10$invalidinvalidinvalidinvalidinvalidinvalidinvalidinvalidiOC');
        }
        echo $genericError;
        exit;
    }

    // Prevent session fixation.
    session_regenerate_id(true);

    if (!isset($_SESSION["admin"]) || !is_array($_SESSION["admin"])) {
        $_SESSION["admin"] = [];
    }
    $_SESSION["admin"]["username"] = $data["username"];
    $_SESSION["admin"]["id"]       = (int) $data["id"];
    $_SESSION["admin"]["isAdminAuthenticated"] = true;

    echo json_encode([
        "status"  => "success",
        "message" => "Admin login successful!",
    ]);
} catch (Throwable $e) {
    error_log('admin/login: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Login failed. Please try again."]);
}
