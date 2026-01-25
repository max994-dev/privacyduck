<div class="flex-1  relative ">
    <!-- Stats Cards -->
    <div class="grid mobile-grid sm-grid tablet-grid lg:grid-cols-4 gap-4 sm:gap-6 my-6 sm:my-8">
        <div class="card-glassmorphism rounded-2xl p-4 sm:p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray text-xs sm:text-sm font-medium">Total Users</p>
                    <p id="email_total_users" class="text-2xl sm:text-3xl font-bold text-dark mt-1 sm:mt-2">0</p>
                    <p class="text-green-600 text-xs sm:text-sm mt-1">+12% from last month</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card-glassmorphism rounded-2xl p-4 sm:p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray text-xs sm:text-sm font-medium">Total Paid Users</p>
                    <p id="email_paid_users" class="text-2xl sm:text-3xl font-bold text-dark mt-1 sm:mt-2">0</p>
                    <p class="text-green-600 text-xs sm:text-sm mt-1">+8% from last month</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card-glassmorphism rounded-2xl p-4 sm:p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray text-xs sm:text-sm font-medium">Not Paid Users</p>
                    <p id="email_not_paid_users" class="text-2xl sm:text-3xl font-bold text-dark mt-1 sm:mt-2">0</p>
                    <p class="text-yellow-600 text-xs sm:text-sm mt-1">Awaiting approval</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card-glassmorphism rounded-2xl p-4 sm:p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray text-xs sm:text-sm font-medium">PrivacyPros Paid Users</p>
                    <p id="email_blocked_users" class="text-2xl sm:text-3xl font-bold text-dark mt-1 sm:mt-2">0</p>
                    <p class="text-red-600 text-xs sm:text-sm mt-1">Security violations</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="relative content-glassmorphism rounded-2xl shadow-lg">
        <div class="p-4 sm:p-6 border-b border-light">
            <h3 class="text-lg sm:text-xl font-semibold text-dark">Privacyduck Users</h3>
            <p class="text-gray mt-1 text-sm sm:text-base">Manage and monitor user accounts</p>
        </div>

        <div class="overflow-x-auto w-[calc(100vw-26rem)]">
            <div id="user_table" class="overflow-x-auto">
            </div>
            <div id="pagination-container" class="py-4 flex justify-center"></div>
        </div>
    </div>
</div>
<script>
    var searchOptions = {
        current: 1,
        pageSize: 10,
        sort: "createdAt",
        search: ""
    };

    function initSearchOptions_and_events() {
        const urlParams = new URLSearchParams(window.location.search);
        setSearchOptions({
            current: urlParams.get("current") || 1,
            pageSize: urlParams.get("pageSize") || 10,
            sort: urlParams.get("sort") || "createdAt",
            search: urlParams.get("search") || ""
        });

    }

    function setSearchOptions(data) {
        if (data) {
            searchOptions = {
                ...searchOptions,
                ...data
            };
            if (data["search"] || data["sort"]) {
                searchOptions.current = 1;
            }
            getData();
        }
    }

    function setUrlForSearchOption() {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set("current", searchOptions.current);
        urlParams.set("pageSize", searchOptions.pageSize);
        urlParams.set("sort", searchOptions.sort);
        urlParams.set("search", searchOptions.search);
        window.history.pushState(null, "", "?" + urlParams.toString());
    }
    var user_manage_table = null;

    function userTable(data, total, pageSize, currentPage) {
        if (!user_manage_table) {
            user_manage_table = new Tabulator("#user_table", {
                data: data,
                layout: "fitDataStretch", // Columns auto-adjust within container width
                pagination: "local", // ← enable local pagination
                paginationSize: 10, // ← show 5 rows per page
                columns: [{
                        title: "No",
                        headerSort: false,
                        formatter: function(cell) {
                            const index = cell.getRow().getPosition(true);
                            return index;
                        },
                    },
                    {
                        title: "Email",
                        field: "email",
                        headerSort: true,
                        formatter: cell => cell.getValue() ?? "-"
                    },
                    {
                        title: "Name",
                        field: "firstname", // just needed for Tabulator to know the base field
                        headerSort: true,
                        formatter: function(cell) {
                            const row = cell.getData();
                            const fullName = `${row.firstname ?? ""} ${row.lastname ?? ""}`.trim();
                            return fullName || "-";
                        }
                    },
                    {
                        title: "Role",
                        field: "role",
                        headerSort: false,
                        formatter: function(cell) {
                            const value = cell.getValue();
                            return `<span class="${value===1 ? "text-[#24A556]" : "text-[#C00000]"}">${value===1?"Active":"Blocked"}</span>`;
                        },
                    },
                    {
                        title: "Status",
                        field: "plan_start",
                        headerSort: false,
                        formatter: function(cell) {
                            const row = cell.getData();
                            const start = row.plan_start;
                            const end = row.plan_end;
                            if (start && end && Date.parse(end) >= Date.now()) {
                                return `<span class="text-[#24A556]">Paid</span>`;
                            } else if (start && end && Date.parse(end) < Date.now()) {
                                return `<span class="text-[#FFCF50]">Expiration</span>`;
                            }
                            return `<span class="text-[#C00000]">Not Paid</span>`;
                        },
                    },
                    {
                        title: "Last Emailing Time",
                        field: "planedAt",
                        headerSort: true,
                        formatter: function(cell) {
                            const value = cell.getValue();
                            if (value) {
                                return `<span class="text-[#24A556]">${value}</span>`;
                            }
                            return `<span class="text-[#a9b6b7]">-</span>`;
                        },
                    },
                    {
                        title: "CreatedAt",
                        field: "created_at",
                        headerSort: true,
                        formatter: function(cell) {
                            const value = cell.getValue();
                            return `<span class="text-[#010205]">${value}</span>`;
                        },
                    },
                    {
                        title: "Control",
                        field: "id",
                        headerSort: false,
                        formatter: function(cell) {
                            const value = cell.getValue();
                            return `
                            <button class="btn-hover px-3 sm:px-4 py-2 bg-[#24A556] text-white font-medium rounded-xl hover:bg-[#24A556]/80 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-200 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h6m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                            </button>
                        `
                        }
                    }


                ]
            });
        } else {
            user_manage_table.setData(data);
        }
    }

    function getData() {
        setUrlForSearchOption();
        $.get("/super/admin/api/emailing/getlist", searchOptions, function(data) {
            $("#email_total_users").text(data.total);
            $("#email_paid_users").text(data.paidusers);
            $("#email_not_paid_users").text(data.unpaidusers);
            $("#email_blocked_users").text(data.blockedusers);
            userTable(data.list, data.total, data.pageSize, data.currentPage);
        })
    }
    getData();
</script>