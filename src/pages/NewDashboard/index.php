<?php
require_once BASEPATH . '/src/pages/Dashboard/dashboard_bootstrap.php';

if (!empty($_SESSION['pd_book_call_intent']) && empty($_SESSION['pd_book_call_done']) && !empty($_SESSION['planable'])) {
    header('Location: ' . WEB_DOMAIN . '/book-call');
    exit;
}

$meta_title = 'PrivacyDuck - Dashboard';
$meta_description = 'Protect your privacy with PrivacyDuck. We remove your personal data from the internet and safeguard your online presence. Get started today!';
$meta_url = 'https://privacyduck.com/new_dashboard';
$meta_image = 'https://privacyduck.com/assets/pageSEO/landing.jpg';

include_once BASEPATH . '/src/common/meta.php';
main_head_start();
main_head_end();
common_splash();

function pd_user_face_image_url_for_session(): string
{
    try {
        if (empty($_SESSION["user_id"])) {
            return '';
        }
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT url FROM users WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $_SESSION["user_id"]);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conn->close();
        if ($row && !empty($row["url"])) {
            return "/assets/uploads/specialinfo/" . rawurlencode((string) $row["url"]);
        }
    } catch (Throwable $e) {
        // ignore avatar failures
    }
    return '';
}

$pdHeaderFaceImage = pd_user_face_image_url_for_session();

function new_dashboard_fixed_menu()
{
    $faceImage = $GLOBALS["pdHeaderFaceImage"] ?? '';
    ?>
    <div class="hidden xl:flex justify-between py-[16px] px-[48px] items-center">
        <div class="flex items-center min-w-0">
            <button type="button" id="dashboard-desktop-sidebar-show"
                class="hidden items-center justify-center w-10 h-10 rounded-full text-[#010205] hover:bg-[#F0F0F0] mr-3 shrink-0"
                aria-label="Show sidebar" title="Show sidebar">
                <?php require_once BASEPATH . '/src/common/svgs/dashboard/sidebar/menu.php'; ?>
            </button>
            <div id="dashboard_header_contact" class="flex items-center gap-[16px] min-w-0">
                <div class="flex items-center gap-[8px]">
                    <?php require BASEPATH . '/src/common/svgs/dashboard/menu/supportemail.php'; ?>
                    <h1 class="text-[16px] text-[#010205] font-medium"> hello@privacyduck.com</h1>
                </div>
                <div class="flex items-center gap-[8px]">
                    <?php require BASEPATH . '/src/common/svgs/dashboard/menu/phone.php'; ?>
                    <h1 class="text-[16px] text-[#010205] font-medium">+1 775 443 3727</h1>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-[10px]">
            <?php if (!$_SESSION['plan_id'] || !$_SESSION['planable']) { ?>
                <button onclick="navigateTo('/new_dashboard/plans')"
                    class=" flex items-center justify-center bg-gradient-to-r from-[#77B248] to-[#24A556] w-[169px] h-[34px] rounded-full space-x-[6px]">
                    <?php require BASEPATH . '/src/common/svgs/dashboard/sidebar/fixed_menu_protect_user.php'; ?>
                    <h1 class="text-[14px] text-white">Protect Yourself</h1>
                </button>
            <?php } ?>
            <div class="relative group">
                <?php if ($faceImage !== '') { ?>
                    <div class="w-10 h-10 rounded-full overflow-hidden border border-[#E6E6E6] bg-white shadow-sm">
                        <img src="<?= htmlspecialchars($faceImage, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile"
                            class="w-full h-full object-cover" />
                    </div>
                <?php } else { ?>
                    <?php require BASEPATH . '/src/common/svgs/dashboard/sidebar/fixed_menu_user.php'; ?>
                <?php } ?>
                <ul
                    class="absolute right-[20px] pt-[10px] top-[100%] w-[161px] bg-white/10 backdrop-blur-md z-[1000] invisible group-hover:visible z-[1000] space-y-[24px]">
                    <li>
                        <h1 class="text-[12px] text-[#4B4B4E] font-medium"><?php echo $_SESSION['email']; ?></h1>
                    </li>
                    <li>
                        <a data-link href="/new_dashboard/account" class="flex items-center  space-x-[8px]">
                            <?php require BASEPATH . '/src/common/svgs/dashboard/sidebar/fixed_menu_account.php'; ?>
                            <h1 class="text-[16px] text-[#4B4B4E] font-medium">Account</h1>
                        </a>
                    </li>
                    <li>
                        <a href="/logout" class="flex items-center  space-x-[8px]">
                            <?php require BASEPATH . '/src/common/svgs/dashboard/sidebar/fixed_menu_logout.php'; ?>
                            <h1 class="text-[16px] text-[#C00000] font-medium">Logout</h1>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <?php
}
?>
<style>
    body {
        overflow-x: hidden;
    }
    @media (min-width: 1280px) {
        #dashboard-desktop-sidebar-aside {
            width: 307px;
            flex-shrink: 0;
            transition: width 0.3s ease-out;
        }
        #dashboard-desktop-sidebar-aside.is-collapsed {
            width: 0 !important;
            border: none;
            pointer-events: none;
        }
    }
