<?php

if (!function_exists('formatTanggal')) {
    function formatTanggal($date, $withTime = false) {
        if (!$date) return '-';
        
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $carbon = \Carbon\Carbon::parse($date)->setTimezone('Asia/Makassar');
        $format = $carbon->day . ' ' . $bulan[$carbon->month] . ' ' . $carbon->year;
        
        if ($withTime) {
            $format .= ' ' . $carbon->format('H:i');
        }
        
        return $format;
    }
}