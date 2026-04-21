<style>
    #work:focus {
        border: none !important;
    }
</style>
<div class="flex items-center justify-between pl-[52px] pr-[38.5px] h-[472px] rounded-[30px] shadow-[0px_4px_4px_#00000040]
     bg-[url('/assets/image/desktop/dashboard/work/work_bg.png')] bg-cover">
    <?php require BASEPATH . "/src/common/svgs/dashboard/work/big_duck.php"; ?>
    <div class="flex flex-col max-w-[50%]">
        <h1 class="font-semibold text-[38px] leading-[130%] tracking-[-0.03em] text-white">
            Unlock Additional Privacy Features & Benefits from Your Employer
        </h1>
        <h1 class="mt-[24px] font-medium text-[18px] leading-[120%] text-white">
            Many companies even cover the cost of your license!
        </h1>
        <h1 class="mt-[16px] text-[14px] leading-[120%] text-white">
            Check your license eligibility and more by quickly linking your work email:
        </h1>
        <div class="mt-[48px] flex justify-between items-center">
            <input type="text" id="work" class="border-none text-white placeholder:text-white placeholder:font-medium placeholder:text-[16px]
            leading-[140%] tracking-[-0.02em] w-[314px] h-[52px] 
            pl-[24px] bg-white/10 backdrop-blur-[50px] rounded-full" placeholder="Your Work Email*">
            <button id="business_work_btn" class="w-[148px] h-[56px] bg-[#00530F] rounded-full flex justify-center items-center">
                <h1 class="text-[#FFCF50] font-bold text-[16px] leading-[140%] tracking-[-0.02em]">Continue</h1>
                <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.5 12H19.5" stroke="#FFCF50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M12.5 5L19.5 12L12.5 19" stroke="#FFCF50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    </div>
</div>
<div class="bg-[#FEFEFE] border border-[#F6F6F6] rounded-[30px] mt-[32px]">
    <div class="relative pt-[24px] pr-[69px] pb-[58px] pl-[32px]">
        <div class="flex gap-[13px]">
            <div class="relative top-[50px]">
                <?php require BASEPATH . "/src/common/svgs/dashboard/work/coma.php"; ?>
            </div>
            <div class="flex flex-col">
                <div class="flex items-center space-x-[28px] text-[24px] ">
                    <button class="hover:text-[#0F381280] text-[#0F3812]">&lt;</button>
                    <button class="hover:text-[#0F381280] text-[#0F3812]">&gt;</button>
                </div>
                <h1 class="mt-[42px] font-medium text-[24px] leading-[160%] tracking-[-0.03em] text-[#010205]">
                    Using PrivacyDuck to remove my business data was a game changer.
                    The process was quick and efficient, and I felt secure knowing my information was being handled with care.
                </h1>
                <div class="mt-[17px] flex items-center space-x-[24px]">
                    <?php require BASEPATH . "/src/common/svgs/dashboard/work/avatar.php"; ?>
                    <div>
                        <div class="font-bold text-[18px] leading-[180%] text-[#010205]">Michael Kaizer</div>
                        <div class="font-medium text-[14px] leading-[180%] text-[#010205CC]">CEO of Basecamp Corp</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function business_work() {
        $("#business_work_btn").click(function() {
            const business_isauthenticated = "<?php echo isset($_SESSION["work_isAuthenticated"]) ? $_SESSION["work_isAuthenticated"]:false; ?>"
            if (business_isauthenticated) {
                window.location.href = "/business/dashboard";
            } else {
                window.location.href = "/business/link"
            }
        })

    }
</script>