</style>
<div class="xl:flex xl:flex-row xl:min-h-screen w-full">
    <?php require_once BASEPATH . '/src/pages/Dashboard/Family/add_info.php'; ?>
    <div class="fixed z-[1000] top-0 left-0 w-full h-[72px] bg-white border-b border-[#F1F1F1]">
        <div class="px-[16px] py-[12px] flex justify-between items-center">
            <div class="flex items-center gap-[10px]">
                <label id="menu-toggle-label"
                    class="cursor-pointer inline-flex items-center justify-center w-10 h-10 rounded-full border border-[#24A55633] bg-[#24A55614] text-[#24A556] hover:bg-[#24A55622]"
                    title="Open menu" aria-label="Open menu">
                    <i class="fa-solid fa-bars text-[16px]" aria-hidden="true"></i>
                </label>
                <a href="/" class="inline-flex items-center">
                    <img src="/assets/image/desktop/logo4.svg" alt="PrivacyDuck" class="h-8 w-auto" />
                </a>
            </div>
            <div class="flex items-center gap-[10px]">
                <?php if (!empty($pdHeaderFaceImage)) { ?>
                    <a data-link href="/new_dashboard/account"
                        class="inline-flex items-center justify-center w-10 h-10 rounded-full overflow-hidden border border-[#E6E6E6] bg-white shadow-sm"
                        title="Account" aria-label="Account">
                        <img src="<?= htmlspecialchars($pdHeaderFaceImage, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile"
                            class="w-full h-full object-cover" />
                    </a>
                <?php } ?>
                <a href="/logout"
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full border border-[#C0000033] bg-[#C0000012] text-[#C00000] hover:bg-[#C0000020]"
                    title="Logout" aria-label="Logout">
                    <i class="fa-solid fa-right-from-bracket text-[16px]" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </div>
    <aside id="dashboard-desktop-sidebar-aside" class="hidden overflow-hidden">
        <?php require BASEPATH . '/src/pages/NewDashboard/Sidebar/sidebar.php'; ?>
    </aside>
    <div id="mobile-sidebar-overlay"
        class="fixed inset-0 bg-black/35 z-[1100] hidden">
        <div id="mobile-sidebar-drawer" class="w-[298px] h-full overflow-y-auto overflow-x-hidden animate-[slideInSimple_0.25s_ease-out_forwards]">
            <?php require_once BASEPATH . '/src/pages/NewDashboard/Sidebar/sidebar_mobile.php'; ?>
        </div>
    </div>
    <div class="relative flex-1 min-w-0 w-screen h-screen overflow-y-auto overflow-x-hidden bg-[#fafafa]">
        <?php new_dashboard_fixed_menu(); ?>
        <div id="content" class="min-h-[calc(100vh-146px)] mt-[72px] xl:mt-[72px] px-[23px] sm:px-[24px] xl:px-[48px] py-[32px] xl:py-[37px]">
            <?php if ($showSignup): ?>
                <?php require BASEPATH . '/src/pages/Dashboard/signup_info.php'; ?>
            <?php else: ?>
                <?php require_once BASEPATH . '/src/pages/Dashboard/Family/add_info.php'; ?>
            <?php endif; ?>
        </div>

        <div class="flex items-center justify-end space-x-[3px] pb-[16px] px-[23px] sm:px-[24px] xl:px-[48px]">
            <?php require_once BASEPATH . '/src/common/svgs/dashboard/copyright.php'; ?>
            <h1 class="text-[#9B9B9C] text-[14px] tracking-[-0.02em]">
                2025 PrivacyDuck
            </h1>
        </div>
    </div>
</div>

<script>
    let sidebarVisible = false;

    document.addEventListener('click', function(event) {
        const overlay = document.getElementById('mobile-sidebar-overlay');
        const drawer = document.getElementById('mobile-sidebar-drawer');
        const label = document.getElementById('menu-toggle-label');
        const desktopBtn = document.getElementById('dashboard-desktop-sidebar-show');

        const clickedLabel = label && label.contains(event.target);
        const clickedDesktopBtn = desktopBtn && desktopBtn.contains(event.target);
        const clickedSidebar = drawer && drawer.contains(event.target);

        if (!sidebarVisible && (clickedLabel || clickedDesktopBtn)) {
            sidebarVisible = true;
            overlay.classList.remove('hidden');
            if (label) label.classList.add('hidden');
        } else if (sidebarVisible && !clickedSidebar && !clickedLabel && !clickedDesktopBtn) {
            sidebarVisible = false;
            overlay.classList.add('hidden');
            if (label) label.classList.remove('hidden');
        }
    });

    window.addEventListener('resize', function() {
        const overlay = document.getElementById('mobile-sidebar-overlay');
        const label = document.getElementById('menu-toggle-label');
        if (!sidebarVisible) return;
        if (overlay.classList.contains('hidden')) return;
        if (label) label.classList.add('hidden');
    });

    (function newDashboardDesktopSidebarCollapse() {
        const aside = document.getElementById('dashboard-desktop-sidebar-aside');
        const showBtn = document.getElementById('dashboard-desktop-sidebar-show');
        const hideBtn = document.getElementById('dashboard-desktop-sidebar-hide');
        if (!aside || !showBtn || !hideBtn) return;
        const KEY = 'pd_new_dashboard_sidebar_collapsed';

        function readCollapsed() {
            try {
                return localStorage.getItem(KEY) === '1';
            } catch (e) {
                return false;
            }
        }

        function apply(collapsed) {
            if (window.innerWidth < 1280) {
                aside.classList.remove('is-collapsed');
                showBtn.classList.add('hidden');
                return;
            }
            aside.classList.toggle('is-collapsed', collapsed);
            showBtn.classList.toggle('hidden', !collapsed);
            try {
                localStorage.setItem(KEY, collapsed ? '1' : '0');
            } catch (e) {}
        }

        apply(readCollapsed());
        window.addEventListener('resize', function() {
            apply(readCollapsed());
        });
        hideBtn.addEventListener('click', function() {
            apply(true);
        });
        showBtn.addEventListener('click', function() {
            apply(false);
        });
    })();

    function sidebar_select() {
        const workButtons = $("[data-type='dashboard_sidebar_personal'], [data-type='dashboard_sidebar_work']");
        const workButtons_mobile = $("[data-type='dashboard_sidebar_mobile_personal'], [data-type='dashboard_sidebar_mobile_work']");

        workButtons.click(function() {
            workButtons.removeClass("bg-[#24A556] text-[#FAFAFA]");
            workButtons_mobile.removeClass("bg-[#24A556] text-[#FAFAFA]");
            $(this).addClass("bg-[#24A556] text-[#FAFAFA]");
            $(workButtons_mobile[workButtons.index(this)]).addClass("bg-[#24A556] text-[#FAFAFA]");
        });

        workButtons_mobile.click(function() {
            workButtons.removeClass("bg-[#24A556] text-[#FAFAFA]");
            workButtons_mobile.removeClass("bg-[#24A556] text-[#FAFAFA]");
            $(this).addClass("bg-[#24A556] text-[#FAFAFA]");
            $(workButtons[workButtons_mobile.index(this)]).addClass("bg-[#24A556] text-[#FAFAFA]");
        });
        const tabpath = window.location.pathname;
        if (tabpath === "/new_dashboard/work") {
            $("[data-type='dashboard_sidebar_work']").addClass("bg-[#24A556] text-[#FAFAFA]");
            $("[data-type='dashboard_sidebar_mobile_work']").addClass("bg-[#24A556] text-[#FAFAFA]");
        } else {
            $("[data-type='dashboard_sidebar_personal']").addClass("bg-[#24A556] text-[#FAFAFA]");
            $("[data-type='dashboard_sidebar_mobile_personal']").addClass("bg-[#24A556] text-[#FAFAFA]");
        }
    }

    sidebar_select();

    const waiting = `<?php require BASEPATH . '/src/pages/Dashboard/splash.php' ?>`;

    function add_active(element) {
        element.querySelector("h1").classList.remove("font-medium");
        element.querySelector("h1").classList.add("font-bold");
        element.innerHTML = element.innerHTML.replace(/#4B4B4E/g, "#24A556");
    }

    function remove_active(element) {
        element.querySelector("h1").classList.remove("font-bold");
        element.querySelector("h1").classList.add("font-medium");
        element.innerHTML = element.innerHTML.replace(/#24A556/g, "#4B4B4E");
    }

    const sidebar = document.querySelector("#sidebar").children;
    const sidebar_mobile = document.querySelector("#sidebar_mobile").children;

    function change_active_sidebar() {
        const path = window.location.pathname;
        for (let index = 0; index < sidebar.length; index++) {
            const element = sidebar[index].children[0];
            if (element && element.getAttribute("href") === path) add_active(element);
            else if (element) remove_active(element);
        }

        for (let index = 0; index < sidebar_mobile.length; index++) {
            const element = sidebar_mobile[index].children[0];
            if (element && element.getAttribute("href") === path) add_active(element);
            else if (element) remove_active(element);
        }
    }

    function tryFinalizePendingFamilyInvite() {
        var params = new URLSearchParams(window.location.search);
        if (params.get("invite_paid") !== "1") {
            return;
        }
        $.post("/invite_payment_finalize_pending", {}, function(res) {
            if (res.success && !res.skipped) {
                if (typeof toastr !== "undefined") {
                    toastr.success("Family member added.");
                }
                if (typeof memberTable === "function") {
                    memberTable();
                }
            } else if (res.success && res.skipped) {
            } else if (res.error) {
                if (typeof toastr !== "undefined") {
                    toastr.error(res.error);
                }
            }
            params.delete("invite_paid");
            var qs = params.toString();
            history.replaceState(null, "", window.location.pathname + (qs ? "?" + qs : ""));
        }, "json").fail(function(xhr) {
            var msg = "Could not add member after payment.";
            try {
                var j = JSON.parse(xhr.responseText);
                if (j && j.error) {
                    msg = j.error;
                }
            } catch (e) {}
            if (typeof toastr !== "undefined") {
                toastr.error(msg);
            }
        });
    }

    function renderRoute() {
        const path = window.location.pathname;
        const id = "<?php echo $_SESSION['user_id']; ?>";
        $('#content').html(waiting);
        if (path === "/new_dashboard/concierge") $("#dashboard_header_contact").show();
        else $("#dashboard_header_contact").hide();

        switch (path) {
            case "/new_dashboard/detail":
                $.get("/dashboard/content/detail", data => {
                    $('#content').html(data)
                });
                break;
            case "/new_dashboard/account":
                $.get("/new_dashboard/content/account", data => {
                    $('#content').html(data);
                });
                break;
            case "/new_dashboard/editinfo":
                $.get("/dashboard/content/editinfo", data => {
                    $('#content').html(data)
                });
                break;
            case "/new_dashboard/family":
                $.get("/dashboard/content/family", data => {
                    $('#content').html(data);
                    if (window.show_family_modal) {
                        add_info_show_modal();
                        window.show_family_modal = false;
                    }
                    tryFinalizePendingFamilyInvite();
                });
                break;
            case "/new_dashboard/plans":
                $.get("/dashboard/content/plans", data => {
                    $('#content').html(data);
                    plans_faq_init();
                    plans_init();
                });
                break;
            case "/new_dashboard/custom":
                const planable = "<?php echo isset($_SESSION['planable']) && $_SESSION['planable']; ?>";
                if (!planable) {
                    toastr.error("Please upgrade your plan to use this feature.");
                    navigateTo("/new_dashboard/plans");
                    return;
                }
                $.get("/dashboard/content/custom", data => {
                    $('#content').html(data)
                });
                break;
            case "/new_dashboard/personal":
                $.get("/dashboard/content/personal", data => {
                    $('#content').html(data)
                });
                break;
            case "/new_dashboard/concierge":
                $.get("/dashboard/content/concierge", data => {
                    $('#content').html(data);
                });
                break;
            case "/new_dashboard/work":
                $.get("/dashboard/content/work", data => {
                    $('#content').html(data);
                    business_work();
                });
                break;
            case "/new_dashboard":
                $.get("/dashboard/content", data => {
                    $('#content').html(data);
                    initSearchOptions_and_events();
                    main_isRemoval();
                    main_animate_progress();
                    databrokers_select();
                    main_progress();
                    main_progress_mobile();
                    main_dropdown();
                    main_table();
                });
                break;
            default:
                if (path.startsWith("/new_dashboard")) {
                    history.replaceState(null, null, "/new_dashboard");
                }
                $.get("/dashboard/content", data => {
                    $('#content').html(data);
                    initSearchOptions_and_events();
                    main_isRemoval();
                    main_animate_progress();
                    databrokers_select();
                    main_progress();
                    main_progress_mobile();
                    main_dropdown();
                    main_table();
                });
        }

        change_active_sidebar();
    }

    window.addEventListener('popstate', renderRoute);

    document.addEventListener('click', e => {
        const target = e.target.closest('a[data-link]');
        if (target) {
            e.preventDefault();
            navigateTo(target.href);
        }
    });

    function navigateTo(url) {
        history.pushState(null, null, url);
        renderRoute();
    }

    renderRoute();
</script>
<script type="text/javascript">
    var Tawk_API = Tawk_API || {},
        Tawk_LoadStart = new Date();
    (function() {
        var s1 = document.createElement("script"),
            s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/6813761a7c6684190de59a7c/1iq60amh0';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
</script>
