<?php

// اتصال به دیتابیس
require_once 'database.php';
//$conn = db_connect();

/* 
// validate token
if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token'])) {
    header('Location: login.php');
    exit();
}
*/

$manager_id = $_SESSION['manager_id'] ?? "";
$manager_exist = false;

if (!empty($manager_id)) {
    $query = "SELECT * FROM manager_info 
    WHERE manager_id = ? AND first_name IS NOT NULL AND TRIM(first_name) <> '' LIMIT 1";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $manager_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && ($row = mysqli_fetch_assoc($result))) {
        $manager_exist = true;
    }

    // پاکسازی: بهتر است از instanceof برای تشخیص نوع نتیجه استفاده کنیم
    if (isset($result) && ($result instanceof mysqli_result)) {
        mysqli_free_result($result);
    }
    
}

?>

<?php if ($manager_exist): ?>
    <div class="admin-card" id="tbl_generated_personal">
        <div class="generated-tbl-header">
            <h5 class="mb-0">اطلاعات شخصی ثبت شده</h5>
        </div>
        <div class="admin-card-body">
            <table>
                <tr>
                    <th>نام</th>
                    <th>نام خانوادگی</th>
                    <th>کد ملی</th>
                    <th>شماره تماس</th>
                    <th>ایمیل</th>
                    <th>عملیات</th>
                </tr>
                <tr>
                    <td><?= htmlspecialchars($row['first_name']); ?></td>
                    <td><?= htmlspecialchars($row['last_name']); ?></td>
                    <td><?= htmlspecialchars($row['national_id']); ?></td>
                    <td><?= htmlspecialchars($row['manager_phone']); ?></td>
                    <td><?= htmlspecialchars($row['manager_email']); ?></td>
                    <td>
                        <!-- فرم حذف — از توکن یک‌بار مصرف صفحه استفاده می‌شود -->
                        <form method="post" action="mp_config_personal.php" onsubmit="return confirm('آیا مطمئنید می‌خواهید اطلاعات شخصی پاک شود؟');">
                            <!-- <input type="hidden" name="csrf_token" value="<?//= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>"> -->
                            <button type="submit" name="delete_personal" value="1" class="action-btn delete-btn">حذف</button>
                        </form>
                    </td>
                </tr>
            </table>
        </div>
    </div>
<?php endif;
mysqli_stmt_close($stmt); 
?>