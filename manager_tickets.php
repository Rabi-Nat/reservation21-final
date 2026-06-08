<?php
session_start();

$manager_id  = $_SESSION['manager_id']  ?? "";

$mp_emptyfield_error = $_SESSION['mp_emptyfield_error'] ?? "";
$salon_pic_file_error = $_SESSION['salon_pic_file_error'] ?? "";
$salon_pic_fileSize_error = $_SESSION['salon_pic_fileSize_error'] ?? "";
$mp_ticket_fileType_error = $_SESSION['mp_ticket_fileType_error'] ?? "";
$mp_ticket_file_error = $_SESSION['mp_ticket_file_error'] ?? "";
$mp_upload_ticket = $_SESSION['mp_upload_ticket'] ?? "";
$mp_upload_ticket_error = $_SESSION['mp_upload_ticket_error'] ?? "";

unset(
    $_SESSION['mp_emptyfield_error'],
    $_SESSION['salon_pic_file_error'],
    $_SESSION['salon_pic_fileSize_error'],
    $_SESSION['mp_ticket_fileType_error'],
    $_SESSION['mp_ticket_file_error'],
    $_SESSION['mp_upload_ticket'],
    $_SESSION['mp_upload_ticket_error']
);

?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ارسال و مشاهده تیکت‌های من</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <style>
        body {
            background: #f8f9fa;
            font-family: Vazirmatn, Tahoma, sans-serif;
        }

        .ticket-container {
            max-width: 1100px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px #0001;
            padding: 30px;
        }

        .ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .ticket-header h2 {
            font-size: 1.5rem;
            margin: 0;
        }

        .ticket-list {
            border-left: 1px solid #eee;
            min-height: 400px;
        }

        .ticket-list-item {
            padding: 15px 10px;
            border-bottom: 1px solid #f1f1f1;
            cursor: pointer;
            transition: background 0.2s;
        }

        .ticket-list-item.active,
        .ticket-list-item:hover {
            background: #f5f5f5;
        }

        .ticket-list-item .status {
            font-size: 12px;
            border-radius: 8px;
            padding: 2px 8px;
            margin-right: 8px;
        }

        .status-open {
            background: #d4edda;
            color: #155724;
        }

        .status-closed {
            background: #f8d7da;
            color: #721c24;
        }

        .ticket-messages {
            max-height: 400px;
            overflow-y: auto;
            padding: 10px 0;
        }

        .message {
            margin-bottom: 18px;
            display: flex;
            flex-direction: column;
        }

        .message.user {
            align-items: flex-end;
        }

        .message.admin {
            align-items: flex-start;
        }

        .message-content {
            background: #f1f1f1;
            border-radius: 10px;
            padding: 10px 16px;
            max-width: 70%;
        }

        .message.user .message-content {
            background: #9a563a;
            color: #fff;
        }

        .message.admin .message-content {
            background: #f1f1f1;
            color: #333;
        }

        .message-time {
            font-size: 11px;
            color: #888;
            margin-top: 4px;
        }

        .ticket-form {
            background: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .ticket-form .form-label {
            font-size: 14px;
        }

        .ticket-form .form-control,
        .ticket-form .form-select {
            font-size: 14px;
        }

        .ticket-form .btn {
            font-size: 14px;
        }

        .ticket-details {
            margin-bottom: 10px;
        }

        @media (max-width: 991px) {
            .ticket-container {
                padding: 10px;
            }

            .ticket-header {
                flex-direction: column;
                gap: 10px;
            }

            .ticket-list {
                border-left: none;
                border-top: 1px solid #eee;
                margin-top: 20px;
            }
        }

        @media (max-width: 768px) {
            .ticket-container {
                padding: 2px;
            }

            .ticket-header {
                flex-direction: column;
                gap: 10px;
            }
        }

        .notification1 {
            position: absolute;
            top: 20px;
            right: 60px;
            padding: 15px 25px;
            background-color: rgb(48, 225, 13);
            color: white;
            border-radius: 4px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 9999;
            /* animation: fadeIn 0.5s, fadeOut 0.5s 3s; */

            animation-name: fadeIn, fadeOut;
            animation-duration: 0.5s, 0.5s;
            animation-delay: 0s, 3s;
            animation-fill-mode: forwards, forwards;
        }

        .notification2 {
            position: absolute;
            top: 20px;
            right: 60px;
            padding: 15px 25px;
            background-color: rgb(249, 85, 21);
            color: white;
            border-radius: 4px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 9999;
            /* animation: fadeIn 0.5s, fadeOut 0.5s 3s; */

            animation-name: fadeIn, fadeOut;
            animation-duration: 0.5s, 0.5s;
            animation-delay: 0s, 3s;
            animation-fill-mode: forwards, forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }

            to {
                opacity: 0;
                transform: translateY(-20px);
            }
        }
    </style>
