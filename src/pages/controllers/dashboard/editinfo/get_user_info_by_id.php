<?php
header('Content-Type: application/json');

// Auth: must be logged in.
if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Not authenticated"]);
    exit;
}

$requestedId = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
$sessionId   = (int) $_SESSION['user_id'];

if ($requestedId <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid id"]);
    exit;
}

$conn = getDBConnection();

// Authorization: caller may only read themselves OR a family member they invited.
$allowed = ($requestedId === $sessionId);
if (!$allowed) {
    $check = $conn->prepare("SELECT 1 FROM family WHERE core_id = ? AND invite_id = ? LIMIT 1");
    $check->bind_param("ii", $sessionId, $requestedId);
    $check->execute();
    $allowed = (bool) $check->get_result()->fetch_row();
    $check->close();
}

if (!$allowed) {
    http_response_code(403);
    echo json_encode(["error" => "Forbidden"]);
    exit;
}

$stmt = $conn->prepare("SELECT id, email, firstname, lastname, phone, city, zip, state, address, age, contacts, url, plan_id, plan_end FROM users WHERE id = ?");
$stmt->bind_param("i", $requestedId);
$stmt->execute();
$data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

foreach ($data as &$row) {
    $row['contacts'] = json_decode($row['contacts'] ?? '', true) ?: [];
}
unset($row);

echo json_encode($data);
?>
