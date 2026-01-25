<div class="flex flex-col gap-[33px]">
    <h1 class="text-[24px] font-bold text-[#010205]">General Settings</h1>
    <div class="flex flex-col gap-[24px] rounded-[30px] bg-white py-[35px] px-[32px] border border-[#F6F6F6] 
    backdrop-blur-[40px]">
        <div class="flex items-center justify-between pb-[20px] border-b border-[#9B9B9C1F]">
            <div>
                <h1 class="text-[20px] leading-[20px] font-medium text-[#010205]">Business Name</h1>
                <h1 class="mt-[16px] text-[16px] leading-[120%] text-[#9B9B9C]">The name that appears on your account and invoices</h1>
            </div>
            <button class="bg-[#00530F] rounded-[5px] w-[77px] h-[30px]" onclick="showBusinessNameModal()">
                <h1 class="text-center text-[14px] leading-[140%] font-medium text-[#FFFFFF] tracking-[-0.02em]">Edit</h1>
            </button>
        </div>
        <div class="flex items-center justify-between pb-[20px] border-b border-[#9B9B9C1F]">
            <div>
                <h1 class="text-[20px] leading-[20px] font-medium text-[#010205]">Email Notifications</h1>
                <h1 class="mt-[16px] text-[16px] leading-[120%] text-[#9B9B9C]">
                    Receive updates about account activity and importants changes
                </h1>
            </div>
            <label class="inline-flex items-center cursor-pointer">
                <div id="switch" class="w-14 h-8 bg-gray-300 rounded-full relative p-1 transition-colors duration-300">
                    <div id="knob" class="bg-white w-6 h-6 rounded-full transform transition-transform duration-300"></div>
                </div>
            </label>

        </div>
        <div class="flex items-center justify-between pb-[20px] border-b border-[#9B9B9C1F]">
            <div>
                <h1 class="text-[20px] leading-[20px] font-medium text-[#010205]">Time Zone</h1>
                <h1 class="mt-[16px] text-[16px] leading-[120%] text-[#9B9B9C]">
                    Set your preferred time zone for reports and notifications
                </h1>
            </div>
            <?php require("timezone.php"); ?>
        </div>
        <div class="flex items-center justify-between pb-[20px]">
            <div>
                <button onclick="showDeleteContactModal()" class="text-[20px] leading-[20px] font-medium text-[#C00000]">Delete Business Account</button>
                <h1 class="mt-[16px] text-[16px] leading-[120%] text-[#9B9B9C]">
                    Permanently delete your business account and all associated data
                </h1>
            </div>
        </div>
    </div>
</div>
<div id="mindmapModal" class="fixed inset-0 inset-0 bg-[#0000007D] px-[16px] border-1 border-[#F6F6F63A] flex items-center justify-center hidden z-50 animate-[opacity_0.5s_ease-out]">
    <div class="relative bg-[#FFFFFF] border border-[#F6F6F63B] rounded-[30px] shadow-[0_4px_4px_0_#F6F6F626] px-[24px] pt-[40px] pb-[32px]">
        <button id="closeModal" onclick="closeModal('mindmapModal')" class="absolute top-[15px] right-[20px] text-center font-bold text-gray-500 hover:text-red-500">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 5L5 19M5 5L19 19" stroke="#020609" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </button>
        <div class="flex flex-col">
            <h1 class="text-[18px] font-semibold text-[#010205]">Business Settings</h1>
            <h1 class="text-[16px] mt-[32px] text-[#010205]">The name that appears on your account and invoices</h1>
            <input type="text" onchange="checkInput('mindmapName','saveMindmap')" id="mindmapName" placeholder="Enter business name" class="mt-[6px] bg-[#FBFBFB] mt-[32px] w-[493px] h-[44px] px-[14px] py-[10px] border border-[#00000040] rounded-[8px]">
            <div class="flex items-center justify-end mt-[26px] gap-[27px]">
                <h1 onclick="closeModal('mindmapModal')" class="underline cursor-pointer text-[14px] text-[#010205] leading-[130%] tracking-[-0.02em] font-semibold">
                    Cancel
                </h1>
                <button disabled id="saveMindmap" onclick="saveMindmap()" class="bg-[#EEEEEE] rounded-full w-[102px] h-[44px] justify-center items-center">
                    Save
                </button>
            </div>
        </div>
    </div>
