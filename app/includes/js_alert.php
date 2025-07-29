<?php
/*
    This file contains utility functions for the application.
*/

function jsAlertRedirect($message, $redirect_url="../views/login.php") {
    /*
    Function to display a JavaScript alert and redirect to a specified URL.

    Args:
    - $message (string): The message to display in the alert.
    - $redirect_url (string): The URL to redirect to after the alert.
    */
    return "<script>alert('$message'); window.location.href = '$redirect_url';</script>";
}