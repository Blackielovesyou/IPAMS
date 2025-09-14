<?php
function logAction($conn, $user_id, $role, $action_type, $content) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';

    $sql = "INSERT INTO system_logs (user_id, role, ip_address, action_type, content) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $user_id, $role, $ip, $action_type, $content);
    $stmt->execute();
    $stmt->close();
}
?>
