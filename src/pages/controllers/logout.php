<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION["isAuthenticated"]=false;
$_SESSION["plan_id"]=null;

header("Location: /"); // Redirect to homepage after logout
exit;
?>
