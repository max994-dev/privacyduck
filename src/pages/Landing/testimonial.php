<style>
    .page-number {
        text-align: center;
        margin-top: 10px;
        font-size: 18px;
        font-weight: bold;
    }
    /* Push Flickity prev/next arrows OUT of the card area into the
       gutter. Defaults put them at left:10px/right:10px which means
       they sit on top of the first/last card. We want them clearly
       outside so they never overlap quote text. */
    #pd-testimonials .flickity-prev-next-button.previous { left: -52px; }
    #pd-testimonials .flickity-prev-next-button.next     { right: -52px; }
    /* Tighter, lighter button look (default Flickity is a big white circle
       with a heavy shadow). Match the dashboard's pill aesthetic. */
    #pd-testimonials .flickity-prev-next-button {
        width: 40px; height: 40px;
        background: #FFFFFF;
        border: 1px solid #EAECEF;
        box-shadow: 0 2px 8px rgba(16,24,40,0.06);
        opacity: 0.95;
        transition: opacity 150ms, box-shadow 150ms, transform 150ms;
    }
    #pd-testimonials .flickity-prev-next-button:hover {
        opacity: 1;
        box-shadow: 0 4px 14px rgba(16,24,40,0.12);
        transform: translateY(-50%) scale(1.04);
    }
    #pd-testimonials .flickity-prev-next-button .flickity-button-icon {
        fill: #5B5F66;
        left: 28%; top: 28%; width: 44%; height: 44%;
    }
    /* Hide the arrow buttons on small viewports where they'd clip the
       cards. Pagination dots remain for navigation. */
    @media (max-width: 640px) {
        #pd-testimonials .flickity-prev-next-button { display: none; }
    }
    /* Page dots restyled lighter / smaller so they don't dominate. */
    #pd-testimonials .flickity-page-dots .dot {
        width: 8px; height: 8px; opacity: 0.35;
        margin: 0 4px;
    }
    #pd-testimonials .flickity-page-dots .dot.is-selected {
        opacity: 1; background: #24A556;
    }
</style>
<!-- Outer wrapper is full-width so the soft grey background bleeds
     edge-to-edge. Header is centered + capped to ~760px (readable line
     length). The carousel itself runs WIDER (1440px cap) and has its
     own internal padding so the Flickity prev/next arrows sit in the
     gutters instead of overlapping the first/last card content. -->
