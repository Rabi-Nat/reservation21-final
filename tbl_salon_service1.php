<?php
//session_start();
require_once 'database.php';

$salon_id = $_SESSION['salon_id'] ?? null;

if (empty($salon_id)) {
    echo "<div class='alert alert-warning'>لطفا ابتدا اطلاعات سالن را تکمیل کنید.</div>";
    return;
}

// fetch all services for this salon
$query = "SELECT * FROM service WHERE salon_id = ?
        ORDER BY service_id ASC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $salon_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header("Location: manager_profile.php#service-salon");
    exit();
}

?>

<!-- بررسی شود -->
<div class="admin-card mt-4">
    <div class="generated-tbl-header">
        <h5 class="mb-0">خدمات ثبت‌ شده سالن</h5>
    </div>
    <div class="admin-card-body table-responsive">
        <table class="table table-bordered" id="salonServiceDisplay">
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>نام خدمت</th>
                    <th>مدت زمان (دقیقه)</th>
                    <th>قیمت (تومان)</th>
                    <th>تخفیف (%)</th>
                    <th>قیمت نهایی (تومان)</th>
                    <th>تاریخ شروع تخفیف</th>
                    <th>تاریخ پایان تخفیف</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_array($result)): ?>
                    <tr data-id="<?= $row['service_id'] ?>">
                        <td><?= htmlspecialchars($row['service_id']) ?></td>
                        <td><?= htmlspecialchars($row['service_name']) ?></td>
                        <td><?= htmlspecialchars($row['duration_min']) ?></td>
                        <td><?= htmlspecialchars($row['price']) ?></td>
                        <td><?= htmlspecialchars($row['discount']) ?></td>
                        <td><?= htmlspecialchars($row['final_price']) ?></td>
                        <td>
                            <?= $row['discount_start_date']
                                ? date('Y/m/d', strtotime($row['discount_start_date']))
                                : '—' ?>
                        </td>
                        <td>
                            <?= $row['discount_end_date']
                                ? date('Y/m/d', strtotime($row['discount_end_date']))
                                : '—' ?>
                        </td>
                        <td><?= $row['is_active'] ? 'فعال' : 'غیرفعال' ?></td>
                        <td>
                            <div class="d-inline-flex gap-2">
                                <!-- <button type="button" class="btn btn-sm btn-primary edit-service">ویرایش</button> -->
                                
                                    <button type="button" class="btn btn-sm btn-danger delete-service" onclick="deleteExistingService(this)">حذف</button>  
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>


<!-- Bootstrap JS (Popper and Bootstrap JS) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/2.0.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>