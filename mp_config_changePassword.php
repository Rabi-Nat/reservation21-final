<?php
session_start();
require_once 'database.php';

$manager_id = $_SESSION['manager_id'];
//$salon_id   = $_SESSION['salon_id'];

if (empty($_SESSION['manager_id'])) {
    $_SESSION['login_error'] = "ابتدا باید به سایت ورود کنید";
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    $currentPassword    = test_input($_POST['currentPassword']);
    $newPassword        = test_input($_POST['newPassword']);
    $confirmNewPassword = test_input($_POST['confirmNewPassword']);

    if ($newPassword === '' || $currentPassword === '' || $confirmNewPassword === '') {
        $_SESSION['password_change_empty'] = "لطفا همهٔ فیلدها را پر کنید";
        mysqli_close($conn);
        header("Location: manager_profile.php#changePasswordModal");
        exit();
    }

    if ($newPassword !== $confirmNewPassword) {
        $_SESSION['password_change_mismatch'] = "هر دو پسورد جدید باید با هم یکسان باشند";
        //$conn = null;
        mysqli_close($conn);
        header("Location: manager_profile.php#changePasswordModal");
        exit();
    }


    $query = "SELECT manager_username, manager_password FROM manager_info WHERE manager_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $manager_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if ($currentPassword == $row['manager_password']) {
            $query2 = "UPDATE manager_info SET manager_password = ? WHERE manager_id = ?";
            $stmt2 = mysqli_prepare($conn, $query2);
            mysqli_stmt_bind_param($stmt2, "si", $newPassword, $manager_id);
            mysqli_stmt_execute($stmt2);
            $_SESSION['password_change_confirm'] = "رمز عبور با موفقیت تغییر یافت";
        } else {
            $_SESSION['current_password_mismatch'] = "رمز عبور فعلی اشتباه است";
            //$conn = null;
            mysqli_close($conn);
            header("Location: manager_profile.php#changePasswordModal");
            exit();
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_stmt_close($stmt2);
    mysqli_close($conn);
    header("Location: manager_profile.php#advanced-settings");
    exit();
}
