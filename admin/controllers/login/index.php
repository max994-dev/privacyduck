<?php
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method not allowed."]);
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
