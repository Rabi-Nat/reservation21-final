<?php
session_start();
require_once 'database.php';


// پردازش فرم ارسال شده
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    /*
    // Validate usernameformat (example: alphanumeric + underscore)
    if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $customerUserName)) {
        die("نام کاربری: 3-20 کاراکتر, فقط حروف, اعداد, _");
    }
    */

    // Sanitize inputs
    $customerUserName = test_input($_POST["customerUserName"]);
    $customerPassword = test_input($_POST["customerPassword"]);
    $customerConfirmPassword = test_input($_POST["customerConfirmPassword"]);

    // اعتبارسنجی فیلدهای خالی
    if (empty($customerUserName) || empty($customerPassword) || empty($customerConfirmPassword)) {
        $_SESSION['empty_fields'] = "همه فیلدها باید پر شوند";
        mysqli_close($conn);
        header("Location: register_simple.php");
        exit();
    }

    // بررسی تطابق پسوردها
    if ($customerPassword !== $customerConfirmPassword) {
        $_SESSION['password_mismatch'] = "هر دو پسورد باید با هم یکسان باشند";
        mysqli_close($conn);
        header("Location: register_simple.php");
        exit();
    }

    // بررسی وجود نام کاربری در دیتابیس
    $query = "SELECT * FROM customer_info WHERE customer_username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $customerUserName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $query2 = "SELECT * FROM manager_info WHERE manager_username = ?";
    $stmt2 = mysqli_prepare($conn, $query2);
    mysqli_stmt_bind_param($stmt2, "s", $customerUserName);
    mysqli_stmt_execute($stmt2);
    $result2 = mysqli_stmt_get_result($stmt2);

    if (mysqli_num_rows($result) > 0 || mysqli_num_rows($result2) > 0) {
        $_SESSION['username_error'] = " این نام کاربری قبلا ثبت شده است. لطفا یک نام کاربری دیگر انتخاب کنید ";
        mysqli_close($conn);
        header("Location: register_simple.php");
        exit();
    } else {
        $insert_query = "INSERT INTO customer_info (customer_username, customer_password) VALUES (?, ?)";
        $stmt2 = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt2, "ss", $customerUserName, $customerPassword);
        mysqli_stmt_execute($stmt2);

        $customerId = mysqli_insert_id($conn);

        $_SESSION['reg_confirm'] = " اطلاعات شخصی خود را تکمیل کنید ";
        $_SESSION['customer_id'] = $customerId;
        $_SESSION['customer_username'] = $customerUserName;

        unset(
            $_SESSION['customer_firstName'],
            $_SESSION['customer_lastName']
        );

        mysqli_close($conn);
        header("Location: customer_page.php");
        exit();
    }
}

