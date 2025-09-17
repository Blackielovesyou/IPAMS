<?php
include("db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $userId = intval($_POST['user_id']);

    // Default password
    $defaultPassword = "password123";
    $hashedPassword = password_hash($defaultPassword, PASSWORD_BCRYPT);

    // Fetch user's email
    $stmt = $conn->prepare("SELECT email, first_name FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) { 
        $email = $user['email'];
        $firstName = $user['first_name'];

        // Update password
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashedPassword, $userId);

        if ($stmt->execute()) {
            // Send email notification
            $subject = "Password Reset Notification";
            $message = "Hi $firstName,\n\nYour password has been successfully reset.\n\nNew password: $defaultPassword\n\nPlease log in and change your password immediately for security reasons.";
            $headers = "From: no-reply@yourdomain.com";

            mail($email, $subject, $message, $headers);

            http_response_code(200);
            echo "Password reset successfully and email sent";
        } else {
            http_response_code(500);
            echo "Error resetting password";
        }
    } else {
        http_response_code(404);
        echo "User not found";
    }
}
?>
