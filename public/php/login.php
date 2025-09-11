<?php
session_start();
include("db.php"); // database connection file

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

            if ($user['role'] == 'superadmin') {
                header("Location: super_admin.php");
                exit;
            } elseif ($user['role'] == 'admin') {
                header("Location:admin_dashboard.php");
                exit;
                
            } else {
                header("Location: main_page.php");
                exit;
            }


        } else {
            echo "❌ Wrong password!";
        }
    } else {
        echo "❌ No user found with that email!";
    }
}
?>