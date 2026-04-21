<div id="payment_modal" class="hidden fixed inset-0 z-[3000] bg-[#00000040] px-[16px] border border-[#F6F6F63A] backdrop-blur-md flex items-center justify-center animate-[opacity_0.5s_ease-out]">
    <div class="relative bg-white rounded-[30px] shadow-[0px_4px_4px_#0206091A] w-full max-w-[1174px]">
        <!-- Close Button -->
        <button onclick="add_payment_close_modal()" id="closeModal"
            class="hidden xl:block absolute top-[16px] right-[30px] font-bold text-gray-500 hover:text-red-500">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M19 5L5 19M5 5L19 19" stroke="#FFFFFF" stroke-width="1.5"
                    stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>
        <button onclick="add_payment_close_modal()" id="closeModal"
            class="absolute top-[16px] right-[30px] font-bold text-gray-500 hover:text-red-500 xl:hidden">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M19 5L5 19M5 5L19 19" stroke="#010205" stroke-width="1.5"
                    stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>
        <div class="flex flex-col xl:flex-row xl:items-stretch">
            <!-- Form Section -->
            <div class="px-[16px] sm:px-[30px] py-[56px] w-full xl:w-[60%] ">
                <div class=" max-h-[80vh] overflow-y-auto">
                    <h1 class="font-bold text-[24px] sm:text-[32px] text-[#010205]">Payment</h1>

                    <!-- Full Inputs -->
                    <div class="grid grid-cols-1 mt-[24px]">
                        <div>
                            <h1 class="font-medium text-[#010205] leading-[20px] text-[14px]">Add Payment Method</h1>
                            <div id="family_card-number-element"
                                class="p-[12px] mt-[6px] rounded-[8px] w-[358px] sm:w-[508px] h-[44px] bg-[#FBFBFB] border border-[#00000040] text-[16px] leading-[24px] text-[#010205]">
                            </div>
                            <div class="hidden text-[#AB4522] mt-[6px] text-[14px] leading-[20px]" id="invalid_card_number">The card number is
                                incorrect</div>
                        </div>
                        <div class="mt-[14px] flex space-x-[26px]">
                            <div>
                                <h1 class="font-medium text-[#010205] leading-[20px] text-[14px]">Expiry Date</h1>
                                <div id="family_card-expiry-element" class="p-[12px] mt-[6px] rounded-[8px] w-[114px] h-[44px] bg-[#FBFBFB] border border-[#00000040] text-[16px] leading-[24px] text-[#010205]">
                                </div>
                            </div>
                            <div>
                                <h1 class="font-medium text-[#010205] leading-[20px] text-[14px]">CCV</h1>
                                <div id="family_card-cvc-element" class="p-[12px] mt-[6px] rounded-[8px] w-[114px] h-[44px] bg-[#FBFBFB] border border-[#00000040] text-[16px] leading-[24px] text-[#010205]">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-[32px] xl:hidden bg-[url('/assets/image/desktop/section5.png')] bg-cover bg-center py-[40px] rounded-[30px] justify-center items-center">
                        <div class="flex flex-col px-[32px]">
                            <h1 class="font-semibold text-[24px] sm:text-[36px] text-white">Payment Details</h1>
                            <div class="mt-[20px] flex flex-col gap-[16px]">
                                <div class="flex flex-wrap gap-x-[30px] sm:justify-between">
                                    <span class="text-[16px] sm:text-[20px] text-[#FFFFFFCC]">Ultimate plan invitation-yearly</span>
                                    <span class="text-[18px] sm:text-[24px] text-[#FFFFFFCC]">$129</span>
                                </div>
                                <div class="flex flex-wrap gap-x-[30px] sm:justify-between">
                                    <span class="text-[16px] sm:text-[20px] text-[#24A556]">Claim Family discount (25%-3 members)</span>
                                    <span class="text-[18px] sm:text-[24px] text-[#24A556]">$32.25</span>
                                </div>
                            </div>
                            <div class="mt-[54px] flex flex-col gap-[16px]">
                                <div class="flex flex-wrap gap-x-[30px] sm:justify-between">
                                    <span class="text-[18px] sm:text-[20px] text-[#FFFFFFCC]">To Pay</span>
                                    <span class="text-[18px] sm:text-[24px] text-[#FFFFFFCC]">$97.75</span>
                                </div>
                                <p class="text-[16px] sm:text-[18px] text-[#FFFFFFCC]"><strong>Note:</strong> You’ll be charged if the invite is redeemed. Charges will be prorated as per your billing cycle. You can cancel this invite anytime on the Manage Family page.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Discount Note -->
                    <div class="flex items-center gap-[5px] mt-[26px]">
                        <?php require_once(BASEPATH . "/src/common/svgs/dashboard/family/close.php"); ?>
                        <p class="font-semibold text-[14px] text-[#3F3F3F]">
                            Add members to paid plans & get <span class="font-extrabold text-[#24A556]">UP TO 30% OFF</span> all your plans!
                        </p>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end items-center mt-[26px] gap-[27px]">
                        <span onclick="add_payment_close_modal()" class="cursor-pointer text-[14px] font-semibold underline text-[#010205]">Cancel</span>
                        <button onclick="payment()"
                            class="w-[172px] h-[44px] rounded-full bg-[#24A556] text-[16px] font-bold text-white">Payment</button>
                    </div>
                </div>
            </div>

            <!-- Right Image Section -->
            <div class="hidden xl:flex py-[40px] flex-1 bg-[url('/assets/image/desktop/section5.png')] bg-cover bg-center rounded-r-[30px] justify-center items-center">
                <div class="flex flex-col px-[32px]">
                    <h1 class="font-semibold text-[36px] text-white">Payment Details</h1>
                    <div class="mt-[20px] flex flex-col gap-[16px]">
                        <div class="flex justify-between">
                            <span class="text-[20px] text-[#FFFFFFCC]">Ultimate plan invitation-yearly</span>
                            <span class="text-[24px] text-[#FFFFFFCC]">$129</span>
                        </div>
                        <div class="flex justify-between">
                            <span id="family_payment_price_cond" class="text-[20px] text-[#24A556]">Claim Family discount (25%-3 members)</span>
                            <span id="family_payment_price_discount" class="text-[24px] text-[#24A556]">$32.25</span>
                        </div>
                    </div>
                    <div class="mt-[54px] flex flex-col gap-[16px]">
                        <div class="flex justify-between">
                            <span class="text-[20px] text-[#FFFFFFCC]">To Pay</span>
                            <span id="family_payment_price_value" class="text-[24px] text-[#FFFFFFCC]">$97.75</span>
                        </div>
                        <p class="text-[18px] text-[#FFFFFFCC]"><strong>Note:</strong> You’ll be charged if the invite is redeemed. Charges will be prorated as per your billing cycle. You can cancel this invite anytime on the Manage Family page.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
    cardNumber.mount('#family_card-number-element');

    const cardExpiry = elements.create('cardExpiry', {
        style
    });
    cardExpiry.mount('#family_card-expiry-element');

    const cardCvc = elements.create('cardCvc', {
        style
    });
    cardCvc.mount('#family_card-cvc-element');

    async function payment() {
        const {
            paymentMethod,
            error: pmError
        } = await stripe.createPaymentMethod({
            type: 'card',
            card: cardNumber,
            billing_details: {
                name: $("#first_name").val().trim() + " " + $("#last_name").val().trim(),
                email: $("#email").val().trim()
            }
        });

        if (pmError) throw new Error(pmError.message);

        // Step 2: Send to backend
        $.post("/plans", {
            payment_method_id: paymentMethod.id,
            plan_id: window.invite_plan_id,
            name: $("#first_name").val().trim() + " " + $("#last_name").val().trim(),
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
                $.post("/invite_payment_mark_complete", {}, function(res) {
                    if (res && res.success) {
                        add_payment_close_modal();
                        if (typeof invite_member === "function") invite_member();
                    } else {
                        throw new Error((res && res.error) ? res.error : "Could not complete invite payment step.");
                    }
                }, "json").fail(function() {
                    throw new Error("Could not complete invite payment step.");
                });
            }
        });
    }
</script>
