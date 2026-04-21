<?php


$conn = getDBConnection();

$stmt = $conn->prepare("SELECT * FROM plans where show_dashboard = 1");
$stmt->execute();
$result = $stmt->get_result();
?>
const dashboard_plans_data = JSON.parse(`<?php
    if ($result->num_rows == 0) {
        echo json_encode([]);
    } else {
        try {
            $tmpData = $result->fetch_all(MYSQLI_ASSOC);
            foreach ($tmpData as $key => $value) {
                // if (isset($_SESSION['email']) && ($_SESSION['email'] == "hello@privacyduck.com" || $_SESSION['email'] == "joewartson757@gmail.com")) $value["stripe_payment_link"] = $value["stripe_payment_link_etc"];
                // else $value["stripe_payment_link_etc"] = "";
                $value["stripe_payment_link"] = $value["stripe_payment_link_etc"];
                if (!isset($dataa[$value["year"]])) {
                    $dataa[$value["year"]] = [];
                }
                $dataa[$value["year"]][$value["person"]] = $value;
            }
            echo json_encode($dataa);
        } catch (Exception $e) {
            echo json_encode([
                "error" => $e->getMessage()
            ]);
        }
    }
?>`);