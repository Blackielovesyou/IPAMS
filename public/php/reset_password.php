<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

include("db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $userId = intval($_POST['user_id']);
    $email = $_POST['user_email'];

    // Generate a random 8-character password
    $defaultPassword = bin2hex(random_bytes(4));
    $hashedPassword = password_hash($defaultPassword, PASSWORD_BCRYPT);

    // Get user info
    $stmt = $conn->prepare("SELECT email, first_name FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        http_response_code(404);
        echo "User not found";
        exit;
    }

    $firstName = $user['first_name'];

    // Update password
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashedPassword, $userId);

    if (!$stmt->execute()) {
        http_response_code(500);
        echo "Error resetting password";
        exit;
    }

    // Send email with PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'test001sender@gmail.com';  // Gmail account
        $mail->Password = 'cofmtrwsqyvafkjo';        // App password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use TLS
        $mail->Port = 587; // TLS port

        // Optional: bypass SSL verification for local testing
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];

        $mail->setFrom('test001sender@gmail.com', 'IPAMS System');
        $mail->addAddress($email, $firstName);

        $mail->isHTML(false);
        $mail->Subject = 'Password Reset Notification';
        $mail->Body = "Hi $firstName,\n\nYour password has been reset.\nNew password: $defaultPassword";

        $mail->send();

        http_response_code(200);
        echo "Password reset successfully and email sent";

    } catch (Exception $e) {
        http_response_code(500);
        echo "Password reset but email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>