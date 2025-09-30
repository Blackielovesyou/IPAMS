<?php
include("db.php");
include("log_action.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $contact_number = $_POST['contact_number'];
    $role = "applicant";

    // Generate email verification token
    $verification_token = bin2hex(random_bytes(32));

    $sql = "INSERT INTO users (first_name, middle_name, last_name, email, password, contact_number, role, email_verified, verification_token) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 0, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $first_name, $middle_name, $last_name, $email, $password, $contact_number, $role, $verification_token);

    if ($stmt->execute()) {
        $newUserId = $stmt->insert_id;
        logAction($conn, $newUserId, $role, "register", "New user registered: $email");

        // Send verification email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'test001sender@gmail.com'; // Your Gmail
            $mail->Password = 'cofmtrwsqyvafkjo';       // Gmail App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('test001sender@gmail.com', 'IPAMS System');
            $mail->addAddress($email, $first_name);

            // Use localhost for local testing
            $verificationLink = "http://localhost/IPAMS/public//php/verify_email.php?token=$verification_token";

            $mail->isHTML(true);
            $mail->Subject = 'Email Verification';
            $mail->Body = "Hi $first_name,<br><br>
                    Please verify your email by clicking the link below:<br>
                    <a href='$verificationLink'>$verificationLink</a><br><br>
                    Thank you!";

            $mail->send();
        } catch (Exception $e) {
            error_log("Mailer Error: {$mail->ErrorInfo}");
        }


        header("Location: loginform.php?registered=success");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>