<?php
isLogin();
$meta_title = "Privacyduck Admin";
include_once(ADMINBASEPATH . "/admin/utils/meta.php");
main_head_start();
?>
<link rel="stylesheet" href="/admin/assets/css/dashboard.css">
<?php
main_head_end();
?>
<div class="relative z-10 h-screen flex">
    <?php require(ADMINBASEPATH . "/admin/views/Dashboard/sidebar.php"); ?>
    <main id="content" class="flex-1 flex flex-col h-screen md:ml-0 p-[16px] overflow-y-auto">
        <?php require(ADMINBASEPATH . "/admin/views/Dashboard/user/index.php"); ?>
    </main>
</div>
<?php
main_footer();
?>