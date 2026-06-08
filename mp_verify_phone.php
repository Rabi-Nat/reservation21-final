<?php
// mp_verify_phone.php
// صفحهٔ وارد کردن کد تایید شماره تلفن
session_start();
require_once 'database.php'; // باید $conn و test_input() را فراهم کند

// --- تنظیمات مشابه فایل قبلی (در صورت تمایل میتوانید اینها را به یک فایل مشترک ببرید) ---
define('SMS_PROVIDER', 'melipayamak'); // 'melipayamak' or 'kavenegar'

define('KAVENEGAR_API_KEY', 'PUT_YOUR_KAVENEGAR_API_KEY_HERE');
define('KAVENEGAR_SENDER', '10004346');
define('MELIPAYAMAK_USERNAME', 'PUT_YOUR_USERNAME');
define('MELIPAYAMAK_PASSWORD', 'PUT_YOUR_PASSWORD');
define('MELIPAYAMAK_SENDER', '5000xxxx');

// --- توابع کمکی (کپی سبکِ send_sms_message از فایل mp_config_personal.php) ---
function send_sms_kavenegar(string $to, string $body): bool {
    $apiKey = defined('KAVENEGAR_API_KEY') ? KAVENEGAR_API_KEY : '';
    $sender = defined('KAVENEGAR_SENDER') ? KAVENEGAR_SENDER : '';
    if ($apiKey === '' || $sender === '') {
        error_log("Kavenegar config missing");
        return false;
    }
    $toNumber = trim($to);
    if (preg_match('/^0(\d{10})$/', $toNumber, $m)) $toNumber = $m[0];
    $url = "https://api.kavenegar.com/v1/" . urlencode($apiKey) . "/sms/send.json";
    $post = http_build_query(['receptor' => $toNumber, 'sender' => $sender, 'message' => $body]);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $resp = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);
    if ($resp === false) { error_log("Kavenegar curl error: {$err}"); return false; }
    $data = @json_decode($resp, true);
    if (is_array($data) && isset($data['return']['status']) && intval($data['return']['status']) === 200) return true;
    if ($httpCode >= 200 && $httpCode < 300) return true;
    error_log("Kavenegar send failed: HTTP={$httpCode} resp=" . substr($resp,0,300));
    return false;
}
function send_sms_melipayamak(string $to, string $body): bool {
    $username = defined('MELIPAYAMAK_USERNAME') ? MELIPAYAMAK_USERNAME : '';
    $password = defined('MELIPAYAMAK_PASSWORD') ? MELIPAYAMAK_PASSWORD : '';
    $sender   = defined('MELIPAYAMAK_SENDER') ? MELIPAYAMAK_SENDER : '';
    if ($username === '' || $password === '' || $sender === '') { error_log("Melipayamak config missing"); return false; }
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
    if ($resp === false) { error_log("Melipayamak curl error: {$err}"); return false; }
    libxml_use_internal_errors(true);
    $xml = @simplexml_load_string($resp);
    if ($xml !== false) {
        $val = trim((string)$xml);
        if ($val !== '' && is_numeric($val) && intval($val) > 0) return true;
    }
    if ($httpCode >= 200 && $httpCode < 300) return true;
    error_log("Melipayamak send failed: HTTP={$httpCode} resp=" . substr($resp,0,300));
    return false;
}
function send_sms_message(string $to, string $body): bool {
    if (SMS_PROVIDER === 'kavenegar') return send_sms_kavenegar($to, $body);
    if (SMS_PROVIDER === 'melipayamak') return send_sms_melipayamak($to, $body);
    error_log("Unknown SMS_PROVIDER: " . SMS_PROVIDER);
    return false;
}

// --- شروع منطق صفحه ---
// چک لاگین
$manager_id = $_SESSION['manager_id'] ?? 0;
if (empty($manager_id)) {
    header('Location: login.php');
    exit();
}

// شماره‌ای که پیامک برایش ارسال شده (باید توسط mp_config_personal.php در سشن تنظیم شده باشد)
$phone = $_SESSION['phone_verification_phone'] ?? '';

// پیام‌های فیدبک برای نمایش
$alert = '';
$alert_type = 'info'; // 'info'|'success'|'danger'

// تعداد تلاش‌ها در سشن (برای جلوگیری از brute force)
if (!isset($_SESSION['verify_attempts'])) $_SESSION['verify_attempts'] = 0;
$maxAttempts = 5;

