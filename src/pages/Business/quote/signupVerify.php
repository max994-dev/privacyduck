<?php
if (!isset($_SESSION['verify_code']) || !isset($_SESSION['work_email'])) {
    header("Location: " . WEB_DOMAIN . "/business/quote/login");
    exit;
}
$verifyCode = $_SESSION['verify_code'];
$verifyEmail = $_SESSION['work_email'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verify_code'])) {
    $enteredCode = implode("", $_POST['verify_code']);

    if ($verifyCode == $enteredCode) {

        //Insert the data(verified email, firstname, lastname) to the MYSQL database (Table users)
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT * FROM workUsers WHERE email = ?");
        $stmt->bind_param("s", $verifyEmail);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            $fullName = $_SESSION["work_fullName"];

            $nameParts = explode(" ", $fullName);
            $firstName = $nameParts[0];
            $lastName = isset($nameParts[1]) ? $nameParts[1] : "";
            $stmt = $conn->prepare("INSERT INTO workUsers (email, firstname, lastname, phone) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $verifyEmail, $firstName, $lastName, $_SESSION["work_phone"]);
            $stmt->execute();
            $_SESSION["work_user_id"] = $conn->insert_id;
        } else {
            $data = $result->fetch_assoc();
            $_SESSION["work_fullName"] = $data["firstname"] . " " . $data["lastname"];
            $_SESSION["work_role"] = $data["role"];
            $_SESSION["work_mindmap_limit"] = $data["member_limit"];
            $_SESSION["work_user_id"] = $data["id"];
        }
        $conn->close();
        $_SESSION["work_isAuthenticated"] = true;
        unset($_SESSION['verify_code']);
        setcookie("work_info", $verifyEmail, time() + 60 * 60 * 24 * 10, "/");
        header("Location: " . WEB_DOMAIN . "/business/dashboard");
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
        border: 1px solid #00000040;
        border-radius: 0.375rem;
    }

    input.verification-box:focus {
        outline: none;
        border-color: #24A556;
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
                        <span class="font-bold"><?= $_SESSION['work_email']; ?>.</span> Please enter the verification code in the email to confirm your address and
                        proceed. If you don’t see the email, check your spam folder.
                    </h1>
                </div>
                <form id="verification-form" class="space-y-6 mt-[32px]" action="" method="POST">
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
                            class="w-[360px] h-[44px] rounded-[8px]  bg-[#5AB87F] but-shadow text-center text-[#FAFAFA] font-semibold leading-[24px] text-[16px] hover:bg-gradient-to-r hover:from-[#77B248] hover:to-[#24A556] active:bg-none active:bg-[#24A556]">Verify</button>
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