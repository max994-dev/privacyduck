<?php
    require_once BASEPATH . '/src/common/odoo_removal_sync.php';

    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(["error" => "Not authenticated"]);
        exit;
    }

    $conn = getDBConnection();
    odoo_removal_ensure_columns($conn);

    $current  = isset($_GET["current"])  ? max(1, (int) $_GET["current"])  : 1;
    $pageSize = isset($_GET["pageSize"]) ? (int) $_GET["pageSize"]         : 10;
    if ($pageSize < 1 || $pageSize > 200) $pageSize = 10;
    $search   = isset($_GET["search"])   ? trim($_GET["search"])           : "";
    $sort     = isset($_GET["sort"])     ? $_GET["sort"]                   : "target_domain";
    $skip     = ($current - 1) * $pageSize;
    $limit    = $pageSize;

    $allowedSort = ['target_domain', 'step'];
    $sortColumn  = in_array($sort, $allowedSort, true) ? $sort : 'target_domain';
    $direction   = ($sortColumn === "step") ? "DESC" : "ASC";

    $userId    = (int) $_SESSION["user_id"];
    $baseWhere = "WHERE user_id = ? AND kind = 1";
    $whereTypes  = "i";
    $whereParams = [$userId];
    if ($search !== "") {
        $baseWhere   .= " AND target_domain LIKE ?";
        $whereTypes  .= "s";
        $whereParams[] = '%' . $search . '%';
    }

    // Page of rows
    $sql   = "SELECT * FROM results $baseWhere ORDER BY $sortColumn $direction LIMIT ? OFFSET ?";
    $types = $whereTypes . "ii";
    $params = array_merge($whereParams, [$limit, $skip]);
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Total count (same WHERE)
    $countStmt = $conn->prepare("SELECT COUNT(*) FROM results $baseWhere");
    $countStmt->bind_param($whereTypes, ...$whereParams);
    $countStmt->execute();
    $totalResult = (int) $countStmt->get_result()->fetch_row()[0];
    $countStmt->close();

    echo json_encode([
        "sites" => $data,
        "total" => $totalResult,
    ]);
?>
