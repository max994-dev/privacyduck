<div class="flex justify-between items-center">
    <h1 class="font-bold text-[14px] sm:text-[18px] leading-[130%] text-[#010205]">Found Profile Screenshots</h1>
    <div class="flex items-center space-x-[18px]">
        <div class="flex items-center sm:space-x-[10px]">
            <img src="/assets/image/desktop/icons/mini_usercheck.svg" alt="usercheck" />
            <h1 class="font-medium text-[10px] leading-[130%] text-[#9B9B9C]">
                Matches found&nbsp;&nbsp;
                <span id="matches_found"
                    class="font-semibold text-[#010205C2]">
                    <?php
                    $path = BASEPATH . '/assets/uploads/' . $_SESSION['user_id'] . '/scan';
                    $conn = getDBConnection();
                    //google scan start
                    $main_stmt = $conn->prepare("SELECT * FROM results WHERE user_id = ? AND kind=1");
                    $main_stmt->bind_param("i", $_SESSION["user_id"]);
                    $main_stmt->execute();
                    $main_result = $main_stmt->get_result();
                    $data = $main_result->fetch_all(MYSQLI_ASSOC);
                    $count = count(array_filter($data, function ($item) {
                        return $item["step"] >= 2;
                    }));
                    // If we've completed all kind=1 sites, there are no brokers to show
                    if ($count == count($data)) {
                        echo 0;
                    }else{
                        if (is_dir($path)) {
                            $files = scandir($path);
                            echo count($files) - 2;
                        } else {
                            echo 0;
                        }
                    }
                    ?>
                </span>
            </h1>
        </div>
        <button
            class="hidden sm:block w-[124px] h-[33px] rounded-full text-center bg-[#24A556] text-[12px] leading-[140%] tracking-[-0.02em] font-bold text-white">Buy
            Custom Plan</button>
    </div>
</div>
<div class="mt-[15px] sm:mt-[12px]">
    <div class="flex items-center">
        <img src="/assets/image/desktop/icons/middle_lock.svg" alt="lock" />
        <h1 class="text-[#010205] font-bold text-[10px] sm:text-[12px] leading-[130%]">DATA BROKERS RESULT</h1>
    </div>
    <div class="mt-[10px] sm:pl-[21px] flex justify-between 2xl:justify-evenly items-center">
        <img src="/assets/image/desktop/icons/mini_small.svg" alt="small" />
        <div class="w-[827px] h-[73px] overflow-hidden">
            <div id="databrokers" class="w-fit flex space-x-[6px] items-center">
            </div>
            <div id="no_databrokers" class="hidden w-full h-full bg-[#24A556] backdrop-blur-md text-center flex space-x-[6px] items-center justify-center">
                <!-- <h1 class="text-[14px] px-[15px] leading-[130%] text-white">You’ll find screenshots of your data exposed with data brokers here once we find them.
                    Now, add more details about yourself before the scan starts to increase accuracy.</h1> -->
            </div>
        </div>
        <img src="/assets/image/desktop/icons/mini_big.svg" alt="small" />
    </div>
</div>
<div class="mt-[24px] sm:mt-[16px]">
    <div class="flex items-center">
        <img src="/assets/image/desktop/icons/mini_google.svg" alt="google" />
        <h1 class="text-[#010205] font-bold text-[10px] sm:text-[12px] leading-[130%]">SEARCH ENGINE RESULTS: 3</h1>
    </div>
    <div class="mt-[10px] sm:pl-[21px] flex justify-between 2xl:justify-evenly items-center">
        <img src="/assets/image/desktop/icons/mini_small.svg" alt="small" />
        <div class="w-[827px] h-[73px] overflow-hidden">
            <div id="googleresults" class="w-fit flex space-x-[6px] items-center">
            </div>
            <div id="no_googleresults" class="hidden w-full h-full bg-[#24A556] backdrop-blur-md text-center flex space-x-[6px] items-center justify-center">
                <h1 class="text-[14px] px-[15px] leading-[130%] text-white">You’ll find screenshots of your data exposed in search engine results here once we find them.
                    Add more details about yourself before the scan starts to increase accuracy.</h1>
            </div>
        </div>
        <img src="/assets/image/desktop/icons/mini_big.svg" alt="small" />
    </div>
</div>
<div class="mt-[24px] flex justify-center sm:hidden">
    <button
        class="w-[124px] h-[33px] rounded-full text-center bg-[#24A556] text-[12px] leading-[140%] tracking-[-0.02em] font-bold text-white">Buy
        Custom Plan</button>
</div>

<div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-75 flex justify-center items-center z-50">
    <img id="modalImage" src="" class="max-w-[90%] max-h-[90%] rounded-lg" />
</div>

