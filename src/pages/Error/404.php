<?php
http_response_code(404);
$meta_title = "Page not found | PrivacyDuck";
$meta_description = "The page you're looking for doesn't exist.";
$meta_url = "https://privacyduck.com/";
$meta_image = "https://privacyduck.com/assets/pageSEO/landing.jpg";

include_once(BASEPATH . "/src/common/meta.php");
main_head_start();
?>
<meta name="robots" content="noindex, nofollow">
<?php
main_head_end();
main_header("black");
?>
<div class="px-[16px] sm:pl-[80px] sm:pr-[48px] pt-[149px] pb-[120px] lg:pt-[128px] bg-[#FAFAFA] min-h-[70vh]">
    <main class="max-w-2xl mx-auto px-6 py-12 text-center">
        <div class="text-[120px] sm:text-[160px] font-bold text-[#24A556] leading-none">404</div>
        <h1 class="mt-4 text-3xl sm:text-4xl font-bold text-slate-900">Page not found</h1>
        <p class="mt-3 text-slate-600 text-lg max-w-md mx-auto">
            The page you’re looking for doesn’t exist or has been moved. Let us point you somewhere useful.
        </p>
        <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
            <a href="/" class="inline-flex items-center justify-center rounded-full bg-[#24A556] hover:bg-[#1E8C49] text-white font-semibold text-[15px] px-6 py-3">
                Back to home
            </a>
            <a href="/new_signup" class="inline-flex items-center justify-center rounded-full border border-slate-300 hover:bg-slate-50 text-slate-900 font-semibold text-[15px] px-6 py-3">
                Start a free scan
            </a>
        </div>
        <div class="mt-12 text-sm text-slate-500">
            Looking for something specific?
            <a href="/sites-we-cover" class="text-[#24A556] font-medium hover:underline">See the sites we cover</a> ·
            <a href="/pricing" class="text-[#24A556] font-medium hover:underline">Pricing</a> ·
            <a href="/policy" class="text-[#24A556] font-medium hover:underline">Privacy policy</a>.
        </div>
    </main>
</div>
<?php main_footer(); ?>
