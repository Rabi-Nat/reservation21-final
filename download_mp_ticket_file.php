<?php
// download_ticket_file.php (نسخه‌ای که مشکلات رایج را برطرف می‌کند)
session_start();
require_once 'database.php';

define('FILE_DIR', 'Files/manager_tickets/');
define('FILE_FULL_DIR', __DIR__ . '/' . FILE_DIR);

// 1) بررسی لاگین
$manager_id = $_SESSION['manager_id'] ?? '';
if (empty($manager_id)) {
    http_response_code(403);
    echo "برای دانلود باید وارد سایت شوید.";
    exit();
}

// 2) گرفتن id تیکت
$ticket_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($ticket_id <= 0) {
    http_response_code(400);
    echo "شناسه تیکت نامعتبر است.";
    exit();
}

// 3) گرفتن نام فایل و بررسی مالکیت از DB
$conn = db_connect();
$query = "SELECT ticket_file, ticket_file_orig, manager_id FROM manager_ticket WHERE m_ticket_id = ? LIMIT 1";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $ticket_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
//$conn = null;
mysqli_close($conn);

if (!$row) {
    http_response_code(404);
    echo "تیکتی با این شناسه یافت نشد.";
    exit();
}
if ((int)$row['manager_id'] !== (int)$manager_id) {
    http_response_code(403);
    echo "شما اجازه دانلود این فایل را ندارید.";
    exit();
}

$storedFileName = $row['ticket_file'] ?? '';
if (empty($storedFileName)) {
    http_response_code(404);
    echo "برای این تیکت فایلی آپلود نشده است.";
    exit();
}

$realBase = realpath(FILE_FULL_DIR);
$filePath = FILE_FULL_DIR . $storedFileName;
$realFile = realpath($filePath);

if ($realFile === false || strpos($realFile, $realBase) !== 0) {
    http_response_code(403);
    echo "دسترسی به فایل مجاز نیست.";
    exit();
}
if (!is_file($realFile) || !is_readable($realFile)) {
    http_response_code(404);
    echo "فایل پیدا نشد.";
    exit();
}

// 4) خاموش کردن هرگونه فشرده‌سازی خروجی که ممکن است بایت‌ها را تغییر دهد
if (ini_get('zlib.output_compression')) {
    ini_set('zlib.output_compression', '0');
}

// 5) پاکسازی تمامی بافرهای خروجی (مهم)
while (ob_get_level()) {
    ob_end_clean();
}

// 6) تعیین MIME-type با finfo
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $realFile);
finfo_close($finfo);
if (!$mime) $mime = 'application/octet-stream';

// 7) نام فایل برای دانلود (اگر نام اصلی در DB ذخیره شده باشد از آن استفاده کن)
$downloadName = $row['ticket_file_orig'] ?? $storedFileName;

// 8) ارسال هدرها
$filesize = filesize($realFile);
$basename = basename($downloadName);
$disposition = "attachment; filename=\"" . $basename . "\"; filename*=UTF-8''" . rawurlencode($basename);

header('Content-Description: File Transfer');
header('Content-Type: ' . $mime);
header('Content-Disposition: ' . $disposition);
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: private, must-revalidate');
header('Pragma: private');
header('Content-Length: ' . $filesize);

// 9) ارسال فایل بصورت باینری (readfile امن و ساده است)
if (false === readfile($realFile)) {
    // readfile ممکن است false برگرداند اگر خطایی رخ دهد
    http_response_code(500);
    exit("خطا در ارسال فایل.");
}
exit();
