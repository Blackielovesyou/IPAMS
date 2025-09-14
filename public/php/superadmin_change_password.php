<?php
session_start();
include("db.php");
include("log_action.php");

// Extend session lifetime (30 days)
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 30);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['id']; // superadmin ID
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];

    if ($newPassword !== $confirmNewPassword) {
        $_SESSION['pass_status'] = ['status' => 'error', 'message' => 'New password and confirmation do not match!'];
        header("Location: super_admin.php");
        exit;
    }

    // Fetch current password
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user || !password_verify($oldPassword, $user['password'])) {
        $_SESSION['pass_status'] = ['status' => 'error', 'message' => 'Old password is incorrect!'];
        header("Location: super_admin.php");
        exit;
    }

    // Update password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashedPassword, $userId);
    $stmt->execute();

    logAction($conn, $userId, $_SESSION['role'], "password_change", "Superadmin changed their password");

    $_SESSION['pass_status'] = ['status' => 'success', 'message' => 'Password changed successfully!'];
    header("Location: super_admin.php");
    exit;
}
?>
