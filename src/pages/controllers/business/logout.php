<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION["work_isAuthenticated"]=false;
header("Location: /business"); // Redirect to homepage after logout
exit;
?>
