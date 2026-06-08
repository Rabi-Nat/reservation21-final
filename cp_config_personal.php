<?php
// mp_config_personal.php - نسخهٔ نهایی
session_start();
require_once 'database.php'; // اتصال $conn و تابع test_input() را اینجا دارید

$customer_id = isset($_SESSION['customer_id']) ? intval($_SESSION['customer_id']) : 0;

if (empty($customer_id)) {
    $_SESSION['login_error'] = "ابتدا باید وارد شوید";
    if (isset($conn) && $conn) mysqli_close($conn);
    header("Location: login.php");
    exit();
}


/* ---------- پردازش فرم ذخیره/بروزرسانی ---------- */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit']) && $_POST['submit'] === '1') {

    // دریافت و پاکسازی ورودی‌ها
    $cp_firstName    = test_input($_POST['firstName'] ?? '');
    $cp_lastName     = test_input($_POST['lastName'] ?? '');
    $cp_nationalCode = test_input($_POST['nationalCode'] ?? '');
    $cp_phoneNumber  = test_input($_POST['phoneNumber'] ?? '');
    $cp_customerEmail = test_input($_POST['customerEmail'] ?? '');

    // اعتبارسنجی پایه
    if ($cp_firstName === '' || $cp_phoneNumber === '') {
        $_SESSION['cp_emptyfield_error'] = "فیلدهای ستاره‌دار باید تکمیل شوند";
        mysqli_close($conn);
        header("Location: customer_profile.php");
        exit();
    }
    if (!preg_match('/^0\d{10}$/', $cp_phoneNumber)) {
        $_SESSION['cp_phone_error'] = "فرمت شماره تلفن نامعتبر است (مثال: 09123456789)";
        mysqli_close($conn);
        header("Location: customer_profile.php");
        exit();
    }
    if ($cp_nationalCode !== '' && !preg_match('/^\d{10}$/', $cp_nationalCode)) {
        $_SESSION['cp_nationalCode_error'] = "فرمت کد ملی نامعتبر است";
        mysqli_close($conn);
        header("Location: customer_profile.php");
        exit();
    }
    if ($cp_customerEmail !== '' && !filter_var($cp_customerEmail, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['cp_email_error'] = "فرمت ایمیل نامعتبر است";
        mysqli_close($conn);
        header("Location: customer_profile.php");
        exit();
    }

    // یکتا بودن شماره
    $query = "SELECT customer_id FROM customer_info WHERE customer_phone = ? AND customer_id <> ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        $_SESSION['cp_not_confirm1'] = "خطا در پرس‌وجو";
        mysqli_close($conn);
        header("Location: customer_profile.php");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "si", $cp_phoneNumber, $customer_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $_SESSION['duplicate_customer_phone'] = "این شماره قبلا ثبت شده است. لطفا شماره دیگری وارد کنید.";
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header("Location: customer_profile.php");
        exit();
    }
    mysqli_stmt_close($stmt);

    // خواندن رکورد فعلی
    $existingFirstName = $existingLastName = $existingNationalId = $existingPhone = $existingEmail = '';
    $checkSql = "SELECT first_name, last_name, national_id, customer_phone, customer_email FROM customer_info WHERE customer_id = ? LIMIT 1";
    $checkStmt = mysqli_prepare($conn, $checkSql);
    if (!$checkStmt) {
        $_SESSION['cp_not_confirm2'] = "خطا در آماده‌سازی بررسی رکورد.";
        mysqli_close($conn);
        header("Location: customer_profile.php");
        exit();
    }
    mysqli_stmt_bind_param($checkStmt, "i", $customer_id);
    mysqli_stmt_execute($checkStmt);
    $res = mysqli_stmt_get_result($checkStmt);
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $existingFirstName = $row['first_name'] ?? '';
        $existingLastName  = $row['last_name'] ?? '';
        $existingNationalId = $row['national_id'] ?? '';
        $existingPhone     = $row['customer_phone'] ?? '';
        $existingEmail     = $row['customer_email'] ?? '';

        $recordExists = true;
    } else {
        $recordExists = false;
    }
    mysqli_stmt_close($checkStmt);

    if (!$recordExists) {
        $_SESSION['cp_not_confirm3'] = "رکورد مدیر یافت نشد.";
        mysqli_close($conn);
        header("Location: customer_profile.php");
        exit();
    }


    // تعیین مقادیر نهایی (اگر کاربر فیلدی خالی گذاشت مقدار قبلی حفظ شود)
    $finalFirstName   = ($cp_firstName !== '')     ? $cp_firstName     : $existingFirstName;
    $finalLastName    = ($cp_lastName !== '')      ? $cp_lastName      : $existingLastName;
    $finalNationalId  = ($cp_nationalCode !== '')  ? $cp_nationalCode  : $existingNationalId;
    $finalPhone       = ($cp_phoneNumber !== '')   ? $cp_phoneNumber   : $existingPhone;
    $finalEmail       = ($cp_customerEmail !== '') ? $cp_customerEmail : $existingEmail;


    // UPDATE رکورد
    $updateSql = "UPDATE customer_info 
                  SET first_name = ?, last_name = ?, national_id = ?, customer_phone = ?, customer_email = ?
                  WHERE customer_id = ?";
    $updateStmt = mysqli_prepare($conn, $updateSql);
    if (! $updateStmt) {
        $_SESSION['cp_not_confirm4'] = "خطا در آماده‌سازی بروزرسانی.";
        mysqli_close($conn);
        header("Location: customer_profile.php");
        exit();
    }
    mysqli_stmt_bind_param(
        $updateStmt,
        "sssssi",
        $finalFirstName,
        $finalLastName,
        $finalNationalId,
        $finalPhone,
        $finalEmail,
        $customer_id
    );

    if (mysqli_stmt_execute($updateStmt)) {
        $_SESSION['cp_confirm'] = "اطلاعات با موفقیت ثبت شد";
        $_SESSION['customer_firstName'] = $finalFirstName;
        $_SESSION['customer_lastName']  = $finalLastName;

    } else {
        $_SESSION['cp_not_confirm5'] = "خطا در بروزرسانی اطلاعات";
    }

    mysqli_stmt_close($updateStmt);
    mysqli_close($conn);
    header("Location: customer_profile.php");
    exit();
}

/* ---------- soft clean (حذف اطلاعات شخصی و تنظیم عکس پیش‌فرض) ---------- */
if (isset($_POST['delete_personal']) && $_POST['delete_personal'] === '1') {

    $query = "UPDATE customer_info 
              SET first_name = '', last_name = '', national_id = '', customer_phone = '', customer_email = ''
              WHERE customer_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $customer_id);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['cp_confirm2'] = 'اطلاعات شخصی پاک شد.';
            unset($_SESSION['customer_firstName'], $_SESSION['customer_lastName']);
        } else {
            $_SESSION['cp_not_confirm6'] = 'خطا در پاک کردن اطلاعات.';
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['cp_not_confirm7'] = 'خطا در آماده‌سازی پاک‌سازی.';
    }

    mysqli_close($conn);
    header('Location: customer_profile.php');
    exit();
}
