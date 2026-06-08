<?php
session_start();
require_once 'database.php';

//=====================for debug=================================
// debug helpers
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start();

// چاپ نشانه‌ها (برای دیدن در سورس صفحه)
echo "<!-- DEBUG: manager_profile.php start -->\n";

// ثبت خاموشی‌های fatal
register_shutdown_function(function () {
    $err = error_get_last();
    if ($err) {
        echo "<pre>SHUTDOWN ERROR: " . htmlspecialchars(print_r($err, true)) . "</pre>";
        @file_put_contents(__DIR__ . '/debug_error.log', date("c") . " SHUTDOWN: " . print_r($err, true) . "\n", FILE_APPEND);
    } else {
        echo "<!-- SHUTDOWN: no last error -->\n";
    }
});

// گرفتن خطاهای معمولی و لاگ کردنشان
set_error_handler(function ($severity, $message, $file, $line) {
    $txt = "PHP ERROR: [$severity] $message in $file on line $line\n";
    @file_put_contents(__DIR__ . '/debug_error.log', date("c") . " " . $txt, FILE_APPEND);
    echo "<pre>" . htmlspecialchars($txt) . "</pre>";
    return false; // ادامه handler پیش‌فرض هم اجرا شود
});

//=========================end debug===================================

/* بدین صورت می توانم یک مدت زمان برای لاگین ماندن تعریف کنم
// Session timeout check (30 minutes)
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    session_unset();
    session_destroy();
    header("Location: login.php?timeout=1");
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time(); 
*/

// Persisted session data
$manager_username  = $_SESSION['manager_username']  ?? "";
$manager_id  = $_SESSION['manager_id']  ?? "";
$manager_firstName  = $_SESSION['manager_firstName']  ?? "نام";
$manager_lastName  = $_SESSION['manager_lastName']  ?? "نام خانوادگی";

// Check if manager is logged in
if (empty($_SESSION['manager_id'])) {
    $_SESSION['login_error'] = "ابتدا باید به سایت ورود کنید";
    //mysqli_close($conn);
    //$conn = null;
    header("Location: login.php");
    exit();
}

/* 
// اگر توکن وجود ندارد، یک توکن امن بساز
if(empty($_SESSION['csrf_token'])){
$_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // 32 بایت امن -> 64 کاراکتر هگز
}

$csrf_token = $_SESSION['csrf_token'];
*/


//$salon_id = $_SESSION['salon_id'] ?? "";
//$location_id = $_SESSION['location_id'] ?? "";
//$bank_account_id = $_SESSION['bank_account_id'] ?? "";

/* 
// برای نمایش پیام‌های فلش
function flash($key) {
    if (!empty($_SESSION[$key])) {
        $msg = $_SESSION[$key];
        unset($_SESSION[$key]);
        return htmlspecialchars($msg);
    }
    return '';
}
*/

// Flash message (display once)
$mp_confirm  = $_SESSION['mp_confirm']  ?? "";
$mp_not_confirm = $_SESSION['mp_not_confirm'] ?? "";
$mp_emptyfield_error = $_SESSION['mp_emptyfield_error'] ?? "";
$mp_phone_error = $_SESSION['mp_phone_error'] ?? "";
$mp_nationalCode_error = $_SESSION['mp_nationalCode_error'] ?? "";
$mp_email_error = $_SESSION['mp_email_error'] ?? "";
$mp_cardNumber_error = $_SESSION['mp_cardNumber_error'] ?? "";
$mp_sheba_error = $_SESSION['mp_sheba_error'] ?? "";
$mp_serviceDuration_error = $_SESSION['mp_serviceDuration_error'] ?? "";
$mp_servicePrice_error = $_SESSION['mp_servicePrice_error'] ?? "";
$mp_serviceDiscount_error = $_SESSION['mp_serviceDiscount_error'] ?? "";
$mp_empty_salon_error = $_SESSION['empty_salon_error'] ?? "";
$negative_value_error = $_SESSION['negative_value_error'] ?? "";
$discount_error = $_SESSION['discount_error'] ?? "";
$mp_emptyfield_service_error = $_SESSION['mp_emptyfield_service_error'] ?? "";
$mp_delete_service_error = $_SESSION['mp_delete_service_error'] ?? "";
$mp_delete_service = $_SESSION['mp_delete_service'] ?? "";
$mp_not_delete_service = $_SESSION['mp_not_delete_service'] ?? "";

$mp_option_error = $_SESSION['mp_option_error'] ?? "";
$mp_emptyfield_option_error = $_SESSION['mp_emptyfield_option_error'] ?? "";
$mp_delete_option = $_SESSION['mp_delete_option'] ?? "";
$mp_not_delete_option = $_SESSION['mp_not_delete_option'] ?? "";
$mp_delete_hour_error = $_SESSION['mp_delete_hour_error'] ?? "";
$mp_delete_hour = $_SESSION['mp_delete_hour'] ?? "";
$mp_not_delete_hour = $_SESSION['mp_not_delete_hour'] ?? "";
$mp_hours_success = $_SESSION['mp_hours_success'] ?? "";
$duplicate_manager_phone = $_SESSION['duplicate_manager_phone'] ?? "";
$mp_emptyfield_portfolio_error = $_SESSION['mp_emptyfield_portfolio_error'] ?? "";

$salon_sample_file_error = $_SESSION['salon_sample_file_error'] ?? "";
$salon_sample_fileSize_error = $_SESSION['salon_sample_fileSize_error'] ?? "";
$salon_sample_fileType_error = $_SESSION['salon_sample_fileType_error'] ?? "";
$salon_sample_num_error = $_SESSION['salon_sample_num_error'] ?? "";
$mp_upload_sample = $_SESSION['mp_upload_sample'] ?? "";
$mp_upload_sample_error = $_SESSION['mp_upload_sample_error'] ?? "";
$mp_delete_sample_error = $_SESSION['mp_delete_sample_error'] ?? "";
$mp_delete_sample = $_SESSION['mp_delete_sample'] ?? "";
$mp_not_delete_sample = $_SESSION['mp_not_delete_sample'] ?? "";
$salon_error = $_SESSION['salon_error'] ?? "";



unset(
    $_SESSION['mp_confirm'],
    $_SESSION['mp_not_confirm'],
    $_SESSION['mp_emptyfield_error'],
    $_SESSION['mp_phone_error'],
    $_SESSION['mp_nationalCode_error'],
    $_SESSION['mp_email_error'],
    $_SESSION['mp_cardNumber_error'],
    $_SESSION['mp_sheba_error'],
    $_SESSION['mp_serviceDuration_error'],
    $_SESSION['mp_servicePrice_error'],
    $_SESSION['mp_serviceDiscount_error'],
    $_SESSION['empty_salon_error'],
    $_SESSION['negative_value_error'],
    $_SESSION['discount_error'],
    $_SESSION['mp_emptyfield_service_error'],
    $_SESSION['mp_delete_service_error'],
    $_SESSION['mp_delete_service'],
    $_SESSION['mp_not_delete_service'],
    $_SESSION['mp_option_error'],
    $_SESSION['mp_emptyfield_option_error'],
    $_SESSION['mp_delete_option'],
    $_SESSION['mp_not_delete_option'],
    $_SESSION['mp_delete_hour_error'],
    $_SESSION['mp_delete_hour'],
    $_SESSION['mp_not_delete_hour'],
    $_SESSION['mp_hours_success'],
    $_SESSION['duplicate_manager_phone'],
    $_SESSION['mp_emptyfield_portfolio_error'],
    $_SESSION['salon_sample_file_error'],
    $_SESSION['salon_sample_fileSize_error'],
    $_SESSION['salon_sample_fileType_error'],
    $_SESSION['salon_sample_num_error'],
    $_SESSION['mp_upload_sample'],
    $_SESSION['mp_upload_sample_error'],
    $_SESSION['mp_delete_sample_error'],
    $_SESSION['mp_delete_sample'],
    $_SESSION['mp_not_delete_sample'],
    $_SESSION['salon_error']
);

//require_once 'login.php';

?>

<!doctype html>
<html class="no-js" lang="fa" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title> سالن زیبایی و آرایشی </title>
    <meta name="author" content="Vecuro">
    <meta name="description" content="B.B - Spa Beauty & Wellness Salon HTML5 Template">
    <meta name="keywords" content="beauty, beauty salon, beauty shop, beauty spa, cosmetics, hairdresser, health, lifestyle, massage, salon, spa, spa booking, wellness, wellness template, yoga">
    <meta name="robots" content="INDEX,FOLLOW">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Favicons - Place favicon.ico in the root directory -->
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">

    <!--==============================
	  Google Fonts
	============================== -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Marcellus&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;700&display=swap" rel="stylesheet">

    <!--==============================
	    All CSS File
	============================== -->
    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="assets/css/app.min.css"> -->
    <!-- Fontawesome Icon -->
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <!-- Layerslider -->
    <link rel="stylesheet" href="assets/css/layerslider.min.css">
    <!-- jQuery DatePicker -->
    <link rel="stylesheet" href="assets/css/jquery.datetimepicker.min.css">
    <!-- Magnific Popup -->
    <link rel="stylesheet" href="assets/css/magnific-popup.min.css">
    <!-- Slick Slider -->
    <link rel="stylesheet" href="assets/css/slick.min.css">
    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- در بخش head -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">

    <!-- Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous"> -->
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/md.bootstrappersiandatetimepicker/dist/jquery.md.bootstrap.datetimepicker.style.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> -->

    <link rel="stylesheet" href="assets/css/profile.css">
    <link rel="stylesheet" href="assets/css/manager-profile.css">

    <!-- jQuery Datepicker -->
    <script src="assets/js/jquery.datetimepicker.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script scr="assets/js/landing.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/persian-date@1.1.0/dist/persian-date.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
    <!-- jQuery DatePicker -->
    <link rel="stylesheet" href="assets/css/jquery.datetimepicker.min.css">


    <!--==============================
	  Google Fonts
	============================== -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Marcellus&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!--- =========== new persian font ====== -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Scheherazade+New:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Reem+Kufi:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- فونت ایران یاقوت -->
    <link href="https://fontiran.com/css/fontiran.css" rel="stylesheet">

    <!-- فونت صبا -->
    <link href="https://cdn.fontcdn.ir/Font/Persian/Sahel/Sahel.css" rel="stylesheet">
    <!-- jQuery DatePicker -->
    <link rel="stylesheet" href="assets/css/jquery.datetimepicker.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
    <!-- jQuery Datepicker -->
    <script src="assets/js/jquery.datetimepicker.min.js"></script>
    <!-- Magnific Popup -->
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <!-- Isotope Filter -->
    <script src="assets/js/imagesloaded.pkgd.min.js"></script>
    <script src="assets/js/isotope.pkgd.min.js"></script>


    <!--==============================
	    All CSS File
	============================== -->
    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="assets/css/app.min.css"> -->
    <!-- Fontawesome Icon -->
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <!-- Layerslider -->
    <link rel="stylesheet" href="assets/css/layerslider.min.css">
    <!-- jQuery DatePicker -->
    <link rel="stylesheet" href="assets/css/jquery.datetimepicker.min.css">
    <!-- Magnific Popup -->
    <link rel="stylesheet" href="assets/css/magnific-popup.min.css">
    <!-- Slick Slider -->
    <link rel="stylesheet" href="assets/css/slick.min.css">
    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/landing.css">

</head>


