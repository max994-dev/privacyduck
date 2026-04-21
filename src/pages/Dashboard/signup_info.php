<?php
$conn = getDBConnection();
//google scan start
$main_stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$main_stmt->bind_param("i", $_SESSION["user_id"]);
$main_stmt->execute();
$main_result = $main_stmt->get_result();
$user = $main_result->fetch_assoc();
$user_contacts = json_decode($user["contacts"], true);
if (!empty($_SESSION["needs_profile_info"])) {
    $init_show_modal = true;
} else if ($user && $user["firstname"] && $user["lastname"] && $user["city"] && $user["state"] && $user["phone"] && $user["zip"] && $user["address"] && $user["email"] && is_array($user_contacts) && count($user_contacts) > 1) {
    $init_show_modal = false;
} else {
    $init_show_modal = true;
}
?>
<div id="signup_info_modal"
    class="hidden fixed inset-0 bg-[#00000040] px-[16px] border border-[#F6F6F63A] backdrop-blur-md flex items-center justify-center z-[2000] animate-[opacity_0.5s_ease-out]">
    <div class="relative bg-white rounded-[30px] shadow-[0px_4px_4px_#0206091A] w-full max-w-[774px] max-h-[95vh] overflow-y-auto">
        <button onclick="signup_info_modal_close()" id="closeModal"
            class="absolute top-[16px] right-[30px] font-bold text-gray-500 hover:text-red-500">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M19 5L5 19M5 5L19 19" stroke="#010205" stroke-width="1.5"
                    stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>

        <div class="flex flex-col xl:flex-row xl:items-stretch">
            <!-- Form Section -->
            <div class="px-[16px] sm:px-[30px] py-[56px] w-full ">
                <h1 class="font-bold text-[24px] sm:text-[32px] text-[#010205]">Add Your Info</h1>
                <div class="flex items-center gap-[8px] mt-[10px] rounded-[15px] bg-[#E8FCE7] border border-[#F6F6F63B] px-[16px] py-[13px] shadow-[0px_4px_4px_0px_#F6F6F626]">
                    <i class="fa-solid fa-circle-exclamation text-[#24A556]"></i>
                    <h2 class="font-medium text-[14px] leading-[120%] text-[#7E7E7E]">
                        The more information you provide, the more PrivacyDuck can find and remove for you.
                    </h2>
                </div>
                <form onsubmit="updateinfo_signup_info()">
                    <div class="mt-[10px] grid grid-cols-1 md:grid-cols-2 md:gap-[26px]">
                        <div class="grid grid-cols-1">
                            <?php
                            $datas = [
                                ["name" => "First Name *", "type" => "text", "id" => "signup_info_firstname", "placeholder" => "John", "size" => "full"],
                                ["name" => "Last Name *", "type" => "text", "id" => "signup_info_lastname", "placeholder" => "Doe", "size" => "full"],
                            ];
                            function searchIndex1($array, $value)
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
                                    <div class="flex flex-col mt-[<?php echo in_array(searchIndex1($datas, $data), [0]) ? "0px" : "16px"; ?>] sm:mt-[<?php echo in_array(searchIndex($datas, $data), [0]) ? "0px" : "16px"; ?>]">
                                        <label for="<?= $data["id"]; ?>" class="font-medium text-[14px] leading-[20px] text-[#010205]"><?= $data["name"]; ?></label>
                                        <input type="<?= $data["type"]; ?>" id="<?= $data["id"]; ?>" placeholder="<?= $data["placeholder"]; ?>"
                                            class="mt-[6px] h-[48px] px-[14px] rounded-[8px] border border-[#00000040]">
                                        <div class="hidden mt-[6px] text-[14px] leading-[20px]" id="family_invalid_<?= $data["id"]; ?>"></div>
                                    </div>
                            <?php }
                            } ?>
                        </div>
                        <div>
                            <label for="signup_info_face-upload" class="signup_info_upload-area flex flex-col items-center justify-center w-full h-48 border-2 border-dashed rounded-xl cursor-pointer bg-gradient-to-br from-gray-50 to-gray-100 hover:from-purple-50 hover:to-pink-50 border-gray-300 hover:border-purple-400 transition-all duration-300">

                                <div id="signup_info_preview" class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <div class="relative mb-4">
                                        <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <p class="mb-2 text-base font-medium text-gray-700">
                                        <span class="text-purple-600 font-semibold">Face upload</span>
                                    </p>
                                    <p class="text-sm text-gray-500 text-center px-2">
                                        Upload a photo of your face to help remove it from face search scanning websites.
                                    </p>
                                </div>
                                <input id="signup_info_face-upload" type="file" accept="image/*" class="hidden" onchange="s_previewImage(event)" />
                            </label>
                            <div id="signup_info_progress-container" class="hidden mt-4">
                                <div class="flex justify-between text-sm text-gray-600 mb-1">
                                    <span>Uploading...</span>
                                    <span id="signup_info_progress-text">0%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div id="signup_info_progress-bar" class="bg-gradient-to-r from-purple-500 to-pink-500 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                                </div>
                            </div>
                            <div id="signup_info_success-message" class="hidden mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
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
                                    ["name" => "Phone Number *", "type" => "text", "id" => "signup_info_phone", "placeholder" => "5551234567", "size" => "full"],
                                    ["name" => "City *", "type" => "text", "id" => "signup_info_city", "placeholder" => "New York", "size" => "full"],
                                ];
                                foreach ($datas as $data) {
                                    if ($data["size"] == "full") {
                                ?>
                                        <div class="flex flex-col mt-[<?php echo in_array(searchIndex1($datas, $data), [0]) ? "0px" : "16px"; ?>] sm:mt-[<?php echo in_array(searchIndex($datas, $data), [0]) ? "0px" : "16px"; ?>]">
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
                                    ["name" => "Zip *", "type" => "text", "id" => "signup_info_zip", "placeholder" => "12345", "size" => "half"],
                                    ["name" => "State *", "type" => "text", "id" => "signup_info_state", "placeholder" => "New York", "size" => "half"],
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
                                    ["name" => "Address *", "type" => "text", "id" => "signup_info_address", "placeholder" => "123 Main St", "size" => "full"],
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
                            <div id="signup_info_phones" class="flex flex-col gap-[8px] items-stretch">
                            </div>
                            <div class="flex gap-[4px] items-center justify-between overflow-y-auto">
                                <button type="button" onclick="signup_info_addAddress()" id="signup_info_add_address" class="w-full h-[44px] flex justify-center items-center rounded-full bg-gradient-to-r 
                        from-[#77B248] to-[#24A556] font-bold text-[16px] leading-[140%] text-white">
                                    Add
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1">
                        <?php
                        $datas = [
                            ["name" => "Email *", "type" => "email", "id" => "signup_info_email", "placeholder" => "Enter Email", "size" => "full"],
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
                        <a class="underline cursor-pointer font-semibold text-[14px] leading-[130%] text-[#010205]" onclick="signup_info_modal_close()">Skip</a>
                        <button type="submit" id="signup_info_btn" class="w-[111px] h-[44px] flex justify-center items-center rounded-full bg-gradient-to-r from-[#77B248] to-[#24A556] font-bold text-[16px] leading-[140%] text-white">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    const signup_info_loadingHtml = "<img src='/assets/image/desktop/loading1.webp' class='w-6 h-6 flex mr-2'> <span class='font-semibold text-[12px] leading-[130%] tracking-[-0.02em]'>Saving...</span>";

    function signup_info_modal_close() {
        document.getElementById("signup_info_modal").classList.add("hidden");
    }

    function signup_info_getAddressPerson(res, index) {
        const ids = [{
                id: "signup_info_phone",
                item: "phone"
            },
            {
                id: "signup_info_city",
                item: "city"
            },
            {
                id: "signup_info_zip",
                item: "zip"
            },
            {
                id: "signup_info_state",
                item: "state"
            },
            {
                id: "signup_info_address",
                item: "address"
            },
        ]
        ids.forEach((v) => {
            document.getElementById(v.id).value = res[index][v.item] || "";
        })
    }

    function signup_info_getcontacts(contacts) {
        // Only treat entries with address/phone fields as "contact entries".
        // (Marketing opt-in is also stored in `contacts`, but should not create empty UI rows.)
        contacts = contacts.filter(v => ["phone", "city", "zip", "state", "address"].some(k => v[k]))
        // Now use it safely
        document.getElementById("signup_info_phones").innerHTML = "";
        contacts.length > 0 ? contacts.map((v, index) => {
            document.getElementById("signup_info_phones").innerHTML += `
            <div class="flex items-center gap-[8px]">
                <div id="phone_${index}" onclick="signup_info_getAddressPerson(window.contacts, ${index})"
                    class="max-w-[80%] cursor-pointer rounded-full bg-[#E6FDE8] px-[16px] py-[8px]">
                    <h1 class="text-[16px] font-medium text-[#010205] overflow-hidden whitespace-nowrap">${v.phone}</h1>
                </div>
                <button type="button" onclick="signup_info_deleteAddress(${index})" id="delete_address">
                    <i class="fa-solid fa-trash text-[12px] text-[#C00000]"></i>
                </button>
            </div>
        `;
        }) : "";
        const ids = [{
                id: "signup_info_phone",
                item: "phone"
            },
            {
                id: "signup_info_city",
                item: "city"
            },
            {
                id: "signup_info_zip",
                item: "zip"
            },
            {
                id: "signup_info_state",
                item: "state"
            },
            {
                id: "signup_info_address",
                item: "address"
            }
        ]
        if (contacts.length > 0) {
            ids.forEach((v) => {
                document.getElementById(v.id).value = contacts[0][v.item] || "";
            })
        } else {
            ids.forEach((v) => {
                document.getElementById(v.id).value = "";
            })
        }
        window.contacts = contacts; // store globally if needed
    }

    function init_display() {
        if (<?php echo $init_show_modal ? "false" : "true"; ?>) {
            document.getElementById("signup_info_modal").classList.add("hidden");
        } else {
            $.get("/get_user_info_by_id", {
                id: "<?php echo $_SESSION['user_id']; ?>"
            }, (res) => {
                const ids = [{
                        id: "signup_info_firstname",
                        item: "firstname"
                    },
                    {
                        id: "signup_info_lastname",
                        item: "lastname"
                    },
                    {
                        id: "signup_info_phone",
                        item: "phone"
                    },
                    {
                        id: "signup_info_city",
                        item: "city"
                    },
                    {
                        id: "signup_info_zip",
                        item: "zip"
                    },
                    {
                        id: "signup_info_state",
                        item: "state"
                    },
                    {
                        id: "signup_info_address",
                        item: "address"
                    },
                    {
                        id: "signup_info_email",
                        item: "email"
                    },
                ]
                ids.forEach((v) => {
                    document.getElementById(v.id).value = res[0][v.item] || "";
                })
                const init_signup_info_previewDiv = document.getElementById('signup_info_preview');
                if (res[0]["url"] == "") {
                    init_signup_info_previewDiv.innerHTML = `
                <div class="relative mb-4">
                                <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <p class="mb-2 text-base font-medium text-gray-700">
                                <span class="text-purple-600 font-semibold">Face upload</span>
                            </p>
                            <p class="text-sm text-gray-500 text-center px-2">
                                Upload a photo of your face to help remove it from face search scanning websites.
                            </p>
                `;
                } else {
                    init_signup_info_previewDiv.innerHTML = `
                <div class="relative group">
                    <img src="https://privacyduck.com/assets/uploads/specialinfo/${res[0]["url"]}" alt="Preview" class="max-h-40 w-full rounded-xl shadow-lg border-2 border-white">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-xl transition-all duration-300 flex items-center justify-center">
                    <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    </div>
                </div>
                <p class="mt-3 text-sm text-gray-600 font-medium">Click to change image</p>
                `;
                }
                document.getElementById("signup_info_modal").classList.remove("hidden");
                signup_info_getcontacts(res[0]["contacts"] || []);
            })
        }
    }


    function updateinfo_signup_info() {
        // Prevent default form submission
        event.preventDefault();
        imagefile = document.getElementById("signup_info_face-upload").files[0];
        document.getElementById("signup_info_btn").innerHTML = window.loadingHtml;
        const formData = new FormData();
        formData.append("first_name", $("#signup_info_firstname").val().trim());
        formData.append("last_name", $("#signup_info_lastname").val().trim());
        formData.append("email", $("#signup_info_email").val().trim());
        formData.append("contacts", JSON.stringify(window.contacts));
        formData.append("file", imagefile);
        $.ajax({
            url: "/update_user_info",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                if (res.error) {
                    toastr.error(res.error);
                    document.getElementById("signup_info_btn").innerHTML = "Save";
                } else {
                    toastr.success("User info updated successfully");
                    document.getElementById("signup_info_btn").innerHTML = "Save";
                    signup_info_modal_close();
                }
            }
        });
    }

    function signup_info_addAddress() {
        event.preventDefault();
        document.getElementById("signup_info_add_address").innerHTML = window.loadingHtml;
        $.post("/add_user_address", {
            contacts: {
                city: $("#signup_info_city").val().trim(),
                state: $("#signup_info_state").val().trim(),
                phone: $("#signup_info_phone").val().trim(),
                zip: $("#signup_info_zip").val().trim(),
                address: $("#signup_info_address").val().trim(),
            }
        }, (res) => {
            if (res.error) {
                toastr.error(res.error);
                document.getElementById("signup_info_add_address").innerHTML = "Add";
            } else {
                toastr.success("Address added successfully");
                document.getElementById("signup_info_add_address").innerHTML = "Add";
                signup_info_getcontacts(res["contacts"]);
            }
        });

        return false; // prevent actual form submission
    }

    function signup_info_deleteAddress(index) {
        event.preventDefault();
        $.post("/delete_user_address", {
            pos: index
        }, (res) => {
            signup_info_getcontacts(res["contacts"]);
        });

        return false; // prevent actual form submission
    }

    let signup_info_uploadArea = document.querySelector('.signup_info_upload-area');

    // Drag and drop functionality
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        signup_info_uploadArea.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        signup_info_uploadArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        signup_info_uploadArea.addEventListener(eventName, unhighlight, false);
    });

    signup_info_uploadArea.addEventListener('drop', handleDrop, false);

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight(e) {
        signup_info_uploadArea.classList.add('dragover');
    }

    function unhighlight(e) {
        signup_info_uploadArea.classList.remove('dragover');
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            document.getElementById('signup_info_face-upload').files = files;
            s_previewImage({
                target: {
                    files: files
                }
            });
        }
    }

    function s_previewImage(event) {
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
        s_showProgress();

        const reader = new FileReader();
        reader.onload = function(e) {
            const previewDiv = document.getElementById('signup_info_preview');
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
                s_hideProgress();
                s_showSuccess();
            }, 1500);
        };
        reader.readAsDataURL(file);
    }

    function s_showProgress() {
        const progressContainer = document.getElementById('signup_info_progress-container');
        const progressBar = document.getElementById('signup_info_progress-bar');
        const progressText = document.getElementById('signup_info_progress-text');

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

    function s_hideProgress() {
        document.getElementById('signup_info_progress-container').classList.add('hidden');
    }

    function s_showSuccess() {
        const successMessage = document.getElementById('signup_info_success-message');
        successMessage.classList.remove('hidden');
        successMessage.classList.add('fade-in');

        setTimeout(() => {
            successMessage.classList.add('hidden');
        }, 3000);
    }
    init_display();
</script>
