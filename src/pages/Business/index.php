<?php
$meta_title = "Enterprise Data Protection Services for Businesses | PrivacyDuck";
$meta_description = "Get enterprise-grade data protection with PrivacyDuck. Remove exposed employee data, ensure GDPR compliance, and prevent insider threats automatically.";
$meta_url = "https://privacyduck.com/business";
$meta_keywords = "business data privacy provider, employee privacy protection solution, remove employee data from internet";
include_once(BASEPATH . "/src/common/meta.php");
main_head_start();
?>
<link href="/assets/css/landing.css" rel="stylesheet">
<link href="/assets/css/landingMobileAnimation.css" rel="stylesheet">
<?php
main_head_end();
business_header();
main_splash();
require(BASEPATH.'/src/pages/Business/landing/intro.php');
?>
<div class="flex flex-col text-[#010205] bg-[#FFFFFF]">
    <?php require(BASEPATH.'/src/pages/Business/landing/demo.php') ?>
    <?php require(BASEPATH.'/src/pages/Business/landing/mindmap.php') ?>
    <?php require(BASEPATH.'/src/pages/Business/landing/growth.php') ?>
    <?php require(BASEPATH.'/src/pages/Business/landing/testimonial.php') ?>
    <?php require(BASEPATH.'/src/pages/Business/landing/phishing.php') ?>
    <?php require(BASEPATH.'/src/pages/Business/landing/doxing.php') ?>
    <?php require(BASEPATH.'/src/pages/Business/landing/faq.php') ?>
    <?php require(BASEPATH.'/src/pages/Business/landing/instant.php') ?>
</div>
<?php
main_business_footer();
?>
<?php
    require(BASEPATH.'/src/pages/Business/contactteams/index.php');
    require(BASEPATH.'/src/pages/Business/contactteams/notify.php');
?>