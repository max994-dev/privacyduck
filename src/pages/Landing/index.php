<?php
$meta_title = "Remove Personal Information Online | PrivacyDuck";
$meta_description = "Erase your digital footprint. PrivacyDuck removes your personal info from Google, people search sites & 500+ data brokers. Try a free scan today.";
$meta_url = "https://privacyduck.com/";
$meta_keywords = "remove employee data, employee privacy protection, delete employee info from google, business data removal, executive privacy";
include_once(BASEPATH . "/src/common/meta.php");
main_head_start();
?>
<link rel="preload" as="image" href="/assets/image/desktop/background.png">
<link rel="preload" as="image" href="/assets/image/mobile/background.png">

<link href="/assets/css/landing.css" rel="stylesheet">
<link href="/assets/css/landingMobileAnimation.css" rel="stylesheet">
<style>
    .call-button {
        position: fixed;
        bottom: 120px;
        /* distance from bottom */
        right: 30px;
        /* distance from right */
        background-color: rgb(255, 255, 255);
        /* green call button */
        color: white;
        font-size: 20px;
        /* icon size */
        padding: 12px;
        border-radius: 50%;
        /* makes it circular */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        text-decoration: none;
        z-index: 1000;
        /* stays above all content */
        transition: background-color 0.2s ease;
    }

    .call-button:hover {
        background-color: #218838;
        /* darker green on hover */
    }

    @media (min-width: 768px) {
        .call-button {
            font-size: 28px;
            padding: 16px;
            bottom: 200px;
            right: 30px;
        }
    }
</style>
<?php
main_head_end();
?>
<!-- Floating Phone Call Button -->
<!-- <a href="tel:+17754433727" class="call-button" aria-label="Call Us">
    📞
</a> -->

<div id="white-header">
    <?php
    main_header();
    ?>
</div>
<div id="black-header" class="hidden">
    <?php
    main_header("black");
    ?>
</div>
<?php
// main_splash();
?>
<div class="landing-section" data-header="white" class="intro">
    <?php
    require("intro.php"); ?>
</div>
<div class="flex flex-col text-[#010205] bg-[#FAFAFA]">
    <div class="landing-section" data-header="dark">
        <?php
        require("scroll_animation.php");
        ?>
    </div>
    <div class="landing-section" data-header="dark">
        <?php
        require("ultimate.php");
        ?>
    </div>
    <div class="landing-section" data-header="white">
        <?php
        require("digital2.php");
        ?>
    </div>
    <div class="landing-section" data-header="dark">
        <?php
        require("hastle.php");
        ?>
    </div>
    <div class="landing-section" data-header="white">
        <?php
        require("journey.php");
        ?>
    </div>
    <div class="landing-section" data-header="dark">
        <?php
        require("mobile_animation.php");
        ?>
    </div>
    <div class="landing-section" data-header="dark">
        <?php
        require("testimonial.php");
        ?>
    </div>
    <div class="landing-section" data-header="dark">
        <?php
        require("faq.php");
        ?>
    </div>
    <div class="landing-section" data-header="white">
        <?php
        require("digital.php"); ?>
    </div>
</div>
<?php include($_SERVER["DOCUMENT_ROOT"] . "/src/pages/Landing/inbound.php"); ?>
<div class="landing-section" data-header="dark">
    <?php
    main_footer();
    ?>
