<?php
$meta_title = "Family Privacy Protection Services in USA | Remove Kids' Info Online";
$meta_description = "Protect your family’s privacy online with PrivacyDuck. We specialize in removing kids’ personal information from the internet. Trusted family data privacy services in the USA.";
$meta_url = "https://privacyduck.com/family";
$meta_keywords = "family privacy protection service, remove kids personal info online, family personal data removal from internet, family privacy services in USA";
include_once(BASEPATH . "/src/common/meta.php");
main_head_start();
?>
<link href="/assets/css/landing.css" rel="stylesheet">
<?php
main_head_end();
main_header();
// main_splash();
?>
<div class="bg-[#FAFAFA]">
    <?php require_once(BASEPATH . "/src/pages/Family/intro.php"); ?>
    <?php require_once(BASEPATH . "/src/pages/Family/manage.php"); ?>
    <?php require_once(BASEPATH . "/src/pages/Family/saveup.php"); ?>
    <?php require_once(BASEPATH . "/src/pages/Landing/faq.php"); ?>
    <?php require_once(BASEPATH . "/src/pages/Landing/testimonial.php"); ?>
</div>
<?php main_footer(); ?>