</head>

<body>
    <div class="container py-4">
        <?php if (!empty($mp_emptyfield_error)): ?>
            <div class="notification2"><?php echo $mp_emptyfield_error; ?></div>
        <?php elseif (!empty($salon_pic_file_error)): ?>
            <div class="notification2"><?php echo $salon_pic_file_error; ?></div>
        <?php elseif (!empty($salon_pic_fileSize_error)): ?>
            <div class="notification2"><?php echo $salon_pic_fileSize_error; ?></div>
        <?php elseif (!empty($mp_ticket_fileType_error)): ?>
            <div class="notification2"><?php echo $mp_ticket_fileType_error; ?></div>
        <?php elseif (!empty($mp_ticket_file_error)): ?>
            <div class="notification2"><?php echo $mp_ticket_file_error; ?></div>
        <?php elseif (!empty($mp_upload_ticket)): ?>
            <div class="notification1"><?php echo $mp_upload_ticket; ?></div>
        <?php elseif (!empty($mp_upload_ticket_error)): ?>
            <div class="notification2"><?php echo $mp_upload_ticket_error; ?></div>
        <?php endif; ?>

        <div class="ticket-container" id="mp_ticket_div">
            <div class="ticket-header">
                <h2>تیکت‌های من</h2>
                <a href="manager_profile.php" class="btn btn-secondary">بازگشت به پنل</a>
            </div>
            <!-- فرم ارسال تیکت جدید -->
            <form action="mp_config_tickets.php" method="post" class="ticket-form" id="newTicketForm" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="ticketTitle">عنوان تیکت <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="ticketTitle" id="ticketTitle" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="ticketCategory">دسته‌بندی <span class="text-danger">*</span></label>
                        <select class="form-select" name="ticketCategory" id="ticketCategory" required>
                            <option value="">انتخاب کنید</option>
                            <option value="عمومی">عمومی</option>
                            <option value="مالی">مالی</option>
                            <option value="رزرو و نوبت">رزرو و نوبت</option>
                            <option value="پیشنهادات">پیشنهادات</option>
                            <option value="انتقادات">انتقادات</option>
                            <option value="فنی">فنی</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="ticketMessage">متن پیام <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="ticketMessage" id="ticketMessage" rows="4" required></textarea>
                    </div>
                    <div class="col-12">
                        <P class="mb-1">فرمت های مجاز فایل: jpg, png, gif, tiff, webp, zip, rar, pdf, doc</P>
                        <p>حداکثر حجم مجاز فایل: 10 مگابایت</p>
                        <label class="form-label" for="ticketFile">فایل ضمیمه (اختیاری)</label>
                        <input type="file" class="form-control" name="ticketFile" id="ticketFile" accept="image/*,.pdf,.doc,.docx,.zip,.rar">
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" name="submit" class="btn btn-primary">ارسال تیکت</button>
                    </div>
                </div>
            </form>
            <!-- نمایش مکالمه تیکت آخر (در صورت وجود)-->
        </div>

        <!-- generated table for user to see submitted data of manager tickets -->
        <?php include("tbl_mp_tickets.php"); ?>

    </div>

    <!-- Modal نمایش پاسخ -->
    <div class="modal fade" id="responseModal" tabindex="-1" aria-labelledby="responseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="responseModalLabel">پاسخ ادمین</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body" id="responseModalBody">
                    <!-- متن پاسخ اینجا قرار می‌گیرد -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/bootstrap.min.js"></script>
    <!-- از jsDelivr (Bootstrap v5) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.bundle.min.js"></script>

    <script src="assets/js/fontawesome.min.js"></script>

    <script>
        // وقتی دکمه نمایش پاسخ کلیک شد، محتوای مودال را از data-response بگیریم
        document.addEventListener('DOMContentLoaded', function() {
            const responseBtns = document.querySelectorAll('.show-response-btn');
            responseBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const resp = this.getAttribute('data-response') || '';
                    const modalBody = document.getElementById('responseModalBody');
                    if (!resp.trim()) {
                        modalBody.innerHTML = '<p class="mb-0">هنوز پاسخی ثبت نشده است.</p>';
                    } else {
                        // متن پاسخ را با حفظ خط‌ها نمایش می‌دهیم
                        modalBody.innerHTML = '<div style="white-space:pre-wrap;">' + resp + '</div>';
                    }
                    var myModal = new bootstrap.Modal(document.getElementById('responseModal'));
                    myModal.show();
                });
            });
        });
    </script>

</body>

</html>