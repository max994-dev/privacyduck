<?php
require BASEPATH . "/src/pages/Dashboard/sites_data.php";
$name = isset($_GET["name"]) ? $_GET["name"] : "";
$city = isset($_GET["city"]) ? $_GET["city"] : "";
$state = isset($_GET["state"]) ? $_GET["state"] : "";
$address = isset($_GET["address"]) ? $_GET["address"] : "";
$zip_code = isset($_GET["zip_code"]) ? $_GET["zip_code"] : "";
$user_email = isset($_GET["user_email"]) ? $_GET["user_email"] : "";
$age = isset($_GET["age"]) ? (int)$_GET["age"] : "";
$birth_day = isset($_GET["birth_day"]) ? (int)$_GET["birth_day"] : "";
$birth_month = isset($_GET["birth_month"]) ? (int)$_GET["birth_month"] : "";
$birth_year = isset($_GET["birth_year"]) ? (int)$_GET["birth_year"] : "";
$area_code = isset($_GET["area_code"]) ? $_GET["area_code"] : "";
$phone_number = isset($_GET["phone_number"]) ? $_GET["phone_number"] : "";
$street = isset($_GET["street"]) ? $_GET["street"] : "";
$county = isset($_GET["county"]) ? $_GET["county"] : "";
$conn = getDBConnection();

$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$userId = -1;
if ($result->num_rows > 0) {
    $userId = $result->fetch_assoc()["id"];
}
function plan($userId, $websites, $websitesUrl, $data)
{
    $conn = getDBConnection();
    $main_stmt = $conn->prepare("SELECT * FROM results WHERE user_id = ? AND kind=1");
    $main_stmt->bind_param("i", $userId);
    $main_stmt->execute();
    $main_result = $main_stmt->get_result();
    if ($main_result->num_rows == 0 || $userId == -1) {
        $values = [];
        $params = [];
        $types = "";

        // Build values and placeholders
        foreach ($websites as $url => $removal_url) {
            $values[] = "(?, ?, ?, ?, ?, ?, ?, ?)";
            $params[] = $url;
            $params[] = $userId;
            $params[] = 1;
            $params[] = 0;
            $params[] = 1;
            $params[] = $websitesUrl[$url];
            $params[] = $removal_url;
            $params[] = $data;
            $types .= "siiiisss";
        }
        $sql = "INSERT INTO results (target_domain, user_id, kind, step, planable, site_url, removal_url, data) VALUES " . implode(", ", $values);
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        echo "success";
    } else {
        $data = $main_result->fetch_all(MYSQLI_ASSOC);
        $count = count(array_filter($data, function ($item) {
            return $item["step"] < 2;
        }));
        $sql = "Select planedAt from users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        if ($count == 0 && $stmt->get_result()->num_rows > 0) {
            $sql = "UPDATE results SET data = ?, step = 0, planable = 1 WHERE user_id = ? AND kind=1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $data, $userId);
            $stmt->execute();
            echo "success";
        }
        else echo "pending";
    }
}
$data = json_encode([
    "email" => $user_email,
    "firstname" => $name,
    "lastname" => $name,
    "age" => $age,
    "city" => $city,
    "zip" => $zip_code,
    "state" => $state,
    "phone" => $phone_number,
    "address" => $address,
    "birth_day" => $birth_day,
    "birth_month" => $birth_month,
    "birth_year" => $birth_year,
    "area_code" => $area_code,
    "street" => $street,
    "county" => $county
]);
plan($userId, $websites, $websitesUrl, $data);