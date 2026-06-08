<?php
session_start();
require_once 'database.php';
//$conn = db_connect();

// بررسی ورود مدیر
if (empty($_SESSION['manager_id'])) {
    $_SESSION['mp_upload_portfolio_error'] = "ابتدا باید به سایت ورود کنید";
    //$conn = null;
    mysqli_close($conn);
    header("Location: login.php");
    exit();
}

if (empty($_SESSION['salon_id'])) {
    $_SESSION['salon_error'] = "ابتدا فرم اطلاعات سالن را تکمیل کنید";
    //$conn = null;
    mysqli_close($conn);
    header("Location: manager_profile.php#salon_images");
    exit();
}

$manager_id  = $_SESSION['manager_id'];
$salon_id    = $_SESSION['salon_id'];
$location_id = $_SESSION['location_id'];

define('IMAGE_DIR', 'Images/Salon/');
define('IMAGE_FULL_DIR', __DIR__ . '/' . IMAGE_DIR);
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10 MB
define('MAX_FILE_NUM', 6);

// مطمئن شو پوشه وجود دارد
if (!is_dir(IMAGE_FULL_DIR)) {
    mkdir(IMAGE_FULL_DIR, 0755, true);
}

// =================Upload===================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    $file  = $_FILES['salon_pic'];
    $title = test_input($_POST['salonTitle']) ?? "";

    // بررسی فایل آپلود
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        $_SESSION['salon_pic_file_error'] = "فایلی انتخاب نشده است.";
        header("Location: manager_profile.php#salon_images");
        exit();
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['salon_pic_file_error'] = "خطا در آپلود فایل.";
        header("Location: manager_profile.php#salon_images");
        exit();
    }

    if ($file['size'] > MAX_FILE_SIZE) {
        $_SESSION['salon_pic_fileSize_error'] = " حداکثر حجم مجاز فایل" . MAX_FILE_SIZE . " مگابایت می باشد";
        //mysqli_close($conn);
        header("Location: manager_profile.php#salon_images");
        exit();
    }

    // بررسی نوع فایل با finfo
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    $allowed = ['image/jpeg', 'image/pjpeg', 'image/png', 'image/gif', 'image/tiff', 'image/webp'];
    if (!in_array($mime, $allowed)) {
        $_SESSION['salon_pic_fileType_error'] = "فقط فایل‌های jpeg, png, gif, tiff, webp مجاز می‌باشند.";
        header("Location: manager_profile.php#salon_images");
        exit();
    }

    // بررسی تعداد عکسهای موجود در دیتابیس
    $SqlCount = "SELECT COUNT(salon_id) AS cnt FROM salon 
                WHERE manager_info_id = ? AND location_id = ? AND salon_photo <> '' ";
    $stmtCount = mysqli_prepare($conn, $SqlCount);
    mysqli_stmt_bind_param($stmtCount, "ii", $manager_id, $location_id);
    mysqli_stmt_execute($stmtCount);

    mysqli_stmt_bind_result($stmtCount, $cnt);
    mysqli_stmt_fetch($stmtCount);

    mysqli_stmt_close($stmtCount);

    if ($cnt >= MAX_FILE_NUM) {
        $_SESSION['salon_pic_num_error'] = "حداکثر می توانید " . MAX_FILE_NUM . " نمونه کار آپلود کنید";
        header("Location: manager_profile.php#salon_images");
        exit();
    }

    // تولید نام یکتا برای فایل و انتقال
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $ext = strtolower($ext);
    // اگر ext خالی بود، از mime برای تعیین ext استفاده کنید (اختیاری)
    $newName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
    $fullPath = IMAGE_FULL_DIR . $newName;
    $dbPath = IMAGE_DIR . $newName; // چیزی که در DB ذخیره می‌شود (نسبی)

    // انتقال فایل به پوشه مقصد
    if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
        $_SESSION['salon_pic_file_error'] = "خطا در ذخیره فایل روی سرور.";
        header("Location: manager_profile.php#salon_images");
        exit();
    }

    // درج در دیتابیس
    $insertSql = "UPDATE salon SET salon_photo = ?, salon_title = ?
            WHERE salon_id = ? AND manager_info_id = ? AND location_id = ?";
    $stmtInsert = mysqli_prepare($conn, $insertSql);
    mysqli_stmt_bind_param($stmtInsert, "ssiii", $dbPath, $title, $salon_id, $manager_id, $location_id);

    if (mysqli_stmt_execute($stmtInsert)) {
        $_SESSION['mp_upload_salon'] = "نمونه کار با موفقیت ذخیره شد.";
    } else {
        // در صورت خطا، فایل را حذف کن
        @unlink($fullPath);
        $_SESSION['mp_upload_salon_error'] = "خطا در ذخیره‌سازی اطلاعات .";
    }
    mysqli_stmt_close($stmtInsert);
    //$conn = null;
    mysqli_close($conn);
    header("Location: manager_profile.php#salon_images");
    exit();
}


//Soft Delete
// ======================Delete============================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_salonpic'])) {

    // انتظار داریم service_id[] و delete_service[] ارسال شود (معمولاً فقط یک مقدار چون هر فرم برای یک ردیف است)
    $salon_ids = $_POST['salon_id'] ?? [];

    if (!is_array($salon_ids) || count($salon_ids) === 0) {
        $_SESSION['mp_delete_salonpic_error'] = "درخواست حذف نامعتبر است.";
        header("Location: manager_profile.php#salon_images");
        exit();
    }

    // آماده‌سازی statement: ابتدا select برای گرفتن مسیر فایل، سپس delete
    $qSelect = "SELECT salon_photo FROM salon WHERE salon_id = ? AND manager_info_id = ? AND location_id = ?";
    $qDelete = "UPDATE salon SET salon_photo = '', salon_title = '' WHERE salon_id = ? AND manager_info_id = ? AND location_id = ?";
    $stmtSelect = mysqli_prepare($conn, $qSelect);
    $stmtDelete = mysqli_prepare($conn, $qDelete);

    foreach ($salon_ids as $sid) {
        $salon_id = intval($sid);
        if ($salon_id <= 0) continue;

        // گرفتن مسیر فایل
        mysqli_stmt_bind_param($stmtSelect, 'iii', $salon_id, $manager_id, $location_id);
        mysqli_stmt_execute($stmtSelect);
        mysqli_stmt_bind_result($stmtSelect, $salon_photo);
        mysqli_stmt_fetch($stmtSelect);
        // پاکسازی internal result
        mysqli_stmt_reset($stmtSelect);

        // حذف فایل فیزیکی اگر وجود دارد
        if (!empty($salon_photo)) {
            $possiblePath = __DIR__ . '/' . $salon_photo;
            if (is_file($possiblePath)) {
                @unlink($possiblePath);
            }
        }

        // حذف رکورد
        mysqli_stmt_bind_param($stmtDelete, 'iii', $salon_id, $manager_id, $location_id);
        if (mysqli_stmt_execute($stmtDelete)) {
            $_SESSION['mp_delete_salonpic'] = "نمونه کار با موفقیت حذف شد.";
        } else {
            $_SESSION['mp_not_delete_salonpic'] = "خطا در حذف نمونه کار.";
        }
    }

    mysqli_stmt_close($stmtSelect);
    mysqli_stmt_close($stmtDelete);
    //$conn = null;
    mysqli_close($conn);
    header("Location: manager_profile.php#salon_images");
    exit();
}

// اگر هیچ POST‌ای نبود
header("Location: manager_profile.php#salon_images");
exit();
?>