<?php 

if (!function_exists('format_date')) {
    function format_date(string $date, string $format = 'Y-m-d H:i:s'): ?string {
        $timestamp = strtotime($date);
        if ($timestamp === false) {
            return null; // Invalid date
        }
        return date($format, $timestamp);
    }
}

if (!function_exists('format_number')) {
    function format_number(float $number, int $decimals = 2): string {
        return number_format($number, $decimals, '.', '');
    }
}

if (!function_exists('format_currency')) {
    function format_currency(float $amount, string $currencySymbol = '$', int $decimals = 2): string {
        return $currencySymbol . number_format($amount, $decimals, '.', '');
    }
}

if (!function_exists('format_percentage')) {
    function format_percentage(float $value, int $decimals = 2): string {
        return number_format($value, $decimals) . '%';  
    }
}
