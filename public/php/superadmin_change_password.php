<?php
session_start();
include("db.php");
include("log_action.php");

// Extend session lifetime (30 days)
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 30);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ✅ Ensure only logged-in superadmin can change password
    if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'superadmin') {
        $_SESSION['pass_status'] = [
            'status' => 'error',
            'message' => 'Unauthorized access!'
        ];
        header("Location: loginform.php");
        exit;
    }

    $userId = $_SESSION['id'];
    $oldPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmNewPassword = $_POST['confirm_password'];

    // ✅ Check if new password matches confirmation
    if ($newPassword !== $confirmNewPassword) {
        $_SESSION['pass_status'] = [
            'status' => 'error',
            'message' => 'New password and confirmation do not match!'
        ];
        header("Location: super_admin.php");
        exit;
    }

    // ✅ Fetch current password for this superadmin
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ? AND role = 'superadmin'");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // ✅ Verify old password
        if (!password_verify($oldPassword, $user['password'])) {
            $_SESSION['pass_status'] = [
                'status' => 'error',
                'message' => 'Old password is incorrect!'
            ];
            header("Location: super_admin.php");
            exit;
        }

        // ✅ Hash new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // ✅ Update password
        $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ? AND role = 'superadmin'");
        $updateStmt->bind_param("si", $hashedPassword, $userId);

        if ($updateStmt->execute()) {
            // ✅ Log action
            logAction($conn, $userId, $_SESSION['role'], "password_change", "Superadmin changed their password");

            // ✅ Option A: Stay logged in
            $_SESSION['pass_status'] = [
                'status' => 'success',
                'message' => 'Password changed successfully!'
            ];

            // ✅ Option B (More Secure): Force logout after change
            // session_destroy();
            // header("Location: loginform.php");
            // exit;
        } else {
            $_SESSION['pass_status'] = [
                'status' => 'error',
                'message' => 'Failed to update password!'
            ];
        }
    } else {
        $_SESSION['pass_status'] = [
            'status' => 'error',
            'message' => 'Superadmin not found!'
        ];
    }

    header("Location: super_admin.php");
    exit;
}
?>