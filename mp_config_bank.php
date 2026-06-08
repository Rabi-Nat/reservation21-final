<?php
session_start();
require_once 'database.php';
//$conn = db_connect();

// Session validation
if (empty($_SESSION['manager_id'])) {
    $_SESSION['login_error'] = "ابتدا باید به سایت ورود کنید";
    mysqli_close($conn);
    //$conn = null;
    header("Location: login.php");
    exit();
}

$manager_id  = $_SESSION['manager_id'] ?? "";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    /*
// Validate usernameformat (example: alphanumeric + underscore)
if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $customerUserName)) {
die("نام کاربری: 3-20 کاراکتر, فقط حروف, اعداد, _");
}
*/

    // sanitize - دریافت داده‌ها و پاکسازی
    $bankName = test_input($_POST["bankName"]);
    $cardNumber = test_input($_POST["cardNumber"]);
    $shebaNumber = test_input($_POST["shebaNumber"]);


    if (empty($bankName) || empty($cardNumber) || empty($shebaNumber)) {
        $_SESSION['mp_emptyfield_error'] = "فیلدهای ستاره دار باید تکمیل شوند";
        mysqli_close($conn);
        //$conn = null;
        header("Location: manager_profile.php#bank_info");
        exit();
    }


    // validate card number
    if (!preg_match('/^\d{16}$/', $cardNumber)) {
        $_SESSION['mp_cardNumber_error'] = " شماره کارت نامعتبر است";
        mysqli_close($conn);
        //$conn = null;
        header("Location: manager_profile.php#bank_info");
        exit();
    }

    // اعتبارسنجی شماره شبا
    if (!preg_match('/^\d{24}$/', $shebaNumber)) {
        $_SESSION['mp_sheba_error'] = " شماره شبا نامعتبر است";
        mysqli_close($conn);
        //$conn = null;
        header("Location: manager_profile.php#bank_info");
        exit();
    }

    // insert data into the table: bank_account
    $query = "SELECT * from bank_account WHERE manager_info_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $manager_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $query2 = "UPDATE bank_account
                SET bank_name=?, account_number=?, shaba_number=?
                WHERE manager_info_id = ?";
        $stmt2 = mysqli_prepare($conn, $query2);
        mysqli_stmt_bind_param(
            $stmt2,
            "sssi",
            $bankName,
            $cardNumber,
            $shebaNumber,
            $manager_id
        );

        if (!mysqli_stmt_execute($stmt2)) {
            die("MySQL error: " . mysqli_stmt_error($stmt2));
        }

        $query3 = "SELECT * from bank_account WHERE manager_info_id = ?";
        $stmt3 = mysqli_prepare($conn, $query3);
        mysqli_stmt_bind_param($stmt3, "i", $manager_id);
        mysqli_stmt_execute($stmt3);
        $result3 = mysqli_stmt_get_result($stmt3);
        $row3 = mysqli_fetch_array($result3);

        $_SESSION['bank_account_id'] = $row3['bank_account_id'];
        $_SESSION['mp_confirm'] = "اطلاعات با موفقیت ذخیره شد";

        mysqli_stmt_close($stmt2);
        mysqli_stmt_close($stmt3);
        mysqli_close($conn);
        //$conn = null;
        header("Location: manager_profile.php#bank_info");
        exit();
    } else {
        $query2 = "INSERT INTO bank_account (bank_name, account_number, shaba_number, manager_info_id)
                VALUES (?,?,?,?)";
        $stmt2 = mysqli_prepare($conn, $query2);
        mysqli_stmt_bind_param(
            $stmt2,
            "sssi",
            $bankName,
            $cardNumber,
            $shebaNumber,
            $manager_id
        );

        if (!mysqli_stmt_execute($stmt2)) {
            die("MySQL error: " . mysqli_stmt_error($stmt2));
        }

        // get primary key to use as foreign key in other tables.
        $bank_account_id = mysqli_insert_id($conn);

        $_SESSION['bank_account_id'] = $bank_account_id;

        $_SESSION['mp_confirm'] = "اطلاعات با موفقیت ذخیره شد";
        mysqli_stmt_close($stmt2);
        mysqli_close($conn);
        //$conn = null;
        header("Location: manager_profile.php#bank_info");
        exit();
    }
}

$bank_account_id = $_SESSION['bank_account_id'];

//soft clean
if (isset($_POST['delete_bank']) && $_POST['delete_bank'] == '1') {
    $query = "UPDATE bank_account SET bank_name = '', account_number = '', shaba_number = ''
                     WHERE bank_account_id = ?";

    // برای سازگاری مقدار ایمیل رو نول بگذاریم یا رشته خالی؛ اینجا رشته خالی میذاریم
    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param($stmt, 'i', $bank_account_id);
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['mp_confirm'] = 'اطلاعات بانکی پاک شد.';
    } else {
        $_SESSION['mp_not_confirm'] = 'خطا در پاک کردن اطلاعات.';
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    //$conn = null;
    header('Location: manager_profile.php#bank_info');
    exit();
}
