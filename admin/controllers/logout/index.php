<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION["admin"]["isAdminAuthenticated"] = false;
header("Location: " . WEB_DOMAIN . "/super/admin/login");
exit;
?>
