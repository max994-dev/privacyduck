<div class="flex items-center space-x-[7px] cursor-pointer" onclick="editUserinfoClose()">
    <?php require(BASEPATH . "/src/common/svgs/dashboard/editinfo/small_symbol.php") ?>
    <h1 id="edit_name" class="text-[24px] text-[#010205] font-semibold"></h1>
</div>
<div class="mt-[28px] mx-auto grid grid-cols-1 md:grid-cols-2 gap-[27px]">
    <div class="bg-[#FFFFFFE3] rounded-[30px] border border-[#F6F6F6] px-[32px] py-[34px]">
        <div class="flex items-center gap-[8px] rounded-[15px] bg-[#E8FCE7] border border-[#F6F6F63B] px-[16px] py-[13px] shadow-[0px_4px_4px_0px_#F6F6F626]">
            <i class="fa-solid fa-circle-exclamation text-[#24A556]"></i>
            <h1 class="font-medium text-[10px] leading-[120%] text-[#7E7E7E]">The more information you provide, the more PrivacyDuck can find and remove for you.
                This allows us to filter out all irrelevant results and exclude people with the same name and/or location as you.</h1>
        </div>
        <form onsubmit="updateinfo()">
            <div class="mt-[10px] grid grid-cols-1 md:grid-cols-2 md:gap-[26px]">
                <div class="grid grid-cols-1">
                    <?php
                    $datas = [
                        ["name" => "First Name *", "type" => "text", "id" => "edit_firstname", "placeholder" => "John", "size" => "full"],
                        ["name" => "Last Name *", "type" => "text", "id" => "edit_lastname", "placeholder" => "Doe", "size" => "full"],
                    ];
                    function searchIndex($array, $value)
                    {
                        foreach ($array as $index => $item) {
                            if ($item === $value) {
                                return $index;
                            }
                        }
                        return -1;
                    }

                    foreach ($datas as $data) {
                        if ($data["size"] == "full") {
                    ?>
                            <div class="flex flex-col mt-[<?php echo in_array(searchIndex($datas, $data), [0]) ? "0px" : "16px"; ?>] sm:mt-[<?php echo in_array(searchIndex($datas, $data), [0]) ? "0px" : "16px"; ?>]">
                                <label for="<?= $data["id"]; ?>" class="font-medium text-[14px] leading-[20px] text-[#010205]"><?= $data["name"]; ?></label>
                                <input type="<?= $data["type"]; ?>" id="<?= $data["id"]; ?>" placeholder="<?= $data["placeholder"]; ?>"
                                    class="mt-[6px] h-[48px] px-[14px] rounded-[8px] border border-[#00000040]">
                                <div class="hidden mt-[6px] text-[14px] leading-[20px]" id="family_invalid_<?= $data["id"]; ?>"></div>
                            </div>
                    <?php }
                    } ?>
                </div>
                <div>
                    <label for="face-upload" class="upload-area flex flex-col items-center justify-center w-full h-48 border-2 border-dashed rounded-xl cursor-pointer bg-gradient-to-br from-gray-50 to-gray-100 hover:from-purple-50 hover:to-pink-50 border-gray-300 hover:border-purple-400 transition-all duration-300">
                        <div id="preview" class="flex flex-col items-center justify-center pt-5 pb-6">
                            
                        </div>
                        <input id="face-upload" type="file" accept="image/*" class="hidden" onchange="previewImage(event)" />
                    </label>
                    <div id="progress-container" class="hidden mt-4">
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Uploading...</span>
                            <span id="progress-text">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div id="progress-bar" class="bg-gradient-to-r from-purple-500 to-pink-500 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                    </div>
                    <div id="success-message" class="hidden mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-green-800 font-medium">Image uploaded successfully!</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-[16px] grid grid-cols-1 md:grid-cols-2 md:gap-[26px]">
                <div>
                    <div class="mt-[10px] grid grid-cols-1">
                        <?php
                        $datas = [
                            ["name" => "Phone Number *", "type" => "text", "id" => "edit_phone", "placeholder" => "+1 123-456-7890", "size" => "full"],
                            ["name" => "City *", "type" => "text", "id" => "edit_city", "placeholder" => "New York", "size" => "full"],
                        ];
                        foreach ($datas as $data) {
                            if ($data["size"] == "full") {
                        ?>
                                <div class="flex flex-col mt-[<?php echo in_array(searchIndex($datas, $data), [0]) ? "0px" : "16px"; ?>] sm:mt-[<?php echo in_array(searchIndex($datas, $data), [0]) ? "0px" : "16px"; ?>]">
                                    <label for="<?= $data["id"]; ?>" class="font-medium text-[14px] leading-[20px] text-[#010205]"><?= $data["name"]; ?></label>
                                    <input type="<?= $data["type"]; ?>" id="<?= $data["id"]; ?>" placeholder="<?= $data["placeholder"]; ?>"
                                        class="mt-[6px] h-[48px] px-[14px] rounded-[8px] border border-[#00000040]">
                                    <div class="hidden mt-[6px] text-[14px] leading-[20px]" id="family_invalid_<?= $data["id"]; ?>"></div>
                                </div>
                        <?php }
                        } ?>
                    </div>
                    <div class="mt-[16px] grid grid-cols-1 lg:grid-cols-2 gap-[12px] lg:gap-[26px]">
                        <?php
                        $datas = [
                            ["name" => "Zip *", "type" => "text", "id" => "edit_zip", "placeholder" => "12345", "size" => "half"],
                            ["name" => "State *", "type" => "text", "id" => "edit_state", "placeholder" => "New York", "size" => "half"],
                        ];
                        foreach ($datas as $data) {
                        ?>
                            <div class="flex flex-col">
                                <label for="<?= $data["id"]; ?>" class="font-medium text-[14px] leading-[20px] text-[#010205]"><?= $data["name"]; ?></label>
                                <input type="<?= $data["type"]; ?>" id="<?= $data["id"]; ?>" placeholder="<?= $data["placeholder"]; ?>"
                                    class="mt-[6px] h-[48px] px-[14px] rounded-[8px] border border-[#00000040]">
                                <div class="hidden mt-[6px] text-[14px] leading-[20px]" id="family_invalid_<?= $data["id"]; ?>"></div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="grid grid-cols-1">
                        <?php
                        $datas = [
                            ["name" => "Address *", "type" => "text", "id" => "edit_address", "placeholder" => "123 Main St", "size" => "full"],
                        ];
                        foreach ($datas as $data) {
                            if ($data["size"] == "full") {
                        ?>
                                <div class="flex flex-col mt-[16px]">
                                    <label for="<?= $data["id"]; ?>" class="font-medium text-[14px] leading-[20px] text-[#010205]"><?= $data["name"]; ?></label>
                                    <input <?php if ($data["type"] == "email") echo 'readonly'; ?> type="<?= $data["type"]; ?>" id="<?= $data["id"]; ?>" placeholder="<?= $data["placeholder"]; ?>"
                                        class="mt-[6px] h-[48px] px-[14px] rounded-[8px] border border-[#00000040]">
                                    <div class="hidden mt-[6px] text-[14px] leading-[20px]" id="family_invalid_<?= $data["id"]; ?>"></div>
                                </div>
                        <?php }
                        } ?>
                    </div>
                </div>
                <div class="mt-[10px] flex flex-col justify-between border border-[#00000040] rounded-[8px] p-[16px]">
                    <div id="phones" class="flex flex-col gap-[8px] items-stretch">
                    </div>
                    <div class="flex gap-[4px] items-center justify-between overflow-y-auto">
                        <button type="button" onclick="addAddress()" id="add_address" class="w-full h-[44px] flex justify-center items-center rounded-full bg-gradient-to-r 
                        from-[#77B248] to-[#24A556] font-bold text-[16px] leading-[140%] text-white">
                            Add
                        </button>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1">
                <?php
                $datas = [
                    ["name" => "Email *", "type" => "email", "id" => "edit_email", "placeholder" => "Enter Email", "size" => "full"],
                ];
                foreach ($datas as $data) {
                    if ($data["size"] == "full") {
                ?>
                        <div class="flex flex-col mt-[16px]">
                            <label for="<?= $data["id"]; ?>" class="font-medium text-[14px] leading-[20px] text-[#010205]"><?= $data["name"]; ?></label>
                            <input <?php if ($data["type"] == "email") echo 'readonly'; ?> type="<?= $data["type"]; ?>" id="<?= $data["id"]; ?>" placeholder="<?= $data["placeholder"]; ?>"
                                class="mt-[6px] h-[48px] px-[14px] rounded-[8px] border border-[#00000040]">
                            <div class="hidden mt-[6px] text-[14px] leading-[20px]" id="family_invalid_<?= $data["id"]; ?>"></div>
                        </div>
                <?php }
                } ?>
            </div>
            <div class="mt-[42px] flex justify-end items-center gap-[27px]">
                <a class="underline cursor-pointer font-semibold text-[14px] leading-[130%] text-[#010205]" onclick="editUserinfoClose()">Skip</a>
                <button type="submit" id="updateinfo_btn" class="w-[111px] h-[44px] flex justify-center items-center rounded-full bg-gradient-to-r from-[#77B248] to-[#24A556] font-bold text-[16px] leading-[140%] text-white">Save</button>
            </div>
        </form>
    </div>
    <div class="bg-[#FFFFFFE3] rounded-[30px] border border-[#F6F6F6] px-[32px] py-[34px] flex flex-col justify-between">
        <div>
            <div class="bg-[#E8FCE7] px-[16px] py-[12px] rounded-[15px] border border-[#F6F6F63B] shadow-[0px_4px_4px_0px_#F6F6F626]">
                <div class="flex flex-col max-w-[90%]">
                    <h1 class="font-semibold text-[12px] leading-[120%] text-[#010205]">First name</h1>
                    <div class="mt-[10px]">
                        <h1 class="font-medium text-[10px] leading-[120%] text-[#7E7E7E]">Add more names if:</h1>
                        <ul class="mt-[3px] pl-[16px] font-medium text-[#7E7E7E] list-disc text-[10px] leading-[150%]">
                            <li>You go by a nickname or abbreviated name, e.g., William/Bill, Christina/Tina, T.J/Travis James, etc</li>
                            <li>There are spelling variations of your name e.g., Jon/John, Army/Aimee, Hailey/Haley, etc.</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="mt-[26px] bg-[#E8FCE7] px-[16px] py-[18px] rounded-[15px] border border-[#F6F6F63B] shadow-[0px_4px_4px_0px_#F6F6F626]">
                <div class="flex flex-col max-w-[90%]">
                    <h1 class="font-semibold text-[12px] leading-[120%] text-[#010205]">Last name</h1>
                    <div class="mt-[10px]">
                        <h1 class="font-medium text-[10px] leading-[120%] text-[#7E7E7E]">Add more names if:</h1>
                        <ul class="mt-[3px] pl-[16px] font-medium text-[#7E7E7E] list-disc text-[10px] leading-[150%]">
                            <li>Your last name changed due to marriage or divorce.</li>
                            <li>Your last name is commonly misspelled, e.g., Smith/Smyth, etc.</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="mt-[53px] bg-[#E8FCE7] px-[16px] py-[18px] rounded-[15px] border border-[#F6F6F63B] shadow-[0px_4px_4px_0px_#F6F6F626]">
                <div class="flex flex-col max-w-[90%]">
                    <h1 class="font-semibold text-[12px] leading-[120%] text-[#010205]">Address</h1>
                    <div class="mt-[10px]">
                        <h1 class="text-[#7E7E7E] font-medium text-[10px] leading-[120%]">
                            Enter as many of your past addresses as you can - people-search sites can have outdated databases and display your profile with the previous address instead of the current one.
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-center items-center text-[#C00000] space-x-[10px] cursor-pointer">
            <i class="fa-solid fa-trash  text-[24px]"></i>
            <button onclick="deleteAccount()" class="font-semibold text-[14px] leading-[130%] tracking-[-0.02em] align-middle">Permanently Delete My Account</button>
        </div>
    </div>
</div>

<script>
    const edit_loadingHtml = "<img src='/assets/image/desktop/loading1.webp' class='w-6 h-6 flex mr-2'> <span class='font-semibold text-[12px] leading-[130%] tracking-[-0.02em]'>Saving...</span>";

    function editUserinfoClose() {
        document.getElementById("edit_intro").classList.remove("hidden");
        document.getElementById("edit_detail_content").classList.add("hidden");
        userinfoTable();
    }

    function updateinfo() {
        // Prevent default form submission
        file = document.getElementById("face-upload").files[0];
        event.preventDefault();
        document.getElementById("updateinfo_btn").innerHTML = edit_loadingHtml;
        const formData = new FormData();
        formData.append("first_name", $("#edit_firstname").val().trim());
        formData.append("last_name", $("#edit_lastname").val().trim());
        formData.append("email", $("#edit_email").val().trim());
        formData.append("contacts", JSON.stringify(window.contacts));
        formData.append("file", file);
        $.ajax({
            url: "/update_user_info",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                if (res.error) {
                    toastr.error(res.error);
                    document.getElementById("updateinfo_btn").innerHTML = "Save";
                } else {
                    toastr.success("User info updated successfully");
                    document.getElementById("updateinfo_btn").innerHTML = "Save";
                    editUserinfoClose();
                }
            }
        });

        return false; // prevent actual form submission
    }

    function addAddress() {
        event.preventDefault();
        document.getElementById("add_address").innerHTML = edit_loadingHtml;
        $.post("/add_user_address", {
            contacts: {
                city: $("#edit_city").val().trim(),
                state: $("#edit_state").val().trim(),
                phone: $("#edit_phone").val().trim(),
                zip: $("#edit_zip").val().trim(),
                address: $("#edit_address").val().trim(),
            }
        }, (res) => {
            if (res.error) {
                toastr.error(res.error);
                document.getElementById("add_address").innerHTML = "Add";
            } else {
                toastr.success("Address added successfully");
                document.getElementById("add_address").innerHTML = "Add";
                getcontacts(res["contacts"]);
            }
        });

        return false; // prevent actual form submission
    }

    function deleteAddress(index) {
        event.preventDefault();
        $.post("/delete_user_address", {
            pos: index
        }, (res) => {
            getcontacts(res["contacts"]);
        });

        return false; // prevent actual form submission
    }

    function deleteAccount() {
        event.preventDefault();
        $.post("/api/delete_account", {}, (res) => {
            if (res.error) {
                toastr.error(res.error);
            } else {
                toastr.success("Account deleted successfully");
                window.location.href = "/login";
            }
        });
    }

    let uploadArea = document.querySelector('.upload-area');

    // Drag and drop functionality
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, unhighlight, false);
    });

    uploadArea.addEventListener('drop', handleDrop, false);

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight(e) {
        uploadArea.classList.add('dragover');
    }

    function unhighlight(e) {
        uploadArea.classList.remove('dragover');
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            document.getElementById('face-upload').files = files;
            previewImage({
                target: {
                    files: files
                }
            });
        }
    }

    function previewImage(event) {
        const file = event.target.files[0];
        if (!file) return;

        // Validate file size (2MB limit)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            return;
        }

        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('Please select a valid image file');
            return;
        }

        // Show progress bar
        showProgress();

        const reader = new FileReader();
        reader.onload = function(e) {
            const previewDiv = document.getElementById('preview');
            previewDiv.innerHTML = `
      <div class="relative group">
        <img src="${e.target.result}" alt="Preview" class="max-h-40 w-full rounded-xl shadow-lg border-2 border-white">
        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-xl transition-all duration-300 flex items-center justify-center">
          <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
          </svg>
        </div>
      </div>
      <p class="mt-3 text-sm text-gray-600 font-medium">Click to change image</p>
    `;

            // Hide progress bar and show success message
            setTimeout(() => {
                hideProgress();
                showSuccess();
            }, 1500);
        };
        reader.readAsDataURL(file);
    }

    function showProgress() {
        const progressContainer = document.getElementById('progress-container');
        const progressBar = document.getElementById('progress-bar');
        const progressText = document.getElementById('progress-text');

        progressContainer.classList.remove('hidden');

        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.random() * 30;
            if (progress > 100) progress = 100;

            progressBar.style.width = progress + '%';
            progressText.textContent = Math.round(progress) + '%';

            if (progress >= 100) {
                clearInterval(interval);
            }
        }, 100);
    }

    function hideProgress() {
        document.getElementById('progress-container').classList.add('hidden');
    }

    function showSuccess() {
        const successMessage = document.getElementById('success-message');
        successMessage.classList.remove('hidden');
        successMessage.classList.add('fade-in');

        setTimeout(() => {
            successMessage.classList.add('hidden');
        }, 3000);
    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.5.2/flowbite.min.js"></script>