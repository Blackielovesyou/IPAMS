<?php
// db.php - Database Connection File

$host = "localhost";      // Your database host (usually localhost)
$user = "root";           // Your MySQL username
$pass = "";               // Your MySQL password
$dbname = "ipams"; // Your database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
