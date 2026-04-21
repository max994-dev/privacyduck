<?php
// Default meta values
$meta_title = $meta_title ?? "Remove Personal Information from the Internet | PrivacyDuck";
$meta_description = $meta_description ?? "Remove personal information from major data brokers with PrivacyDuck. Protect your privacy and limit exposure across multiple online platforms safely.";
$meta_url = $meta_url ?? "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$meta_image = $meta_image ?? "https://privacyduck.com/assets/page/og-default.jpg";
$meta_keywords = $meta_keywords ?? "best online privacy protection";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name='keywords' content="<?= htmlspecialchars($meta_keywords) ?>">

    <title><?= htmlspecialchars($meta_title) ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description) ?>">
    <link rel="canonical" href="<?= htmlspecialchars($meta_url) ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="PrivacyDuck">
    <meta property="og:title" content="<?= htmlspecialchars($meta_title) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($meta_description) ?>">
    <meta property="og:url" content="<?= htmlspecialchars($meta_url) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($meta_image) ?>">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@privacyduck"> <!-- (Optional: Add if you have a Twitter) -->
    <meta name="twitter:title" content="<?= htmlspecialchars($meta_title) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($meta_description) ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($meta_image) ?>">

    <!-- Robots -->
    <meta name="robots" content="index, follow">

    <!-- Favicons -->
    <link rel="icon" href="/assets/favicon.png" type="image/x-icon">
</head>