<div id="pd-testimonials" class="mt-[30px] text-[#010205]">
    <div class="bg-[#FAFAFA] h-fit py-[56px] sm:py-[88px] flex flex-col bg-cover bg-center rounded-3xl">
        <div class="max-w-[1200px] mx-auto w-full px-[20px] sm:px-[32px]">
            <!-- Header: always centered for visual balance with the carousel -->
            <div class="text-center space-y-[14px] max-w-[760px] mx-auto">
                <h2 class="font-semibold text-[28px] sm:text-[36px] lg:text-[42px] leading-[1.2] tracking-[-0.01em]">
                    What Our Users Say
                </h2>
                <div class="flex justify-center">
                    <?php
                    require_once(BASEPATH . "/src/common/svgs/landing/four_five_star.php");
                    ?>
                </div>
                <p class="text-[15px] sm:text-[17px] leading-[1.5] font-medium text-[#5B5F66] max-w-[640px] mx-auto">
                    Real people. Real results. Here&rsquo;s how
                    <span class="text-[#24A556] font-semibold">PrivacyDuck</span>
                    is helping users take back control of their online identity.
                </p>
            </div>
        </div>
        <div class="relative w-full max-w-[1600px] mx-auto mt-[28px] sm:mt-[36px] px-[64px] sm:px-[72px]">
            <div class="relative">
                <section class="overflow-visible">  <!-- overflow-visible so carousel can extend slightly into the gutter padding without clipping -->
                    <div>
                        <div>
                            <!-- Duplicated cards to loop seamlessly -->
                            <div class="carousel" data-flickity='{ "wrapAround": true, "freeScroll": true, "groupCells": 1 }'>
                                <!-- Cards -->
                                <?php
                                $testimonials = [
                                    [
                                        "quote" => "“I was constantly getting spam calls and weird emails. After PrivacyDuck stepped in, the noise just stopped. I could finally breathe again.”",
                                        "name" => "Maria J",
                                        "role" => "Austin TX",
                                        "star" => "five",
                                        "svg" => "https://i.pravatar.cc/40?img=5"
                                    ],
                                    [
                                        "quote" => "“I had no clue my face was tied to public profiles I never created. Their face leak checker found everything and got it removed. Huge peace of mind.”",
                                        "name" => "Daryl L.",
                                        "role" => "Video Editor",
                                        "star" => "four_five",
                                        "svg" => "https://i.pravatar.cc/40?img=12"
                                    ],
                                    [
                                        "quote" => "“I loved seeing my exposure score go down week by week. It felt like progress I could measure, not just promises.”",
                                        "name" => "Tanya K",
                                        "role" => "Entrepreneur",
                                        "star" => "five",
                                        "svg" => "https://i.pravatar.cc/40?img=26"
                                    ],
                                    [
                                        "quote" => "“I signed up for myself, then added my wife and daughter. We all had data floating around online, and now it’s finally gone.”",
                                        "name" => "Jorge M.",
                                        "role" => "Freelance Writer",
                                        "star" => "four_five",
                                        "svg" => "https://i.pravatar.cc/40?img=33"
                                    ],
                                    [
                                        "quote" => "“I didn’t realize how many sites had my info until I ran the free scan. PrivacyDuck cleaned it all up, and my phone finally stopped ringing with scam calls.
”",
                                        "name" => " Erik S.",
                                        "role" => "Sales Manager",
                                        "star" => "four_five",
                                        "svg" => "https://i.pravatar.cc/40?img=45"
                                    ],
                                    [
                                        "quote" => "“I found old photos and my full address on sketchy sites. PrivacyDuck removed everything, including stuff I hadn’t even seen before.”",
                                        "name" => "Jen M.",
                                        "role" => "Makeup Artist",
                                        "star" => "five",
                                        "svg" => "https://i.pravatar.cc/40?img=51"
                                    ],
                                    [
                                        "quote" => "“I tried deleting my data manually but gave up. PrivacyDuck handled it all, and I could track progress from one dashboard. Super easy.”",
                                        "name" => "Liam H.",
                                        "role" => " Tech Consultant",
                                        "star" => "four_five",
                                        "svg" => "https://i.pravatar.cc/40?img=36"
                                    ],
                                ];
                                foreach ($testimonials as $testimonial) {
                                ?>
                                    <!-- Card width: smaller default + smaller bump on sm so we
                                         show 1.x cards on mobile and 2-3 on desktop (was 411px
                                         which only fit 2 even on wide screens). Padding tightened
                                         to feel less hollow. Shadow upgraded from the typo'd
                                         `##D9D9D94A` to a proper soft elevation. -->
                                    <div class="w-[300px] sm:w-[360px] h-[296px] mr-[16px] bg-white flex flex-col justify-between p-[24px] shadow-[0_2px_12px_rgba(16,24,40,0.06)] hover:shadow-[0_4px_20px_rgba(16,24,40,0.1)] transition-shadow rounded-[16px] border border-[#F1F2F4]">
                                        <div>
                                            <?php require BASEPATH . "/src/common/svgs/landing/" . $testimonial['star'] . "_star.php"; ?>
                                            <blockquote class="font-medium text-[15px] sm:text-[16px] leading-[1.55] tracking-[-0.01em] text-[#374151] mt-[14px]">
                                                <?php echo $testimonial["quote"]; ?>
                                            </blockquote>
                                        </div>
                                        <div class="flex items-center gap-[12px] mt-[16px] pt-[14px] border-t border-[#F1F2F4]">
                                            <img src="<?php echo $testimonial["svg"]; ?>" class="w-[40px] h-[40px] rounded-full object-cover" alt="" />
                                            <div class="min-w-0">
                                                <div class="font-bold text-[14px] sm:text-[15px] leading-[1.3] text-[#010205] truncate">
                                                    <?php echo $testimonial["name"]; ?>
                                                </div>
                                                <div class="font-medium text-[12px] sm:text-[13px] leading-[1.3] text-[#878C91] truncate mt-[2px]">
                                                    <?php echo $testimonial["role"]; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <!-- <div class="page-number" id="pageNumber">1 / 5</div> -->
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
<script>
    const flkty = new Flickity('.carousel', {
        wrapAround: true,
        freeScroll: true,
        // autoPlay: 3000, // Auto-slide every 2s
        // selectedAttraction: 0.01, // smooth acceleration
        // pauseAutoPlayOnHover: true,
        // friction: 0.15, // smooth drag
        prevNextButtons: true,
        pageDots: true,
    });
    // const totalSlides = flkty.slides.length;
    // const pageNumberEl = document.getElementById('pageNumber');
    flkty.on('pointerUp', () => {
        flkty.playPlayer(); // resumes autoplay
    });

    // function updatePageNumber(index) {
    //     // Convert to 1-based index
    //     const displayIndex = (index % totalSlides + totalSlides) % totalSlides + 1;
    //     pageNumberEl.textContent = `${displayIndex} / ${totalSlides}`;
    // }

    // flkty.on('change', index => {
    //     updatePageNumber(index);
    // });

    // // Set initial page number
    // updatePageNumber(flkty.selectedIndex);
</script>