<?php
// mp_config_personal.php - نسخهٔ نهایی
session_start();
require_once 'database.php'; // اتصال $conn و تابع test_input() را اینجا دارید

$manager_id = isset($_SESSION['manager_id']) ? intval($_SESSION['manager_id']) : 0;

if (empty($manager_id)) {
    $_SESSION['login_error'] = "ابتدا باید وارد شوید";
    if (isset($conn) && $conn) mysqli_close($conn);
    header("Location: login.php");
    exit();
}

/* ---------- تنظیمات فایل و تصاویر ---------- */
define('IMAGE_DIR', 'Images/Profile/');
define('IMAGE_FULL_DIR', __DIR__ . '/' . IMAGE_DIR);
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB
if (!is_dir(IMAGE_FULL_DIR)) mkdir(IMAGE_FULL_DIR, 0755, true);

/* ---------- انتخاب درگاه پیامکی و پیکربندی ---------- */
/* مقدار SMS_PROVIDER را روی 'kavenegar' یا 'melipayamak' قرار دهید */
define('SMS_PROVIDER', 'melipayamak'); // 'kavenegar' یا 'melipayamak'

/* Kavenegar */
define('KAVENEGAR_API_KEY', 'PUT_YOUR_KAVENEGAR_API_KEY_HERE');
define('KAVENEGAR_SENDER', '10004346');

/* Melipayamak / Payamak-Panel */
define('MELIPAYAMAK_USERNAME', 'PUT_YOUR_USERNAME');
define('MELIPAYAMAK_PASSWORD', 'PUT_YOUR_PASSWORD');
define('MELIPAYAMAK_SENDER', '5000xxxx');

/* ---------- توابع کمکی ---------- */
function generate_verification_code(int $len = 6): string
{
    $min = (int) str_repeat('0', $len - 1);
    $max = (int) str_repeat('9', $len);
    $n = random_int($min, $max);
    return str_pad((string)$n, $len, '0', STR_PAD_LEFT);
}

/* ارسال SMS - wrapper */
function send_sms_message(string $to, string $body): bool
{
    if (SMS_PROVIDER === 'kavenegar') return send_sms_kavenegar($to, $body);
    if (SMS_PROVIDER === 'melipayamak') return send_sms_melipayamak($to, $body);
    error_log("Unknown SMS_PROVIDER: " . SMS_PROVIDER);
    return false;
}

