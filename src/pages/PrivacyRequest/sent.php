<?php
$ref = isset($_GET['ref']) ? trim((string) $_GET['ref']) : '';
if (!preg_match('/^PD-DSAR-[A-Z0-9]{6}$/', $ref)) {
    // Invalid or missing reference - redirect back to the form so it cannot
    // be used as a freeform XSS sink via the reference display.
    header('Location: ' . WEB_DOMAIN . '/privacy-request');
    exit;
}

$meta_title = "Request received {$ref} | PrivacyDuck";
$meta_description = "Your privacy request has been received. We respond within one calendar month.";
$meta_url = "https://privacyduck.com/privacy-request/sent";
$meta_image = "https://privacyduck.com/assets/pageSEO/landing.jpg";

include_once(BASEPATH . "/src/common/meta.php");
main_head_start();
?>
<meta name="robots" content="noindex, nofollow">
<?php
main_head_end();
main_header("black");
?>
<div class="px-[16px] sm:pl-[80px] sm:pr-[48px] pt-[149px] pb-[70px] lg:pt-[128px] lg:pb-0 bg-[#FAFAFA] leading-[1.6em]">
    <main class="px-6 py-12 max-w-2xl mx-auto">
        <div class="bg-white rounded-xl border border-slate-200 p-8 shadow-sm">
            <div class="flex justify-center mb-6">
                <div class="bg-[#24A556] text-white rounded-full w-14 h-14 flex items-center justify-center text-3xl font-bold" aria-hidden="true">
                    &#10003;
                </div>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-3">Request received</h1>
            <p class="text-center text-gray-700 mb-6">
                Thank you. We've recorded your privacy request and will respond within one calendar month, as required by UK GDPR Article 12.
            </p>
            <div class="bg-slate-50 border border-slate-200 rounded-lg px-6 py-4 mb-6 text-center">
                <div class="text-xs uppercase tracking-wide text-slate-500 mb-1">Your reference</div>
                <div class="text-xl font-mono font-bold text-gray-900"><?= htmlspecialchars($ref, ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="mt-2 text-xs text-slate-500">Quote this if you need to follow up.</div>
            </div>
            <h2 class="text-lg font-semibold text-gray-900 mb-3">What happens next</h2>
            <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700 ml-1">
                <li>Our team reviews your request within 5 business days.</li>
                <li>We may email you to verify your identity (UK GDPR Art. 12(6) protects against fraudulent requests).</li>
                <li>We action your request and email you the result, within one month of receipt. If your request is complex we may extend by two further months and will tell you why.</li>
                <li>If you are unhappy with how we handled your request, you may complain to the UK Information Commissioner's Office at <a href="https://ico.org.uk" target="_blank" rel="noopener" class="text-[#24A556] font-medium hover:underline">ico.org.uk</a>.</li>
            </ol>
            <div class="mt-8 text-center">
                <a href="/" class="text-[#24A556] font-medium hover:underline">&larr; Back to PrivacyDuck</a>
            </div>
        </div>
    </main>
</div>
<?php main_footer(); ?>
