<?php
// mp_config_service.php
session_start();
require_once 'database.php';
//$conn = db_connect();

$manager_id = $_SESSION['manager_id'];
$salon_id   = $_SESSION['salon_id'];

if (empty($_SESSION['manager_id'])) {
    $_SESSION['login_error'] = "ابتدا باید به سایت ورود کنید";
    //$conn = null;
    mysqli_close($conn);
    header("Location: login.php");
    exit();
}

if (empty($_SESSION['salon_id'])) {
    $_SESSION['salon_error'] = "ابتدا فرم اطلاعات سالن را تکمیل کنید";
    //$conn = null;
    mysqli_close($conn);
    header("Location: manager_profile.php#service_salon");
    exit();
}


// ذخیرهٔ لیست خدمات (چند ردیف)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // دریافت آرایه‌ها (همه به صورت آرایه ارسال می‌شوند)
    $types = $_POST['serviceType'] ?? [];
    $customs = $_POST['customService'] ?? [];
    $durations = $_POST['serviceDuration'] ?? [];
    $prices = $_POST['servicePrice'] ?? [];
    $discounts = $_POST['serviceDiscount'] ?? [];
    $finals = $_POST['servicePriceFinal'] ?? [];
    $startDates = $_POST['discountStartDate'] ?? [];
    $endDates = $_POST['discountEndDate'] ?? [];
    $statuses = $_POST['serviceStatus'] ?? [];


    // همۀ آرایه‌ها باید حداقل به‌اندازهٔ count ای که استفاده می‌کنیم باشند
    $count = max(
        count($types),
        count($customs),
        count($durations),
        count($prices),
        count($discounts),
        count($finals),
        count($startDates),
        count($endDates),
        count($statuses)
    );

    if ($count === 0) {
        $_SESSION['mp_emptyfield_service_error'] = "هیچ خدمتی برای ذخیره ارسال نشده است.";
        header("Location: manager_profile.php#service_salon");
        exit();
    }

    // آماده‌سازی کوئری‌ها (یک‌بار)
    $insertSql = "INSERT INTO service (salon_id, service_name, duration_min, price, discount, final_price, discount_start_date, discount_end_date, is_active)
                  VALUES (?,?,?,?,?,?,?,?,?)";
    $stmtInsert = mysqli_prepare($conn, $insertSql);

    $updateSql = "UPDATE service SET duration_min = ?, price = ?, discount = ?, final_price = ?, discount_start_date = ?, discount_end_date = ?, is_active = ? WHERE service_id = ?";
    $stmtUpdate = mysqli_prepare($conn, $updateSql);

    $checkSql = "SELECT service_id FROM service WHERE salon_id = ? AND service_name = ? LIMIT 1";
    $stmtCheck = mysqli_prepare($conn, $checkSql);


    for ($i = 0; $i < $count; $i++) {
        // خواندن ایمن مقادیر — بررسی می‌کنیم مقدار در آرایه هست یا نه
        $type = isset($types[$i]) ? test_input($types[$i]) : '';
        $custom = isset($customs[$i]) ? test_input($customs[$i]) : '';
        $duration = isset($durations[$i]) ? test_input($durations[$i]) : 0;
        $price = isset($prices[$i]) ? test_input($prices[$i]) : 0;
        $discount = isset($discounts[$i]) ? test_input($discounts[$i]) : 0;
        $final = isset($finals[$i]) ? test_input($finals[$i]) : 0;
        $start = isset($startDates[$i]) && $startDates[$i] !== '' ? test_input($startDates[$i]) : null;
        $end = isset($endDates[$i]) && $endDates[$i] !== '' ? test_input($endDates[$i]) : null;
        $status = isset($statuses[$i]) ? test_input($statuses[$i]) : 'فعال';

        // یعنی در هر ردیف این را بررسی می کند
        // نام سرویس: اگر custom پر شده باشد اولویت با آن است
        $service_name = $custom !== '' ? $custom : $type;

        if (empty($type) && empty($custom)) {
            $_SESSION['mp_emptyfield_error'] = "فیلدهای ستاره دار باید تکمیل شوند";
            //$conn = null;
            mysqli_close($conn);
            header("Location: manager_profile.php#service_salon");
            exit();
        }

        if ($price < 0 || $duration < 0) {
            $_SESSION['negative_value_error'] = "مدت زمان و قیمت نمی توانند مقدار منفی داشته باشند";
            //$conn = null;
            mysqli_close($conn);
            header("Location: manager_profile.php#service_salon");
            exit();
        }

        if ($discount < 0 || $discount > 100) {
            $_SESSION['discount_error'] = "مقدار تخفیف باید عددی بین 0 و 100 باشد";
            //$conn = null;
            mysqli_close($conn);
            header("Location: manager_profile.php#service_salon");
            exit();
        }

        $specific_date = new DateTime($end);
        $current_date = new DateTime();

        // اتمام تخفیف بعد از تاریخ پایان تخفیف
        if ($current_date > $specific_date) {
            $discount = 0;
        }

        $final = $price - ($price * ($discount / 100));

        // تبدیل وضعیت به 0/1
        $is_active = ($status === 'فعال' || $status === '1' || $status === 1) ? 1 : 0;

        // بررسی وجود سرویس با همین نام برای همین سالن
        mysqli_stmt_bind_param($stmtCheck, "is", $salon_id, $service_name);
        mysqli_stmt_execute($stmtCheck);
        mysqli_stmt_store_result($stmtCheck);

        if (mysqli_stmt_num_rows($stmtCheck) > 0) {
            // موجود است -> UPDATE
            mysqli_stmt_bind_result($stmtCheck, $existing_service_id);
            mysqli_stmt_fetch($stmtCheck);
            // UPDATE: duration_min=?, price=?, discount=?, final_price=?, discount_start_date=?, discount_end_date=?, is_active=? WHERE service_id=?
            mysqli_stmt_bind_param($stmtUpdate, "iiiissii", $duration, $price, $discount, $final, $start, $end, $is_active, $existing_service_id);
            mysqli_stmt_execute($stmtUpdate);
            // اگر لازم است، میتوانید بررسی کنید mysqli_stmt_affected_rows
        } else {
            // موجود نیست -> INSERT
            // VALUES (salon_id, service_name, duration_min, price, discount, final_price, discount_start_date, discount_end_date, is_active)
            mysqli_stmt_bind_param($stmtInsert, "isiiiissi", $salon_id, $service_name, $duration, $price, $discount, $final, $start, $end, $is_active);
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

    header("Location: manager_profile.php#service_salon");
    exit();
}


// حذف یک سرویس ثبت‌شده
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_service'])) {

    // انتظار داریم service_id[] و delete_service[] ارسال شود (معمولاً فقط یک مقدار چون هر فرم برای یک ردیف است)
    $service_ids = $_POST['service_id'] ?? [];

    if (count($service_ids) === 0) {
        $_SESSION['mp_delete_service_error'] = "درخواست حذف نامعتبر است.";
        header("Location: manager_profile.php#service_salon");
        exit();
    }

    $qdel = "DELETE FROM service WHERE service_id = ?";
    $stdel = mysqli_prepare($conn, $qdel);

    foreach ($service_ids as $sid) {
        $service_id = intval($sid);
        if ($service_id <= 0) continue;
        mysqli_stmt_bind_param($stdel, 'i', $service_id);
        if (mysqli_stmt_execute($stdel)) {
            $_SESSION['mp_delete_service'] = "خدمت با موفقیت حذف شد.";
        } else {
            $_SESSION['mp_not_delete_service'] = "خطا در حذف خدمت.";
        }
    }
    mysqli_stmt_close($stdel);
    mysqli_close($conn);
    //$conn = null;
    header("Location: manager_profile.php#service_salon");
    exit();
}


// اگر به اینجا رسیدی بدون POST، بازگرد
header("Location: manager_profile.php#service_salon");
exit();
