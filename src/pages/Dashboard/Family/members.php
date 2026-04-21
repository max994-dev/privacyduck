<div id="modern-table" class="mt-[24px] px-[20px]">
</div>
<script>
    function familyEditUserinfo(id) {
        document.getElementById("family_edit_intro").classList.add("hidden");
        document.getElementById("family_edit_waiting").classList.remove("hidden");
        $.get("/get_user_info_by_id", {
            id: id
        }, (res) => {
            document.getElementById("family_member_name").textContent = capitalize(res[0].firstname) + " " + capitalize(res[0].lastname);
            const ids = [{
                    id: "family_edit_firstname",
                    item: "firstname"
                },
                {
                    id: "family_edit_lastname",
                    item: "lastname"
                },
                {
                    id: "family_edit_phone",
                    item: "phone"
                },
                {
                    id: "family_edit_city",
                    item: "city"
                },
                {
                    id: "family_edit_zip",
                    item: "zip"
                },
                {
                    id: "family_edit_state",
                    item: "state"
                },
                {
                    id: "family_edit_address",
                    item: "address"
                },
                {
                    id: "family_edit_email",
                    item: "email"
                },
            ]
            ids.forEach((v) => {
                document.getElementById(v.id).value = res[0][v.item] || "";
            })
            document.getElementById("family_edit_waiting").classList.add("hidden");
            document.getElementById("family_edit_detail_content").classList.remove("hidden");
        })
    }

    async function familyDeleteUserinfo(id) {
        const confirmed = await showConfirm();
        if (confirmed) {
            $.post("/delete_member_info", {
                invite_id: id
            }, (res) => {
                if (res.status == 1) {
                    toastr.success("Member deleted successfully");
                    memberTable();
                } else {
                    toastr.error("Failed to delete member");
                }
            });
        }
    }

    function memberTable() {
        document.getElementById("modern-table").innerHTML = `<div class="flex py-[32px] items-center justify-center bg-white transition-opacity  ease-in-out">
            <img src="/assets/image/desktop/duck.svg" class="animate-bounce w-[50px] h-[50px]" />
        </div>
        `;


        function get_removal_status(item) {
            var kind1 = parseInt(item.kind1_total, 10);
            if (isNaN(kind1)) kind1 = 0;
            var pending = parseInt(item.pending, 10);
            var ongoing = parseInt(item.ongoing, 10);
            var removed = parseInt(item.removed, 10);
            var notfound = parseInt(item.notfound, 10);
            if (isNaN(pending)) pending = 0;
            if (isNaN(ongoing)) ongoing = 0;
            if (isNaN(removed)) removed = 0;
            if (isNaN(notfound)) notfound = 0;
            // No broker-removal rows yet — always Pending (avoids false "Completed" right after invite).
            if (kind1 === 0) {
                return "Pending";
            }
            if (pending > 0) {
                return "Pending";
            }
            if (ongoing > 0) {
                return "Ongoing";
            }
            if (removed === 0 && notfound === 0) {
                return "Ongoing";
            }
            return "Completed";
        }
        $.ajax({
            url: "/get_members",
            cache: false,
            dataType: "json",
            success: (res) => {
            document.getElementById("family_members_count").textContent = res.length + " Members";
            window.member_tableData = res.map((item) => {
                return {
                    id: item.user_id,
                    name: capitalize(item.firstname) + " " + capitalize(item.lastname),
                    exposed: item.exposed,
                    removed: (item.removed || "-") + " / " + (item.notfound || "-"),
                    plan: item.display_status,
                    removal_status: get_removal_status(item),
                }
            });
            if (window.member_tableData.length > 0) {
                const table = new Tabulator("#modern-table", {
                    data: window.member_tableData,
                    layout:"fitDataStretch", // Columns auto-adjust within container width

                    // pagination: "local", // ← enable local pagination
                    // paginationSize: 5, // ← show 5 rows per page
                    columns: [{
                            title: "Name",
                            field: "name",
                            headerSort: false,
                        },
                        {
                            title: "Profile Exposed",
                            field: "exposed",
                            headerSort: false,
                            formatter: cell => cell.getValue() ?? "-"
                        },
                        {
                            title: "Removed/not found",
                            field: "removed",
                            headerSort: false,
                            formatter: cell => cell.getValue() ?? "-",
                        },
                        {
                            title: "Plan",
                            field: "plan",
                            headerSort: false,
                            formatter: function(cell) {
                                const value = cell.getValue();
                                return `<span class="${value==="Invite" ? "text-[#24A556]" : "text-[#a9b6b7]"}">${value}</span>`;
                            },
                        },
                        {
                            title: "Removal status",
                            field: "removal_status",
                            headerSort: false,
                            formatter: function(cell) {
                                const value = cell.getValue();
                                return `<span class="${value==="Pending" ? "text-[#010205]" : (value==="Ongoing" ? "text-[#AB4522]" : "text-[#24A556]")}">${value}</span>`;
                            },
                        },
                        {
                            title: "Actions",
                            field: "id",
                            headerSort: false,
                            formatter: function(cell) {
                                const id = cell.getValue();
                                return `
                            <div class="flex items-center space-x-[10px]">
                                <div class="cursor-pointer" onclick="familyEditUserinfo(${id})">
                                    <?php require(BASEPATH . "/src/common/svgs/dashboard/family/action_edit.php"); ?>
                                </div>
                                <div class="cursor-pointer" onclick="familyDeleteUserinfo(${id})">
                                    <?php require(BASEPATH . "/src/common/svgs/dashboard/family/action_trash.php"); ?>
                                </div>
                            </div>
                            `;
                            },
                        }
                    ]
                });
            } else {
                document.getElementById("modern-table").innerHTML = `<div class="flex justify-center items-center h-full bg-[#fbfbfb]">
                    <img class="w-[191px] h-[191px]" src="/assets/image/desktop/family/no_members.png" alt="no_members" />
                </div>`;
            }
            }
        });
    }

    memberTable();
</script>