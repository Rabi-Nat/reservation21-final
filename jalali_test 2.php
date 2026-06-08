<!doctype html>
<html lang="fa">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>فرم چندتایی تاریخ شمسی</title>
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

        .date-input-group {
            border: 1px solid #e0e0e0;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            background: #f9f9f9;
            position: relative;
        }

        .date-input-group:last-child {
            margin-bottom: 0;
        }

        .remove-btn {
            position: absolute;
            left: 15px;
            top: 15px;
            background: #ff4757;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
            cursor: pointer;
        }

        .add-btn {
            background: #2ed573;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 10px 15px;
            cursor: pointer;
            margin-bottom: 15px;
        }

        input[type=text],
        input[type=hidden] {
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ddd;
            font-size: 15px;
            margin-bottom: 10px;
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

        .cal-head button {
            background: #0555c4;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 6px 10px;
            cursor: pointer;
            font-weight: bold;
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
            padding: 4px 0;
            background-color: #f8fafc;
            border-radius: 4px;
            margin: 2px 0;
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
        <h2>فرم چندتایی تاریخ شمسی</h2>

        <?php
        require_once 'database.php';
        require_once 'jalali.php';

        $errors = array();
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // دریافت آرایه‌های تاریخ‌ها
            $jalali_dates = isset($_POST['jalali_date']) ? $_POST['jalali_date'] : array();
            $gregorian_dates = isset($_POST['gregorian_date']) ? $_POST['gregorian_date'] : array();
            $notes = isset($_POST['note']) ? $_POST['note'] : array();

            $valid_records = 0;
            
            // پردازش هر رکورد
            foreach ($jalali_dates as $index => $jalali_raw) {
                $gregorian_raw = isset($gregorian_dates[$index]) ? $gregorian_dates[$index] : '';
                $note_raw = isset($notes[$index]) ? $notes[$index] : '';

                $jalali = test_input($jalali_raw);
                $gregorian = test_input($gregorian_raw);
                $note = test_input($note_raw);

                // اعتبارسنجی
                if ($jalali === '' || $gregorian === '') {
                    continue; // رد کردن رکوردهای خالی
                }

                if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $gregorian)) {
                    $errors[] = "فرمت تاریخ میلادی نامعتبر است برای تاریخ شمسی: " . htmlspecialchars($jalali);
                    continue;
                }

                $dt = DateTime::createFromFormat('Y-m-d', $gregorian);
                if (!$dt) {
                    $errors[] = "تبدیل تاریخ میلادی ناموفق بود برای تاریخ شمسی: " . htmlspecialchars($jalali);
                    continue;
                }

                // ذخیره در دیتابیس
                $to_store = $dt->format('Y-m-d');
                $sql = "INSERT INTO jalali_dates (jalali_date, gregorian_date, note) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                
                if ($stmt === false) {
                    $errors[] = "خطا در آماده‌سازی پرس‌وجو: " . mysqli_error($conn);
                    continue;
                }

                mysqli_stmt_bind_param($stmt, 'sss', $jalali, $to_store, $note);
                $exec = mysqli_stmt_execute($stmt);
                
                if ($exec) {
                    $valid_records++;
                } else {
                    $errors[] = "خطا در ذخیره تاریخ شمسی " . htmlspecialchars($jalali) . ": " . mysqli_stmt_error($stmt);
                }
                
                mysqli_stmt_close($stmt);
            }

            if ($valid_records > 0) {
                $success = "تعداد $valid_records رکورد با موفقیت ذخیره شد.";
            } elseif (empty($errors) && count($jalali_dates) > 0) {
                $errors[] = "هیچ رکورد معتبری برای ذخیره یافت نشد.";
            }
        }
        ?>

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

        <form id="multiDateForm" method="post" action="">
            <div id="dateInputsContainer">
                <!-- اولین فیلد تاریخ -->
                <div class="date-input-group" data-index="0">
                    <button type="button" class="remove-btn" style="display:none;">حذف</button>
                    
                    <div class="row" style="position:relative; display:inline-block;">
                        <label>تاریخ (شمسی)</label>
                        <input type="text" class="jalali-input" name="jalali_date[]" placeholder="مثال: 1404/07/05" readonly style="width:220px;" />
                        <input type="hidden" class="gregorian-input" name="gregorian_date[]" />
                        <button type="button" class="clear-btn">پاک کردن</button>

                        <div class="calendar"></div>
                    </div>

                    <div class="row">
                        <label>یادداشت (اختیاری)</label>
                        <input type="text" name="note[]" class="note-input" style="width:100%;" />
                    </div>
                </div>
            </div>

            <button type="button" id="addDateBtn" class="add-btn">+ افزودن تاریخ دیگر</button>

            <div class="row">
                <button type="submit">ذخیره همه تاریخ‌ها</button>
            </div>
        </form>
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

        /* ---------- مدیریت چندین فیلد تاریخ ---------- */
        (function() {
            const container = document.getElementById('dateInputsContainer');
            const addBtn = document.getElementById('addDateBtn');
            const form = document.getElementById('multiDateForm');

            const monthNames = ["فروردین", "اردیبهشت", "خرداد", "تیر", "مرداد", "شهریور", "مهر", "آبان", "آذر", "دی", "بهمن", "اسفند"];
            const weekNames = ["ش", "ی", "د", "س", "چ", "پ", "ج"];

            // کلاس برای مدیریت هر تقویم
            class CalendarManager {
                constructor(group) {
                    this.group = group;
                    this.jalaliInput = group.querySelector('.jalali-input');
                    this.gregorianInput = group.querySelector('.gregorian-input');
                    this.calendar = group.querySelector('.calendar');
                    this.clearBtn = group.querySelector('.clear-btn');
                    
                    this.curYear = null;
                    this.curMonth = null;
                    this.selected = null;
                    this.todayJalali = null;
                    
                    this.init();
                    this.bindEvents();
                }
                
                init() {
                    const d = new Date();
                    const j = gregorianToJalali(d.getFullYear(), d.getMonth() + 1, d.getDate());
                    this.curYear = j.jy;
                    this.curMonth = j.jm;
                    this.todayJalali = {
                        jy: j.jy,
                        jm: j.jm,
                        jd: j.jd
                    };
                }
                
                bindEvents() {
                    this.jalaliInput.addEventListener('click', (e) => {
                        e.stopPropagation();
                        this.toggleCalendar();
                    });
                    
                    this.clearBtn.addEventListener('click', () => {
                        this.clearSelection();
                    });
                    
                    // بستن تقویم وقتی خارج از آن کلیک شود
                    document.addEventListener('click', (e) => {
                        if (!this.calendar.contains(e.target) && e.target !== this.jalaliInput) {
                            this.calendar.style.display = 'none';
                        }
                    });
                }
                
                toggleCalendar() {
                    if (this.calendar.style.display === 'block') {
                        this.calendar.style.display = 'none';
                    } else {
                        // اگر تاریخ‌ای انتخاب شده، تقویم را روی آن ماه نشان بده
                        if (this.jalaliInput.value) {
                            const parts = this.jalaliInput.value.split('/');
                            if (parts.length === 3) {
                                this.curYear = parseInt(parts[0]);
                                this.curMonth = parseInt(parts[1]);
                            }
                        }
                        this.renderCalendar();
                        this.calendar.style.display = 'block';
                        this.positionCalendar();
                    }
                }
                
                positionCalendar() {
                    const rect = this.jalaliInput.getBoundingClientRect();
                    const parentRect = this.jalaliInput.parentElement.getBoundingClientRect();
                    const offsetRight = parentRect.right - rect.right;
                    this.calendar.style.top = (rect.bottom - parentRect.top + 6) + 'px';
                    this.calendar.style.right = offsetRight + 'px';
                }
                
                changeMonth(delta) {
                    this.curMonth += delta;
                    if (this.curMonth < 1) {
                        this.curMonth = 12;
                        this.curYear -= 1;
                    }
                    if (this.curMonth > 12) {
                        this.curMonth = 1;
                        this.curYear += 1;
                    }
                    this.renderCalendar();
                }
                
                getJalaliMonthDays(jy, jm) {
                    if (jm <= 6) return 31;
                    if (jm <= 11) return 30;
                    return isJalaliLeapYear(jy) ? 30 : 29;
                }
                
                getFirstDayOfMonth(jy, jm) {
                    const gFirst = jalaliToGregorian(jy, jm, 1);
                    const gDate = new Date(gFirst.gy, gFirst.gm - 1, gFirst.gd);
                    let day = gDate.getDay();
                    let persianDay = (day + 3) % 7;
                    return persianDay;
                }
                
                renderCalendar() {
                    this.calendar.innerHTML = '';
                    
                    // هدر با دکمه‌های قبلی/بعدی
                    const head = document.createElement('div');
                    head.className = 'cal-head';
                    
                    const prevBtn = document.createElement('button');
                    prevBtn.type = 'button';
                    prevBtn.textContent = '‹';
                    prevBtn.style.cssText = 'font-size:18px; padding:5px 10px;';
                    prevBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        this.changeMonth(-1);
                    });
                    
                    const title = document.createElement('div');
                    title.textContent = this.curYear + ' — ' + monthNames[this.curMonth - 1];
                    title.style.cssText = 'flex-grow:1; text-align:center;';
                    
                    const nextBtn = document.createElement('button');
                    nextBtn.type = 'button';
                    nextBtn.textContent = '›';
                    nextBtn.style.cssText = 'font-size:18px; padding:5px 10px;';
                    nextBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        this.changeMonth(1);
                    });
                    
                    head.appendChild(prevBtn);
                    head.appendChild(title);
                    head.appendChild(nextBtn);
                    this.calendar.appendChild(head);
                    
                    // روزهای هفته
                    const wdiv = document.createElement('div');
                    wdiv.className = 'cal-grid';
                    weekNames.forEach(function(w) {
                        const el = document.createElement('div');
                        el.className = 'weekday';
                        el.textContent = w;
                        wdiv.appendChild(el);
                    });
                    this.calendar.appendChild(wdiv);
                    
                    // محاسبه روز اول ماه و تعداد روزها
                    const startIndex = this.getFirstDayOfMonth(this.curYear, this.curMonth);
                    const mdays = this.getJalaliMonthDays(this.curYear, this.curMonth);
                    
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
                        cell.textContent = d;
                        
                        // بررسی آیا امروز است
                        if (this.todayJalali &&
                            this.todayJalali.jy === this.curYear &&
                            this.todayJalali.jm === this.curMonth &&
                            this.todayJalali.jd === d) {
                            cell.classList.add('today');
                        }
                        
                        // بررسی آیا انتخاب شده است
                        if (this.selected &&
                            this.selected.jy === this.curYear &&
                            this.selected.jm === this.curMonth &&
                            this.selected.jd === d) {
                            cell.classList.add('selected');
                        }
                        
                        cell.addEventListener('click', (e) => {
                            e.stopPropagation();
                            this.selectDate(d);
                        });
                        
                        grid.appendChild(cell);
                    }
                    
                    this.calendar.appendChild(grid);
                    this.positionCalendar();
                }
                
                selectDate(day) {
                    this.selected = {
                        jy: this.curYear,
                        jm: this.curMonth,
                        jd: day
                    };
                    
                    const pad = n => n < 10 ? '0' + n : '' + n;
                    this.jalaliInput.value = this.selected.jy + '/' + pad(this.selected.jm) + '/' + pad(this.selected.jd);
                    
                    const g = jalaliToGregorian(this.selected.jy, this.selected.jm, this.selected.jd);
                    this.gregorianInput.value = g.gy + '-' + pad(g.gm) + '-' + pad(g.gd);
                    
                    this.calendar.style.display = 'none';
                }
                
                clearSelection() {
                    this.jalaliInput.value = '';
                    this.gregorianInput.value = '';
                    this.selected = null;
                    this.calendar.style.display = 'none';
                }
            }

            const calendarManagers = new Map();
            
            // راه‌اندازی اولیه برای گروه‌های تاریخ
            function initDateGroup(group) {
                const manager = new CalendarManager(group);
                calendarManagers.set(group, manager);
                
                const removeBtn = group.querySelector('.remove-btn');
                removeBtn.addEventListener('click', function() {
                    if (container.querySelectorAll('.date-input-group').length > 1) {
                        calendarManagers.delete(group);
                        group.remove();
                    } else {
                        alert('حداقل یک فیلد تاریخ باید وجود داشته باشد.');
                    }
                });
            }

            // افزودن فیلد تاریخ جدید
            addBtn.addEventListener('click', function() {
                const groups = container.querySelectorAll('.date-input-group');
                const newIndex = groups.length;
                
                const firstGroup = groups[0];
                const newGroup = firstGroup.cloneNode(true);
                
                // به روزرسانی index
                newGroup.setAttribute('data-index', newIndex);
                
                // پاک کردن مقادیر
                const jalaliInput = newGroup.querySelector('.jalali-input');
                const gregorianInput = newGroup.querySelector('.gregorian-input');
                const noteInput = newGroup.querySelector('.note-input');
                
                jalaliInput.value = '';
                gregorianInput.value = '';
                noteInput.value = '';
                
                // نمایش دکمه حذف
                const removeBtn = newGroup.querySelector('.remove-btn');
                removeBtn.style.display = 'block';
                
                // اضافه کردن به container
                container.appendChild(newGroup);
                
                // راه‌اندازی calendar manager برای گروه جدید
                initDateGroup(newGroup);
            });

            // راه‌اندازی اولیه برای اولین گروه
            initDateGroup(container.querySelector('.date-input-group'));

        })();
    </script>
</body>
</html>