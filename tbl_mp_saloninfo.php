<?php

// اتصال به دیتابیس
require_once 'database.php';
//$conn = db_connect();

$manager_id = $_SESSION['manager_id'];
//$location_id = $_SESSION['location_id'];
//$salon_id = $_SESSION['salon_id'];

$salon_exist = false;

if (empty($manager_id)) {
    $_SESSION['login_error'] = "ابتدا باید به سایت ورود کنید";
    mysqli_close($conn);
    //$conn = null;
    header("Location: login.php");
    exit();
}

/* 
// validate token
if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token'])) {
    header('Location: login.php');
    exit();
}
*/

// یک پرس‌وجوی واحد با JOIN برای دریافت اطلاعات salon و location
$query = "
    SELECT s.salon_id, s.salon_name, s.salon_gender, l.location_id, l.province, l.city, l.full_address, l.location_tel
    FROM salon s
    LEFT JOIN location l ON s.location_id = l.location_id
    WHERE s.manager_info_id = ?
    LIMIT 1
";

if ($stmt = mysqli_prepare($conn, $query)) {
    mysqli_stmt_bind_param($stmt, 'i', $manager_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && ($row = mysqli_fetch_assoc($result))) {
        // چک کن province و salon_name مقدار دارند (نه NULL و نه رشته خالی)
        $hasProvince = isset($row['province']) && trim($row['province']) !== '';
        $hasSalonName = isset($row['salon_name']) && trim($row['salon_name']) !== '';
        if ($hasProvince && $hasSalonName) {
            $salon_exist = true;
            // به‌روزرسانی session ids (اختیاری، فقط اگر لازم باشد)
            if (!empty($row['location_id'])) {
                $_SESSION['location_id'] = (int)$row['location_id'];
            }
            if (!empty($row['salon_id'])) {
                $_SESSION['salon_id'] = (int)$row['salon_id'];
            }
        }
    }

    if (isset($result) && $result instanceof mysqli_result) {
        mysqli_free_result($result);
    }
    
} else {
    // لاگ کردن خطای آماده‌سازی برای دیباگ (نه خروجی به کاربر)
    error_log("prepare failed: " . mysqli_error($conn));
}

?>

<?php if ($salon_exist): ?>
    <div class="admin-card" id="tbl_generated_saloninfo">
        <div class="generated-tbl-header">
            <h5 class="mb-0">اطلاعات سالن ثبت شده</h5>
        </div>
        <div class="admin-card-body">
            <table>
                <tr>
                    <th>نوع سالن</th>
                    <th>نام سالن</th>
                    <th>استان</th>
                    <th>شهر</th>
                    <th>آدرس</th>
                    <th>شماره محل کار</th>
                    <th>عملیات</th>
                </tr>
                <tr>
                    <td><?= htmlspecialchars($row['salon_gender']); ?></td>
                    <td><?= htmlspecialchars($row['salon_name']); ?></td>
                    <td><?= htmlspecialchars($row['province']); ?></td>
                    <td><?= htmlspecialchars($row['city']); ?></td>
                    <td><?= htmlspecialchars($row['full_address']); ?></td>
                    <td><?= htmlspecialchars($row['location_tel']); ?></td>
                    <td>
                        <!-- فرم حذف — از توکن یک‌بار مصرف صفحه استفاده می‌شود -->
                        <form method="post" action="mp_config_saloninfo.php" onsubmit="return confirm('آیا مطمئنید می‌خواهید اطلاعات سالن پاک شود؟');">
                            <!-- <input type="hidden" name="csrf_token" value="<?//= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>"> -->
                            <button type="submit" name="delete_saloninfo" value="1" class="action-btn delete-btn">حذف</button>
                        </form>
                    </td>
                </tr>
            </table>
        </div>
    </div>
<?php endif;
mysqli_stmt_close($stmt);
?>