<script>
    window.scanImages = JSON.parse(`<?php
                                    $path = BASEPATH . '/assets/uploads/' . $_SESSION['user_id'] . '/scan';
                                    if (is_dir($path)) {
                                        $files = scandir($path);
                                        echo json_encode($files);
                                    } else {
                                        echo "[]";
                                    }
                                    ?>`).filter(v => v.length > 3);
    window.googleScanImages = JSON.parse(`<?php
                                            $path = BASEPATH . '/assets/uploads/' . $_SESSION['user_id'] . '/google_scan';
                                            if (is_dir($path)) {
                                                $files = scandir($path);
                                                echo json_encode($files);
                                            } else {
                                                echo "[]";
                                            }
                                            ?>`).filter(v => v.length > 3);

    function addScanImage(image) {
        window.scanImages.push(image);
        main_databrokers();
    }

    function addGoogleScanImage(image) {
        window.googleScanImages.push(image);
        main_google_results();
    }

    function showFullImage(src) {
        $('#modalImage').attr('src', src);
        $('#imageModal').removeClass('hidden');
    }

    // Hide the modal when clicked
    $('#imageModal').on('click', function() {
        $(this).addClass('hidden');
    });

    function main_databrokers() {

        const arrayOfDivs = window.scanImages
            .map(v => v.target_domain ? `scan_${v.target_domain}_<?= $_SESSION['user_id'] ?>.png` : v)
            .filter((v, k, a) => !a.find((v2, k2) => v2 === v && k2 > k))
            .map(v => `
            <div class="w-[100px] cursor-pointer">
                <div class="flex items-center">
                <img src="/assets/image/desktop/icons/middle_lock.svg" class="w-[14px] h-[14px]" />
                <h1 class="font-medium text-[10px] leading-[130%] text-[#9B9B9C]">IDTrue</h1>
                </div>
                <img 
                src="/assets/uploads/<?= $_SESSION['user_id'] ?>/scan/${v}" 
                class="w-full mt-[8px] rounded hover:scale-[1.25] hover:transition hover:duration-[100ms] hover:ease-in-out hover:z-10 hover:bg-[#24A556] hover:bg-opacity-[0.1] transition" 
                onclick="showFullImage(this.src)"
                />
            </div>
            `);
        if (window.removal_progress === window.totalcount || window.scanImages.length === 0) {
            $('#databrokers').addClass('hidden');
            $('#no_databrokers').removeClass('hidden');
            if (window.removal_progress === window.totalcount) {
                $('#no_databrokers').html(`
                <h1 class="text-[14px] px-[15px] leading-[130%] text-white">Your data is safe and secure. No data brokers found.</h1>
                `);
            } else {
                $('#no_databrokers').html(`
                <h1 class="text-[14px] px-[15px] leading-[130%] text-white">You’ll find screenshots of your data exposed with data brokers here once we find them.
                    Now, add more details about yourself before the scan starts to increase accuracy.</h1>
                `);
            }
        } else {
            $('#databrokers').removeClass('hidden');
            $('#no_databrokers').addClass('hidden');
            $('#databrokers').html(arrayOfDivs);
        }
    }

    function main_google_results() {
        const arrayOfDivs = window.googleScanImages
            .map(v => v.target_domain ? `scan_${v.target_domain}_<?= $_SESSION['user_id'] ?>.png` : v)
            .filter((v, k, a) => !a.find((v2, k2) => v2 === v && k2 > k))
            .map(v => `
            <div class="w-[100px] cursor-pointer">
                <div class="flex items-center">
                <img src="/assets/image/desktop/icons/middle_lock.svg" class="w-[14px] h-[14px]" />
                <h1 class="font-medium text-[10px] leading-[130%] text-[#9B9B9C]">IDTrue</h1>
                </div>
                <img 
                src="/assets/uploads/<?= $_SESSION['user_id'] ?>/google_scan/${v}" 
                class="w-full mt-[8px] rounded hover:scale-[1.25] hover:transition hover:duration-[100ms] hover:ease-in-out hover:z-10 hover:bg-[#24A556] hover:bg-opacity-[0.1] transition" 
                onclick="showFullImage(this.src)"
                />
            </div>
            `);
        if (window.googleScanImages.length === 0) {
            $('#googleresults').addClass('hidden');
            $('#no_googleresults').removeClass('hidden');
        } else {
            $('#googleresults').removeClass('hidden');
            $('#no_googleresults').addClass('hidden');
            $('#googleresults').html(arrayOfDivs);
        }
    }

    function updateMatchesFound() {
        const scan_count = document.getElementById('matches_found');
        if (scan_count) scan_count.innerHTML = JSON.parse(`<?php
                                                            $path = BASEPATH . '/assets/uploads/' . $_SESSION['user_id'] . '/scan';
                                                            if (is_dir($path)) {
                                                                $files = scandir($path);
                                                                echo json_encode($files);
                                                            } else {
                                                                echo "[]";
                                                            }
                                                            ?>`).filter(v => v.length > 3).length;
    }
</script>