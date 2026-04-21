<?php
    require_once BASEPATH . '/src/common/odoo_removal_sync.php';
    $conn = getDBConnection();
    odoo_removal_ensure_columns($conn);

    $current = isset($_GET["current"]) ? (int)$_GET["current"] : 1;
    $pageSize = isset($_GET["pageSize"]) ? (int)$_GET["pageSize"] : 10;
    $search = isset($_GET["search"]) ? trim($_GET["search"]) : "";
    $sort = isset($_GET["sort"]) ? $_GET["sort"] : "target_domain";
    $skip = ($current - 1) * $pageSize;
    $limit = $pageSize;
    $allowedSort = ['target_domain', 'step'];
    $sortColumn = in_array($sort, $allowedSort) ? $sort : 'target_domain';
    $direction = "ASC";
    if ($sortColumn === "step") $direction = "DESC";
    $sql = "SELECT * FROM results WHERE user_id = ? AND kind = 1";
    $params = [];
    $types = "i"; // user_id
    $params[] = $_SESSION["user_id"];
    
    if (!empty($search)) {
        $sql .= " AND target_domain LIKE ?";
        $types .= "s";
        $params[] = '%' . $search . '%';
    }
    $sql .= " ORDER BY $sortColumn $direction LIMIT ? OFFSET ?";
    $types .= "ii";
    $params[] = $limit;
    $params[] = $skip;

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $sql = "SELECT COUNT(*) FROM results WHERE user_id = ? AND kind = 1";
    $params = [];
    $types = "i"; // user_id
    $params[] = $_SESSION["user_id"];
    if (!empty($search)) {
        $sql .= " AND target_domain LIKE ?";
        $types .= "s";
        $params[] = '%' . $search . '%';
    }
    $totalResult = $conn->prepare($sql);
    $totalResult->bind_param($types, ...$params);
    $totalResult->execute();
    $totalResult = $totalResult->get_result()->fetch_row()[0];
    echo json_encode([
        "sites"=>$data,
        "total"=>$totalResult
    ]);
?>