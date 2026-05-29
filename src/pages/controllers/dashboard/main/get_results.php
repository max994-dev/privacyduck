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
    // status filter: '', '0' (not yet), '1' (ongoing), '2' (sent), '3' (not found)
    $status   = isset($_GET["status"])   ? trim((string) $_GET["status"])  : "";
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
    if ($status !== "" && in_array($status, ['0','1','2','3'], true)) {
        $baseWhere   .= " AND step = ?";
        $whereTypes  .= "i";
        $whereParams[] = (int) $status;
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

    // Total count (same WHERE -- includes status filter so pagination matches)
    $countStmt = $conn->prepare("SELECT COUNT(*) FROM results $baseWhere");
    $countStmt->bind_param($whereTypes, ...$whereParams);
    $countStmt->execute();
    $totalResult = (int) $countStmt->get_result()->fetch_row()[0];
    $countStmt->close();

    // Per-status counts (so the filter chips can show how many in each bucket).
    // Uses the same kind=1 + user_id + search scope as the main query but
    // NOT the status filter -- chips need to show all bucket sizes.
    $chipWhere = "WHERE user_id = ? AND kind = 1";
    $chipTypes = "i";
    $chipParams = [$userId];
    if ($search !== "") {
        $chipWhere   .= " AND target_domain LIKE ?";
        $chipTypes   .= "s";
        $chipParams[] = '%' . $search . '%';
    }
    $statusCountStmt = $conn->prepare(
        "SELECT step, COUNT(*) c FROM results $chipWhere GROUP BY step"
    );
    $statusCountStmt->bind_param($chipTypes, ...$chipParams);
    $statusCountStmt->execute();
    $statusRows = $statusCountStmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $statusCountStmt->close();

    $statusCounts = ['all' => 0, '0' => 0, '1' => 0, '2' => 0, '3' => 0];
    foreach ($statusRows as $r) {
        $s = (string) (int) $r['step'];
        if (isset($statusCounts[$s])) $statusCounts[$s] = (int) $r['c'];
        $statusCounts['all'] += (int) $r['c'];
    }

    echo json_encode([
        "sites" => $data,
        "total" => $totalResult,
        "status_counts" => $statusCounts,
    ]);
?>
