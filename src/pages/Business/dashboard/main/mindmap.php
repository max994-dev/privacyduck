    <style>
        .mindmapContainer {
            position: relative;
        }

        .node {
            position: absolute;
            background: white;
            padding: 8px 12px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.4s ease, transform 0.4s ease;
            cursor: grab;
        }

        .node.show {
            opacity: 1;
            transform: translateY(0);
        }

        .node.fade-out {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.4s ease, transform 0.4s ease;
        }

        .avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .name {
            margin-right: 8px;
        }

        .add-btn,
        .delete-btn {
            background: #2ecc71;
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .delete-btn {
            background: #e74c3c;
        }

        .mindmap-svg {
            position: absolute;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .mindmapContainerScroll {
            width: 100%;
            height: 100%;
            overflow: auto;
        }
    </style>

    <div class="mindmapContainerScroll" id="mindmapmindmapContainer">
        <div class="mindmapContainer" id="mindmap">
            <svg id="svg-lines" class="mindmap-svg"></svg>
        </div>
    </div>
    <?php require(BASEPATH . "/src/pages/Business/dashboard/main/addmember.php"); ?>
    <div id="mindmapModal" class="fixed inset-0 inset-0 bg-[#00000040] px-[16px] border-1 border-[#F6F6F63A] backdrop-blur-md flex items-center justify-center hidden z-50 animate-[opacity_0.5s_ease-out]">
        <div class="relative bg-[#FFFFFF] border border-[#EEEEEE] shadow-[0_4px_4px_0_#F6F6F626] px-[24px] pt-[40px] pb-[32px]">
            <button id="closeModal" onclick="closeModal()" class="absolute top-[15px] right-[20px] text-center font-bold text-gray-500 hover:text-red-500">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 5L5 19M5 5L19 19" stroke="#020609" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </button>
            <div class="flex flex-col items-center">
                <h1 class="text-[18px] font-semibold text-[#010205]">Give your map a name</h1>
                <input type="text" onchange="checkInput()" id="mindmapName" placeholder="New Map" class="bg-[#FBFBFB] mt-[32px] w-[234px] h-[44px] px-[14px] py-[10px] border border-[#00000040] rounded-[8px]">
                <button disabled id="saveMindmap" onclick="saveMindmap()" class="mt-[26px] bg-[#EEEEEE] rounded-full w-full h-[44px] justify-center items-center">Save</button>
            </div>
        </div>
    </div>

    <script>
        let arrayData = [];
        let initData = [];

        function addPlusButton() {
            const node = document.createElement('div');
            node.className = 'node';
            node.id = `root`;
            node.setAttribute('data-parent', 'root');
            node.style.left = `50px`;
            node.style.top = `300px`;

            node.innerHTML = `
            <div class="relative" id="mindmap_init_plus">
                <button class="add-btn" onclick="newMindMapModal()"><i class="fa-solid fa-plus"></i></button> 
            </div>
    `;

            mindmapContainer.appendChild(node);
            requestAnimationFrame(() => node.classList.add('show'));
            drawAllCurves(true);
        }

        function getInitData() {
            $.post("/business/dashboard/main/getMindmap", function(data) {
                initData = data;
                if (!initData.length) {
                    addPlusButton();
                } else {
                    buildMindMap(initData);
                }
            });
        }
        let childCount = initData.length;
        let mindmapContainer = document.getElementById('mindmap');
        let mindmapContainerScroll = document.getElementById('mindmapmindmapContainer');
        let svg = document.getElementById('svg-lines');
        let connections = [];


        function getCenterPos(el) {
            const rect = getComputedStyle(el);
            const left = parseInt(rect.left.replace("px", "")),
                top = parseInt(rect.top.replace("px", "")),
                width = parseInt(rect.width.replace("px", "")),
                height = parseInt(rect.height.replace("px", ""));
            return {
                x: left + width / 2,
                y: top + height / 2,
                width,
                height
            };
        }

        function drawAllCurves(isInit, isAdd) {
            svg.innerHTML = '';
            const children = Array.from(mindmapContainer.children);
            const maxBottom = Math.max(...children.map(child => {
                const top = parseFloat(child.style.top || 0);
                const height = child.offsetHeight || 0;
                return top + height;
            }));
            mindmapContainer.style.height = maxBottom + 20 + 'px';
            const maxRight = Math.max(...children.map(child => {
                const top = parseFloat(child.style.left || 0);
                const height = child.offsetWidth || 0;
                return top + height;
            }));
            mindmapContainer.style.width = maxRight + 50 + 'px';
            connections.forEach((conn, k) => {
                const lastChild = (k === connections.length - 1);
                const fromNode = document.getElementById(conn.from);
                const toNode = document.getElementById(conn.to);
                if (!fromNode || !toNode) return;

                const from = getCenterPos(fromNode);
                const to = getCenterPos(toNode);

                const path = document.createElementNS("http://www.w3.org/2000/svg", "path");
                path.setAttribute("stroke", "#888");
                path.setAttribute("fill", "transparent");
                path.setAttribute("stroke-width", "2");
                const a = 0.5;
                const d = `M ${from.x} ${from.y - (isInit ? 20 : 0)}
                L ${from.x + from.width / 2 + 20} ${from.y - (isInit ? 20 : 0)}
                C ${from.x + (to.x - from.x) * a} ${from.y - (isInit ? 20 : 0)},
                ${to.x - (to.x - from.x) * a} ${to.y - (isInit || (lastChild && isAdd) ? 20 : 0)},
                ${to.x - to.width / 2 - 20} ${to.y - (isInit || (lastChild && isAdd) ? 20 : 0)}
                L ${to.x} ${to.y - (isInit || (lastChild && isAdd) ? 20 : 0)}`;
                path.setAttribute("d", d);
                svg.appendChild(path);
            });
        }

        function updateJSONData() {
            const nodes = Array.from(document.querySelectorAll('.node'));
            arrayData = nodes.map(node => {
                return {
                    id: node.id,
                    parent: node.getAttribute('data-parent') || null,
                    title: node.querySelector('.name')?.textContent.trim(),
                    avatar: node.querySelector('.avatar')?.src,
                    x: parseInt(node.style.left, 10),
                    y: parseInt(node.style.top, 10),
                };
            });
            // console.clear();
        }

        function createMindmap(mindmap_name) {
            const child = document.createElement('div');
            child.className = 'node';
            child.setAttribute('data-parent', "root");
            const top = 300;
            const left = 50;

            child.style.top = `${top}px`;
            child.style.left = `${left}px`;

            $.post('/business/dashboard/main/createMindmap', {
                mindmap_name: mindmap_name,
                x: left,
                y: top
            }, function(response) {
                if (!response.id) return;
                $("#mindmap_init_plus").hide();
                child.id = response.id;
                child.innerHTML = `
      <img src="https://i.pravatar.cc/40?img=${(childCount % 70) + 1}" class="avatar">
      <div class="name">${mindmap_name}</div>
      <button class="add-btn" onclick="addChild(${response.id}, {
        email: 'eruis2579@gmail.com',
        firstname: 'Name',
        lastname: 'Surname',
        avatar: 'https://i.pravatar.cc/40?img=1',
        })">+</button>
    `;

                mindmapContainer.appendChild(child);
                requestAnimationFrame(() => child.classList.add('show'));

                // connections.push({
                //     from: parentId,
                //     to: newId
                // });
                // makeDraggable(child);
                drawAllCurves(false, true);
                updateJSONData();
                drawAllCurves();
            });
            //-----
        }

        function addChild(parentId, data) {
            const parent = document.getElementById(parentId);
            const newId = String(Date.now());
            const child = document.createElement('div');
            child.className = 'node';
            child.setAttribute('data-parent', parentId);
            const thisChildCount = connections.filter(conn => conn.from === parentId).length;
            const top = parent.offsetTop - 100 + (thisChildCount * 60);
            const left = parent.offsetLeft + 300;

            child.style.top = `${top}px`;
            child.style.left = `${left}px`;

            $.post('/business/dashboard/main/addMember', {
                email: data.email,
                firstname: data.firstname,
                lastname: data.lastname,
                x: left,
                y: top
            }, function(response) {
                if (!response.id) return;
                child.id = response.id;
                child.innerHTML = `
      <img src="https://i.pravatar.cc/40?img=${(childCount % 70) + 1}" class="avatar">
      <div class="name">${data.firstname} ${data.lastname}</div>
      <button class="delete-btn" onclick="deleteNode('${response.id}')"><i class="fa-solid fa-trash"></i></button>
    `;

                mindmapContainer.appendChild(child);
                requestAnimationFrame(() => child.classList.add('show'));

                connections.push({
                    from: parentId,
                    to: response.id
                });
                makeDraggable(child);
                updateJSONData();
                drawAllCurves(false, true);
                drawAllCurves();
            });
            //-----
        }

        function deleteNode(id) {
            const children = connections.filter(conn => conn.from === id).map(conn => conn.to);
            children.forEach(deleteNode);

            $.post('/business/dashboard/main/deleteMember', {
                id: id
            }, function(response) {
                connections = connections.filter(conn => conn.from != id && conn.to != id);
                const el = document.getElementById(id);
                if (!el) return;

                el.classList.remove('show');
                el.classList.add('fade-out');

                setTimeout(() => {
                    el.remove();
                    drawAllCurves();
                    updateJSONData();
                }, 400);
            });
        }

        function makeDraggable(el) {
            let offsetX, offsetY, dragging = false;

            el.addEventListener('mousedown', e => {
                dragging = true;
                offsetX = e.clientX - el.offsetLeft;
                offsetY = e.clientY - el.offsetTop;
                el.style.zIndex = 1000;
            });

            document.addEventListener('mousemove', e => {
                if (!dragging) return;
                el.style.left = `${e.clientX - offsetX}px`;
                el.style.top = `${e.clientY - offsetY}px`;
                drawAllCurves();
            });

            document.addEventListener('mouseup', () => {
                if (dragging) {
                    $.post('/business/dashboard/main/changePositionMember', {
                        mindmap_id: el.id,
                        x: el.offsetLeft,
                        y: el.offsetTop
                    }, function(response) {
                    });
                    updateJSONData();
                }
                dragging = false;
                el.style.zIndex = '';
            });
        }

        function createNodeFromData(nodeData) {
            const node = document.createElement('div');
            node.className = 'node';
            node.id = nodeData.id;
            node.setAttribute('data-parent', nodeData.parent || '');
            node.style.left = `${nodeData.x}px`;
            node.style.top = `${nodeData.y}px`;
            node.innerHTML = `
      <img src="https://i.pravatar.cc/40?img=${nodeData.id%50+2}" class="avatar">
        ${nodeData.mindmapname?`<div class="name">${nodeData.mindmapname}</div>`:`<div class="name">${nodeData.firstname} ${nodeData.lastname}</div>`}
      ${nodeData.mindmapname?`<button class="add-btn" onclick="showAddChildModal(${nodeData.id})"><i class="fa-solid fa-plus"></i></button>`:""}
      ${!nodeData.mindmapname ? `<button class="delete-btn" onclick="deleteNode('${nodeData.id}')"><i class="fa-solid fa-trash"></i></button>` : ''}
    `;

            mindmapContainer.appendChild(node);
            requestAnimationFrame(() => node.classList.add('show'));

            if (!nodeData.mindmapname) makeDraggable(node);
        }

        function buildMindMap(data) {
            data.forEach(node => {
                createNodeFromData(node);
                if (node.parent) {
                    connections.push({
                        from: node.parent,
                        to: node.id
                    });
                }
            });
            drawAllCurves(true);
            updateJSONData();
        }

        // Initialize
        function mindmap_init() {
            childCount = initData.length;
            mindmapContainer = document.getElementById('mindmap');
            mindmapContainerScroll = document.getElementById('mindmapmindmapContainer');
            svg = document.getElementById('svg-lines');
            connections = [];
            getInitData();
        }

        function newMindMapModal() {
            const role = "<?php echo $_SESSION["work_role"]; ?>";
            if (role != 1) {
                toastr.error("Business account is not authorized. Please contact support team.")
                return;
            }
            document.getElementById('mindmapModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('mindmapModal').classList.add('hidden');
        }

        function checkInput() {
            const input = document.getElementById('mindmapName');
            if (input.value) {
                document.getElementById('saveMindmap').disabled = false;
                document.getElementById('saveMindmap').classList.remove('bg-[#EEEEEE]');
                document.getElementById('saveMindmap').classList.remove('text-[#010205]');
                document.getElementById('saveMindmap').classList.add('bg-[#24A556]');
                document.getElementById('saveMindmap').classList.add('text-[#FFFFFF]');
            } else {
                document.getElementById('saveMindmap').disabled = true;
                document.getElementById('saveMindmap').classList.remove('bg-[#24A556]');
                document.getElementById('saveMindmap').classList.remove('text-[#FFFFFF]');
                document.getElementById('saveMindmap').classList.add('bg-[#EEEEEE]');
                document.getElementById('saveMindmap').classList.add('text-[#010205]');
            }
        }

        function saveMindmap() {
            const input = document.getElementById('mindmapName').value;
            createMindmap(input);
            closeModal();
        }
        function showAddChildModal(parentId) {
            // addChild(${nodeData.id}, {
                // email: 'thaddeus@privacypros.com',
                // firstname: 'Name',
                // lastname: 'Surname',
                // avatar: 'https://i.pravatar.cc/40?img=1',
            // })
            document.getElementById('business_addchild_info_modal').classList.remove('hidden');
        }
    </script>