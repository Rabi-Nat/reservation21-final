<?php

// اتصال به دیتابیس
require_once 'database.php';
//$conn = db_connect();

$manager_id = $_SESSION['manager_id'];
$manager_photo_exist = false;

if (empty($manager_id)) {
    header("Location: manager_profile.php");
    exit();
}

//if (!empty($manager_id)) {
$query = "SELECT manager_photo FROM manager_info 
    WHERE manager_id = ? AND manager_photo IS NOT NULL AND manager_photo != '' LIMIT 1";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $manager_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (($row = mysqli_fetch_array($result)) && !empty($row['manager_photo'])) {
    $manager_photo_exist = true;
    $manager_photo_path = $row['manager_photo'];
    // اگر مسیر با '/' شروع می‌شود، حذف کن (نمایش نسبی)
    if (strpos($manager_photo_path, '/') === 0) {
        $manager_photo_path = ltrim($manager_photo_path, '/');
    }
}

// if ($result && ($row = mysqli_fetch_assoc($result))) {
//     if (!empty($row['manager_photo'])) {
//         $manager_photo_exist = true;
//         $manager_photo_path = $row['manager_photo'];
//     }
// }

// پاکسازی: بهتر است از instanceof برای تشخیص نوع نتیجه استفاده کنیم
if (isset($result) && ($result instanceof mysqli_result)) {
    mysqli_free_result($result);
}
//}

?>

<? //php if ($manager_photo_exist): 
?>
<!-- <img id="main-profile-image" src=<? //= $row['manager_photo']; 
                                        ?> alt="User Avatar" class="rounded-circle mb-3 profile-image" style="width: 100px; height: 100px; object-fit: cover;"> -->
<? // elseif(!$manager_photo_exist): 
?>
<!-- <img id="default-profile-image" src="assets/img/team/user1-128x128.jpg" alt="User Avatar" class="rounded-circle mb-3 profile-image" style="width: 100px; height: 100px; object-fit: cover;"> -->
<? //php endif; 
?>


<!-- modified version -->
<?php if ($manager_photo_exist && !empty($manager_photo_path) && file_exists($manager_photo_path)): ?>
    <img id="main-profile-image" src="<?php echo htmlspecialchars($manager_photo_path); ?>" alt="User Avatar" class="rounded-circle mb-3 profile-image" style="width: 100px; height: 100px; object-fit: cover;">
<?php else: ?>
    <img id="main-profile-image" src="assets/img/team/user1-128x128.jpg" alt="User Avatar" class="rounded-circle mb-3 profile-image" style="width: 100px; height: 100px; object-fit: cover;">
<?php endif; ?>