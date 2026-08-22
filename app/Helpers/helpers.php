<?php

if (!function_exists('format_amount')) {
    /**
     * Format currency / money amounts without trailing .00 decimals
     */
    function format_amount($value, int $decimals = 2): string
    {
        if ($value === null || $value === '' || $value === '-') {
            return '-';
        }
        $num = (float)$value;
        if (floor($num) == $num) {
            return number_format($num, 0);
        }
        return rtrim(rtrim(number_format($num, $decimals), '0'), '.');
    }
}

if (!function_exists('format_quantity')) {
    /**
     * Format quantity / weights without trailing .00 decimals
     */
    function format_quantity($value, int $decimals = 2): string
    {
        if ($value === null || $value === '' || $value === '-') {
            return '-';
        }
        $num = (float)$value;
        if (floor($num) == $num) {
            return number_format($num, 0);
        }
        return rtrim(rtrim(number_format($num, $decimals), '0'), '.');
    }
}
