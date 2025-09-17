<?php
session_start();
include("db.php");

// Only superadmin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $systemName = trim($_POST['system_name']);

    if (!empty($systemName)) {
        // Check if an app_info row exists
        $sqlCheck = "SELECT id FROM app_info ORDER BY id LIMIT 1";
        $result = $conn->query($sqlCheck);

        if ($result && $result->num_rows > 0) {
            // Update the existing row
            $sql = "UPDATE app_info SET appname = ? ORDER BY id LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $systemName);
        } else {
            // Insert new row
            $sql = "INSERT INTO app_info (appname, category) VALUES (?, 'General')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $systemName);
        }

        if ($stmt->execute()) {
            $_SESSION['pass_status'] = [
                'status' => 'success',
                'message' => 'System name updated successfully!'
            ];
        } else {
            $_SESSION['pass_status'] = [
                'status' => 'error',
                'message' => 'Failed to update system name.'
            ];
        }

        $stmt->close();
    }

    header("Location: super_admin.php"); // back to dashboard
    exit;
}
?>