/* Kavenegar - POST form-encoded، بررسی پاسخ JSON */
function send_sms_kavenegar(string $to, string $body): bool
{
    $apiKey = defined('KAVENEGAR_API_KEY') ? KAVENEGAR_API_KEY : '';
    $sender = defined('KAVENEGAR_SENDER') ? KAVENEGAR_SENDER : '';
    if ($apiKey === '' || $sender === '') {
        error_log("Kavenegar config missing");
        return false;
    }
    $toNumber = trim($to);
    // Kavenegar معمولاً 09... را قبول می‌کند؛ اگر می‌خواهید +98 ارسال کنید تغییر دهید
    if (preg_match('/^0(\d{10})$/', $toNumber, $m)) $toNumber = $m[0];

    $url = "https://api.kavenegar.com/v1/" . urlencode($apiKey) . "/sms/send.json";
    $post = http_build_query([
        'receptor' => $toNumber,
        'sender'   => $sender,
        'message'  => $body,
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $resp = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);

    if ($resp === false) {
        error_log("Kavenegar curl error: {$err}");
        return false;
    }
    $data = @json_decode($resp, true);
    if (is_array($data) && isset($data['return']['status']) && intval($data['return']['status']) === 200) return true;
    if ($httpCode >= 200 && $httpCode < 300) return true;
    error_log("Kavenegar send failed: HTTP={$httpCode} resp=" . substr($resp, 0, 300));
    return false;
}

/* Melipayamak (Payamak-Panel) - POST form-encoded، بررسی XML یا پاسخ ساده */
function send_sms_melipayamak(string $to, string $body): bool
{
    $username = defined('MELIPAYAMAK_USERNAME') ? MELIPAYAMAK_USERNAME : '';
    $password = defined('MELIPAYAMAK_PASSWORD') ? MELIPAYAMAK_PASSWORD : '';
    $sender   = defined('MELIPAYAMAK_SENDER') ? MELIPAYAMAK_SENDER : '';

    if ($username === '' || $password === '' || $sender === '') {
        error_log("Melipayamak config missing");
        return false;
    }

    $toNumber = trim($to);
    if (preg_match('/^0(\d{10})$/', $toNumber, $m)) $toNumber = $m[0];

    $url = "https://api.payamak-panel.com/post/Send.asmx/SendSimpleSMS";
    $post = http_build_query([
        'username' => $username,
        'password' => $password,
        'to'       => $toNumber,
        'from'     => $sender,
        'text'     => $body,
        'isflash'  => 'false'
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $resp = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);

    if ($resp === false) {
        error_log("Melipayamak curl error: {$err}");
        return false;
    }

    // تلاش برای پارس XML (اغلب پاسخ payamak-panel عدد یا xml است)
    libxml_use_internal_errors(true);
    $xml = @simplexml_load_string($resp);
    if ($xml !== false) {
        $val = trim((string)$xml);
        if ($val !== '') {
            if (is_numeric($val) && intval($val) > 0) return true; // عدد مثبت => موفق
        }
    }
    if ($httpCode >= 200 && $httpCode < 300) return true;
    error_log("Melipayamak send failed: HTTP={$httpCode} resp=" . substr($resp, 0, 300));
    return false;
}

/* ---------- پردازش فرم ذخیره/بروزرسانی ---------- */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit']) && $_POST['submit'] === '1') {

    // دریافت و پاکسازی ورودی‌ها
    $mp_firstName    = test_input($_POST['firstName'] ?? '');
    $mp_lastName     = test_input($_POST['lastName'] ?? '');
    $mp_nationalCode = test_input($_POST['nationalCode'] ?? '');
    $mp_phoneNumber  = test_input($_POST['phoneNumber'] ?? '');
    $mp_managerEmail = test_input($_POST['managerEmail'] ?? '');

    // اعتبارسنجی پایه
    if ($mp_firstName === '' || $mp_phoneNumber === '') {
        $_SESSION['mp_emptyfield_error'] = "فیلدهای ستاره‌دار باید تکمیل شوند";
        mysqli_close($conn);
        header("Location: manager_profile.php");
        exit();
    }
    if (!preg_match('/^0\d{10}$/', $mp_phoneNumber)) {
        $_SESSION['mp_phone_error'] = "فرمت شماره تلفن نامعتبر است (مثال: 09121234567)";
        mysqli_close($conn);
        header("Location: manager_profile.php");
        exit();
    }
    if ($mp_nationalCode !== '' && !preg_match('/^\d{10}$/', $mp_nationalCode)) {
        $_SESSION['mp_nationalCode_error'] = "فرمت کد ملی نامعتبر است";
        mysqli_close($conn);
        header("Location: manager_profile.php");
        exit();
    }
    if ($mp_managerEmail !== '' && !filter_var($mp_managerEmail, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['mp_email_error'] = "فرمت ایمیل نامعتبر است";
        mysqli_close($conn);
        header("Location: manager_profile.php");
        exit();
    }

    // یکتا بودن شماره
    $query = "SELECT manager_id FROM manager_info WHERE manager_phone = ? AND manager_id <> ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        $_SESSION['mp_not_confirm'] = "خطا در پرس‌وجو";
        mysqli_close($conn);
        header("Location: manager_profile.php");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "si", $mp_phoneNumber, $manager_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $_SESSION['duplicate_manager_phone'] = "این شماره قبلا ثبت شده است. لطفا شماره دیگری وارد کنید.";
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header("Location: manager_profile.php");
        exit();
    }
    mysqli_stmt_close($stmt);

    // خواندن رکورد فعلی
    $existingFirstName = $existingLastName = $existingNationalId = $existingPhone = $existingEmail = $existingPhoto = '';
    $checkSql = "SELECT first_name, last_name, national_id, manager_phone, manager_email, manager_photo FROM manager_info WHERE manager_id = ? LIMIT 1";
    $checkStmt = mysqli_prepare($conn, $checkSql);
    if (!$checkStmt) {
        $_SESSION['mp_not_confirm'] = "خطا در آماده‌سازی بررسی رکورد.";
        mysqli_close($conn);
        header("Location: manager_profile.php");
        exit();
    }
    mysqli_stmt_bind_param($checkStmt, "i", $manager_id);
    mysqli_stmt_execute($checkStmt);
    $res = mysqli_stmt_get_result($checkStmt);
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $existingFirstName = $row['first_name'] ?? '';
        $existingLastName  = $row['last_name'] ?? '';
        $existingNationalId = $row['national_id'] ?? '';
        $existingPhone     = $row['manager_phone'] ?? '';
        $existingEmail     = $row['manager_email'] ?? '';
        $existingPhoto     = $row['manager_photo'] ?? '';
        $recordExists = true;
    } else {
        $recordExists = false;
    }
    mysqli_stmt_close($checkStmt);

    if (!$recordExists) {
        $_SESSION['mp_not_confirm'] = "رکورد مدیر یافت نشد.";
        mysqli_close($conn);
        header("Location: manager_profile.php");
        exit();
    }

    // پردازش آپلود عکس (در صورت وجود)
    $newDbPath = null;
    if (isset($_FILES['managerPhoto']) && isset($_FILES['managerPhoto']['error']) && $_FILES['managerPhoto']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['managerPhoto'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['manager_photo_upload_error'] = "خطا در آپلود فایل.";
            mysqli_close($conn);
            header("Location: manager_profile.php");
            exit();
        }
        if ($file['size'] > MAX_FILE_SIZE) {
            $_SESSION['manager_photo_fileSize_error'] = "حداکثر حجم مجاز فایل " . (MAX_FILE_SIZE / 1024 / 1024) . " مگابایت می‌باشد";
            mysqli_close($conn);
            header("Location: manager_profile.php");
            exit();
        }
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        $allowed = ['image/jpeg', 'image/pjpeg', 'image/png', 'image/gif', 'image/tiff', 'image/webp'];
        if (!in_array($mime, $allowed)) {
            $_SESSION['manager_photo_fileType_error'] = "فقط فایل‌های jpeg, png, gif, tiff, webp مجاز می‌باشند.";
            mysqli_close($conn);
            header("Location: manager_profile.php");
            exit();
        }
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $ext = strtolower($ext);
        try {
            $newName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        } catch (Exception $e) {
            $newName = time() . '_' . uniqid() . '.' . $ext;
        }
        $fullPath = IMAGE_FULL_DIR . $newName;
        // مسیر نسبی برای ذخیره در دیتابیس (بدون اسلش اول)
        $dbPath = 'Images/Profile/' . $newName;
        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            $_SESSION['manager_photo_file_error'] = "خطا در ذخیره فایل روی سرور.";
            mysqli_close($conn);
            header("Location: manager_profile.php");
            exit();
        }
        $newDbPath = $dbPath;
    }

    // تعیین مقادیر نهایی (اگر کاربر فیلدی خالی گذاشت مقدار قبلی حفظ شود)
    $finalFirstName   = ($mp_firstName !== '') ? $mp_firstName : $existingFirstName;
    $finalLastName    = ($mp_lastName !== '') ? $mp_lastName : $existingLastName;
    $finalNationalId  = ($mp_nationalCode !== '') ? $mp_nationalCode : $existingNationalId;
    $finalPhone       = ($mp_phoneNumber !== '') ? $mp_phoneNumber : $existingPhone;
    $finalEmail       = ($mp_managerEmail !== '') ? $mp_managerEmail : $existingEmail;
    $finalPhoto       = ($newDbPath !== null) ? $newDbPath : $existingPhoto;

    // UPDATE رکورد
    $updateSql = "UPDATE manager_info 
                  SET first_name = ?, last_name = ?, national_id = ?, manager_phone = ?, manager_email = ?, manager_photo = ?
                  WHERE manager_id = ?";
    $updateStmt = mysqli_prepare($conn, $updateSql);
    if (!$updateStmt) {
        $_SESSION['mp_not_confirm'] = "خطا در آماده‌سازی بروزرسانی.";
        mysqli_close($conn);
        header("Location: manager_profile.php");
        exit();
    }
    mysqli_stmt_bind_param(
        $updateStmt,
        "ssssssi",
        $finalFirstName,
        $finalLastName,
        $finalNationalId,
        $finalPhone,
        $finalEmail,
        $finalPhoto,
        $manager_id
    );

    if (mysqli_stmt_execute($updateStmt)) {
        $_SESSION['mp_confirm'] = "اطلاعات با موفقیت بروزرسانی شد";
        $_SESSION['manager_firstName'] = $finalFirstName;
        $_SESSION['manager_lastName']  = $finalLastName;

        // حذف امن عکس قدیمی در صورت آپلود عکس جدید
        if ($newDbPath !== null && $existingPhoto !== '' && $existingPhoto !== $finalPhoto) {
            $candidate = IMAGE_FULL_DIR . '/' . basename($existingPhoto);
            $realOld = @realpath($candidate);
            $realDir = @realpath(IMAGE_FULL_DIR);
            if ($realOld && $realDir && strpos($realOld, $realDir) === 0 && is_file($realOld)) {
                @unlink($realOld);
            }
        }

        // اگر شماره تغییر کرده، ارسال کد تایید
        if ($finalPhone !== $existingPhone) {
            // تلاش برای ست کردن flag تایید شماره (اگر ستون وجود داشته باشد)
            $trySetVerify0 = mysqli_prepare($conn, "UPDATE manager_info SET manager_phone_verified = 0 WHERE manager_id = ?");
            if ($trySetVerify0) {
                mysqli_stmt_bind_param($trySetVerify0, "i", $manager_id);
                @mysqli_stmt_execute($trySetVerify0);
                mysqli_stmt_close($trySetVerify0);
            }

            // تولید کد و ذخیرهٔ هش (ابتدا درج می‌کنیم و در صورت شکست در ارسال حذف می‌کنیم)
            $verificationCode = generate_verification_code(6);
            //$codeHash = password_hash($verificationCode, PASSWORD_DEFAULT);
            //$codeHash = $verificationCode;
            $expiresAt = date('Y-m-d H:i:s', time() + 300); // 5 دقیقه

            $insSql = "INSERT INTO phone_verifications (manager_id, manager_phone, code, expires_at) VALUES (?, ?, ?, ?)";
            $insStmt = mysqli_prepare($conn, $insSql);
            if ($insStmt) {
                mysqli_stmt_bind_param($insStmt, "isss", $manager_id, $finalPhone, $verificationCode, $expiresAt);
                if (mysqli_stmt_execute($insStmt)) {
                    $verificationId = mysqli_insert_id($conn);
                    mysqli_stmt_close($insStmt);

                    // آماده‌سازی شماره برای ارسال
                    $toNumber = $finalPhone;
                    if (preg_match('/^0(\d{10})$/', $toNumber, $m)) $toNumber = '+98' . $m[1];

                    $smsBody = "کد تایید شما: $verificationCode\nاین کد تا 5 دقیقه معتبر است.";

                    $smsSent = send_sms_message($toNumber, $smsBody);

                    if ($smsSent) {
                        $_SESSION['phone_verification_pending'] = true;
                        $_SESSION['phone_verification_phone'] = $finalPhone;
                        mysqli_stmt_close($updateStmt);
                        mysqli_close($conn);
                        header('Location: mp_verify_phone.php');
                        exit();
                    } else {
                        // ارسال ناموفق -> حذف رکورد verification تا بلااستفاده نماند
                        $del = mysqli_prepare($conn, "DELETE FROM phone_verifications WHERE m_verify_id = ?");
                        if ($del) {
                            mysqli_stmt_bind_param($del, "i", $verificationId);
                            mysqli_stmt_execute($del);
                            mysqli_stmt_close($del);
                        }
                        $_SESSION['mp_not_confirm'] = "اطلاعات ذخیره شد اما ارسال پیامک با خطا مواجه شد. لطفاً بعدا تلاش کنید.";
                    }
                } else {
                    mysqli_stmt_close($insStmt);
                    $_SESSION['mp_not_confirm'] = "خطا در ذخیرهٔ کد تایید.";
                }
            } else {
                $_SESSION['mp_not_confirm'] = "خطا در آماده‌سازی ذخیرهٔ کد تایید.";
            }
        } // end if phone changed

    } else {
        $_SESSION['mp_not_confirm'] = "خطا در بروزرسانی اطلاعات";
    }

    mysqli_stmt_close($updateStmt);
    mysqli_close($conn);
    header("Location: manager_profile.php");
    exit();
}

