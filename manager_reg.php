<?php
session_start();
require_once 'database.php';
//$conn = db_connect();


// پردازش فرم ارسال شده
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    /*
    // Validate usernameformat (example: alphanumeric + underscore)
    if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $customerUserName)) {
        die("نام کاربری: 3-20 کاراکتر, فقط حروف, اعداد, _");
    }
    */

    // Sanitize inputs
    $managerUserName = test_input($_POST["managerUserName"]);
    $managerPassword = test_input($_POST["managerPassword"]);
    $managerConfirmPassword = test_input($_POST["managerConfirmPassword"]);

    // اعتبارسنجی فیلدهای خالی
    if (empty($managerUserName) || empty($managerPassword) || empty($managerConfirmPassword)) {
        $_SESSION['empty_fields'] = "همه فیلدها باید پر شوند";
        //$conn = null;
        mysqli_close($conn);
        header("Location: register_simple.php");
        exit();
    }

    // بررسی تطابق پسوردها
    if ($managerPassword !== $managerConfirmPassword) {
        $_SESSION['password_mismatch'] = "هر دو پسورد باید با هم یکسان باشند";
        //$conn = null;
        mysqli_close($conn);
        header("Location: register_simple.php");
        exit();
    }

    // بررسی وجود نام کاربری در دیتابیس
    $query = "SELECT * FROM manager_info WHERE manager_username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $managerUserName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $query2 = "SELECT * FROM customer_info WHERE customer_username = ?";
    $stmt2 = mysqli_prepare($conn, $query2);
    mysqli_stmt_bind_param($stmt2, "s", $managerUserName);
    mysqli_stmt_execute($stmt2);
    $result2 = mysqli_stmt_get_result($stmt2);

    if (mysqli_num_rows($result) > 0 || mysqli_num_rows($result2) > 0) {
        $_SESSION['username_error'] = " این نام کاربری قبلا ثبت شده است. لطفا یک نام کاربری دیگر انتخاب کنید ";
        mysqli_close($conn);
        //$conn = null;
        header("Location: register_simple.php");
        exit();
    } else {
        $insert_query = "INSERT INTO manager_info (manager_username, manager_password) VALUES (?, ?)";
        $stmt3 = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt3, "ss", $managerUserName, $managerPassword);
        mysqli_stmt_execute($stmt3);
        // we can not call get_result() and fetch_array() on INSERT INTO and UPDATE
        //$result2 = mysqli_stmt_get_result($stmt2);
        //$row = mysqli_fetch_array($result2);
        // instead of get_result() and fetch_array()
        // we use mysqli_insert_id()

        //for fetch id from UPDATE: 1. we should use SELECT (after UPDATE) and then 
        // 2. we should use get_result() and fetch_array()

        // fetch the auto‑generated ID. this is used only for INSERT INTO
        $managerId = mysqli_insert_id($conn);

        $_SESSION['reg_confirm'] = " اطلاعات شخصی خود را تکمیل کنید ";
        $_SESSION['manager_id'] = $managerId;
        $_SESSION['manager_username'] = $managerUserName;
        //$_SESSION['manager_firstName'] = "";
        //$_SESSION['manager_lastName'] = "";
        unset(
            $_SESSION['manager_firstName'],
            $_SESSION['manager_lastName']
        );

        mysqli_close($conn);
        //$conn = null;
        header("Location: manager_page.php");
        exit();
    }
}

// Close connection
//mysqli_close($conn);
