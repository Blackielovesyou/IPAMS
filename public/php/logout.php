<?php
session_start();
session_unset();
session_destroy();

// Clear session cookie
setcookie(session_name(), "", time() - 3600, "/");

// Redirect back to login page
header("Location: loginform.php?logout=1");
exit;
?>
