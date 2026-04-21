<?php
$name = isset($_GET["fullname"]) ? $_GET["fullname"] : "";
$city = isset($_GET["city"]) ? $_GET["city"] : "";
?>
<div class="flex justify-center mt-[48px]">
    <div class="flex rounded-full bg-[#FAFAFA] items-center">
        <div class=" px-[13px] py-[7px] sm:px-[24.5px] sm:py-[13px] text-[10px] sm:text-[16px] leading-[140%] font-medium text-[#24A556] rounded-full transition-all whitespace-nowrap duration-200 bg-[#24A55630] active"
            data-type="one" data-people="1">
            1.Enter your information
        </div>
        <div class=" px-[13px]  sm:px-[32px]  text-[10px] sm:text-[18px] font-medium text-[#010205] rounded-full transition-all whitespace-nowrap duration-200"
            data-type="two" data-people="2">
            2.Scan data brokers
        </div>
        <div class=" px-[13px]  sm:px-[32px]  text-[10px] sm:text-[18px] font-medium text-[#010205] rounded-full transition-all whitespace-nowrap duration-200"
            data-type="three" data-people="3">
            See results
        </div>
    </div>
</div>
<div class="flex justify-center sm:block">
    <div class="mt-[48px] px-[16px] sm:mt-[48px] w-[362px] sm:w-[639px]">
        <div>
            <div class="mt-[32px] ">
                <h1 class="font-bold text-[32px] sm:text-[56px] leading-[110%] tracking-[-0.03em] text-[#010205]">
                    Please provide your full name and location
                </h1>
                <h1 class="text-[16px] sm:text-[18px] leading-[130%] text-[#010205] mt-[24px] sm:mt-[33px]">
                    We will
                    scan
                    301 people-search sites now to find those that expose your personal information.
                </h1>
            </div>
            <div class="mt-[32px] ">
                <div class="sm:flex justify-between">
                    <div>
                        <h1 class="font-medium text-[#9D9D9D] leading-[20px] text-[14px]">First and Last
                            Name</h1>
                        <input id="name"
                            class="mt-[6px] rounded-[8px] w-[263px] h-[44px] px-[14px] py-[10px] bg-[#FBFBFB] placeholder:text-[16px] placeholder:leading-[24px] placeholder:text-[#9D9D9D] text-[16px] leading-[24px] text-[#010205]"
                            placeholder="Enter your full name" value="<?= $name ?>" />
                        <div class="hidden text-[#AB4522] mt-[6px] text-[14px] leading-[20px]" id="invalidname">
                            The
                            Full Name is
                            incorrect</div>
                    </div>
                    <div class="mt-[24px] sm:mt-0">
                        <h1 class="font-medium text-[#9D9D9D] leading-[20px] text-[14px]">City, State
                        </h1>
                        <div id="place-picker-box" class="mt-[6px] focus:border-black focus:border-[1px]">
                            <div id="place-picker-container">
                                <!-- populated by google.maps.places.PlaceAutocompleteElement -->
                            </div>
                        </div>
                        <div class="hidden text-[#AB4522] mt-[6px] text-[14px] leading-[20px]" id="invalidcity">
                            The
                            City is
                            incorrect</div>
                    </div>
                </div>
                <div class="mt-[24px] flex justify-center">
                    <button id="send"
                        class="w-[340px] sm:w-[360px] h-[44px] rounded-[8px]  bg-[#5AB87F] but-shadow text-center text-[#FAFAFA] font-semibold leading-[24px] text-[16px] hover:bg-gradient-to-r hover:from-[#77B248] hover:to-[#24A556] active:bg-none active:bg-[#24A556]">Continue</button>
                </div>
            </div>
        </div>
        <div class="relative overflow-y-auto overflow-x-hidden mt-[56px] h-[260px]">
            <div id="accordion-collapse" data-accordion="collapse" class=" w-full border-b border-black divide-y" onclick="toggleCollapse()">
                <!-- FAQ 1 -->
                <h1 class="transition-colors border-black" id="faq1-heading">
                    <button type="button"
                        class="flex items-center justify-between w-full px-5 py-[25px] text-left font-semibold text-black leading-[150%] tracking-[3%] text-[18px]"
                        data-accordion-target="#faq1-body" aria-expanded="false" aria-controls="faq1-body">
                        <span class="faq_title text-[#878C91] dark:bg-white !important dark:text-black !important">Why provide your location?</span>
                        <span class="text-2xl">
                            <span class="icon-plus">+</span>
                            <span class="icon-minus">−</span>
                        </span>
                    </button>
                </h1>
                <div id="faq1-body" class="hidden border-t border-t-transparent " aria-labelledby="faq1-heading">
                    <div class="py-2 px-[24px] text-base text-[#878C91] text-[14px] leading-[180%]">
                        We need your name and location to search for your profiles on people-search sites. The results
                        of our scan will display the profiles of the person with the name and location you provided.
                    </div>
                </div>

                <!-- FAQ 2 -->
                <h1 class="transition-colors border-t border-black" id="faq2-heading">
                    <button type="button"
                        class="flex items-center justify-between w-full px-5 py-5 text-left font-semibold text-black leading-[150%] tracking-[3%] text-[16px]"
                        data-accordion-target="#faq2-body" aria-expanded="false" aria-controls="faq2-body">
                        <span class="faq_title text-[#878C91] dark:bg-white !important dark:text-black !important">What do people-search sites reveal about you?</span>
                        <span class="text-2xl">
                            <span class="icon-plus">+</span>
                            <span class="icon-minus">−</span>
                        </span>
                    </button>
                </h1>
                <div id="faq2-body" class="hidden border-t border-t-transparent" aria-labelledby="faq2-heading">
                    <div class="py-2 px-[24px] text-base text-[#878C91] text-[14px] leading-[180%]">
                        Search sites expose your sensitive personal information such as your name, age, home address,
                        phone number, email addresses, your family members, other people associated with you, your
                        income range, credit score range, political preferences, criminal records, and much more.
                    </div>
                </div>

                <!-- FAQ 3 -->
                <h1 class="transition-colors border-t border-black" id="faq3-heading">
                    <button type="button"
                        class="flex items-center justify-between w-full px-5 py-[27px] text-left font-semibold text-black leading-[150%] tracking-[3%] text-[18px]"
                        data-accordion-target="#faq3-body" aria-expanded="false" aria-controls="faq3-body">
                        <span class="faq_title text-[#878C91] dark:bg-white !important dark:text-black !important">Why is information exposure a problem?</span>
                        <span class="text-2xl">
                            <span class="icon-plus">+</span>
                            <span class="icon-minus">−</span>
                        </span>
                    </button>
                </h1>
                <div id="faq3-body" class="hidden border-t border-t-transparent" aria-labelledby="faq3-heading">
                    <div class="py-2 px-[24px] text-base text-[#878C91] text-[14px] leading-[180%]">
                        Personal data is widely used in criminal and fraudulent schemes. Your information exposed on
                        people-search sites puts you and your family at risk of identity theft, stalking, online
                        harassment, and even home attacks.
                    </div>
                </div>
            </div>
        </div>
        <div class="h-[60px] mt-[60px]  sm:hidden">
            <div class="flex justify-center ">
                <h1 class="text-[14px] text-[#010205] leading-[20px]">©PrivacyDuck 2025</h1>
            </div>
            <div class="flex justify-center mt-[28px] pb-[8px]">
                <div class="bg-[black] h-[5px] w-[134px]"></div>
            </div>
        </div>
    </div>
