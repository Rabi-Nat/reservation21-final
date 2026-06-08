<?php
// mp = manager profile
session_start();
require_once 'database.php';
//$conn = db_connect();


$manager_id  = $_SESSION['manager_id'] ?? "";

// Session validation
if (empty($_SESSION['manager_id'])) {
    $_SESSION['login_error'] = "ابتدا باید به سایت ورود کنید";
    mysqli_close($conn);
    //$conn = null;
    header("Location: login.php");
    exit();
}

define('FILE_DIR', 'Files/manager_tickets/');
define('FILE_FULL_DIR', __DIR__ . '/' . FILE_DIR);
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10 MB
//define('MAX_FILE_NUM', 6);

// مطمئن شو پوشه وجود دارد
if (!is_dir(FILE_FULL_DIR)) {
    mkdir(FILE_FULL_DIR, 0755, true);
}

// یک تابع کمکی برای پاکسازی نام اصلی فایل (حذف null-byte و محدود کردن طول)
function sanitize_original_filename($name, $maxLen = 255)
{
    // basename برای حذف مسیر (اگر کلاینت سعی کرده مسیر بفرستد)
    $name = basename($name);
    // حذف null bytes
    $name = str_replace("\0", '', $name);
    // کوتاه کردن امن (برای UTF-8 از mb_substr استفاده می‌کنیم)
    if (function_exists('mb_substr')) {
        if (mb_strlen($name, 'UTF-8') > $maxLen) {
            $name = mb_substr($name, 0, $maxLen, 'UTF-8');
        }
    } else {
        if (strlen($name) > $maxLen) {
            $name = substr($name, 0, $maxLen);
        }
    }
    return $name;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {


    $ticketTitle = test_input($_POST['ticketTitle']);
    $ticketCategory = test_input($_POST['ticketCategory']);
    $ticketMessage = test_input($_POST['ticketMessage']);
    $file  = $_FILES['ticketFile'];

    if (empty($ticketTitle) || empty($ticketCategory) || empty($ticketMessage)) {
        $_SESSION['mp_emptyfield_error'] = "فیلدهای ستاره دار باید تکمیل شوند";
        mysqli_close($conn);
        //$conn = null;
        header("Location: manager_tickets.php");
        exit();
    }

    if (isset($file) && $file['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['salon_pic_file_error'] = "خطا در آپلود فایل";
            header("Location: manager_tickets.php");
            exit();
        }

        if ($file['size'] > MAX_FILE_SIZE) {
            $_SESSION['salon_pic_fileSize_error'] = " حداکثر حجم مجاز فایل" . MAX_FILE_SIZE . " مگابایت می باشد";
            //mysqli_close($conn);
            header("Location: manager_tickets.php");
            exit();
        }

        // بررسی نوع فایل با finfo
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        $allowed = [
            'image/jpeg',
            'image/pjpeg',
            'image/png',
            'image/gif',
            'image/tiff',
            'image/webp',
            'application/zip',
            'application/x-rar-compressed',
            'application/vnd.rar',
            'application/pdf',
            'application/msword', // doc
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' // docx
        ];
        if (!in_array($mime, $allowed)) {
            $_SESSION['mp_ticket_fileType_error'] = "فقط فایل‌های jpeg, png, gif, tiff, webp, zip, rar, pdf, doc مجاز می‌باشند.";
            header("Location: manager_tickets.php");
            exit();
        }

        // نام اصلی فایل (تصحیح و کوتاه‌سازی)
        $originalName = sanitize_original_filename($file['name'], 255);

        // تولید نام یکتا برای فایل و انتقال
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $ext = strtolower($ext);
        // اگر ext خالی بود، از mime برای تعیین ext استفاده کنید (اختیاری)
        $newName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        $fullPath = FILE_FULL_DIR . $newName;
        $dbPath = FILE_DIR . $newName; // چیزی که در DB ذخیره می‌شود (نسبی)

        // انتقال فایل به پوشه مقصد
        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            $_SESSION['mp_ticket_file_error'] = "خطا در ذخیره فایل روی سرور";
            header("Location: manager_tickets.php");
            exit();
        }

        // === with file ===
        // ذخیره در دیتابیس همراه با نام اصلی
        $query = "INSERT INTO manager_ticket (manager_id, ticket_title, ticket_category, ticket_message, ticket_file, ticket_file_orig)
                VALUES (?,?,?,?,?,?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'isssss', $manager_id, $ticketTitle, $ticketCategory, $ticketMessage, $newName, $originalName);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['mp_upload_ticket'] = " تیکت با موفقیت ارسال شد";
        } else {
            // در صورت خطا، فایل را حذف کن
            @unlink($fullPath);
            $_SESSION['mp_upload_ticket_error'] = "خطا در ارسال تیکت ";
        }
    } else {
        // === without file ===
        // بدون فایل — ستون ticket_file_orig NULL می‌ماند
        $query = "INSERT INTO manager_ticket (manager_id, ticket_title, ticket_category, ticket_message)
                VALUES (?,?,?,?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'isss', $manager_id, $ticketTitle, $ticketCategory, $ticketMessage);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['mp_upload_ticket'] = " تیکت با موفقیت ارسال شد";
        } else {
            // در صورت خطا، فایل را حذف کن
            $_SESSION['mp_upload_ticket_error'] = "خطا در ارسال تیکت ";
        }
    }

    mysqli_stmt_close($stmt);
    //$conn = null;
    mysqli_close($conn);
    header("Location: manager_tickets.php");
    exit();
}
