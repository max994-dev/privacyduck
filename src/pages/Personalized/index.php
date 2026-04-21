<?php
$meta_title = "Remove Personal Info from Google | Expert Help by PrivacyDuck ";
$meta_description = "Remove personal data from Google with PrivacyDuck. Get expert, personalized help to protect your privacy and control what appears in search results.";
$meta_url = "https://privacyduck.com/personalized-service";
$meta_keywords = "personalized data removal form google, personalized data removal form google in usa, please provide your rationale below, privacyduck personalized service in usa, personalized privacy removal in usa, personalized data remove service";
include_once(BASEPATH . "/src/common/meta.php");
main_head_start();
main_head_end();
main_header();
// main_splash();
?>
<style>
    .dot {
        transition: background-color 0.3s ease;
    }

    .dot.active {
        background-color: #22c55e;
        /* Green */
    }
</style>
<div class="bg-[#FAFAFA]">
    <?php require_once(BASEPATH . "/src/pages/Personalized/intro.php"); ?>
    <?php require_once(BASEPATH . "/src/pages/Personalized/items.php"); ?>
    <?php require_once(BASEPATH . "/src/pages/Personalized/concierge.php"); ?>
    <?php require_once(BASEPATH . "/src/pages/Personalized/milestones.php"); ?>
    <?php require_once(BASEPATH . "/src/pages/Personalized/team.php"); ?>
    <?php require_once(BASEPATH . "/src/pages/Landing/testimonial.php"); ?>
<?php main_footer(); ?>