<?php

require_once 'database.php';
//$conn = db_connect();

if (!isset($_POST["managerUserName"]) || !isset($_POST["managerPassword"])) {
    die("خطا : ابتدا باید وارد سایت شوید");
    header("Location: register_simple.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $requiredFields = [
        "managerUserName",
        "managerPassword",
        "managerConfirmPassword"
    ];

    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            die("خطا : همه فیلدها باید پر شوند");
            header("Location: register_simple.php");
            exit();
        }
    }

    /*
    // Validate usernameformat (example: alphanumeric + underscore)
    if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $customerUserName)) {
        die("نام کاربری: 3-20 کاراکتر, فقط حروف, اعداد, _");
    }
    */

    // Sanitize inputs
    $managerUserName = test_input($_POST["managerUserName"]);
    $managerPassword = test_input($_POST["managerPassword"]);
    $managerConfirmPassword = test_input($_POST["managerConfirmPassword"]);

    if ($managerPassword !== $managerConfirmPassword) {
        die("خطا : پسورد و تایید پسورد باید یکسان باشند");
        header("Location: register_simple.php");
        exit();
    }

    /*
    if (strlen($customerPassword) < 12) {
        throw new Exception("Password must be 12+ characters");
    }
    */

    if (isset($_POST["managerUserName"]) && !empty($_POST["managerUserName"])) {
        $query = "SELECT manager_username, manager_password 
                    FROM manager_info 
                    WHERE manager_username = $managerUserName and manager_password = $managerPassword";

        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_array($result);
            $manager_id = $row["manager_id"];
            $manager_username = $row["manager_username"];
        } elseif (mysqli_num_rows($result) == 0) {
            die("خطا : نام کاربری یا رمز عبور اشتباه است");
            header("Location: register_simple.php");
            exit();
        }
    }
}

// Close connection
//mysqli_close($conn);
