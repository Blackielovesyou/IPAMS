<?php
session_start();
include("db.php");
include("log_action.php");

// Extend session lifetime (30 days)
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 30);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Fetch user by email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // ✅ Remove email verification check for all admins
        // Only block unverified applicants or users
        if (
            $user['role'] !== 'admin' &&
            $user['role'] !== 'superadmin' &&
            $user['email_verified'] == 0
        ) {
            logAction($conn, $user['id'], $user['role'], "login_failed", "Attempted login without verifying email: $email");
            $_SESSION['error'] = "Please verify your email before logging in!";
            header("Location: loginform.php");
            exit;
        }

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['success'] = $user['role'];

            logAction($conn, $user['id'], $user['role'], "login", "User logged in successfully");

            header("Location: loginform.php");
            exit;
        } else {
            logAction($conn, $user['id'], $user['role'], "login_failed", "Wrong password for email: $email");
            $_SESSION['error'] = "Wrong password!";
            header("Location: loginform.php");
            exit;
        }
    } else {
        logAction($conn, null, "applicant", "login_failed", "No user found with email: $email");
        $_SESSION['error'] = "No user found with that email!";
        header("Location: loginform.php");
        exit;
    }

    $stmt->close();
    $conn->close();
}
?>
