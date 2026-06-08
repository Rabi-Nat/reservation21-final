<?php
// tbl_mp_option.php
require_once 'database.php';
//$conn = db_connect();

$manager_id = $_SESSION['manager_id'];
$salon_id = $_SESSION['salon_id'] ?? "";
$option_exist = false;
//$rows = [];

if (empty($manager_id)) {
    $_SESSION['login_error'] = "ابتدا باید به سایت ورود کنید";
    mysqli_close($conn);
    //$conn = null;
    header("Location: login.php");
    exit();
}

if (empty($salon_id)) {
    $_SESSION['salon_error'] = "ابتدا فرم اطلاعات سالن را تکمیل کنید";
    //mysqli_close($conn);
    //$conn = null;
    //header("Location: manager_profile.php#option_salon");
    //exit();
}

$query = "SELECT * FROM salon_option WHERE salon_id = ? AND salon_id <> ''
          ORDER BY option_name ASC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $salon_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) > 0) $option_exist = true;

?>

<?php if ($option_exist): ?>
    <div class="admin-card" id="tbl_generated_options">
        <div class="generated-tbl-header">
            <h5 class="mb-0">امکانات ثبت شده برای سالن</h5>
        </div>
        <div class="admin-card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>ردیف</th>
                            <th>امکانات</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1;
                        while ($row = mysqli_fetch_array($result)): ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= htmlspecialchars($row['option_name']); ?></td>
                                <td>
                                    <form method="post" action="mp_config_option.php" onsubmit="return confirm('آیا مطمئنید می‌خواهید این امکانات از سالن حذف شود؟');" style="display:inline">

                                        <input type="hidden" name="option_id[]" value="<?= $row['option_id']; ?>">
                                        <button type="submit" name="delete_option" value="1" class="btn btn-sm btn-danger">حذف</button>

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