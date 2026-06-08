<?php
// tbl_mp_portfolio.php

require_once __DIR__ . '/database.php';
//$conn = db_connect(); // حتماً اتصال زنده را بگیر

$manager_id = $_SESSION['manager_id'] ?? "";
$salon_id = $_SESSION['salon_id'] ?? "";
$portfolio_exist = false;

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

$query = "SELECT * FROM salon_sample WHERE salon_id = ? AND photo_url <> ''
            ORDER BY sample_id DESC";
$stmt = mysqli_prepare($conn, $query); // در اینجا ارتباط با سرور قطع می شود.  چرا؟؟؟؟
// reason : @mysqli_ping($conn) ---> deprecated in php 8.4
mysqli_stmt_bind_param($stmt, 'i', $salon_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) $portfolio_exist = true;

?>

<?php if ($portfolio_exist): ?>
    <div class="admin-card" id="tbl_generated_portfolio">
        <div class="generated-tbl-header">
            <h5 class="mb-0">نمونه کارهای سالن</h5>
        </div>
        <div class="admin-card-body">
            <div class="row g-3">

                <?php while ($row = mysqli_fetch_array($result)) :
                    // مسیر نسبت به وب: اگر لازم شد از base URL استفاده کنید
                    $imgSrc = htmlspecialchars($row['photo_url']);
                    $title = htmlspecialchars($row['salon_photo_title']) ?? "";
                    $sample_id = intval($row['sample_id']);
                ?>
                    <div class="col-12 col-md-4 portfolio-col">
                        <div class="portfolio-item card p-2">
                            <img src="<?= $imgSrc; ?>" width="200" height="300">
                            <div class="portfolio-info mt-2">
                                <?= $title; ?>
                            </div>
                            <form action="mp_config_portfolio.php" method="post" id="deletePortfolioForm">
                                <input type="hidden" name="sample_id[]" value="<?= $sample_id; ?>">
                                <button type="submit" name="delete_portfolio" class="btn btn-light btn-sm" onclick="return confirm('آیا مطمئن هستید که می‌خواهید این نمونه کار را حذف کنید؟');">
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