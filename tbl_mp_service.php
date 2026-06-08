<?php
// tbl_mp_service.php
require_once 'database.php';
//$conn = db_connect();

$manager_id = $_SESSION['manager_id'];
$salon_id = $_SESSION['salon_id'] ?? "";
$service_exist = false;
//$rows = [];

if (empty($manager_id)) {
    $_SESSION['login_error'] = "ابتدا باید به سایت ورود کنید";
    //$conn = null;
    mysqli_close($conn);
    header("Location: login.php");
    exit();
}

$query = "SELECT * FROM service WHERE salon_id = ? AND salon_id <> ''
          ORDER BY service_name ASC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $salon_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) $service_exist = true;

?>

<?php if ($service_exist): ?>
    <div class="admin-card" id="tbl_generated_services">
        <div class="generated-tbl-header">
            <h5 class="mb-0">خدمات ثبت شده برای سالن</h5>
        </div>
        <div class="admin-card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>ردیف</th>
                            <th>نوع خدمت</th>
                            <th>مدت زمان (دقیقه)</th>
                            <th>قیمت (تومان)</th>
                            <th>تخفیف %</th>
                            <th>قیمت نهای (تومان)</th>
                            <th>تاریخ شروع تخفیف</th>
                            <th>تاریخ پایان تخفیف</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                        while ($row = mysqli_fetch_array($result)): ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= htmlspecialchars($row['service_name']); ?></td>
                                <td><?= htmlspecialchars($row['duration_min']); ?></td>
                                <td><?= htmlspecialchars($row['price']); ?></td>
                                <td><?= htmlspecialchars($row['discount']); ?></td>
                                <td><?= htmlspecialchars($row['final_price']); ?></td>
                                <td>
                                    <?php
                                    if ($row['discount'] == 0 || $row['discount'] == '') {
                                        echo '';
                                    } else {
                                        echo htmlspecialchars($row['discount_start_date']);
                                    }
                                    ?>
                                    <?//= htmlspecialchars($row['discount_start_date']); ?>
                                </td>
                                <td>
                                    <?php
                                    if ($row['discount'] == 0 || $row['discount'] == '') {
                                        echo '';
                                    } else {
                                        echo htmlspecialchars($row['discount_end_date']);
                                    }
                                    ?>
                                    <?//= htmlspecialchars($row['discount_end_date']); ?>
                                </td>
                                <?php if ($row['is_active'] == 1) {
                                    $row['is_active'] = "فعال";
                                } else {
                                    $row['is_active'] = "غیر فعال";
                                }  ?>
                                <td><?= htmlspecialchars($row['is_active']); ?></td>
                                <td>
                                    <form method="post" action="mp_config_service.php" onsubmit="return confirm('آیا مطمئنید می‌خواهید این خدمت از سالن حذف شود؟');" style="display:inline">

                                        <input type="hidden" name="service_id[]" value="<?= $row['service_id']; ?>">
                                        <!-- <input type="hidden" name="service_id" value="<? //= $row['service_id']; 
                                                                                            ?>"> -->
                                        <button type="submit" name="delete_service" value="1" class="btn btn-sm btn-danger">حذف</button>
                                        <!-- <button type="submit" name="delete_service[]" class="btn btn-sm btn-danger">حذف</button> -->

                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif;
mysqli_stmt_close($stmt);

?>