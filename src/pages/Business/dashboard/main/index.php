<div>
    <div
        class="hidden sm:flex justify-center items-center h-[106px] px-[35.5px] w-fit rounded-tl-[26px] rounded-tr-[26px] bg-[#FFFFFFCC] border border-b-0 border-[#F6F6F6]">
        <div class=" w-fit bg-[#FAFAFA] rounded-full flex space-x-[10px]">
            <?php
            $data = [
                ["id" => "mindmap_title", "name" => "Mind Map", "icon" => "mindmap"],
                ["id" => "list_title", "name" => "List", "icon" => "list"],
            ];
            foreach ($data as $key => $value) {
            ?>
                <div id="<?= $value["id"] ?>" class="py-[16px] flex justify-center items-center gap-[4px] w-[160px] rounded-full cursor-pointer transition-all duration-[0.2s]">
                    <?php require BASEPATH . "/src/common/svgs/business/main/" . $value['icon'] . ".php"; ?>
                    <h1 class="font-medium leading-[140%] text-[18px] tracking-[-0.02em]"><?= $value["name"] ?></h1>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>
<div id="main_content" class="bg-[#FFFFFF] w-full min-h-[calc(100vh-300px)] rounded-[26px] rounded-tl-none">
</div>
<script>
    function menu_select() {
        const ids = ["mindmap_title", "list_title"];
        ids.forEach(id => {
            $("#" + id).click(function() {
                ids.forEach(id => {
                    $("#" + id).removeClass("bg-[#FFCF50] text-[#3D5300]");
                });
                $(this).addClass("bg-[#FFCF50] text-[#3D5300]");
                if (id == "mindmap_title") {
                    $.get("/business/content/dashboard/main/mindmap", data => {
                        $("#main_content").html(data);
                        mindmap_init();
                    });
                } else {
                    $.get("/business/content/dashboard/main/list", data => {
                        $("#main_content").html(data);
                    });
                }
            });
        });
        $("#mindmap_title").addClass("bg-[#FFCF50] text-[#3D5300]");
        $.get("/business/content/dashboard/main/mindmap", data => {
            $("#main_content").html(data);
            mindmap_init();
        });
    }
</script>