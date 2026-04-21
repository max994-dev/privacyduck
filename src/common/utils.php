<?php
// require_once("sesion.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Some servers/proxies leave $_POST empty for POST + application/x-www-form-urlencoded.
 */
function pd_normalize_post_request(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST' || !empty($_POST)) {
        return;
    }
    $raw = file_get_contents('php://input');
    if ($raw === false || $raw === '') {
        return;
    }
    $ct = $_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_CONTENT_TYPE'] ?? '';
    if ($ct !== '' && stripos($ct, 'application/x-www-form-urlencoded') === false) {
        return;
    }
    parse_str($raw, $parsed);
    if (is_array($parsed) && $parsed !== []) {
        $_POST = $parsed;
    }
}

/** Users table role: treat missing/null as allowed; block only when role is set and is below 1. */
function pd_user_may_login(array $user): bool
{
    if (!array_key_exists('role', $user)) {
        return true;
    }
    $r = $user['role'];
    if ($r === null || $r === '') {
        return true;
    }
    return (int) $r >= 1;
}

function main_head_start()
{ ?>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="IsaNzKjoHBwx870MEenhIdXssc8XIkH_mMcxLb0pkes" />

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://unpkg.com/tabulator-tables@5.5.0/dist/css/tabulator.min.css" rel="stylesheet">
    <?php $assetV = defined('VERISON') ? rawurlencode((string) VERISON) : (string) time(); ?>
    <link href="/assets/css/animate.css?v=<?= $assetV ?>" rel="stylesheet">
    <link href="/assets/css/splash.css?v=<?= $assetV ?>" rel="stylesheet">
    <link href="/assets/css/main.css?v=<?= $assetV ?>" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Alatsi&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css' rel='stylesheet' />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">


    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://unpkg.com/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>
    <script src='https://cdn.tailwindcss.com'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://unpkg.com/gsap@3.12.5/dist/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/@googlemaps/extended-component-library/0.6.11/index.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-P6WKNFG8FS"></script>
    <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
    <!-- <script src="https://analytics.ahrefs.com/analytics.js" data-key="InYjlKfAfcPiKMQbKt7zUQ" async></script> -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-P679J6HD');
    </script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-P6WKNFG8FS');
    </script>
    <!-- <script type="text/javascript">
        (function() {
            var options = {
                whatsapp: "+17754433727", // Your WhatsApp number
                call_to_action: "Message us on WhatsApp", // Button text
                position: "right", // Position: 'right' or 'left'
                pre_filled_message: "Hi! I’d like to request a Privacy Report or Data Removal.", // Default message
            };
            var proto = document.location.protocol,
                host = "getbutton.io",
                url = proto + "//static." + host;
            var s = document.createElement('script');
            s.type = 'text/javascript';
            s.async = true;
            s.src = url + '/widget-send-button/js/init.js';
            s.onload = function() {
                WhWidgetSendButton.init(host, proto, options);
            };
            var x = document.getElementsByTagName('script')[0];
            x.parentNode.insertBefore(s, x);
        })();
    </script> -->
    <link rel="icon" type="image/png" href="/assets/favicon.png">
    <?php if (isset($_SESSION['isAuthenticated']) && $_SESSION['isAuthenticated'] == true) {
    ?>
        <script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
    <?php
    } ?>
    <script>
        window.loadingHtml = "<img src='/assets/image/desktop/loading1.webp' class='w-6 h-6 flex mr-2'> <span class='font-semibold text-[12px] leading-[130%] tracking-[-0.02em]'>Saving...</span>";
        window.loadingHtml2 = "<img src='/assets/image/desktop/loading1.webp' class='w-6 h-6 flex mr-2'> <span class='font-semibold text-[12px] leading-[130%] tracking-[-0.02em]'>Loading...</span>";
        window.loadingHtml3 = "<img src='/assets/image/desktop/loading1.webp' class='w-6 h-6 flex mr-2'> <span class='font-semibold text-[12px] leading-[130%] tracking-[-0.02em]'>Saving...</span>";
        window.loadingHtml4 = "<img src='/assets/image/desktop/loading1.webp' class='w-6 h-6 flex mr-2'> <span class='font-semibold text-[#00530F] text-[12px] leading-[130%] tracking-[-0.02em]'>Sending...</span>";
        window.show_family_modal = false;
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right", // other options: toast-top-right, etc.
            "timeOut": "2000"
        };

        function capitalize(str) {
            return (str || "").charAt(0).toUpperCase() + (str || "").slice(1).toLowerCase();
        }

        function showConfirm(icon = null, title = null, description = null) {
            return new Promise((resolve) => {
                const modal = document.getElementById("custom-alert");
                modal.classList.remove("hidden");

                document.getElementById("alert_icon").innerHTML = icon || "<i class='fa-solid fa-circle-exclamation text-orange-500 text-[32px] mt-1'></i>";
                document.getElementById("alert_title").innerHTML = title || "Are you sure delete this member?";

                const cancelBtn = document.getElementById("cancel-btn");
                const okBtn = document.getElementById("ok-btn");

                const closeModal = () => modal.classList.add("hidden");

                // Reset event listeners
                const newCancel = cancelBtn.cloneNode(true);
                const newOk = okBtn.cloneNode(true);
                cancelBtn.parentNode.replaceChild(newCancel, cancelBtn);
                okBtn.parentNode.replaceChild(newOk, okBtn);

                newCancel.addEventListener("click", () => {
                    closeModal();
                    resolve(false); // User clicked Cancel
                });

                newOk.addEventListener("click", () => {
                    closeModal();
                    resolve(true); // User clicked OK
                });
            });
        }

        function activePanel(panel, withPlus = true) {
            panel.style["borderColor"] = "#24A556";
            if (withPlus) panel.querySelector(".icon-plus").style["color"] = "#24A556";
            if (withPlus) panel.querySelector(".icon-minus").style["color"] = "#24A556";
        }

        function inactivePanel(panel) {
            panel.style["borderColor"] = "rgb(0 0 0 / var(--tw-border-opacity, 1))";
            panel.querySelector(".icon-plus").style["color"] = "rgb(0 0 0 / var(--tw-border-opacity, 1))";
            panel.querySelector(".icon-minus").style["color"] = "rgb(0 0 0 / var(--tw-border-opacity, 1))";
        }

        function changeActiveCollase(method) {
            const element = document.querySelector("#accordion-collapse");
            if (method === 0) {
                element.style.borderTopColor = "rgb(0 0 0 / var(--tw-border-opacity, 1))"
                element.style.borderBottomColor = "rgb(0 0 0 / var(--tw-border-opacity, 1))"
            } else if (method === 3) {
                element.style.borderTopColor = "#24A556";
                element.style.borderBottomColor = "#24A556";
            } else if (method === 1) {
                element.style.borderTopColor = "#24A556";
                element.style.borderBottomColor = "rgb(0 0 0 / var(--tw-border-opacity, 1))";
            } else if (method === 2) {
                element.style.borderTopColor = "rgb(0 0 0 / var(--tw-border-opacity, 1))";
                element.style.borderBottomColor = "#24A556";
            }
        }

        function toggleCollapse() {
            let panels = document.querySelectorAll("#accordion-collapse>h1");
            for (let i = 0; i < panels.length; i++) {
                const element = panels[i];
                inactivePanel(panels[i]);
                changeActiveCollase(0);
            }
            for (let i = 0; i < panels.length; i++) {
                const element = panels[i];
                if (element.querySelector("button").getAttribute("aria-expanded") === "true") {
                    if (i === 0) changeActiveCollase(1)
                    activePanel(panels[i]);
                    if (i < panels.length - 1) activePanel(panels[i + 1], false);
                    else changeActiveCollase(2)
                }
            }
        }
        <?php if (isset($_SESSION['isAuthenticated']) && $_SESSION['isAuthenticated'] == true) {
        ?>
            // const socket = io('https://sayloapp.com');

            // socket.on('connect', () => {
            //     socket.emit('join', '<?= $_SESSION['user_id'] ?>');
            // });

            // socket.on('disconnect', () => {
            // });
            // socket.on('progress', (data) => {
            //     if (data.kind === 1) {
            //     }
            // });
            // socket.on('complete', (data) => {
            //     if (data.kind === 0) {
            //         if (window.addScanImage) window.addScanImage(data);
            //         if (window.inc_scan_count) window.inc_scan_count();
            //         if (window.updateMatchesFound) window.updateMatchesFound();
            //     } else if (data.kind === 1) {
            //         if (window.main_animate_progress) window.main_animate_progress("progress", 1);
            //         if (window.main_table) window.main_table();
            //     } else if (data.kind === 3) {
            //     }
            // });
        <?php
        }
        ?>
    </script>
