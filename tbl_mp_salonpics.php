<?php
// tbl_mp_salonpics.php
require_once __DIR__ . '/database.php';
//$conn = db_connect(); // حتماً اتصال زنده را بگیر

$manager_id = $_SESSION['manager_id'] ?? "";
$salon_id = $_SESSION['salon_id'] ?? "";
$location_id = $_SESSION['location_id'] ?? "";
$salonpic_exist = false;

if (empty($manager_id)) {
    $_SESSION['login_error'] = "ابتدا باید به سایت ورود کنید";
    //mysqli_close($conn);
    //header("Location: login.php");
    //exit();
}

if (empty($salon_id)) {
    $_SESSION['salon_error'] = "ابتدا فرم اطلاعات سالن را تکمیل کنید";
    //mysqli_close($conn);
    //header("Location: manager_profile.php#portfolioPics");
    //exit();
}

$query = "SELECT * FROM salon WHERE salon_id = ? AND salon_photo <> ''
            ORDER BY salon_id DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $salon_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) $salonpic_exist = true;

?>

<?php if ($salonpic_exist): ?>
    <div class="admin-card" id="tbl_generated_portfolio">
        <div class="generated-tbl-header">
            <h5 class="mb-0">عکس های سالن</h5>
        </div>
        <div class="admin-card-body">
            <div class="row g-3">

                <?php while ($row = mysqli_fetch_array($result)) :
                    // مسیر نسبت به وب: اگر لازم شد از base URL استفاده کنید
                    $imgSrc = htmlspecialchars($row['salon_photo']);
                    $title = test_input($row['salon_title']) ?? "";
                    $salon_id = intval($row['salon_id']);
                ?>
                    <div class="col-12 col-md-4 portfolio-col">
                        <div class="portfolio-item card p-2">
                            <img src="<?= $imgSrc; ?>" width="200" height="300">
                            <div class="portfolio-info mt-2">
                                <?= $title; ?>
                            </div>
                            <form action="mp_config_salonpics.php" method="post" id="deleteSalonPicForm">
                                <input type="hidden" name="salon_id[]" value="<?= $salon_id; ?>">
                                <button type="submit" name="delete_salonpic" class="btn btn-light btn-sm" onclick="return confirm('آیا مطمئن هستید که می‌خواهید این عکس سالن را حذف کنید؟');">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>

            </div>
        </div>
    </div>
<?php endif;
//mysqli_stmt_close($stmt);

?>