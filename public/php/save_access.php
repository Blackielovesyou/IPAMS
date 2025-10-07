<?php
include("db.php");

$userId = intval($_POST['user_id'] ?? 0);
$modules = $_POST['modules'] ?? [];
$response = ["success" => false];

if ($userId > 0) {
    // Clear old access
    $conn->query("DELETE FROM module_access_data WHERE user_id=$userId");

    // Insert new access
    foreach ($modules as $m) {
        $stmt = $conn->prepare("INSERT INTO module_access_data (user_id, module_name, has_access) VALUES (?, ?, 1)");
        $stmt->bind_param("is", $userId, $m);
        $stmt->execute();
    }
    $response['success'] = true;
}
echo json_encode($response);