<body class="home-7">


    <!--********************************
   		Code Start From Here 
	******************************** -->

    <!--==============================
     Preloader
  ==============================-->
    <div class="preloader  ">
        <!-- <button class="vs-btn preloaderCls">لغو بارگذاری</button> -->
        <div class="preloader-inner">
            <div class="loader"></div>
        </div>
    </div>
    <!--==============================
    Mobile Menu
  ============================== -->

    <?php include 'assets/includes/mobile-menu.php'; ?>

    <!--==============================
    Popup Search Box
    ============================== -->

    <!--==============================
    Header Area
    ==============================-->
    <header class="main-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h4 mb-0" style="margin:auto;">پنل مدیریت</h1>
                <div class="d-flex align-items-center">
                    <a href="manager_page.php" class="header-back-button">
                        <i class="fas fa-arrow-right"></i>
                        بازگشت
                    </a>
                </div>

            </div>
        </div>
        <style>

        </style>
    </header>
    <!--==============================
    Hero Area
    ==============================-->
    <section class="vs-hero-wrapper hero-layout7 position-relative">
        <!-- Admin Panel Content -->
        <div class="admin-container">
            <!-- Sidebar -->
            <div class="admin-sidebar">
                <?php if (!empty($mp_confirm)): ?>
                    <div class="notification1"><?php echo $mp_confirm; ?></div>
                <?php elseif (!empty($mp_not_confirm)): ?>
                    <div class="notification2"><?php echo $mp_not_confirm; ?></div>
                <?php elseif (!empty($mp_emptyfield_error)): ?>
                    <div class="notification2"><?php echo $mp_emptyfield_error; ?></div>
                <?php elseif (!empty($mp_phone_error)): ?>
                    <div class="notification2"><?php echo $mp_phone_error; ?></div>
                <?php elseif (!empty($mp_nationalCode_error)): ?>
                    <div class="notification2"><?php echo $mp_nationalCode_error; ?></div>
                <?php elseif (!empty($mp_email_error)): ?>
                    <div class="notification2"><?php echo $mp_email_error; ?></div>
                <?php elseif (!empty($mp_cardNumber_error)): ?>
                    <div class="notification2"><?php echo $mp_cardNumber_error; ?></div>
                <?php elseif (!empty($mp_sheba_error)): ?>
                    <div class="notification2"><?php echo $mp_sheba_error; ?></div>
                <?php elseif (!empty($mp_serviceDuration_error)): ?>
                    <div class="notification2"><?php echo $mp_serviceDuration_error; ?></div>
                <?php elseif (!empty($mp_servicePrice_error)): ?>
                    <div class="notification2"><?php echo $mp_servicePrice_error; ?></div>
                <?php elseif (!empty($mp_serviceDiscount_error)): ?>
                    <div class="notification2"><?php echo $mp_serviceDiscount_error; ?></div>
                <?php elseif (!empty($mp_empty_salon_error)): ?>
                    <div class="notification2"><?php echo $mp_empty_salon_error; ?></div>
                <?php elseif (!empty($negative_value_error)): ?>
                    <div class="notification2"><?php echo $negative_value_error; ?></div>
                <?php elseif (!empty($discount_error)): ?>
                    <div class="notification2"><?php echo $discount_error; ?></div>
                <?php elseif (!empty($mp_emptyfield_service_error)): ?>
                    <div class="notification2"><?php echo $mp_emptyfield_service_error; ?></div>
                <?php elseif (!empty($mp_delete_service_error)): ?>
                    <div class="notification2"><?php echo $mp_delete_service_error; ?></div>
                <?php elseif (!empty($mp_delete_service)): ?>
                    <div class="notification1"><?php echo $mp_delete_service; ?></div>
                <?php elseif (!empty($mp_not_delete_service)): ?>
                    <div class="notification2"><?php echo $mp_not_delete_service; ?></div>
                <?php elseif (!empty($mp_option_error)): ?>
                    <div class="notification2"><?php echo $mp_option_error; ?></div>
                <?php elseif (!empty($mp_emptyfield_option_error)): ?>
                    <div class="notification2"><?php echo $mp_emptyfield_option_error; ?></div>
                <?php elseif (!empty($mp_delete_option)): ?>
                    <div class="notification1"><?php echo $mp_delete_option; ?></div>
                <?php elseif (!empty($mp_not_delete_option)): ?>
                    <div class="notification2"><?php echo $mp_not_delete_option; ?></div>
                <?php elseif (!empty($mp_delete_hour)): ?>
                    <div class="notification1"><?php echo $mp_delete_hour; ?></div>
                <?php elseif (!empty($mp_not_delete_hour)): ?>
                    <div class="notification2"><?php echo $mp_not_delete_hour; ?></div>
                <?php elseif (!empty($mp_hours_success)): ?>
                    <div class="notification1"><?php echo $mp_hours_success; ?></div>
                <?php elseif (!empty($duplicate_manager_phone)): ?>
                    <div class="notification2"><?php echo $duplicate_manager_phone; ?></div>
                <?php elseif (!empty($mp_emptyfield_portfolio_error)): ?>
                    <div class="notification2"><?php echo $mp_emptyfield_portfolio_error; ?></div>
                <?php elseif (!empty($salon_sample_file_error)): ?>
                    <div class="notification2"><?php echo $salon_sample_file_error; ?></div>
                <?php elseif (!empty($salon_sample_fileSize_error)): ?>
                    <div class="notification2"><?php echo $salon_sample_fileSize_error; ?></div>
                <?php elseif (!empty($salon_sample_fileType_error)): ?>
                    <div class="notification2"><?php echo $salon_sample_fileType_error; ?></div>
                <?php elseif (!empty($salon_sample_num_error)): ?>
                    <div class="notification2"><?php echo $salon_sample_num_error; ?></div>
                <?php elseif (!empty($mp_upload_sample)): ?>
                    <div class="notification1"><?php echo $mp_upload_sample; ?></div>
                <?php elseif (!empty($mp_upload_sample_error)): ?>
                    <div class="notification2"><?php echo $mp_upload_sample_error; ?></div>
                <?php elseif (!empty($mp_delete_sample_error)): ?>
                    <div class="notification2"><?php echo $mp_delete_sample_error; ?></div>
                <?php elseif (!empty($mp_delete_sample)): ?>
                    <div class="notification1"><?php echo $mp_delete_sample; ?></div>
                <?php elseif (!empty($mp_not_delete_sample)): ?>
                    <div class="notification2"><?php echo $mp_not_delete_sample; ?></div>
                <?php elseif (!empty($salon_error)): ?>
                    <div class="notification2"><?php echo $salon_error; ?></div>
                <?php endif; ?>


                <div class="text-center mb-4">
                    <div class="profile-image-container position-relative">
                        <?php include 'mp_personal_photo.php'; ?>
                        <!-- <img id="main-profile-image" src="assets/img/team/user1-128x128.jpg" alt="User Avatar" class="rounded-circle mb-3 profile-image" style="width: 100px; height: 100px; object-fit: cover;"> -->
                    </div>
                    <h5 class="mb-1"><?php echo $manager_firstName . "  " . $manager_lastName; ?></h5>
                    <p class="text-muted mb-0"> مدیر </p>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="#profile" data-bs-toggle="collapse" data-bs-target="#profileSubmenu" aria-expanded="true">
                        <i class="fas fa-user"></i> پروفایل کاربری
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </a>
                    <div class="collapse show" id="profileSubmenu">
                        <div class="nav flex-column ms-3">
                            <a class="nav-link" href="#personal_info">
                                <i class="fas fa-user-circle"></i> اطلاعات شخصی
                            </a>
                            <a class="nav-link" href="#salon_info">
                                <i class="fas fa-user-circle"></i> اطلاعات سالن
                            </a>
                            <a class="nav-link" href="#bank_info">
                                <i class="fas fa-bank"></i> اطلاعات بانکی
                            </a>
                            <a class="nav-link" href="#service_salon">
                                <i class="fas fa-concierge-bell"></i> خدمات سالن
                            </a>
                            <a class="nav-link" href="#option_salon">
                                <i class="fas fa-concierge-bell"></i> امکانات سالن
                            </a>
                            <a class="nav-link" href="#working_hours">
                                <i class="fas fa-clock"></i> ساعات کاری
                            </a>
                            <a class="nav-link" href="#portfoliopics">
                                <i class="fas fa-images"></i> نمونه تصاویر
                            </a>
                            <a class="nav-link" href="#financial_report">
                                <i class="fas fa-chart-line"></i> گزارش مالی
                            </a>
                            <a class="nav-link" href="#my_appointments">
                                <i class="fas fa-calendar-check"></i> نوبت‌های رزرو شده
                            </a>
                        </div>
                    </div>

                    <a class="nav-link" href="#settings">
                        <i class="fas fa-cog"></i> تنظیمات اعلان ها
                    </a>
                    <a class="nav-link" href="#support_center">
                        <i class="fas fa-headset"></i> پشتیبانی - ارسال تیکت
                    </a>
                    <a class="nav-link" href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> خروج از حساب کاربری
                    </a>

                    <!-- <form action="logout.php" method="post" class="nav-link">
                        <button type="submit" name="submit" class="fas fa-sign-out-alt">خروج از حساب کاربری</button>
                    </form> -->

                </nav>
            </div>

            <!-- Main Content -->
            <div class="admin-content">
                <!-- Stats Overview -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <i class="fas fa-calendar-check"></i>
                        <h3>۱۵</h3>
                        <p>نوبت‌های امروز</p>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-users"></i>
                        <h3>۲۵۰</h3>
                        <p>نوبت های انجام شده </p>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-money-bill-wave"></i>
                        <h3>۵,۰۰۰,۰۰۰</h3>
                        <p>جمع واریزی ها</p>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-star"></i>
                        <h3>۴.۸</h3>
                        <p>امتیاز کلی</p>
                    </div>
                </div>

                <!-- Content Sections -->
                <div class="content-sections">
                    <!-- Personal Information -->
                    <div class="admin-card" id="personal_info">
                        <div class="admin-card-header">
                            <h5 class="mb-0">اطلاعات شخصی</h5>
                        </div>
                        <div class="admin-card-body">
                            <form action="mp_config_personal.php" method="post" id="personalInfoForm" dir="rtl" enctype="multipart/form-data">
                                <!-- <input type="hidden" name="csrf_token" value="<? //= htmlspecialchars($csrf_token) 
                                                                                    ?>"> -->
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="firstName" class="form-label text-end d-block">نام <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control text-end" id="firstName" name="firstName" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="lastName" class="form-label text-end d-block">نام خانوادگی</label>
                                            <input type="text" class="form-control text-end" id="lastName" name="lastName">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nationalCode" class="form-label text-end d-block">کد ملی </label>
                                            <input type="text" class="form-control text-end" id="nationalCode" name="nationalCode" maxlength="10" pattern="[0-9]{10}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phoneNumber" class="form-label text-end d-block">شماره تماس <span class="text-danger">*</span></label>
                                            <input type="tel" class="form-control text-end" id="phoneNumber" name="phoneNumber" pattern="[0-9]{11}" maxlength="11" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="managerEmail">ایمیل</label>
                                            <input type="email" id="managerEmail" name="managerEmail">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="managerPhoto">عکس پروفایل</label>
                                            <input type="file" id="managerPhoto" name="managerPhoto">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 text-center mt-4">
                                    <button type="submit" name="submit" value="1" class="vs-btn style12">ذخیره اطلاعات</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- generated table for user to see submitted data of personal info -->
                    <?php include 'tbl_mp_personal.php'; ?>


                    <!-- salon Information -->
                    <div class="admin-card" id="salon_info">
                        <div class="admin-card-header">
                            <h5 class="mb-0">اطلاعات سالن</h5>
                        </div>
                        <div class="admin-card-body">
                            <form action="mp_config_saloninfo.php" method="post" id="salonInfoForm" dir="rtl">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="salonType" class="form-label text-end d-block">نوع سالن زیبایی <span class="text-danger">*</span></label>
                                            <select class="form-select text-end" id="salonType" name="salonType" required>
                                                <option value="">انتخاب نوع سالن</option>
                                                <option value="بانوان">بانوان</option>
                                                <option value="آقایان">آقایان</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="salonName" class="form-label text-end d-block">نام سالن زیبایی <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control text-end" id="salonName" name="salonName" required placeholder="نام سالن زیبایی خود را وارد کنید">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="province" class="form-label text-end d-block">استان <span class="text-danger">*</span></label>
                                            <select class="form-select text-end" id="province" name="province" required>
                                                <option value="">انتخاب استان</option>
                                                <option value="البرز">البرز</option>
                                                <option value="اردبیل">اردبیل</option>
                                                <option value="بوشهر">بوشهر</option>
                                                <option value="چهارمحال و بختیاری">چهارمحال و بختیاری</option>
                                                <option value="آذربایجان شرقی">آذربایجان شرقی</option>
                                                <option value="فارس">فارس</option>
                                                <option value="گیلان">گیلان</option>
                                                <option value="گلستان">گلستان</option>
                                                <option value="همدان">همدان</option>
                                                <option value="هرمزگان">هرمزگان</option>
                                                <option value="ایلام">ایلام</option>
                                                <option value="اصفهان">اصفهان</option>
                                                <option value="کرمان">کرمان</option>
                                                <option value="کرمانشاه">کرمانشاه</option>
                                                <option value="خوزستان">خوزستان</option>
                                                <option value="کهکیلویه و بویراحمد">کهگیلویه و بویراحمد</option>
                                                <option value="کردستان">کردستان</option>
                                                <option value="لرستان">لرستان</option>
                                                <option value="مرکزی">مرکزی</option>
                                                <option value="مازندران">مازندران</option>
                                                <option value="خراسان شمالی">خراسان شمالی</option>
                                                <option value="قزوین">قزوین</option>
                                                <option value="قم">قم</option>
                                                <option value="خراسان رضوی">خراسان رضوی</option>
                                                <option value="سمنان">سمنان</option>
                                                <option value="سیستان و بلوچستان">سیستان و بلوچستان</option>
                                                <option value="خراسان جنوبی">خراسان جنوبی</option>
                                                <option value="تهران">تهران</option>
                                                <option value="آذربایجان غربی">آذربایجان غربی</option>
                                                <option value="یزد">یزد</option>
                                                <option value="زنجان">زنجان</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="city" class="form-label text-end d-block">شهر <span class="text-danger">*</span></label>
                                            <select class="form-select text-end" id="city" name="city" required disabled>
                                                <option value="">ابتدا استان را انتخاب کنید</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="detailedAddress" class="form-label text-end d-block">آدرس دقیق <span class="text-danger">*</span></label>
                                            <textarea class="form-control text-end" id="detailedAddress" name="detailedAddress" rows="3" required placeholder="لطفا آدرس دقیق محل کار خود را وارد کنید"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="workPhoneNumber" class="form-label text-end d-block">شماره تماس محل کار</label>
                                            <input type="tel" class="form-control text-end" id="workPhoneNumber" name="workPhoneNumber" pattern="[0-9]{11}" maxlength="11" placeholder="شماره موبایل یا شماره محل کار به همراه کد شهر">
                                        </div>
                                    </div>
                                    <div class="col-12 text-center mt-4">
                                        <button type="submit" name="submit" class="vs-btn style12">ذخیره اطلاعات</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- generated table for user to see submitted data of salon info -->
                    <?php include 'tbl_mp_saloninfo.php'; ?>

                    <!-- Bank account Information -->
                    <div class="admin-card" id="bank_info">
                        <div class="admin-card-header">
                            <h5 class="mb-0">اطلاعات بانکی</h5>
                        </div>
                        <div class="admin-card-body">
                            <form action="mp_config_bank.php" method="post" id="bankInfoForm" dir="rtl">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="bankName" class="form-label text-end d-block">نام بانک <span class="text-danger">*</span></label>
                                            <select class="form-select text-end" id="bankName" name="bankName" required>
                                                <option value="">انتخاب بانک</option>
                                                <option value="ملت">بانک ملت</option>
                                                <option value="ملی">بانک ملی ایران</option>
                                                <option value="صادرات">بانک صادرات ایران</option>
                                                <option value="پارسیان">بانک پارسیان</option>
                                                <option value="پاسارگاد">بانک پاسارگاد</option>
                                                <option value="سامان">بانک سامان</option>
                                                <option value="سینا">بانک سینا</option>
                                                <option value="تجارت">بانک تجارت</option>
                                                <option value="رسالت">بانک رسالت</option>
                                                <option value="رفاه کارگران">بانک رفاه کارگران</option>
                                                <option value="سپه">بانک سپه</option>
                                                <option value="کشاورزی">بانک کشاورزی</option>
                                                <option value="مسکن">بانک مسکن</option>
                                                <option value="پست بانک">پست بانک ایران</option>
                                                <option value="انصار">بانک انصار</option>
                                                <option value="حکمت ایرانیان">بانک حکمت ایرانیان</option>
                                                <option value="کارآفرین">بانک کارآفرین</option>
                                                <option value="کوثر">بانک کوثر</option>
                                                <option value="مهر ایران">بانک مهر ایران</option>
                                                <option value="مهر اقتصاد">بانک مهر اقتصاد</option>
                                                <option value="شهر">بانک شهر</option>
                                                <option value="توسعه تعاون">بانک توسعه تعاون</option>
                                                <option value="توسعه صادرات">بانک توسعه صادرات</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="cardNumber" class="form-label text-end d-block">شماره کارت <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control text-end" id="cardNumber" name="cardNumber"
                                                pattern="[0-9]{16}" maxlength="16"
                                                placeholder="شماره ۱۶ رقمی کارت بانکی"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="shebaNumber" class="form-label text-end d-block">شماره شبا <span class="text-danger">*</span></label>
                                            <div style="display: flex; align-items: center;">
                                                <input type="text" class="form-control text-end" id="shebaNumber" name="shebaNumber"
                                                    pattern="[0-9]{24}" maxlength="24"
                                                    style="border-radius:4px 0 0 4px; border-right:0; text-align:left;"
                                                    placeholder="24 رقم شماره شبا را وارد کنید"
                                                    required>
                                                <span style="font-weight:bold; background:#eee; padding:6px 12px; border:1px solid #ccc; border-radius:0 4px 4px 0;">:IR</span>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="col-12 text-center mt-4">
                                        <button type="submit" name="submit" class="vs-btn style12">ذخیره اطلاعات</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- generated table for user to see submitted data of bank info -->
                    <?php include 'tbl_mp_bankinfo.php'; ?>

                    <!-- Salon Services Section -->
                    <div class="admin-card" id="service_salon">
                        <form action="mp_config_service.php" method="post" id="salonServiceForm" dir="rtl">
                            <div class="admin-card-header d-flex align-items-center justify-content-between">
                                <h5 class="mb-0">خدمات آرایشی سالن</h5>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-light btn-sm add-service-btn">افزودن خدمت جدید</button>
                                    <button type="submit" name="submit" class="btn btn-light btn-sm">
                                        <i class="fas fa-save"></i> ذخیره خدمات
                                    </button>
                                </div>
                            </div>
                            <div class="admin-card-body">
                                <input type="hidden" name="form" value="service">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="serviceTable">
                                        <thead>
                                            <tr>
                                                <th>نوع خدمت <span class="text-danger">*</span></th>
                                                <th>مدت زمان (دقیقه)</th>
                                                <th>قیمت (تومان)</th>
                                                <th>تخفیف </th>
                                                <th>قیمت نهایی (تومان)</th>
                                                <th>تاریخ شروع تخفیف</th>
                                                <th>تاریخ پایان تخفیف</th>
                                                <!-- <th>وضعیت</th> -->
                                                <th>عملیات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- یک ردیف نمونه که با JS قابلیت کلون شدن دارد -->
                                            <tr>
                                                <td>
                                                    <select class="form-select form-select-sm service-type" name="serviceType[]">
                                                        <option value="کوتاهی مو">کوتاهی مو</option>
                                                        <option value="رنگ مو">رنگ مو</option>
                                                        <option value="هایلایت">هایلایت</option>
                                                        <option value="کراتینه مو">کراتینه مو</option>
                                                        <option value="بوتاکس مو">بوتاکس مو</option>
                                                        <option value="صاف کردن مو">صاف کردن مو</option>
                                                        <option value="فر کردن مو">فر کردن مو</option>
                                                        <option value="شینیون">شینیون</option>
                                                        <option value="آرایش صورت">آرایش صورت</option>
                                                        <option value="آرایش عروس">آرایش عروس</option>
                                                        <option value="آرایش شب">آرایش شب</option>
                                                        <option value="آرایش روز">آرایش روز</option>
                                                        <option value="میکاپ">میکاپ</option>
                                                        <option value="کاشت مژه">کاشت مژه</option>
                                                        <option value="کاشت ناخن">کاشت ناخن</option>
                                                        <option value="مانیکور">مانیکور</option>
                                                        <option value="پدیکور">پدیکور</option>
                                                        <option value="ژل ناخن">ژل ناخن</option>
                                                        <option value="اکستنشن ناخن">اکستنشن ناخن</option>
                                                        <option value="ماساژ صورت">ماساژ صورت</option>
                                                        <option value="ماساژ بدن">ماساژ بدن</option>
                                                        <option value="ماساژ پا">ماساژ پا</option>
                                                        <option value="ماساژ سر">ماساژ سر</option>
                                                        <option value="اپیلاسیون">اپیلاسیون</option>
                                                        <option value="لیزر موهای زائد">لیزر موهای زائد</option>
                                                        <option value="بوتاکس">بوتاکس</option>
                                                        <option value="فیلر">فیلر</option>
                                                        <option value="میکرونیدلینگ">میکرونیدلینگ</option>
                                                        <option value="پاکسازی صورت">پاکسازی صورت</option>
                                                        <option value="ماسک صورت">ماسک صورت</option>
                                                        <option value="میکروبلیدینگ ابرو">میکروبلیدینگ ابرو</option>
                                                        <option value="تاتو ابرو">تاتو ابرو</option>
                                                        <option value="تاتو لب">تاتو لب</option>
                                                        <option value="تاتو چشم">تاتو چشم</option>
                                                        <option value="سایر">سایر</option>
                                                    </select>
                                                    <input type="text" class="form-control form-control-sm mt-1 custom-service-type" name="customService[]" style="display: none;" placeholder="نام خدمت را وارد کنید">
                                                </td>

                                                <td>
                                                    <input type="number" class="form-control form-control-sm service-duration" name="serviceDuration[]" maxlength="4" placeholder="مدت (دقیقه)">
                                                </td>

                                                <td>
                                                    <input type="number" class="form-control form-control-sm service-price" name="servicePrice[]" maxlength="10" placeholder="قیمت" onchange="updateService(this)">
                                                </td>

                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" class="form-control service-discount" name="serviceDiscount[]" value="0" min="0" max="100" placeholder="0" onchange="calculateFinalPrice(this)">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </td>

                                                <td>
                                                    <input type="number" class="form-control form-control-sm service-final-price" name="servicePriceFinal[]" placeholder="قیمت نهایی" readonly>
                                                </td>

                                                <td>
                                                    <label for="bookingDate" style="font-weight:600; color:#9a563a;"></label>
                                                    <div class="position-relative">
                                                        <input type="text" id="bookingDate" name="discountStartDate[]" class="form-control" placeholder="روز/ماه/سال" readonly style="margin-top:6px;text-align: center;font-size: 12px; width:100%; border-radius:8px; border:1px solid #e7e5e5; padding:8px 10px; padding-right:10px; background:#fff; cursor:pointer;">
                                                        <!-- calendar icon kept for appearance only (click opens modal) -->
                                                        <i id="bookingDateOpen" class="fal fa-calendar-alt" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); color:#9a563a; cursor:pointer;"></i>
                                                    </div>
                                                </td>

                                                <td>
                                                    <label for="bookingDateForm" style="font-weight:600; color:#9a563a;"></label>
                                                    <div class="position-relative">
                                                        <input type="text" id="bookingDateForm" name="discountEndDate[]" class="form-control" placeholder="روز/ماه/سال" readonly style="margin-top:6px;text-align: center;font-size: 12px; width:100%; border-radius:8px; border:1px solid #e7e5e5; padding:8px 10px; padding-right:10px; background:#fff; cursor:pointer;">
                                                        <!-- calendar icon kept for appearance only (click opens modal) -->
                                                        <i id="bookingDateFormOpen" class="fal fa-calendar-alt" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); color:#9a563a; cursor:pointer;"></i>
                                                    </div>
                                                </td>

                                                <!-- <td>
                                                    <select class="form-select form-select-sm service-status" name="serviceStatus[]">
                                                        <option value="فعال">فعال</option>
                                                        <option value="غیرفعال">غیرفعال</option>
                                                    </select>
                                                </td> -->

                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <button type="button" class="action-btn delete-btn btn btn-sm btn-outline-danger">حذف</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- generated table for user to see submitted data of salon service info -->
                    <?php include 'tbl_mp_service.php'; ?>



                    <!--  امکانات سالن بعد از جدول خدمات  -->
                    <div class="admin-card" id="option_salon">
                        <form action="mp_config_option.php" method="post" id="OptionSalonForm">
                            <div class=" admin-card-header">
                                <h5 class="mb-0">امکانات سالن</h5>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-light btn-sm btn-add-facility"> امکانات جدید </button>
                                    <!-- برای اطمینان، به submit دکمه value="1" دادم و در PHP از isset($_POST['submit']) استفاده می‌کنیم. -->
                                    <button type="submit" name="submit" value="1" class="btn btn-light btn-sm">
                                        <i class="fas fa-save"></i> ذخیره امکانات
                                    </button>
                                </div>
                            </div>

                            <!-- table for salon facilities -->
                            <div class="admin-card-body">
                                <table class="salon-facilities-table">
                                    <tr>
                                        <td>
                                            <label class="salon-facility-label">
                                                <input type="checkbox" name="salon_option[]" value="تهویه مطبوع (کولر/بخاری)" class="salon-facility-checkbox" id="facility-default-1">
                                                <span class="custom-checkbox"></span>
                                                <span class="facility-text" style="user-select: none;">تهویه مطبوع (کولر/بخاری)</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label class="salon-facility-label">
                                                <input type="checkbox" name="salon_option[]" value="صندلی کودک" class="salon-facility-checkbox" id="facility-default-2">
                                                <span class="custom-checkbox"></span>
                                                <span class="facility-text" style="user-select: none;">صندلی کودک</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label class="salon-facility-label">
                                                <input type="checkbox" name="salon_option[]" value="پارکینگ اختصاصی" class="salon-facility-checkbox" id="facility-default-3">
                                                <span class="custom-checkbox"></span>
                                                <span class="facility-text" style="user-select: none;">پارکینگ اختصاصی</span>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="salon-facility-label">
                                                <input type="checkbox" name="salon_option[]" value="دسترسی آسان برای معلولین" class="salon-facility-checkbox" id="facility-default-4">
                                                <span class="custom-checkbox"></span>
                                                <span class="facility-text" style="user-select: none;">دسترسی آسان برای معلولین</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label class="salon-facility-label">
                                                <input type="checkbox" name="salon_option[]" value="اینترنت رایگان" class="salon-facility-checkbox" id="facility-default-5">
                                                <span class="custom-checkbox"></span>
                                                <span class="facility-text" style="user-select: none;">اینترنت رایگان</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label class="salon-facility-label">
                                                <input type="checkbox" name="salon_option[]" value="سیستم صوتی" class="salon-facility-checkbox" id="facility-default-6">
                                                <span class="custom-checkbox"></span>
                                                <span class="facility-text" style="user-select: none;">سیستم صوتی</span>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="salon-facility-label">
                                                <input type="checkbox" name="salon_option[]" value="آبسردکن" class="salon-facility-checkbox" id="facility-default-7">
                                                <span class="custom-checkbox"></span>
                                                <span class="facility-text" style="user-select: none;">آبسردکن</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label class="salon-facility-label">
                                                <input type="checkbox" name="salon_option[]" value="اتاق انتظار مجهز" class="salon-facility-checkbox" id="facility-default-8">
                                                <span class="custom-checkbox"></span>
                                                <span class="facility-text" style="user-select: none;">اتاق انتظار مجهز</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label class="salon-facility-label">
                                                <input type="checkbox" name="salon_option[]" value="سرویس بهداشتی" class="salon-facility-checkbox" id="facility-default-9">
                                                <span class="custom-checkbox"></span>
                                                <span class="facility-text" style="user-select: none;">سرویس بهداشتی</span>
                                            </label>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </form>
                    </div>


                    <!-- generated table for user to see submitted data of option salon info -->
                    <?php include 'tbl_mp_option.php'; ?>

                    <!-- Working Hours Section -->
                    <div class="admin-card" id="working_hours">
                        <form action="mp_config_hours.php" method="post" id="salonHoursForm">
                            <div class="admin-card-header">
                                <h5 class="mb-0">ساعات کاری</h5>
                                <div class="d-flex gap-2">
                                    <!-- <button class="btn btn-light btn-sm" onclick="editWorkingHours()">ویرایش ساعات کاری</button> -->
                                    <button type="submit" name="submit" class="btn btn-light btn-sm">
                                        <i class="fas fa-save"></i> ذخیره ساعات کاری
                                    </button>
                                </div>
                            </div>
                            <div class="admin-card-body">
                                <div class="table-responsive">
                                    <table class="admin-table">
                                        <thead>
                                            <tr>
                                                <th>روز هفته</th>
                                                <th>ساعت شروع به کار سالن</th>
                                                <th>ساعت پایان کار سالن</th>
                                                <th>وضعیت</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><input type="text" name="day[]" value="شنبه" readonly></td>
                                                <td><input type="time" name="open_hour[]" id="saturday_open" class="form-control form-control-sm start-time" value="09:00"></td>
                                                <td><input type="time" name="close_hour[]" id="saturday_close" class="form-control form-control-sm end-time" value="21:00"></td>
                                                <td>
                                                    <select class="form-select form-select-sm day-status" name="status[]">
                                                        <option value="فعال">فعال</option>
                                                        <option value="غیرفعال">غیرفعال</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><input type="text" name="day[]" value="یکشنبه" readonly></td>
                                                <td><input type="time" name="open_hour[]" id="sunday_open" class="form-control form-control-sm start-time" value="09:00"></td>
                                                <td><input type="time" name="close_hour[]" id="sunday_close" class="form-control form-control-sm end-time" value="21:00"></td>
                                                <td>
                                                    <select class="form-select form-select-sm day-status" name="status[]">
                                                        <option value="فعال">فعال</option>
                                                        <option value="غیرفعال">غیرفعال</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><input type="text" name="day[]" value="دوشنبه" readonly></td>
                                                <td><input type="time" name="open_hour[]" id="monday_open" class="form-control form-control-sm start-time" value="09:00"></td>
                                                <td><input type="time" name="close_hour[]" id="monday_close" class="form-control form-control-sm end-time" value="21:00"></td>
                                                <td>
                                                    <select class="form-select form-select-sm day-status" name="status[]">
                                                        <option value="فعال">فعال</option>
                                                        <option value="غیرفعال">غیرفعال</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><input type="text" name="day[]" value="سه شنبه" readonly></td>
                                                <td><input type="time" name="open_hour[]" id="tuesday_open" class="form-control form-control-sm start-time" value="09:00"></td>
                                                <td><input type="time" name="close_hour[]" id="tuesday_close" class="form-control form-control-sm end-time" value="21:00"></td>
                                                <td>
                                                    <select class="form-select form-select-sm day-status" name="status[]">
                                                        <option value="فعال">فعال</option>
                                                        <option value="غیرفعال">غیرفعال</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><input type="text" name="day[]" value="چهار شنبه" readonly></td>
                                                <td><input type="time" name="open_hour[]" id="wednesday_open" class="form-control form-control-sm start-time" value="09:00"></td>
                                                <td><input type="time" name="close_hour[]" id="wednesday_close" class="form-control form-control-sm end-time" value="21:00"></td>
                                                <td>
                                                    <select class="form-select form-select-sm day-status" name="status[]">
                                                        <option value="فعال">فعال</option>
                                                        <option value="غیرفعال">غیرفعال</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><input type="text" name="day[]" value="پنجشنبه" readonly></td>
                                                <td><input type="time" name="open_hour[]" id="thursday_open" class="form-control form-control-sm start-time" value="09:00"></td>
                                                <td><input type="time" name="close_hour[]" id="thursday_close" class="form-control form-control-sm end-time" value="21:00"></td>
                                                <td>
                                                    <select class="form-select form-select-sm day-status" name="status[]">
                                                        <option value="فعال">فعال</option>
                                                        <option value="غیرفعال">غیرفعال</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><input type="text" name="day[]" value="جمعه" readonly></td>
                                                <td><input type="time" name="open_hour[]" id="friday_open" class="form-control form-control-sm start-time" value="09:00"></td>
                                                <td><input type="time" name="close_hour[]" id="friday_close" class="form-control form-control-sm end-time" value="21:00"></td>
                                                <td>
                                                    <select class="form-select form-select-sm day-status" name="status[]">
                                                        <option value="فعال">فعال</option>
                                                        <option value="غیرفعال">غیرفعال</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- generated table for user to see submitted data of salon hours info -->
                    <?php include 'tbl_mp_hours.php'; ?>


                    <!-- Portfolio Section -->
                    <div class="admin-card" id="portfolioPics">
                        <form action="mp_config_portfolio.php" method="post" id="salonPortfolioPics" enctype="multipart/form-data">
                            <div class="admin-card-header">
                                <h5 class="mb-0">نمونه کارها</h5>
                                <div class="d-flex gap-2">
                                    <!-- <button type="button" class="btn btn-light btn-sm" onclick="addPortfolioItem()">افزودن نمونه کار</button> -->
                                    <button type="submit" name="submit" class="btn btn-light btn-sm">
                                        <i class="fas fa-save"></i> ذخیره نمونه کار
                                    </button>
                                </div>
                            </div>
                            <div class="admin-card-body">
                                <p>این فرم در یک سطر باشد، بعدش اینو پاک کن. توضیحات برای نوع و حجم فایل</p>
                                <label for="portfolio_pic">نمونه کار خود را بارگذاری کنید:</label>
                                <input type="file" name="portfolio_pic" id="portfolio_pic"><br><br>
                                <label for="portfolio_title">توضیحات نمونه کار:</label>
                                <input type="text" name="portfolio_title" id="portfolio_title">
                            </div>
                        </form>
                    </div>

                    <!-- generated table for user to see submitted data of salon portfolio pics -->
                    <?php include 'tbl_mp_portfolio.php'; ?>


                    <!-- Salon Images Section -->
                    <div class="admin-card" id="salon_images">
                        <form action="mp_config_salonpics.php" method="post" id="salonPics" enctype="multipart/form-data">
                            <div class="admin-card-header">
                                <h5 class="mb-0">تصاویر سالن</h5>
                                <div class="d-flex gap-2">
                                    <!-- <button type="button" class="btn btn-light btn-sm" onclick="addPortfolioItem()">افزودن نمونه کار</button> -->
                                    <button type="submit" name="submit" class="btn btn-light btn-sm">
                                        <i class="fas fa-save"></i> ذخیره تصاویر سالن
                                    </button>
                                </div>
                            </div>
                            <div class="admin-card-body">
                                <p>این فرم در یک سطر باشد، بعدش اینو پاک کن. توضیحات برای نوع و حجم فایل</p>
                                <label for="salon_pic">عکس سالن را بارگذاری کنید:</label>
                                <input type="file" name="salon_pic" id="salon_pic"><br><br>
                                <label for="salonTitle">توضیحات عکس سالن</label>
                                <input type="text" name="salonTitle" id="salonTitle">
                            </div>
                        </form>
                    </div>

                    <!-- generated table for user to see submitted data of salon pics -->
                    <?php include 'tbl_mp_salonpics.php'; ?>


                    <script>
                        (function() {
                            // helper: محاسبه قیمت نهایی و گذاشتن در فیلد مربوطه
                            function calculateFinalForRow(row) {
                                const priceInput = row.querySelector('.service-price');
                                const discountInput = row.querySelector('.service-discount');
                                const finalInput = row.querySelector('.service-final-price');

                                const price = parseFloat(priceInput.value) || 0;
                                let discount = parseFloat(discountInput.value);
                                if (isNaN(discount)) discount = 0;
                                if (discount < 0) discount = 0;
                                if (discount > 100) discount = 100;

                                const finalPrice = Math.round(price * (100 - discount) / 100);
                                finalInput.value = finalPrice;
                            }

                            // updateService and calculateFinalPrice referenced in markup
                            window.updateService = function(elem) {
                                const row = elem.closest('tr');
                                if (!row) return;
                                // Update final
                                calculateFinalForRow(row);
                                // handle service-type special case
                                const select = row.querySelector('.service-type');
                                const customInput = row.querySelector('.custom-service-type');
                                if (select && customInput) {
                                    if (select.value === 'سایر') {
                                        customInput.style.display = 'block';
                                        customInput.required = true;
                                    } else {
                                        customInput.style.display = 'none';
                                        customInput.required = false;
                                        customInput.value = '';
                                    }
                                }
                            };

                            window.calculateFinalPrice = function(elem) {
                                const row = elem.closest('tr');
                                if (!row) return;
                                calculateFinalForRow(row);
                            };

                            // add new row (clone first row and clear values)
                            const addBtn = document.querySelector('.add-service-btn');
                            const tableBody = document.querySelector('#serviceTable tbody');

                            if (addBtn && tableBody) {
                                addBtn.addEventListener('click', function() {
                                    // clone the last row (or first) and clear its inputs
                                    const sampleRow = tableBody.querySelector('tr');
                                    if (!sampleRow) return;
                                    const newRow = sampleRow.cloneNode(true);

                                    // clear inputs in newRow
                                    newRow.querySelectorAll('input').forEach(function(inp) {
                                        if (inp.type === 'number' || inp.type === 'text' || inp.type === 'date') inp.value = '';
                                        else inp.checked = false;
                                    });
                                    newRow.querySelectorAll('select').forEach(function(sel) {
                                        sel.selectedIndex = 0;
                                    });
                                    // hide custom field
                                    const custom = newRow.querySelector('.custom-service-type');
                                    if (custom) {
                                        custom.style.display = 'none';
                                        custom.value = '';
                                        custom.required = false;
                                    }
                                    // attach event listeners for new row (delete, select change, input change)
                                    attachRowListeners(newRow);
                                    tableBody.appendChild(newRow);
                                });
                            }

                            function attachRowListeners(row) {
                                const delBtn = row.querySelector('.delete-btn');
                                if (delBtn) {
                                    delBtn.addEventListener('click', function(e) {
                                        e.preventDefault();
                                        // if only one row left, just clear it instead of removing (optional)
                                        const rows = tableBody.querySelectorAll('tr');
                                        if (rows.length <= 1) {
                                            row.querySelectorAll('input').forEach(function(inp) {
                                                if (inp.type === 'number' || inp.type === 'text' || inp.type === 'date') inp.value = '';
                                                else inp.checked = false;
                                            });
                                            row.querySelectorAll('select').forEach(function(sel) {
                                                sel.selectedIndex = 0;
                                            });
                                            const custom = row.querySelector('.custom-service-type');
                                            if (custom) {
                                                custom.style.display = 'none';
                                                custom.value = '';
                                            }
                                            return;
                                        }
                                        row.remove();
                                    });
                                }

                                const select = row.querySelector('.service-type');
                                if (select) {
                                    select.addEventListener('change', function() {
                                        const custom = row.querySelector('.custom-service-type');
                                        if (this.value === 'سایر') {
                                            custom.style.display = 'block';
                                            custom.required = true;
                                        } else {
                                            custom.style.display = 'none';
                                            custom.required = false;
                                            if (custom) custom.value = '';
                                        }
                                    });
                                }

                                // price/discount inputs
                                const price = row.querySelector('.service-price');
                                const discount = row.querySelector('.service-discount');
                                const anyInput = row.querySelectorAll('.service-price, .service-discount');
                                anyInput.forEach(function(inp) {
                                    inp.addEventListener('input', function() {
                                        calculateFinalForRow(row);
                                    });
                                });
                            }

                            // attach listeners for existing rows on page load
                            document.querySelectorAll('#serviceTable tbody tr').forEach(function(r) {
                                attachRowListeners(r);
                            });

                        })();
                    </script>


                    <script>
                        function deleteExistingService(button) {
                            if (!confirm('آیا از حذف این خدمت اطمینان دارید؟')) return;

                            const tr = button.closest('tr');
                            const serviceId = tr.dataset.id;

                            fetch('tbl_salon_service_delete.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                                    },
                                    body: new URLSearchParams({
                                        service_id: serviceId
                                    })
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        tr.remove();
                                    } else {
                                        alert(data.error || 'خطا در حذف خدمت');
                                    }
                                })
                                .catch(() => {
                                    alert('خطا در ارتباط با سرور');
                                });
                        }
                    </script>


                    <script>
                        // تابع ذخیره امکانات سالن
                        function saveFacilities() {
                            const facilities = [];

                            // جمع‌آوری امکانات فعال
                            for (let i = 1; i <= 12; i++) {
                                const checkbox = document.getElementById(`facility${i}`);
                                if (checkbox) {
                                    if (checkbox.checked) {
                                        const label = checkbox.nextElementSibling.textContent;
                                        facilities.push(label);
                                    }
                                }
                            }

                            // ذخیره در localStorage
                            localStorage.setItem('salonFacilities', JSON.stringify(facilities));
                            alert('امکانات سالن با موفقیت ذخیره شدند.');
                            saveFacilities();
                        }
                    </script>

                    <!-- Financial Report -->
                    <div class="admin-card" id="financial_report">
                        <div class="admin-card-header">
                            <h5 class="mb-0">گزارش مالی</h5>
                            <div class="d-flex gap-2">
                                <select class="form-select form-select-sm w-auto" id="reportPeriod">
                                    <option value="today">امروز</option>
                                    <option value="weekly">هفته جاری</option>
                                    <option value="current_month">این ماه</option>
                                    <option value="last_month">ماه گذشته</option>
                                    <option value="last_3_months">سه ماه گذشته</option>
                                    <option value="current_year">سال جاری</option>
                                </select>
                                <button class="btn btn-light btn-sm" id="downloadReport" onclick="downloadFinancialReport()">
                                    <i class="fas fa-download"></i> دریافت گزارش
                                </button>
                            </div>
                        </div>
                        <div class="admin-card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="report-card">
                                        <h6>آمار نوبت‌ها</h6>
                                        <canvas id="appointmentsChart"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="report-card">
                                        <h6>گزارش مالی</h6>
                                        <canvas id="financialChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="admin-table">
                                    <thead>
                                        <tr>
                                            <th>تاریخ</th>
                                            <th>شماره فاکتور</th>
                                            <th>نام مشتری</th>
                                            <th>شماره تماس</th>
                                            <th>خدمات</th>
                                            <th>مبلغ</th>
                                            <th>وضعیت پرداخت</th>
                                            <th>عملیات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>۱۴۰۳/۰۱/۱۵</td>
                                            <td>INV-001</td>
                                            <td>علی محمدی</td>
                                            <td>۰۹۱۲۳۴۵۶۷۸۹</td>
                                            <td>کوتاهی مو</td>
                                            <td>۱۵۰,۰۰۰ تومان</td>
                                            <td><span class="status-badge status-confirmed">پرداخت شده</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-info" title="جزئیات">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-success send-receipt-btn" title="ارسال رسید">
                                                    <i class="fas fa-receipt"></i>
                                                </button>
                                                <div class="share-menu" style="display:none;background-color: rgb(209, 206, 200); position:absolute; z-index:999;">
                                                    <a href="#" class="share-whatsapp wa-link" target="_blank"><i class="fab fa-whatsapp"></i> <span>واتساپ</span></a>
                                                    |
                                                    <a href="#" class="share-telegram tg-link" target="_blank"><i class="fab fa-telegram-plane"></i> <span>تلگرام</span></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>۱۴۰۳/۰۱/۱۴</td>
                                            <td>INV-002</td>
                                            <td>مریم احمدی</td>
                                            <td>۰۹۸۷۶۵۴۳۲۱۰</td>
                                            <td>آرایش صورت</td>
                                            <td>۲۵۰,۰۰۰ تومان</td>
                                            <td><span class="status-badge status-pending">پرداخت در محل</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-info" title="جزئیات">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-success send-receipt-btn" title="ارسال رسید">
                                                    <i class="fas fa-receipt"></i>
                                                </button>
                                                <div class="share-menu" style="display:none; position:absolute; z-index:999;">
                                                    <a href="#" class="share-whatsapp wa-link" target="_blank"><i class="fab fa-whatsapp"></i> <span>واتساپ</span></a>
                                                    |
                                                    <a href="#" class="share-telegram tg-link" target="_blank"><i class="fab fa-telegram-plane"></i> <span>تلگرام</span></a>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Appointments -->
                    <div class="admin-card" id="my_appointments">
                        <div class="admin-card-header">
                            <h5 class="mb-0">نوبت‌های در انتظار </h5>
                            <!-- <button class="btn btn-light btn-sm">افزودن نوبت جدید</button> -->
                        </div>
                        <div class="admin-card-body">
                            <div class="table-responsive">
                                <table class="admin-table">
                                    <thead>
                                        <tr>
                                            <th>نام مشتری</th>
                                            <th>تاریخ</th>
                                            <th>ساعت</th>
                                            <th>خدمات</th>
                                            <th>وضعیت</th>
                                            <th>عملیات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>فرید دوکی </td>
                                            <td>۱۴۰۳/۰۱/۱۵</td>
                                            <td>۱۴:۳۰</td>
                                            <td>کوتاهی مو</td>
                                            <td><span class="status-badge status-confirmed">لغو نمود </span></td>
                                            <td>
                                                <button class="action-btn edit-btn" onclick="openSendMessageModal(this)">ارسال پیغام به مشتری</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>ربی </td>
                                            <td>۱۴۰۳/۰۱/۱۵</td>
                                            <td>۱۶:۰۰</td>
                                            <td>ماساژ هپی اندینگ </td>
                                            <td><span class="status-badge status-pending">در انتظار </span></td>
                                            <td>
                                                <button class="action-btn edit-btn" onclick="openSendMessageModal(this)">ارسال پیغام به مشتری</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Advanced Appointment Management -->
                    <div class="admin-card" id="appointment-management">
                        <div class="admin-card-header">
                            <h5 class="mb-0">تاریخچه نوبت‌ها</h5>
                            <div class="d-flex gap-2">
                                <select class="form-select form-select-sm w-auto">
                                    <option value="all">همه نوبت‌ها</option>
                                    <option value="completed">انجام شده</option>
                                    <option value="cancelled">لغو شده</option>
                                    <option value="pending">در انتظار</option>
                                </select>
                                <input type="text" class="form-control form-control-sm" placeholder="جستجو...">
                            </div>
                        </div>
                        <div class="admin-card-body">
                            <div class="table-responsive">
                                <table class="admin-table">
                                    <thead>
                                        <tr>
                                            <th>تاریخ</th>
                                            <th>ساعت</th>
                                            <th>خدمات</th>
                                            <th>مشتری</th>
                                            <th>وضعیت</th>


                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>۱۴۰۳/۰۱/۱۵</td>
                                            <td>۱۴:۳۰</td>
                                            <td>کوتاهی مو</td>
                                            <td>کچل مو فرفری </td>
                                            <td><span class="status-badge status-completed">انجام شده</span></td>

                                            <!-- <td>
                                                <button class="action-btn edit-btn" title="ثبت نظر">
                                                    <i class="fas fa-comment"></i>
                                                </button>
                                                <button class="action-btn delete-btn" title="لغو نوبت">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </td> -->
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Profile Section -->
                    <div class="admin-card" id="settings">
                        <div class="admin-card-header">
                            <h5 class="mb-0">اعلان ها </h5>
                            <!-- <button class="btn btn-light btn-sm">ویرایش پروفایل</button> -->
                        </div>
                        <div class="admin-card-body">
                            <div class="row">

                                <div class="col-md-8">
                                    <div dir=rtl>
                                        <h6 class="mb-3">تنظیمات اعلان‌ها</h6>
                                        <div class="d-flex flex-column gap-1">
                                            <div class="form-check form-switch mb-2 d-flex flex-row " style="gap:0;">
                                                <input class="form-check-input" type="checkbox" id="emailNotif">
                                                <label class="form-check-label me-2 mb-0" for="emailNotif">اعلان‌های ایمیلی</label>
                                            </div>
                                            <div class="form-check form-switch mb-2 d-flex flex-row align-items-center" style="gap:0;">
                                                <input class="form-check-input" type="checkbox" id="smsNotif" checked>
                                                <label class="form-check-label me-2 mb-0" for="smsNotif">اعلان‌های پیامکی</label>
                                            </div>
                                            <div class="form-check form-switch mb-2 d-flex flex-row align-items-center" style="gap:0;">
                                                <input class="form-check-input" type="checkbox" id="appNotif" checked>
                                                <label class="form-check-label me-2 mb-0" for="appNotif">اعلان‌های درون برنامه‌ای</label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="profile-stats">
                                        <p><i class="fas fa-calendar-check"></i> تاریخ عضویت: ۱۴۰۲/۱۰/۱۵</p>

                                    </div>
                                </div>

                            </div>

                        </div>




                        <!-- Support Center -->
                        <div class="admin-card" id="support_center">
                            <div class="admin-card-header">
                                <h5 class="mb-0">پشتیبانی</h5>
                            </div>
                            <div class="admin-card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="support-options">
                                            <button class="btn btn-primary w-100 mb-3">
                                                <i class="fas fa-comments"></i> چت آنلاین
                                            </button>
                                            <button class="btn btn-outline-primary w-100 mb-3" onclick="window.location.href='manager_tickets.php'">
                                                <i class="fas fa-ticket-alt"></i> ارسال تیکت
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="faq-section">
                                            <h6 class="mb-3">سوالات متداول</h6>
                                            <div class="accordion" id="faqAccordion">
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                                            چگونه نوبت خود را لغو کنم؟
                                                        </button>
                                                    </h2>
                                                    <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                                        <div class="accordion-body">
                                                            برای لغو نوبت، به بخش نوبت‌های من مراجعه کرده و روی دکمه لغو کلیک کنید.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                                            چگونه می‌توانم اطلاعات پروفایل خود را ویرایش کنم؟
                                                        </button>
                                                    </h2>
                                                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                                        <div class="accordion-body">
                                                            برای ویرایش اطلاعات پروفایل، به بخش پروفایل کاربری مراجعه کرده و روی دکمه ویرایش کلیک کنید.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                                            چگونه می‌توانم رمز عبور خود را تغییر دهم؟
                                                        </button>
                                                    </h2>
                                                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                                        <div class="accordion-body">
                                                            برای تغییر رمز عبور، به بخش تنظیمات امنیتی مراجعه کرده و گزینه تغییر رمز عبور را انتخاب کنید.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                                            چگونه می‌توانم اعلان‌ها را فعال یا غیرفعال کنم؟
                                                        </button>
                                                    </h2>
                                                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                                        <div class="accordion-body">
                                                            برای مدیریت اعلان‌ها، به بخش تنظیمات اعلان‌ها مراجعه کرده و گزینه‌های مورد نظر را فعال یا غیرفعال کنید.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                                            چگونه می‌توانم با پشتیبانی تماس بگیرم؟
                                                        </button>
                                                    </h2>
                                                    <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                                        <div class="accordion-body">
                                                            برای تماس با پشتیبانی، از بخش پشتیبانی - ارسال تیکت استفاده کنید یا از طریق چت آنلاین پیام ارسال نمایید.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Advanced Settings -->
                        <div class="admin-card" id="advanced-settings">
                            <div class="admin-card-header">
                                <h5 class="mb-0">تنظیمات پیشرفته</h5>
                            </div>
                            <div class="admin-card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="settings-section">
                                            <h6 class="mb-3">تنظیمات حریم خصوصی</h6>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" id="profileVisibility" checked>
                                                <label class="form-check-label" for="profileVisibility">نمایش پروفایل برای دیگران</label>
                                            </div>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" id="activityVisibility" checked>
                                                <label class="form-check-label" for="activityVisibility">نمایش فعالیت‌های اخیر</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="settings-section">
                                            <h6 class="mb-3">تنظیمات امنیتی</h6>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" id="twoFactorAuth">
                                                <label class="form-check-label" for="twoFactorAuth">احراز هویت دو مرحله‌ای</label>
                                            </div>
                                            <button class="btn btn-outline-primary btn-sm" id="changePasswordBtn" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                                <i class="fas fa-key"></i> تغییر رمز عبور
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Content -->
                        <div class="footer-content mt-4">
                            <div class="row gx-60 justify-content-between">
                                <div class="col-lg-4 text-center text-lg-start">
                                    <div class="widget">
                                        <div class="mb-3">
                                            <img src="assets/img/logo-4.svg" alt="logo">
                                        </div>
                                        <p class="" style="text-align: justify; color: #050505;"> جهت ارایه ی هرگونه نظرات پیشنهادات و انتقادات صمیمانه پذیرای شما هستیم . برای دسترسی با ما در تماس باشید</p>
                                        <a href="contact.html" class="vs-btn style12">تماس با ما</a>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="widget">
                                        <p style="text-align: center;">تیم پشتیبانی آنلاین</p>
                                        <div class="sidebar-gallery column-4">
                                            <div class="gallery-thumb">
                                                <img src="assets/img/team/reviews1.jpg" alt="Gallery Image" class="w-100">
                                            </div>
                                            <div class="gallery-thumb">
                                                <img src="assets/img/team/user1-128x128.jpg" alt="Gallery Image" class="w-100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="widget footer-widget">
                                        <div class="follow-box">
                                            <h4 class="mb-1">ما را دنبال کنید</h4>
                                            <p class="fs-xs mb-15">برای دریافت آخرین به‌روزرسانی‌ها ما را دنبال کنید</p>
                                            <ul class="follow-social mb-1">
                                                <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                                <li><a href="#"><i class="fab fa-skype"></i></a></li>
                                                <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                                <li><a href="#"><i class="fab fa-youtube"></i></a></li>
                                            </ul>
                                            <p class="fs-md font-title mt-10 mb-0"><a href="mailto:info@example.com" class="text-inherit">info@example.com</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="copyright-wrap mt-4">
                                <div class="container">
                                    <p>
                                        کلیه حقوق این وب‌سایت متعلق به سیستم رزرواسیون آنلاین می‌باشد &copy; 1404
                                        <span class="separator">|</span>
                                        <a href="#">قوانین و مقررات</a>
                                        <span class="separator">|</span>
                                        <a href="#">حریم خصوصی</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Personal Information Form Section -->
    <section class="vs-profile-wrapper py-5">

    </section>

    <!--==============================
			Footer Area
	==============================-->
    <!-- <footer class="footer-wrapper bg-secondary footer-layout5" data-bg-src="assets/img/bg/footer-bg-6-1.jpg">
        ... old footer content ...
    </footer> -->
    <!-- Scroll To Top -->
    <a href="#" class="scrollToTop scroll-btn"><i class="far fa-arrow-up"></i></a>

    <!-- Floating Chat Box -->
    <div class="floating-chat-box" id="floatingChatBox">
        <div class="chat-header">
            <div class="d-flex align-items-center">
                <img src="assets/img/team/user1-128x128.jpg" alt="Support" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                <div>
                    <h6 class="mb-0">پشتیبانی آنلاین</h6>
                    <small class="text-success"><i class="fas fa-circle"></i> آنلاین</small>
                </div>
            </div>
            <div class="chat-actions">
                <button class="btn btn-sm btn-light" id="minimizeChat">
                    <i class="fas fa-minus"></i>
                </button>
                <button class="btn btn-sm btn-light" id="closeChat">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="chat-body">
            <div class="chat-messages">
                <div class="message support">
                    <div class="message-content">
                        سلام! چطور می‌تونم کمکتون کنم؟
                    </div>
                    <small class="message-time">۱۰:۳۰</small>
                </div>
            </div>
        </div>
        <div class="chat-footer">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="پیام خود را بنویسید...">
                <button class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Chat Toggle Button -->
    <button class="chat-toggle-btn" id="chatToggleBtn">
        <i class="fas fa-comments"></i>
        <span class="notification-badge">1</span>
    </button>

    <!-- Add Mobile Menu Toggle Button -->
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Add JavaScript for Mobile Menu -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const adminSidebar = document.querySelector('.admin-sidebar');

            mobileMenuToggle.addEventListener('click', function() {
                adminSidebar.classList.toggle('active');
            });

            // Close sidebar when clicking outside
            document.addEventListener('click', function(event) {
                if (!adminSidebar.contains(event.target) && !mobileMenuToggle.contains(event.target)) {
                    adminSidebar.classList.remove('active');
                }
            });
        });
    </script>

    <!--********************************
			Code End  Here 
	******************************** -->

    <!--==============================
        All Js File
    ============================== -->
    <!-- Jquery -->
    <script src="assets/js/vendor/jquery-3.6.0.min.js"></script>
    <!-- jQuery UI -->
    <script src="assets/js/jquery-ui.min.js"></script>
    <!-- Slick Slider -->
    <script src="assets/js/slick.min.js"></script>
    <!-- <script src="assets/js/app.min.js"></script> -->
    <!-- Layerslider -->
    <script src="assets/js/layerslider.utils.js"></script>
    <script src="assets/js/layerslider.transitions.js"></script>
    <script src="assets/js/layerslider.kreaturamedia.jquery.js"></script>
    <!-- Bootstrap -->
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <!-- Bootstrap JS (Popper and Bootstrap JS) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.0.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>
    <!-- Parallax Scroll -->
    <script src="assets/js/universal-parallax.min.js"></script>
    <!-- Wow.js Animation -->
    <script src="assets/js/wow.min.js"></script>
    <!-- jQuery Datepicker -->
    <script src="assets/js/jquery.datetimepicker.min.js"></script>
    <!-- Magnific Popup -->
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <!-- Isotope Filter -->
    <script src="assets/js/imagesloaded.pkgd.min.js"></script>
    <script src="assets/js/isotope.pkgd.min.js"></script>
    <!-- Main Js File -->
    <script src="assets/js/main.js"></script>
    <!-- JavaScript Files -->

    <script src="assets/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/persian-date@1.1.0/dist/persian-date.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/md.bootstrappersiandatetimepicker@3.6.0/dist/jquery.md.bootstrap.datetimepicker.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        src = "assets/js/profile1.js"
    </script>
    <script src="assets/js/bootstrap.min.js"></script>
    <div id="modalContainer"></div>

    <!-- Add this hidden file input -->
    <input type="file" id="profileImageInput" accept="image/*" style="display: none;">

    <!-- Add JavaScript for Salon Services -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Salon Services Management
            const serviceTable = document.querySelector('#service-salon .admin-table tbody');
            const addServiceBtn = document.querySelector('#service-salon .admin-card-header .btn');

            // Add Service Button Click Handler
            addServiceBtn.addEventListener('click', () => {
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td>
                        <select class="form-select form-select-sm service-type" onchange="handleServiceTypeChange(this)">
                            <option value="کوتاهی مو">کوتاهی مو</option>
                            <option value="رنگ مو">رنگ مو</option>
                            <option value="هایلایت">هایلایت</option>
                            <option value="کراتینه مو">کراتینه مو</option>
                            <option value="بوتاکس مو">بوتاکس مو</option>
                            <option value="صاف کردن مو">صاف کردن مو</option>
                            <option value="فر کردن مو">فر کردن مو</option>
                            <option value="شینیون">شینیون</option>
                            <option value="آرایش صورت">آرایش صورت</option>
                            <option value="آرایش عروس">آرایش عروس</option>
                            <option value="آرایش شب">آرایش شب</option>
                            <option value="آرایش روز">آرایش روز</option>
                            <option value="میکاپ">میکاپ</option>
                            <option value="کاشت مژه">کاشت مژه</option>
                            <option value="کاشت ناخن">کاشت ناخن</option>
                            <option value="مانیکور">مانیکور</option>
                            <option value="پدیکور">پدیکور</option>
                            <option value="ژل ناخن">ژل ناخن</option>
                            <option value="اکستنشن ناخن">اکستنشن ناخن</option>
                            <option value="ماساژ صورت">ماساژ صورت</option>
                            <option value="ماساژ بدن">ماساژ بدن</option>
                            <option value="ماساژ پا">ماساژ پا</option>
                            <option value="ماساژ سر">ماساژ سر</option>
                            <option value="اپیلاسیون">اپیلاسیون</option>
                            <option value="لیزر موهای زائد">لیزر موهای زائد</option>
                            <option value="بوتاکس">بوتاکس</option>
                            <option value="فیلر">فیلر</option>
                            <option value="میکرونیدلینگ">میکرونیدلینگ</option>
                            <option value="پاکسازی صورت">پاکسازی صورت</option>
                            <option value="ماسک صورت">ماسک صورت</option>
                            <option value="میکروبلیدینگ ابرو">میکروبلیدینگ ابرو</option>
                            <option value="تاتو ابرو">تاتو ابرو</option>
                            <option value="تاتو لب">تاتو لب</option>
                            <option value="تاتو چشم">تاتو چشم</option>
                            <option value="سایر">سایر خدمات</option>
                        </select>
                        <input type="text" class="form-control form-control-sm mt-1 custom-service-type" style="display: none;" placeholder="نام خدمت را در اینجا وارد کنید">
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="number" class="form-control service-duration" value="" placeholder="مدت زمان" onchange="updateService(this)">
                            <span class="input-group-text">دقیقه</span>
                        </div>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="number" class="form-control service-price" value="" placeholder="قیمت" onchange="updateService(this)">
                        </div>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="number" class="form-control service-discount" value="0" min="0" max="100" placeholder="0" onchange="calculateFinalPrice(this)">
                            <span class="input-group-text">%</span>
                        </div>
                    </td>
                    <td>
                        <select class="form-select form-select-sm discount-type" onchange="handleDiscountTypeChange(this)">
                            <option value="daily" selected>روزانه</option>
                            <option value="always">همیشه</option>
                            <option value="weekly">هفتگی</option>
                        </select>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="number" class="form-control service-final-price" value="" placeholder="قیمت نهایی" readonly>
                        </div>
                    </td>
                    <td>
                        <select class="form-select form-select-sm service-status" onchange="updateService(this)">
                            <option value="فعال">فعال</option>
                            <option value="غیرفعال">غیرفعال</option>
                        </select>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <button class="action-btn save-btn" onclick="saveNewService(this)">ذخیره</button>
                            <button class="action-btn delete-btn" onclick="deleteService(this)">حذف</button>
                        </div>
                    </td>
                `;
                serviceTable.insertBefore(newRow, serviceTable.firstChild);

                // Focus on the first input
                newRow.querySelector('.service-type').focus();
            });

            // Handle Service Type Change
            window.handleServiceTypeChange = function(select) {
                const customInput = select.parentElement.querySelector('.custom-service-type');
                if (select.value === 'سایر') {
                    select.style.display = 'none';
                    customInput.style.display = 'block';
                    customInput.focus();
                } else {
                    select.style.display = 'block';
                    customInput.style.display = 'none';
                    customInput.value = '';
                }
                updateService(select);
            };

            // Save New Service Function
            window.saveNewService = function(button) {
                const row = button.closest('tr');
                const typeSelect = row.querySelector('.service-type');
                const customInput = row.querySelector('.custom-service-type');
                const durationInput = row.querySelector('.service-duration');
                const priceInput = row.querySelector('.service-price');
                const statusSelect = row.querySelector('.service-status');

                // Validate inputs
                if (typeSelect.value === 'سایر' && !customInput.value) {
                    alert('لطفا نام خدمت را وارد کنید');
                    customInput.focus();
                    return;
                } else if (typeSelect.value === '') {
                    alert('لطفا نوع خدمت را انتخاب کنید');
                    typeSelect.focus();
                    return;
                }
                if (!durationInput.value) {
                    alert('لطفا مدت زمان را وارد کنید');
                    durationInput.focus();
                    return;
                }
                if (!priceInput.value) {
                    alert('لطفا قیمت را وارد کنید');
                    priceInput.focus();
                    return;
                }

                // Replace save button with edit button
                const actionsCell = row.querySelector('td:last-child');
                actionsCell.innerHTML = `
                    <div class="d-flex gap-1">
                        <button class="action-btn edit-btn" onclick="editService(this)">ویرایش</button>
                        <button class="action-btn delete-btn" onclick="deleteService(this)">حذف</button>
                    </div>
                `;
            };

            // Edit Service Function
            window.editService = function(button) {
                const row = button.closest('tr');
                const typeSelect = row.querySelector('.service-type');
                const customInput = row.querySelector('.custom-service-type');
                const durationInput = row.querySelector('.service-duration');
                const priceInput = row.querySelector('.service-price');
                const statusSelect = row.querySelector('.service-status');

                // Enable editing
                typeSelect.disabled = false;
                customInput.disabled = false;
                durationInput.disabled = false;
                priceInput.disabled = false;
                statusSelect.disabled = false;

                // If it's a custom service, show the input
                if (typeSelect.value === 'سایر') {
                    typeSelect.style.display = 'none';
                    customInput.style.display = 'block';
                }

                // Change edit button to save button
                const actionsCell = row.querySelector('td:last-child');
                actionsCell.innerHTML = `
                    <div class="d-flex gap-1">
                        <button class="action-btn save-btn" onclick="saveEdit(this)">ذخیره</button>
                        <button class="action-btn delete-btn" onclick="deleteService(this)">حذف</button>
                    </div>
                `;
            };

            // Save Edit Function
            window.saveEdit = function(button) {
                const row = button.closest('tr');
                const typeSelect = row.querySelector('.service-type');
                const customInput = row.querySelector('.custom-service-type');
                const durationInput = row.querySelector('.service-duration');
                const priceInput = row.querySelector('.service-price');
                const statusSelect = row.querySelector('.service-status');

                // Validate inputs
                if (typeSelect.value === 'سایر' && !customInput.value) {
                    alert('لطفا نام خدمت را وارد کنید');
                    customInput.focus();
                    return;
                } else if (typeSelect.value === '') {
                    alert('لطفا نوع خدمت را انتخاب کنید');
                    typeSelect.focus();
                    return;
                }
                if (!durationInput.value) {
                    alert('لطفا مدت زمان را وارد کنید');
                    durationInput.focus();
                    return;
                }
                if (!priceInput.value) {
                    alert('لطفا قیمت را وارد کنید');
                    priceInput.focus();
                    return;
                }

                // Disable editing
                typeSelect.disabled = true;
                customInput.disabled = true;
                durationInput.disabled = true;
                priceInput.disabled = true;
                statusSelect.disabled = true;

                // If it's a custom service, hide the input and show the select
                if (typeSelect.value === 'سایر') {
                    typeSelect.style.display = 'block';
                    customInput.style.display = 'none';
                }

                // Change save button back to edit button
                const actionsCell = row.querySelector('td:last-child');
                actionsCell.innerHTML = `
                    <div class="d-flex gap-1">
                        <button class="action-btn edit-btn" onclick="editService(this)">ویرایش</button>
                        <button class="action-btn delete-btn" onclick="deleteService(this)">حذف</button>
                    </div>
                `;
            };

            // Delete Service Function
            window.deleteService = function(button) {
                if (confirm('آیا از حذف این خدمت اطمینان دارید؟')) {
                    const row = button.closest('tr');
                    row.remove();
                }
            };

            // Update Service Function
            window.updateService = function(element) {
                // You can add any additional logic here when a field is updated
                console.log('Service updated:', element.value);
                // اگر قیمت تغییر کرد، قیمت نهایی را محاسبه کن
                if (element.classList.contains('service-price')) {
                    calculateFinalPrice(element);
                }
            };

            // تابع محاسبه قیمت نهایی با تخفیف
            window.calculateFinalPrice = function(element) {
                const row = element.closest('tr');
                const priceInput = row.querySelector('.service-price');
                const discountInput = row.querySelector('.service-discount');
                const finalPriceInput = row.querySelector('.service-final-price');
                const discountTypeSelect = row.querySelector('.discount-type');

                if (priceInput && discountInput && finalPriceInput) {
                    const originalPrice = parseFloat(priceInput.value) || 0;
                    const discountPercent = parseFloat(discountInput.value) || 0;

                    // بررسی اعتبار تخفیف بر اساس نوع
                    let isDiscountValid = true;
                    const discountType = discountTypeSelect ? discountTypeSelect.value : 'always';

                    if (discountType === 'custom') {
                        const startDate = row.querySelector('.discount-start-date');
                        const endDate = row.querySelector('.discount-end-date');
                        if (startDate && endDate && startDate.value && endDate.value) {
                            const today = new Date();
                            const start = new Date(startDate.value);
                            const end = new Date(endDate.value);
                            isDiscountValid = today >= start && today <= end;
                        } else {
                            isDiscountValid = false;
                        }
                    } else if (discountType === 'daily') {
                        // برای تخفیف روزانه، همیشه معتبر است
                        isDiscountValid = true;
                    } else if (discountType === 'weekly') {
                        // برای تخفیف هفتگی، می‌توان منطق خاصی اضافه کرد
                        isDiscountValid = true;
                    }

                    // محاسبه قیمت نهایی
                    let finalPrice = originalPrice;
                    if (isDiscountValid && discountPercent > 0) {
                        const discountAmount = (originalPrice * discountPercent) / 100;
                        finalPrice = originalPrice - discountAmount;
                    }

                    // نمایش قیمت نهایی
                    finalPriceInput.value = Math.round(finalPrice);
                }
            };

            // تابع مدیریت تغییر نوع تخفیف
            window.handleDiscountTypeChange = function(select) {
                const row = select.closest('tr');
                const dateRangeDiv = row.querySelector('.discount-date-range');

                if (select.value === 'custom') {
                    dateRangeDiv.style.display = 'block';
                } else {
                    dateRangeDiv.style.display = 'none';
                }

                // محاسبه مجدد قیمت نهایی
                calculateFinalPrice(select);
            };

            // Add New Service Function
            window.addNewService = function() {
                const serviceTable = document.getElementById('serviceTable');
                if (!serviceTable) return;

                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td>
                        <select class="form-select form-select-sm service-type" name="serviceType[]" onchange="handleServiceTypeChange(this)">
                            <option value="">انتخاب نوع خدمت</option>
                            <option value="کوتاهی مو">کوتاهی مو</option>
                            <option value="رنگ مو">رنگ مو</option>
                            <option value="هایلایت">هایلایت</option>
                            <option value="کراتینه مو">کراتینه مو</option>
                            <option value="بوتاکس مو">بوتاکس مو</option>
                            <option value="صاف کردن مو">صاف کردن مو</option>
                            <option value="فر کردن مو">فر کردن مو</option>
                            <option value="شینیون">شینیون</option>
                            <option value="آرایش صورت">آرایش صورت</option>
                            <option value="آرایش عروس">آرایش عروس</option>
                            <option value="آرایش شب">آرایش شب</option>
                            <option value="آرایش روز">آرایش روز</option>
                            <option value="میکاپ">میکاپ</option>
                            <option value="کاشت مژه">کاشت مژه</option>
                            <option value="کاشت ناخن">کاشت ناخن</option>
                            <option value="مانیکور">مانیکور</option>
                            <option value="پدیکور">پدیکور</option>
                            <option value="ژل ناخن">ژل ناخن</option>
                            <option value="اکستنشن ناخن">اکستنشن ناخن</option>
                            <option value="ماساژ صورت">ماساژ صورت</option>
                            <option value="ماساژ بدن">ماساژ بدن</option>
                            <option value="ماساژ پا">ماساژ پا</option>
                            <option value="ماساژ سر">ماساژ سر</option>
                            <option value="اپیلاسیون">اپیلاسیون</option>
                            <option value="لیزر موهای زائد">لیزر موهای زائد</option>
                            <option value="بوتاکس">بوتاکس</option>
                            <option value="فیلر">فیلر</option>
                            <option value="میکرونیدلینگ">میکرونیدلینگ</option>
                            <option value="پاکسازی صورت">پاکسازی صورت</option>
                            <option value="ماسک صورت">ماسک صورت</option>
                            <option value="میکروبلیدینگ ابرو">میکروبلیدینگ ابرو</option>
                            <option value="تاتو ابرو">تاتو ابرو</option>
                            <option value="تاتو لب">تاتو لب</option>
                            <option value="تاتو چشم">تاتو چشم</option>
                            <option value="سایر">سایر خدمات</option>
                        </select>
                        <input type="text" class="form-control form-control-sm mt-1 custom-service-type" name="customService[]" style="display: none;" placeholder="نام خدمت را در اینجا وارد کنید">
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="number" class="form-control service-duration" name="serviceDuration[]" maxlength="4" value="" placeholder="مدت زمان" onchange="updateService(this)">
                        </div>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="number" class="form-control service-price" name="servicePrice[]" maxlength="10" value="" placeholder="قیمت" onchange="updateService(this)">
                        </div>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="number" class="form-control service-discount" name="serviceDiscount[]" value="0" min="0" max="100" placeholder="0" onchange="calculateFinalPrice(this)">
                            <span class="input-group-text">%</span>
                        </div>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="number" class="form-control service-final-price" name="servicePriceFinal[]" value="" placeholder="قیمت نهایی" readonly>
                        </div>
                    </td>
                    <td>
                        <select class="form-select form-select-sm discount-type" name="discountType[]" onchange="handleDiscountTypeChange(this)">
                            <option value="daily" selected>روزانه</option>
                            <option value="always">همیشه</option>
                            <option value="weekly">هفتگی</option>
                        </select>
                        <div class="discount-date-range mt-1">
                            <input type="date" class="form-control form-control-sm discount-start-date" name="discountStartDate[]" placeholder="تاریخ شروع" onchange="calculateFinalPrice(this)">
                            <input type="date" class="form-control form-control-sm discount-end-date mt-1" name="discountEndDate[]" placeholder="تاریخ پایان" onchange="calculateFinalPrice(this)">
                        </div>
                    </td>
                    <td>
                        <select class="form-select form-select-sm service-status" name="serviceStatus[]" onchange="updateService(this)">
                            <option value="فعال">فعال</option>
                            <option value="غیرفعال">غیرفعال</option>
                        </select>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <button class="action-btn save-btn" onclick="saveNewService(this)">ذخیره</button>
                            <button class="action-btn delete-btn" onclick="deleteService(this)">حذف</button>
                        </div>
                    </td>
                `;

                const tbody = serviceTable.querySelector('tbody');
                if (tbody) {
                    tbody.insertBefore(newRow, tbody.firstChild);
                    // Focus on the first input
                    newRow.querySelector('.service-type').focus();
                }
            };
        });
    </script>

    <script>
        function editWorkingHours() {
            // Enable editing of all time inputs and status selects
            document.querySelectorAll('.start-time, .end-time').forEach(input => {
                input.disabled = false;
            });
            document.querySelectorAll('.day-status').forEach(select => {
                select.disabled = false;
            });
        }

        function saveWorkingHours(button) {
            const row = button.closest('tr');
            const day = row.cells[0].textContent;
            const startTime = row.querySelector('.start-time').value;
            const endTime = row.querySelector('.end-time').value;
            const status = row.querySelector('.day-status').value;

            // Here you would typically make an AJAX call to save the data
            // For now, we'll just show a success message
            alert(`ساعات کاری ${day} با موفقیت ذخیره شد`);

            // Disable editing after save
            row.querySelectorAll('.start-time, .end-time').forEach(input => {
                input.disabled = true;
            });
            row.querySelector('.day-status').disabled = true;
        }
    </script>

    <script>
        function downloadFinancialReport() {
            const period = document.getElementById('reportPeriod').value;
            // Here you would typically make an API call to generate and download the report
            fetch(`/api/reports/financial?period=${period}`)
                .then(response => response.blob())
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `financial_report-${period}.pdf`;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                })
                .catch(error => {
                    console.error('Error downloading report:', error);
                    alert('خطا در دانلود گزارش. لطفا دوباره تلاش کنید.');
                });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // تنظیمات کلی برای نمودارها
            Chart.defaults.font.family = 'Vazirmatn';
            Chart.defaults.font.size = 12;
            Chart.defaults.color = '#666';

            // نمودار نوبت‌ها
            const appointmentsCtx = document.getElementById('appointmentsChart').getContext('2d');
            const appointmentsChart = new Chart(appointmentsCtx, {
                type: 'line',
                data: {
                    labels: ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'],
                    datasets: [{
                        label: 'تعداد نوبت‌ها',
                        data: [12, 19, 15, 17, 22, 25, 18],
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            rtl: true
                        },
                        title: {
                            display: true,
                            text: 'آمار نوبت‌های هفته جاری',
                            rtl: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // نمودار مالی
            const financialCtx = document.getElementById('financialChart').getContext('2d');
            const financialChart = new Chart(financialCtx, {
                type: 'bar',
                data: {
                    labels: ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'],
                    datasets: [{
                        label: 'درآمد (هزار تومان)',
                        data: [450, 580, 620, 750, 680, 820, 650],
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgb(54, 162, 235)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            rtl: true
                        },
                        title: {
                            display: true,
                            text: 'درآمد هفته جاری',
                            rtl: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value + ' هزار';
                                }
                            }
                        }
                    }
                }
            });

            // به‌روزرسانی نمودارها با تغییر دوره زمانی
            document.getElementById('reportPeriod').addEventListener('change', function(e) {
                const period = e.target.value;
                updateCharts(period);
            });

            function updateCharts(period) {
                // این تابع با توجه به دوره زمانی انتخاب شده، داده‌های جدید را دریافت می‌کند
                // و نمودارها را به‌روزرسانی می‌کند
                fetch(`/api/reports/charts?period=${period}`)
                    .then(response => response.json())
                    .then(data => {
                        appointmentsChart.data.datasets[0].data = data.appointments;
                        appointmentsChart.update();

                        financialChart.data.datasets[0].data = data.financial;
                        financialChart.update();
                    })
                    .catch(error => {
                        console.error('Error updating charts:', error);
                    });
            }
        });
    </script>

    <!-- در HTML دکمه را این‌طور بنویس (پیشنهاد): -->
    <!-- <button type="button" class="btn btn-light btn-sm btn-add-facility">امکانات جدید</button> -->

    <script>
        /* ======= JS کامل و مقاوم برای add/delete + sync value + ارسال فقط چک‌شده‌ها ======= */

        function attachFacilityHandlers(span, checkbox) {
            const placeholder = span.dataset.placeholder || 'عنوان امکان جدید را وارد کنید';

            span.addEventListener('input', function() {
                const txt = span.textContent.trim();
                checkbox.value = txt;
            });

            span.addEventListener('blur', function() {
                if (!span.textContent.trim()) {
                    span.innerHTML = '<em>' + placeholder + '</em>';
                    checkbox.value = '';
                }
            });

            span.addEventListener('focus', function() {
                const txt = span.textContent.trim();
                if (txt === placeholder) span.innerHTML = '';
            });

            const initial = span.textContent.trim();
            if (initial && initial !== placeholder) {
                checkbox.value = initial;
            } else {
                if (!initial) span.innerHTML = '<em>' + placeholder + '</em>';
                checkbox.value = '';
            }
        }

        function addNewFacility() {
            const table = document.querySelector('.salon-facilities-table');
            if (!table) return;
            const rows = table.querySelectorAll('tr');
            const newId = Date.now();

            const cellHtml = `
        <label class="salon-facility-label">
            <input type="checkbox" name="salon_option[]" class="salon-facility-checkbox" id="facility-${newId}" value="">
            <span class="custom-checkbox"></span>
            <span contenteditable="true"
                  class="facility-text"
                  data-placeholder="عنوان امکان جدید را وارد کنید"
                  style="min-width: 150px; display: inline-block; border-bottom: 1px dashed #666;"></span>
            <button type="button" class="btn-delete-facility" onclick="deleteFacility(this)"
                    style="background: none; border: none; color: #ff5252; margin-right: 8px;">
                <i class="fas fa-trash"></i>
            </button>
        </label>
    `;

            if (rows.length === 0 || rows[rows.length - 1].querySelectorAll('td').length >= 3) {
                const newRow = document.createElement('tr');
                newRow.innerHTML = `<td>${cellHtml}</td>`;
                table.appendChild(newRow);

                const span = newRow.querySelector('.facility-text');
                const checkbox = newRow.querySelector('input[type="checkbox"]');
                attachFacilityHandlers(span, checkbox);
                span.focus();
            } else {
                const lastRow = rows[rows.length - 1];
                const newCell = document.createElement('td');
                newCell.innerHTML = cellHtml;
                lastRow.appendChild(newCell);

                const span = newCell.querySelector('.facility-text');
                const checkbox = newCell.querySelector('input[type="checkbox"]');
                attachFacilityHandlers(span, checkbox);
                span.focus();
            }
        }

        function deleteFacility(button) {
            if (!confirm('آیا از حذف این امکان اطمینان دارید؟')) return;
            const label = button.closest('.salon-facility-label');
            if (!label) return;
            const td = label.closest('td');
            const tr = td.closest('tr');

            td.remove();
            if (tr && tr.querySelectorAll('td').length === 0) {
                tr.remove();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // attach handlers برای المان‌های موجود
            document.querySelectorAll('.salon-facility-label').forEach(function(label) {
                const span = label.querySelector('.facility-text');
                const checkbox = label.querySelector('input[type="checkbox"]');
                if (span && checkbox) attachFacilityHandlers(span, checkbox);
            });

            // --- مهم: استفاده از event delegation برای دکمه اضافه ---
            document.addEventListener('click', function(e) {
                const addBtn = e.target.closest('.btn-add-facility');
                if (addBtn) {
                    // جلوگیری از رفتار پیش‌فرض اگر دکمه از نوع لینک باشد
                    e.preventDefault();
                    // اطمینان از اینکه دکمه submit نیست
                    if (addBtn.type && addBtn.type.toLowerCase() === 'submit') {
                        addBtn.type = 'button';
                    }
                    addNewFacility();
                }
            });

            // همسان‌سازی نهایی قبل از submit: فقط چک‌شده‌ها را ارسال کن
            const form = document.getElementById('OptionSalonForm');
            if (!form) return;

            form.addEventListener('submit', function(e) {
                const labels = form.querySelectorAll('.salon-facility-label');
                labels.forEach(function(label) {
                    const span = label.querySelector('.facility-text');
                    const checkbox = label.querySelector('input[type="checkbox"]');
                    if (!checkbox) return;
                    const txt = (span && span.textContent) ? span.textContent.trim() : '';

                    if (checkbox.checked && txt !== '') {
                        checkbox.value = txt;
                        checkbox.setAttribute('name', 'salon_option[]');
                    } else {
                        checkbox.removeAttribute('name');
                    }
                });

                // اکنون فقط checkboxهایی که تیک شده و متن دارند ارسال می‌شوند
            });
        });
    </script>



    <script>
        // لیست استان‌ها و شهرهای مربوطه
        const citiesByProvince = {
            "البرز": ["کرج", "فردیس", "نظرآباد", "اشتهارد", "طالقان", "ساوجبلاغ", "چهارباغ", "ماهدشت", "محمدشهر", "کوهسار", "گرمدره"],
            "اردبیل": ["اردبیل", "مشگین‌شهر", "پارس‌آباد", "خلخال", "نمین", "گرمی", "بیله‌سوار", "نیر", "سرعین", "کوثر", "اصلاندوز"],
            "بوشهر": ["بوشهر", "دشتستان", "تنگستان", "کنگان", "گناوه", "دشتی", "دیر", "جم", "عسلویه", "دیلم", "بردخون", "اهرم"],
            "چهارمحال و بختیاری": ["شهرکرد", "بروجن", "فارسان", "لردگان", "سامان", "کوهرنگ", "اردل", "کیار", "بن", "سودجان"],
            "آذربایجان شرقی": ["تبریز", "مراغه", "مرند", "میانه", "اهر", "بناب", "شبستر", "سراب", "هریس", "ملکان", "بستان‌آباد", "جلفا", "آذرشهر", "اسکو", "ورزقان", "چاراویماق", "خدا آفرین"],
            "فارس": ["شیراز", "مرودشت", "کازرون", "جهرم", "لار", "فسا", "داراب", "نی‌ریز", "سپیدان", "استهبان", "آباده", "اقلید", "لامرد", "ممسنی", "زرین‌دشت", "خرامه", "سروستان", "کوار", "خنج", "رستم", "پاسارگاد", "بوانات", "مهر", "فراشبند", "ارسنجان", "زرقان"],
            "گیلان": ["رشت", "انزلی", "لاهیجان", "آستارا", "تالش", "رودسر", "لنگرود", "صومعه‌سرا", "فومن", "آستانه اشرفیه", "رضوانشهر", "شفت", "ماسال", "املش", "سیاهکل", "خمام", "بندر کیاشهر"],
            "گلستان": ["گرگان", "گنبد", "علی‌آباد", "آق‌قلا", "کلاله", "مینودشت", "بندر ترکمن", "آزادشهر", "رامیان", "گمیشان", "مراوه‌تپه", "گالیکش", "کردکوی", "بندرگز", "نگین‌شهر"],
            "همدان": ["همدان", "ملایر", "نهاوند", "تویسرکان", "اسدآباد", "رزن", "کبودرآهنگ", "فامنین", "بهار", "قروه درجزین", "جوکار"],
            "هرمزگان": ["بندرعباس", "قشم", "بندرلنگه", "میناب", "حاجی‌آباد", "کیش", "جاسک", "پارسیان", "رودان", "سیریک", "ابوموسی", "بستک", "بندر خمیر", "بندر چارک"],
            "ایلام": ["ایلام", "دهلران", "آبدانان", "دره‌شهر", "مهران", "ایوان", "ملکشاهی", "چوار", "بدره", "سرابله", "لومار"],
            "اصفهان": ["اصفهان", "کاشان", "خمینی‌شهر", "نجف‌آباد", "شاهین‌شهر", "فلاورجان", "زرین‌شهر", "مبارکه", "لنجان", "آران و بیدگل", "فریدن", "فریدون‌شهر", "نایین", "اردستان", "سمیرم", "گلپایگان", "خوانسار", "دهاقان", "تیران و کرون", "چادگان", "برخوار", "شاهین‌شهر و میمه", "هرند", "جوشقان و کامو"],
            "کرمان": ["کرمان", "رفسنجان", "جیرفت", "سیرجان", "بم", "زرند", "بردسیر", "راور", "کهنوج", "عنبرآباد", "شهربابک", "فهرج", "ریگان", "منوجان", "قلعه گنج", "انار", "ارزوئیه", "نرماشیر"],
            "کرمانشاه": ["کرمانشاه", "اسلام‌آباد غرب", "هرسین", "سنقر", "قصرشیرین", "کنگاور", "جوانرود", "پاوه", "گیلانغرب", "سرپل ذهاب", "روانسر", "صحنه", "ثلاث باباجانی", "باینگان"],
            "خوزشتان": ["اهواز", "آبادان", "دزفول", "خرمشهر", "ماهشهر", "شوشتر", "بهبهان", "شادگان", "ایذه", "امیدیه", "رامهرمز", "شوش", "مسجدسلیمان", "اندیمشک", "رامشیر", "هندیجان", "لالی", "حمیدیه", "باوی", "کارون", "گتوند", "بندر امام خمینی", "سوسنگرد", "ملاثانی"],
            "کهگیلویه و بویراحمد": ["یاسوج", "گچساران", "دهدشت", "لیکک", "چرام", "باشت", "دنا", "لنده", "سوق", "سی‌سخت", "مارگون"],
            "کردستان": ["سنندج", "سقز", "بانه", "قروه", "بیجار", "کامیاران", "دیواندره", "مریوان", "دهگلان", "سریش‌آباد", "دلبران", "اورامان تخت"],
            "لرستان": ["خرم‌آباد", "بروجرد", "دورود", "الیگودرز", "کوهدشت", "ازنا", "پلدختر", "الشتر", "نورآباد", "چغلوندی", "زاغه", "سپیددشت"],
            "مرکزی": ["اراک", "ساوه", "خمین", "محلات", "شازند", "دلیجان", "تفرش", "آشتیان", "کمیجان", "خنداب", "فراهان", "زرندیه", "پرندک"],
            "مازندران": ["ساری", "بابل", "آمل", "قائم‌شهر", "بابلسر", "نکا", "بهشهر", "تنکابن", "رامسر", "نور", "محمودآباد", "جویبار", "سوادکوه", "عباس‌آباد", "فریدونکنار", "کلاردشت", "گلوگاه", "پل سفید", "مرزن‌آباد"],
            "خراسان شمالی": ["بجنورد", "اسفراین", "شیروان", "جاجرم", "مانه و سملقان", "فاروج", "گرمه", "راز و جرگلان", "درق", "آشخانه"],
            "قزوین": ["قزوین", "البرز", "آبیک", "تاکستان", "بوئین‌زهرا", "آوج", "محمودآباد نمونه", "خرمدشت", "شال", "اسفرورین", "سیردان"],
            "قم": ["قم", "جعفریه", "کهک", "دستجرد", "سلفچگان"],
            "خراسان رضوی": ["مشهد", "نیشابور", "سبزوار", "تربت حیدریه", "قوچان", "کاشمر", "تایباد", "تربت جام", "خواف", "چناران", "درگز", "بردسکن", "سرخس", "طرقبه", "فریمان", "گناباد", "کلات", "خلیل‌آباد", "رشتخوار", "باخرز", "فیروزه", "بینالود"],
            "سمنان": ["سمنان", "شاهرود", "دامغان", "گرمسار", "مهدی‌شهر", "آرادان", "سرخه", "ایوانکی", "امیریه", "بیارجمند"],
            "سیستان و بلوچستان": ["زاهدان", "چابهار", "زابل", "ایرانشهر", "خاش", "سراوان", "کنارک", "زهک", "هیرمند", "میرجاوه", "نیک‌شهر", "دلگان", "فنوج", "سیب و سوران", "قصرقند", "بمپور"],
            "خراسان جنوبی": ["بیرجند", "قائن", "فردوس", "طبس", "نهبندان", "سربیشه", "درمیان", "بشرویه", "خوسف", "زیرکوه", "آیسک", "محمدشهر"],
            "تهران": ["تهران", "ری", "شمیرانات", "اسلامشهر", "شهریار", "ورامین", "قدس", "ملارد", "پاکدشت", "رباط‌کریم", "بهارستان", "قرچک", "دماوند", "پردیس", "فیروزکوه", "باقرشهر", "چهاردانگه", "بومهن", "آبسرد", "آبعلی", "رودهن"],
            "آذربایجان غربی": ["ارومیه", "خوی", "میاندوآب", "بوکان", "سلماس", "پیرانشهر", "مهاباد", "نقده", "شاهین‌دژ", "اشنویه", "تکاب", "چایپاره", "شوط", "پلدشت", "چالدران", "ربط"],
            "یزد": ["یزد", "میبد", "اردکان", "بافق", "ابرکوه", "مهریز", "تفت", "خضرآباد", "اشکذر", "هرات", "زارچ", "بهاباد"],
            "زنجان": ["زنجان", "ابهر", "خرمدره", "طارم", "ماهنشان", "ایجرود", "سلطانیه", "دندی", "زرین‌آباد", "حلب", "سجاس"],
        };

        document.addEventListener('DOMContentLoaded', function() {
            const provinceSelect = document.getElementById('province');
            const citySelect = document.getElementById('city');

            // --- مرتب‌سازی و تولید داینامیک استان‌ها ---
            const provinceNames = [{
                    value: "البرز",
                    label: "البرز"
                },
                {
                    value: "اردبیل",
                    label: "اردبیل"
                },
                {
                    value: "اصفهان",
                    label: "اصفهان"
                },
                {
                    value: "آذربایجان شرقی",
                    label: "آذربایجان شرقی"
                },
                {
                    value: "آذربایجان غربی",
                    label: "آذربایجان غربی"
                },
                {
                    value: "ایلام",
                    label: "ایلام"
                },
                {
                    value: "بوشهر",
                    label: "بوشهر"
                },
                {
                    value: "تهران",
                    label: "تهران"
                },
                {
                    value: "چهارمحال و بختیاری",
                    label: "چهارمحال و بختیاری"
                },
                {
                    value: "خراسان جنوبی",
                    label: "خراسان جنوبی"
                },
                {
                    value: "خراسان رضوی",
                    label: "خراسان رضوی"
                },
                {
                    value: "خراسان شمالی",
                    label: "خراسان شمالی"
                },
                {
                    value: "خوزستان",
                    label: "خوزستان"
                },
                {
                    value: "زنجان",
                    label: "زنجان"
                },
                {
                    value: "سمنان",
                    label: "سمنان"
                },
                {
                    value: "سیستان و بلوچستان",
                    label: "سیستان و بلوچستان"
                },
                {
                    value: "فارس",
                    label: "فارس"
                },
                {
                    value: "قزوین",
                    label: "قزوین"
                },
                {
                    value: "قم",
                    label: "قم"
                },
                {
                    value: "کردستان",
                    label: "کردستان"
                },
                {
                    value: "کرمان",
                    label: "کرمان"
                },
                {
                    value: "کرمانشاه",
                    label: "کرمانشاه"
                },
                {
                    value: "کهگیلویه و بویراحمد",
                    label: "کهگیلویه و بویراحمد"
                },
                {
                    value: "گلستان",
                    label: "گلستان"
                },
                {
                    value: "گیلان",
                    label: "گیلان"
                },
                {
                    value: "لرستان",
                    label: "لرستان"
                },
                {
                    value: "مازندران",
                    label: "مازندران"
                },
                {
                    value: "مرکزی",
                    label: "مرکزی"
                },
                {
                    value: "هرمزگان",
                    label: "هرمزگان"
                },
                {
                    value: "همدان",
                    label: "همدان"
                },
                {
                    value: "یزد",
                    label: "یزد"
                }
            ];
            // مرتب‌سازی بر اساس حروف الفبا (label)
            provinceNames.sort((a, b) => a.label.localeCompare(b.label, 'fa'));

            // پاک کردن گزینه‌های قبلی و افزودن گزینه پیش‌فرض
            provinceSelect.innerHTML = '<option value="">انتخاب استان</option>';
            provinceNames.forEach(prov => {
                const option = document.createElement('option');
                option.value = prov.value;
                option.textContent = prov.label;
                provinceSelect.appendChild(option);
            });
            // --- پایان مرتب‌سازی استان‌ها ---

            provinceSelect.addEventListener('change', function() {
                const province = this.value;
                citySelect.innerHTML = '';

                if (province && citiesByProvince[province]) {
                    citySelect.disabled = false;
                    citySelect.innerHTML = '<option value="">انتخاب شهر</option>';
                    citiesByProvince[province].forEach(city => {
                        const option = document.createElement('option');
                        option.value = city;
                        option.textContent = city;
                        citySelect.appendChild(option);
                    });
                } else {
                    citySelect.disabled = true;
                    citySelect.innerHTML = '<option value="">ابتدا استان را انتخاب کنید</option>';
                }
            });
        });
    </script>

    <!-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mainProfileImage = document.getElementById('main-profile-image');
            const mainProfileImageInput = document.getElementById('profileImageInput');
            const mainProfileImageOverlay = document.querySelector('.profile-image-overlay');
            const advProfileImage = document.getElementById('advanced-profile-image71');
            const advProfileImageInput = document.getElementById('advancedProfileImageInput71');
            const advProfileImageBtn = document.getElementById('change-photo-btn71');

            // همگام‌سازی تغییر عکس پروفایل اصلی
            function handleMainProfileImageUpload(event) {
                const file = event.target.files[0];
                if (file) {
                    if (!file.type.startsWith('image/')) {
                        alert('لطفا یک فایل تصویر انتخاب کنید');
                        return;
                    }
                    if (file.size > 2 * 1024 * 1024) {
                        alert('حجم فایل نباید بیشتر از 2 مگابایت باشد');
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        mainProfileImage.src = e.target.result;
                        if (advProfileImage) advProfileImage.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            }
            // همگام‌سازی تغییر عکس پروفایل پیشرفته
            function handleAdvProfileImageUpload(event) {
                const file = event.target.files[0];
                if (file) {
                    if (!file.type.startsWith('image/')) {
                        alert('لطفا یک فایل تصویر انتخاب کنید');
                        return;
                    }
                    if (file.size > 2 * 1024 * 1024) {
                        alert('حجم فایل نباید بیشتر از 2 مگابایت باشد');
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        advProfileImage.src = e.target.result;
                        if (mainProfileImage) mainProfileImage.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            }
            // رویدادها برای عکس پروفایل اصلی
            if (mainProfileImage && mainProfileImageInput && mainProfileImageOverlay) {
                mainProfileImage.addEventListener('click', () => mainProfileImageInput.click());
                mainProfileImageOverlay.addEventListener('click', () => mainProfileImageInput.click());
                mainProfileImageInput.addEventListener('change', handleMainProfileImageUpload);
            }
            // رویدادها برای عکس پروفایل پیشرفته
            if (advProfileImage && advProfileImageInput && advProfileImageBtn) {
                advProfileImageBtn.addEventListener('click', function() {
                    advProfileImageInput.click();
                });
                advProfileImageInput.addEventListener('change', handleAdvProfileImageUpload);
            }
        });
    </script> -->

    <!-- Modal ارسال پیام به مشتری -->
    <div class="modal fade" id="sendMessageModal" tabindex="-1" aria-labelledby="sendMessageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="direction: rtl;">
                <div class="modal-header">
                    <h5 class="modal-title" id="sendMessageModalLabel">ارسال پیام به مشتری</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2"><strong>مشتری: <span id="modalCustomerName"></span></strong></div>
                    <textarea id="messageText" class="form-control" rows="4" placeholder="متن پیام را وارد کنید..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                    <button type="button" class="btn btn-primary" id="sendMsgBtn">ارسال پیام</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openSendMessageModal(button) {
            // پیدا کردن نام مشتری از ردیف جدول
            const row = button.closest('tr');
            const customerName = row.cells[0].textContent.trim();
            document.getElementById('modalCustomerName').textContent = customerName;
            document.getElementById('messageText').value = '';
            // ذخیره نام مشتری برای استفاده هنگام ارسال
            document.getElementById('sendMsgBtn').setAttribute('data-customer', customerName);

            // نمایش modal (Bootstrap 5)
            var myModal = new bootstrap.Modal(document.getElementById('sendMessageModal'));
            myModal.show();
        }

        document.getElementById('sendMsgBtn').addEventListener('click', function() {
            const customerName = this.getAttribute('data-customer');
            const message = document.getElementById('messageText').value.trim();
            if (!message) {
                alert('لطفا متن پیام را وارد کنید.');
                return;
            }
            let notifications = JSON.parse(localStorage.getItem('customerNotifications') || '{}');
            if (!notifications[customerName]) notifications[customerName] = [];
            notifications[customerName].push({
                message: message,
                date: new Date().toLocaleString('fa-IR')
            });
            localStorage.setItem('customerNotifications', JSON.stringify(notifications));
            alert('پیام با موفقیت ذخیره شد و به مشتری ارسال خواهد شد.');
            var modal = bootstrap.Modal.getInstance(document.getElementById('sendMessageModal'));
            modal.hide();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- چت آنلاین ---
            const chatBox = document.getElementById('floatingChatBox');
            const chatToggleBtn = document.getElementById('chatToggleBtn');
            const minimizeBtn = document.getElementById('minimizeChat');
            const closeBtn = document.getElementById('closeChat');
            const chatFooterInput = chatBox.querySelector('.chat-footer input');
            const chatFooterSend = chatBox.querySelector('.chat-footer button');
            const chatMessages = chatBox.querySelector('.chat-messages');
            const badge = chatToggleBtn.querySelector('.notification-badge');

            // وضعیت خوانده شدن پیام‌ها
            function setBadgeVisibility(show) {
                badge.style.display = show ? 'inline-block' : 'none';
            }

            function markChatAsRead() {
                localStorage.setItem('supportChatRead', '1');
                setBadgeVisibility(false);
            }

            function markChatAsUnread() {
                localStorage.setItem('supportChatRead', '0');
                setBadgeVisibility(true);
            }
            // مقداردهی اولیه badge
            if (localStorage.getItem('supportChatRead') === '0') {
                setBadgeVisibility(true);
            } else {
                setBadgeVisibility(false);
            }

            // نمایش/مخفی‌سازی چت با دکمه شناور
            chatToggleBtn.addEventListener('click', function() {
                chatBox.style.display = 'block';
                chatBox.classList.add('active');
                chatToggleBtn.style.display = 'none';
                markChatAsRead();
            });
            // بستن کامل چت
            closeBtn.addEventListener('click', function() {
                chatBox.style.display = 'none';
                chatBox.classList.remove('active');
                chatToggleBtn.style.display = 'block';
            });
            // مینیمایز چت
            minimizeBtn.addEventListener('click', function() {
                chatBox.classList.toggle('minimized');
                if (chatBox.classList.contains('minimized')) {
                    chatBox.style.height = '60px';
                } else {
                    chatBox.style.height = '';
                }
            });
            // دکمه چت آنلاین در پشتیبانی
            const supportChatBtn = document.querySelector('#support_center .support-options .btn-primary');
            if (supportChatBtn) {
                supportChatBtn.addEventListener('click', function() {
                    chatBox.style.display = 'block';
                    chatBox.classList.add('active');
                    chatToggleBtn.style.display = 'none';
                    markChatAsRead();
                });
            }
            // ارسال پیام
            function sendMessage() {
                const text = chatFooterInput.value.trim();
                if (!text) return;
                const now = new Date();
                const time = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
                // افزودن پیام کاربر
                const msgDiv = document.createElement('div');
                msgDiv.className = 'message user';
                msgDiv.innerHTML = `<div class=\"message-content\">${text}</div><small class=\"message-time\">${time}</small>`;
                chatMessages.appendChild(msgDiv);
                chatFooterInput.value = '';
                chatMessages.scrollTop = chatMessages.scrollHeight;
                saveChatMessages();
                // شبیه‌سازی پاسخ پشتیبانی (اختیاری)
                setTimeout(function() {
                    const replyDiv = document.createElement('div');
                    replyDiv.className = 'message support';
                    replyDiv.innerHTML = `<div class=\"message-content\">پیام شما دریافت شد. پشتیبانی بزودی پاسخ می‌دهد.</div><small class=\"message-time\">${time}</small>`;
                    chatMessages.appendChild(replyDiv);
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                    saveChatMessages();
                    markChatAsUnread();
                }, 1200);
            }
            chatFooterSend.addEventListener('click', sendMessage);
            chatFooterInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    sendMessage();
                }
            });
            // ذخیره و بارگذاری پیام‌های چت در localStorage
            function saveChatMessages() {
                localStorage.setItem('supportChatMessages', chatMessages.innerHTML);
            }

            function loadChatMessages() {
                const saved = localStorage.getItem('supportChatMessages');
                if (saved) chatMessages.innerHTML = saved;
            }
            loadChatMessages();
        });
    </script>

    <!-- Modal for Change Password -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="direction: rtl;">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">تغییر رمز عبور</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body">
                    <form action="mp_config_changePassword.php" method="post" id="changePasswordForm">
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">رمز عبور فعلی</label>
                            <input type="password" name="currentPassword" class="form-control" id="currentPassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">رمز عبور جدید</label>
                            <input type="password" name="newPassword" class="form-control" id="newPassword" required minlength="6">
                        </div>
                        <div class="mb-3">
                            <label for="confirmNewPassword" class="form-label">تکرار رمز عبور جدید</label>
                            <input type="password" name="confirmNewPassword" class="form-control" id="confirmNewPassword" required minlength="6">
                        </div>
                        <div id="changePasswordError" class="text-danger mb-2" style="display:none;"></div>
                        <button type="submit" name="submit" class="btn btn-primary">تغییر رمز</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ... سایر کدها ... -->
    <!--==============================
        All Js File
    ============================== -->
    <!-- Jquery -->
    <script src="assets/js/vendor/jquery-3.6.0.min.js"></script>
    <!-- jQuery UI -->
    <script src="assets/js/jquery-ui.min.js"></script>
    <!-- Slick Slider -->
    <script src="assets/js/slick.min.js"></script>
    <!-- <script src="assets/js/app.min.js"></script> -->
    <!-- Layerslider -->
    <script src="assets/js/layerslider.utils.js"></script>
    <script src="assets/js/layerslider.transitions.js"></script>
    <script src="assets/js/layerslider.kreaturamedia.jquery.js"></script>
    <!-- Bootstrap -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Parallax Scroll -->
    <script src="assets/js/universal-parallax.min.js"></script>
    <!-- Wow.js Animation -->
    <script src="assets/js/wow.min.js"></script>
    <!-- jQuery Datepicker -->
    <script src="assets/js/jquery.datetimepicker.min.js"></script>
    <!-- Magnific Popup -->
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <!-- Isotope Filter -->
    <script src="assets/js/imagesloaded.pkgd.min.js"></script>
    <script src="assets/js/isotope.pkgd.min.js"></script>


    <!-- Persian Datepicker Plugin -->
    <script src="assets\js\jquery.datetimepicker.min.jss"></script>

    <script src="https://cdn.jsdelivr.net/npm/persian-date@1.1.0/dist/persian-date.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>

    <!-- Persian calendar modal -->
    <div id="psCalendarBackdrop" class="ps-modal-backdrop" aria-hidden="true">
        <div class="ps-modal" role="dialog" aria-modal="true" aria-label="تقویم">
            <div class="ps-cal-header">
                <div class="ps-nav-btn" id="psPrev">❮</div>
                <div class="ps-center"><span class="ps-month" id="psMonth">—</span><input id="psYear" class="ps-year" type="number" min="1200" max="1600" /></div>
                <div class="ps-nav-btn" id="psNext">❯</div>
            </div>
            <table class="ps-table">
                <thead>
                    <tr>
                        <th>ش</th>
                        <th>ی</th>
                        <th>د</th>
                        <th>س</th>
                        <th>چ</th>
                        <th>پ</th>
                        <th style="color:red">ج</th>
                    </tr>
                </thead>
                <tbody id="psDaysBody"></tbody>
            </table>
            <div class="ps-footer">
                <div>امروز: <span id="psToday">—</span></div>
                <div>انتخاب: <span id="psSelected">—</span></div>
            </div>
        </div>
    </div>

    <!-- JavaScript Files -->
    <script src="assets/js/script.js"></script>
    <!-- Main Js File -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/landing.js"></script>


</body>

</html>