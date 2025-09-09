<?php
/*
    Controller: login_as_guest.php
    Purpose: Logs in a user as a guest by destroying any existing session and redirecting to the home page.
*/

session_start();

// Clear all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: /OMS/app/views/home.php");
exit();
?>
