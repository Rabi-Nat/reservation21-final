<?php
// mp_config_option.php
session_start();
require_once 'database.php';
//$conn = db_connect();

$manager_id = $_SESSION['manager_id'];
$salon_id   = $_SESSION['salon_id'];

if (empty($_SESSION['manager_id'])) {
    $_SESSION['login_error'] = "ابتدا باید به سایت ورود کنید";
    header("Location: login.php");
    exit();
}

if (empty($_SESSION['salon_id'])) {
    $_SESSION['salon_error'] = "ابتدا فرم اطلاعات سالن را تکمیل کنید";
    mysqli_close($conn);
    //$conn = null;
    header("Location: manager_profile.php#option_salon");
    exit();
}


// ذخیرهٔ لیست امکانات (چند ردیف)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // دریافت آرایه‌ها (همه به صورت آرایه ارسال می‌شوند)
    $salon_options = $_POST['salon_option'] ?? [];


    // همۀ آرایه‌ها باید حداقل به‌اندازهٔ count ای که استفاده می‌کنیم باشند
    $count = count($salon_options);

    if ($count === 0) {
        $_SESSION['mp_emptyfield_option_error'] = "هیچ امکاناتی برای ذخیره ارسال نشده است.";
        header("Location: manager_profile.php#option_salon");
        exit();
    }

    // آماده‌سازی کوئری‌ها (یک‌بار)
    $insertSql = "INSERT INTO salon_option (option_name, salon_id)
                  VALUES (?,?)";
    $stmtInsert = mysqli_prepare($conn, $insertSql);

    $updateSql = "UPDATE salon_option SET option_name = ? WHERE option_id = ?";
    $stmtUpdate = mysqli_prepare($conn, $updateSql);

    $checkSql = "SELECT option_id FROM salon_option WHERE salon_id = ? AND option_name = ? LIMIT 1";
    $stmtCheck = mysqli_prepare($conn, $checkSql);


    for ($i = 0; $i < $count; $i++) {
        // خواندن ایمن مقادیر — بررسی می‌کنیم مقدار در آرایه هست یا نه
        $salon_option = isset($salon_options[$i]) ? test_input($salon_options[$i]) : '';

        if (empty($salon_option)) {
            $_SESSION['mp_emptyfield_error'] = "فیلدهای ستاره دار باید تکمیل شوند";
            mysqli_close($conn);
            //$conn = null;
            header("Location: manager_profile.php#option_salon");
            exit();
        }

        // بررسی وجود امکانات با همین نام برای همین سالن
        mysqli_stmt_bind_param($stmtCheck, "is", $salon_id, $salon_option);
        mysqli_stmt_execute($stmtCheck);
        mysqli_stmt_store_result($stmtCheck);

        if (mysqli_stmt_num_rows($stmtCheck) > 0) {
            // موجود است -> UPDATE
            mysqli_stmt_bind_result($stmtCheck, $existing_option_id);
            mysqli_stmt_fetch($stmtCheck);
            // UPDATE: option_name=? WHERE option_id=?
            mysqli_stmt_bind_param($stmtUpdate, "si", $salon_option, $existing_option_id);
            mysqli_stmt_execute($stmtUpdate);
            // اگر لازم است، میتوانید بررسی کنید mysqli_stmt_affected_rows
        } else {
            // موجود نیست -> INSERT
            // VALUES (salon_id, service_name, duration_min, price, discount, final_price, discount_start_date, discount_end_date, is_active)
            mysqli_stmt_bind_param($stmtInsert, "si", $salon_option, $salon_id);
            mysqli_stmt_execute($stmtInsert);
        }
        // پاکسازی result برای آماده‌سازی iteration بعدی
        mysqli_stmt_free_result($stmtCheck);
    } // end for  

    // بستن statement ها و اتصال
    if ($stmtInsert) mysqli_stmt_close($stmtInsert);
    if ($stmtUpdate) mysqli_stmt_close($stmtUpdate);
    if ($stmtCheck) mysqli_stmt_close($stmtCheck);
    mysqli_close($conn);
    //$conn = null;

    header("Location: manager_profile.php#option_salon");
    exit();
}


// حذف یک سرویس ثبت‌شده
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_option'])) {

    // انتظار داریم service_id[] و delete_service[] ارسال شود (معمولاً فقط یک مقدار چون هر فرم برای یک ردیف است)
    $option_ids = $_POST['option_id'] ?? [];
    $salon_options_raw = $_POST['salon_option'] ?? [];

    // جلوگیری از ذخیره مقادیر خالی در دیتابیس
    $salon_options = array_values(array_filter(array_map('trim', $salon_options_raw), function ($v) {
        return $v !== '';
    }));

    if (count($option_ids) === 0) {
        $_SESSION['mp_delete_service_error'] = "درخواست حذف نامعتبر است.";
        header("Location: manager_profile.php#option_salon");
        exit();
    }

    $qdel = "DELETE FROM salon_option WHERE option_id = ?";
    $stdel = mysqli_prepare($conn, $qdel);

    foreach ($option_ids as $oid) {
        $option_id = intval($oid);
        if ($option_id <= 0) continue;
        mysqli_stmt_bind_param($stdel, 'i', $option_id);
        if (mysqli_stmt_execute($stdel)) {
            $_SESSION['mp_delete_option'] = "امکانات سالن با موفقیت حذف شد.";
        } else {
            $_SESSION['mp_not_delete_option'] = "خطا در حذف امکانات.";
        }
    }
    mysqli_stmt_close($stdel);
    mysqli_close($conn);
    //$conn = null;
    header("Location: manager_profile.php#option_salon");
    exit();
}


// اگر به اینجا رسیدی بدون POST، بازگرد
header("Location: manager_profile.php#option_salon");
exit();
