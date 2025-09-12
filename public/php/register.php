<?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $contact_number = $_POST['contact_number'];
    $role = "Applicant"; // ✅ set default role to Applicant for new accounts

    $sql = "INSERT INTO users (first_name, middle_name, last_name, email, password, contact_number, role) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $first_name, $middle_name, $last_name, $email, $password, $contact_number, $role);

    if ($stmt->execute()) {
        // Registration successful → redirect to login form
        header("Location: loginform.php?registered=success");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
