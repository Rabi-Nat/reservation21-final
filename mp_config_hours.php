<?php
// mp_config_hours.php
session_start();
require_once 'database.php';
//$conn = db_connect();

if (empty($_SESSION['manager_id'])) {
    $_SESSION['login_error'] = "ابتدا باید به سایت ورود کنید";
    header("Location: login.php");
    exit();
}

if (empty($_SESSION['salon_id'])) {
    $_SESSION['salon_error'] = "ابتدا فرم اطلاعات سالن را تکمیل کنید";
    mysqli_close($conn);
    //$conn = null;
    header("Location: manager_profile.php#working_hours");
    exit();
}

$manager_id = $_SESSION['manager_id'];
$salon_id   = $_SESSION['salon_id'];

//================= Delete a row =======================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_hour'])) {
    $hour_ids = [];
    if (isset($_POST['hour_id'])) {
        if (is_array($_POST['hour_id'])) $hour_ids = $_POST['hour_id'];
        else $hour_ids = [$_POST['hour_id']];
    }
    
    if (count($hour_ids) === 0) {
        $_SESSION['mp_delete_hour_error'] = "درخواست حذف نامعتبر است.";
    } else {
        $qdel = "DELETE FROM salon_hours WHERE hour_id = ? AND salon_id = ?";
        $stdel = mysqli_prepare($conn, $qdel);
        if (!$stdel) {
            $_SESSION['mp_delete_hour_error'] = "خطا در آماده‌سازی حذف: " . mysqli_error($conn);
        } else {
            foreach ($hour_ids as $hid) {
                $hour_id = intval($hid);
                if ($hour_id <= 0) continue;
                mysqli_stmt_bind_param($stdel, 'ii', $hour_id, $salon_id);
                if (mysqli_stmt_execute($stdel)) {
                    $_SESSION['mp_delete_hour'] = "ساعت کاری با موفقیت حذف شد.";
                } else {
                    $_SESSION['mp_not_delete_hour'] = "خطا در حذف ساعت کاری: " . mysqli_stmt_error($stdel);
                }
            }
            mysqli_stmt_close($stdel);
        }
    }
    
    // بررسی وجود رکوردهای باقیمانده
    $countQ = "SELECT COUNT(*) AS cnt FROM salon_hours WHERE salon_id = ?";
    $stmtCount = mysqli_prepare($conn, $countQ);
    mysqli_stmt_bind_param($stmtCount, 'i', $salon_id);
    mysqli_stmt_execute($stmtCount);
    mysqli_stmt_bind_result($stmtCount, $remaining);
    mysqli_stmt_fetch($stmtCount);
    mysqli_stmt_close($stmtCount);
    
    mysqli_close($conn);
    //$conn = null;
    
    if (!empty($remaining)) {
        header("Location: manager_profile.php#tbl_generated_hours");
    } else {
        header("Location: manager_profile.php#working_hours");
    }
    exit();
}

// =========== Save to database =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $days = $_POST['day'] ?? [];
    $open_hours = $_POST['open_hour'] ?? [];
    $close_hours = $_POST['close_hour'] ?? [];
    $statuses = $_POST['status'] ?? [];
    
    // ابتدا همه رکوردهای موجود برای این سالن را حذف کنید
    $deleteAllSql = "DELETE FROM salon_hours WHERE salon_id = ?";
    $stmtDeleteAll = mysqli_prepare($conn, $deleteAllSql);
    if (!$stmtDeleteAll) {
        $_SESSION['mp_hours_errors'] = ["خطا در آماده‌سازی حذف: " . mysqli_error($conn)];
        mysqli_close($conn);
        //$conn = null;
        header("Location: manager_profile.php#working_hours");
        exit();
    }
    
    mysqli_stmt_bind_param($stmtDeleteAll, "i", $salon_id);
    if (!mysqli_stmt_execute($stmtDeleteAll)) {
        $_SESSION['mp_hours_errors'] = ["خطا در حذف رکوردهای موجود: " . mysqli_stmt_error($stmtDeleteAll)];
        mysqli_stmt_close($stmtDeleteAll);
        mysqli_close($conn);
        //$conn = null;
        header("Location: manager_profile.php#working_hours");
        exit();
    }
    mysqli_stmt_close($stmtDeleteAll);
    
    // سپس فقط روزهای فعال را insert کنید
    $insertSql = "INSERT INTO salon_hours (salon_id, day_of_week, open_time, close_time, status) VALUES (?, ?, ?, ?, ?)";
    $stmtInsert = mysqli_prepare($conn, $insertSql);
    if (!$stmtInsert) {
        $_SESSION['mp_hours_errors'] = ["خطا در آماده‌سازی درج: " . mysqli_error($conn)];
        $conn = null;
        header("Location: manager_profile.php#working_hours");
        exit();
    }
    
    $errors = [];
    $count = count($days);
    
    for ($i = 0; $i < $count; $i++) {
        $day = test_input($days[$i]);
        $open_hour = test_input($open_hours[$i]);
        $close_hour = test_input($close_hours[$i]);
        $status_raw = test_input($statuses[$i]);
        $status = ($status_raw === 'فعال') ? 1 : 0;
        
        // فقط روزهای فعال را insert کنید
        if ($status === 1) {
            mysqli_stmt_bind_param($stmtInsert, "isssi", $salon_id, $day, $open_hour, $close_hour, $status);
            if (!mysqli_stmt_execute($stmtInsert)) {
                $errors[] = "خطا در درج روز {$day}: " . mysqli_stmt_error($stmtInsert);
            }
        }
    }
    
    mysqli_stmt_close($stmtInsert);
    
    // بررسی وجود رکوردهای باقیمانده
    $countQ = "SELECT COUNT(*) AS cnt FROM salon_hours WHERE salon_id = ?";
    $stmtCount = mysqli_prepare($conn, $countQ);
    mysqli_stmt_bind_param($stmtCount, 'i', $salon_id);
    mysqli_stmt_execute($stmtCount);
    mysqli_stmt_bind_result($stmtCount, $remaining);
    mysqli_stmt_fetch($stmtCount);
    mysqli_stmt_close($stmtCount);
    
    mysqli_close($conn);
    //$conn = null;
    
    if (!empty($errors)) {
        $_SESSION['mp_hours_errors'] = $errors;
    } else {
        $_SESSION['mp_hours_success'] = "ساعات با موفقیت ذخیره شد.";
    }
    
    if (!empty($remaining)) {
        header("Location: manager_profile.php#tbl_generated_hours");
    } else {
        header("Location: manager_profile.php#working_hours");
    }
    exit();
}

// اگر به اینجا رسیدیم بدون POST، بازگرد
mysqli_close($conn);
//$conn = null;
header("Location: manager_profile.php#working_hours");
exit();
?>