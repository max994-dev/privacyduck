<?php
$meta_title = "Login | Privacyduck Admin";
include_once(ADMINBASEPATH . "/admin/utils/meta.php");
main_head_start();
main_head_end();
require(ADMINBASEPATH . "/admin/views/Login/login.php");
main_footer();
?>