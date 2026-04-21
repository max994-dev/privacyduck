<?php
$rel = $_SESSION["pending_invite_stripe_return"] ?? "/dashboard/family?invite_paid=1";
$base = rtrim(defined("WEB_DOMAIN") ? WEB_DOMAIN : "", "/");
$target = $base . (strpos($rel, "/") === 0 ? $rel : "/" . $rel);
header("Content-Type: text/html; charset=utf-8");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment complete</title>
</head>
<body>
<script>
(function () {
    var url = <?php echo json_encode($target, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    if (window.opener && !window.opener.closed) {
        try {
            window.opener.location.href = url;
        } catch (e) {}
        window.close();
        return;
    }
    window.location.replace(url);
})();
</script>
<p style="font-family:system-ui,sans-serif;padding:1.5rem">Payment complete. <a href="<?php echo htmlspecialchars($target, ENT_QUOTES, "UTF-8"); ?>">Continue</a></p>
</body>
</html>
