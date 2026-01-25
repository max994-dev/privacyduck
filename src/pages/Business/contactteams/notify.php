<div id="business_contactteam_notify_modal"
    class="fixed inset-0 inset-0 bg-[#00000040] px-[16px] border-1 border-[#F6F6F63A] backdrop-blur-md flex items-center justify-center hidden z-[19] animate-[opacity_0.5s_ease-out]">
    <div
        class="relative bg-[#00530F] rounded-[15px] shadow-[0px_4px_30px_0px_#00530F7D] px-[8px] py-[16px] sm:px-[30px] sm:py-[32px] relative border border-[#F6F6F63B]">
        <button id="business_closecontactnotifymodal" onclick="business_closecontactnotifymodal()" class="absolute top-[32px] right-[30px] text-center font-bold text-gray-500 hover:text-red-500">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 5L5 19M5 5L19 19" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </button>
        <div class="pt-[50px] px-[30px] pb-[32px] flex flex-col items-center">
            <div class="w-[592px] flex justify-center">
                <div>
                    <div class="flex items-center gap-[8px]">
                        <h1 style="font-family: 'Alatsi', sans-serif;" class="text-[28px] tracking-[-0.02em] uppercase text-[#FFFFFF]">Privacy<label class="text-[#FFCF50]" style="font-family: 'Alatsi', sans-serif;">Duck</label></h1>
                        <?php require(BASEPATH . '/src/common/svgs/business/landing/duck.php'); ?>
                    </div>
                    <h1 style="font-family: 'Alatsi', sans-serif;" class="relative top-[-10px] text-[20px] tracking-[-0.02em] text-[#FFFFFF] uppercase">Enterprice</h1>
                </div>
            </div>
            <h1 class="mt-[32px] text-[24px] leading-[24px] text-[#FFFFFF]">Thank you for your request</h1>
        </div>
    </div>
</div>
<script>
    function business_closecontactnotifymodal() {
        document.getElementById("business_contactteam_notify_modal").classList.add("hidden");
    }
</script>