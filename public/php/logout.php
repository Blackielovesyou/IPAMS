<?php
session_start();
include("db.php");
include("log_action.php");

if (isset($_SESSION['id']) && isset($_SESSION['role'])) {
    logAction($conn, $_SESSION['id'], $_SESSION['role'], "logout", "User logged out");
}

session_unset();
session_destroy();
setcookie(session_name(), "", time() - 3600, "/");

// Redirect to root index.php
header("Location: ../../index.php");
exit;
?>
