<?php
session_start();

//Pull errors (if any) from the session, then clear them
$emptyfield_login = $_SESSION['emptyfield_login'] ?? "";
$manager_page_error = $_SESSION['manager_page_error'] ?? "";
$manager_profile_error = $_SESSION['manager_profile_error'] ?? "";
$profile_login_error = $_SESSION['mp_login_error'] ?? "";
$reg_error = $_SESSION['reg_error'] ?? "";

// unset flash sessions
unset(
    $_SESSION['emptyfield_login'],
    $_SESSION['manager_page_error'],     //?
    $_SESSION['manager_profile_error'],   //?
    $_SESSION['mp_login_error'],           //?
    $_SESSION['reg_error']
);

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

    <title>ورود به حساب کاربری </title>
    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Marcellus&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/register-simple.css">
    <!-- Favicons - Place favicon.ico in the root directory -->
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="assets/css/login.css">

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

</head>

<body class="register-bg">
    <header>
        <div class="header-row"
            style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
            <div id="alert_1">
            </div>
            <a href="landing.php" class="back-btn">بازگشت </a>
        </div>
    </header>
    <div class="register-box">
        <div class="register-title">ورود به حساب کاربری </div>
        <form action="login_config.php" method="post" class="register-form active form-grid" id="loginform">
            <div class="form-row">
                <div class="form-group">
                    <label for="loginUsername">نام کاربری یا شماره تلفن *</label>
                    <input type="text" id="loginUsername" name="loginUsername">
                </div>
            </div>
            </br>
            <div class="form-row">
                <div class="form-group">
                    <label for="loginPassword">پسورد *</label>
                    <input type="password" id="loginPassword" name="loginPassword" autocomplete="new-password">
                </div>
            </div>
            </br>
            <button type="submit" name="submit" class="submit-btn">ورود </button>
            <button type="button" name="google_submit" class="google-btn"><img
                    src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/google/google-original.svg" alt="Google"
                    width="22"> ورود با گوگل</button>
            <div class="form-footer">
                <p><a href="#" class="forgot-password-link" onclick="openModal(); return false;">فراموشی رمز</a></p>
                <p><a href="register_simple.php" class="forgot-password-link">ثبت نام نکرده اید؟ </a></p>
            </div>
        </form>
    </div>

    <!-- Modal for Forgot Password -->
    <div id="forgotPasswordModal" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:16px;max-width:350px;width:90vw;padding:32px 24px;box-shadow:0 8px 32px rgba(0,0,0,0.18);position:relative;display:flex;flex-direction:column;align-items:center;">
            <button onclick="closeModal()" style="position:absolute;top:10px;left:10px;background:none;border:none;font-size:22px;color:#888;cursor:pointer;">&times;</button>
            <div style="font-size:1.2rem;font-weight:500;margin-bottom:18px;">بازیابی رمز عبور</div>
            <div style="width:100%;margin-bottom:12px;">
                <label for="forgotPhoneInput" style="font-size:0.95rem;">شماره موبایل خود را وارد کنید:</label>
                <input type="text" id="forgotPhoneInput" style="width:100%;margin-top:7px;padding:8px 10px;border-radius:7px;border:1px solid #ccc;outline:none;direction:ltr;" placeholder="09xxxxxxxxx">
            </div>
            <button onclick="sendForgotPasswordCode()" style="width:100%;background:#bd7a22ff;color:#fff;padding:10px 0;border:none;border-radius:7px;font-size:1rem;font-weight:500;cursor:pointer;">ارسال رمز عبور</button>
        </div>
    </div>

    <?php if (!empty($emptyfield_login)): ?>
        <div class="notification2"><?php echo $emptyfield_login; ?></div>
    <?php elseif (!empty($login_error)): ?>
        <div class="notification2"><?php echo $ogin_error; ?></div>
    <?php elseif (!empty($manager_page_error)): ?>
        <div class="notification2"><?php echo $manager_page_error; ?></div>
    <?php elseif (!empty($manager_profile_error)): ?>
        <div class="notification2"><?php echo $manager_profile_error; ?></div>
    <?php elseif (!empty($reg_error)): ?>
        <div class="notification2"><?php echo $reg_error; ?></div>
    <?php endif; ?>


    <!-- Boostrap CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous">
    </script>
    <script>
        function openModal() {
            document.getElementById('forgotPasswordModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('forgotPasswordModal').style.display = 'none';
        }

        function sendForgotPasswordCode() {
            var phone = document.getElementById('forgotPhoneInput').value;
            if (!phone) {
                alert('لطفاً شماره موبایل را وارد کنید.');
                return;
            }
            // اینجا می‌توانید کد ارسال درخواست به سرور را اضافه کنید
            alert('در صورت وجود حساب، رمز به شماره ' + phone + ' ارسال خواهد شد.');
            closeModal();
        }
    </script>

</body>

</html>