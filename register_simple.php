<?php
// start session
session_start();


// code:01 - Pull errors (if any) from the session, then clear them
$empty_fields = $_SESSION['empty_fields'] ?? "";
$username_error  = $_SESSION['username_error']  ?? "";
$password_mismatch  = $_SESSION['password_mismatch']  ?? "";
$reg_error = $_SESSION['reg_error'] ?? "";
$double_reg_error = $_SESSION['double_reg_error'] ?? "";

unset(
    $_SESSION['empty_fields'],
    $_SESSION['username_error'],
    $_SESSION['password_mismatch'],
    $_SESSION['reg_error'],
    $_SESSION['double_reg_error']
);

/* معادل با code:01
if (isset($_SESSION['empty_fields']) && !empty($_SESSION['empty_fields'])) {
    $empty_fields = $_SESSION['empty_fields'];
} else {
    $empty_fields = array();
}

if (isset($_SESSION['password_mismatch']) && !empty($_SESSION['password_mismatch'])) {
    $password_mismatch = $_SESSION['password_mismatch'];
} else {
    $password_mismatch = array();
}

if (isset($_SESSION['username_error']) && !empty($_SESSION['username_error'])) {
    $username_error = $_SESSION['username_error'];
} else {
    $username_error = array();
}

unset($_SESSION['empty_fields'], $_SESSION['password_mismatch'], $_SESSION['password_mismatch']);
 */

?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Cache Busting -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    <title>ثبت نام</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Marcellus&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/register-simple.css">
    <!-- Favicons - Place favicon.ico in the root directory -->
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

    <script src="assets/js/register_simple.js"></script>


</head>

<body class="register-bg">
    <header>
        <div class="header-row"
            style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
            <div id="alert_1">
                <?php if (!empty($empty_fields)): ?>
                    <div class="notification2"><?php echo $empty_fields; ?></div>
                <?php elseif (!empty($username_error)): ?>
                    <div class="notification2"><?php echo $username_error; ?></div>
                <?php elseif (!empty($password_mismatch)): ?>
                    <div class="notification2"><?php echo $password_mismatch; ?></div>
                <?php elseif (!empty($reg_error)): ?>
                    <div class="notification2"><?php echo $reg_error; ?></div>
                <?php endif; ?>
            </div>
            <a href="landing.php" class="back-btn">بازگشت </a>
        </div>
    </header>
    <div class="register-box">
        <div class="register-title">ثبت نام</div>
        <div class="user-type-row">
            <button class="user-type-btn active" id="customerBtn" type="button">مشتری</button>
            <button class="user-type-btn" id="managerBtn" type="button">مدیر سالن</button>
        </div>
        <form action="customer_reg.php" method="post" class="register-form active form-grid" id="customerForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="customerUserName">نام کاربری *</label>
                    <input type="text" id="customerUserName" name="customerUserName">
                </div>
            </div>
            </br>
            <div class="form-row">
                <div class="form-group">
                    <label for="customerPassword">پسورد *</label>
                    <input type="password" id="customerPassword" name="customerPassword" autocomplete="new-password">
                </div>
            </div>
            </br>
            <div class="form-row">
                <div class="form-group">
                    <label for="customerConfirmPassword">تکرار پسورد *</label>
                    <input type="password" id="customerConfirmPassword" name="customerConfirmPassword" autocomplete="new-password">
                </div>
            </div>
            </br>
            <button type="submit" name="submit" class="submit-btn">ثبت نام</button>
            <button type="button" name="google_submit" class="google-btn"><img
                    src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/google/google-original.svg" alt="Google"
                    width="22"> ثبت نام با گوگل
            </button>

            <div class="form-footer">
                <p><a href="login.php" class="forgot-password-link">قبلا ثبت نام کرده ام (ورود)</a></p>
            </div>

        </form>
        <form action="manager_reg.php" method="post" class="register-form  form-grid" id="managerForm">
            <div class="form-row">
                <div class="form-group" >
                    <label for="managerUserName">نام کاربری *</label>
                    <input type="text" id="managerUserName" name="managerUserName">
                </div>
            </div>
            </br>
            <div class="form-row">
                <div class="form-group">
                    <label for="managerPassword">پسورد *</label>
                    <input type="password" id="managerPassword" name="managerPassword" autocomplete="new-password">
                </div>
            </div>
            </br>
            <div class="form-row">
                <div class="form-group">
                    <label for="managerConfirmPassword">تکرار پسورد *</label>
                    <input type="password" id="managerConfirmPassword" name="managerConfirmPassword" autocomplete="new-password">
                </div>
            </div>
            </br>
            <button type="submit" name="submit" class="submit-btn">ثبت نام</button>
            <button type="button" name="google_submit" class="google-btn"><img
                    src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/google/google-original.svg" alt="Google"
                    width="22"> ثبت نام با گوگل
            </button>
            <div class="form-footer">
                <p><a href="login.php" class="forgot-password-link">قبلا ثبت نام کرده ام (ورود)</a></p>
            </div>
        </form>
    </div>

    <script>


    </script>

    <!-- Boostrap CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>

</body>

</html>