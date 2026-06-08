<?php
session_start();
require_once 'database.php';

// Persisted session data
$customer_id  = $_SESSION['customer_id']  ?? "";
$customer_username  = $_SESSION['customer_username']  ?? "";
$customer_firstName  = $_SESSION['customer_firstName']  ?? "نام";
$customer_lastName  = $_SESSION['customer_lastName']  ?? "نام خانوادگی";

// Check if customer is logged in
if (empty($_SESSION['customer_id'])) {
    $_SESSION['login_error'] = "ابتدا باید به سایت ورود کنید";
    //mysqli_close($conn);
    //$conn = null;
    header("Location: login.php");
    exit();
}

// Flash messages (display once)
$cp_confirm  = $_SESSION['cp_confirm']  ?? "";
$cp_not_confirm = $_SESSION['cp_not_confirm'] ?? "";
// cp_config_personal
$login_error = $_SESSION['login_error'] ?? "";
$cp_emptyfield_error = $_SESSION['cp_emptyfield_error'] ?? "";
$cp_phone_error = $_SESSION['cp_phone_error'] ?? "";
$cp_nationalCode_error = $_SESSION['cp_nationalCode_error'] ?? "";
$cp_email_error = $_SESSION['cp_email_error'] ?? "";
$cp_not_confirm1 = $_SESSION['cp_not_confirm1'] ?? "";
$duplicate_customer_phone = $_SESSION['duplicate_customer_phone'] ?? "";
$cp_not_confirm2 = $_SESSION['cp_not_confirm2'] ?? "";
$cp_not_confirm3 = $_SESSION['cp_not_confirm3'] ?? "";
$cp_not_confirm4 = $_SESSION['cp_not_confirm4'] ?? "";
$cp_confirm = $_SESSION['cp_confirm'] ?? "";
$cp_not_confirm5 = $_SESSION['cp_not_confirm5'] ?? "";
$cp_confirm2 = $_SESSION['cp_confirm2'] ?? "";
$cp_not_confirm6 = $_SESSION['cp_not_confirm6'] ?? "";
$cp_not_confirm7 = $_SESSION['cp_not_confirm7'] ?? "";
//cp_config_bank.php
$cp_cardNumber_error = $_SESSION['cp_cardNumber_error'] ?? "";
$cp_sheba_error = $_SESSION['cp_sheba_error'] ?? "";
//cp_config_changePassword.php
$password_change_empty = $_SESSION['password_change_empty'] ?? "";
$password_change_mismatch = $_SESSION['password_change_mismatch'] ?? "";
$password_change_confirm = $_SESSION['password_change_confirm'] ?? "";
$current_password_mismatch = $_SESSION['current_password_mismatch'] ?? "";



unset(
    $_SESSION['cp_confirm'],
    $_SESSION['cp_not_confirm'],
    $_SESSION['login_error'],
    $_SESSION['cp_emptyfield_error'],
    $_SESSION['cp_phone_error'],
    $_SESSION['cp_nationalCode_error'],
    $_SESSION['cp_email_error'],
    $_SESSION['cp_not_confirm1'],
    $_SESSION['duplicate_customer_phone'],
    $_SESSION['cp_not_confirm2'],
    $_SESSION['cp_not_confirm3'],
    $_SESSION['cp_not_confirm4'],
    $_SESSION['cp_confirm'],
    $_SESSION['cp_not_confirm5'],
    $_SESSION['cp_confirm2'],
    $_SESSION['cp_not_confirm6'],
    $_SESSION['cp_not_confirm7'],
    $_SESSION['cp_cardNumber_error'],
    $_SESSION['cp_sheba_error']
);

?>