<?php }
function main_head_end()
{ ?>
    </head>

    <body>
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P679J6HD"
                height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <div id="custom-alert" class="fixed inset-0 bg-black/30 flex items-center justify-center z-50 hidden px-[16px]">
            <div class="bg-white rounded-xl shadow-xl max-w-sm w-full p-6">
                <div class="flex items-center">
                    <div id="alert_icon" class="w-[40px]">
                    </div>
                    <div>
                        <h2 id="alert_title" class="ml-[15px] text-base font-semibold text-gray-800"></h2>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button id="cancel-btn" class="px-4 py-1.5 text-sm rounded border border-gray-300 text-gray-700 hover:bg-gray-100">
                        Cancel
                    </button>
                    <button id="ok-btn" class="px-4 py-1.5 text-sm rounded border border-red-500 text-red-600 hover:bg-red-50">
                        Ok
                    </button>
                </div>
            </div>
        </div>
    <?php }


function main_logout_header($x)
{ ?>
        <input type="checkbox" id="menu-toggle" class="hidden peer" />
        <div class="fixed w-full h-[96px] bg-white/10 backdrop-blur-md z-20"></div>
        <div class="py-[24px] fixed w-full h-[96px] z-20 text-white">
            <div class="px-[16px] flex justify-between items-center lg:hidden">
                <a href="/"><img src="/assets/image/desktop/duck.svg" alt="duck" /></a>
                <label for="menu-toggle" class="cursor-pointer">
                    <svg width="40" height="18" viewBox="0 0 40 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <line y1="1" x2="40" y2="1" stroke="#24A556" stroke-width="2" />
                        <line y1="9" x2="40" y2="9" stroke="#24A556" stroke-width="2" />
                        <line y1="17" x2="40" y2="17" stroke="#24A556" stroke-width="2" />
                    </svg>

                </label>
            </div>
            <div class="px-[10px] xl:px-[80px]  justify-between items-center hidden lg:flex">
                <div class="flex flex-row items-center lg:space-x-5 xl:space-x-10 ">
                    <a href="/"><img src="/assets/image/desktop/<?php if ($x != 'black') echo 'logo.svg';
                                                                else echo 'logo2.svg'; ?>" alt="logo" /></a>
                    <div class="flex flex-row">
                        <ul class="flex lg:space-x-[20px] xl:space-x-[20px]">
                            <li><a href="/business" class="font-bold hover:text-[#24A556] text-[14px] underline <?php if ($x == 'black') echo 'text-[#010205]'; ?>">Business</a></li>
                            <li><a href="/pricing" class="font-medium hover:text-[#24A556] text-[14px] <?php if ($x == 'black') echo 'text-[#010205]'; ?>">Pricing</a></li>
                            <li><a href="/sites-we-cover" class="font-medium hover:text-[#24A556] text-[14px] <?php if ($x == 'black') echo 'text-[#010205]'; ?>">Sites We Cover</a></li>
                            <li><a href="/personalized-service" class="font-medium hover:text-[#24A556] text-[14px] <?php if ($x == 'black') echo 'text-[#010205]'; ?>">Personalized Service</a></li>
                            <li><a href="/family" class="font-medium hover:text-[#24A556] text-[14px] <?php if ($x == 'black') echo 'text-[#010205]'; ?>">Family</a></li>
                            <li class="relative group flex items-center">
                                <a href="#" class="font-medium text-[14px] block flex items-center space-x-1 <?php if ($x == 'black') echo 'text-[#010205]'; ?> group-hover:text-[#24A556]">
                                    <span>Resources</span>
                                    <svg class="w-3 h-3 transition-transform duration-200 group-hover:rotate-180 <?php if ($x == 'black') echo 'text-[#010205]'; ?> group-hover:text-[#24A556]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </a>

                                <div>
                                    <ul class=" absolute right-0 mt-[60px] w-[287px] p-[24px] bg-white/10 backdrop-blur-md rounded-[30px] <?php if ($x == 'black') echo 'text-[#010205]'; ?> shadow-2xl invisible group-hover:visible  transition-all duration-300 z-50 space-y-[16px]">
                                        <li><a href="https://tawk.to/chat/6813761a7c6684190de59a7c/1iq60amh0" target="_blank" class="block text-[16px] font-semibold hover:text-[#24A556]">Help Desk</a></li>
                                        <!-- <li><a href="/blog" class="block text-[16px] font-semibold hover:text-[#24A556]">Blog</a></li>
                                        <li><a href="#" class="block text-[16px] font-semibold hover:text-[#24A556]">Data Broker Directory</a></li>
                                        <li><a href="#" class="block text-[16px] font-semibold hover:text-[#24A556]">For High-Risk Communication</a></li>
                                        <li><a href="#" class="block text-[16px] font-semibold hover:text-[#24A556]">About Us</a></li>
                                        <li><a href="#" class="block text-[16px] font-semibold hover:text-[#24A556]">OPT OUT Guides</a></li>
                                        <li><a href="#" class="block text-[16px] font-semibold hover:text-[#24A556]">Product Updates</a></li> -->
                                        <!-- <li><a href="https://tawk.to/chat/6813761a7c6684190de59a7c/1iq60amh0" target="_blank" class="block text-[16px] font-semibold hover:text-[#24A556]">Customer Reviews</a></li> -->
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="flex flex-row items-center gap-[9px]">
                    <!-- <div class="flex items-center gap-[8px]">
                        <?php include(BASEPATH . "/src/common/svgs/dashboard/menu/phone.php"); ?>
                        <a href="tel:+17754433727" class="text-[12px] <?php if ($x == 'black') echo 'text-[#010205]'; ?> font-medium">+1&nbsp;&nbsp;775&nbsp; 443&nbsp; 3727</a>
                    </div> -->
                    <a href="/login" class="font-semibold text-[14px] px-[37px] py-[13px] <?php if ($x == 'black') echo 'text-[#010205]'; ?> hover:text-[#24A556]">Log In</a>
                    <a href="/signup" class="font-semibold text-[14px] bg-gradient-to-r from-[#77B248] to-[#24A556] shadow-[0px_4px_4px_0px_#24A5561A] hover:bg-green-700 rounded-full text-white px-[35px] py-[13px] transition-all duration-200">Get
                        Started</a>
                </div>
            </div>
        </div>

        <div
            class="text-white fixed top-0 left-0 w-full h-screen bg-white/10 backdrop-blur-xl z-30 opacity-0 invisible peer-checked:opacity-100 peer-checked:visible transition-opacity duration-300">
            <div class="py-[24px] w-full h-[96px]">
                <div class="px-[24px] flex justify-between items-center">
                    <!-- <img src="/assets/image/desktop/<?php if ($x == 'black') echo 'duck.svg';
                                                            else echo 'duck_hover.svg'; ?>" alt="duck_hover" /> -->
                    <img src="/assets/image/desktop/duck.svg" alt="duck_hover" />
                    <label for="menu-toggle" class="cursor-pointer">
                        <img src="/assets/image/mobile/<?php if ($x == 'black') echo 'hugeicons_cancel_black.svg';
                                                        else echo 'hugeicons_cancel.svg'; ?>" class="w-[24px] h-[24px]" alt="menu">
                    </label>
                </div>
            </div>

            <!-- Menu Content -->
            <div
                class="flex flex-col justify-between min-h-[calc(100%-126px)] space-y-4 text-lg font-medium py-[20px] px-[24px]">
                <div class="flex flex-col space-y-4">
                    <a href="/pricing" class="<?php if ($x == 'black') echo 'text-[#010205]'; ?> hover:text-[#24A556] text-[#010205]">Pricing</a>
                    <a href="/sites-we-cover" class="<?php if ($x == 'black') echo 'text-[#010205]'; ?> hover:text-[#24A556] text-[#010205]">Sites We Cover</a>
                    <a href="/personalized-service" class="<?php if ($x == 'black') echo 'text-[#010205]'; ?> hover:text-[#24A556] text-[#010205]">Personalized Service</a>
                    <a href="/business" class="font-bold <?php if ($x == 'black') echo 'text-[#010205]'; ?> hover:text-[#24A556] text-[#010205] underline">Business</a>
                    <a href="/family" class="<?php if ($x == 'black') echo 'text-[#010205]'; ?> hover:text-[#24A556] text-[#010205]">Family</a>
                    <div class="relative group space-y-4">
                        <a href="#" class="font-medium block flex items-center space-x-1 <?php if ($x == 'black') echo 'text-[#010205]'; ?> group-hover:text-[#24A556] text-[#010205]">
                            <span class="text-[18px]">Resources</span>
                            <svg class="w-3 h-3 transition-transform duration-200 group-hover:rotate-180 group-hover:text-[#24A556] text-[#010205]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </a>

                        <ul class="<?php if ($x == 'black') echo 'text-[#010205]'; ?> opacity-0 group-hover:opacity-100  transition-all duration-300 z-50 space-y-4">
                            <a href="https://tawk.to/chat/6813761a7c6684190de59a7c/1iq60amh0" target="_blank" class="block text-[16px] font-semibold hover:text-[#24A556]">Help Desk</a>
                            <!-- <a href="/blog" class="block text-[16px] font-semibold hover:text-[#24A556]">Blog</a>
                            <a href="#" class="block text-[16px] font-semibold hover:text-[#24A556]">Data Broker Directory</a>
                            <a href="#" class="block text-[16px] font-semibold hover:text-[#24A556]">For High-Risk Communication</a>
                            <a href="#" class="block text-[16px] font-semibold hover:text-[#24A556]">About Us</a>
                            <a href="#" class="block text-[16px] font-semibold hover:text-[#24A556]">OPT OUT Guides</a>
                            <a href="#" class="block text-[16px] font-semibold hover:text-[#24A556]">Product Updates</a> -->
                            <!-- <a href="https://tawk.to/chat/6813761a7c6684190de59a7c/1iq60amh0" target="_blank" class="block text-[16px] font-semibold hover:text-[#24A556]">Customer Reviews</a> -->
                        </ul>
                    </div>
                </div>
                <div class="flex flex-col space-y-4 items-center w-full">

                    <a href="/login" class="<?php if ($x == 'black') echo 'text-[#010205]'; ?> hover:text-[#24A556]">Log In</a>
                    <a href="#" class="hover:text-gray-300 bg-[#24A556] rounded-full py-3 w-full text-center">Get
                        Started</a>
                </div>
            </div>
        </div>
    <?php }