</div>
<script>
    function landing_init() {
        const buttonCouple = {
            "but1": 0,
            "but2": 1,
            "but3": 2,
            "but4": 3,
            "but5": 4,
            "but6": 5,
        }

        const butSize = [152, 142, 135, 135, 95, 126, ]
        const cards = document.querySelectorAll('.timeline-card');
        const fill = document.getElementById('timeline-fill');
        const duck = document.getElementById('duck');
        let timeline_init = false

        let visibleIndexes = new Set();

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                const card = entry.target;
                const index = parseInt(card.getAttribute('data-index'));
                const cardBottom = entry.boundingClientRect.bottom;

                const viewportHeight = window.innerHeight;

                const isCardVisible = cardBottom < viewportHeight;
                if (cardBottom >= 0 && !timeline_init) {
                    timeline_init = true;
                }
                if (timeline_init) {
                    if (isCardVisible) {
                        visibleIndexes.add(index);
                        card.classList.remove('opacity-10');
                        card.classList.add('opacity-100');
                    } else {
                        visibleIndexes.delete(index);
                        card.classList.remove('opacity-100');
                        card.classList.add('opacity-10');
                    }
                }

                const maxIndex = Math.max(...[...visibleIndexes], -1);
                if (maxIndex >= 0) {
                    const lastCard = document.querySelector(`.timeline-card[data-index="${maxIndex}"]`);
                    const bottom = lastCard.getBoundingClientRect().bottom + window.scrollY;
                    const timelineTop = fill.parentElement.getBoundingClientRect().top + window.scrollY;
                    fill.style.height = `${bottom - timelineTop + 15}px`;
                    duck.style.top = `${bottom - timelineTop}px`;
                } else {
                    duck.style.top = '0px';
                    fill.style.height = '15px';
                }
            });
        }, {
            threshold: 1 // Adjust sensitivity
        });
        cards.forEach(card => observer.observe(card));



        const mobile_cube = document.getElementById('mobile_cube');
        const faces = {
            front: mobile_cube.querySelector('.mobile_front'),
            right: mobile_cube.querySelector('.mobile_right'),
            back: mobile_cube.querySelector('.mobile_back'),
            left: mobile_cube.querySelector('.mobile_left')
        };
        // const images = ['1.webp', '2.webp', '3.webp', '4.webp', '5.webp', '6.webp'];
        const images = ['1.png', '2.png', '3.png', '4.png', '5.png', '6.png'];
        const imgpos = [2, 3, 4, 5, 0, 1, 2, 3, 4, 5, 0, 1];



        faces.front.style.backgroundImage = `url('/assets/image/desktop/${images[0]}')`;
        faces.right.style.backgroundImage = `url('/assets/image/desktop/${images[1]}')`;
        faces.back.style.backgroundImage = `url('/assets/image/desktop/${images[2]}')`;
        faces.left.style.backgroundImage = `url('/assets/image/desktop/${images[5]}')`;

        let isDragging = false;
        let startX = 0;
        let currentRotation = 0;
        let autoRotateInterval;
        let virtualRotation;
        let virtualpos = 0;
        let finalCurrentRotation;
        let startCurrentRotation;
        let isScrolling = false;

        function buttonfocus() {
            const butpos = Math.abs(currentRotation) / 90 % 6;

            for (let index = 0; index < 6; index++) {
                const ids = `but${index + 1}`

                if (index === butpos) {
                    document.getElementById(ids).focus({
                        preventScroll: true
                    });
                } else {
                    document.getElementById(ids).blur();
                }
            }
        }


        function updateFaceImages() {
            currentIndex = Math.abs(currentRotation / 90) % 12;
            if (currentIndex % 4 === 1) {
                faces.left.style.backgroundImage = `url('/assets/image/desktop/${images[imgpos[currentIndex]]}')`;
            }
            if (currentIndex % 4 === 2) {
                faces.front.style.backgroundImage = `url('/assets/image/desktop/${images[imgpos[currentIndex]]}')`;
            }
            if (currentIndex % 4 === 3) {
                faces.right.style.backgroundImage = `url('/assets/image/desktop/${images[imgpos[currentIndex]]}')`;
            }
            if (currentIndex % 4 === 0) {
                faces.back.style.backgroundImage = `url('/assets/image/desktop/${images[imgpos[currentIndex]]}')`;
            }
            buttonfocus()
        }

        function updateCube() {
            mobile_cube.style.transform = `rotateY(${currentRotation}deg)`;

        }

        function buttonCircle() {
            stopAutoRotate();
            mobile_cube.classList.remove("mobile_transition");
            mobile_cube.classList.add("mobile_transition_fast");
            for (let index = startCurrentRotation; index >= finalCurrentRotation; index -= 90) {
                currentRotation = index;
                updateCube();
                updateFaceImages();
            }
            startCurrentRotation = 0;
            finalCurrentRotation = 0;
            mobile_cube.classList.remove("mobile_transition_fast");
            mobile_cube.classList.add("mobile_transition");
            startAutoRotate()
        }

        function updateCubeDuringDrag(pos) {
            if (pos < virtualpos - 90) {
                virtualpos = virtualpos - 90;
                currentRotation = currentRotation - 90
                updateFaceImages();
            }
            let deltapos = pos - virtualpos;
            if (virtualpos > pos && pos > (-180 + virtualpos) && pos % 90 === 0) {

                currentRotation = currentRotation + pos - virtualpos;
                virtualpos = pos;
                updateFaceImages();
                deltapos = 0;
            }
            mobile_cube.style.transform = `rotateY(${currentRotation + deltapos}deg)`;
        }

        function rotateRight() {
            currentRotation -= 90;
            updateCube();
            updateFaceImages();
        }

        function startAutoRotate() {
            if (!isDragging) {
                autoRotateInterval = setInterval(() => {
                    rotateRight();
                }, 3000); // Rotate every 3 seconds
            }
        }

        function stopAutoRotate() {
            clearInterval(autoRotateInterval);
        }

        function isCubeInViewport() {
            const cubeRect = mobile_cube.getBoundingClientRect();
            const windowHeight = window.innerHeight;
            // Check if the bottom of the cube is in the viewport
            return cubeRect.top < windowHeight && cubeRect.bottom >= 0;
        }

        window.addEventListener('scroll', () => {
            if (isCubeInViewport()) {
                if (!isScrolling) {
                    startAutoRotate(); // Start rotating when cube is in view
                    isScrolling = true;
                }
            } else {
                if (isScrolling) {
                    stopAutoRotate(); // Stop rotating when cube is out of view
                    isScrolling = false;
                }
            }
        });
        //mouse event
        function startDrag(e) {
            isDragging = true;
            startX = e.clientX || e?.touches[0]?.clientX;
            mobile_cube.classList.remove('mobile_transition');
        }

        function duringDrag(e) {
            if (!isDragging) return;
            const x = e.clientX || e?.touches[0]?.clientX;

            const delta = x - startX;
            virtualRotation = delta * 0.5;
            updateCubeDuringDrag(virtualRotation);
        }

        function endDrag() {
            if (!isDragging) return;
            isDragging = false;
            mobile_cube.classList.add("mobile_transition");
            currentRotation = currentRotation + virtualRotation - virtualpos;
            if ((Math.abs(currentRotation) + 90) % 90 > 45) {
                currentRotation = currentRotation - (90 - Math.abs(currentRotation) % 90)
            } else {
                currentRotation = currentRotation + (Math.abs(currentRotation) + 90) % 90
            }
            virtualpos = 0;
            updateFaceImages();
            updateCube();
        }
        // Mouse events
        mobile_cube.addEventListener('mouseover', stopAutoRotate);
        mobile_cube.addEventListener('mouseleave', startAutoRotate);
        mobile_cube.addEventListener('mousedown', startDrag);
        window.addEventListener('mousemove', duringDrag);
        window.addEventListener('mouseup', endDrag);

        mobile_cube.addEventListener('touchstart', startDrag);
        window.addEventListener('touchmove', duringDrag);
        window.addEventListener('touchend', endDrag);

        document.addEventListener('visibilitychange', function() {
            if (isScrolling) {
                if (document.hidden) {
                    stopAutoRotate();
                } else {
                    startAutoRotate();
                }
            }
        });

        buttonfocus()

        // Function to handle button focus behavior
        const butgroups = document.getElementById("button-mobile-group");
        const buttons = butgroups.querySelectorAll("button"); // Select all buttons

        // Function to handle button focus behavior
        function handleFocusBehavior(event) {
            const focusedButton = event.currentTarget;
            const currentid = focusedButton.getAttribute('id')
            const width = window.innerWidth;
            if (width < 640) {
                const currentButPos = buttonCouple[currentid];
                if (currentButPos == 0) {
                    butgroups.style.transform = `translateX(-10px)`;
                    return
                }
                const totalsum = butSize.slice(0, 6).reduce((acc, val) => acc + val, 0);
                if (currentButPos == 5) {
                    butgroups.style.transform = `translateX(-${totalsum - width}px)`;
                    return
                }
                const upsum = butSize.slice(0, currentButPos).reduce((acc, val) => acc + val, 0);
                const beforesum = butSize.slice(currentButPos, 6).reduce((acc, val) => acc + val, 0);
                if (beforesum > width) {
                    butgroups.style.transform = `translateX(-${upsum - 20}px)`;
                } else {
                    butgroups.style.transform = `translateX(-${totalsum - width - 30}px)`;
                }
            }
            // Keep focus on the button clicked
            const rotationpos = Math.abs(currentRotation) / 90 % 6;
            const moveDegree = (buttonCouple[currentid] + 6 - rotationpos) % 6 * (-90);
            startCurrentRotation = currentRotation;
            finalCurrentRotation = currentRotation + moveDegree;
            buttonCircle();


        }
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 640) {
                butgroups.style.transform = `translateX(0px)`;
            }
        });

        // Attach the focus behavior to each button
        buttons.forEach(button => {
            button.addEventListener('click', handleFocusBehavior);
            button.addEventListener('mouseover', stopAutoRotate);
            button.addEventListener('mouseleave', startAutoRotate);
            button.addEventListener('touchstart', stopAutoRotate);
            button.addEventListener('touchend', startAutoRotate); // use touchend instead of touchleave
        });

        $("#freeScan").click(function() {
            const name = $("#freeScanName").val();
            const baseUrl = `${window.location.origin}/signup`;
            const url = new URL(baseUrl);
            if (name) {
                url.searchParams.set("fullname", name);
            }
            window.location.href = url.toString();
        });
    }

    function auto_landing_header() {
        const white_header = document.getElementById("white-header");
        const black_header = document.getElementById("black-header");
        const sections = document.querySelectorAll(".landing-section");

        function updateHeader() {
            let scrollPos = window.scrollY; // current scroll from top

            // Find the section that has just reached/passed the top
            let currentSection = null;
            sections.forEach(section => {
                if (scrollPos >= section.offsetTop - 30) {
                    currentSection = section;
                }
            });

            if (currentSection) {
                const theme = currentSection.getAttribute("data-header");
                if (theme === "dark") {
                    white_header.classList.add("hidden");
                    black_header.classList.remove("hidden");
                } else {
                    black_header.classList.add("hidden");
                    white_header.classList.remove("hidden");
                }
            }
        }

        window.addEventListener("scroll", updateHeader);
        window.addEventListener("load", updateHeader); // run on first load
    }
    auto_landing_header();
    landing_init();
</script>