</div>
<script>
    // let placeIsValid = false;
    let placeIsValid = true;
    let $city;
    let city_value;

    async function initPlaceAutocomplete() {
        try {
            await google.maps.importLibrary("places");
            const container = document.getElementById("place-picker-container");
            if (!container) return;

            const el = new google.maps.places.PlaceAutocompleteElement({
                includedPrimaryTypes: ["locality", "administrative_area_level_1", "country"],
            });
            el.placeholder = "Los Angeles, CA, USA";
            el.style.width = "263px";
            el.style.height = "44px";
            el.style.background = "#FBFBFB";
            el.style.borderRadius = "8px";
            el.style.padding = "10px 14px";
            el.style.display = "block";

            container.innerHTML = "";
            container.appendChild(el);

            const params = new URLSearchParams(window.location.search);
            if (params.get("city")) {
                city_value = params.get("city");
                placeIsValid = true;
                // best-effort: show previous value
                el.value = city_value;
            }

            el.addEventListener("gmp-select", async (event) => {
                const prediction = event.placePrediction;
                city_value = prediction?.text?.text || prediction?.text || "";
                placeIsValid = !!city_value;
            });

            // If user types without selecting, keep a fallback value.
            el.addEventListener("input", () => {
                city_value = (el.value || "").trim();
            });
        } catch (e) {
            console.error(e);
        }
    }

    $("#name").on("input", function() {
        const fullName = $(this).val().trim();
        const pattern = /^[A-Za-z]+(?:\s[A-Za-z]+)+$/;
        let result = pattern.test(fullName);
        if (!result) {
            $("#name").addClass("valid-border");
            $("#invalidname").removeClass("hidden").addClass("flex");
        } else {
            $("#invalidname").removeClass("flex").addClass("hidden");
            $("#name").removeClass("valid-border");
        }
    });

    // Keep the button click if you also want to validate when clicking "Send"
    $("#send").click(function() {
        const fullName = $("#name").val().trim();
        const name_pattern = /^[A-Za-z]+(?:\s[A-Za-z]+)+$/;
        let name_result = name_pattern.test(fullName);

        if (!name_result) {
            $("#name").addClass("valid-border");
            $("#invalidname").removeClass("hidden").addClass("flex");
        } else {
            $("#invalidname").removeClass("flex").addClass("hidden");
            $("#name").removeClass("valid-border");
        }

        // if (!placeIsValid) {
        //     $city.addClass("valid-border");
        //     $("#invalidcity").removeClass("hidden").addClass("flex");
        // } else {
        //     $("#invalidcity").removeClass("flex").addClass("hidden");
        //     $city.removeClass("valid-border");
        // }

        if (!name_result || !placeIsValid) return;

        const params = new URLSearchParams(window.location.search);
        params.set('fullname', fullName);
        params.set('city', city_value);
        const newUrl = `${window.location.pathname}?${params.toString()}`;
        window.history.pushState({}, '', newUrl);
        $.get("/freescaning", {
            fullname: fullName||"",
            city: city_value||""
        }, function(data, status) {
            $('#content').html(data);
        });
    });
</script>
<script async
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA3h-EkI4_Cv126aW4jqkw6COz9PPBUAzs&v=weekly&callback=initPlaceAutocomplete">
</script>