<!doctype html>
<html class="no-js" lang="fa" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!-- Cache Busting -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

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

    <link rel="stylesheet" href="assets/css/profile.css">
    <link rel="stylesheet" href="assets/css/costumer-profile.css">


    <style>

    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/md.bootstrappersiandatetimepicker/dist/jquery.md.bootstrap.datetimepicker.style.css">

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

    <!--==============================
    Popup Search Box
    ============================== -->
    <div class="popup-search-box d-none d-lg-block  ">
        <button class="searchClose"><i class="fal fa-times"></i></button>
        <form action="#">
            <input type="text" class="border-theme" placeholder="دنبال چه چیزی می‌گردید؟">
            <button type="submit"><i class="fal fa-search"></i></button>
        </form>
    </div>
    <!--==============================
    Header Area
    ==============================-->
    <header class="main-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h4 mb-0" style="margin:auto;">پنل مدیریت</h1>
                <div class="d-flex align-items-center">
                    <a href="index-72.html" class="header-back-button">
                        <i class="fas fa-arrow-right"></i>
                        بازگشت
                    </a>
                </div>

            </div>
        </div>
    </header>
    <!--==============================
    Hero Area
    ==============================-->
    <section class="vs-hero-wrapper hero-layout7 position-relative">
        <!-- Admin Panel Content -->
        <div class="admin-container">
            <!-- Sidebar -->
            <div class="admin-sidebar">
                <?php if (!empty($c_confirm)): ?>
                    <div class="notification1"><?php echo $c_confirm; ?></div>
                <?php elseif (!empty($c_not_confirm)): ?>
                    <div class="notification2"><?php echo $c_not_confirm; ?></div>
                <?php elseif (!empty($login_error)): ?>
                    <div class="notification2"><?php echo $login_error; ?></div>
                <?php elseif (!empty($cp_emptyfield_error)): ?>
                    <div class="notification2"><?php echo $cp_emptyfield_error; ?></div>
                <?php elseif (!empty($cp_phone_error)): ?>
                    <div class="notification2"><?php echo $cp_phone_error; ?></div>
                <?php elseif (!empty($cp_nationalCode_error)): ?>
                    <div class="notification2"><?php echo $cp_nationalCode_error; ?></div>
                <?php elseif (!empty($cp_email_error)): ?>
                    <div class="notification2"><?php echo $cp_email_error; ?></div>
                <?php elseif (!empty($cp_not_confirm1)): ?>
                    <div class="notification2"><?php echo $cp_not_confirm1; ?></div>
                <?php elseif (!empty($duplicate_customer_phone)): ?>
                    <div class="notification2"><?php echo $duplicate_customer_phone; ?></div>
                <?php elseif (!empty($cp_not_confirm2)): ?>
                    <div class="notification2"><?php echo $cp_not_confirm2; ?></div>
                <?php elseif (!empty($cp_not_confirm3)): ?>
                    <div class="notification2"><?php echo $cp_not_confirm3; ?></div>
                <?php elseif (!empty($cp_not_confirm4)): ?>
                    <div class="notification2"><?php echo $cp_not_confirm4; ?></div>
                <?php elseif (!empty($cp_confirm)): ?>
                    <div class="notification1"><?php echo $cp_confirm; ?></div>
                <?php elseif (!empty($cp_not_confirm5)): ?>
                    <div class="notification2"><?php echo $cp_not_confirm5; ?></div>
                <?php elseif (!empty($cp_confirm2)): ?>
                    <div class="notification1"><?php echo $cp_confirm2; ?></div>
                <?php elseif (!empty($cp_not_confirm6)): ?>
                    <div class="notification2"><?php echo $cp_not_confirm6; ?></div>
                <?php elseif (!empty($cp_not_confirm7)): ?>
                    <div class="notification2"><?php echo $cp_not_confirm7; ?></div>
                <?php elseif (!empty($cp_cardNumber_error)): ?>
                    <div class="notification2"><?php echo $cp_cardNumber_error; ?></div>
                <?php elseif (!empty($cp_sheba_error)): ?>
                    <div class="notification2"><?php echo $cp_sheba_error; ?></div>
                <?php elseif (!empty($password_change_mismatch)): ?>
                    <div class="notification2"><?php echo $password_change_mismatch; ?></div>
                <?php elseif (!empty($password_change_confirm)): ?>
                    <div class="notification1"><?php echo $password_change_confirm; ?></div>
                <?php elseif (!empty($current_password_mismatch)): ?>
                    <div class="notification2"><?php echo $current_password_mismatch; ?></div>
                <?php elseif (!empty($password_change_empty)): ?>
                    <div class="notification2"><?php echo $password_change_empty; ?></div>
                <?php endif; ?>


                <div class="text-center mb-4">
                    <div class="profile-image-container position-relative">
                        <img id="main-profile-image" src="assets/img/team/user1-128x128.jpg" alt="User Avatar" class="rounded-circle mb-3 profile-image" style="width: 100px; height: 100px; object-fit: cover; cursor: pointer;">
                        <div class="profile-image-overlay">
                            <i class="fas fa-camera"></i>
                            <span>تغییر عکس</span>
                        </div>
                    </div>
                    <h5 class="mb-1"><?php echo $customer_firstName . " " . $customer_lastName; ?></h5>
                    <p class="text-muted mb-0"> کاربر </p>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="#profile" data-bs-toggle="collapse" data-bs-target="#profileSubmenu" aria-expanded="true">
                        <i class="fas fa-user"></i> پروفایل کاربری
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </a>
                    <div class="collapse show" id="profileSubmenu">
                        <div class="nav flex-column ms-3">
                            <a class="nav-link" href="#personal-info">
                                <i class="fas fa-user-circle"></i> اطلاعات شخصی
                            </a>
                            <a class="nav-link" href="#financial-report">
                                <i class="fas fa-chart-line"></i> گزارش مالی
                            </a>
                            <a class="nav-link" href="#my-appointments">
                                <i class="fas fa-calendar-check"></i> نوبت‌های من
                            </a>
                        </div>
                    </div>
                    <!-- <a class="nav-link" href="#services">
                        <i class="fas fa-concierge-bell"></i> مدیریت خدمات
                    </a> -->
                    <a class="nav-link" href="#settings">
                        <i class="fas fa-cog"></i> تنظیمات اعلان ها
                    </a>
                    <a class="nav-link" href="#support-center">
                        <i class="fas fa-headset"></i> پشتیبانی - ارسال تیکت
                    </a>
                    <!-- <a class="nav-link" href="landing.html">
                        <i class="fas fa-sign-out-alt"></i> خروج از حساب کاربری
                    </a> -->
                    <a class="nav-link" href="landing.php">
                        <i class="fas fa-sign-out-alt"></i> خروج از حساب کاربری
                    </a>
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
                    <div class="admin-card" id="personal-info">
                        <div class="admin-card-header">
                            <h5 class="mb-0">اطلاعات شخصی</h5>
                        </div>
                        <div class="admin-card-body">
                            <form action="cp_config_personal.php" method="post" id="personalInfoForm" dir="rtl">
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
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="customerEmail">ایمیل</label>
                                            <input type="email" id="customerEmail" name="customerEmail">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="province" class="form-label text-end d-block">استان</label>
                                            <select class="form-select text-end" id="province" name="province">
                                                <option value="">انتخاب استان</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="city" class="form-label text-end d-block">شهر</label>
                                            <select class="form-select text-end" id="city" name="city" disabled>
                                                <option value="">ابتدا استان را انتخاب کنید</option>
                                            </select>
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

                    <!-- Generated Table for Personal Information -->
                    <?php include 'tbl_cp_personal.php'; ?>

                    <!-- Bank Information Section -->
                    <div class="admin-card" id="BankInfo">
                        <div class="admin-card-header">
                            <h5 class="mb-0">اطلاعات بانکی</h5>
                        </div>
                        <div class="admin-card-body">
                            <form action="cp_config_bank.php" method="post" id="bankInfoForm" dir="rtl">
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
                                                <span style="font-weight:bold; background:#eee; padding:6px 12px; border:1px solid #ccc; border-radius:0 4px 4px 0;">IR</span>
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
                    <?php include 'tbl_cp_bankinfo.php'; ?>

                    <!-- Financial Report -->
                    <div class="admin-card" id="financial-report">
                        <div class="admin-card-header">
                            <h5 class="mb-0">گزارش مالی</h5>
                            <div class="d-flex gap-2">
                                <select class="form-select form-select-sm w-auto">
                                    <option>این ماه</option>
                                    <option>ماه گذشته</option>
                                    <option>سه ماه گذشته</option>
                                    <option>سال جاری</option>
                                </select>
                                <button class="btn btn-light btn-sm">
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
                                <table class="admin-table" id="financial-table">
                                    <thead>
                                        <tr>
                                            <th>تاریخ</th>
                                            <th>شماره فاکتور</th>
                                            <th>نام سالن</th>
                                            <th>خدمات</th>
                                            <th>مبلغ</th>
                                            <th>وضعیت پرداخت</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>۱۴۰۳/۰۱/۱۵</td>
                                            <td>INV-001</td>
                                            <td>سالن زیبایی گل</td>
                                            <td>کوتاهی مو</td>
                                            <td>۱۵۰,۰۰۰ تومان</td>
                                            <td><span class="status-badge status-confirmed">پرداخت شده</span></td>
                                        </tr>
                                        <tr>
                                            <td>۱۴۰۳/۰۱/۱۴</td>
                                            <td>INV-002</td>
                                            <td>سالن زیبایی ماه</td>
                                            <td>آرایش صورت</td>
                                            <td>۲۵۰,۰۰۰ تومان</td>
                                            <td><span class="status-badge status-pending">پرداخت در محل</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="pagination-controls" id="financial-pagination"></div>
                        </div>
                    </div>

                    <!-- Appointments -->
                    <div class="admin-card" id="my-appointments">
                        <div class="admin-card-header">
                            <h5 class="mb-0">نوبت‌های من </h5>
                            <a href="customer_page.php" class="btn btn-light btn-sm">افزودن نوبت جدید</a>
                        </div>
                        <div class="admin-card-body">
                            <div class="table-responsive">
                                <table class="admin-table" id="appointments-table">
                                    <thead>
                                        <tr>
                                            <th>نام سالن</th>
                                            <th>تاریخ</th>
                                            <th>ساعت</th>
                                            <th>خدمات</th>
                                            <!-- <th>وضعیت</th> -->
                                            <th>عملیات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>سالن زیبایی گل</td>
                                            <td>۱۴۰۳/۰۱/۱۵</td>
                                            <td>۱۴:۳۰</td>
                                            <td>کوتاهی مو</td>
                                            <!-- <td><span class="status-badge status-completed">انجام شده</span></td> -->
                                            <!-- <td>
                                                <div class="rating" data-rating="0">
                                                    <i class="far fa-star rating-star" data-index="1"></i>
                                                    <i class="far fa-star rating-star" data-index="2"></i>
                                                    <i class="far fa-star rating-star" data-index="3"></i>
                                                    <i class="far fa-star rating-star" data-index="4"></i>
                                                    <i class="far fa-star rating-star" data-index="5"></i>
                                                </div>
                                            </td> -->
                                            <td>
                                                <button class="action-btn edit-btn" title="ارسال پیغام به مدیرسالن " data-comment-id="1403-01-15-سالن زیبایی گل">
                                                    <i class="fas fa-comment"></i>
                                                </button>
                                                <button class="action-btn delete-btn" title="لغو نوبت">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>سالن زیبایی ماه</td>
                                            <td>۱۴۰۳/۰۱/۱۵</td>
                                            <td>۱۶:۰۰</td>
                                            <td>آرایش صورت</td>
                                            <!-- <td><span class="status-badge status-pending">در انتظار </span></td> -->
                                            <!-- <td>
                                                <div class="rating" data-rating="0">
                                                    <i class="far fa-star rating-star" data-index="1"></i>
                                                    <i class="far fa-star rating-star" data-index="2"></i>
                                                    <i class="far fa-star rating-star" data-index="3"></i>
                                                    <i class="far fa-star rating-star" data-index="4"></i>
                                                    <i class="far fa-star rating-star" data-index="5"></i>
                                                </div>
                                            </td> -->
                                            <td>
                                                <button class="action-btn edit-btn" title="ارسال پیغام به مدیرسالن " data-comment-id="1403-01-15-سالن زیبایی ماه">
                                                    <i class="fas fa-comment"></i>
                                                </button>
                                                <button class="action-btn delete-btn" title="لغو نوبت">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="pagination-controls" id="appointments-pagination"></div>
                        </div>
                    </div>

                    <!-- Advanced Appointment Management -->
                    <div class="admin-card" id="appointment-management">
                        <div class="admin-card-header">
                            <h5 class="mb-0"> نوبت های انجام شده </h5>
                            <div class="d-flex gap-2">
                                <select class="form-select form-select-sm w-auto">
                                    <option value="all">همه نوبت‌ها</option>
                                    <option value="completed">انجام شده</option>
                                    <option value="cancelled">لغو شده</option>
                                    <!-- <option value="pending">در انتظار</option> -->
                                </select>
                                <input type="text" class="form-control form-control-sm" placeholder="جستجو...">
                            </div>
                        </div>
                        <div class="admin-card-body">
                            <div class="table-responsive">
                                <table class="admin-table" id="advanced-appointments-table">
                                    <thead>
                                        <tr>
                                            <th>تاریخ</th>
                                            <th>ساعت</th>
                                            <th>خدمات</th>
                                            <th>سالن</th>
                                            <th>وضعیت</th>
                                            <th>امتیاز</th>
                                            <th>ثبت نظر</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>۱۴۰۳/۰۱/۱۵</td>
                                            <td>۱۴:۳۰</td>
                                            <td>کوتاهی مو</td>
                                            <td>سالن زیبایی گل</td>
                                            <td><span class="status-badge status-completed">انجام شده</span></td>
                                            <td>
                                                <div class="rating" data-rating="0">
                                                    <i class="far fa-star rating-star" data-index="1"></i>
                                                    <i class="far fa-star rating-star" data-index="2"></i>
                                                    <i class="far fa-star rating-star" data-index="3"></i>
                                                    <i class="far fa-star rating-star" data-index="4"></i>
                                                    <i class="far fa-star rating-star" data-index="5"></i>
                                                </div>
                                            </td>
                                            <td>
                                                <button class="action-btn edit-btn" title="ثبت نظر" data-comment-id="1403-01-15-سالن زیبایی گل">
                                                    <i class="fas fa-comment"></i>
                                                </button>

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="pagination-controls" id="advanced-appointments-pagination"></div>
                        </div>
                    </div>

                    <!-- Enhanced Profile Section -->
                    <div class="admin-card" id="settings">
                        <div class="admin-card-header">
                            <h5 class="mb-0">اعلان ها </h5>

                        </div>
                        <div class="admin-card-body">
                            <div class="row">

                                <div class="col-md-8">
                                    <div  dir=rtl>
                                        <h6 class="mb-3">تنظیمات اعلان‌ها</h6>
                                        <div class="d-flex flex-column gap-1" >
                                            <div class="form-check form-switch mb-2 d-flex flex-row " style="gap:0;" >
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
                    <div class="admin-card" id="support-center">
                        <div class="admin-card-header">
                            <h5 class="mb-0">پشتیبانی</h5>
                        </div>
                        <div class="admin-card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="support-options">
                                        <button class="btn btn-primary w-100 mb-3" id="openChatBtn">
                                            <i class="fas fa-comments"></i> چت آنلاین
                                        </button>
                                        <button class="btn btn-outline-primary w-100 mb-3" id="openSupportTicketBtn">
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
                            <h5 class="mb-0">تنظیمات بیشتر </h5>
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
                                        <button class="btn btn-outline-primary btn-sm" id="changePasswordBtn" data-bs-toggle="modal" data-bs-target="#changePasswordModal2">
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
        <span class="notification-badge" style="display: flex;">1</span>
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
    <div id="modalContainer"></div>

    <!-- Add this hidden file input -->
    <input type="file" id="profileImageInput" accept="image/*" style="display: none;">

    <!-- Add this JavaScript code before the closing body tag -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const profileImage = document.querySelector('.profile-image');
            const profileImageInput = document.getElementById('profileImageInput');
            const profileImageOverlay = document.querySelector('.profile-image-overlay');
            const sidemenuProfileImage = document.getElementById('sidemenu-profile-image');

            // Function to handle image upload
            function handleImageUpload(event) {
                const file = event.target.files[0];
                if (file) {
                    // Check if file is an image
                    if (!file.type.startsWith('image/')) {
                        alert('لطفا یک فایل تصویر انتخاب کنید');
                        return;
                    }

                    // Check file size (max 2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('حجم فایل نباید بیشتر از 2 مگابایت باشد');
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        profileImage.src = e.target.result;
                        if (sidemenuProfileImage) {
                            sidemenuProfileImage.src = e.target.result;
                        }
                        // Here you can add code to upload the image to server
                        // For example:
                        // uploadImageToServer(file);
                    };
                    reader.readAsDataURL(file);
                }
            }

            // Add click event listeners
            profileImage.addEventListener('click', () => profileImageInput.click());
            profileImageOverlay.addEventListener('click', () => profileImageInput.click());
            profileImageInput.addEventListener('change', handleImageUpload);

            // Optional: Add drag and drop functionality
            profileImage.addEventListener('dragover', (e) => {
                e.preventDefault();
                profileImageOverlay.style.opacity = '1';
            });

            profileImage.addEventListener('dragleave', () => {
                profileImageOverlay.style.opacity = '0';
            });

            profileImage.addEventListener('drop', (e) => {
                e.preventDefault();
                profileImageOverlay.style.opacity = '0';
                const file = e.dataTransfer.files[0];
                if (file) {
                    profileImageInput.files = e.dataTransfer.files;
                    handleImageUpload({
                        target: {
                            files: [file]
                        }
                    });
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ... existing code ...
            // Provinces and cities data
            const provincesAndCities = {
                'آذربایجان شرقی': ['تبریز', 'مراغه', 'مرند', 'میانه', 'اهر', 'بناب', 'سراب', 'شبستر', 'هریس', 'ملکان'],
                'آذربایجان غربی': ['ارومیه', 'خوی', 'میاندوآب', 'مهاباد', 'بوکان', 'سلماس', 'پیرانشهر', 'نقده', 'شاهین‌دژ', 'تکاب'],
                'اردبیل': ['اردبیل', 'مشگین‌شهر', 'پارس‌آباد', 'خلخال', 'گرمی', 'نمین', 'نیر', 'بیله‌سوار', 'کوثر', 'سرعین'],
                'اصفهان': ['اصفهان', 'کاشان', 'خمینی‌شهر', 'نجف‌آباد', 'شاهین‌شهر', 'لنجان', 'فلاورجان', 'مبارکه', 'آران و بیدگل', 'زرین‌شهر'],
                'البرز': ['کرج', 'فردیس', 'نظرآباد', 'ساوجبلاغ', 'اشتهارد', 'طالقان'],
                'ایلام': ['ایلام', 'دهلران', 'دره‌شهر', 'آبدانان', 'مهران', 'ایوان', 'ملکشاهی', 'چرداول', 'بدره'],
                'بوشهر': ['بوشهر', 'دشتستان', 'کنگان', 'گناوه', 'دشتی', 'تنگستان', 'جم', 'عسلویه', 'دیلم'],
                'تهران': ['تهران', 'شهریار', 'اسلامشهر', 'ملارد', 'بهارستان', 'پاکدشت', 'پردیس', 'قرچک', 'قدس', 'ورامین'],
                'چهارمحال و بختیاری': ['شهرکرد', 'بروجن', 'فارسان', 'لردگان', 'کیار', 'سامان', 'اردل', 'کوهرنگ', 'بن', 'خانمیرزا'],
                'خراسان جنوبی': ['بیرجند', 'قائن', 'فردوس', 'نهبندان', 'سربیشه', 'طبس', 'درمیان', 'بشرویه', 'خوسف', 'زیرکوه'],
                'خراسان رضوی': ['مشهد', 'نیشابور', 'سبزوار', 'تربت حیدریه', 'قوچان', 'کاشمر', 'تربت جام', 'چناران', 'خواف', 'بردسکن'],
                'خراسان شمالی': ['بجنورد', 'اسفراین', 'شیروان', 'جاجرم', 'مانه و سملقان', 'فاروج', 'گرمه', 'راز و جرگلان'],
                'خوزستان': ['اهواز', 'دزفول', 'آبادان', 'خرمشهر', 'ماهشهر', 'شوشتر', 'بهبهان', 'شادگان', 'اندیمشک', 'ایذه'],
                'زنجان': ['زنجان', 'ابهر', 'خرمدره', 'ماهنشان', 'ایجرود', 'سلطانیه', 'طارم', 'دندی'],
                'سمنان': ['سمنان', 'شاهرود', 'دامغان', 'گرمسار', 'مهدی‌شهر', 'آرادان', 'میامی', 'سرخه'],
                'سیستان و بلوچستان': ['زاهدان', 'چابهار', 'ایرانشهر', 'خاش', 'سراوان', 'نیک‌شهر', 'کنارک', 'زهک', 'هیرمند', 'دلگان'],
                'فارس': ['شیراز', 'مرودشت', 'جهرم', 'کازرون', 'فسا', 'داراب', 'لار', 'نی‌ریز', 'سپیدان', 'ممسنی'],
                'قزوین': ['قزوین', 'البرز', 'آبیک', 'تاکستان', 'بوئین‌زهرا', 'آوج'],
                'قم': ['قم'],
                'کردستان': ['سنندج', 'سقز', 'بانه', 'قروه', 'بیجار', 'کامیاران', 'دیواندره', 'مریوان', 'دهگلان'],
                'کرمان': ['کرمان', 'رفسنجان', 'جیرفت', 'سیرجان', 'بم', 'زرند', 'کهنوج', 'بردسیر', 'راور', 'عنبرآباد'],
                'کرمانشاه': ['کرمانشاه', 'اسلام‌آباد غرب', 'هرسین', 'سنقر', 'کنگاور', 'سرپل ذهاب', 'قصر شیرین', 'پاوه', 'جوانرود', 'گیلانغرب'],
                'کهگیلویه و بویراحمد': ['یاسوج', 'گچساران', 'دهدشت', 'دوگنبدان', 'سی‌سخت', 'باشت', 'چرام', 'لیکک'],
                'گلستان': ['گرگان', 'گنبد کاووس', 'علی‌آباد', 'آق‌قلا', 'کردکوی', 'بندر ترکمن', 'مینودشت', 'کلاله', 'گمیشان', 'مراوه‌تپه'],
                'گیلان': ['رشت', 'انزلی', 'لاهیجان', 'لنگرود', 'آستارا', 'تالش', 'آستانه اشرفیه', 'رودسر', 'فومن', 'صومعه‌سرا'],
                'لرستان': ['خرم‌آباد', 'بروجرد', 'دورود', 'الیگودرز', 'کوهدشت', 'ازنا', 'پلدختر', 'دلفان', 'سلسله', 'رومشکان'],
                'مازندران': ['ساری', 'بابل', 'آمل', 'قائم‌شهر', 'تنکابن', 'نکا', 'بابلسر', 'محمودآباد', 'نور', 'چالوس'],
                'مرکزی': ['اراک', 'ساوه', 'خمین', 'محلات', 'دلیجان', 'شازند', 'تفرش', 'آشتیان', 'زرندیه', 'کمیجان'],
                'هرمزگان': ['بندرعباس', 'بندر لنگه', 'میناب', 'قشم', 'حاجی‌آباد', 'رودان', 'پارسیان', 'جاسک', 'سیریک', 'ابوموسی'],
                'همدان': ['همدان', 'ملایر', 'نهاوند', 'کبودرآهنگ', 'تویسرکان', 'بهار', 'رزن', 'اسدآباد', 'فامنین'],
                'یزد': ['یزد', 'میبد', 'اردکان', 'بافق', 'ابرکوه', 'مهریز', 'اشکذر', 'خضرآباد', 'تفت', 'هرات']
            };

            const provinceSelect = document.getElementById('province');
            const citySelect = document.getElementById('city');

            // Populate provinces
            provinceSelect.innerHTML = '<option value="">انتخاب استان</option>';
            Object.keys(provincesAndCities).forEach(function(province) {
                const option = document.createElement('option');
                option.value = province;
                option.textContent = province;
                provinceSelect.appendChild(option);
            });

            // Province change event
            provinceSelect.addEventListener('change', function() {
                const selectedProvince = this.value;
                citySelect.innerHTML = '';
                if (selectedProvince && provincesAndCities[selectedProvince]) {
                    citySelect.disabled = false;
                    citySelect.innerHTML = '<option value="">انتخاب شهر</option>';
                    provincesAndCities[selectedProvince].forEach(function(city) {
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mainProfileImage = document.getElementById('main-profile-image');
            const mainProfileImageInput = document.getElementById('profileImageInput');
            const mainProfileImageOverlay = document.querySelector('.profile-image-overlay');
            const advProfileImage = document.getElementById('advanced-profile-image72');
            const advProfileImageInput = document.getElementById('advancedProfileImageInput72');
            const advProfileImageBtn = document.getElementById('change-photo-btn72');

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
    </script>

    <!-- Add this hidden file input -->
    <input type="file" id="advancedProfileImageInput72" accept="image/*" style="display: none;">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ... existing code ...
            // امتیازدهی ستاره‌ای در مدیریت پیشرفته نوبت‌ها
            document.querySelectorAll('.admin-table .rating').forEach(function(ratingDiv) {
                const stars = ratingDiv.querySelectorAll('.rating-star');
                // مقدار اولیه را از data-rating بخوان و ستاره‌ها را رنگی کن
                const initialRating = parseInt(ratingDiv.getAttribute('data-rating')) || 0;
                stars.forEach(function(s, i) {
                    if (i < initialRating) {
                        s.classList.add('fas', 'text-warning');
                        s.classList.remove('far');
                    } else {
                        s.classList.remove('fas', 'text-warning');
                        s.classList.add('far');
                    }
                });
                stars.forEach(function(star, idx) {
                    star.addEventListener('click', function() {
                        const rating = idx + 1;
                        // ستاره‌ها را بر اساس امتیاز رنگی کن
                        stars.forEach(function(s, i) {
                            if (i < rating) {
                                s.classList.add('fas', 'text-warning');
                                s.classList.remove('far');
                            } else {
                                s.classList.remove('fas', 'text-warning');
                                s.classList.add('far');
                            }
                        });
                        // مقدار امتیاز را در data-rating ذخیره کن (در صورت نیاز)
                        ratingDiv.setAttribute('data-rating', rating);
                        // اینجا می‌توانید امتیاز را به سرور ارسال کنید
                    });
                });
            });
        });
    </script>

    <!-- Modal for submitting comment -->
    <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="commentForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="commentModalLabel">ثبت نظر شما</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                    </div>
                    <div class="modal-body">
                        <textarea class="form-control" id="commentText" rows="4" placeholder="نظر خود را بنویسید..." required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">ثبت نظر</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ... سایر کدها ...
            // ثبت نظر با ذخیره و نمایش نظر قبلی برای هر ردیف
            let currentRowForComment = null;
            let currentCommentId = null;
            const commentsMap = {};
            document.querySelectorAll('.action-btn.edit-btn[title="ثبت نظر"]').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    currentRowForComment = btn.closest('tr');
                    currentCommentId = btn.getAttribute('data-comment-id');
                    document.getElementById('commentText').value = commentsMap[currentCommentId] || '';
                    var commentModal = new bootstrap.Modal(document.getElementById('commentModal'));
                    commentModal.show();
                });
            });
            document.getElementById('commentForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const comment = document.getElementById('commentText').value.trim();
                if (currentCommentId) {
                    commentsMap[currentCommentId] = comment;
                    alert('نظر شما با موفقیت ثبت شد!');
                    var commentModal = bootstrap.Modal.getInstance(document.getElementById('commentModal'));
                    commentModal.hide();
                }
            });
        });
    </script>

    <!-- Modal for Change Password -->
    <div class="modal fade" id="changePasswordModal2" tabindex="-1" aria-labelledby="changePasswordModalLabel2" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="cp_config_changePassword.php" method="post" id="changePasswordForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changePasswordModalLabel2">تغییر رمز عبور</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="oldPassword" class="form-label">رمز قبلی</label>
                            <input type="password" class="form-control" name="oldPassword" id="oldPassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">رمز جدید</label>
                            <input type="password" class="form-control" name="newPassword" id="newPassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmNewPassword" class="form-label">تایید رمز جدید</label>
                            <input type="password" class="form-control" name="confirmNewPassword" id="confirmNewPassword" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="submit" class="btn btn-primary">ثبت تغییرات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ... سایر کدها ...
            // هدایت به صفحه ارسال تیکت
            var openSupportTicketBtn = document.getElementById('openSupportTicketBtn');
            if (openSupportTicketBtn) {
                openSupportTicketBtn.addEventListener('click', function() {
                    window.location.href = 'customer_tickets.php';
                });
            }
            // ... سایر کدها ...
        });
        // ... existing code ...
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ... سایر کدها ...
            // فعال‌سازی دکمه چت آنلاین
            var openChatBtn = document.getElementById('openChatBtn');
            var chatToggleBtn = document.getElementById('chatToggleBtn');
            var floatingChatBox = document.getElementById('floatingChatBox');
            var minimizeChat = document.getElementById('minimizeChat');
            var closeChat = document.getElementById('closeChat');
            var chatFooterInput = floatingChatBox.querySelector('.chat-footer input');
            var chatFooterBtn = floatingChatBox.querySelector('.chat-footer button');
            var chatMessages = floatingChatBox.querySelector('.chat-messages');
            var notificationBadge = chatToggleBtn.querySelector('.notification-badge');
            var chatFooterForm = floatingChatBox.querySelector('.chat-footer .input-group');
            var chatOpenedOnce = false;

            // تابع باز کردن چت
            function openChat() {
                floatingChatBox.classList.add('active');
                notificationBadge.style.display = 'none';
                chatOpenedOnce = true;
                setTimeout(function() {
                    chatFooterInput.focus();
                }, 200);
            }
            // تابع بستن چت
            function closeChatBox() {
                floatingChatBox.classList.remove('active');
                // اگر پیام جدیدی نیامده باشد، عدد نمایش داده نشود
                notificationBadge.style.display = 'none';
            }
            // تابع مینیمایز چت
            function minimizeChatBox() {
                floatingChatBox.classList.remove('active');
                // اگر پیام جدیدی نیامده باشد، عدد نمایش داده نشود
                notificationBadge.style.display = 'none';
            }
            // رویداد کلیک دکمه چت آنلاین
            if (openChatBtn) {
                openChatBtn.addEventListener('click', function() {
                    openChat();
                });
            }
            // رویداد کلیک دکمه گوشه صفحه
            if (chatToggleBtn) {
                chatToggleBtn.addEventListener('click', function() {
                    openChat();
                });
            }
            // رویداد بستن چت
            if (closeChat) {
                closeChat.addEventListener('click', function() {
                    closeChatBox();
                });
            }
            // رویداد مینیمایز چت
            if (minimizeChat) {
                minimizeChat.addEventListener('click', function() {
                    minimizeChatBox();
                });
            }
            // ارسال پیام
            function sendMessage() {
                var msg = chatFooterInput.value.trim();
                if (!msg) return;
                // پیام کاربر
                var userMsgDiv = document.createElement('div');
                userMsgDiv.className = 'message user';
                userMsgDiv.innerHTML = '<div class="message-content">' + msg + '</div>' +
                    '<small class="message-time">' + getCurrentTime() + '</small>';
                chatMessages.appendChild(userMsgDiv);
                chatFooterInput.value = '';
                scrollChatToBottom();
                // پاسخ شبیه‌سازی‌شده پشتیبان
                setTimeout(function() {
                    var supportMsgDiv = document.createElement('div');
                    supportMsgDiv.className = 'message support';
                    supportMsgDiv.innerHTML = '<div class="message-content">پیام شما دریافت شد، به زودی پاسخ داده می‌شود.</div>' +
                        '<small class="message-time">' + getCurrentTime() + '</small>';
                    chatMessages.appendChild(supportMsgDiv);
                    scrollChatToBottom();
                    // اگر چت باز نیست، عدد 1 نمایش داده شود
                    if (!floatingChatBox.classList.contains('active')) {
                        notificationBadge.textContent = '1';
                        notificationBadge.style.display = 'flex';
                    }
                }, 1200);
            }
            // رویداد ارسال با دکمه
            chatFooterBtn.addEventListener('click', function(e) {
                e.preventDefault();
                sendMessage();
            });
            // رویداد ارسال با اینتر
            chatFooterInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    sendMessage();
                }
            });
            // اسکرول خودکار به آخر پیام‌ها
            function scrollChatToBottom() {
                floatingChatBox.querySelector('.chat-body').scrollTop = chatMessages.scrollHeight;
            }
            // گرفتن ساعت فعلی
            function getCurrentTime() {
                var now = new Date();
                return now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
            }
            // در ابتدا اگر چت باز نشده، عدد 1 نمایش داده شود
            if (!chatOpenedOnce) {
                notificationBadge.textContent = '1';
                notificationBadge.style.display = 'flex';
            }
        });
        // ... existing code ...
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ... سایر کدها ...
            // رویداد لغو نوبت
            document.querySelectorAll('.action-btn.delete-btn[title="لغو نوبت"]').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    if (confirm('آیا از لغو این نوبت مطمئن هستید؟')) {
                        var row = btn.closest('tr');
                        if (row) row.remove();
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // تابع صفحه‌بندی عمومی برای جداول
            function setupTablePagination(tableId, paginationId, rowsPerPage = 5) {
                const table = document.getElementById(tableId);
                const pagination = document.getElementById(paginationId);
                if (!table || !pagination) return;
                const tbody = table.querySelector('tbody');
                if (!tbody) return;
                let rows = Array.from(tbody.querySelectorAll('tr'));
                let currentPage = 1;
                let totalPages = Math.ceil(rows.length / rowsPerPage);

                function renderPage(page) {
                    // مخفی کردن همه سطرها
                    rows.forEach(row => row.style.display = 'none');
                    // نمایش سطرهای صفحه جاری
                    const start = (page - 1) * rowsPerPage;
                    const end = start + rowsPerPage;
                    rows.slice(start, end).forEach(row => row.style.display = '');
                    // ساخت دکمه‌های صفحه‌بندی
                    pagination.innerHTML = '';
                    if (totalPages > 1) {
                        const prevBtn = document.createElement('button');
                        prevBtn.className = 'btn btn-sm btn-outline-secondary mx-1';
                        prevBtn.textContent = 'قبلی';
                        prevBtn.disabled = page === 1;
                        prevBtn.onclick = function() {
                            if (currentPage > 1) {
                                currentPage--;
                                renderPage(currentPage);
                            }
                        };
                        pagination.appendChild(prevBtn);
                        // شماره صفحات
                        for (let i = 1; i <= totalPages; i++) {
                            const pageBtn = document.createElement('button');
                            pageBtn.className = 'btn btn-sm ' + (i === page ? 'btn-primary' : 'btn-outline-primary') + ' mx-1';
                            pageBtn.textContent = i;
                            pageBtn.onclick = function() {
                                currentPage = i;
                                renderPage(currentPage);
                            };
                            pagination.appendChild(pageBtn);
                        }
                        const nextBtn = document.createElement('button');
                        nextBtn.className = 'btn btn-sm btn-outline-secondary mx-1';
                        nextBtn.textContent = 'بعدی';
                        nextBtn.disabled = page === totalPages;
                        nextBtn.onclick = function() {
                            if (currentPage < totalPages) {
                                currentPage++;
                                renderPage(currentPage);
                            }
                        };
                        pagination.appendChild(nextBtn);
                    }
                }
                renderPage(currentPage);
            }
            // راه‌اندازی صفحه‌بندی برای هر جدول
            setupTablePagination('financial-table', 'financial-pagination', 5);
            setupTablePagination('appointments-table', 'appointments-pagination', 5);
            setupTablePagination('advanced-appointments-table', 'advanced-appointments-pagination', 5);
        });
    </script>

</body>

</html>