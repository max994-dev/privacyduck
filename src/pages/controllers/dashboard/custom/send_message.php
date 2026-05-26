<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "method-not-allowed";
    exit;
}

if (empty($_SESSION["user_id"]) || empty($_SESSION["planable"])) {
    http_response_code(403);
    echo "forbidden";
    exit;
}

$message = isset($_POST["message"]) ? trim((string) $_POST["message"]) : "";
if ($message === "") {
    http_response_code(400);
    echo "empty";
    exit;
}

$conn = getDBConnection();
$stmt = $conn->prepare("INSERT INTO custom_messages (user_id, message, time) VALUES (?, ?, ?)");
$time = date("Y-m-d H:i:s");
$user_id = (int) $_SESSION["user_id"];
$stmt->bind_param("iss", $user_id, $message, $time);
$stmt->execute();
$stmt->close();
$conn->close();

echo "success";
?>
