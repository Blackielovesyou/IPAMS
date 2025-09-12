<?php
session_start();
include("db.php"); // database connection file

// Extend session lifetime (30 days)
ini_set('session.cookie_lifetime', 60*60*24*30);
ini_set('session.gc_maxlifetime', 60*60*24*30);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user by email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // ✅ Save success message and redirect
            $_SESSION['success'] = $user['role'];
            header("Location: loginform.php");
            exit;
        } else {
            $_SESSION['error'] = "Wrong password!";
            header("Location: loginform.php");
            exit;
        }
    } else {
        $_SESSION['error'] = "No user found with that email!";
        header("Location: loginform.php");
        exit;
    }
}
?>
