<header class="header-glassmorphism p-4 sm:p-6">
    <div class="flex items-center justify-between">
        <!-- Mobile Menu Button -->
        <button id="mobileMenuBtn" class="sm:hidden text-gray-700 hover:text-gray-900 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <!-- Page Title -->
        <div class="flex-1 lg:flex-none ml-4 lg:ml-0">
            <h1 class="text-xl sm:text-2xl font-bold text-dark">User Management</h1>
            <p class="text-sm sm:text-base text-gray">Manage system users and permissions</p>
        </div>

        <!-- Header Actions -->
        <div class="flex items-center space-x-2 sm:space-x-4">
            <!-- Add New Button -->
            <button class="btn-hover px-3 sm:px-4 py-2 bg-[#24A556] text-white font-medium rounded-xl hover:bg-[#24A556]/80 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-200 shadow-sm">
                <div class="flex items-center space-x-1 sm:space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="hidden sm:block text-sm">Add User</span>
                    <span class="sm:hidden text-sm">Add</span>
                </div>
            </button>
        </div>
    </div>
</header>