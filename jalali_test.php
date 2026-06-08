<?php
// index.php (نسخهٔ اصلاح‌شده — باز شدن تقویم با کلیک روی input و prev/next صحیح)
// اتصال از db.php (شما قبلاً فرستادی و متغیر $conn را تعریف کرده)
header('Content-Type: text/html; charset=utf-8');
require_once 'database.php';
require_once 'jalali.php';


// تست/سینی‌تایز (اگر در db.php نیست)
if (!function_exists('test_input')) {
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        return $data;
    }
}

// پردازش فرم
$errors = array();
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jalali_raw = isset($_POST['jalali_date']) ? $_POST['jalali_date'] : '';
    $gregorian_raw = isset($_POST['gregorian_date']) ? $_POST['gregorian_date'] : '';
    $note_raw = isset($_POST['note']) ? $_POST['note'] : '';

    $jalali = test_input($jalali_raw);
    $gregorian = test_input($gregorian_raw);
    $note = test_input($note_raw);

    if ($jalali === '') $errors[] = "لطفاً یک تاریخ شمسی انتخاب کنید.";
    if ($gregorian === '') $errors[] = "تبدیل میلادی معادل تولید نشده است (مطمئن شوید JS فعال باشد).";
    if ($gregorian !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $gregorian)) $errors[] = "فرمت تاریخ میلادی نامعتبر است (باید YYYY-MM-DD باشد).";

    if (empty($errors)) {
        $dt = DateTime::createFromFormat('Y-m-d', $gregorian);
        if (!$dt) {
            $errors[] = "تبدیل تاریخ میلادی ناموفق بود.";
        } else {
            // مثال: اگر خواستی چند روز اضافه/کسر کنی اینجا انجام بده
            // $dt->modify('+7 days');
            $to_store = $dt->format('Y-m-d');

            $sql = "INSERT INTO jalali_dates (jalali_date, gregorian_date, note) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            if ($stmt === false) {
                $errors[] = "خطا در آماده‌سازی پرس‌وجو: " . mysqli_error($conn);
            } else {
                mysqli_stmt_bind_param($stmt, 'sss', $jalali, $to_store, $note);
                $exec = mysqli_stmt_execute($stmt);
                if ($exec) {
                    $success = "ذخیره با موفقیت انجام شد. (میلادی ذخیره‌شده: " . htmlspecialchars($to_store, ENT_QUOTES, 'UTF-8') . ")";
                } else {
                    $errors[] = "خطا در اجرای پرس‌وجو: " . mysqli_stmt_error($stmt);
                }
                mysqli_stmt_close($stmt);
            }
        }
    }
}

