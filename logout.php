<?php
// Start session
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: http://localhost/SFE%20Project/SignIn2/");
exit();
?>