// خواندن آخرین رکورد ارسال‌شده (برای نرخ‌دهی resend و چک وجود کد)
function get_last_verification($conn, $manager_id, $phone) {
    $sql = "SELECT m_verify_id, code, expires_at, created_at, used FROM phone_verifications
            WHERE manager_id = ? AND phone = ? ORDER BY m_verify_id DESC LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) return false;
    mysqli_stmt_bind_param($stmt, "is", $manager_id, $phone);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = ($res && mysqli_num_rows($res)>0) ? mysqli_fetch_assoc($res) : false;
    mysqli_stmt_close($stmt);
    return $row;
}

// POST: کاربر می‌خواهد کد را بررسی کند
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_code'])) {
    $code = trim($_POST['verify_code']);
    if ($code === '') {
        $alert = 'لطفا کد را وارد کنید.';
        $alert_type = 'danger';
    } elseif ($_SESSION['verify_attempts'] >= $maxAttempts) {
        $alert = 'تعداد تلاش‌ها بیش از حد مجاز است. لطفا بعدا تلاش کنید.';
        $alert_type = 'danger';
    } else {
        $last = get_last_verification($conn, $manager_id, $phone);
        if (!$last) {
            $alert = 'هیچ کد فعالی یافت نشد. لطفاً ابتدا فرم اطلاعات را ارسال کنید.';
            $alert_type = 'danger';
        } elseif (intval($last['used']) === 1) {
            $alert = 'این کد قبلاً استفاده شده است. یک کد جدید درخواست دهید.';
            $alert_type = 'danger';
        } elseif (new DateTime() > new DateTime($last['expires_at'])) {
            $alert = 'کد منقضی شده است. لطفاً دوباره درخواست ارسال کد کنید.';
            $alert_type = 'danger';
        } else {
            // بررسی هش
            if (password_verify($code, $last['code'])) {
                // موفق: علامت‌گذاری used و آپدیت manager_info
                $u1 = mysqli_prepare($conn, "UPDATE phone_verifications SET used = 1 WHERE id = ?");
                if ($u1) {
                    mysqli_stmt_bind_param($u1, "i", $last['id']);
                    mysqli_stmt_execute($u1);
                    mysqli_stmt_close($u1);
                }
                $u2 = mysqli_prepare($conn, "UPDATE manager_info SET manager_phone_verified = 1 WHERE manager_id = ?");
                if ($u2) {
                    mysqli_stmt_bind_param($u2, "i", $manager_id);
                    mysqli_stmt_execute($u2);
                    mysqli_stmt_close($u2);
                }
                unset($_SESSION['phone_verification_pending'], $_SESSION['phone_verification_phone']);
                $_SESSION['mp_confirm'] = 'شماره تلفن با موفقیت تایید شد.';
                $_SESSION['verify_attempts'] = 0;
                mysqli_close($conn);
                header('Location: manager_profile.php');
                exit();
            } else {
                $_SESSION['verify_attempts'] += 1;
                $remaining = $maxAttempts - $_SESSION['verify_attempts'];
                $alert = 'کد نادرست است. تعداد تلاش‌های باقی‌مانده: ' . $remaining;
                $alert_type = 'danger';
            }
        }
    }
}

