<?php
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy session
session_destroy();

//mysqli_close($conn);

// Redirect to login
header("Location: login.php");
exit();
?>