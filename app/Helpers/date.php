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