// POST: کاربر درخواست ارسال مجدد کرده (resend)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resend_code'])) {
    // محدودیت resend بر اساس آخرین created_at (مثلا 60 ثانیه)
    $last = get_last_verification($conn, $manager_id, $phone);
    $allowResend = true;
    if ($last && !empty($last['created_at'])) {
        $created = new DateTime($last['created_at']);
        $diff = (new DateTime())->getTimestamp() - $created->getTimestamp();
        if ($diff < 60) { // کمتر از 60 ثانیه
            $allowResend = false;
            $alert = 'لطفاً حداقل 60 ثانیه بین درخواست‌های ارسال کد فاصله بگذارید.';
            $alert_type = 'danger';
        }
    }

    if ($allowResend) {
        // تولید کد جدید، ذخیره و ارسال
        $verificationCode = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $codeHash = password_hash($verificationCode, PASSWORD_DEFAULT);
        $expiresAt = date('Y-m-d H:i:s', time() + 300); // 5 دقیقه

        $ins = mysqli_prepare($conn, "INSERT INTO phone_verifications (manager_id, manager_phone, code, expires_at) VALUES (?, ?, ?, ?)");
        if ($ins) {
            mysqli_stmt_bind_param($ins, "isss", $manager_id, $phone, $codeHash, $expiresAt);
            if (mysqli_stmt_execute($ins)) {
                $vid = mysqli_insert_id($conn);
                mysqli_stmt_close($ins);
                // ارسال پیامک
                $toNumber = $phone;
                if (preg_match('/^0(\d{10})$/', $toNumber, $m)) $toNumber = '+98' . $m[1];
                $smsBody = "کد تایید شما: $verificationCode\nاین کد تا 5 دقیقه معتبر است.";
                $sent = send_sms_message($toNumber, $smsBody);
                if ($sent) {
                    $_SESSION['phone_verification_pending'] = true;
                    $_SESSION['phone_verification_phone'] = $phone;
                    $alert = 'کد جدید ارسال شد.';
                    $alert_type = 'success';
                } else {
                    // حذف رکورد بی‌استفاده
                    $d = mysqli_prepare($conn, "DELETE FROM phone_verifications WHERE m_verify_id = ?");
                    if ($d) { mysqli_stmt_bind_param($d, "i", $vid); mysqli_stmt_execute($d); mysqli_stmt_close($d); }
                    $alert = 'ارسال پیامک با خطا مواجه شد. لطفاً بعداً تلاش کنید.';
                    $alert_type = 'danger';
                }
            } else {
                mysqli_stmt_close($ins);
                $alert = 'خطا در ذخیرهٔ کد. لطفاً دوباره تلاش کنید.';
                $alert_type = 'danger';
            }
        } else {
            $alert = 'خطا در آماده‌سازی ذخیرهٔ کد.';
            $alert_type = 'danger';
        }
    }
}

// --- نمایش فرم HTML ---
?>
<!doctype html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>تایید شماره تلفن</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    body { font-family: Tahoma, Arial, sans-serif; background:#f7f7f7; padding:20px; direction:rtl; }
    .card { max-width:480px; margin:30px auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.08); }
    h2 { margin-top:0; }
    .alert { padding:10px; border-radius:4px; margin-bottom:12px; }
    .alert.info { background:#e7f3fe; color:#0c5460; }
    .alert.success { background:#e6ffed; color:#155724; }
    .alert.danger { background:#ffecec; color:#721c24; }
    label { display:block; margin-bottom:8px; }
    input[type="text"] { width:100%; padding:10px; box-sizing:border-box; margin-bottom:10px; }
    button { padding:10px 14px; border:0; border-radius:6px; background:#0066cc; color:#fff; cursor:pointer; }
    .muted { color:#666; font-size:13px; margin-bottom:8px; }
    .row { display:flex; gap:8px; }
    .row button { flex:1; }
    a.small { font-size:13px; color:#0066cc; text-decoration:none; }
  </style>
</head>
<body>
  <div class="card">
    <h2>تایید شماره تلفن</h2>

    <?php if ($alert !== ''): ?>
      <div class="alert <?php echo ($alert_type==='danger'?'danger':($alert_type==='success'?'success':'info')); ?>">
        <?php echo htmlspecialchars($alert); ?>
      </div>
    <?php endif; ?>

    <?php if (empty($phone)): ?>
      <p class="muted">شمارهٔ تلفنی برای تأیید یافت نشد. لطفاً مجدداً از صفحهٔ پروفایل، شماره را بروز کنید.</p>
      <p><a class="small" href="manager_profile.php">بازگشت به پروفایل</a></p>
    <?php else: ?>
      <p class="muted">کدی به شمارهٔ <strong><?php echo htmlspecialchars($phone); ?></strong> ارسال شده است. (تا ۵ دقیقه معتبر)</p>

      <form method="post" autocomplete="off" dir="rtl">
        <label>کد ۶ رقمی:
          <input type="text" name="verify_code" pattern="\d{6}" maxlength="6" inputmode="numeric" required>
        </label>
        <div class="row">
          <button type="submit">تایید کد</button>
          <button type="submit" name="resend_code" value="1" style="background:#28a745;">ارسال دوباره</button>
        </div>
      </form>

      <p style="margin-top:12px" class="muted">
        اگر پیامک را دریافت نکردید، دقایقی صبر کنید و سپس «ارسال دوباره» را بزنید.
        در صورت مشکل با پشتیبانی تماس بگیرید.
      </p>
      <p><a class="small" href="manager_profile.php">بازگشت به پروفایل</a></p>
    <?php endif; ?>
  </div>
</body>
</html>
