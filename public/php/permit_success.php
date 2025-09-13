<?php
session_start();
if (!isset($_SESSION['success'])) {
    header("Location: building_permit.php"); 
    exit;
}
unset($_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Permit Success</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5 text-center">
        <div class="alert alert-success p-4">
            <h4 class="fw-bold">Application Submitted Successfully!</h4>
            <p>Your building permit application has been received. You'll receive a confirmation email shortly.</p>
            <a href="main_page.php" class="btn btn-primary mt-3">Back to Home</a>
        </div>
    </div>
</body>
</html>
