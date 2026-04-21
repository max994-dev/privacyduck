<div class="mt-[42px] bg-[url('/assets/image/desktop/dashboard/editinfo/edit_bg.png')] bg-cover bg-center bg-no-repeat 
        rounded-[30px] px-[42px] py-[33px]">
    <div class="pr-[44px] flex justify-between items-center">
        <div class="flex space-x-[32px] items-center">
            <?php require_once(BASEPATH . "/src/common/svgs/dashboard/editinfo/dark.php"); ?>
            <div class="max-w-[440px]">
                <h1 class="font-bold text-white text-[18px] leading-[130%] tracking-[-0.03em] align-middle">Protect yourself and your family</h1>
                <h1 class="text-white text-[14px] leading-[130%] tracking-[-0.03em] align-middle mt-[10px]">
                    Add your past, current, and future names, addresses, and phone numbers to be included in the next scheduled wave of removals
                </h1>
            </div>
        </div>
        <button onclick="editUserinfo(<?php echo $_SESSION['user_id']; ?>)" class="w-[219px] h-[56px] bg-[#24A556] text-white text-[16px] leading-[140%] tracking-[-0.02em] font-bold rounded-full flex justify-center items-center gap-[42px]">
            Get Started
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5 12H19" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M12 5L19 12L12 19" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>
    </div>
</div>