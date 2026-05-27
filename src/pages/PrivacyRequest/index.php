<?php
$meta_title = "Privacy Request | PrivacyDuck";
$meta_description = "Exercise your data protection rights under UK GDPR, EU GDPR, or CCPA. Request access, deletion, correction, or restriction of your personal data.";
$meta_url = "https://privacyduck.com/privacy-request";
$meta_image = "https://privacyduck.com/assets/pageSEO/landing.jpg";

include_once(BASEPATH . "/src/common/meta.php");
main_head_start();
main_head_end();
main_header("black");

$err = isset($_GET['err']) ? (string) $_GET['err'] : '';
$prefillEmail = isset($_GET['email']) ? trim((string) $_GET['email']) : '';
if ($prefillEmail !== '' && !filter_var($prefillEmail, FILTER_VALIDATE_EMAIL)) {
    $prefillEmail = '';
}
?>
<div class="px-[16px] sm:pl-[80px] sm:pr-[48px] pt-[149px] pb-[70px] lg:pt-[128px] lg:pb-0 bg-[#FAFAFA] leading-[1.6em]">
    <main class="px-6 py-12 max-w-3xl mx-auto">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-3">Privacy Request</h1>
            <p class="text-gray-700">
                Use this form to exercise your rights under UK GDPR, the Data Protection Act 2018, EU GDPR, or CCPA.
                We respond within one calendar month. For background see our
                <a href="/policy" class="text-[#24A556] font-medium hover:underline">Privacy Policy</a>.
            </p>
        </div>

        <?php if ($err !== ''): ?>
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="/privacy-request/submit"
              class="space-y-6 rounded-xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">

            <fieldset class="space-y-3">
                <legend class="text-sm font-semibold text-slate-900 mb-2">What would you like to do? <span class="text-red-600">*</span></legend>
                <?php
                $types = [
                    'access'           => ['Access my data',           'Get a copy of all personal data PrivacyDuck holds about me.'],
                    'rectification'    => ['Correct my data',          'Fix something inaccurate or incomplete.'],
                    'erasure'          => ['Delete my data',           'The "right to be forgotten". Subject to legal retention obligations.'],
                    'restrict'         => ['Restrict processing',      'Pause processing of my data while a dispute is resolved.'],
                    'portability'      => ['Export my data',           'Receive my data in a machine-readable format.'],
                    'object'           => ['Object to processing',     'Stop processing my data for a specific purpose (e.g. marketing).'],
                    'no_automated'     => ['No automated decisions',   'Confirm no significant decisions about me are made without human involvement.'],
                    'withdraw_consent' => ['Withdraw consent',         'Withdraw consent for processing that needs it (e.g. marketing, analytics cookies).'],
                ];
                foreach ($types as $val => [$label, $desc]) :
                ?>
                <label class="flex items-start gap-3 rounded-lg border border-slate-200 px-4 py-3 hover:bg-slate-50 cursor-pointer">
                    <input type="radio" name="request_type" value="<?= htmlspecialchars($val) ?>" required
                           class="mt-1 h-4 w-4 text-[#24A556] border-slate-300 focus:ring-[#24A556]" />
                    <span>
                        <span class="block text-sm font-semibold text-slate-900"><?= htmlspecialchars($label) ?></span>
                        <span class="block text-xs text-slate-600 mt-0.5"><?= htmlspecialchars($desc) ?></span>
                    </span>
                </label>
                <?php endforeach; ?>
            </fieldset>

            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-1">Email address <span class="text-red-600">*</span></label>
                <p class="text-xs text-slate-500 mb-2">We use this to verify your identity and send our response.</p>
                <input name="email" type="email" required autocomplete="email" maxlength="255"
                       value="<?= htmlspecialchars($prefillEmail, ENT_QUOTES, 'UTF-8'); ?>"
                       class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600" />
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-1">Full name <span class="text-slate-500 font-normal text-xs">(optional)</span></label>
                    <input name="name" type="text" maxlength="200" autocomplete="name"
                           class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600" />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-1">Country <span class="text-slate-500 font-normal text-xs">(optional)</span></label>
                    <select name="country" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600">
                        <option value="">Select...</option>
                        <option value="UK">United Kingdom</option>
                        <option value="EU">European Union</option>
                        <option value="US">United States</option>
                        <option value="CA">Canada</option>
                        <option value="OTHER">Other</option>
                    </select>
                </div>
            </div>

            <fieldset class="space-y-2">
                <legend class="text-sm font-semibold text-slate-900 mb-1">Capacity <span class="text-red-600">*</span></legend>
                <label class="flex items-start gap-3">
                    <input type="radio" name="capacity" value="self" required checked
                           class="mt-1 h-4 w-4 text-[#24A556] border-slate-300 focus:ring-[#24A556]" />
                    <span class="text-sm text-slate-700">I am the data subject (the personal data is about me).</span>
                </label>
                <label class="flex items-start gap-3">
                    <input type="radio" name="capacity" value="representative" required
                           class="mt-1 h-4 w-4 text-[#24A556] border-slate-300 focus:ring-[#24A556]" />
                    <span class="text-sm text-slate-700">I am authorised to act on behalf of the data subject (we may ask for proof).</span>
                </label>
            </fieldset>

            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-1">Details <span class="text-slate-500 font-normal text-xs">(optional)</span></label>
                <p class="text-xs text-slate-500 mb-2">Helps us respond faster - e.g. which data you want corrected, which processing you object to.</p>
                <textarea name="details" rows="5" maxlength="4000"
                          class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600"></textarea>
            </div>

            <!-- Honeypot: hidden from humans, bots fill it. Server discards on truthy value. -->
            <div aria-hidden="true" style="position:absolute;left:-10000px;top:auto;width:1px;height:1px;overflow:hidden;">
                <label>Leave this field blank
                    <input type="text" name="website" tabindex="-1" autocomplete="off" />
                </label>
            </div>

            <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                <label class="flex items-start gap-3">
                    <input type="checkbox" name="declaration" value="1" required
                           class="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-600" />
                    <span class="text-sm text-slate-700 leading-relaxed">
                        I confirm the information above is accurate. I understand PrivacyDuck may need to verify my identity before processing this request, and will respond within one calendar month.
                    </span>
                </label>
            </div>

            <button type="submit"
                    class="w-full rounded-lg border border-emerald-700 bg-emerald-600 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                Submit request
            </button>
        </form>
    </main>
</div>
<?php main_footer(); ?>
