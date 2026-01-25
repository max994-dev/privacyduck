<?php
$meta_title = "Special Information Removal Services | PrivacyDuck";
$meta_description = "PrivacyDuck offers specialized information removal services for unique cases. Our team handles complex data deletion requests with precision and confidentiality.";
$meta_url = "https://privacyduck.com/specialinfo";
$meta_keywords = "special information removal, specialized data deletion, unique case handling, privacy protection services, information removal services";
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
    <?php require_once(BASEPATH . "/src/pages/Specialinfo/intro.php"); ?>
</div>
<?php main_footer(); ?>