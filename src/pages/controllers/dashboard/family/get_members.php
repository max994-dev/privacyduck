<?php
    include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/config.php");
    include_once($_SERVER["DOCUMENT_ROOT"] . "/src/common/database.php");
    header('Content-Type: application/json');
    $conn = getDBConnection();

    $sql = "SELECT 
      t_results.user_id,
      t_results.firstname,
      t_results.lastname,
      t_results.display_status,
      COALESCE(SUM(t_results.kind = '1' AND t_results.step = 0), 0) AS pending,
      COALESCE(SUM(t_results.kind = '1' AND t_results.step = 1), 0) AS ongoing,
      COALESCE(SUM(t_results.kind = '1' AND t_results.step = 2), 0) AS removed,
      COALESCE(SUM(t_results.kind = '1' AND t_results.step = 3), 0) AS notfound,
      COALESCE(SUM(t_results.kind = '0' AND t_results.step = 2), 0) AS exposed,
      COALESCE(SUM(t_results.kind = '1'), 0) AS kind1_total
    FROM (
      SELECT 
        users.id AS user_id,
        users.firstname,
        users.lastname,
        family.display_status,
        results.step,
        results.kind
      FROM family
      LEFT JOIN users ON users.id = family.invite_id
      LEFT JOIN results ON users.id = results.user_id AND (results.kind = 0 OR results.kind = 1)
      WHERE family.core_id = ?
    ) AS t_results
    GROUP BY 
      t_results.user_id,
      t_results.firstname,
      t_results.lastname,
      t_results.display_status;
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    
    echo json_encode($data);
?>
