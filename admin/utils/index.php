<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function main_head_start()
{ ?>
    <meta charset="UTF-8">
    <meta name='keywords' content='privacyduck.com'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <link href="/admin/assets/css/index.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Alatsi&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css' rel='stylesheet' />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link href="https://unpkg.com/tabulator-tables@5.5.0/dist/css/tabulator.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/paginationjs@2/dist/pagination.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src='https://cdn.tailwindcss.com'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/@googlemaps/extended-component-library/0.6.11/index.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://unpkg.com/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/paginationjs@2/dist/pagination.min.js"></script>

    <link rel="icon" type="image/png" href="/admin/assets/favicon.png">
    <?php if (isset($_SESSION['isAuthenticated']) && $_SESSION['isAuthenticated'] == true) {
    ?>
        <script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
    <?php
    } ?>
    <script>
        window.loadingHtml = "<img src='/assets/image/desktop/loading1.webp' class='w-6 h-6 flex mr-2'> <span class='font-semibold text-[12px] leading-[130%] tracking-[-0.02em]'>Saving...</span>";
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

        function showError(message) {
            Toastify({
                text: message,
                duration: 4000,
                gravity: "right",
                position: "right",
                className: "error",
                stopOnFocus: true,
                style: {
                    background: "rgba(220, 38, 38, 0.9)",
                    borderRadius: "12px",
                    backdropFilter: "blur(16px)",
                    color: "white",
                    minWidth: "300px",
                    fontWeight: "500",
                }
            }).showToast();
        }
        function showWarning(message) {
            Toastify({
                text: message,
                duration: 4000,
                gravity: "right",
                position: "right",
                className: "warning",
                stopOnFocus: true,
                style: {
                    background: "rgba(181, 220, 38, 0.9)",
                    borderRadius: "12px",
                    backdropFilter: "blur(16px)",
                    color: "white",
                    minWidth: "300px",
                    fontWeight: "500",
                }
            }).showToast();
        }

        function showSuccess(message) {
            Toastify({
                text: message,
                duration: 3000,
                gravity: "right",
                position: "right",
                className: "success",
                stopOnFocus: true,
                style: {
                    background: "rgba(34, 197, 94, 0.9)",
                    borderRadius: "12px",
                    backdropFilter: "blur(16px)",
                    color: "white",
                    minWidth: "300px",
                    fontWeight: "500",
                }
            }).showToast();
        }

        <?php if (isset($_SESSION['isAuthenticated']) && $_SESSION['isAuthenticated'] == true) {
        ?>
            // const socket = io('https://sayloapp.com');

            // socket.on('connect', () => {
            //     socket.emit('join', '<?= $_SESSION['user_id'] ?>');
            // });

            // socket.on('disconnect', () => {
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
        <div>Logout</div>
    <?php }

function main_login_header($x)
{ ?>
        <div>
            Login
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
{ ?>
    </body>

    </html>
<?php }

function isLogin()
{
    if (!isset($_SESSION["admin"]) || !$_SESSION["admin"]["isAdminAuthenticated"]) {
        header("Location: " . WEB_DOMAIN . "/super/admin/login");
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
