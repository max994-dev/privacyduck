<div class="bg-[#FAFAFA] min-h-screen pt-[140px] pb-[100px] text-[#010205]">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">User Information Form</h1>
                <p class="text-gray-600">Please fill out all required fields to complete your profile.</p>
            </div>

            <form id="userForm" class="space-y-8" enctype="multipart/form-data">
                <!-- Personal Information Section -->
                <div class="border-b border-gray-200 pb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-[#24A556]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                        Personal Information
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="firstName" class="block text-sm font-medium text-[#010205] mb-2">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="firstName" name="firstName" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200 hover:border-gray-400"
                                placeholder="Enter your first name">
                        </div>
                        <div>
                            <label for="lastName" class="block text-sm font-medium text-[#010205] mb-2">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="lastName" name="lastName" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200 hover:border-gray-400"
                                placeholder="Enter your last name">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-[#010205] mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200 hover:border-gray-400"
                                placeholder="Enter your email address">
                        </div>
                        <div>
                            <label for="dateOfBirth" class="block text-sm font-medium text-[#010205] mb-2">
                                Date of Birth
                            </label>
                            <input type="date" id="dateOfBirth" name="dateOfBirth"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200 hover:border-gray-400">
                        </div>
                        <div class="md:col-span-2">
                            <label for="profilePicture" class="block text-sm font-medium text-gray-700 mb-2">
                            Picture of Yourself
                            </label>
                            <div class="flex items-center space-x-6">
                                <div class="shrink-0">
                                    <img id="profilePreview" class="h-20 w-20 object-cover rounded-full border-2 border-gray-300"
                                        src="data:image/svg+xml,%3csvg width='100' height='100' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100' height='100' fill='%23f3f4f6'/%3e%3ctext x='50%25' y='50%25' font-size='14' text-anchor='middle' dy='.3em' fill='%236b7280'%3eNo Image%3c/text%3e%3c/svg%3e"
                                        alt="Profile preview">
                                </div>
                                <div class="flex-1">
                                    <input type="file" id="profilePicture" name="profilePicture" accept="image/*"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-[#24A556] file:text-white hover:file:bg-green-400 file:transition-colors file:duration-200">
                                    <p class="mt-2 text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                    <button type="button" id="removeImage" class="mt-2 text-sm text-red-600 hover:text-red-800 hidden">
                                        Remove image
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Phone Numbers Section -->
                <div class="border-b border-gray-200 pb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-[#24A556]" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                            </svg>
                            Phone Numbers
                        </h2>
                        <button type="button" id="addPhone"
                            class="bg-[#24A556] text-white px-4 py-2 rounded-lg hover:bg-green-400 transition-colors duration-200 flex items-center text-sm">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                            </svg>
                            Add Phone
                        </button>
                    </div>
                    <div id="phoneContainer" class="space-y-4">
                        <div class="phone-entry grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-gray-50 rounded-lg">
                            <div>
                                <label class="block text-sm font-medium text-[#010205] mb-2">Phone Type</label>
                                <select name="phoneType[]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                    <option value="mobile">Mobile</option>
                                    <option value="home">Home</option>
                                    <option value="work">Work</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[#010205] mb-2">Phone Number <span class="text-red-500">*</span></label>
                                <input type="tel" name="phoneNumber[]" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200 hover:border-gray-400"
                                    placeholder="(555) 123-4567">
                            </div>
                            <div class="flex items-end">
                                <button type="button" class="remove-phone w-full md:w-auto bg-red-500 text-white px-4 py-3 rounded-lg hover:bg-red-600 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Addresses Section -->
                <div class="border-b border-gray-200 pb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-[#24A556]" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            Addresses
                        </h2>
                        <button type="button" id="addAddress"
                            class="bg-[#24A556] text-white px-4 py-2 rounded-lg hover:bg-green-400 transition-colors duration-200 flex items-center text-sm">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                            </svg>
                            Add Address
                        </button>
                    </div>
                    <div id="addressContainer" class="space-y-6">
                        <div class="address-entry p-6 bg-gray-50 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-[#010205] mb-2">Address Type</label>
                                    <select name="addressType[]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                        <option value="home">Home</option>
                                        <option value="work">Work</option>
                                        <option value="billing">Billing</option>
                                        <option value="shipping">Shipping</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="flex items-end">
                                    <button type="button" class="remove-address w-full md:w-auto bg-red-500 text-white px-4 py-3 rounded-lg hover:bg-red-600 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                        Remove Address
                                    </button>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-[#010205] mb-2">Street Address <span class="text-red-500">*</span></label>
                                    <input type="text" name="streetAddress[]" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200 hover:border-gray-400"
                                        placeholder="123 Main Street">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-[#010205] mb-2">City <span class="text-red-500">*</span></label>
                                        <input type="text" name="city[]" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200 hover:border-gray-400"
                                            placeholder="New York">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-[#010205] mb-2">State/Province <span class="text-red-500">*</span></label>
                                        <input type="text" name="state[]" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200 hover:border-gray-400"
                                            placeholder="NY">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-[#010205] mb-2">ZIP/Postal Code <span class="text-red-500">*</span></label>
                                        <input type="text" name="zipCode[]" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200 hover:border-gray-400"
                                            placeholder="10001">
                                    </div>
                                </div>
                                <!-- <div>
                                    <label class="block text-sm font-medium text-[#010205] mb-2">Country <span class="text-red-500">*</span></label>
                                    <select name="country[]" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                        <option value="">Select Country</option>
                                        <option value="US">United States</option>
                                        <option value="CA">Canada</option>
                                        <option value="UK">United Kingdom</option>
                                        <option value="AU">Australia</option>
                                        <option value="DE">Germany</option>
                                        <option value="FR">France</option>
                                        <option value="JP">Japan</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Section -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6">
                    <button type="submit" id="submit"
                        class="flex-1 flex items-center justify-center bg-[#24A556] text-white py-3 px-6 rounded-lg hover:bg-green-400 transition-colors duration-200 font-medium text-lg">
                        Submit Information
                    </button>
                    <button type="reset"
                        class="flex-1 bg-gray-500 text-white py-3 px-6 rounded-lg hover:bg-gray-600 transition-colors duration-200 font-medium text-lg">
                        Reset Form
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let phoneCount = 1;
    const maxPhones = 5;

    document.getElementById('addPhone').addEventListener('click', function() {
        if (phoneCount >= maxPhones) {
            alert('Maximum 5 phone numbers allowed');
            return;
        }

        const phoneContainer = document.getElementById('phoneContainer');
        const phoneEntry = document.createElement('div');
        phoneEntry.className = 'phone-entry grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-gray-50 rounded-lg';
        phoneEntry.innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Type</label>
                    <select name="phoneType[]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="mobile">Mobile</option>
                        <option value="home">Home</option>
                        <option value="work">Work</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number <span class="text-red-500">*</span></label>
                    <input type="tel" name="phoneNumber[]" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200 hover:border-gray-400"
                        placeholder="(555) 123-4567">
                </div>
                <div class="flex items-end">
                    <button type="button" class="remove-phone w-full md:w-auto bg-red-500 text-white px-4 py-3 rounded-lg hover:bg-red-600 transition-colors duration-200">
                        Remove
                    </button>
                </div>
            `;
        phoneContainer.appendChild(phoneEntry);
        phoneCount++;
        updateRemoveButtons();
    });

    // Address management
    let addressCount = 1;
    const maxAddresses = 3;

    document.getElementById('addAddress').addEventListener('click', function() {
        if (addressCount >= maxAddresses) {
            alert('Maximum 3 addresses allowed');
            return;
        }

        const addressContainer = document.getElementById('addressContainer');
        const addressEntry = document.createElement('div');
        addressEntry.className = 'address-entry p-6 bg-gray-50 rounded-lg';
        addressEntry.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address Type</label>
                        <select name="addressType[]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="home">Home</option>
                            <option value="work">Work</option>
                            <option value="billing">Billing</option>
                            <option value="shipping">Shipping</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="button" class="remove-address w-full md:w-auto bg-red-500 text-white px-4 py-3 rounded-lg hover:bg-red-600 transition-colors duration-200">
                            Remove Address
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Street Address <span class="text-red-500">*</span></label>
                        <input type="text" name="streetAddress[]" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200 hover:border-gray-400"
                            placeholder="123 Main Street">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">City <span class="text-red-500">*</span></label>
                            <input type="text" name="city[]" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200 hover:border-gray-400"
                                placeholder="New York">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">State/Province <span class="text-red-500">*</span></label>
                            <input type="text" name="state[]" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200 hover:border-gray-400"
                                placeholder="NY">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ZIP/Postal Code <span class="text-red-500">*</span></label>
                            <input type="text" name="zipCode[]" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200 hover:border-gray-400"
                                placeholder="10001">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Country <span class="text-red-500">*</span></label>
                        <select name="country[]" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="">Select Country</option>
                            <option value="US">United States</option>
                            <option value="CA">Canada</option>
                            <option value="UK">United Kingdom</option>
                            <option value="AU">Australia</option>
                            <option value="DE">Germany</option>
                            <option value="FR">France</option>
                            <option value="JP">Japan</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
            `;
        addressContainer.appendChild(addressEntry);
        addressCount++;
        updateRemoveButtons();
    });

    // Remove phone/address functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-phone')) {
            e.target.closest('.phone-entry').remove();
            phoneCount--;
            updateRemoveButtons();
        }

        if (e.target.classList.contains('remove-address')) {
            e.target.closest('.address-entry').remove();
            addressCount--;
            updateRemoveButtons();
        }
    });

    function updateRemoveButtons() {
        const phoneRemoveButtons = document.querySelectorAll('.remove-phone');
        const addressRemoveButtons = document.querySelectorAll('.remove-address');

        phoneRemoveButtons.forEach(button => {
            button.disabled = phoneRemoveButtons.length <= 1;
        });

        addressRemoveButtons.forEach(button => {
            button.disabled = addressRemoveButtons.length <= 1;
        });
    }

    // Form submission
    document.getElementById('userForm').addEventListener('submit', function(e) {
        e.preventDefault();
        document.getElementById('submit').disabled = true;
        $("#submit").html(window.loadingHtml3);
        const formData = new FormData(this);
        const data = {};

        // Collect form data
        for (let [key, value] of formData.entries()) {
            if (data[key]) {
                if (Array.isArray(data[key])) {
                    data[key].push(value);
                } else {
                    data[key] = [data[key], value];
                }
            } else {
                data[key] = value;
            }
        }

        const {profilePicture, ...rest} = data;
        var file = new FormData();
        file.append('profilePicture', $('#profilePicture')[0].files[0]);
        file.append('email', data.email);
        $.ajax({
            url: '/api/image',  // your server-side upload handler
            type: 'POST',
            data: file,
            processData: false,  // prevent jQuery from processing the data
            contentType: false,  // prevent jQuery from setting content type
            success: function(response) {
                const {url} = response;
                $.post('/api/specialinfo', {
                    email: data.email,
                    data: JSON.stringify(rest),
                    url: url
                }, function(response) {
                    toastr.success("Special information submitted successfully!")
                    document.getElementById('submit').disabled = false;
                    $("#submit").html("Submit Information");
                });
            },
            error: function(xhr) {
            }
        });
    });

    // Initialize remove button states
    updateRemoveButtons();

    // Image upload functionality
    const profilePictureInput = document.getElementById('profilePicture');
    const profilePreview = document.getElementById('profilePreview');
    const removeImageBtn = document.getElementById('removeImage');
    const defaultImageSrc = "data:image/svg+xml,%3csvg width='100' height='100' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100' height='100' fill='%23f3f4f6'/%3e%3ctext x='50%25' y='50%25' font-size='14' text-anchor='middle' dy='.3em' fill='%236b7280'%3eNo Image%3c/text%3e%3c/svg%3e";

    profilePictureInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file size (10MB limit)
            if (file.size > 10 * 1024 * 1024) {
                alert('File size must be less than 10MB');
                e.target.value = '';
                return;
            }

            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert('Please select a valid image file');
                e.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                profilePreview.src = e.target.result;
                removeImageBtn.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    removeImageBtn.addEventListener('click', function() {
        profilePictureInput.value = '';
        profilePreview.src = defaultImageSrc;
        removeImageBtn.classList.add('hidden');
    });
</script>