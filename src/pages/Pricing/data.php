<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/utils.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/common/database.php');

$conn = getDBConnection();
$stmt = $conn->prepare('SELECT * FROM plans where show_dashboard = 1');
$stmt->execute();
$result = $stmt->get_result();

$data = [];
if ($result->num_rows > 0) {
    $tmpData = $result->fetch_all(MYSQLI_ASSOC);
    foreach ($tmpData as $key => $value) {
        $value['stripe_payment_link'] = $value['stripe_payment_link_etc'] ?? '';
        if (!isset($data[$value['year']])) {
            $data[$value['year']] = [];
        }
        $data[$value['year']][$value['person']] = $value;
    }
}
$stmt->close();
$conn->close();

$jsonFlags = JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
if (defined('JSON_INVALID_UTF8_SUBSTITUTE')) {
    $jsonFlags |= JSON_INVALID_UTF8_SUBSTITUTE;
}
$json = json_encode($data, $jsonFlags);
if ($json === false) {
    $json = '[]';
}
?>
const dashboard_plans_data = <?php echo $json; ?>;
