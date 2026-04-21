<div class="mt-[32px] bg-[#FFFFFFE3] rounded-[30px] border border-[#F6F6F6]">
    <div class="px-[40px] py-[30px]">
        <h1 class="text-[#010205] text-[14px] font-bold tracking-[-0.01em]">1 member</h1>
        <div class="mt-[16px]" id="modern-editinfo-table">
        </div>
    </div>
</div>
<script>
    function userinfoTable() {
        document.getElementById("modern-editinfo-table").innerHTML = `<div class="flex py-[32px] items-center justify-center bg-white transition-opacity  ease-in-out">
            <img src="/assets/image/desktop/duck.svg" class="animate-bounce w-[50px] h-[50px]" />
        </div>
        `;
        $.get("/get_user_info", {}, (res) => {
            window.userinfo_tableData = res.map((item) => {
                return {
                    name: item.firstname + " " + item.lastname,
                    address: item.address,
                    phone: item.phone,
                    id: item.id
                }
            });
            if (window.userinfo_tableData.length > 0) {
                const table = new Tabulator("#modern-editinfo-table", {
                    data: window.userinfo_tableData,
                    layout: "fitDataStretch",

                    // pagination: "local", // ← enable local pagination
                    // paginationSize: 5, // ← show 5 rows per page
                    columns: [{
                            title: "Name",
                            field: "name",
                            headerSort: false,
                        },
                        {
                            title: "Address",
                            field: "address",
                            headerSort: false,
                            formatter: cell => cell.getValue() ?? "-"
                        },
                        {
                            title: "Phone numbers",
                            field: "phone",
                            headerSort: false,
                            formatter: cell => cell.getValue() ?? "-",
                        },
                        {
                            title: "Actions",
                            field: "id",
                            headerSort: false,
                            formatter: (cell) => `<h1 onclick="editUserinfo(${cell.getValue()})" class="info-edit-icon rounded-full px-[12px] py-[4.5px] w-fit flex items-center">&#9998; <span class="text-[10px]">Action</span></h1>`, // ✏️

                        }
                    ]
                });
            }
        })
    }

    function getAddressPerson(res, index) {
        const ids = [{
                id: "edit_phone",
                item: "phone"
            },
            {
                id: "edit_city",
                item: "city"
            },
            {
                id: "edit_zip",
                item: "zip"
            },
            {
                id: "edit_state",
                item: "state"
            },
            {
                id: "edit_address",
                item: "address"
            },
        ]
        ids.forEach((v) => {
            document.getElementById(v.id).value = res[index][v.item] || "";
        })
    }

    function getcontacts(contacts) {
        // Only treat entries with address/phone fields as "contact entries".
        // (Marketing opt-in is also stored in `contacts`, but should not create empty UI rows.)
        contacts = contacts.filter(v => ["phone", "city", "zip", "state", "address"].some(k => v[k]))
        // Now use it safely
        document.getElementById("phones").innerHTML = "";
        contacts.length > 0 ? contacts.map((v, index) => {
            document.getElementById("phones").innerHTML += `
            <div class="flex items-center gap-[8px]">
                <div id="phone_${index}" onclick="getAddressPerson(window.contacts, ${index})"
                    class="max-w-[80%] cursor-pointer rounded-full bg-[#E6FDE8] px-[16px] py-[8px]">
                    <h1 class="text-[16px] font-medium text-[#010205] overflow-hidden whitespace-nowrap">${v.phone}</h1>
                </div>
                <button type="button" onclick="deleteAddress(${index})" id="delete_address">
                    <i class="fa-solid fa-trash text-[12px] text-[#C00000]"></i>
                </button>
            </div>
        `;
        }) : "";
        const ids = [{
                id: "edit_phone",
                item: "phone"
            },
            {
                id: "edit_city",
                item: "city"
            },
            {
                id: "edit_zip",
                item: "zip"
            },
            {
                id: "edit_state",
                item: "state"
            },
            {
                id: "edit_address",
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

    function editUserinfo(id) {
        document.getElementById("edit_intro").classList.add("hidden");
        document.getElementById("edit_waiting").classList.remove("hidden");
        $.get("/get_user_info_by_id", {
            id: id
        }, (res) => {

            document.getElementById("edit_name").textContent = res[0].firstname + " " + res[0].lastname;
            const ids = [{
                    id: "edit_firstname",
                    item: "firstname"
                },
                {
                    id: "edit_lastname",
                    item: "lastname"
                },
                {
                    id: "edit_phone",
                    item: "phone"
                },
                {
                    id: "edit_city",
                    item: "city"
                },
                {
                    id: "edit_zip",
                    item: "zip"
                },
                {
                    id: "edit_state",
                    item: "state"
                },
                {
                    id: "edit_address",
                    item: "address"
                },
                {
                    id: "edit_email",
                    item: "email"
                }
            ]
            ids.forEach((v) => {
                document.getElementById(v.id).value = res[0][v.item] || "";
            })
            getcontacts(res[0]["contacts"] || []);
            const init_previewDiv = document.getElementById('preview');
            if (res[0]["url"] == "") {
                init_previewDiv.innerHTML = `
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
                init_previewDiv.innerHTML = `
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
            document.getElementById("edit_waiting").classList.add("hidden");
            document.getElementById("edit_detail_content").classList.remove("hidden");
        })
    }
    userinfoTable();
</script>