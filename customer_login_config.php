<?php

require_once 'database.php';

if (!isset($_POST["customerUserName"]) || !isset($_POST["customerPassword"])) {
    die("خطا : ابتدا باید وارد سایت شوید");
    header("Location: register_simple.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $requiredFields = [
        "customerUserName",
        "customerPassword",
        "customerConfirmPassword"
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
    $customerUserName = test_input($_POST["customerUserName"]);
    $customerPassword = test_input($_POST["customerPassword"]);
    $customerConfirmPassword = test_input($_POST["customerConfirmPassword"]);

    if ($customerPassword !== $customerConfirmPassword) {
        die("خطا : پسورد و تایید پسورد باید یکسان باشند");
        header("Location: register_simple.php");
        exit();
    }

    /*
    if (strlen($customerPassword) < 12) {
        throw new Exception("Password must be 12+ characters");
    }
    */

    if (isset($_POST["customerUserName"]) && !empty($_POST["customerUserName"])) {
        $query = "SELECT customer_username, customer_password 
                    FROM customer_info 
                    WHERE customer_username = $customerUserName and customer_password = $customerPassword";

        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_array($result);
            $customer_id = $row["customer_id"];
            $customer_username = $row["customer_username"];
        } elseif (mysqli_num_rows($result) == 0) {
            die("خطا : نام کاربری یا رمز عبور اشتباه است");
            header("Location: register_simple.php");
            exit();
        }
    }
}

// Close connection
//mysqli_close($conn);
