<style>
    .page-number {
        text-align: center;
        margin-top: 10px;
        font-size: 18px;
        font-weight: bold;
    }
</style>
<div class="mt-[30px] text-[#010205]">
    <div
        class="bg-[#FAFAFA] h-fit py-[70px] sm:py-[120px] flex flex-col bg-cover bg-center rounded-3xl px-[16px]">
        <div class="flex flex-col px-3 py-10 text-center lg:text-left space-y-8 w-full">
            <div class="w-full space-y-[16px] px-10">
                <h2 class="flex flex-row lg:justify-start justify-center font-semibold text-[32px] lg:text-[48px] leading-[130%] tracking-[3%]">
                    What Our Users Say</h2>
                <div class="flex justify-center lg:block">
                    <?php
                    require_once(BASEPATH . "/src/common/svgs/landing/four_five_star.php");
                    ?>
                </div>
                <h2 class="text-[18px] leading-[130%] font-semibold text-[#010205]">Real people. Real results.
                    Here’s how <span class="text-[#24A556]">PrivacyDuck</span> is helping users take back control of their online identity.</h2>
            </div>
        </div>
        <div class="relative w-full">
            <div class="relative">
                <section class="overflow-hidden py-10">
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
                                    <div class="w-[297px] h-[327px] sm:w-[411px] mr-[16px] bg-white flex flex-col justify-between py-[32px] pl-[16px] shadow-[0px_4px_4px_##D9D9D94A] rounded-[20px]">
                                        <div>
                                            <?php require BASEPATH . "/src/common/svgs/landing/" . $testimonial['star'] . "_star.php"; ?>
                                            <blockquote class="font-semibold text-[16px] sm:text-[18px] leading-[160%] tracking-[-0.03em] text-[#010205CC] max-w-[90%] mt-[16px]">
                                                <?php echo $testimonial["quote"]; ?>
                                            </blockquote>
                                        </div>
                                        <div class="flex items-center space-x-[8px] sm:space-x-[24px]">
                                            <img src="<?php echo $testimonial["svg"]; ?>" class="rounded-full" />
                                            <div>
                                                <h2 class="font-bold text-[16px] sm:text-[20px] leading-[180%] text-[#010205]">
                                                    <?php echo $testimonial["name"]; ?>
                                                </h2>
                                                <h2 class="font-medium text-[14px] sm:text-[16px] leading-[180%] tracking-[-0.03em] text-[#010205CC]">
                                                    <?php echo $testimonial["role"]; ?>
                                                </h2>
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