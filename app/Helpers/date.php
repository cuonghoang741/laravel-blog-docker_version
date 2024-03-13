<?php

use Illuminate\Support\Carbon;

/**
 * Return a Carbon instance.
 */
function carbon(string $parseString = '', ?string $tz = null): Carbon
{
    return new Carbon($parseString, $tz);
}

/**
 * Return a formatted Carbon date.
 */
function humanize_date(Carbon $date, string $format = 'd F Y, H:i'): string
{
    return $date->format($format);
}
function isUpdatedWithinOneMonth($updated_at) {
    // Chuyển đổi ngày tháng từ chuỗi thành đối tượng DateTime
    $updatedDate = new DateTime($updated_at);
    $currentDate = new DateTime();

    // Tính số ngày kể từ khi cập nhật đến thời điểm hiện tại
    $interval = $updatedDate->diff($currentDate);
    $daysDiff = $interval->days;
    // Nếu số ngày là nhỏ hơn hoặc bằng 30, thì trả về true, ngược lại trả về false
    return $daysDiff >= 30;
}

function day_diff($daterange) {
    // Phân tách ngày bắt đầu và ngày kết thúc từ chuỗi đầu vào
    $ngay = explode(" - ", $daterange);
    $ngayBatDau = strtotime(substr($ngay[0], 0, 10));
    $ngayKetThuc = strtotime(substr($ngay[1], 0, 10));

    // Tính số ngày giữa hai ngày
    $soNgay = round(($ngayKetThuc - $ngayBatDau) / (60 * 60 * 24));

    return $soNgay;
}
function getDateRange($dateRange) {
    // Tách chuỗi thời gian thành ngày bắt đầu và kết thúc
    $dates = explode(" - ", $dateRange);

    // Chuyển đổi ngày bắt đầu và kết thúc thành đối tượng DateTime
    $startDate = new DateTime($dates[0]);
    $endDate = new DateTime($dates[1]);

    // Khởi tạo mảng để lưu trữ các ngày
    $dateList = array();

    // Lặp qua mỗi ngày từ ngày bắt đầu đến ngày kết thúc
    $currentDate = clone $startDate;
    while ($currentDate <= $endDate) {
        $dateList[] = $currentDate->format('l, d M Y'); // Thêm ngày vào mảng
        $currentDate->modify('+1 day'); // Tăng ngày lên 1
    }

    return $dateList;
}

function getDateFromDateRange($dateRange) {
    // Tách chuỗi thời gian thành ngày bắt đầu và kết thúc
    $dates = explode(" - ", $dateRange);

    // Chuyển đổi ngày bắt đầu và kết thúc thành đối tượng DateTime
    $startDate = new DateTime($dates[0]);
    $endDate = new DateTime($dates[1]);

    return [
        "startDate"=>$startDate->format('d M Y'),
        "endDate"=>$endDate->format('d M Y')
    ];
}



function removeSubstringAfterLastDash($str) {
    $index = strrpos($str, '-'); // Tìm vị trí của ký tự '-' cuối cùng
    if ($index !== false) { // Nếu tìm thấy ký tự '-'
        $new_str = trim(substr($str, 0, $index)); // Cắt chuỗi từ đầu đến ký tự '-' và loại bỏ khoảng trắng ở hai đầu
        return $new_str;
    } else {
        return $str;
    }
}

function randomHexColors($limit) {
    $colors = array();

    for ($i = 0; $i < $limit; $i++) {
        // Generate random RGB values
        $red = mt_rand(0, 255);
        $green = mt_rand(0, 255);
        $blue = mt_rand(0, 255);

        // Convert RGB to hexadecimal
        $hex = sprintf("#%02x%02x%02x", $red, $green, $blue);

        // Add the color to the array
        $colors[] = $hex;
    }

    return $colors;
}
