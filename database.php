<?php

$servername = "localhost";      // IP address of server or hostname.
$username = "root";             // Database username.
$password = "";                 // Database password.
$dbname = "beautysalon";        // Database name. The name of the website (or web application)

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname)
    or die("Couldn't connect to the database: " . mysqli_connect_error());


// Check connection
/* if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} */


/**
 * Test and sanitize input data
 * تست و پاکسازی داده‌های ورودی
 */
function test_input($data) {

    // basic HTML‑safe sanitization
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

?>