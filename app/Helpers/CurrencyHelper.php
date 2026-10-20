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
     * Reverse of format(). Indonesian numbering uses dots for thousands
     * and a comma for decimals, so dots are stripped first to avoid
     * creating a second decimal point.
     */
    public static function parse(string $input): float
    {
        $cleaned = preg_replace('/[^\d.,-]/', '', $input);
        $cleaned = str_replace('.', '', $cleaned);
        $cleaned = str_replace(',', '.', $cleaned);

        return (float) $cleaned;
    }
}
