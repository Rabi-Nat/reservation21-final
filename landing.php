<!DOCTYPE html>
<html class="no-js" lang="fa" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>    سالن زیبایی و آرایشی بی بی </title>
    <meta name="author" content="Vecuro">
    <meta name="description" content="B.B - Spa Beauty & Wellness Salon HTML5 Template">
    <meta name="keywords" content="beauty, beauty salon, beauty shop, beauty spa, cosmetics, hairdresser, health, lifestyle, massage, salon, spa, spa booking, wellness, wellness template, yoga">
    <meta name="robots" content="INDEX,FOLLOW">
    <!-- Cache Busting -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

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
    <!-- Main Js File -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/landing.js"></script>
    <!-- JavaScript Files -->
    <script src="assets/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/persian-date@1.1.0/dist/persian-date.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
    <!-- Magnific Popup -->
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

    <!-- Persian calendar modal styles -->


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
    Mobile Menu (Included from external file)
==============================-->
    <?php include 'assets/includes/mobile-menu.php'; ?>
    <!--==============================
    Popup Search Box
    ============================== -->
    <div class="popup-search-box d-none d-lg-block  ">
        <button class="searchClose"><i class="fal fa-times"></i></button>
        <form action="#">
            <input type="text" class="border-theme"  placeholder="    جهت جستجو نام سالن یا مدیر سالن را وارد کنید">
            <button type="submit"><i class="fal fa-search"></i></button>
        </form>
    </div>



    <!--==============================
        Sidemenu
    ============================== -->
    <?php include 'assets/includes/landing-sidemenu.php'; ?>
    <!--==============================
    Header Area
    ==============================-->
    <?php include 'assets/includes/landing-header.php'; ?>
    <!--==============================
    Hero Area
    ==============================-->
    <section class="vs-hero-wrapper hero-layout7 position-relative  ">

        <!-- جدول رزرو ثابت بالای اسلایدر -->
        <div class="booking-table-fixed">
            <table style="width:100%; border:none;">
                <tr>
                    <td colspan="2" style="padding-bottom:12px;">
                        <label for="searchName" style="font-weight:600; color:#9a563a; text-align: center;"> رزرو سریع سالن زیبایی</label>
                        <div class="position-relative">
                            <input type="text" id="searchName" class="form-control" placeholder="نام سالن یا مدیر..." style="margin-top:6px;text-align: center; width:100%; border-radius:8px; border:1px solid #e7e5e5; padding:8px 10px; padding-right:35px;">
                            <i class="fal fa-search" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); color:#9a563a;"></i>
                        </div>
                        <div id="searchResults" style="margin-top:4px; font-size:0.95em; color:#333;"></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-bottom:12px;">
                        <label for="serviceSelect" style="font-weight:600; color:#9a563a;"></label>
                        <div class="position-relative">
                            <select id="serviceSelect" class="form-control" style="margin-top:6px;text-align: center; width:100%; border-radius:8px; border:1px solid #e7e5e5; padding:8px 10px; padding-right:35px; background-color: transparent; color: black; cursor: pointer; appearance: none; -webkit-appearance: none; -moz-appearance: none;">
                                <option value="" style="color: #000000; background-color: white;">انتخاب خدمات...</option>
                                <option value="1" style="color: #333; background-color: white;">کوتاهی مو</option>
                                <option value="2" style="color: #333; background-color: white;">رنگ مو</option>
                                <option value="3" style="color: #333; background-color: white;">اصلاح صورت</option>
                                <option value="4" style="color: #333; background-color: white;">ماساژ</option>
                                <option value="5" style="color: #333; background-color: white;">ناخن</option>
                                <option value="6" style="color: #333; background-color: white;">پوست</option>
                            </select>
                            <i class="fal fa-list" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); color:#9a563a; pointer-events: none;"></i>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="width:50%;">
                        <label for="bookingDate" style="font-weight:600; color:#9a563a;"></label>
                        <div class="position-relative">
                            <input type="text" id="bookingDate" class="form-control" placeholder="تاریخ را انتخاب کنید..." readonly style="margin-top:6px;text-align: center;font-size: 12px; width:100%; border-radius:8px; border:1px solid #e7e5e5; padding:8px 10px; padding-right:10px; background:#fff; cursor:pointer;">
                            <!-- calendar icon kept for appearance only (click opens modal) -->
                            <i id="bookingDateOpen" class="fal fa-calendar-alt" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); color:#9a563a; cursor:pointer;"></i>
                        </div>
                    </td>
                    <td style="width:50%; padding-right:10px;">
                        <label for="bookingTime" style="font-weight:600; color:#9a563a;"></label>
                        <div class="position-relative">
                            <input type="text" id="bookingTime" class="form-control time-pick" placeholder="ساعت را انتخاب کنید..." style="margin-top:6px;text-align: center;font-size: 12px; width:100%; border-radius:8px; border:1px solid #e7e5e5; padding:8px 10px; padding-right:35px;">
                            <i class="fal fa-clock" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); color:#9a563a;"></i>
                        </div>
                    </td>
                </tr>

            </table>
            <div style='color:#000; text-align:center; font-style:normal; text-decoration:none; text-transform:none; font-weight:400; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; background-clip:border-box; overflow:visible; top:480px; left:150px; font-family:"DM Sans", sans-serif; -webkit-background-clip:border-box;' class="ls-l ls-hide-desktop ls-hide-tablet ls-html-layer" data-ls="offsetyin:150; durationin:1500; delayin:800; easingin:easeOutQuint; offsetyout:150; durationout:1500; easingout:easeOutQuint;"><a href="appointment.html" class="vs-btn style12">رزرو نوبت</a></div>
        </div>
        <div class="vs-hero-carousel" data-height="850" data-container="1900" data-slidertype="responsive">
            <div class="shape-mockup jump " style="z-index:100;" data-top="77%" data-right="0"><img src="assets/img/icons/flower-1-1.png" alt="شکل"></div>

            <?php include 'assets/includes/slides.php'; ?>

        </div>
    </section>
    <!--==============================
    About Area
    ==============================-->
    <section class=" space-top space-extra-bottom">

        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-6 col-lg-3 col-xl order-0 order-lg-0">
                    <div class="about-avater">
                        <div class="avater mega-hover"><a href="women-salon.html"><img src="assets/img/gallery/w4.jpg" alt="Avater Image"></a></div>
                        <h3 class="name h4"><a href="service.html" class="text-inherit"></br>سالن زیبایی آرایشی بانوان </a></h3>
                    </div>
                </div>
                <div class="col-lg-6 order-2 order-lg-1 col-xl-5 text-center">
                    <div class="mb-30">
                        <span class="sec-subtitle mb-6">رزرو خدمات سالن زیبایی با</span>
                        <h2 class="sec-title">بی بی  </h2>
                        <p class="mb-30 pb-lg-3" style="font-family: Arial, Helvetica, sans-serif;">جهت مشاهده و رزرو بر روی سالن منتخب کلیک کنید</p>
                        <!-- <a href="about.html" class="vs-btn style12">بیشتر بدانید</a> -->
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-xl order-1 order-lg-2">
                    <div class="about-avater">
                        <div class="avater mega-hover"><a href="men-salon.html"><img src="assets/img/gallery/m2.jpg" alt="Avater Image"></a></div>
                        <h3 class="name h4"><a href="service.html" class="text-inherit"></br>سالن آرایشی آقایان </a></h3>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--==============================
    Brand Partners
    ==============================-->
    <div class=" ">
        <div class="container">
            <h5>سالن های پیشنهادی کاربران</h5>
            <div class="brand-inner1">
                <div class="row vs-carousel text-center salon-slider"
                    data-slide-show="5"
                    data-lg-slide-show="4"
                    data-md-slide-show="3"
                    data-sm-slide-show="2"
                    data-xs-slide-show="2"
                    data-autoplay="true"
                    data-autoplay-speed="3000"
                    data-arrows="true"
                    data-dots="true"
                    data-infinite="true"
                    data-pause-on-hover="true">
                    <div class="col-auto">
                        <div class="salon-card">
                            <a href="salon-page.html"><img src="assets/img/gallery/salon1.jpg" alt="brand"></a>
                            <h4 class="salon-name">سالن زیبایی فرید</h4>
                            <p class="salon-address"><i class="fas fa-map-marker-alt"></i> تهران، خیابان ولیعصر، پلاک 123</p>
                            <div class="salon-services">
                                <span class="service-tag">کوتاهی مو</span>
                                <span class="service-tag">رنگ مو</span>
                                <span class="service-tag">اصلاح صورت</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="salon-card">
                            <img src="assets/img/gallery/team-1.png" alt="brand">
                            <h4 class="salon-name">سالن زیبایی گل</h4>
                            <p class="salon-address"><i class="fas fa-map-marker-alt"></i> تهران، خیابان شریعتی، پلاک 45</p>
                            <div class="salon-services">
                                <span class="service-tag">ماساژ</span>
                                <span class="service-tag">ناخن</span>
                                <span class="service-tag">پوست</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="salon-card">
                            <img src="assets/img/gallery/team-2.png" alt="brand">
                            <h4 class="salon-name">سالن زیبایی ماه</h4>
                            <p class="salon-address"><i class="fas fa-map-marker-alt"></i> تهران، خیابان پاسداران، پلاک 78</p>
                            <div class="salon-services">
                                <span class="service-tag">کراتین</span>
                                <span class="service-tag">رنگ مو</span>
                                <span class="service-tag">اصلاح صورت</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="salon-card">
                            <img src="assets/img/gallery/team-3.png" alt="brand">
                            <h4 class="salon-name">سالن زیبایی ستاره</h4>
                            <p class="salon-address"><i class="fas fa-map-marker-alt"></i> تهران، خیابان فرشته، پلاک 90</p>
                            <div class="salon-services">
                                <span class="service-tag">ماساژ</span>
                                <span class="service-tag">کوتاهی مو</span>
                                <span class="service-tag">ناخن</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="salon-card">
                            <img src="assets/img/gallery/team-4.png" alt="brand">
                            <h4 class="salon-name">سالن زیبایی آفتاب</h4>
                            <p class="salon-address"><i class="fas fa-map-marker-alt"></i> تهران، خیابان نیاوران، پلاک 34</p>
                            <div class="salon-services">
                                <span class="service-tag">پوست</span>
                                <span class="service-tag">رنگ مو</span>
                                <span class="service-tag">ماساژ</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="salon-card">
                            <img src="assets/img/gallery/salonmen.jpg" alt="brand">
                            <h4 class="salon-name">سالن زیبایی بهار</h4>
                            <p class="salon-address"><i class="fas fa-map-marker-alt"></i> تهران، خیابان گاندی، پلاک 56</p>
                            <div class="salon-services">
                                <span class="service-tag">کوتاهی مو</span>
                                <span class="service-tag">ناخن</span>
                                <span class="service-tag">پوست</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="salon-card">
                            <img src="assets/img/gallery/w3.jpg" alt="brand">
                            <h4 class="salon-name">سالن زیبایی پاییز</h4>
                            <p class="salon-address"><i class="fas fa-map-marker-alt"></i> تهران، خیابان سعادت آباد، پلاک 89</p>
                            <div class="salon-services">
                                <span class="service-tag">ماساژ</span>
                                <span class="service-tag">کراتین</span>
                                <span class="service-tag">رنگ مو</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!--==============================
    Brand Partners
    ==============================-->
    <div class=" ">
        <div class="container">
            <h5>سالن های پربازدید </h5>
            <div class="brand-inner1">
                <div class="row vs-carousel text-center salon-slider"
                    data-slide-show="5"
                    data-lg-slide-show="4"
                    data-md-slide-show="3"
                    data-sm-slide-show="2"
                    data-xs-slide-show="2"
                    data-autoplay="true"
                    data-autoplay-speed="3000"
                    data-arrows="true"
                    data-dots="true"
                    data-infinite="true"
                    data-pause-on-hover="true">
                    <div class="col-auto">
                        <div class="salon-card">
                            <img src="assets/img/gallery/salon1.jpg" alt="brand">
                            <h4 class="salon-name">سالن زیبایی فرید</h4>
                            <p class="salon-address"><i class="fas fa-map-marker-alt"></i> تهران، خیابان ولیعصر، پلاک 123</p>
                            <div class="salon-services">
                                <span class="service-tag">کوتاهی مو</span>
                                <span class="service-tag">رنگ مو</span>
                                <span class="service-tag">اصلاح صورت</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="salon-card">
                            <img src="assets/img/gallery/team-1.png" alt="brand">
                            <h4 class="salon-name">سالن زیبایی گل</h4>
                            <p class="salon-address"><i class="fas fa-map-marker-alt"></i> تهران، خیابان شریعتی، پلاک 45</p>
                            <div class="salon-services">
                                <span class="service-tag">ماساژ</span>
                                <span class="service-tag">ناخن</span>
                                <span class="service-tag">پوست</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="salon-card">
                            <img src="assets/img/gallery/team-2.png" alt="brand">
                            <h4 class="salon-name">سالن زیبایی ماه</h4>
                            <p class="salon-address"><i class="fas fa-map-marker-alt"></i> تهران، خیابان پاسداران، پلاک 78</p>
                            <div class="salon-services">
                                <span class="service-tag">کراتین</span>
                                <span class="service-tag">رنگ مو</span>
                                <span class="service-tag">اصلاح صورت</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="salon-card">
                            <img src="assets/img/gallery/team-3.png" alt="brand">
                            <h4 class="salon-name">سالن زیبایی ستاره</h4>
                            <p class="salon-address"><i class="fas fa-map-marker-alt"></i> تهران، خیابان فرشته، پلاک 90</p>
                            <div class="salon-services">
                                <span class="service-tag">ماساژ</span>
                                <span class="service-tag">کوتاهی مو</span>
                                <span class="service-tag">ناخن</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="salon-card">
                            <img src="assets/img/gallery/team-4.png" alt="brand">
                            <h4 class="salon-name">سالن زیبایی آفتاب</h4>
                            <p class="salon-address"><i class="fas fa-map-marker-alt"></i> تهران، خیابان نیاوران، پلاک 34</p>
                            <div class="salon-services">
                                <span class="service-tag">پوست</span>
                                <span class="service-tag">رنگ مو</span>
                                <span class="service-tag">ماساژ</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="salon-card">
                            <img src="assets/img/gallery/salonmen.jpg" alt="brand">
                            <h4 class="salon-name">سالن زیبایی بهار</h4>
                            <p class="salon-address"><i class="fas fa-map-marker-alt"></i> تهران، خیابان گاندی، پلاک 56</p>
                            <div class="salon-services">
                                <span class="service-tag">کوتاهی مو</span>
                                <span class="service-tag">ناخن</span>
                                <span class="service-tag">پوست</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="salon-card">
                            <img src="assets/img/gallery/w3.jpg" alt="brand">
                            <h4 class="salon-name">سالن زیبایی پاییز</h4>
                            <p class="salon-address"><i class="fas fa-map-marker-alt"></i> تهران، خیابان سعادت آباد، پلاک 89</p>
                            <div class="salon-services">
                                <span class="service-tag">ماساژ</span>
                                <span class="service-tag">کراتین</span>
                                <span class="service-tag">رنگ مو</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <section class="space">
        <div class="container">
            <div class="row gx-70">
                <div class="col-lg-6 mb-40 mb-lg-0 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="text-center text-lg-start">

                        <h2 class="sec-title3 h2 text-uppercase " style="text-align: right;"> نزدیک ترین <span class="text-theme">سالن به شما و مسیریابی </span></h2>
                        <div class="col-xxl-10 pb-xl-3">
                            <p class="pe-xxl-4" style="text-align: right;"> جهت سهولت برای یافتن نزدیک ترین سالن آرایشی به خود و همچنین مسیریابی لازم ، میتوانید با فعال نمودن موقعیت مکانی خود و یا جسجتوی نام سالن براحتی از نقشه ی مورد نظر استفاده نمایید.
                            </p>
                        </div>
                    </div>
                    <form action="mail.php" method="POST" class="ajax-contact form-style6">
                        <div class="form-group">
                            <input type="text" name="name" id="name" placeholder="نام سالن یا مدیرسالن">
                        </div>

                        <div class="form-group">
                            <select name="subject" id="subject">
                                <option value="نزدیک ترین"> نزدیک ترین</option>
                                <option value="نزدیک ترین "> پشنهادی کاربران </option>
                                <option value="پربازدید">پربازدید ترین ها</option>
                                <option value="تخفیف "> تخفیف دار </option>


                            </select>
                        </div>

                        <button class="vs-btn" type="submit">جستجو بر روی نقشه </button>

                    </form>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="map">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d8640.015075048146!2d51.38240193087461!3d35.79136569944656!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1z2LPYp9mE2YYg2KLYsdin24zYtA!5e0!3m2!1sen!2s!4v1750577048073!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

                    </div>
                    <div class="contact-table">
                        <div class="tr">
                            <div class="tb-col">
                                <span class="th">آدرس :</span>
                                <span class="td"> تهران تجریش</span>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>



    <!--==============================
    Video Area
    ==============================-->
    <section class=" space">
        <div class="video-bg-shape"></div>
        <div class="shape-mockup ani-moving-y d-none d-xxl-block" data-right="11%" data-top="17%"><img src="assets/img/icons/leaf-1-1.png" alt="Leaf Image"></div>
        <div class="shape-mockup ani-moving-x d-none d-xxl-block" data-left="14%" data-bottom="-2%"><img src="assets/img/icons/flower-1-1.png" alt="Leaf Image"></div>
        <div class="container z-index-common">
            <div class="title-area text-center">
                <span class="sec-subtitle">ویدیو تبلیغاتی</span>
                <h2 class="sec-title">زیبایی در یک نگاه </h2>
            </div>
            <!-- ویدیو تبلیغاتی -->
            <!-- ویدیو تبلیغاتی -->
            <div class="video-box-inner bg-white">
                <div class="video-box position-relative mega-hover" style="transform: scale(0.75); margin: auto;">
                    <div class="video-container" style="position: relative;">
                        <video id="promoVideo" muted loop playsinline style="width: 100%; height: auto; border-radius: 8px;">
                            <source src="assets/img/video/demo.mp4" type="video/mp4">
                            مرورگر شما از ویدیو پشتیبانی نمی‌کند.
                        </video>
                        <div class="video-controls" style="position: absolute; bottom: 10px; left: 0; right: 0; display: flex; justify-content: center; align-items: center; padding: 10px; background: linear-gradient(to top, rgba(0,0,0,0.7), transparent); border-radius: 0 0 8px 8px;">
                            <button class="play-pause-btn" style="background: rgba(255,255,255,0.2); border: none; border-radius: 50%; width: 40px; height: 40px; margin: 0 5px; cursor: pointer;">
                                <i class="fas fa-play" style="color: white;"></i>
                            </button>
                            <input type="range" class="video-progress" min="0" max="100" value="0" style="flex-grow: 1; margin: 0 10px; height: 5px; cursor: pointer; direction: ltr;">
                            <button class="volume-btn" style="background: rgba(255,255,255,0.2); border: none; border-radius: 50%; width: 40px; height: 40px; margin: 0 5px; cursor: pointer;">
                                <i class="fas fa-volume-mute" style="color: white;"></i>
                            </button>
                            <button class="fullscreen-btn" style="background: rgba(255,255,255,0.2); border: none; border-radius: 50%; width: 40px; height: 40px; margin: 0 5px; cursor: pointer;">
                                <i class="fas fa-expand" style="color: white;"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const video = document.getElementById('promoVideo');
                    const playPauseBtn = document.querySelector('.play-pause-btn');
                    const volumeBtn = document.querySelector('.volume-btn');
                    const fullscreenBtn = document.querySelector('.fullscreen-btn');
                    const progressBar = document.querySelector('.video-progress');
                    const playIcon = playPauseBtn.querySelector('i');
                    const volumeIcon = volumeBtn.querySelector('i');
                    const fullscreenIcon = fullscreenBtn.querySelector('i');

                    // تنظیم اولیه: صدا قطع و آیکون متناسب
                    video.muted = true;
                    volumeIcon.className = 'fas fa-volume-mute';

                    // Play/Pause functionality
                    playPauseBtn.addEventListener('click', function() {
                        if (video.paused) {
                            video.play();
                            playIcon.className = 'fas fa-pause';
                        } else {
                            video.pause();
                            playIcon.className = 'fas fa-play';
                        }
                    });

                    // Volume control
                    volumeBtn.addEventListener('click', function() {
                        if (video.muted) {
                            video.muted = false;
                            volumeIcon.className = 'fas fa-volume-up';
                        } else {
                            video.muted = true;
                            volumeIcon.className = 'fas fa-volume-mute';
                        }
                    });

                    // Fullscreen functionality
                    fullscreenBtn.addEventListener('click', function() {
                        if (!document.fullscreenElement) {
                            video.requestFullscreen().catch(err => {
                                console.log(`Error attempting to enable fullscreen: ${err.message}`);
                            });
                            fullscreenIcon.className = 'fas fa-compress';
                        } else {
                            document.exitFullscreen();
                            fullscreenIcon.className = 'fas fa-expand';
                        }
                    });

                    // Update progress bar (راست به چپ برای فارسی)
                    video.addEventListener('timeupdate', function() {
                        const percent = (video.currentTime / video.duration) * 100;
                        progressBar.value = percent;

                        // تغییر جهت پیشرفت بار از چپ به راست
                        progressBar.style.background = `linear-gradient(to right, #9a563a 0%, #9a563a ${percent}%, rgba(255,255,255,0.3) ${percent}%, rgba(255,255,255,0.3) 100%)`;
                    });

                    // Seek functionality
                    progressBar.addEventListener('input', function() {
                        const seekTime = (progressBar.value / 100) * video.duration;
                        video.currentTime = seekTime;

                        // به روزرسانی رنگ نوار پیشرفت هنگام تغییر دستی
                        progressBar.style.background = `linear-gradient(to right, #9a563a 0%, #9a563a ${this.value}%, rgba(255,255,255,0.3) ${this.value}%, rgba(255,255,255,0.3) 100%)`;
                    });

                    // Auto-play when in view
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                video.play().catch(function(error) {
                                    console.log("Auto-play prevented:", error);
                                });
                                playIcon.className = 'fas fa-pause';
                            } else {
                                video.pause();
                                playIcon.className = 'fas fa-play';
                            }
                        });
                    }, {
                        threshold: 0.5
                    });

                    observer.observe(video);
                });
            </script>
            <div class="counter-inner1">
                <!-- Counter Area -->
                <div class="row justify-content-around text-center text-lg-start">
                    <div class="col-sm-6 col-lg-auto">
                        <div class="counter-media">
                            <h2 class="sec-title text-theme">1990</h2>
                            <p class="counter-text">تعداد سالن ها </p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-auto">
                        <div class="counter-media">
                            <h2 class="sec-title text-theme">2398</h2>
                            <p class="counter-text">رزرو های موفق </p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-auto">
                        <div class="counter-media">
                            <h2 class="sec-title text-theme">500+</h2>
                            <p class="counter-text">رزرو سالن زیبایی عروس و داماد </p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-auto">
                        <div class="counter-media">
                            <h2 class="sec-title text-theme">9081</h2>
                            <p class="counter-text">اعضای فعال</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
    <!--==============================
    Gallery Area
    ==============================-->
    <section class=" space-extra-bottom" id="gallery">
        <div class="container">
            <div class="row gx-30">
                <div class="mb-30 text-center text-lg-start col-lg-4 align-self-center">
                    <div class="title-area mb-30 mb-lg-0">
                        <span class="sec-subtitle" style="text-align: right;">گالری</span>
                        <h2 class="sec-title mb-0" style="text-align: right;"> نمونه کارهای آرایشی و زیبایی</h2>
                    </div>
                </div>
                <div class="mb-30 col-sm-6 col-lg-4">
                    <div class="gallery-box">
                        <img src="assets/img/gallery/gallery-4.jpg" alt="Gallery Image" class="w-100">
                        <a href="assets/img/gallery/gallery-4.jpg" class="popup-image gal-btn"><i class="far fa-plus"></i></a>
                    </div>
                </div>
                <div class="mb-30 col-sm-6 col-lg-4">
                    <div class="gallery-box">
                        <img src="assets/img/gallery/gallery-5.jpg" alt="Gallery Image" class="w-100">
                        <a href="assets/img/gallery/gallery-1-2.jpg" class="popup-image gal-btn"><i class="far fa-plus"></i></a>
                    </div>
                </div>
                <div class="mb-30  col-lg-4">
                    <div class="gallery-box">
                        <img src="assets/img/gallery/gallery-6.jpg" alt="Gallery Image" class="w-100">
                        <a href="assets/img/gallery/gallery-1-3.jpg" class="popup-image gal-btn"><i class="far fa-plus"></i></a>
                    </div>
                </div>
                <div class="mb-30 col-sm-6 col-lg-4">
                    <div class="gallery-box">
                        <img src="assets/img/gallery/gallery-7.jpg" alt="Gallery Image" class="w-100">
                        <a href="assets/img/gallery/gallery-1-4.jpg" class="popup-image gal-btn"><i class="far fa-plus"></i></a>
                    </div>
                </div>
                <div class="mb-30 col-sm-6 col-lg-4">
                    <div class="gallery-box">
                        <img src="assets/img/gallery/gallery-8.jpg" alt="Gallery Image" class="w-100">
                        <a href="assets/img/gallery/gallery-1-5.jpg" class="popup-image gal-btn"><i class="far fa-plus"></i></a>
                    </div>
                </div>
                <div class="col-12 text-center mb-30 pt-lg-3">
                    <a href="" class="vs-btn style12">مشاهده بیشتر<i class="ps-2 pe-0 far fa-arrow-right"></i> </a>
                </div>
            </div>
        </div>
    </section>
    <!--==============================
    About Features
    ==============================-->
    <section class=" space-top space-extra-bottom">
        <div class="container">
            <div class="row gx-60">
                <div class="col-lg-6">
                    <div class="img-box9">
                        <div class="img-1"><img src="assets/img/gallery/r1.JPG" alt="About Image"></div>
                        <!-- <div class="img-2"><img src="assets/img/about/about-9-2.jpg" alt="About Image"></div> -->
                    </div>
                </div>
                <div class="col-lg-6 align-self-center">
                    <span class="sec-subtitle">داستان ما</span>
                    <h2 class="sec-title">تیم رزروآنلاین در راستای راحتی مشتریان و صرفه جویی در وقت عزیزان</h2>
                    <p class="mb-30 pb-2">> تیم رزرو آنلاین جهت راحتی و صرفه جویی در زمان و ارایه ی گزینه های متنوع و کارآمد از آرایشگران اقدام به راه اندازی سیستم نوبت گیری در کمترین زمان نمود تا علاوه بر معرفی استعدادهای و هنرمندان آرایشگر فضای مناسب جهت معرفی سالن ها به مشتریان را ایجاد نموده و همچین مشتریان به سهولت با ارایه نظرات و دیدگاه ها از خدمات سالن ها کمکی شایان در این صنعت را فراهم نماید</p>
                    <div class="media-style7">
                        <div class="media-icon"><img src="assets/img/icons/sr-7-4.svg" alt="icon"></div>
                        <div class="media-body">
                            <h3 class="media-title h5"> برندینگ سالن های زیبایی</h3>
                            <p class="media-text">با معرفی مدیران و سالن داران در جهت ارایه خدمات برتر و برندینگ ، زمینه ی شناخت مشتریان را فراهم مینمایمم</p>
                        </div>
                    </div>
                    <div class="media-style7">
                        <div class="media-icon"><img src="assets/img/icons/sr-7-7.svg" alt="icon"></div>
                        <div class="media-body">
                            <h3 class="media-title h5">پشتیبانی ویژه</h3>
                            <p class="media-text">در راستای صرفه جویی در وقت و هزینه ی مشتریان تیم رزروآنلاین تمام تلاش خود را جهت راحتی و سرعت بخشیدن به نوبت گیری و امنتیت مشتریان انجام میدهد</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--==============================
    reservation Form Area
    ==============================-->
    <section class=" space" data-overlay="black" data-opacity="7">
        <div class="parallax" data-parallax-image="assets/img/bg/appoin-bg-8-1.jpg"></div>
        <div class="container">
            <div class="row text-center justify-content-center">
                <div class="col-xl-6 col-lg-8 col-md-8">
                    <div class="title-area">
                        <span class="sec-subtitle text-white">رزرو آسان</span>
                        <h2 class="h2 mt-n1 text-white">منتظر حضور شما هستیم</h2>
                    </div>
                </div>
            </div>
            <form action="#" class="form-style8">
                <div class="row gx-4 gy-gx">
                    <div class="col-md-6 col-lg form-group">
                        <input type="text" class="form-control" placeholder="نام مدیر یا سالن را وارد کنید">
                        <i class="far fa-user-alt"></i>
                    </div>
                    <div class="col-md-6 col-lg form-group">
                        <label for="serviceSelect2" style="font-weight:600; color:#9a563a;"></label>
                        <div class="position-relative">
                            <select id="serviceSelect2" class="form-control" style="margin-top:6px;text-align: center; width:100%; border-radius:8px; border:1px solid #e7e5e5; padding:8px 10px; padding-right:35px; background-color: transparent; color: white; cursor: pointer; appearance: none; -webkit-appearance: none; -moz-appearance: none;">
                                <option value="" style="color: #666; background-color: white;">انتخاب خدمات...</option>
                                <option value="1" style="color: #000; background-color: white;">کوتاهی مو</option>
                                <option value="2" style="color: #000; background-color: white;">رنگ مو</option>
                                <option value="3" style="color: #000; background-color: white;">اصلاح صورت</option>
                                <option value="4" style="color: #000; background-color: white;">ماساژ</option>
                                <option value="5" style="color: #000; background-color: white;">ناخن</option>
                                <option value="6" style="color: #000; background-color: white;">پوست</option>
                            </select>
                            <i class="fal fa-list" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); color:#9a563a; pointer-events: none;"></i>
                        </div>
                    </div>
                    <!-- تغییرات در این بخش -->
                    <div class="col-md-6 col-lg form-group">
                        <input type="text" id="bookingDateForm" autocomplete="on" class="form-control" placeholder="تاریخ را انتخاب کنید" style="cursor:pointer;">
                        <i id="bookingDateFormOpen" class="fal fa-calendar-alt"></i>
                    </div>

                    <div class="col-md-6 col-lg form-group ">
                        <input type="text" id="bookingTime2" autocomplete="on" class="form-control time-pick" placeholder="ساعت را انتخاب کنید">
                        <i class="fal fa-clock" ></i>
                    </div>
                    <div class="col-md-6 col-lg-auto form-group">
                        <button class="vs-btn style12 w-100"><i class="fal fa-clipboard-list"></i> رزرو سریع</button>
                    </div>
                </div>
            </form>
        </div>
    </section><!--==============================
  Services Area
  ==============================-->
    <section class="bg-theme space-top space-extra-bottom">
        <div class="container">
            <h3 style="margin-right: 20px; margin-top: 0;"> فروشگاه محصولات زیبایی </h3>
            <div class="row gx-40 text-center text-xl-start vs-carousel" data-slide-show="4" data-lg-slide-show="3" data-md-slide-show="2" data-sm-slide-show="2">
                <div class="col-sm-6 col-xl-3">
                    <div class="vs-service service-style6">
                        <div class="service-icon"><img src="assets/img/icon/sr-i-6-1.svg" alt="icon"></div>
                        <h3 class="service-name h4 text-white"><a href="service-details.html" class="text-inherit">چشم و سایه</a></h3>
                        <p class="service-text text-light-white">شبکه اجتماعی مورد علاقه خود را انتخاب کنید و آیکون‌های ما را با دوستان خود به اشتراک بگذارید.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="vs-service service-style6">
                        <div class="service-icon"><img src="assets/img/icon/sr-i-6-2.svg" alt="icon"></div>
                        <h3 class="service-name h4 text-white"><a href="service-details.html" class="text-inherit">حمام حرارتی</a></h3>
                        <p class="service-text text-light-white">شبکه اجتماعی مورد علاقه خود را انتخاب کنید و آیکون‌های ما را با دوستان خود به اشتراک بگذارید.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="vs-service service-style6">
                        <div class="service-icon"><img src="assets/img/icon/sr-i-6-3.svg" alt="icon"></div>
                        <h3 class="service-name h4 text-white"><a href="service-details.html" class="text-inherit">آرایشگاه و زیبایی</a></h3>
                        <p class="service-text text-light-white">شبکه اجتماعی مورد علاقه خود را انتخاب کنید و آیکون‌های ما را با دوستان خود به اشتراک بگذارید.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="vs-service service-style6">
                        <div class="service-icon"><img src="assets/img/icon/sr-i-6-4.svg" alt="icon"></div>
                        <h3 class="service-name h4 text-white"><a href="service-details.html" class="text-inherit">ماساژ ورزشی</a></h3>
                        <p class="service-text text-light-white">شبکه اجتماعی مورد علاقه خود را انتخاب کنید و آیکون‌های ما را با دوستان خود به اشتراک بگذارید.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="vs-service service-style6">
                        <div class="service-icon"><img src="assets/img/icon/sr-i-6-5.svg" alt="icon"></div>
                        <h3 class="service-name h4 text-white"><a href="service-details.html" class="text-inherit">ماساژ سنگ داغ</a></h3>
                        <p class="service-text text-light-white">شبکه اجتماعی مورد علاقه خود را انتخاب کنید و آیکون‌های ما را با دوستان خود به اشتراک بگذارید.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
 

    <a href="#" class="scrollToTop scroll-btn"><i class="far fa-arrow-up"></i></a>
    <!--==============================
			Footer Area
	==============================-->
    <?php include 'assets/includes/footer.php'; ?>

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
                <thead><tr><th>ش</th><th>ی</th><th>د</th><th>س</th><th>چ</th><th>پ</th><th style="color:red">ج</th></tr></thead>
                <tbody id="psDaysBody"></tbody>
            </table>
            <div class="ps-footer"><div>امروز: <span id="psToday">—</span></div><div>انتخاب: <span id="psSelected">—</span></div></div>
        </div>
    </div>

    <!-- JavaScript Files -->
    <script src="assets/js/script.js"></script>
    <!-- Main Js File -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/landing.js"></script>


</body>
</html>