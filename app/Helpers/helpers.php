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

if (!function_exists('transaction_type_label')) {
    /**
     * Get Arabic translated label for any transaction type
     */
    function transaction_type_label(?string $type, string $context = 'financial'): string
    {
        if (!$type) return '-';

        $translations = [
            'purchase' => $context === 'product' ? 'مشتريات (دخول)' : 'فاتورة مشتريات',
            'sale' => $context === 'product' ? 'مبيعات (سحب)' : 'فاتورة مبيعات',
            'payment_made' => 'سداد نقدية',
            'payment_received' => 'تحصيل نقدية',
            'payment_sent' => 'سداد نقدية',
            'return_purchase' => 'مرتجع مشتريات',
            'return_sale' => 'مرتجع مبيعات',
            'adjustment_add' => 'تسوية بالزيادة',
            'adjustment_sub' => 'تسوية بالنقص',
            'initial_balance' => 'رصيد أول المدة',
            'opening_balance' => 'رصيد افتتاحي',
        ];

        return $translations[$type] ?? $type;
    }
}

