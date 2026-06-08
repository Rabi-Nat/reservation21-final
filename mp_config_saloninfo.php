<?php
// mp = manager profile
session_start();
require_once 'database.php';
//$conn = db_connect();

// Session validation
if (empty($_SESSION['manager_id'])) {
    $_SESSION['login_error'] = "ابتدا باید به سایت ورود کنید";
    //$conn = null;
    mysqli_close($conn);
    header("Location: login.php");
    exit();
}

$manager_id  = $_SESSION['manager_id'] ?? "";

/* 
// validate token
if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token'])) {
    header('Location: login.php');
    exit();
} 
*/


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    /*       
// Validate usernameformat (example: alphanumeric + underscore)
if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $customerUserName)) {
die("نام کاربری: 3-20 کاراکتر, فقط حروف, اعداد, _");
}
*/

    // sanitize - دریافت داده‌ها و پاکسازی
    $salonType = test_input($_POST["salonType"]);
    $salonName = test_input($_POST["salonName"]);
    $province = test_input($_POST["province"]);
    $city = test_input($_POST["city"]);
    $detailedAddress = test_input($_POST["detailedAddress"]);
    $workPhoneNumber = test_input($_POST["workPhoneNumber"]) ?? "";

    if (
        empty($salonType) || empty($salonName) || empty($province) || empty($city)
        || empty($detailedAddress)
    ) {
        $_SESSION['mp_emptyfield_error'] = "فیلدهای ستاره دار باید تکمیل شوند";
        $conn = null;
        //mysqli_close($conn);
        header("Location: manager_profile.php#salon_info");
        exit();
    }

    if (isset($workPhoneNumber) && !empty($workPhoneNumber) && !preg_match('/^0\d{10}$/', $workPhoneNumber)) {
        $_SESSION['mp_phone_error'] = "فرمت شماره تلفن نامعتبر است";
        //$conn = null;
        mysqli_close($conn);
        header("Location: manager_profile.php#salon_info");
        exit();
    }

    // insert data into the table: location
    $query1 = "SELECT * from location WHERE manager_info_id = ?";
    $stmt1 = mysqli_prepare($conn, $query1);
    mysqli_stmt_bind_param($stmt1, "i", $manager_id);
    mysqli_stmt_execute($stmt1);
    $result1 = mysqli_stmt_get_result($stmt1);

    if (mysqli_num_rows($result1) == 1) {
        $query2 = "UPDATE location SET province=?, city=?, full_address=?, location_tel=?
            WHERE manager_info_id = ?";
        $stmt2 = mysqli_prepare($conn, $query2);
        mysqli_stmt_bind_param(
            $stmt2,
            "ssssi",
            $province,
            $city,
            $detailedAddress,
            $workPhoneNumber,
            $manager_id
        );
        mysqli_stmt_execute($stmt2);

        // re-fetch location_id
        $query3 = "SELECT * from location WHERE manager_info_id = ?";
        $stmt3 = mysqli_prepare($conn, $query3);
        mysqli_stmt_bind_param($stmt3, "i", $manager_id);
        mysqli_stmt_execute($stmt3);
        $result3 = mysqli_stmt_get_result($stmt3);

        $row3 = mysqli_fetch_array($result3);
        $location_id = $row3['location_id'];
        $_SESSION['location_id'] = $location_id;
    } else {
        $query2 = "INSERT INTO location (province, city, full_address, location_tel, manager_info_id) 
                    VALUES (?,?,?,?,?)";
        $stmt2 = mysqli_prepare($conn, $query2);
        mysqli_stmt_bind_param(
            $stmt2,
            "ssssi",
            $province,
            $city,
            $detailedAddress,
            $workPhoneNumber,
            $manager_id
        );
        mysqli_stmt_execute($stmt2);

        $location_id = mysqli_insert_id($conn);
        $_SESSION['location_id'] = $location_id;
    }


    // insert data into the table: salon  (should be after the table: location)
    $query4 = "SELECT * from salon WHERE manager_info_id = ?";
    $stmt4 = mysqli_prepare($conn, $query4);
    mysqli_stmt_bind_param($stmt4, "i", $manager_id);
    mysqli_stmt_execute($stmt4);
    $result4 = mysqli_stmt_get_result($stmt4);

    if (mysqli_num_rows($result4) == 1) {
        $query5 = "UPDATE salon SET salon_name=?, salon_gender=?, location_id=?
            WHERE manager_info_id = ?";
        $stmt5 = mysqli_prepare($conn, $query5);
        mysqli_stmt_bind_param(
            $stmt5,
            "ssii",
            $salonName,
            $salonType,
            $location_id,
            $manager_id
        );
        mysqli_stmt_execute($stmt5);

        // re-fetch salon_id
        $query6 = "SELECT * from salon WHERE manager_info_id = ?";
        $stmt6 = mysqli_prepare($conn, $query6);
        mysqli_stmt_bind_param($stmt6, "i", $manager_id);
        mysqli_stmt_execute($stmt6);
        $result6 = mysqli_stmt_get_result($stmt6);

        $row6 = mysqli_fetch_array($result6);
        $_SESSION['salon_id'] = $row6['salon_id'];
    } else {
        $query5 = "INSERT INTO salon (salon_name, salon_gender, manager_info_id, location_id) 
                    VALUES (?,?,?,?)";
        $stmt5 = mysqli_prepare($conn, $query5);
        mysqli_stmt_bind_param(
            $stmt5,
            "ssii",
            $salonName,
            $salonType,
            $manager_id,
            $location_id
        );
        mysqli_stmt_execute($stmt5);

        $salon_id = mysqli_insert_id($conn);
        $_SESSION['salon_id'] = $salon_id;
    }


    if ($_SESSION['location_id'] && $_SESSION['salon_id']) {
        $_SESSION['mp_confirm'] = "اطلاعات با موفقیت ذخیره شد";
        //mysqli_stmt_close($stmt2);
        //mysqli_stmt_close($stmt5);
        //mysqli_close($conn);
        $conn = null;
        header("Location: manager_profile.php#salon_info");
        exit();
    } else {
        $_SESSION['mp_not_confirm'] = "خطا در به‌روزرسانی اطلاعات";
        //mysqli_stmt_close($stmt2);
        //mysqli_stmt_close($stmt5);
        //$conn = null;
        mysqli_close($conn);
        header("Location: manager_profile.php#salon_info");
        exit();
    }
}

