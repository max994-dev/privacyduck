<?php
isBusinessLogin();
$meta_title = "PrivacyDuck - Dashboard";
$meta_description = "Protect your privacy with PrivacyDuck. We remove your personal data from the internet and safeguard your online presence. Get started today!";
$meta_url = "https://privacyduck.com/";
$meta_image = "https://privacyduck.com/assets/pageSEO/landing.jpg";

include_once(BASEPATH . "/src/common/meta.php");
main_head_start();
main_head_end();
common_splash();
function fixed_menu()
{
?>
    <div class="hidden xl:flex justify-between py-[16px] px-[48px]">
        <div class="flex items-center">
            <div id="dashboard_header_contact" class="flex items-center gap-[16px]">
                <div class="flex items-center gap-[8px]">
                    <?php require(BASEPATH . "/src/common/svgs/dashboard/menu/supportemail.php"); ?>
                    <h1 class="text-[16px] text-[#010205] font-medium"> hello@privacyduck.com</h1>
                </div>
                <!-- <div class="flex items-center gap-[8px]">
                    <?php require(BASEPATH . "/src/common/svgs/dashboard/menu/phone.php"); ?>
                    <h1 class="text-[16px] text-[#010205] font-medium">+1 775 443 3727</h1>
                </div> -->
            </div>
        </div>
        <div class="flex items-center space-x-[10px]">
            <div class="relative group">
                <?php require(BASEPATH . "/src/common/svgs/dashboard/sidebar/fixed_menu_user.php"); ?>
                <ul
                    class="absolute left-[-50%] pt-[10px] top-[100%] w-[161px] bg-white/10 backdrop-blur-md z-[1000] invisible group-hover:visible z-[1000] space-y-[24px]">
                    <li>
                        <a data-link href="/business/dashboard/settings/account" class="flex items-center  space-x-[8px]">
                            <?php require(BASEPATH . "/src/common/svgs/dashboard/sidebar/fixed_menu_account.php"); ?>
                            <h1 class="text-[16px] text-[#4B4B4E] font-medium">Account</h1>
                        </a>
                    </li>
                    <li>
                        <a href="/business/logout" class="flex items-center  space-x-[8px]">
                            <?php require(BASEPATH . "/src/common/svgs/dashboard/sidebar/fixed_menu_logout.php"); ?>
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
</style>
<div class="xl:flex">
    <!-- Sidebar -->
    <?php require_once(BASEPATH . "/src/pages/Dashboard/Family/add_info.php"); ?>
    <div class="fixed z-[1000] top-0 left-0 w-full h-[72px] bg-white text-white xl:hidden">
        <div class="px-[16px] py-[12px] flex justify-between items-center">
            <a href="/"><img src="/assets/image/desktop/duck.svg" alt="duck" /></a>
            <label id="menu-toggle-label" class="cursor-pointer">
                <?php require_once(BASEPATH . "/src/common/svgs/dashboard/sidebar/menu.php"); ?>
            </label>
        </div>
    </div>
    <?php require(BASEPATH . "/src/pages/Business/dashboard/sidebar/desktop.php"); ?>
    <div id="mobile-sidebar-overlay"
        class="fixed inset-0  overflow-y-auto overflow-x-hidden w-[298px] animate-[slideInSimple_0.4s_ease-out_forwards] z-[1000] hidden">
        <?php require_once(BASEPATH . "/src/pages/Business/dashboard/sidebar/mobile.php"); ?>
    </div>
    <!-- Content -->
    <div class="relative w-screen xl:w-[calc(100vw-307px)] h-screen overflow-y-auto overflow-x-hidden bg-[#fafafa]">
        <?php fixed_menu(); ?>
        <div id="content" class="min-h-[calc(100vh-146px)] mt-[72px] xl:mt-0 px-[23px] sm:px-[24px] xl:px-[48px] py-[32px] xl:py-[37px]">
        </div>
        <div class="flex items-center justify-end space-x-[3px] pb-[16px] px-[23px] sm:px-[24px] xl:px-[48px]">
            <?php require_once(BASEPATH . "/src/common/svgs/dashboard/copyright.php"); ?>
            <h1 class="text-[#9B9B9C] text-[14px] tracking-[-0.02em]">
                2025 PrivacyDuck
            </h1>
        </div>
    </div>
</div>
<script>
    // Sidebar toggle state
    let sidebarVisible = false;

    // Sidebar toggle by clicking hamburger and closing by clicking outside
    document.addEventListener('click', function(event) {
        const overlay = document.getElementById('mobile-sidebar-overlay');
        const label = document.getElementById('menu-toggle-label');

        const clickedLabel = label.contains(event.target);
        const clickedSidebar = overlay.contains(event.target);

        if (!sidebarVisible && clickedLabel) {
            sidebarVisible = true;
            overlay.classList.remove('hidden');
            label.classList.add('hidden');
        } else if (sidebarVisible && !clickedSidebar && !clickedLabel) {
            sidebarVisible = false;
            overlay.classList.add('hidden');
            label.classList.remove('hidden');
        }
    });

    window.addEventListener('resize', function() {
        const overlay = document.getElementById('mobile-sidebar-overlay');
        const label = document.getElementById('menu-toggle-label');
        if (window.innerWidth >= 1280) {
            sidebarVisible = false;
            overlay.classList.add('hidden');
            label.classList.remove('hidden');
        }
    });

    function add_active(element) {
        element.querySelector("h1").classList.remove("text-[#FFFFF]");
        element.querySelector("h1").classList.add("text-[#FFCF50]");
    }

    function remove_active(element) {
        element.querySelector("h1").classList.remove("text-[#FFCF50]");
        element.querySelector("h1").classList.add("text-[#FFFFF]");
    }

    const sidebar = document.querySelector("#sidebar").children;
    const sub_sidedbar = document.querySelector("#sub_sidebar").children;
    const sidebar_mobile = document.querySelector("#sidebar_mobile").children;

    function change_active_sidebar() {
        const path = window.location.pathname;
        let activeIndex = [
            "/business/dashboard",
            "/business/dashboard/main",
            "/business/dashboard/support",
            "/business/dashboard/speaksales",
            "/business/dashboard/settings/general",
            "/business/dashboard/settings/account",
            "/business/dashboard/settings/security",
            "/business/dashboard/settings/team",
            "/business/dashboard/settings/billing",
            "/business/dashboard/settings/integrations",
            "/business/dashboard/settings/notifications",
            "/business/dashboard/settings/privacy",
            "/business/dashboard/settings/apikeys"
        ].indexOf(path);
        for (let index = 0; index < sidebar.length; index++) {
            const element = sidebar[index].children[0];
            if (element.getAttribute("href") === null) continue;
            if (element.getAttribute("href") === path) add_active(element);
            else remove_active(element);
        }
        for (let index = 0; index < sub_sidedbar.length; index++) {
            const element = sub_sidedbar[index];
            if (element.getAttribute("href") === null) continue;
            if (element.getAttribute("href") === path) add_active(element);
            else remove_active(element);
        }
        // for (let index = 0; index < sidebar_mobile.length; index++) {
        //     const element = sidebar_mobile[index].children[0];
        //     if (element.getAttribute("href") === path) add_active(element);
        //     else remove_active(element);
        // }
    }

    // Sidebar selection highlight
    function sidebar_select() {
        const workButtons = $("[data-type='business_sidebar_personal'], [data-type='business_sidebar_work']");
        const workButtons_mobile = $("[data-type='business_sidebar_mobile_personal'], [data-type='business_sidebar_mobile_work']");

        workButtons.click(function() {
            workButtons.removeClass("bg-[#FFCF50] text-[#3D5300]");
            workButtons_mobile.removeClass("bg-[#FFCF50] text-[#3D5300]");
            $(this).addClass("bg-[#FFCF50] text-[#3D5300]");
            $(workButtons_mobile[workButtons.index(this)]).addClass("bg-[#FFCF50] text-[#3D5300]");
        });

        workButtons_mobile.click(function() {
            workButtons.removeClass("bg-[#FFCF50] text-[#3D5300]");
            workButtons_mobile.removeClass("bg-[#FFCF50] text-[#3D5300]");
            $(this).addClass("bg-[#FFCF50] text-[#3D5300]");
            $(workButtons[workButtons_mobile.index(this)]).addClass("bg-[#FFCF50] text-[#3D5300]");
        });

        $("[data-type='business_sidebar_work']").addClass("bg-[#FFCF50] text-[#3D5300]");
        $("[data-type='business_sidebar_mobile_work']").addClass("bg-[#FFCF50] text-[#3D5300]");
    }

    sidebar_select();

    // Splash page content
    const waiting = `<?php require(BASEPATH . "/src/pages/Dashboard/splash.php") ?>`;

    function tryFinalizePendingBusinessInvite() {
        var params = new URLSearchParams(window.location.search);
        if (params.get("invite_paid") !== "1") {
            return;
        }
        $.post("/invite_payment_finalize_pending", {}, function(res) {
            if (res.success && !res.skipped) {
                if (typeof toastr !== "undefined") {
                    toastr.success("Member added.");
                }
                params.delete("invite_paid");
                var qs = params.toString();
                history.replaceState(null, "", window.location.pathname + (qs ? "?" + qs : ""));
                location.reload();
                return;
            }
            if (res.success && res.skipped) {
                // no pending
            } else if (res.error && typeof toastr !== "undefined") {
                toastr.error(res.error);
            }
            params.delete("invite_paid");
            var qs2 = params.toString();
            history.replaceState(null, "", window.location.pathname + (qs2 ? "?" + qs2 : ""));
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
        $('#content').html(waiting);
        switch (path) {
            case "/business/dashboard/support":
                $.get("/business/content/dashboard/support", data => {
                    $('#content').html(data);
                    slide_init();
                    // show_phone();
                });
                break;
            case "/business/dashboard/speaksales":
                $.get("/business/content/dashboard/speaksales", data => {
                    $('#content').html(data);
                    slide_init_speaksales();
                });
                break;
            case "/business/dashboard/settings/general":
                $.get("/business/content/dashboard/settings/general", data => {
                    $('#content').html(data);
                    switch_button();
                    timezone();
                });
                break;
            case "/business/dashboard/settings/account":
                $.get("/business/content/dashboard/settings/account", data => {
                    $('#content').html(data);
                    get_business_account_info();
                });
                break;
            case "/business/dashboard/settings/security":
                $.get("/business/content/dashboard/settings/security", data => {
                    $('#content').html(data)
                });
                break;
            case "/business/dashboard/settings/team":
                $.get("/business/content/dashboard/settings/team", data => {
                    $('#content').html(data)
                });
                break;
            case "/business/dashboard/settings/billing":
                $.get("/business/content/dashboard/settings/billing", data => {
                    $('#content').html(data)
                });
                break;
            case "/business/dashboard/settings/integrations":
                $.get("/business/content/dashboard/settings/integrations", data => {
                    $('#content').html(data)
                });
                break;
            case "/business/dashboard/settings/notifications":
                $.get("/business/content/dashboard/settings/notifications", data => {
                    $('#content').html(data)
                });
                break;
            case "/business/dashboard/settings/privacy":
                $.get("/business/content/dashboard/settings/privacy", data => {
                    $('#content').html(data)
                });
                break;
            case "/business/dashboard/settings/apikeys":
                $.get("/business/content/dashboard/settings/apikeys", data => {
                    $('#content').html(data)
                });
                break;
            default:
                if (window.location.pathname === "/business/dashboard" || window.location.pathname === "/business/dashboard/main") {
                    // keep ?invite_paid= etc.
                } else {
                    history.pushState(null, null, "/business/dashboard");
                }
                $.get("/business/content/dashboard/main", data => {
                    $('#content').html(data);
                    menu_select();
                    tryFinalizePendingBusinessInvite();
                });
        }
        change_active_sidebar();
    }

    window.addEventListener('popstate', renderRoute);

    document.addEventListener('click', e => {
        const target = e.target.closest('a[data-link]');
        if (target) {
            e.preventDefault();
            if (target.href === "http://privacyduck.com/business/dashboard/settings") {
                navigateTo("/business/dashboard/settings/general");
            } else {
                navigateTo(target.href);
            }
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
