<?php
session_start();

// اتصال به دیتابیس
require_once 'database.php';
//$conn = db_connect();


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

/*
// Validate usernameformat (example: alphanumeric + underscore)
if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $customerUserName)) {
die("نام کاربری: 3-20 کاراکتر, فقط حروف, اعداد, _");
}
*/

    // دریافت داده‌ها و پاکسازی
    $login_username = test_input($_POST["loginUsername"]);
    $login_password = test_input($_POST["loginPassword"]);

    if (empty($login_username) || empty($login_password)) {
        $_SESSION['emptyfield_login'] = "همه فیلدها باید تکمیل شوند";
        //$conn = null;
        mysqli_close($conn);
        header("Location: login.php");
        exit();
    }

    $query1 = "SELECT * FROM manager_info 
        WHERE (manager_username = ? OR manager_phone = ?) AND manager_password = ?";
    $stmt1 = mysqli_prepare($conn, $query1);
    mysqli_stmt_bind_param($stmt1, "sss", $login_username, $login_username, $login_password);
    mysqli_stmt_execute($stmt1);
    $result1 = mysqli_stmt_get_result($stmt1);

    $query2 = "SELECT * FROM customer_info 
        WHERE (customer_username = ? OR customer_phone = ?) AND customer_password = ?";
    $stmt2 = mysqli_prepare($conn, $query2);
    mysqli_stmt_bind_param($stmt2, "sss", $login_username, $login_username, $login_password);
    mysqli_stmt_execute($stmt2);
    $result2 = mysqli_stmt_get_result($stmt2);

    if (mysqli_num_rows($result1) == 1 && mysqli_num_rows($result2) == 1) {
        $_SESSION['double_reg_error'] = " لطفا با یک نام کاربری دیگر ثبت نام کنید";
        //$conn = null;
        mysqli_close($conn);
        // i can redirect to register_simple.php
        header("Location: register_simple.php");
        exit();
    } elseif (mysqli_num_rows($result1) == 1) {
        $row1 = mysqli_fetch_array($result1);
        $_SESSION['manager_username'] = $row1['manager_username'];
        $_SESSION['manager_id'] = $row1['manager_id'];
        //if !empty($row1['first_name']) then (!empty($row1['last_name']) and !empty($row1['manager_phone']))
        if (!empty($row1['first_name'])) {
            $_SESSION['manager_firstName'] = $row1['first_name'];
            $_SESSION['manager_lastName'] = $row1['last_name'];
            $_SESSION['login_confirm'] = " خوش آمدید ";
        } else {
            $_SESSION['login_confirm'] = " اطلاعات شخصی خود را تکمیل کنید ";
        }
        //mysqli_close($conn);
        //$conn = null;
        header("Location: manager_page.php");
        exit(); 
        
        
        /* 
        // Set salon_id if exists
        $querySalon = "SELECT * FROM salon WHERE manager_info_id = ?";
        $stmtSalon = mysqli_prepare($conn, $querySalon);
        mysqli_stmt_bind_param($stmtSalon, "i", $row1['manager_id']);
        mysqli_stmt_execute($stmtSalon);
        $resultSalon = mysqli_stmt_get_result($stmtSalon);
        if ($rowSalon = mysqli_fetch_array($resultSalon)) {
            $_SESSION['salon_id'] = $rowSalon['salon_id'];
            mysqli_close($conn);
            header("Location: manager_page.php");
            exit();
        } else {
            // No salon yet, force to salon info form
            mysqli_close($conn);
            header("Location: manager_profile.php");
            $_SESSION[''] = "";
            exit();
        }  
        */

    } elseif (mysqli_num_rows($result2) == 1) {
        $row2 = mysqli_fetch_array($result2);
        $_SESSION['customer_username'] = $row2['customer_username'];
        $_SESSION['customer_id'] = $row2['customer_id'];
        //if !empty($row2['first_name']) then (!empty($row2['last_name']) and !empty($row2['customer_phone']))
        if (!empty($row2['first_name'])) {
            $_SESSION['customer_firstName'] = $row2['first_name'];
            $_SESSION['customer_lastName'] = $row2['last_name'];
            $_SESSION['login_confirm'] = " خوش آمدید ";
        } else {
            $_SESSION['login_confirm'] = " اطلاعات شخصی خود را تکمیل کنید ";
        }
        //mysqli_close($conn);
        //$conn = null;
        header("Location: customer_page.php");
        exit();
    } else {
        $_SESSION['reg_error'] = "برای ورود به سایت ابتدا باید ثبت نام کنید";
        //$conn = null;
        mysqli_close($conn);
        // i can redirect to register_simple.php
        header("Location: login.php");
        exit();
    }
}
