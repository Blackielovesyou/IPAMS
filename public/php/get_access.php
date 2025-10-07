<?php
include("db.php");

$userId = intval($_POST['user_id'] ?? 0);
$response = ["success" => false, "modules" => []];

if ($userId > 0) {
    $sql = "SELECT module_name FROM module_access_data WHERE user_id=? AND has_access=1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $response['modules'][] = $row['module_name'];
    }
    $response['success'] = true;
}
echo json_encode($response);
