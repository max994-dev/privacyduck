<?php
if (!isset($_SESSION['verify_code']) || !isset($_SESSION['email'])) {
    if (isset($_SESSION["invite_requirePayment"])) unset($_SESSION["invite_requirePayment"]);
    if (isset($_SESSION["invite_pay_verified"])) unset($_SESSION["invite_pay_verified"]);
    if (isset($_SESSION["invite_pay_verified_at"])) unset($_SESSION["invite_pay_verified_at"]);
    if (isset($_SESSION["addon_invitee_email"])) unset($_SESSION["addon_invitee_email"]);
    if (isset($_SESSION["invite_count"])) unset($_SESSION["invite_count"]);
    if (isset($_SESSION["invite_price"])) unset($_SESSION["invite_price"]);
    exit();
}

$verifyCode = $_SESSION['verify_code'];
$verifyEmail = $_SESSION['email'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verify_code'])) {
    $enteredCode = implode("", $_POST['verify_code']);

    if ($verifyCode == $enteredCode) {
        $_SESSION["invite_pay_verified"] = true;
        $_SESSION["invite_pay_verified_at"] = time();
        unset($_SESSION['verify_code']);
        ?>
        <script>
            if (window.opener) {
                window.opener.postMessage({
                    type: 'invite_payment_verified',
                }, window.location.origin);

                window.close(); // Close the popup after sending result
            }
        </script>
        <?php
        exit();
    } else {
        $error = "Invalid verification code. Please try again.";
    }
}
$meta_title = "PrivacyDuck - Verify";
$meta_description = "Protect your privacy with PrivacyDuck. We remove your personal data from the internet and safeguard your online presence. Get started today!";
$meta_url = "https://privacyduck.com/";
$meta_image = "https://privacyduck.com/assets/pageSEO/landing.jpg";

include_once(BASEPATH . "/src/common/meta.php");
main_head_start();
main_head_end();
?>

<style>
    input.verification-box {
        width: 3rem;
        height: 3rem;
        text-align: center;
        font-size: 1.5rem;
        border: 1px solid #D1D5DB;
        border-radius: 0.375rem;
    }

    input.verification-box:focus {
        outline: none;
        border-color: #2563EB;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const firstInput = document.querySelector('.verification-box');
        if (firstInput) {
            firstInput.focus();
        }
        const inputs = document.querySelectorAll('.verification-box');
        const form = document.getElementById('verification-form');

        inputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                if (input.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }

                // Automatically submit the form when all inputs are filled
                if (Array.from(inputs).every(input => input.value.length === 1)) {
                    form.submit();
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !input.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });

        form.addEventListener('paste', (e) => {
            const pasteData = e.clipboardData.getData('text');
            if (pasteData.length === inputs.length) {
                pasteData.split('').forEach((char, index) => {
                    if (inputs[index]) {
                        inputs[index].value = char;
                    }
                });
                // Automatically submit the form after pasting
                if (Array.from(inputs).every(input => input.value.length === 1)) {
                    form.submit();
                    const but = `<img src='/assets/image/desktop/loading1.webp' class='w-6 h-6 flex mr-2'> <span class=''>Sending...</span>`;
                    $("#send").html(but);
                    $("#send").prop("disabled", true);
                }
            }
            e.preventDefault();
        });
    });
</script>
<div class="flex bg-white justify-center">
    <div class="lg:w-1/2">
        <div class="h-[96px]"></div>
        <div class="flex justify-center items-center min-h-[calc(100vh-192px)] px-[15px]">
            <div>
                <div class="flex justify-center">
                    <a href="/"><img class="w-[220px] h-[45px]" src="/assets/image/desktop/logo2.svg" alt="logo" /></a>
                </div>
                <div class="text-center mt-[32px] px-[9px] sm:px-[70px] max-w-[584px]">
                    <h1 class="text-[16px] leading-[24px] text-[#010205]">We sent an email to
                        <span class="font-bold"><?= $_SESSION['email']; ?>.</span> Please enter the verification code in the email to confirm your address and
                        proceed. If you don’t see the email, check your spam folder.
                    </h1>
                </div>
                <form id="verification-form" class="space-y-6 mt-[32px]" action="/invite_paymentverify" method="POST">
                    <div class="flex space-x-2 justify-center">
                        <input type="text" name="verify_code[]" autocomplete="off" maxlength="1"
                            class="verification-box text-[#010205]" required>
                        <input type="text" name="verify_code[]" autocomplete="off" maxlength="1"
                            class="verification-box text-[#010205]" required>
                        <input type="text" name="verify_code[]" autocomplete="off" maxlength="1"
                            class="verification-box text-[#010205]" required>
                        <input type="text" name="verify_code[]" autocomplete="off" maxlength="1"
                            class="verification-box text-[#010205]" required>
                        <input type="text" name="verify_code[]" autocomplete="off" maxlength="1"
                            class="verification-box text-[#010205]" required>
                        <input type="text" name="verify_code[]" autocomplete="off" maxlength="1"
                            class="verification-box text-[#010205]" required>
                    </div>
                    <?php if (isset($error)): ?>
                        <p class="text-center text-sm mt-4 text-red-600"><?= htmlspecialchars($error); ?></p>
                    <?php endif; ?>
                    <div class="mt-[24px] flex justify-center">
                        <button id="send" type="submit"
                            class="flex items-center justify-center w-[360px] h-[44px] rounded-[8px]  bg-[#5AB87F] but-shadow text-center text-[#FAFAFA] font-semibold leading-[24px] text-[16px] hover:bg-gradient-to-r hover:from-[#77B248] hover:to-[#24A556] active:bg-none active:bg-[#24A556]">Verify</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="h-[96px] flex justify-center">
            <h1 class="mt-[60px] text-[14px] text-[#010205] leading-[20px]">©PrivacyDuck 2025</h1>
        </div>
    </div>
    <div
        class="hidden relative w-1/2 bg-[url('/assets/image/desktop/login.png')] bg-cover lg:flex justify-center items-center rounded-tl-[30px] rounded-bl-[30px]">
        <img src="/assets/image/desktop/privacyduckfight.png" class="w-[645.58px] h-[360px]" alt="img" />
        <img class="absolute w-[49.83px] h-[55px] bottom-[35px] right-[36px]" src="/assets/image/desktop/login_duck.svg"
            alt="mark" />
    </div>
</div>
<?php
no_footer();
?>