</div>
<div id="deleteContactModal" class="fixed inset-0 inset-0 bg-[#0000007D] px-[16px] border-1 border-[#F6F6F63A] flex items-center justify-center hidden z-50 animate-[opacity_0.5s_ease-out]">
    <div class="relative bg-[#FFFFFF] border border-[#F6F6F63B] rounded-[30px] shadow-[0_4px_4px_0_#F6F6F626] px-[24px] pt-[40px] pb-[32px]">
        <button id="closeModal" onclick="closeModal('deleteContactModal')" class="absolute top-[15px] right-[20px] text-center font-bold text-gray-500 hover:text-red-500">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 5L5 19M5 5L19 19" stroke="#020609" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </button>
        <div class="flex flex-col">
            <h1 class="text-[18px] font-semibold text-[#010205]">Delete Business Account</h1>
            <h1 class="text-[16px] mt-[32px] text-[#010205]">We will contact to you with phone number</h1>
            <input type="text" onchange="checkInput('deleteTitle','deleteAccount','bg-[#C00000]')" id="deleteTitle" placeholder="Enter phone number" class="mt-[6px] bg-[#FBFBFB] mt-[32px] w-[493px] h-[44px] px-[14px] py-[10px] border border-[#00000040] rounded-[8px]">
            <div class="flex items-center justify-end mt-[26px] gap-[27px]">
                <h1 onclick="closeModal('deleteContactModal')" class="underline cursor-pointer text-[14px] text-[#010205] leading-[130%] tracking-[-0.02em] font-semibold">
                    Cancel
                </h1>
                <button disabled id="deleteAccount" onclick="deleteAccount()" class="bg-[#EEEEEE] rounded-full w-[102px] h-[44px] justify-center items-center">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function showBusinessNameModal() {
        const role = "<?php echo $_SESSION["work_role"]; ?>";
        if (role != 1) {
            toastr.error("Business account is not authorized. Please contact support team.")
            return;
        }
        let mindmapName = "<?php echo isset($_SESSION['mindmap_name']) ? $_SESSION['mindmap_name'] : ''; ?>";
        document.getElementById('mindmapName').value = mindmapName;
        document.getElementById('mindmapModal').classList.remove('hidden');
    }
    function showDeleteContactModal() {
        const role = "<?php echo $_SESSION["work_role"]; ?>";
        if (role != 1) {
            toastr.error("You don't have business account!")
            return;
        }
        document.getElementById('deleteContactModal').classList.remove('hidden');
    }

    function closeModal(x) {
        document.getElementById(x).classList.add('hidden');
    }

    function checkInput(x="mindmapName",y="saveMindmap",bg="bg-[#24A556]") {
        const input = document.getElementById(x);
        if (input.value) {
            document.getElementById(y).disabled = false;
            document.getElementById(y).classList.remove('bg-[#EEEEEE]');
            document.getElementById(y).classList.remove('text-[#010205]');
            document.getElementById(y).classList.add(bg);
            document.getElementById(y).classList.add('text-[#FFFFFF]');
        } else {
            document.getElementById(y).disabled = true;
            document.getElementById(y).classList.remove(bg);
            document.getElementById(y).classList.remove('text-[#FFFFFF]');
            document.getElementById(y).classList.add('bg-[#EEEEEE]');
            document.getElementById(y).classList.add('text-[#010205]');
        }
    }

    function saveMindmap() {
        const input = document.getElementById('mindmapName').value;
        $.post("/business/dashboard/main/editMindmapname", {
                mindmap_name: input
            },
            function(data) {
                if (data.error) {
                    toastr.error(data.error);
                } else {
                    document.getElementById('mindmapName').value = "";
                    toastr.success(data.success);
                }
                closeModal("mindmapModal");
            });
    }

    function deleteAccount() {
        const input = document.getElementById('deleteTitle').value;
        toastr.success("Support team will contact you to delete your business account.")
        closeModal('deleteContactModal')
        // $.post("/business/dashboard/main/deleteAccount", {
        //         delete_title: input
        //     },
        //     function(data) {
        //         if (data.error) {
        //             toastr.error(data.error);
        //         } else {
        //             document.getElementById('deleteTitle').value = "";
        //             toastr.success(data.success);
        //         }
        //         closeModal();
        //     });
    }



    function switch_button() {
        let enabled = localStorage.getItem("business_email_notifications");
        const switchEl = document.getElementById('switch');
        const knobEl = document.getElementById('knob');
        const toggleSwitch = () => {
            enabled = !enabled;
            localStorage.setItem("business_email_notifications", enabled);
            if (enabled) {
                switchEl.classList.remove('bg-gray-300');
                switchEl.classList.add('bg-green-500');
                knobEl.classList.add('translate-x-6');
            } else {
                switchEl.classList.remove('bg-green-500');
                switchEl.classList.add('bg-gray-300');
                knobEl.classList.remove('translate-x-6');
            }
        };
        if (enabled) {
            switchEl.classList.remove('bg-gray-300');
            switchEl.classList.add('bg-green-500');
            knobEl.classList.add('translate-x-6');
        } else {
            switchEl.classList.remove('bg-green-500');
            switchEl.classList.add('bg-gray-300');
            knobEl.classList.remove('translate-x-6');
        }
        // Add click event
        switchEl.addEventListener('click', toggleSwitch);
    }
</script>