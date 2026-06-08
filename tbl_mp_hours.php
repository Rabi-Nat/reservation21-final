<?php

//=====================for debug=====================
// debug header
@file_put_contents(__DIR__ . '/debug_include.log', date("c") . " tbl_mp_hours.php START\n", FILE_APPEND);
echo "<!-- tbl_mp_hours.php START -->\n";

// محافظت از session (اگر هنوز session نداریم)
//if (session_status() === PHP_SESSION_NONE) session_start();

//======================end debug===============================
// tbl_mp_hours.php — ساده، مستقیم، بدون توابع/آرایه‌های کمکی
//if (session_status() !== PHP_SESSION_ACTIVE) session_start();

require_once 'database.php'; // باید $conn (mysqli) را فراهم کند
//$conn = db_connect();

$manager_id = $_SESSION['manager_id'] ?? "";
$salon_id = $_SESSION['salon_id'] ?? "";
$hours_exist = false;

if (empty($manager_id) || $manager_id ==="") {
    $_SESSION['login_error'] = "ابتدا باید به سایت ورود کنید";
    //mysqli_close($conn);
    //$conn = null;
    //header("Location: login.php");
    exit();
    //return;
}

//if (empty($salon_id) || $salon_id==="" || !isset($salon_id)) {
    //$_SESSION['salon_error'] = "ابتدا فرم اطلاعات سالن را تکمیل کنید";
    //mysqli_close($conn);
    //$conn = null;
    //header("Location: manager_profile.php#working_hours");
    //exit();
    //return;
//}

$query = "SELECT hour_id FROM salon_hours WHERE salon_id = ? AND status = 1";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $salon_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($result) > 0) {
    $hours_exist = true;
}




// // اگر هیچ رکورد فعالی وجود ندارد، هیچ چیز نمایش نده
// $countQ = "SELECT COUNT(hour_id) AS cnt FROM salon_hours WHERE salon_id = ? AND status = 1";
// $stmtc = mysqli_prepare($conn, $countQ);
// if ($stmtc) {
//     mysqli_stmt_bind_param($stmtc, 'i', $salon_id);
//     mysqli_stmt_execute($stmtc);
//     mysqli_stmt_bind_result($stmtc, $cnt);
//     mysqli_stmt_fetch($stmtc);
//     mysqli_stmt_close($stmtc);
// } else {
//     // در صورت خطا، بهتر است چیزی نریزیم تا صفحه بهم نریزد
//     if (isset($conn)) {
//         mysqli_close($conn);
//         //$conn = null;
//     }
//     return;
//     //exit();
// }


// //if (empty($cnt))
// if ($cnt == 0) {
//     // هیچ رکورد فعالی نیست — هیچ HTML مربوط به جدول تولید نمی‌شود
//     if (isset($conn)) {
//         mysqli_close($conn);
//         //$conn = null;
//     }
//     return;
//     //exit();
// }


// اگر به اینجا رسیدیم حداقل یک ردیف فعال وجود دارد — جدول را رندر می‌کنیم
?>