function main_login_header($x)
{ ?>
        <input type="checkbox" id="menu-toggle" class="hidden peer" />
        <div class="fixed w-full h-[96px] bg-white/10 backdrop-blur-md z-20"></div>
        <div class="py-[24px] fixed w-full h-[96px]  z-20 text-white">
            <div class="px-[16px] flex justify-between items-center lg:hidden">
                <a href="/"><img src="/assets/image/desktop/duck.svg" alt="duck" /></a>
                <label for="menu-toggle" class="cursor-pointer">
                    <svg width="40" height="18" viewBox="0 0 40 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <line y1="1" x2="40" y2="1" stroke="#24A556" stroke-width="2" />
                        <line y1="9" x2="40" y2="9" stroke="#24A556" stroke-width="2" />
                        <line y1="17" x2="40" y2="17" stroke="#24A556" stroke-width="2" />
                    </svg>

                </label>
            </div>
            <div class="px-[10px] xl:px-[80px]  justify-between items-center hidden lg:flex">
                <div class="flex flex-row items-center lg:space-x-5 xl:space-x-10 ">
                    <a href="/"><img src="/assets/image/desktop/<?php if ($x != 'black') echo 'logo.svg';
                                                                else echo 'logo2.svg'; ?>" alt="logo" /></>
                        <div class="flex flex-row">
                            <ul class="flex lg:space-x-[20px] xl:space-x-10">
                                <li><a href="/business" class="hover:text-[#24A556] font-bold text-[14px] <?php if ($x == 'black') echo 'text-[#010205]'; ?> underline">Business</a></li>
                                <li><a href="/pricing" class="hover:text-[#24A556] font-medium text-[14px] <?php if ($x == 'black') echo 'text-[#010205]'; ?>">Pricing</a></li>
                                <li><a href="/sites-we-cover" class="hover:text-[#24A556] font-medium text-[14px] <?php if ($x == 'black') echo 'text-[#010205]'; ?>">Sites We Cover</a></li>
                                <li><a href="/personalized-service" class="hover:text-[#24A556] font-medium text-[14px] <?php if ($x == 'black') echo 'text-[#010205]'; ?>">Personalized Service</a></li>
                                <li><a href="/family" class="hover:text-[#24A556] font-medium text-[14px] <?php if ($x == 'black') echo 'text-[#010205]'; ?>">Family</a></li>
                                <li class="relative group flex items-center">
                                    <a href="#" class="font-medium text-[14px] block flex items-center space-x-1 <?php if ($x == 'black') echo 'text-[#010205]'; ?> group-hover:text-[#24A556]">
                                        <span>Resources</span>
                                        <svg class="w-3 h-3 transition-transform duration-200 group-hover:rotate-180 group-hover:text-[#24A556]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </a>
                                    <div>
                                        <ul class=" absolute right-0 mt-[60px] w-[287px] p-[24px] <?php if ($x == 'black') echo 'text-[#010205]'; ?> bg-white/10 backdrop-blur-md rounded-[30px]  shadow-2xl invisible group-hover:visible  transition-all duration-300 z-50 space-y-[16px]">
                                            <li><a href="https://tawk.to/chat/6813761a7c6684190de59a7c/1iq60amh0" target="_blank" class="block text-[16px] font-semibold hover:text-[#24A556]">Help Desk</a></li>
                                            <!-- <li><a href="/blog" class="block text-[16px] font-semibold hover:text-[#24A556]">Blog</a></li>
                                            <li><a href="#" class="block text-[16px] font-semibold hover:text-[#24A556]">Data Broker Directory</a></li>
                                            <li><a href="#" class="block text-[16px] font-semibold hover:text-[#24A556]">For High-Risk Communication</a></li>
                                            <li><a href="#" class="block text-[16px] font-semibold hover:text-[#24A556]">About Us</a></li>
                                            <li><a href="#" class="block text-[16px] font-semibold hover:text-[#24A556]">OPT OUT Guides</a></li>
                                            <li><a href="#" class="block text-[16px] font-semibold hover:text-[#24A556]">Product Updates</a></li> -->
                                            <!-- <li><a href="https://tawk.to/chat/6813761a7c6684190de59a7c/1iq60amh0" target="_blank" class="block text-[16px] font-semibold hover:text-[#24A556]">Customer Reviews</a></li> -->
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                </div>
                <div class="flex flex-row items-center gap-[12px]">
                    <img src="https://flagcdn.com/us.svg" alt="US Flag" width="32" height="32" style="border-radius: 2px;">
                    <a href="/dashboard" class="hover:bg-green-700 transition-all duration-200 font-semibold text-[14px] rounded-full text-white bg-[#24A556] px-10 py-2 flex items-center gap-[5px]">
                        Open Dashboard
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 12H19" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M12 5L19 12L12 19" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>

                </div>
            </div>
        </div>

        <div
            class="text-white fixed top-0 left-0 w-full h-screen bg-white/10 backdrop-blur-xl z-30 opacity-0 invisible peer-checked:opacity-100 peer-checked:visible transition-opacity duration-300 lg:hidden">
            <div class="py-[24px] w-full h-[96px] ">
                <div class="px-[24px] flex justify-between items-center ">
                    <!-- <img src="/assets/image/desktop/<?php if ($x == 'black') echo 'duck.svg';
                                                            else echo 'duck_hover.svg'; ?>" alt="duck_hover" /> -->
                    <img src="/assets/image/desktop/duck.svg" alt="duck_hover" />
                    <label for="menu-toggle" class="cursor-pointer">
                        <img src="/assets/image/mobile/<?php if ($x == 'black') echo 'hugeicons_cancel_black.svg';
                                                        else echo 'hugeicons_cancel.svg'; ?>" class="w-[24px] h-[24px]" alt="menu">
                    </label>
                </div>
            </div>

            <!-- Menu Content -->
            <div
                class="flex flex-col justify-between min-h-[calc(100%-126px)] space-y-4 text-lg font-medium py-[20px] px-[24px] lg:hidden">
                <div class="flex flex-col space-y-4">
                    <a href="/pricing" class="<?php if ($x == 'black') echo 'text-[#010205]'; ?> hover:text-[#24A556] text-[#010205]">Pricing</a>
                    <a href="/sites-we-cover" class="<?php if ($x == 'black') echo 'text-[#010205]'; ?> hover:text-[#24A556] text-[#010205]">Sites We Cover</a>
                    <a href="/personalized-service" class="<?php if ($x == 'black') echo 'text-[#010205]'; ?> hover:text-[#24A556] text-[#010205]">Personalized Service</a>
                    <a href="/business" class="font-bold <?php if ($x == 'black') echo 'text-[#010205]'; ?> hover:text-[#24A556] text-[#010205] underline">Business</a>
                    <a href="/family" class="<?php if ($x == 'black') echo 'text-[#010205]'; ?> hover:text-[#24A556] text-[#010205]">Family</a>
                    <div class="relative group space-y-4">
                        <a href="#" class="font-medium block flex items-center space-x-1 <?php if ($x == 'black') echo 'text-[#010205]'; ?> group-hover:text-[#24A556] text-[#010205]">
                            <span class="text-[18px]">Resources</span>
                            <svg class="w-3 h-3 transition-transform duration-200 <?php if ($x == 'black') echo 'text-[#010205]'; ?> group-hover:rotate-180 group-hover:text-[#24A556] text-[#010205]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </a>

                        <ul class="<?php if ($x == 'black') echo 'text-[#010205]'; ?> opacity-0 group-hover:opacity-100  transition-all duration-300 z-50 space-y-4">
                            <a href="https://tawk.to/chat/6813761a7c6684190de59a7c/1iq60amh0" target="_blank" class="block text-[16px] font-semibold hover:text-[#24A556]">Help Desk</a>
                            <!-- <a href="/blog" class="block text-[16px] font-semibold hover:text-[#24A556]">Blog</a>
                            <a href="#" class="block text-[16px] font-semibold hover:text-[#24A556]">Data Broker Directory</a>
                            <a href="#" class="block text-[16px] font-semibold hover:text-[#24A556]">For High-Risk Communication</a>
                            <a href="#" class="block text-[16px] font-semibold hover:text-[#24A556]">About Us</a>
                            <a href="#" class="block text-[16px] font-semibold hover:text-[#24A556]">OPT OUT Guides</a>
                            <a href="#" class="block text-[16px] font-semibold hover:text-[#24A556]">Product Updates</a> -->
                            <!-- <a href="https://tawk.to/chat/6813761a7c6684190de59a7c/1iq60amh0" target="_blank" class="block text-[16px] font-semibold hover:text-[#24A556]">Customer Reviews</a> -->
                        </ul>
                    </div>
                </div>
                <div class="flex justify-center items-center w-full gap-[12px]">
                    <img src="https://flagcdn.com/us.svg" alt="US Flag" width="32" height="32" style="border-radius: 2px;">
                    <a href="/dashboard" class="hover:text-gray-300 bg-[#24A556] rounded-full py-3 px-[16px] text-center flex items-center gap-[5px]">Open Dashboard
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 12H19" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M12 5L19 12L12 19" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    <?php }
function main_header($x = "white")
{
    if (isset($_SESSION["isAuthenticated"]) && $_SESSION["isAuthenticated"] == true) {
        main_login_header($x);
    } else {
        main_logout_header($x);
    }
}

