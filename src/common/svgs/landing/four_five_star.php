<div class="flex items-center space-x-[2px]">
    <?php
    array_map(function ($item) {
        require("star.php");
    }, range(1, 4));
    require("half_star.php");
    ?>
</div>