<?php

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["name"])) {
    $_SESSION["freeScanName"] = $_POST["name"];
    echo "success";
} else {
    echo "error";
}
?>