function main_footer()
{
    include __DIR__ . '/../pages/Landing/footer_main_content.php';
    ?>
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
    </body>

    </html>
<?php }

function no_footer()
{
    $content = <<<EOT
                <script type="text/javascript">
                    var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
                    (function () {
                        var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
                        s1.async = true;
                        s1.src = 'https://embed.tawk.to/6813761a7c6684190de59a7c/1iq60amh0';
                        s1.charset = 'UTF-8';
                        s1.setAttribute('crossorigin', '*');
                        s0.parentNode.insertBefore(s1, s0);
                    })();
                </script>
            </body>
        </html>
    EOT;
    echo $content;
}


function isLogin()
{
    if (!isset($_SESSION["isAuthenticated"]) || $_SESSION["isAuthenticated"] !== true) {
        header("Location: " . WEB_DOMAIN);
        exit; // Don't put echo after this
    }
}
function isReverseLogin()
{
    if (isset($_SESSION["isAuthenticated"]) && $_SESSION["isAuthenticated"] == true) {
        header("Location: " . WEB_DOMAIN . "/dashboard");
        exit; // Don't put echo after this
    }
}
function isBusinessLogin()
{
    if (!isset($_SESSION["work_isAuthenticated"]) || $_SESSION["work_isAuthenticated"] !== true) {
        header("Location: " . WEB_DOMAIN . "/business/quote/login");
        exit; // Don't put echo after this
    }
}
function isBusinessReverseLogin()
{
    if (isset($_SESSION["work_isAuthenticated"]) && $_SESSION["work_isAuthenticated"] == true) {
        header("Location: " . WEB_DOMAIN . "/business/dashboard");
        exit; // Don't put echo after this
    }
}

