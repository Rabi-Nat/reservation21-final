<?php
// jalali.php - تبدیل‌ها بین میلادی و جلالی

function gregorian_to_jalali($gy, $gm, $gd) {
    $g_days_in_month = [31,28,31,30,31,30,31,31,30,31,30,31];
    $j_days_in_month = [31,31,31,31,31,31,30,30,30,30,30,29];

    $gy -= 1600; $gm -= 1; $gd -= 1;
    $g_day_no = 365*$gy + intdiv($gy+3,4) - intdiv($gy+99,100) + intdiv($gy+399,400);
    for ($i=0;$i<$gm;$i++) $g_day_no += $g_days_in_month[$i];
    if ($gm > 1 && ((($gy+1600)%4==0 && ($gy+1600)%100!=0) || (($gy+1600)%400==0))) $g_day_no += 1;
    $g_day_no += $gd;

    $j_day_no = $g_day_no - 79;
    $j_np = intdiv($j_day_no,12053);
    $j_day_no = $j_day_no % 12053;

    $jy = 979 + 33*$j_np + 4*intdiv($j_day_no,1461);
    $j_day_no = $j_day_no % 1461;

    if ($j_day_no >= 366) {
        $jy += intdiv($j_day_no-1,365);
        $j_day_no = ($j_day_no-1) % 365;
    }

    $jm = 0;
    for ($i=0;$i<11;$i++) {
        if ($j_day_no < $j_days_in_month[$i]) { $jm = $i+1; break; }
        $j_day_no -= $j_days_in_month[$i];
    }
    $jd = $j_day_no + 1;
    if ($jm == 0) $jm = 12;
    return [$jy, $jm, $jd];
}

function jalali_to_gregorian($jy, $jm, $jd) {
    $g_days_in_month = [31,28,31,30,31,30,31,31,30,31,30,31];
    $j_days_in_month = [31,31,31,31,31,31,30,30,30,30,30,29];

    $jy -= 979; $jm -= 1; $jd -= 1;
    $j_day_no = 365*$jy + intdiv($jy,33)*8 + intdiv(($jy%33)+3,4);
    for ($i=0;$i<$jm;$i++) $j_day_no += $j_days_in_month[$i];
    $j_day_no += $jd;

    $g_day_no = $j_day_no + 79;
    $gy = 1600 + 400 * intdiv($g_day_no,146097);
    $g_day_no = $g_day_no % 146097;

    if ($g_day_no >= 36525) {
        $g_day_no--;
        $gy += 100 * intdiv($g_day_no,36524);
        $g_day_no = $g_day_no % 36524;
        if ($g_day_no >= 365) $g_day_no++;
    }

    $gy += 4 * intdiv($g_day_no,1461);
    $g_day_no = $g_day_no % 1461;

    if ($g_day_no >= 366) {
        $g_day_no -= 366;
        $gy += intdiv($g_day_no,365);
        $g_day_no = $g_day_no % 365;
    }

    $i = 0;
    $leap = (($gy%4==0 && $gy%100!=0) || ($gy%400==0));
    while ($g_day_no >= ($g_days_in_month[$i] + ($i==1 && $leap ? 1 : 0))) {
        $g_day_no -= ($g_days_in_month[$i] + ($i==1 && $leap ? 1 : 0));
        $i++;
    }
    $gm = $i + 1;
    $gd = $g_day_no + 1;
    return [$gy, $gm, $gd];
}
