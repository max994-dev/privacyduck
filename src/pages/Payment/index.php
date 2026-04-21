<?php

$conn = getDBConnection();
        $stmt = $conn->prepare("SELECT * FROM plans WHERE id = ?");
        $stmt->bind_param("i", $_GET["plan_id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $plan = $result->fetch_assoc();
$meta_title = "PrivacyDuck - Payment";
$meta_description = "Protect your privacy with PrivacyDuck. We remove your personal data from the internet and safeguard your online presence. Get started today!";
$meta_url = "https://privacyduck.com/";
$meta_image = "https://privacyduck.com/assets/pageSEO/landing.jpg";

include_once(BASEPATH . "/src/common/meta.php");
main_head_start();
main_head_end();
?>
<script src="https://js.stripe.com/v3/"></script>
<div class="flex bg-white justify-center">
    <div class="lg:w-1/2">
        <div class="h-[17px]"></div>
        <div class="flex justify-center mt-[32.5px] min-h-[calc(100vh-108.5px)]">
            <div>
                <div class="flex justify-center sm:justify-start">
                    <a href="/"><img class="w-[220px] h-[45px]" src="/assets/image/desktop/logo2.svg" alt="logo" /></a>
                </div>
                <div class="flex justify-center sm:justify-start mt-[32px] sm:mt-[50px]">
                    <h1 class="font-bold text-[24px] sm:text-[48px] leading-[150%] sm:leading-[38px] text-[#000000]">Start Removal!</h1>
                </div>
                <div class="mt-[24px] sm:mt-[36px] flex sm:flex-col justify-center sm:justify-start  ">
                    <div class="flex flex-col space-y-[26px]">
                        <div>
                            <h1 class="font-medium text-[#010205] leading-[20px] text-[14px]">Your Name</h1>
                            <input id="name"
                                class="mt-[6px] rounded-[8px] w-[358px] sm:w-[508px] h-[44px] px-[14px] py-[10px] bg-[#FBFBFB] border border-[#00000040] placeholder:text-[16px] placeholder:leading-[24px] placeholder:text-[#9D9D9D] text-[16px] leading-[24px] text-[#010205]"
                                placeholder="John Mayes" value="<?php if (isset($_SESSION["isAuthenticated"]) && $_SESSION["isAuthenticated"] == true) echo $_SESSION["fullName"]; ?>" <?php if (isset($_SESSION["isAuthenticated"]) && $_SESSION["isAuthenticated"] == true) echo "readonly"; ?> />
                            <div class="hidden text-[#AB4522] mt-[6px] text-[14px] leading-[20px]" id="invalid_name">The name is
                                incorrect</div>
                        </div>
                        <div>
                            <h1 class="font-medium text-[#010205] leading-[20px] text-[14px]">Your Email</h1>
                            <input id="email"
                                class="mt-[6px] rounded-[8px] w-[358px] sm:w-[508px] h-[44px] px-[14px] py-[10px] bg-[#FBFBFB] border border-[#00000040] placeholder:text-[16px] placeholder:leading-[24px] placeholder:text-[#9D9D9D] text-[16px] leading-[24px] text-[#010205]"
                                placeholder="example@gmail.com" value="<?php if (isset($_SESSION["isAuthenticated"]) && $_SESSION["isAuthenticated"] == true) echo $_SESSION["email"]; ?>" <?php if (isset($_SESSION["isAuthenticated"]) && $_SESSION["isAuthenticated"] == true) echo "readonly"; ?> />
                            <div class="text-[#9D9D9D] mt-[6px] text-[14px] leading-[20px]" id="invalid_email">Your Email will be used to access your account</div>
                        </div>
                    </div>
                </div>
                <div class="mt-[32px] sm:mt-[51px] flex justify-center sm:justify-start">
                    <div class="flex flex-col space-y-[26px]">
                        <div>
                            <h1 class="font-medium text-[#010205] leading-[20px] text-[14px]">Add Payment Method</h1>
                            <div id="card-number-element"
                                class="p-[12px] mt-[6px] rounded-[8px] w-[358px] sm:w-[508px] h-[44px] bg-[#FBFBFB] border border-[#00000040] text-[16px] leading-[24px] text-[#010205]">
                            </div>
                            <div class="hidden text-[#AB4522] mt-[6px] text-[14px] leading-[20px]" id="invalid_card_number">The card number is
                                incorrect</div>
                        </div>
                        <h1 class="mt-[6px] font-medium text-[#010205] leading-[20px] text-[14px]">Did you see pricing <a href="/pricing" class="text-[#24A556]">plan</a><span class="text-[#9D9D9D]">?</span></h1>
                        <div class="mt-[14px] flex space-x-[26px]">
                            <div>
                                <h1 class="font-medium text-[#010205] leading-[20px] text-[14px]">Expiry Date</h1>
                                <div id="card-expiry-element" class="p-[12px] mt-[6px] rounded-[8px] w-[114px] h-[44px] bg-[#FBFBFB] border border-[#00000040] text-[16px] leading-[24px] text-[#010205]">
                                </div>
                            </div>
                            <div>
                                <h1 class="font-medium text-[#010205] leading-[20px] text-[14px]">CCV</h1>
                                <div id="card-cvc-element" class="p-[12px] mt-[6px] rounded-[8px] w-[114px] h-[44px] bg-[#FBFBFB] border border-[#00000040] text-[16px] leading-[24px] text-[#010205]">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-center sm:justify-start mt-[32px] px-[16px] sm:px-0" id="agree_container">
                    <div class="flex space-x-2 items-center justify-center">
                        <input type="checkbox"
                            class="w-[24px] h-[24px]  hover:cursor-pointer rounded-[4px] border-[#CDCDCD] border-[1px] focus:ring-0 text-[#24A556]"
                            id="agree_term">&nbsp;&nbsp;
                        <div class="flex flex-wrap">
                            <span class="text-[#010205]">I have read and I agree to the</span>
                            <a
                                class="font-bold text-[16px] leading-[24px] hover:cursor-pointer bg-gradient-to-r from-[#77B248] to-[#24A556] text-transparent bg-clip-text border-b border-[#77B248] hover:border-[#24A556]">
                                Terms of Service
                            </a>
                            <span class="text-[#010205]">and</span>
                            <a
                                class="font-bold text-[16px] leading-[24px] hover:cursor-pointer bg-gradient-to-r from-[#77B248] to-[#24A556] text-transparent bg-clip-text border-b border-[#77B248] hover:border-[#24A556]">
                                Privacy Policy.
                            </a>
                        </div>
                    </div>
                </div>
                <div class="flex justify-center mt-[22px]">
                    <h1 class="font-semibold text-[28px] leading-[150%]">
                        Total Price:  $<?php echo intval($plan["value"]) / 100; ?>/<?php echo $plan["year"] == "two" ? 2 : 1; ?> year
                    </h1>
                </div>
                <div class="mt-[32px] flex justify-center sm:justify-start">
                    <button id="protection" onclick="pay()" class="bg-[#24A556] rounded-full text-[#FFFFFF] font-bold text-[18px] leading-[140%] items-center h-[55px] w-[358px] sm:w-[506px] flex justify-center">
                        Start Protection&nbsp;&nbsp;
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 12H19" stroke="white" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M12 5L19 12L12 19" stroke="white" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>

            </div>
        </div>
        <div class="h-[59px] flex justify-center items-center">
            <h1 class="text-[14px] text-[#010205] leading-[20px]">©PrivacyDuck 2025</h1>
        </div>
    </div>
    <div
        class="hidden relative w-1/2 bg-[url('/assets/image/desktop/login.png')] bg-cover lg:flex justify-center items-center rounded-tl-[30px] rounded-bl-[30px]">
        <img src="/assets/image/desktop/privacyduckfight.png" class="w-[645.58px] h-[360px]" alt="img" />
        <img class="absolute w-[49.83px] h-[55px] bottom-[35px] right-[36px]" src="/assets/image/desktop/login_duck.svg"
            alt="mark" />
    </div>
    <!-- <div id="card-element" class="w-[70%]">

    </div> -->
</div>
<?php
no_footer();
?>
<script>
    const loadingHtml = "<img src='/assets/image/desktop/loading1.webp' class='w-6 h-6 flex mr-2'> <span class=''>Sending...</span>";
    const stripe = Stripe(<?php echo json_encode(pd_stripe_publishable_key()); ?>);
    const elements = stripe.elements();

    const style = {
        base: {
            fontSize: '16px',
            color: '#32325d',
            '::placeholder': {
                color: '#aab7c4'
            },
        }
    };
    const cardNumber = elements.create('cardNumber', {
        style
    });
    cardNumber.mount('#card-number-element');

    const cardExpiry = elements.create('cardExpiry', {
        style
    });
    cardExpiry.mount('#card-expiry-element');

    const cardCvc = elements.create('cardCvc', {
        style
    });
    cardCvc.mount('#card-cvc-element');
    $("#agree_term").on("click", function() {
        $("#agree_container").removeClass("text-red-500");
        $("#agree_term").removeClass("ring-2 ring-red-500");
    });

    async function pay() {
        const id = <?php echo $_GET["plan_id"]; ?>;
        if (!$("#name").val().trim()) {
            $("#invalid_name").removeClass("hidden");
            return;
        }
        if (!$("#email").val().trim()) {
            $("#invalid_email").removeClass("hidden");
            return;
        }
        const agreed = document.getElementById("agree_term").checked;
        if (!agreed) {
            $("#agree_container").addClass("text-red-500");
            $("#agree_term").addClass("ring-2 ring-red-500");
            return;
        }
        
        try {
            $("#protection").html(loadingHtml);
            $("#protection").prop("disabled", true);
            const {
                paymentMethod,
                error: pmError
            } = await stripe.createPaymentMethod({
                type: 'card',
                card: cardNumber,
                billing_details: {
                    name: $("#name").val().trim(),
                    email: $("#email").val().trim()
                }
            });

            if (pmError) throw new Error(pmError.message);

            // Step 2: Send to backend
            $.post("/plans", {
                payment_method_id: paymentMethod.id,
                plan_id: id,
                name: $("#name").val().trim(),
                email: $("#email").val().trim()
            }, async function(response) {
                const data = JSON.parse(response);

                if (data.error) {
                    throw new Error(data.error);
                }

                const {
                    paymentIntent,
                    error: confirmError
                } = await stripe.confirmCardPayment(data.clientSecret);

                if (confirmError) {
                    throw new Error(confirmError.message);
                }

                if (paymentIntent.status === "succeeded") {
                    $.post("/success", {}, function(res) {
                        if (res === "success") {
                            window.location.href = "<?= WEB_DOMAIN ?>/paymentverify";
                        } else {
                            throw new Error(res);
                        }
                    });
                }
            });

        } catch (err) {
            const but = `Start Protection&nbsp;&nbsp;
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 12H19" stroke="white" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M12 5L19 12L12 19" stroke="white" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>`;
            $("#protection").html(but);
            $("#protection").prop("disabled", false);
            alert("Payment failed: " + err.message);
            console.error(err);
        }
    }
</script>