/* ---------- soft clean (حذف اطلاعات شخصی و تنظیم عکس پیش‌فرض) ---------- */
if (isset($_POST['delete_personal']) && $_POST['delete_personal'] === '1') {

    $defaultPhoto = 'assets/img/team/user1-128x128.jpg';
    $existingPhoto = '';

    $checkSql = "SELECT manager_photo FROM manager_info WHERE manager_id = ? LIMIT 1";
    $checkStmt = mysqli_prepare($conn, $checkSql);
    if ($checkStmt) {
        mysqli_stmt_bind_param($checkStmt, "i", $manager_id);
        mysqli_stmt_execute($checkStmt);
        $res = mysqli_stmt_get_result($checkStmt);
        if ($res && mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            $existingPhoto = $row['manager_photo'] ?? '';
        }
        mysqli_stmt_close($checkStmt);
    }

    $query = "UPDATE manager_info 
              SET first_name = '', last_name = '', national_id = '', manager_phone = '', manager_email = '', manager_photo = ?
              WHERE manager_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'si', $defaultPhoto, $manager_id);
        if (mysqli_stmt_execute($stmt)) {
            if (!empty($existingPhoto) && $existingPhoto !== $defaultPhoto) {
                $candidate = IMAGE_FULL_DIR . '/' . basename($existingPhoto);
                $realOld = @realpath($candidate);
                $realDir = @realpath(IMAGE_FULL_DIR);
                if ($realOld && $realDir && strpos($realOld, $realDir) === 0 && is_file($realOld)) {
                    @unlink($realOld);
                }
            }
            $_SESSION['mp_confirm'] = 'اطلاعات شخصی پاک شد.';
            unset($_SESSION['manager_firstName'], $_SESSION['manager_lastName']);
        } else {
            $_SESSION['mp_not_confirm'] = 'خطا در پاک کردن اطلاعات.';
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['mp_not_confirm'] = 'خطا در آماده‌سازی پاک‌سازی.';
    }

    mysqli_close($conn);
    header('Location: manager_profile.php');
    exit();
}
