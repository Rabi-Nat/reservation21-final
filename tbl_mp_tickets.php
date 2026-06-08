<?php
// tbl_mp_tickets.php
require_once 'database.php';
//$conn = db_connect();

$manager_id = $_SESSION['manager_id'] ?? "";
//$salon_id = $_SESSION['salon_id'] ?? "";
$ticket_exist = false;
//$rows = [];

if (empty($manager_id)) {
    $_SESSION['login_error'] = "ابتدا باید به سایت ورود کنید";
    //$conn = null;
    mysqli_close($conn);
    header("Location: login.php");
    exit();
}

$query = "SELECT * FROM manager_ticket WHERE manager_id = ?
          ORDER BY m_ticket_id DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $manager_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) $ticket_exist = true;

?>

<?php if ($ticket_exist): ?>
    <div class="ticket-container" id="mp_ticket_div">
        <div class="admin-card" id="tbl_generated_tickets">
            <div class="generated-tbl-header">
                <h5 class="mb-0">تیکت های ثبت شده</h5>
            </div>
            <div class="admin-card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>ردیف</th>
                                <th>عنوان</th>
                                <th>دسته بندی</th>
                                <th>متن پیام ارسالی</th>
                                <th>فایل ارسال شده</th>
                                <th>تاریخ و زمان ارسال تیکت</th>
                                <th>وضعیت</th>
                                <th>پاسخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            while ($row = mysqli_fetch_array($result)): ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= htmlspecialchars($row['ticket_title']); ?></td>
                                    <td><?= htmlspecialchars($row['ticket_category']); ?></td>
                                    <!-- <td><? //= htmlspecialchars($row['ticket_message']); 
                                                ?></td> -->
                                    <td style="max-width:250px; white-space:pre-wrap;"><?= htmlspecialchars($row['ticket_message']); ?></td>
                                    <!-- <td><? //= htmlspecialchars($row['ticket_file']); 
                                                ?></td> -->
                                    <td>
                                        <?php
                                        $fileName = $row['ticket_file'];
                                        if (!empty($fileName)) {
                                            $displayName = htmlspecialchars($row['ticket_file_orig'] ?? $fileName);
                                            $downloadUrl = 'download_mp_ticket_file.php?id=' . (int)$row['m_ticket_id'];
                                            echo '<div class="d-flex gap-2 align-items-center">';
                                            echo '<a href="' . htmlspecialchars($downloadUrl) . '" class="btn btn-sm btn-outline-primary">دانلود</a>';
                                            echo '<small class="text-muted ms-2">' . $displayName . '</small>';
                                            echo '</div>';
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                    </td>
                                    <td><?= $row['upload_time']; ?></td>
                                    <td><?= htmlspecialchars($row['ticket_status'] ?? 'در حال بررسی'); ?></td>
                                    <td>
                                        <button type="button"
                                            class="btn btn-sm btn-info show-response-btn"
                                            data-response="<?= htmlspecialchars($row['ticket_response'] ?? '', ENT_QUOTES) ?>">
                                            نمایش پاسخ
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endif;
mysqli_stmt_close($stmt);
//$conn = null;
?>