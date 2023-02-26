<?php

/* get current uri without prefix */
// function uri($path = ''){
//     $route = '/' . Request::path();
//     $prefix = Route::current()->getPrefix() . '/';
//     if (!empty(explode($prefix, $route)[1]))
//         return explode($prefix, $route)[1] . ($path == '' ? '' : '/'.$path);
//     return !empty(explode('/', Route::current()->getPrefix())[1]) ? explode('/', Route::current()->getPrefix())[1] : '';
// }

function leading_zero($_num=1, $length=5){
    return str_pad($_num, $length, '0', STR_PAD_LEFT);
}

function time_to_text($time){
    $old_time = strtotime($time);
    $new_time = time();
    $seconds = $new_time - $old_time;
    if ($seconds < 60)
        return (int)$seconds . ' giây';
    if ($seconds < 3600)
        return (int)($seconds/60) . ' phút';
    if ($seconds < 86400)
        return (int)($seconds/3600) . ' giờ';
    if ($seconds < 604800)
        return (int)($seconds/86400) . ' ngày';
    if ($seconds < 31536000)
        return (int)($seconds/604800) . ' tuần';
    return (int)($seconds/31536000) . ' năm';
}

function datetime_now(){
    return date("Y-m-d H:i:s");
}

function array_get_default($array, $index, $default = ''){
    if (!empty($array[$index]))
        return $array[$index];
    return $default;
}

function change_the_date($date, $quantity = '-1', $unit = 'day'){
    $day_before = date('Y-m-d H:i:s', strtotime($date.' '.$quantity.' '.$unit));
    return $day_before;
}

function format_size($size, $precision = 2){
    if ($size == 0)
        return $size.' B';
    $base = log($size, 1024);
    $suffixes = array('', 'KB', 'MB', 'GB', 'TB');   
    return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
}

function get_file_size($dir){
    $file_size = 0;
    if (!file_exists($dir))
        return format_size($file_size); 
    if (!is_file($dir)){
        $target = new \DirectoryIterator($dir);
        foreach( \File::allFiles($dir) as $file){
            $file_size += $file->getSize();
        }
    } else
        $file_size = \File::size($dir);
    return format_size($file_size);

}

/* get current request URI */
function request_uri(){
    return Request::path();
}

function state_to_text($state){
    switch ($state){
        case 'CONTACT':
            return "<span class='label label-success'>CONTACT</span>";
        case 'LEAD':
            return "<span class='label label-warning'>LEAD</span>";
        case 'CLIENT':
            return "<span class='label label-danger'>CLIENT</span>";
        default:
            return "<span class='label label-info'>DRAFT</span>";
    }
}

function type_to_text($state){
    switch ($state){
        case 'CONTACT':
            return "<span class='label label-success'>Màu Xanh Lá</span>";
        case 'LEAD':
            return "<span class='label label-warning'>Màu vàng vàng</span>";
        case 'CLIENT':
            return "<span class='label label-danger'>Màu Đỏ Đỏ</span>";
        default:
            return "<span class='label label-info'>DRAFT</span>";
    }
}

function state_to_label($state){
    switch ($state){
        case 'CONTACT':
            return "<span class='label label-success'>Màu Xanh Lá</span>";
        case 'LEAD':
            return "<span class='label label-default'>Màu vàng vàng</span>";
        case 'CLIENT':
            return "<span class='label label-danger'>Màu Đỏ Đô</span>";
        default:
            return "<span class='label label-info'>Màu Xanh Da Trời</span>";
    }
}

function convert_number_to_words($number) {
    $hyphen      = ' ';
    $conjunction = '  ';
    $separator   = ' ';
    $negative    = 'âm ';
    $decimal     = ' phẩy ';
    $dictionary  = array(
        0                   => 'không',
        1                   => 'một',
        2                   => 'hai',
        3                   => 'ba',
        4                   => 'bốn',
        5                   => 'năm',
        6                   => 'sáu',
        7                   => 'bảy',
        8                   => 'tám',
        9                   => 'chín',
        10                  => 'mười',
        11                  => 'mười một',
        12                  => 'mười hai',
        13                  => 'mười ba',
        14                  => 'mười bốn',
        15                  => 'mười năm',
        16                  => 'mười sáu',
        17                  => 'mười bảy',
        18                  => 'mười tám',
        19                  => 'mười chín',
        20                  => 'hai mươi',
        30                  => 'ba mươi',
        40                  => 'bốn mươi',
        50                  => 'năm mươi',
        60                  => 'sáu mươi',
        70                  => 'bảy mươi',
        80                  => 'tám mươi',
        90                  => 'chín mươi',
        100                 => 'trăm',
        1000                => 'nghìn',
        1000000             => 'triệu',
        1000000000          => 'tỷ',
        1000000000000       => 'nghìn tỷ',
        1000000000000000    => 'nghìn triệu triệu',
        1000000000000000000 => 'tỷ tỷ'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}
function is_base64($s){
    return (bool) preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s);
}
function sw_get_current_weekday($_date = null) {
    // date_default_timezone_set('Asia/Ho_Chi_Minh');
    $_date = !empty($_date) ? $_date : date('Y-d-m');
    $weekday = date("l", strtotime($_date));
    $weekday = strtolower($weekday);
    switch($weekday) {
        case 'monday':
            $weekday = 'Thứ hai';
            break;
        case 'tuesday':
            $weekday = 'Thứ ba';
            break;
        case 'wednesday':
            $weekday = 'Thứ tư';
            break;
        case 'thursday':
            $weekday = 'Thứ năm';
            break;
        case 'friday':
            $weekday = 'Thứ sáu';
            break;
        case 'saturday':
            $weekday = 'Thứ bảy';
            break;
        default:
            $weekday = 'Chủ nhật';
            break;
    }
    return $weekday;
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'năm',
        'm' => 'tháng',
        'w' => 'tuần',
        'd' => 'ngày',
        'h' => 'giờ',
        'i' => 'phút',
        's' => 'giây',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' trước' : 'bây giờ';
}

function server_busy($numer){
    if (PHP_OS == 'Linux' AND @file_exists('/proc/loadavg') AND $filestuff = @file_get_contents('/proc/loadavg')){
        $loadavg = explode(' ', $filestuff);
        if (trim($loadavg[0]) > $numer) {
            return 'Lượng truy cập đang quá tải, mời bạn quay lại sau vài phút.';
        }
    }
    return false;
}

function number_of_working_days($startDate,$endDate,$holidays=[]){
    $endDate = strtotime($endDate);
    $startDate = strtotime($startDate);

    $days = ($endDate - $startDate) / 86400 + 1;

    $no_full_weeks = floor($days / 7);
    $no_remaining_days = fmod($days, 7);

    $the_first_day_of_week = date("N", $startDate);
    $the_last_day_of_week = date("N", $endDate);

    if ($the_first_day_of_week <= $the_last_day_of_week) {
        if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
        if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
    } else {
        if ($the_first_day_of_week == 7) {
            $no_remaining_days--;
            if ($the_last_day_of_week == 6)
                $no_remaining_days--;
        } else
            $no_remaining_days -= 2;
    }

    $workingDays = $no_full_weeks * 5;
    if ($no_remaining_days > 0 )
        $workingDays += $no_remaining_days;

    foreach($holidays as $holiday){
        $time_stamp=strtotime($holiday);
        if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
            $workingDays--;
    }

    return $workingDays;
}
