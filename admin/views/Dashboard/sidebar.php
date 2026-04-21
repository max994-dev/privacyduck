<div id="sidebarOverlay" class="fixed inset-0 sidebar-overlay z-40 sm:hidden hidden"></div>
<div id="sidebar" class="sidebar-mobile fixed md:relative z-50 w-[320px] h-screen sidebar-glassmorphism bg-gradient-to-br from-[#77B248] to-[#24A556]">
    <!-- Logo Section -->
    <div class="px-6 pt-10 pb-6 border-b border-white border-opacity-20">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 logo-container rounded-2xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-white">Privacyduck</h2>
                <p class="text-sm text-green-100">Admin Portal</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="p-4 space-y-2">
        <a href="/super/admin/usermanage" class="nav-item active flex items-center space-x-3 px-4 py-3 rounded-xl text-white font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
            <span>User Management</span>
        </a>

        <a href="/super/admin/familymanage" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-xl text-white font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span>Family Management</span>
        </a>

        <a href="/super/admin/removalmanage" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-xl text-white font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            <span>Removal Management</span>
        </a>

        <a href="/super/admin/businessmanage" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-xl text-white font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <span>Business Management</span>
        </a>
        <a href="/super/admin/emailingsystem" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-xl text-white font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h6m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
            </svg>
            <span>Emailing System</span>
        </a>
    </nav>

    <!-- User Profile Section -->
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-white border-opacity-20">
        <div class="flex items-center space-x-3 px-4 py-3 rounded-xl bg-white bg-opacity-10">
            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-white">Administrator</p>
                <p class="text-xs text-green-100"><?php echo $_SESSION["admin"]["username"]; ?></p>
            </div>
            <a href="/super/admin/api/logout" class="text-white hover:text-green-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
            </a>
        </div>
    </div>
</div>

<script>
    class SidebarToggle {
        constructor() {
            this.sidebar = document.getElementById('sidebar');
            this.overlay = document.getElementById('sidebarOverlay');
            this.mobileMenuBtn = document.getElementById('mobileMenuBtn');

            this.mobileMenuBtn.addEventListener('click', () => this.toggleSidebar());
            this.overlay.addEventListener('click', () => this.closeSidebar());
        }

        toggleSidebar() {
            this.sidebar.classList.toggle('open');
            this.overlay.classList.toggle('hidden');
        }

        closeSidebar() {
            this.sidebar.classList.remove('open');
            this.overlay.classList.add('hidden');
        }
    }
    const sidebar = document.querySelectorAll('.nav-item');

    function renderRoute() {
        const path = window.location.pathname;
        $('#content').html("...waiting");

        switch (path) {
            case "/super/admin/usermanage":
                $.get("/super/admin/content/usermanage", data => {
                    $('#content').html(data)
                });
                break;
            case "/super/admin/familymanage":
                $.get("/super/admin/content/familymanage", data => {
                    $('#content').html(data)
                });
                break;
            case "/super/admin/removalmanage":
                $.get("/super/admin/content/removalmanage", data => {
                    $('#content').html(data)
                });
                break;
            case "/super/admin/businessmanage":
                $.get("/super/admin/content/businessmanage", data => {
                    $('#content').html(data)
                });
                break;
            case "/super/admin/emailingsystem":
                $.get("/super/admin/content/emailingsystem", data => {
                    $('#content').html(data)
                });
                break;
            default:
                history.pushState(null, null, "/super/admin/usermanage");
                $.get("/super/admin/content/usermanage", data => {
                    $('#content').html(data);
                });
        }
        change_active_sidebar();
    }

    function change_active_sidebar() {
        const path = window.location.pathname;
        let activeIndex = [
            "/super/admin/usermanage",
            "/super/admin/familymanage",
            "/super/admin/removalmanage",
            "/super/admin/businessmanage",
            "/super/admin/emailingsystem",
        ].indexOf(path);
        sidebar.forEach(item => item.classList.remove('active'));
        sidebar[activeIndex].classList.add('active');
    }

    function add_active(element) {
        element.classList.add('active');
    }

    function remove_active(element) {
        element.classList.remove('active');
    }
    class Navigation {
        constructor() {
            this.navItems = document.querySelectorAll('.nav-item');
            this.pageTitle = document.querySelector('header h1');
            this.pageSubtitle = document.querySelector('header p');

            this.navItems.forEach(item => {
                item.addEventListener('click', (e) => this.handleNavClick(e));
            });
        }

        handleNavClick(e) {
            e.preventDefault();
            navigateTo(e.currentTarget.getAttribute('href'));
        }
    }
    window.addEventListener('popstate', renderRoute);

    function navigateTo(url) {
        history.pushState(null, null, url);
        renderRoute();
    }

    renderRoute();
    // Initialize everything when DOM is loaded
    document.addEventListener('DOMContentLoaded', () => {
        new SidebarToggle();
        new Navigation();
    });
</script>