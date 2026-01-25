<?php
header("Content-Type: application/json");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawPassword = $_POST['password']; // from HTML form
    $username = $_POST['username'];
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT * FROM adminusers WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo json_encode([
            "status"=>"error",
            "message" => "Username doesn't exist!"
        ]);
    } else {
        $data = $result->fetch_assoc();
        try {
                if (password_verify($rawPassword, $data["password"])) {
                    if(!isset($_SESSION["admin"])){
                        $_SESSION["admin"] = array();
                    }
                    $_SESSION["admin"]["username"] = $data["username"];
                    $_SESSION["admin"]["id"] = $data["id"];
                    $_SESSION["admin"]["isAdminAuthenticated"] = true;
                    echo json_encode([
                        "status" => "success",
                        "message" => "Admin login successful!"
                    ]);
                } else {
                    echo json_encode([
                        "status" => "error",
                        "message" => "Invalid password! Please enter password again."
                    ]);
                }
        } catch (Exception $e) {
            echo json_encode([
                "error" => $e->getMessage()
            ]);
        }
    }
}