<?php if ($hours_exist == true): ?>
    <div class="admin-card" id="tbl_generated_hours">
        <div class="generated-tbl-header">
            <h5 class="mb-0">ساعات کاری ثبت شده برای سالن</h5>
        </div>
        <div class="admin-card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>روز هفته</th>
                            <th>ساعت شروع</th>
                            <th>ساعت پایان</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // شنبه
                        $q1 = "SELECT hour_id, day_of_week, open_time, close_time FROM salon_hours WHERE salon_id = ? AND status = 1 AND day_of_week = 'شنبه' LIMIT 1";
                        $s1 = mysqli_prepare($conn, $q1);
                        if ($s1) {
                            mysqli_stmt_bind_param($s1, 'i', $salon_id);
                            mysqli_stmt_execute($s1);
                            $res1 = mysqli_stmt_get_result($s1);
                            if ($row1 = mysqli_fetch_assoc($res1)) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row1['day_of_week']) . '</td>';
                                echo '<td>' . htmlspecialchars($row1['open_time']) . '</td>';
                                echo '<td>' . htmlspecialchars($row1['close_time']) . '</td>';
                                echo '<td>
                                    <form method="post" action="mp_config_hours.php" style="display:inline;">
                                        <input type="hidden" name="hour_id" value="' . (int)$row1['hour_id'] . '">
                                        <button type="submit" name="delete_hour" value="1" class="btn btn-sm btn-danger" onclick="return confirm(\'آیا مطمئنید می‌خواهید این ساعت کاری حذف شود؟\');">حذف</button>
                                    </form>
                                  </td>';
                                echo '</tr>';
                            }
                            mysqli_stmt_close($s1);
                        }

                        // یکشنبه
                        $q2 = "SELECT hour_id, day_of_week, open_time, close_time FROM salon_hours WHERE salon_id = ? AND status = 1 AND day_of_week = 'یکشنبه' LIMIT 1";
                        $s2 = mysqli_prepare($conn, $q2);
                        if ($s2) {
                            mysqli_stmt_bind_param($s2, 'i', $salon_id);
                            mysqli_stmt_execute($s2);
                            $res2 = mysqli_stmt_get_result($s2);
                            if ($row2 = mysqli_fetch_assoc($res2)) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row2['day_of_week']) . '</td>';
                                echo '<td>' . htmlspecialchars($row2['open_time']) . '</td>';
                                echo '<td>' . htmlspecialchars($row2['close_time']) . '</td>';
                                echo '<td>
                                    <form method="post" action="mp_config_hours.php" style="display:inline;">
                                        <input type="hidden" name="hour_id" value="' . (int)$row2['hour_id'] . '">
                                        <button type="submit" name="delete_hour" value="1" class="btn btn-sm btn-danger" onclick="return confirm(\'آیا مطمئنید می‌خواهید این ساعت کاری حذف شود؟\');">حذف</button>
                                    </form>
                                  </td>';
                                echo '</tr>';
                            }
                            mysqli_stmt_close($s2);
                        }

                        // دوشنبه
                        $q3 = "SELECT hour_id, day_of_week, open_time, close_time FROM salon_hours WHERE salon_id = ? AND status = 1 AND day_of_week = 'دوشنبه' LIMIT 1";
                        $s3 = mysqli_prepare($conn, $q3);
                        if ($s3) {
                            mysqli_stmt_bind_param($s3, 'i', $salon_id);
                            mysqli_stmt_execute($s3);
                            $res3 = mysqli_stmt_get_result($s3);
                            if ($row3 = mysqli_fetch_assoc($res3)) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row3['day_of_week']) . '</td>';
                                echo '<td>' . htmlspecialchars($row3['open_time']) . '</td>';
                                echo '<td>' . htmlspecialchars($row3['close_time']) . '</td>';
                                echo '<td>
                                    <form method="post" action="mp_config_hours.php" style="display:inline;">
                                        <input type="hidden" name="hour_id" value="' . (int)$row3['hour_id'] . '">
                                        <button type="submit" name="delete_hour" value="1" class="btn btn-sm btn-danger" onclick="return confirm(\'آیا مطمئنید می‌خواهید این ساعت کاری حذف شود؟\');">حذف</button>
                                    </form>
                                  </td>';
                                echo '</tr>';
                            }
                            mysqli_stmt_close($s3);
                        }

                        // سه‌شنبه (پوشش دو نگارش معمول)
                        $q4 = "SELECT hour_id, day_of_week, open_time, close_time FROM salon_hours WHERE salon_id = ? AND status = 1 AND (day_of_week = 'سه‌شنبه' OR day_of_week = 'سه شنبه') LIMIT 1";
                        $s4 = mysqli_prepare($conn, $q4);
                        if ($s4) {
                            mysqli_stmt_bind_param($s4, 'i', $salon_id);
                            mysqli_stmt_execute($s4);
                            $res4 = mysqli_stmt_get_result($s4);
                            if ($row4 = mysqli_fetch_assoc($res4)) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row4['day_of_week']) . '</td>';
                                echo '<td>' . htmlspecialchars($row4['open_time']) . '</td>';
                                echo '<td>' . htmlspecialchars($row4['close_time']) . '</td>';
                                echo '<td>
                                    <form method="post" action="mp_config_hours.php" style="display:inline;">
                                        <input type="hidden" name="hour_id" value="' . (int)$row4['hour_id'] . '">
                                        <button type="submit" name="delete_hour" value="1" class="btn btn-sm btn-danger" onclick="return confirm(\'آیا مطمئنید می‌خواهید این ساعت کاری حذف شود؟\');">حذف</button>
                                    </form>
                                  </td>';
                                echo '</tr>';
                            }
                            mysqli_stmt_close($s4);
                        }

                        // چهارشنبه (پوشش دو نگارش ممکن)
                        $q5 = "SELECT hour_id, day_of_week, open_time, close_time FROM salon_hours WHERE salon_id = ? AND status = 1 AND (day_of_week = 'چهارشنبه' OR day_of_week = 'چهار شنبه') LIMIT 1";
                        $s5 = mysqli_prepare($conn, $q5);
                        if ($s5) {
                            mysqli_stmt_bind_param($s5, 'i', $salon_id);
                            mysqli_stmt_execute($s5);
                            $res5 = mysqli_stmt_get_result($s5);
                            if ($row5 = mysqli_fetch_assoc($res5)) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row5['day_of_week']) . '</td>';
                                echo '<td>' . htmlspecialchars($row5['open_time']) . '</td>';
                                echo '<td>' . htmlspecialchars($row5['close_time']) . '</td>';
                                echo '<td>
                                    <form method="post" action="mp_config_hours.php" style="display:inline;">
                                        <input type="hidden" name="hour_id" value="' . (int)$row5['hour_id'] . '">
                                        <button type="submit" name="delete_hour" value="1" class="btn btn-sm btn-danger" onclick="return confirm(\'آیا مطمئنید می‌خواهید این ساعت کاری حذف شود؟\');">حذف</button>
                                    </form>
                                  </td>';
                                echo '</tr>';
                            }
                            mysqli_stmt_close($s5);
                        }

                        // پنجشنبه
                        $q6 = "SELECT hour_id, day_of_week, open_time, close_time FROM salon_hours WHERE salon_id = ? AND status = 1 AND day_of_week = 'پنجشنبه' LIMIT 1";
                        $s6 = mysqli_prepare($conn, $q6);
                        if ($s6) {
                            mysqli_stmt_bind_param($s6, 'i', $salon_id);
                            mysqli_stmt_execute($s6);
                            $res6 = mysqli_stmt_get_result($s6);
                            if ($row6 = mysqli_fetch_assoc($res6)) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row6['day_of_week']) . '</td>';
                                echo '<td>' . htmlspecialchars($row6['open_time']) . '</td>';
                                echo '<td>' . htmlspecialchars($row6['close_time']) . '</td>';
                                echo '<td>
                                    <form method="post" action="mp_config_hours.php" style="display:inline;">
                                        <input type="hidden" name="hour_id" value="' . (int)$row6['hour_id'] . '">
                                        <button type="submit" name="delete_hour" value="1" class="btn btn-sm btn-danger" onclick="return confirm(\'آیا مطمئنید می‌خواهید این ساعت کاری حذف شود؟\');">حذف</button>
                                    </form>
                                  </td>';
                                echo '</tr>';
                            }
                            mysqli_stmt_close($s6);
                        }

                        // جمعه
                        $q7 = "SELECT hour_id, day_of_week, open_time, close_time FROM salon_hours WHERE salon_id = ? AND status = 1 AND day_of_week = 'جمعه' LIMIT 1";
                        $s7 = mysqli_prepare($conn, $q7);
                        if ($s7) {
                            mysqli_stmt_bind_param($s7, 'i', $salon_id);
                            mysqli_stmt_execute($s7);
                            $res7 = mysqli_stmt_get_result($s7);
                            if ($row7 = mysqli_fetch_assoc($res7)) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row7['day_of_week']) . '</td>';
                                echo '<td>' . htmlspecialchars($row7['open_time']) . '</td>';
                                echo '<td>' . htmlspecialchars($row7['close_time']) . '</td>';
                                echo '<td>
                                    <form method="post" action="mp_config_hours.php" style="display:inline;">
                                        <input type="hidden" name="hour_id" value="' . (int)$row7['hour_id'] . '">
                                        <button type="submit" name="delete_hour" value="1" class="btn btn-sm btn-danger" onclick="return confirm(\'آیا مطمئنید می‌خواهید این ساعت کاری حذف شود؟\');">حذف</button>
                                    </form>
                                  </td>';
                                echo '</tr>';
                            }
                            mysqli_stmt_close($s7);
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif;
mysqli_stmt_close($stmt);
?>
<?php
// if (isset($conn)) {
//     //mysqli_close($conn);
//     $conn = null;
// }

//========================for debug=============================
@file_put_contents(__DIR__ . '/debug_include.log', date("c") . " tbl_mp_hours.php END\n", FILE_APPEND);
echo "<!-- tbl_mp_hours.php END -->\n";

//========================end debug================================
?>