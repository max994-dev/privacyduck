<?php
$meta_title = "Remove Personal Information Online | PrivacyDuck - Experts in Data Privacy";
$meta_description = "Protect your privacy with PrivacyDuck. We remove your personal data from the internet and safeguard your online presence. Get started today!";
$meta_url = "https://privacyduck.com/";
$meta_image = "https://privacyduck.com/assets/pageSEO/landing.jpg";

include_once(BASEPATH . "/src/common/meta.php");
main_head_start();
?>
<link href="/assets/css/landing.css" rel="stylesheet">
<?php
main_head_end();
main_header("black");
// main_splash();
?>
<div class="bg-[#FAFAFA]">
    <?php require_once(BASEPATH . "/src/pages/Restoration/intro.php"); ?>
    <?php require_once(BASEPATH . "/src/pages/Restoration/service.php"); ?>
    <?php require_once(BASEPATH . "/src/pages/Restoration/scroll.php"); ?>
    <?php require_once(BASEPATH . "/src/pages/Restoration/claim.php"); ?>
    <?php require_once(BASEPATH . "/src/pages/Restoration/plan.php"); ?>
    <?php require_once(BASEPATH . "/src/pages/Restoration/vs.php"); ?>
</div>
<?php main_footer(); ?>