// soft clean
if (isset($_POST['delete_saloninfo']) && $_POST['delete_saloninfo'] == '1') {
    $query1 = "UPDATE location SET province = '', city = '',full_address = '', 
                location_tel = '', map_url = '' 
                WHERE location_id = ?";

    $query2 = "UPDATE salon SET salon_name = '', salon_gender = '', 
                                manager_info_id = ?, location_id = ? 
                WHERE salon_id = ?";

    // برای سازگاری مقدار ایمیل رو نول بگذاریم یا رشته خالی؛ اینجا رشته خالی میذاریم
    $stmt1 = mysqli_prepare($conn, $query1);
    $stmt2 = mysqli_prepare($conn, $query2);

    mysqli_stmt_bind_param($stmt1, 'i', $_SESSION['location_id']);
    mysqli_stmt_bind_param($stmt2, 'iii', $manager_id, $_SESSION['location_id'], $_SESSION['salon_id']);


    if (mysqli_stmt_execute($stmt1) && mysqli_stmt_execute($stmt2)) {
        $_SESSION['mp_confirm'] = 'اطلاعات سالن پاک شد.';
    } else {
        $_SESSION['mp_not_confirm'] = 'خطا در پاک کردن اطلاعات.';
    }
    //mysqli_stmt_close($stmt1);
    //mysqli_stmt_close($stmt2);
    //$conn = null;
    mysqli_close($conn);
    header('Location: manager_profile.php#salon_info');
    exit();
}
