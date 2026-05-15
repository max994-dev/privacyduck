<?php
$meta_title = "Privacy Protection Plans and Service Pricing | PrivacyDuck";
$meta_description = "Transparent pricing for privacy removal services with plans designed to protect personal data, reduce online exposure, and support long-term digital security needs.";
$meta_url = "https://privacyduck.com/pricing";
$json_ld = [
    "@context" => "https://schema.org",
    "@type" => "Service",
    "name" => "Personal Data Removal Service",
    "provider" => [
        "@type" => "Organization",
        "@id" => "https://privacyduck.com/#organization",
        "name" => "PrivacyDuck",
        "url" => "https://privacyduck.com"
    ],
    "description" => "Remove your personal data from 300+ data broker sites, people search engines, and Google. Includes dark web monitoring and privacy concierge support.",
    "url" => "https://privacyduck.com/pricing",
    "areaServed" => "US",
    "offers" => [
        "@type" => "Offer",
        "name" => "Standard Protection — 1 Year",
        "price" => "299.99",
        "priceCurrency" => "USD",
        "priceSpecification" => [
            "@type" => "UnitPriceSpecification",
            "price" => "299.99",
            "priceCurrency" => "USD",
            "billingDuration" => "P1Y"
        ],
        "url" => "https://privacyduck.com/pricing"
    ]
];
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once(BASEPATH . "/src/common/meta.php");
main_head_start();
?>
<link href="/assets/css/pricing.css" rel="stylesheet">
<!-- <script src="/src/pages/Pricing/script.js"></script> -->
<?php
main_head_end();
main_header();
// main_splash();
include_once(BASEPATH . "/src/pages/Pricing/price.php");
include_once(BASEPATH . "/src/pages/Pricing/slide.php");
include_once(BASEPATH . "/src/pages/Pricing/faq.php");
include_once(BASEPATH . "/src/pages/Pricing/digital.php");
main_footer();
?>
