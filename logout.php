<?php
session_start();  // Start the session

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Delete the "Remember Me" cookie if it exists
if (isset($_COOKIE['user_id'])) {
    setcookie("user_id", "", time() - 3600, "/");  // Expire the cookie
}

// Redirect to the sign-in page
header("Location: sign-in.php");
exit();
?>