// دریافت رکوردها
$rows = array();
$res = mysqli_query($conn, "SELECT id, jalali_date, gregorian_date, note, created_at FROM jalali_dates ORDER BY created_at DESC LIMIT 200");
if ($res) {
    while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
    mysqli_free_result($res);
} else {
    $errors[] = "خطا در خواندن رکوردها: " . mysqli_error($conn);
}
?>
<!doctype html>
<html lang="fa">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>انتخاب تاریخ شمسی (تقویم باز با کلیک روی فیلد)</title>
    <style>
        body {
            font-family: Tahoma, Arial;
            direction: rtl;
            padding: 18px;
            background: #f3f7fb;
        }

        .card {
            background: #fff;
            padding: 18px;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            max-width: 980px;
            margin: auto;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 700;
        }

        .row {
            margin-bottom: 12px;
        }

        input[type=text],
        input[type=hidden] {
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ddd;
            font-size: 15px;
        }

        button {
            padding: 10px 14px;
            border-radius: 8px;
            border: none;
            background: #1372d9;
            color: #fff;
            cursor: pointer;
        }

        .calendar {
            position: absolute;
            background: #fff;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 8px;
            margin-top: 6px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            z-index: 1000;
            display: none;
        }

        .cal-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            font-weight: 700;
            padding: 8px 4px;
            background: #f1f5f9;
            border-radius: 6px;
        }

        /* بهبود دکمه‌های قبلی/بعدی */
        .cal-head button {
            background: #0555c4ff;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 6px 10px;
            cursor: pointer;
            font-weight: bold;
            /* transition: background 0.2s ease; */
        }

        .cal-head button:hover {
            background: #475569;
        }

        .cal-grid {
            display: grid;
            grid-template-columns: repeat(7, 40px);
            gap: 4px;
        }

        .cal-day {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .cal-day:hover {
            background: #eef6ff;
            transform: translateY(-1px);
        }

        /* ظاهر روزِ 'امروز' (وقتی تقویم باز می‌شود) */
        .cal-day.today {
            background: #e8f4fd !important;
            border: 2px solid #2196F3 !important;
            color: #1976d2 !important;
            font-weight: bold;
        }

        .cal-day.selected {
            background: #1372d9 !important;
            color: #fff !important;
            font-weight: bold;
        }

        .cal-day.today.selected {
            background: #1976d2 !important;
            color: #fff !important;
            border-color: #0d47a1 !important;
        }

        .weekday {
            font-size: 14px;
            color: #666;
            text-align: center;
            font-weight: 700;
            /* bold */
            padding: 4px 0;
            background-color: #f8fafc;
            border-radius: 4px;
            margin: 2px 0;
        }

        table.simple {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }

        table.simple th,
        table.simple td {
            border: 1px solid #eee;
            padding: 8px;
            text-align: center;
        }

        .msg {
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .error {
            background: #ffecec;
            color: #900;
            border: 1px solid #f5c6c6;
        }

        .ok {
            background: #e8f8ef;
            color: #0a6a3a;
            border: 1px solid #bfe9cf;
        }

        @media (max-width:480px) {
            .cal-grid {
                grid-template-columns: repeat(7, 32px);
            }

            .cal-day {
                width: 32px;
                height: 32px;
            }
        }
    </style>
</head>

<body>
    <div class="card">
        <h2>انتخاب تاریخ شمسی</h2>

        <?php if (!empty($errors)) : ?>
            <div class="msg error">
                <strong>خطا(ها):</strong>
                <ul>
                    <?php foreach ($errors as $er) echo '<li>' . htmlspecialchars($er, ENT_QUOTES, 'UTF-8') . '</li>'; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($success) : ?>
            <div class="msg ok"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <form id="dateForm" method="post" action="">
            <div class="row" style="position:relative; display:inline-block;">
                <label for="jalaliInput">تاریخ (شمسی)</label>
                <input type="text" id="jalaliInput" name="jalali_date" placeholder="مثال: 1404/07/05" readonly style="width:220px;" />
                <input type="hidden" id="gregorianHidden" name="gregorian_date" />
                <button type="button" id="clearBtn">پاک کردن</button>

                <!-- تقویم قرار گرفته در همان والد input تا راحت پوزیشن شود -->
                <div id="calendarContainer" class="calendar" style="right:0;"></div>
            </div>

            <div class="row">
                <label for="note">یادداشت (اختیاری)</label>
                <input type="text" name="note" id="note" style="width:100%;" />
            </div>

            <div class="row">
                <button type="submit">ارسال و ذخیره</button>
            </div>
        </form>

        <h3>پیش‌نمایش انتخاب</h3>
        <table class="simple">
            <thead>
                <tr>
                    <th>تاریخ شمسی</th>
                    <th>تاریخ میلادی (ارسال‌شده)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td id="previewJ">-</td>
                    <td id="previewG">-</td>
                </tr>
            </tbody>
        </table>

        <h3 style="margin-top:18px;">رکوردهای ذخیره‌شده (آخرین‌ها)</h3>
        <table class="simple">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>تاریخ شمسی (ورودی)</th>
                    <th>تاریخ میلادی (ذخیره)</th>
                    <th>تبدیل میلادی→جلالی (نمایش)</th>
                    <th>یادداشت</th>
                    <th>زمان ثبت</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r) :
                    $g = isset($r['gregorian_date']) ? $r['gregorian_date'] : '';
                    $jdisplay = '-';
                    if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $g, $m)) {
                        list($jy, $jm, $jd) = gregorian_to_jalali(intval($m[1]), intval($m[2]), intval($m[3]));
                        $jdisplay = sprintf('%04d/%02d/%02d', $jy, $jm, $jd);
                    }
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($r['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($r['jalali_date'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($r['gregorian_date'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($jdisplay, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($r['note'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($r['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        /* ---------- توابع تبدیل در سمت کلاینت (JS) ---------- */
        const jMonthDays = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];

        function isJalaliLeapYear(jy) {
            const remainders = [1, 5, 9, 13, 17, 22, 26, 30];
            return remainders.includes(jy % 33);
        }

        function jalaliToGregorian(jy, jm, jd) {
            var j_days_in_month = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];

            if (isJalaliLeapYear(jy)) {
                j_days_in_month[11] = 30;
            }

            var jy2 = jy - 979;
            var jm2 = jm - 1;
            var jd2 = jd - 1;
            var j_day_no = 365 * jy2 + Math.floor(jy2 / 33) * 8 + Math.floor((jy2 % 33 + 3) / 4);
            for (var i = 0; i < jm2; i++) j_day_no += j_days_in_month[i];
            j_day_no += jd2;
            var g_day_no = j_day_no + 79;
            var gy = 1600 + 400 * Math.floor(g_day_no / 146097);
            g_day_no = g_day_no % 146097;
            if (g_day_no >= 36525) {
                g_day_no--;
                gy += 100 * Math.floor(g_day_no / 36524);
                g_day_no = g_day_no % 36524;
                if (g_day_no >= 365) g_day_no++;
            }
            gy += 4 * Math.floor(g_day_no / 1461);
            g_day_no = g_day_no % 1461;
            if (g_day_no >= 366) {
                g_day_no -= 366;
                gy += Math.floor(g_day_no / 365);
                g_day_no = g_day_no % 365;
            }
            var gregorian_month_days = [31, (((gy % 4 === 0 && gy % 100 !== 0) || (gy % 400 === 0)) ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
            var gm = 0;
            for (var i = 0; i < 12; i++) {
                if (g_day_no < gregorian_month_days[i]) {
                    gm = i + 1;
                    break;
                }
                g_day_no -= gregorian_month_days[i];
            }
            var gd = g_day_no + 1;
            return {
                gy: gy,
                gm: gm,
                gd: gd
            };
        }

        function gregorianToJalali(gy, gm, gd) {
            var g_days_in_month = [31, (((gy % 4 === 0 && gy % 100 !== 0) || (gy % 400 === 0)) ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
            var j_days_in_month = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];

            var gy2 = gy - 1600;
            var gm2 = gm - 1;
            var gd2 = gd - 1;
            var g_day_no = 365 * gy2 + Math.floor((gy2 + 3) / 4) - Math.floor((gy2 + 99) / 100) + Math.floor((gy2 + 399) / 400);
            for (var i = 0; i < gm2; i++) g_day_no += g_days_in_month[i];
            g_day_no += gd2;
            var j_day_no = g_day_no - 79;
            var j_np = Math.floor(j_day_no / 12053);
            j_day_no = j_day_no % 12053;
            var jy = 979 + 33 * j_np + 4 * Math.floor(j_day_no / 1461);
            j_day_no = j_day_no % 1461;
            if (j_day_no >= 366) {
                jy += Math.floor((j_day_no - 1) / 365);
                j_day_no = (j_day_no - 1) % 365;
            }

            if (isJalaliLeapYear(jy)) {
                j_days_in_month[11] = 30;
            }

            var jm = 0;
            for (var i = 0; i < 11; i++) {
                if (j_day_no < j_days_in_month[i]) {
                    jm = i + 1;
                    break;
                }
                j_day_no -= j_days_in_month[i];
            }
            var jd = j_day_no + 1;
            if (jm === 0) jm = 12;
            return {
                jy: jy,
                jm: jm,
                jd: jd
            };
        }

        /* ---------- UI تقویم با event delegation ---------- */
        (function() {
            const jalaliInput = document.getElementById('jalaliInput');
            const gregHidden = document.getElementById('gregorianHidden');
            const calContainer = document.getElementById('calendarContainer');
            const previewJ = document.getElementById('previewJ');
            const previewG = document.getElementById('previewG');
            const clearBtn = document.getElementById('clearBtn');
            const form = document.getElementById('dateForm');

            const monthNames = ["فروردین", "اردیبهشت", "خرداد", "تیر", "مرداد", "شهریور", "مهر", "آبان", "آذر", "دی", "بهمن", "اسفند"];
            const weekNames = ["ش", "ی", "د", "س", "چ", "پ", "ج"];

            let curYear, curMonth, selected = null;
            let todayJalali = null;

            (function init() {
                const d = new Date();
                const j = gregorianToJalali(d.getFullYear(), d.getMonth() + 1, d.getDate());
                curYear = j.jy;
                curMonth = j.jm;
                todayJalali = {
                    jy: j.jy,
                    jm: j.jm,
                    jd: j.jd
                };
            })();

            function changeMonth(delta) {
                curMonth += delta;
                if (curMonth < 1) {
                    curMonth = 12;
                    curYear -= 1;
                }
                if (curMonth > 12) {
                    curMonth = 1;
                    curYear += 1;
                }
                renderCalendar();
            }

            function getJalaliMonthDays(jy, jm) {
                if (jm <= 6) return 31;
                if (jm <= 11) return 30;
                return isJalaliLeapYear(jy) ? 30 : 29;
            }

            function getFirstDayOfMonth(jy, jm) {
                // محاسبه روز اول ماه جلالی
                const gFirst = jalaliToGregorian(jy, jm, 1);
                const gDate = new Date(gFirst.gy, gFirst.gm - 1, gFirst.gd);
                let day = gDate.getDay(); // 0=Sunday, 1=Monday, ..., 6=Saturday

                // تبدیل به شماره روز هفته شمسی (0=شنبه, 1=یکشنبه, ..., 6=جمعه)
                // در تقویم شمسی: شنبه=0, یکشنبه=1, ..., جمعه=6
                // تبدیل از میلادی به شمسی: (day + 3) % 7
                let persianDay = (day + 3) % 7;
                return persianDay;
            }

            function renderCalendar() {
                calContainer.innerHTML = '';

                // هدر با دکمه‌های قبلی/بعدی
                const head = document.createElement('div');
                head.className = 'cal-head';

                const prevBtn = document.createElement('button');
                prevBtn.type = 'button';
                prevBtn.setAttribute('data-action', 'prev');
                prevBtn.textContent = '‹';
                prevBtn.style.cssText = 'font-size:18px; padding:5px 10px;';

                const title = document.createElement('div');
                title.textContent = curYear + ' — ' + monthNames[curMonth - 1];
                title.style.cssText = 'flex-grow:1; text-align:center;';

                const nextBtn = document.createElement('button');
                nextBtn.type = 'button';
                nextBtn.setAttribute('data-action', 'next');
                nextBtn.textContent = '›';
                nextBtn.style.cssText = 'font-size:18px; padding:5px 10px;';

                head.appendChild(prevBtn);
                head.appendChild(title);
                head.appendChild(nextBtn);
                calContainer.appendChild(head);

                // روزهای هفته
                const wdiv = document.createElement('div');
                wdiv.className = 'cal-grid';
                weekNames.forEach(function(w) {
                    const el = document.createElement('div');
                    el.className = 'weekday';
                    el.textContent = w;
                    wdiv.appendChild(el);
                });
                calContainer.appendChild(wdiv);

                // محاسبه روز اول ماه و تعداد روزها
                const startIndex = getFirstDayOfMonth(curYear, curMonth);
                const mdays = getJalaliMonthDays(curYear, curMonth);

                const grid = document.createElement('div');
                grid.className = 'cal-grid';

                // خانه‌های خالی قبل از روز اول ماه
                for (let i = 0; i < startIndex; i++) {
                    const empty = document.createElement('div');
                    empty.className = 'cal-day';
                    empty.style.opacity = '0.3';
                    empty.textContent = '';
                    grid.appendChild(empty);
                }

                // روزهای ماه
                for (let d = 1; d <= mdays; d++) {
                    const cell = document.createElement('div');
                    cell.className = 'cal-day';
                    cell.setAttribute('data-day', d);

                    // بررسی آیا امروز است
                    if (todayJalali &&
                        todayJalali.jy === curYear &&
                        todayJalali.jm === curMonth &&
                        todayJalali.jd === d) {
                        cell.classList.add('today');
                    }

                    // بررسی آیا انتخاب شده است
                    if (selected &&
                        selected.jy === curYear &&
                        selected.jm === curMonth &&
                        selected.jd === d) {
                        cell.classList.add('selected');
                    }

                    cell.textContent = d;
                    grid.appendChild(cell);
                }

                calContainer.appendChild(grid);
                calContainer.style.display = 'block';
                positionCalendarUnderInput();
            }

            // event delegation برای مدیریت کلیک‌ها
            calContainer.addEventListener('click', function(e) {
                let el = e.target;

                // اگر روی متن کلیک شده، والد را بگیر
                if (el.nodeType === 3) {
                    el = el.parentElement;
                }

                if (el.dataset && el.dataset.action) {
                    const action = el.dataset.action;
                    if (action === 'prev') {
                        changeMonth(-1);
                    } else if (action === 'next') {
                        changeMonth(1);
                    }
                    e.stopPropagation();
                    return;
                }

                if (el.classList && el.classList.contains('cal-day') && el.hasAttribute('data-day') && el.textContent !== '') {
                    const day = parseInt(el.getAttribute('data-day'), 10);
                    if (!isNaN(day)) {
                        selected = {
                            jy: curYear,
                            jm: curMonth,
                            jd: day
                        };
                        const pad = n => n < 10 ? '0' + n : '' + n;
                        jalaliInput.value = selected.jy + '/' + pad(selected.jm) + '/' + pad(selected.jd);
                        const g = jalaliToGregorian(selected.jy, selected.jm, selected.jd);
                        gregHidden.value = g.gy + '-' + pad(g.gm) + '-' + pad(g.gd);
                        previewJ.textContent = jalaliInput.value;
                        previewG.textContent = gregHidden.value;
                        calContainer.style.display = 'none';
                    }
                    e.stopPropagation();
                    return;
                }
            });

            // بقیه event listenerها بدون تغییر ...
            jalaliInput.addEventListener('click', function(e) {
                e.stopPropagation();
                if (calContainer.style.display === 'block') {
                    calContainer.style.display = 'none';
                } else {
                    renderCalendar();
                }
            });

            calContainer.addEventListener('mousedown', function(e) {
                e.stopPropagation();
            });

            document.addEventListener('click', function(e) {
                if (!calContainer.contains(e.target) && e.target !== jalaliInput) {
                    calContainer.style.display = 'none';
                }
            });

            clearBtn.addEventListener('click', function() {
                jalaliInput.value = '';
                gregHidden.value = '';
                previewJ.textContent = '-';
                previewG.textContent = '-';
                selected = null;
                calContainer.style.display = 'none';
            });

            form.addEventListener('submit', function(e) {
                if (!gregHidden.value) {
                    e.preventDefault();
                    alert('لطفاً ابتدا یک تاریخ شمسی انتخاب کنید.');
                }
            });

            function positionCalendarUnderInput() {
                const rect = jalaliInput.getBoundingClientRect();
                const parentRect = jalaliInput.parentElement.getBoundingClientRect();
                const offsetRight = parentRect.right - rect.right;
                calContainer.style.top = (rect.bottom - parentRect.top + 6) + 'px';
                calContainer.style.right = offsetRight + 'px';
            }

            window.addEventListener('resize', function() {
                if (calContainer.style.display === 'block') positionCalendarUnderInput();
            });

        })();

    </script>
</body>

</html>