function denyAccess($code = 403, $message = "Access denied.")
{
    http_response_code($code);
    echo json_encode(["error" => $message]);
    exit;
}

function main_splash()
{
    $content = <<<EOT
        <div id="splash-screen" class="fixed inset-0 z-50 flex items-center justify-center bg-white transition-opacity  ease-in-out">
            <div class="text-[40px] font-bold font-sans flex items-center space-x-2 animate-logo-loop">
                <span class="text-black opacity-0 animate-privacy-fade text-[#010205] text-[40px] sm:text-[70px] md:text-[100px] font-normal" style="font-family: 'Alatsi', sans-serif;">PRIVACY</span>
                <span class="text-[#24A556] opacity-0 animate-duck-fade text-[40px] sm:text-[70px] md:text-[100px] font-normal" style="font-family: 'Alatsi', sans-serif;">DUCK</span>
                <div class="relative w-[40px] h-[44px] sm:w-[82px] sm:h-[133px]">
                    <svg class="absolute opacity-0 animate-logo-fade w-[40px] h-[44px] sm:w-[120px] sm:h-[133px]" viewBox="0 0 82 133" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M74.5297 53.6452H55.7171C55.7171 53.6452 66.9144 62.6037 69.6019 88.583C72.2894 114.562 61.5393 128 61.5393 128L3.3098 68.4265C3.3098 68.4265 2.61275 43.7985 3.3098 33.4888C4.00686 23.1792 17.3334 3.37175 38.3856 3.01066C59.4378 2.64957 68.8441 11.5263 73.7713 19.5877C78.6984 27.6491 79.1463 32.1304 76.9067 32.5752" stroke="url(#paint0_linear_1010_10292)" stroke-width="6"/>
                        <defs>
                            <linearGradient id="paint0_linear_1010_10292" x1="11.9107" y1="-21.6356" x2="55.3589" y2="53.167" gradientUnits="userSpaceOnUse">
                            <stop offset="0.400858" stop-color="#77B248"/>
                            <stop offset="1" stop-color="#24A556"/>
                            </linearGradient>
                        </defs>
                    </svg>
                    <svg class="absolute left-[7px] sm:left-[18px] opacity-0 animate-logo1-fade  w-[40px] h-[44px] sm:w-[120px] sm:h-[133px]" viewBox="0 0 120 133" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M43.2174 53.1916L73.7272 53.6395M73.7272 53.6395H116.676C116.676 53.6395 116.442 48.7841 112.248 45.1291C108.055 41.474 81.7102 37.9547 74.135 19.5977C80.6012 35.2672 79.5245 44.3228 73.7272 53.6395Z" stroke="url(#paint0_linear_1010_10302)" stroke-width="6"/>
                        <path d="M74.5297 53.6452H55.7171C55.7171 53.6452 66.9144 62.6037 69.6019 88.583C72.2894 114.562 61.5393 128 61.5393 128L3.3098 68.4265C3.3098 68.4265 2.61275 43.7985 3.3098 33.4888C4.00686 23.1792 17.3334 3.37175 38.3856 3.01066C59.4378 2.64957 68.8441 11.5263 73.7713 19.5877C78.6984 27.6491 79.1463 32.1304 76.9067 32.5752" stroke="url(#paint1_linear_1010_10302)" stroke-width="6"/>
                        <defs>
                        <linearGradient id="paint0_linear_1010_10302" x1="116.724" y1="51.3754" x2="76.4111" y2="32.1149" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#77B248"/>
                        <stop offset="1" stop-color="#24A556"/>
                        </linearGradient>
                        <linearGradient id="paint1_linear_1010_10302" x1="11.9107" y1="-21.6356" x2="55.3589" y2="53.167" gradientUnits="userSpaceOnUse">
                        <stop offset="0.400858" stop-color="#77B248"/>
                        <stop offset="1" stop-color="#24A556"/>
                        </linearGradient>
                        </defs>
                    </svg>
                    <svg class="absolute left-[7px] sm:left-[18px] opacity-0 animate-logo2-fade  w-[40px] h-[44px] sm:w-[120px] sm:h-[133px]" viewBox="0 0 120 133" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M41.4739 25.396H47.7447" stroke="url(#paint0_linear_1010_10312)" stroke-width="3"/>
                        <path d="M55.7959 25.396H62.0795" stroke="url(#paint1_linear_1010_10312)" stroke-width="3"/>
                        <path d="M43.2174 53.1916L73.7272 53.6395M73.7272 53.6395H116.676C116.676 53.6395 116.442 48.7841 112.248 45.1291C108.055 41.474 81.7102 37.9547 74.135 19.5977C80.6012 35.2672 79.5245 44.3228 73.7272 53.6395Z" stroke="url(#paint2_linear_1010_10312)" stroke-width="6"/>
                        <path d="M84.4741 39.7295L87.6096 42.417" stroke="url(#paint3_linear_1010_10312)" stroke-width="3"/>
                        <circle cx="51.6858" cy="24.9727" r="4.37503" stroke="url(#paint4_linear_1010_10312)" stroke-width="2"/>
                        <circle cx="52.1338" cy="22.7328" r="1.6146" stroke="url(#paint5_linear_1010_10312)" stroke-width="1.25"/>
                        <path d="M74.5297 53.6452H55.7171C55.7171 53.6452 66.9144 62.6037 69.6019 88.583C72.2894 114.562 61.5393 128 61.5393 128L3.3098 68.4265C3.3098 68.4265 2.61275 43.7985 3.3098 33.4888C4.00686 23.1792 17.3334 3.37175 38.3856 3.01066C59.4378 2.64957 68.8441 11.5263 73.7713 19.5877C78.6984 27.6491 79.1463 32.1304 76.9067 32.5752" stroke="url(#paint6_linear_1010_10312)" stroke-width="6"/>
                        <defs>
                        <linearGradient id="paint0_linear_1010_10312" x1="44.6093" y1="25.396" x2="44.6093" y2="26.396" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#77B248"/>
                        <stop offset="1" stop-color="#24A556"/>
                        </linearGradient>
                        <linearGradient id="paint1_linear_1010_10312" x1="58.9377" y1="25.396" x2="58.9377" y2="26.396" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#77B248"/>
                        <stop offset="1" stop-color="#24A556"/>
                        </linearGradient>
                        <linearGradient id="paint2_linear_1010_10312" x1="116.724" y1="51.3754" x2="76.4111" y2="32.1149" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#77B248"/>
                        <stop offset="1" stop-color="#24A556"/>
                        </linearGradient>
                        <linearGradient id="paint3_linear_1010_10312" x1="86.0418" y1="39.7295" x2="86.0418" y2="42.417" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#77B248"/>
                        <stop offset="1" stop-color="#24A556"/>
                        </linearGradient>
                        <linearGradient id="paint4_linear_1010_10312" x1="51.6858" y1="19.5977" x2="51.6858" y2="30.3477" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#77B248"/>
                        <stop offset="1" stop-color="#24A556"/>
                        </linearGradient>
                        <linearGradient id="paint5_linear_1010_10312" x1="52.1338" y1="20.4932" x2="52.1338" y2="24.9724" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#77B248"/>
                        <stop offset="1" stop-color="#24A556"/>
                        </linearGradient>
                        <linearGradient id="paint6_linear_1010_10312" x1="11.9107" y1="-21.6356" x2="55.3589" y2="53.167" gradientUnits="userSpaceOnUse">
                        <stop offset="0.400858" stop-color="#77B248"/>
                        <stop offset="1" stop-color="#24A556"/>
                        </linearGradient>
                        </defs>
                    </svg>


                </div>
            </div>
        </div>
        <script>
            window.addEventListener("load", () => {
                const splash = document.getElementById("splash-screen");
                
                setTimeout(() => {
                    splash.classList.add("opacity-0");
                    setTimeout(() => {
                        splash.style.display = "none";
                    }, 1000);
                }, 1000); // Match transition duration
            });
        </script>
    EOT;
    echo $content;
}
function common_splash()
{
    $content = <<<EOT
        <div id="splash-screen" class="fixed inset-0 z-50 flex items-center justify-center bg-white transition-opacity  ease-in-out">
            <img src="/assets/image/desktop/duck.svg" class="animate-bounce w-[150px] h-[150px]" />
        </div>
        <script>
            window.addEventListener("load", () => {
                const splash = document.getElementById("splash-screen");
                
                setTimeout(() => {
                    splash.classList.add("opacity-0");
                    setTimeout(() => {
                        splash.style.display = "none";
                    }, 1000);
                }, 1000); // Match transition duration
            });
        </script>
    EOT;
    echo $content;
}

function business_header($x = "white")
{ ?>
    <input type="checkbox" id="menu-toggle" class="hidden peer" />
    <div class="absolute w-full h-[70px] lg:h-[140px] bg-white/10 backdrop-blur-md z-20"></div>
    <div class="h-[70px] lg:h-[140px] px-[16px] lg:px-[80px] lg:flex lg:flex-col absolute w-full z-20">
        <div class="flex justify-between items-center lg:hidden h-full">
            <a href="/business"><img class="w-[171px] h-[48px]" src="/assets/image/desktop/business/landing/mobile_duck.png" alt="duck" /></a>
            <label for="menu-toggle" class="cursor-pointer">
                <svg width="40" height="18" viewBox="0 0 40 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <line y1="1" x2="40" y2="1" stroke="#FFCF50" stroke-width="2" />
                    <line y1="9" x2="40" y2="9" stroke="#FFCF50" stroke-width="2" />
                    <line y1="17" x2="40" y2="17" stroke="#FFCF50" stroke-width="2" />
                </svg>
            </label>
        </div>
        <div class="h-[46px] hidden lg:flex items-center justify-between border-b-[1px] border-[#ADADAD]">
            <a href="/">
                <h2 class="text-[12px] font-medium text-white">For consumers</h2>
            </a>
            <div class="flex items-center gap-[12px] text-[12px] font-medium text-white">
                <div class="flex items-center gap-[7px]">
                    <h2>Experiencing an Urgent Risk?</h2>
                    <button class="flex justify-center items-center w-[138px] h-[26px] text-[12px] font-semibold tracking-[-0.01em] text-[#FFCF50] border border-[#FFCF50] rounded-full">Incident Response</button>
                </div>
                <a href="/speaksales">
                    <h2>Speak With Sales</h2>
                </a>
                <a href="/login">
                    <h2>Customer Login</h2>
                </a>
            </div>

        </div>
        <div class=" hidden lg:flex flex-1 justify-between items-center">
            <div class="flex items-center">
                <a href='/business'>
                    <div class="relative flex flex-col">
                        <div class="flex items-center gap-[8px]">
                            <h2 style="font-family: 'Alatsi', sans-serif;" class="text-[28px] tracking-[-0.02em] uppercase text-white">Privacy<label class="text-[#FFCF50]">Duck</label></h2>
                            <?php require(BASEPATH . '/src/common/svgs/business/landing/duck.php'); ?>
                        </div>
                        <h2 style="font-family: 'Alatsi', sans-serif;" class="relative top-[-10px] text-[20px] tracking-[-0.02em] text-white">BUSINESS</h2>
                    </div>
                </a>
                <!-- <div class="flex flex-row ml-[80px]">
                    <ul class="flex lg:space-x-4 xl:space-x-10">
                        <li><a href="/api" class="font-medium hover:text-[#FFCF50] text-[18px] <?php if ($x == 'black') echo 'text-[#010205]'; ?>">API</a></li>
                        <li class="relative group flex items-center">
                            <a href="#" class="font-medium text-[18px] block flex items-center space-x-1 <?php if ($x == 'black') echo 'text-[#010205]'; ?> group-hover:text-[#FFCF50]">
                                <span>APPS</span>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="group-hover:rotate-180 group-hover:text-[#FFCF50]">
                                    <g clip-path="url(#clip0_2216_5774)">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12.7064 15.7064C12.5188 15.8938 12.2645 15.9992 11.9994 15.9992C11.7342 15.9992 11.4799 15.8938 11.2924 15.7064L5.63537 10.0494C5.53986 9.95712 5.46367 9.84678 5.41126 9.72477C5.35886 9.60277 5.33127 9.47155 5.33012 9.33877C5.32896 9.20599 5.35426 9.07431 5.40454 8.95141C5.45483 8.82852 5.52908 8.71686 5.62297 8.62297C5.71686 8.52908 5.82852 8.45483 5.95141 8.40454C6.07431 8.35426 6.20599 8.32896 6.33877 8.33012C6.47155 8.33127 6.60277 8.35886 6.72477 8.41126C6.84677 8.46367 6.95712 8.53986 7.04937 8.63537L11.9994 13.5854L16.9494 8.63537C17.138 8.45321 17.3906 8.35241 17.6528 8.35469C17.915 8.35697 18.1658 8.46214 18.3512 8.64755C18.5366 8.83296 18.6418 9.08377 18.644 9.34597C18.6463 9.60816 18.5455 9.86076 18.3634 10.0494L12.7064 15.7064Z" fill="white" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_2216_5774">
                                            <rect width="24" height="24" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>

                            </a>

                            <div>
                                <ul class=" absolute right-0 mt-[60px] w-[287px] p-[24px] bg-white/10 backdrop-blur-md rounded-[30px] <?php if ($x == 'black') echo 'text-[#010205]'; ?> shadow-2xl invisible group-hover:visible  transition-all duration-300 z-50 space-y-[16px]">
                                    <?php
                                    $datas = [
                                        ["title" => "Privacy Request", "link" => "/"],
                                        ["title" => "Zero Request", "link" => "/"],
                                        ["title" => "Trust Seal", "link" => "/"],
                                        ["title" => "Privacy Feed", "link" => "/"],
                                        ["title" => "Company Risk Analysis", "link" => "/"],
                                        ["title" => "Email Unsubscribe", "link" => "/"]
                                    ];
                                    foreach ($datas as $data) {
                                    ?>
                                        <li><a href="<?php echo $data["link"]; ?>" class="block tracking-[-0.01em] text-[16px] font-semibold hover:text-[#FFCF50]"><?php echo $data["title"]; ?></a></li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                        </li>
                        <li class="relative group flex items-center">
                            <a href="#" class="font-medium text-[18px] block flex items-center space-x-1 <?php if ($x == 'black') echo 'text-[#010205]'; ?> group-hover:text-[#FFCF50]">
                                <span>CASES</span>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="group-hover:rotate-180 group-hover:text-[#FFCF50]">
                                    <g clip-path="url(#clip0_2216_5774)">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12.7064 15.7064C12.5188 15.8938 12.2645 15.9992 11.9994 15.9992C11.7342 15.9992 11.4799 15.8938 11.2924 15.7064L5.63537 10.0494C5.53986 9.95712 5.46367 9.84678 5.41126 9.72477C5.35886 9.60277 5.33127 9.47155 5.33012 9.33877C5.32896 9.20599 5.35426 9.07431 5.40454 8.95141C5.45483 8.82852 5.52908 8.71686 5.62297 8.62297C5.71686 8.52908 5.82852 8.45483 5.95141 8.40454C6.07431 8.35426 6.20599 8.32896 6.33877 8.33012C6.47155 8.33127 6.60277 8.35886 6.72477 8.41126C6.84677 8.46367 6.95712 8.53986 7.04937 8.63537L11.9994 13.5854L16.9494 8.63537C17.138 8.45321 17.3906 8.35241 17.6528 8.35469C17.915 8.35697 18.1658 8.46214 18.3512 8.64755C18.5366 8.83296 18.6418 9.08377 18.644 9.34597C18.6463 9.60816 18.5455 9.86076 18.3634 10.0494L12.7064 15.7064Z" fill="white" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_2216_5774">
                                            <rect width="24" height="24" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>

                            </a>

                            <div>
                                <ul class=" absolute right-[-266px] mt-[60px] w-[532px] p-[24px] bg-white/10 backdrop-blur-md rounded-[30px] <?php if ($x == 'black') echo 'text-[#010205]'; ?> shadow-2xl invisible group-hover:visible  transition-all duration-300 z-50 space-y-[16px]">
                                    <?php
                                    $datas = [
                                        ["title" => "Social Engineering Prevention", "link" => "/"],
                                        ["title" => "Spear-phishing/Whaling Prevention", "link" => "/"],
                                        ["title" => "Smishing & Vishing Risk Mitigation", "link" => "/"],
                                        ["title" => "Credential Theft & Account Takeover Prevention", "link" => "/"],
                                        ["title" => "Attack Surface Management", "link" => "/"],
                                        ["title" => "Employee Privacy Protection", "link" => "/"],
                                        ["title" => "Identity Theft Risk Reduction", "link" => "/"],
                                        ["title" => "Doxing, Harassment, & Physical Threat Risk Mitigation", "link" => "/"],
                                        ["title" => "Cyber Attack Prevention/ Defensive Cyber Counterintelligence", "link" => "/"],
                                        ["title" => "Insider Threat Prevention", "link" => "/"],
                                        ["title" => "Protection Against Business Email Compromise (BEC)", "link" => "/"],
                                        ["title" => "Reducing Spam and Targeted Advertising", "link" => "/"]
                                    ];
                                    foreach ($datas as $data) {
                                    ?>
                                        <li><a href="<?php echo $data["link"]; ?>" class="block text-[16px] font-semibold hover:text-[#FFCF50] tracking-[-0.01em]"><?php echo $data["title"]; ?></a></li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div> -->
            </div>
            <a class="cursor-pointer w-[202px] h-[48px] flex justify-center items-center rounded-full bg-[#00530F] shadow-[0px_4px_4px_0px_#24A5561A] font-bold text-[16px] leading-[140%] tracking-[-0.02em] text-[#FFCF50]"
                style="font-family: 'Manrope', sans-serif;" href="/business/quote/login">Enterprise Login</a>
        </div>
    </div>

    <div
        class="text-white fixed top-0 left-0 w-full h-screen bg-white/10 backdrop-blur-xl z-30 opacity-0 invisible peer-checked:opacity-100 peer-checked:visible transition-opacity duration-300 lg:hidden">
        <div class="py-[24px] w-full h-[96px] ">
            <div class="px-[24px] flex justify-between items-center ">
                <a href="/business"><img class="w-[171px] h-[48px]" src="/assets/image/desktop/business/landing/mobile_duck.png" alt="duck" /></a>
                <label for="menu-toggle" class="cursor-pointer">
                    <?php require(BASEPATH . "/src/common/svgs/business/landing/cancel.php"); ?>
                </label>
            </div>
        </div>
        <!-- Menu Content -->
        <div
            class="flex flex-col min-h-[calc(100%-126px)] space-y-4 text-lg font-semibold py-[20px] px-[16px] lg:hidden">
            <div class="flex flex-col space-y-4">
                <!-- <a href="/api" class="<?php if ($x == 'black') echo 'text-[#010205]'; ?> hover:text-[#FFCF50]">API</a>
                <div class="relative group space-y-4">
                    <a href="#" class="font-semibold block flex items-center space-x-1 <?php if ($x == 'black') echo 'text-[#010205]'; ?> group-hover:text-[#FFCF50]">
                        <span class="text-[18px]">APPS</span>
                        <svg class="w-[24px] h-[24px] transition-transform duration-200 <?php if ($x == 'black') echo 'text-[#010205]'; ?> group-hover:rotate-180 group-hover:text-[#FFCF50]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </a>

                    <ul class="<?php if ($x == 'black') echo 'text-[#010205]'; ?> hidden group-hover:block  transition-all duration-300 z-50 space-y-4">
                        <?php
                        $datas = [
                            ["title" => "Privacy Request", "link" => "/"],
                            ["title" => "Zero Request", "link" => "/"],
                            ["title" => "Trust Seal", "link" => "/"],
                            ["title" => "Privacy Feed", "link" => "/"],
                            ["title" => "Company Risk Analysis", "link" => "/"],
                            ["title" => "Email Unsubscribe", "link" => "/"]
                        ];
                        foreach ($datas as $data) {
                        ?>
                            <a href="<?php echo $data["link"]; ?>" class="block tracking-[-0.01em] text-[16px] font-semibold hover:text-[#FFCF50]"><?php echo $data["title"]; ?></a>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
                <div class="relative group space-y-4">
                    <a href="#" class="font-semibold block flex items-center space-x-1 <?php if ($x == 'black') echo 'text-[#010205]'; ?> group-hover:text-[#FFCF50]">
                        <span class="text-[18px]">CASES</span>
                        <svg class="w-[24px] h-[24px] transition-transform duration-200 <?php if ($x == 'black') echo 'text-[#010205]'; ?> group-hover:rotate-180 group-hover:text-[#FFCF50]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </a>

                    <ul class="<?php if ($x == 'black') echo 'text-[#010205]'; ?> hidden group-hover:block  transition-all duration-300 z-50 space-y-4">
                        <?php
                        $datas = [
                            ["title" => "Social Engineering Prevention", "link" => "/"],
                            ["title" => "Spear-phishing/Whaling Prevention", "link" => "/"],
                            ["title" => "Smishing & Vishing Risk Mitigation", "link" => "/"],
                            ["title" => "Credential Theft & Account Takeover Prevention", "link" => "/"],
                            ["title" => "Attack Surface Management", "link" => "/"],
                            ["title" => "Employee Privacy Protection", "link" => "/"],
                            ["title" => "Identity Theft Risk Reduction", "link" => "/"],
                            ["title" => "Doxing, Harassment, & Physical Threat Risk Mitigation", "link" => "/"],
                            ["title" => "Cyber Attack Prevention/ Defensive Cyber Counterintelligence", "link" => "/"],
                            ["title" => "Insider Threat Prevention", "link" => "/"],
                            ["title" => "Protection Against Business Email Compromise (BEC)", "link" => "/"],
                            ["title" => "Reducing Spam and Targeted Advertising", "link" => "/"]
                        ];
                        foreach ($datas as $data) {
                        ?>
                            <a href="<?php echo $data["link"]; ?>" class="block tracking-[-0.01em] text-[16px] font-semibold hover:text-[#FFCF50]"><?php echo $data["title"]; ?></a>
                        <?php
                        }
                        ?>
                    </ul>
                </div> -->
                <a href="/" class="text-[#FFCF50]">For Consumers</a>
                <a href="/speaksales" class="text-[#FFCF50]">Speak With Sales</a>
                <a href="/login" class="text-[#FFCF50]">Customer Login</a>
            </div>
            <div class="flex justify-center items-center w-full">
                <a href="business/quote/signup" class="cursor-pointer w-full max-w-[330px] h-[56px] flex justify-center items-center rounded-full 
                bg-[#00530F] shadow-[0px_4px_4px_0px_#24A5561A] uppercase font-bold text-[16px] leading-[140%] 
                tracking-[-0.02em] text-[#FFCF50]" style="font-family: 'Manrope', sans-serif;">Enterprise Login</a>
            </div>
        </div>
    </div>
<?php }

function main_business_footer()
{
?>
    <div class="pt-[75px] pb-[37px] bg-[#0F3812]">
        <div class="flex justify-center gap-[98px]">
            <a href='/business'>
                <div class="relative flex flex-col">
                    <div class="flex items-center gap-[8px]">
                        <h2 style="font-family: 'Alatsi', sans-serif;" class="text-[28px] tracking-[-0.02em] uppercase text-white">Privacy<label class="text-[#FFCF50]">Duck</label></h2>
                        <?php require(BASEPATH . '/src/common/svgs/business/landing/duck.php'); ?>
                    </div>
                    <h2 style="font-family: 'Alatsi', sans-serif;" class="relative top-[-10px] text-[20px] tracking-[-0.02em] text-white">BUSINESS</h2>
                </div>
            </a>
            <div>
                <h2 class="text-[16px] uppercase text-white tracking-[-0.02em]">Top privacy apps</h2>
                <div class="mt-[35px] flex flex-col gap-[16px]">
                    <?php
                    $datas = ["Privacy Request", "Zero Request", "Privacy Trust Seal", "Privacy Feed", "Company Risk Analysis", "Email Unsubscribe"];
                    foreach ($datas as $data) {
                    ?>
                        <h2 class="tracking-[-0.01em] text-[12px] text-[#9B9B9C]"><?php echo $data; ?></h2>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div>
                <h2 class="text-[16px] uppercase text-white tracking-[-0.02em]">CASES</h2>
                <div class="mt-[35px]  flex gap-[31px]">
                    <div class="flex flex-col gap-[16px]">
                        <?php
                        $datas = [
                            "Social Engineering Prevention",
                            "Spear-phishing/Whaling Prevention",
                            "Smishing & Vishing Risk Mitigation",
                            "Credential Theft & Account Takeover Prevention",
                            "Attack Surface Management",
                            "Employee Privacy Protection",

                        ];
                        foreach ($datas as $data) {
                        ?>
                            <h2 class="tracking-[-0.01em] text-[12px] text-[#9B9B9C]"><?php echo $data; ?></h2>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="flex flex-col gap-[16px]">
                        <?php
                        $datas = [
                            "Identity Theft Risk Reduction",
                            "Doxing, Harassment, & Physical Threat Risk Mitigation",
                            "Cyber Attack Prevention/ Defensive Cyber Counterintelligence",
                            "Insider Threat Prevention",
                            "Protection Against Business Email Compromise (BEC)",
                            "Reducing Spam and Targeted Advertising"
                        ];
                        foreach ($datas as $data) {
                        ?>
                            <h2 class="tracking-[-0.01em] text-[12px] text-[#9B9B9C]"><?php echo $data; ?></h2>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-[48px] flex flex-col items-center">
            <h2 class="text-[#9B9B9C] text-[14px]">
                Copyright © 2025 Privacy Duck &nbsp;&nbsp;•&nbsp;&nbsp; Privacy Policy &nbsp;&nbsp;•&nbsp;&nbsp; Terms of Services.
            </h2>
            <h2 class="text-[#9B9B9C] text-[14px]">
                2972 Westheimer Rd. Santa Ana, Illinois 85486
            </h2>
        </div>
    </div>
<?php } ?>
