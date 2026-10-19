<?php

namespace App\Helpers;

class CurrencyHelper
{
    /**
     * Format a decimal value as Indonesian Rupiah (IDR).
     */
    public static function format(mixed $amount, bool $prefixSymbol = true): string
    {
        $formatted = number_format((float) $amount, 2, ',', '.');

        return $prefixSymbol ? 'Rp '.$formatted : $formatted;
    }

    /**
     * Parse a currency string back to a decimal value (removes Rp, dots, commas).
     */
    public static function parse(string $input): float
    {
        $cleaned = preg_replace('/[^\d.,-]/', '', $input);
        // Replace comma thousand separators then normalize decimal point
        $cleaned = str_replace(',', '.', $cleaned);

        return (float) $cleaned;
    }
}
