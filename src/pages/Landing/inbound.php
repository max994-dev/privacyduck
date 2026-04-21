<div id="openModalBtn"
    class="cursor-pointer animate-[slideUp_0.5s_ease-out] fixed flex gap-[10px] items-center bottom-4 left-4 bg-[#24A556] font-bold text-[14px] text-white px-[20px] py-[10px] rounded-[5px] shadow-lg hidden z-40">
    <div id="inboundRun" class="max-w-[65px] text-center">GET 10% OFF</div>
    <button id="closeInbound" class="text-center font-bold text-gray-500 hover:text-red-500">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19 5L5 19M5 5L19 19" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round"
                stroke-linejoin="round" />
        </svg>
    </button>
</div>
<!-- Modal -->
<div id="emailModal"
    class="fixed inset-0 bg-[#00000040] px-[16px] border-1 border-[#F6F6F63A] backdrop-blur-md flex items-center justify-center hidden z-50 animate-[opacity_0.5s_ease-out]">
    <div
        class="relative bg-white max-h-[95vh] overflow-y-auto  rounded-[5px] shadow-xl px-[20px] sm:px-[30px] py-[32px] text-center relative shadow-[0px_4px_4px_#0206091A]">
        <button id="closeModal" class="absolute top-[32px] right-[30px] text-center font-bold text-gray-500 hover:text-red-500">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 5L5 19M5 5L19 19" stroke="#020609" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </button>
        <div class="mt-[34px] text-center space-y-[32px] ">
            <div class="flex justify-center">
                <img src="/assets/image/desktop/logo2.svg" alt="logo" class="w-[236px] h-[48px]" />
            </div>
            <h2 class="font-bold text-[32px] text-[#020609]"> Ready to Remove <br />Your Personal Data?</h2>
            <h2 class="font-medium text-[24px] leading-[110%] text-[#020609E6] max-w-[325px]">Sign Up below to save 10%
                on your first course</h2>
            <div class="flex justify-center">
                <div>
                    <h2 class="font-bold text-[18px] text-[#020609] max-w-[325px]">You’ll also receivce:</h2>
                    <ul class="mt-[17px] text-left text-[#010205] flex flex-col gap-[8px]">
                        <li class="flex items-center gap-x-[4px]">
                            <i class="fa-solid fa-square-check" style="color: #24A556;"></i>
                            Remove data from people search sites
                        </li>
                        <li class="flex items-center gap-x-[4px]">
                            <i class="fa-solid fa-square-check" style="color: #24A556;"></i>
                            Monitor personal data exposure
                        </li>
                        <li class="flex items-center gap-x-[4px]">
                            <i class="fa-solid fa-square-check" style="color: #24A556;"></i>
                            Prevent identity theft & doxing
                        </li>
                        <li class="flex items-center gap-x-[4px]">
                            <i class="fa-solid fa-square-check" style="color: #24A556;"></i>
                            Stop data brokers from selling your info
                        </li>
                        <li class="flex items-center gap-x-[4px]">
                            <i class="fa-solid fa-square-check" style="color: #24A556;"></i>
                            Secure family member info too
                        </li>

                    </ul>
                </div>
            </div>
            <div>
                <div class="flex justify-center">
                    <input id="inboundEmail" type="text"
                        class="w-[298px] text-[#010205] sm:w-[325px] h-[44px] bg-[#FBFBFB] rounded-[8px] border-[#EEEEEE] px-[14px] py-[10px] placeholder:text-[#9D9D9D] placeholder:text-[16px] placeholder:leading-[24px] focus:ring-black focus:border-none"
                        placeholder="Enter your email" />
                </div>
                <div class="hidden text-[#AB4522] mt-[6px] text-[14px] leading-[20px]" id="invalid">The email is
                    incorrect</div>
            </div>
            <button id="subscribe"
                class="cursor-pointer relative flex justify-center group items-center gap-2 py-[16px] w-full bg-[#24A556] text-white text-[#F5F5F5] text-[16px] leading-[140%] font-bold rounded-full hover:bg-green-700 transition duration-300">
                Subscribe
                <svg class="w-4 h-4 transition-transform duration-300 transform group-hover:translate-x-1"
                    fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M10.293 3.293a1 1 0 011.414 0L17 8.586a1 1 0 010 1.414l-5.293 5.293a1 1 0 01-1.414-1.414L14.586 10H4a1 1 0 110-2h10.586l-4.293-4.293a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
</div>
<script>
    function landing_inbound_init() {
        function setCookie(name, value, days) {
            let expires = "";
            if (days) {
                const date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/";
        }

        function getCookie(name) {
            const nameEQ = name + "=";
            const ca = document.cookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }
        const modal = document.getElementById("emailModal");
        const openBtn = document.getElementById("openModalBtn");
        const inboundRunBtn = document.getElementById("inboundRun");
        const closeBtn = document.getElementById("closeModal");
        const closeInboundBtn = document.getElementById("closeInbound");
        if (!getCookie("emailModalHidden")) {
            setTimeout(() => {
                document.getElementById("openModalBtn").classList.remove("hidden");
            }, 5000); // 5 seconds
        }
        if (!getCookie("firstModalShown")) {
            setTimeout(() => {
                if (!getCookie("firstModalShown")) {
                    modal.classList.remove("hidden");
                    openBtn.classList.add("hidden");
                    setCookie("firstModalShown", "true", 10)
                }
            }, 10000); // 10 seconds
        }


        inboundRunBtn.addEventListener("click", () => {
            setCookie("firstModalShown", "true", 10)
            modal.classList.remove("hidden");
            openBtn.classList.add("hidden");
        });

        closeBtn.addEventListener("click", () => {
            openBtn.classList.remove("hidden");
            modal.classList.add("hidden");
        });

        closeInboundBtn.addEventListener("click", () => {
            openBtn.classList.add("hidden");
            setCookie("emailModalHidden", "true", 10)
        });

        // subscribeBtn.addEventListener("click", () => {
        //     setCookie("emailModalHidden", "true", 10)
        //     modal.classList.add("hidden");
        // });

        $("#subscribe").click(function() {
            const email = $("#inboundEmail").val().trim()
            let pattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            let result = pattern.test(email);
            if (!result) {
                $("#inboundEmail").addClass("valid-border");
                $("#invalid").removeClass("hidden").addClass("flex");
            } else {
                $("#invalid").removeClass("flex").addClass("hidden");
                $("#inboundEmail").removeClass("valid-border");
                $("#subscribe").html(window.loadingHtml);
                $("#subscribe").prop("disabled", true);
                $.post("/inboundProcess", {
                    email: email,
                }, function(res) {
                    const but = `Subscribe
                <svg class="w-4 h-4 transition-transform duration-300 transform group-hover:translate-x-1"
                    fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M10.293 3.293a1 1 0 011.414 0L17 8.586a1 1 0 010 1.414l-5.293 5.293a1 1 0 01-1.414-1.414L14.586 10H4a1 1 0 110-2h10.586l-4.293-4.293a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>`
                    $("#subscribe").html(but);
                    $("#subscribe").prop("disabled", false);
                    if (res["error"]) {
                        toastr.error("Server Error")
                    } else if (res["warning"]) {
                        toastr.warning(res["warning"])
                    } else {
                        window.open("https://buy.stripe.com/5kQ5kC5bBegS01u6uCdwc0M?prefilled_email=" + email + "&prefilled_promo_code=ARTDECO", "_blank");
                        // toastr.success(`${email} has been successfully submitted.`)
                    }
                });
                // You can add submit logic here if valid
            }

        })
    }
    landing_inbound_init();
</script>