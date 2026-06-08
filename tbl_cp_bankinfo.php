<?php

// اتصال به دیتابیس
require_once 'database.php';
//$conn = db_connect();

$customer_id = $_SESSION['customer_id'];
//$bank_account_id = $_SESSION['bank_account_id'];

$bank_exist = false;

if (empty($customer_id)) {
    $_SESSION['login_error'] = "ابتدا باید به سایت ورود کنید";
    mysqli_close($conn);
    //$conn = null;
    header("Location: login.php");
    exit();
}

$query = "SELECT * FROM bank_account_customer WHERE customer_info_id = ? 
                AND bank_name IS NOT NULL AND TRIM(bank_name) <> '' LIMIT 1";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $customer_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_array($result);

if(mysqli_num_rows($result) == 1){
$bank_exist = true;
}

// به‌روزرسانی session ids (اختیاری، فقط اگر لازم باشد)
if (!empty($row['bank_account_id'])) {
    $_SESSION['bank_account_id'] = (int)$row['bank_account_id'];
}



?>

<?php if ($bank_exist): ?>
    <div class="admin-card" id="tbl_generated_bank">
        <div class="generated-tbl-header">
            <h5 class="mb-0">اطلاعات بانکی ثبت شده</h5>
        </div>
        <div class="admin-card-body">
            <table>
                <tr>
                    <th>نام بانک</th>
                    <th>شماره کارت</th>
                    <th>شماره شبا</th>
                    <th>عملیات</th>
                </tr>
                <tr>
                    <td><?= htmlspecialchars($row['bank_name']); ?></td>
                    <td><?= htmlspecialchars($row['account_number']); ?></td>
                    <td><?= htmlspecialchars($row['shaba_number']); ?></td>
                    <td>
                        <form method="post" action="cp_config_bank.php" onsubmit="return confirm('آیا مطمئنید می‌خواهید اطلاعات بانکی پاک شود؟');">
                            <button type="submit" name="delete_bank" value="1" class="action-btn delete-btn">حذف</button>
                        </form>
                    </td>
                </tr>
            </table>
        </div>
    </div>
<?php endif;
mysqli_stmt_close($stmt);
?>