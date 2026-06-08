<?php
session_start();
require_once 'database.php';

$customer_id = $_SESSION['customer_id'];
//$salon_id   = $_SESSION['salon_id'];

if (empty($_SESSION['customer_id'])) {
    $_SESSION['login_error'] = "ابتدا باید به سایت ورود کنید";
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    $currentPassword    = test_input($_POST['oldPassword']);
    $newPassword        = test_input($_POST['newPassword']);
    $confirmNewPassword = test_input($_POST['confirmNewPassword']);

    if ($newPassword === '' || $currentPassword === '' || $confirmNewPassword === '') {
        $_SESSION['password_change_empty'] = "لطفا همهٔ فیلدها را پر کنید";
        mysqli_close($conn);
        header("Location: customer_profile.php#changePasswordModal2");
        exit();
    }

    if ($newPassword !== $confirmNewPassword) {
        $_SESSION['password_change_mismatch'] = "هر دو پسورد جدید باید با هم یکسان باشند";
        //$conn = null;
        mysqli_close($conn);
        header("Location: customer_profile.php#changePasswordModal2");
        exit();
    }


    $query = "SELECT customer_username, customer_password FROM customer_info WHERE customer_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $customer_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if ($currentPassword == $row['customer_password']) {
            $query2 = "UPDATE customer_info SET customer_password = ? WHERE customer_id = ?";
            $stmt2 = mysqli_prepare($conn, $query2);
            mysqli_stmt_bind_param($stmt2, "si", $newPassword, $customer_id);
            mysqli_stmt_execute($stmt2);
            $_SESSION['password_change_confirm'] = "رمز عبور با موفقیت تغییر یافت";
        } else {
            $_SESSION['current_password_mismatch'] = "رمز عبور فعلی اشتباه است";
            //$conn = null;
            mysqli_close($conn);
            header("Location: customer_profile.php#changePasswordModal2");
            exit();
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_stmt_close($stmt2);
    mysqli_close($conn);
    header("Location: customer_profile.php#advanced-settings");
    exit();
}
