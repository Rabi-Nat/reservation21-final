<?php
// database.php — connection factory (safe reconnect)
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "beautysalon";

function db_connect() {
    static $conn = null;
    global $DB_HOST, $DB_USER, $DB_PASS, $DB_NAME;
    
    if ($conn instanceof mysqli) {
        // تست زنده بودن اتصال
        $res = @mysqli_query($conn, 'SELECT 1');
        if ($res !== false) {
            mysqli_free_result($res);
            return $conn;
        }
        @$conn->close();
        $conn = null;
    }
    
    // ساخت اتصال جدید
    $conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    if (!$conn) {
        error_log("DB connect error: " . mysqli_connect_error());
        die("Couldn't connect to the database: " . mysqli_connect_error());
    }
    
    // تنظیم charset و collation به صورت صحیح
    mysqli_set_charset($conn, 'utf8mb4');
    mysqli_query($conn, "SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'");
    mysqli_query($conn, "SET CHARACTER SET utf8mb4");
    mysqli_query($conn, "SET character_set_connection = utf8mb4");
    
    return $conn;
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>