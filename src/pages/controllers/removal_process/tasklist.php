<?php
header('Content-Type: application/json');
$email = isset($_GET["user_email"]) ? $_GET["user_email"] : "";
$conn = getDBConnection();
$main_stmt = $conn->prepare("SELECT * FROM results WHERE " . ($email ? " JSON_UNQUOTE(JSON_EXTRACT(data, '$.email')) = ? AND" : "") . " kind=1");
if ($email) $main_stmt->bind_param("s", $email);
$main_stmt->execute();
$main_result = $main_stmt->get_result();
if ($main_result->num_rows == 0) {
} else {
    $list = $main_result->fetch_all(MYSQLI_ASSOC);
    echo json_encode([
        "task_list" => [
            "completed" => [
                "count" => count(array_filter($list, function ($item) {
                    return $item["step"] > 2;
                })),
                "tasks" => array_map(function ($item) {
                    return $item["target_domain"];
                }, array_filter($list, function ($item) {
                    return $item["step"] > 2;
                }))
            ],
            "pending" => [
                "count" => count(array_filter($list, function ($item) {
                    return $item["step"] == 0;
                })),
                "tasks" => array_map(function ($item) {
                    return $item["target_domain"];
                }, array_filter($list, function ($item) {
                    return $item["step"] == 0;
                }))
            ],
            "running" => [
                "count" => count(array_filter($list, function ($item) {
                    return $item["step"] == 1;
                })),
                "tasks" => array_map(function ($item) {
                    return $item["target_domain"];
                }, array_filter($list, function ($item) {
                    return $item["step"] == 1;
                }))
            ],
            "total_tasks" => count($list)
        ]
    ]);
}
