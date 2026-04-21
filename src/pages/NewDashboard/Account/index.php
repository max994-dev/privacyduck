<div class="grid grid-cols-1 xl:grid-cols-12 gap-[20px]">
    <div class="xl:col-span-4 bg-[#FEFEFE] border border-[#F6F6F6] rounded-[30px] p-[20px] sm:p-[24px]">
        <h1 class="text-[22px] text-[#010205] font-semibold">Original info</h1>
        <p class="mt-[6px] text-[13px] text-[#9B9B9C]">Keep your primary details and face image up to date.</p>

        <form id="nda-main-form" class="mt-[16px]">
            <div class="space-y-3">
                <div >
                    <label class="text-[13px] font-medium text-[#4B4B4E]">Face image</label>
                    <label for="nda-face-upload" class="mt-1 block border border-dashed border-[#D9D9D9] rounded-[12px] p-3 cursor-pointer hover:bg-[#FAFAFA] transition">
                        <div id="nda-face-preview" class="min-h-[120px] flex items-center justify-center text-[#9B9B9C] text-[13px]">
                            Upload image
                        </div>
                    </label>
                    <input id="nda-face-upload" type="file" accept="image/*" class="hidden" />
                </div>
                <div class="mt-4">
                    <label for="nda-firstname" class="text-[13px] font-medium text-[#4B4B4E]">First name *</label>
                    <input id="nda-firstname" type="text" class="mt-1 w-full h-[42px] rounded-[8px] border border-[#00000040] px-3" />
                </div>
                <div>
                    <label for="nda-lastname" class="text-[13px] font-medium text-[#4B4B4E]">Last name *</label>
                    <input id="nda-lastname" type="text" class="mt-1 w-full h-[42px] rounded-[8px] border border-[#00000040] px-3" />
                </div>
                <div>
                    <label for="nda-main-phone" class="text-[13px] font-medium text-[#4B4B4E]">Phone *</label>
                    <input id="nda-main-phone" type="text" class="mt-1 w-full h-[42px] rounded-[8px] border border-[#00000040] px-3" />
                </div>
                <div>
                    <label for="nda-main-city" class="text-[13px] font-medium text-[#4B4B4E]">City</label>
                    <input id="nda-main-city" type="text" class="mt-1 w-full h-[42px] rounded-[8px] border border-[#00000040] px-3" />
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="nda-main-zip" class="text-[13px] font-medium text-[#4B4B4E]">Zip</label>
                        <input id="nda-main-zip" type="text" class="mt-1 w-full h-[42px] rounded-[8px] border border-[#00000040] px-3" />
                    </div>
                    <div>
                        <label for="nda-main-state" class="text-[13px] font-medium text-[#4B4B4E]">State</label>
                        <input id="nda-main-state" type="text" class="mt-1 w-full h-[42px] rounded-[8px] border border-[#00000040] px-3" />
                    </div>
                </div>
                <div>
                    <label for="nda-main-address" class="text-[13px] font-medium text-[#4B4B4E]">Address</label>
                    <input id="nda-main-address" type="text" class="mt-1 w-full h-[42px] rounded-[8px] border border-[#00000040] px-3" />
                </div>
                <div>
                    <label for="nda-main-email" class="text-[13px] font-medium text-[#4B4B4E]">Email</label>
                    <input id="nda-main-email" type="email" class="mt-1 w-full h-[42px] rounded-[8px] border border-[#00000040] px-3 bg-[#FAFAFA]" readonly />
                </div>
            </div>

            

            <div class="mt-4 flex justify-end">
                <button id="nda-main-save" type="submit"
                    class="h-[42px] px-5 rounded-full bg-gradient-to-r from-[#77B248] to-[#24A556] text-white text-[14px] font-semibold">
                    Save
                </button>
            </div>
        </form>
    </div>

    <div class="xl:col-span-8 bg-[#FEFEFE] border border-[#F6F6F6] rounded-[30px] p-[20px] sm:p-[24px]">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-[22px] text-[#010205] font-semibold">Additional info</h2>
                <p class="mt-[6px] text-[13px] text-[#9B9B9C]">Add multiple contact records for better removal coverage.</p>
            </div>
            <button id="nda-open-add"
                class="h-[44px] px-[16px] rounded-full bg-gradient-to-r from-[#77B248] to-[#24A556] text-white text-[14px] font-semibold inline-flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-white/20">
                    <svg viewBox="0 0 24 24" class="w-3.5 h-3.5" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </span>
                Add
            </button>
        </div>

        <div class="mt-[18px] overflow-x-auto">
            <table class="min-w-full text-left border border-[#ECECEC] rounded-[14px] overflow-hidden">
                <thead class="bg-[#F8FAF8] text-[#4B4B4E] text-[13px]">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Phone</th>
                        <th class="px-4 py-3 font-semibold">City</th>
                        <th class="px-4 py-3 font-semibold">Zip</th>
                        <th class="px-4 py-3 font-semibold">State</th>
                        <th class="px-4 py-3 font-semibold">Address</th>
                        <th class="px-4 py-3 font-semibold">Email</th>
                    </tr>
                </thead>
                <tbody id="nda-table-body" class="text-[14px] text-[#010205] bg-white">
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-[#9B9B9C]">Loading...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="nda-modal" class="fixed inset-0 bg-black/40 z-[1200] hidden items-center justify-center px-4">
    <div class="w-full max-w-[620px] bg-white rounded-[20px] border border-[#ECECEC] p-[20px] sm:p-[24px]">
        <div class="flex items-center justify-between">
            <h2 class="text-[20px] font-semibold text-[#010205]">Add contact info</h2>
            <button id="nda-close-modal" class="text-[#9B9B9C] hover:text-[#010205] text-[22px] leading-none">&times;</button>
        </div>

        <div id="nda-modal-error" class="hidden mt-3 rounded-md border border-red-200 bg-red-50 px-3 py-2 text-[13px] text-red-700"></div>

        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="nda-phone" class="text-[13px] font-medium text-[#4B4B4E]">Phone *</label>
                <input id="nda-phone" type="text" class="mt-1 w-full h-[44px] rounded-[8px] border border-[#00000040] px-3" placeholder="+1 123-456-7890" />
            </div>
            <div>
                <label for="nda-city" class="text-[13px] font-medium text-[#4B4B4E]">City</label>
                <input id="nda-city" type="text" class="mt-1 w-full h-[44px] rounded-[8px] border border-[#00000040] px-3" placeholder="New York" />
            </div>
            <div>
                <label for="nda-zip" class="text-[13px] font-medium text-[#4B4B4E]">Zip</label>
                <input id="nda-zip" type="text" class="mt-1 w-full h-[44px] rounded-[8px] border border-[#00000040] px-3" placeholder="10001" />
            </div>
            <div>
                <label for="nda-state" class="text-[13px] font-medium text-[#4B4B4E]">State</label>
                <input id="nda-state" type="text" class="mt-1 w-full h-[44px] rounded-[8px] border border-[#00000040] px-3" placeholder="NY" />
            </div>
            <div class="sm:col-span-2">
                <label for="nda-address" class="text-[13px] font-medium text-[#4B4B4E]">Address</label>
                <input id="nda-address" type="text" class="mt-1 w-full h-[44px] rounded-[8px] border border-[#00000040] px-3" placeholder="123 Main St" />
            </div>
            <div class="sm:col-span-2">
                <label for="nda-email" class="text-[13px] font-medium text-[#4B4B4E]">Email</label>
                <input id="nda-email" type="email" class="mt-1 w-full h-[44px] rounded-[8px] border border-[#00000040] px-3 bg-[#FAFAFA]" readonly />
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button id="nda-cancel" class="h-[42px] px-4 rounded-full border border-[#D7D7D7] text-[#4B4B4E] text-[14px]">Cancel</button>
            <button id="nda-save" class="h-[42px] px-5 rounded-full bg-gradient-to-r from-[#77B248] to-[#24A556] text-white text-[14px] font-semibold">Save</button>
        </div>
    </div>
</div>

<script>
(function() {
    var userEmail = '';
    var currentUser = {};
    var tableBody = document.getElementById('nda-table-body');
    var modal = document.getElementById('nda-modal');
    var faceInput = document.getElementById('nda-face-upload');
    var facePreview = document.getElementById('nda-face-preview');
    var modalErr = document.getElementById('nda-modal-error');
    var saveBtn = document.getElementById('nda-save');
    var mainSaveBtn = document.getElementById('nda-main-save');
    var openBtn = document.getElementById('nda-open-add');

    function esc(v) {
        return String(v || '').replace(/[&<>"']/g, function(c) {
            return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[c];
        });
    }

    function showRows(contacts) {
        if (!Array.isArray(contacts) || contacts.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="6" class="px-4 py-6 text-center text-[#9B9B9C]">No records yet. Click Add.</td></tr>';
            return;
        }
        tableBody.innerHTML = contacts.map(function(c) {
            return '<tr class="border-t border-[#F1F1F1]">' +
                '<td class="px-4 py-3">' + esc(c.phone) + '</td>' +
                '<td class="px-4 py-3">' + esc(c.city) + '</td>' +
                '<td class="px-4 py-3">' + esc(c.zip) + '</td>' +
                '<td class="px-4 py-3">' + esc(c.state) + '</td>' +
                '<td class="px-4 py-3">' + esc(c.address) + '</td>' +
                '<td class="px-4 py-3">' + esc(userEmail) + '</td>' +
                '</tr>';
        }).join('');
    }

    function setFacePreview(url) {
        if (!url) {
            facePreview.innerHTML = 'Upload image';
            return;
        }
        facePreview.innerHTML = '<img src="' + esc(url) + '" alt="Face image" class="max-h-[170px] rounded-[10px] w-auto mx-auto" />';
    }

    function normalizeContacts(user) {
        var contacts = user.contacts;
        if (typeof contacts === 'string' && contacts !== '') {
            try { contacts = JSON.parse(contacts); } catch (e) { contacts = []; }
        }
        if (!Array.isArray(contacts) || contacts.length === 0) {
            if (user.phone || user.city || user.zip || user.state || user.address) {
                contacts = [{
                    phone: user.phone || '',
                    city: user.city || '',
                    zip: user.zip || '',
                    state: user.state || '',
                    address: user.address || ''
                }];
            } else {
                contacts = [];
            }
        }
        return contacts;
    }

    function loadData() {
        tableBody.innerHTML = '<tr><td colspan="6" class="px-4 py-6 text-center text-[#9B9B9C]">Loading...</td></tr>';
        $.get('/get_user_info', function(res) {
            var user = (Array.isArray(res) && res[0]) ? res[0] : {};
            currentUser = user;
            userEmail = user.email || '';
            document.getElementById('nda-email').value = userEmail;
            document.getElementById('nda-main-email').value = userEmail;
            document.getElementById('nda-firstname').value = user.firstname || '';
            document.getElementById('nda-lastname').value = user.lastname || '';
            document.getElementById('nda-main-phone').value = user.phone || '';
            document.getElementById('nda-main-city').value = user.city || '';
            document.getElementById('nda-main-zip').value = user.zip || '';
            document.getElementById('nda-main-state').value = user.state || '';
            document.getElementById('nda-main-address').value = user.address || '';
            var faceUrl = user.url ? ('/assets/uploads/specialinfo/' + user.url) : '';
            setFacePreview(faceUrl);
            showRows(normalizeContacts(user));
        }, 'json').fail(function() {
            tableBody.innerHTML = '<tr><td colspan="6" class="px-4 py-6 text-center text-red-600">Failed to load data.</td></tr>';
        });
    }

    function openModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modalErr.classList.add('hidden');
        modalErr.textContent = '';
        document.getElementById('nda-phone').focus();
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        ['nda-phone', 'nda-city', 'nda-zip', 'nda-state', 'nda-address'].forEach(function(id) {
            document.getElementById(id).value = '';
        });
    }

    openBtn.addEventListener('click', openModal);
    document.getElementById('nda-close-modal').addEventListener('click', closeModal);
    document.getElementById('nda-cancel').addEventListener('click', closeModal);
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });

    saveBtn.addEventListener('click', function() {
        modalErr.classList.add('hidden');
        var payload = {
            phone: (document.getElementById('nda-phone').value || '').trim(),
            city: (document.getElementById('nda-city').value || '').trim(),
            zip: (document.getElementById('nda-zip').value || '').trim(),
            state: (document.getElementById('nda-state').value || '').trim(),
            address: (document.getElementById('nda-address').value || '').trim()
        };
        if (!payload.phone) {
            modalErr.textContent = 'Phone is required.';
            modalErr.classList.remove('hidden');
            return;
        }

        var oldText = saveBtn.textContent;
        saveBtn.disabled = true;
        saveBtn.textContent = 'Saving...';
        $.post('/add_user_address', { contacts: payload }, function(res) {
            if (res.error) {
                modalErr.textContent = res.error;
                modalErr.classList.remove('hidden');
                return;
            }
            closeModal();
            loadData();
            if (typeof toastr !== 'undefined') toastr.success('Contact added.');
        }, 'json').fail(function(xhr) {
            var msg = 'Request failed.';
            try {
                var j = JSON.parse(xhr.responseText);
                if (j && j.error) msg = j.error;
            } catch (e) {}
            modalErr.textContent = msg;
            modalErr.classList.remove('hidden');
        }).always(function() {
            saveBtn.disabled = false;
            saveBtn.textContent = oldText;
        });
    });

    faceInput.addEventListener('change', function(e) {
        var file = e.target.files && e.target.files[0];
        if (!file) return;
        var reader = new FileReader();
        reader.onload = function(ev) {
            setFacePreview(ev.target.result);
        };
        reader.readAsDataURL(file);
    });

    document.getElementById('nda-main-form').addEventListener('submit', function(e) {
        e.preventDefault();
        var first = (document.getElementById('nda-firstname').value || '').trim();
        var last = (document.getElementById('nda-lastname').value || '').trim();
        var contact = {
            phone: (document.getElementById('nda-main-phone').value || '').trim(),
            city: (document.getElementById('nda-main-city').value || '').trim(),
            zip: (document.getElementById('nda-main-zip').value || '').trim(),
            state: (document.getElementById('nda-main-state').value || '').trim(),
            address: (document.getElementById('nda-main-address').value || '').trim()
        };
        if (!first || !last) {
            if (typeof toastr !== 'undefined') toastr.error('First name and last name are required.');
            return;
        }
        if (!contact.phone) {
            if (typeof toastr !== 'undefined') toastr.error('Phone is required.');
            return;
        }
        var oldText = mainSaveBtn.textContent;
        mainSaveBtn.disabled = true;
        mainSaveBtn.textContent = 'Saving...';
        var formData = new FormData();
        formData.append('first_name', first);
        formData.append('last_name', last);
        formData.append('email', userEmail);
        formData.append('contacts', JSON.stringify([contact]));
        if (faceInput.files && faceInput.files[0]) {
            formData.append('file', faceInput.files[0]);
        }
        $.ajax({
            url: '/update_user_info',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false
        }).done(function(res) {
            if (res && res.error) {
                if (typeof toastr !== 'undefined') toastr.error(res.error);
                return;
            }
            if (typeof toastr !== 'undefined') toastr.success('Account info updated.');
            loadData();
        }).fail(function(xhr) {
            var msg = 'Request failed.';
            try {
                var j = JSON.parse(xhr.responseText);
                if (j && j.error) msg = j.error;
            } catch (err) {}
            if (typeof toastr !== 'undefined') toastr.error(msg);
        }).always(function() {
            mainSaveBtn.disabled = false;
            mainSaveBtn.textContent = oldText;
        });
    });

    loadData();
})();
</script>
