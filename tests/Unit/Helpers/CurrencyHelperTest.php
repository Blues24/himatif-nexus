<?php

namespace Tests\Unit\Helpers;

use App\Helpers\CurrencyHelper;
use Tests\TestCase;

class CurrencyHelperTest extends TestCase
{
    public function test_format_adds_rupiah_prefix_and_indonesian_separators(): void
    {
        $this->assertSame('Rp 1.234.567,89', CurrencyHelper::format(1234567.89));
    }

    public function test_format_without_prefix(): void
    {
        $this->assertSame('1.234.567,89', CurrencyHelper::format(1234567.89, prefixSymbol: false));
    }

    public function test_parse_indonesian_format_to_float(): void
    {
        $this->assertSame(1234567.89, CurrencyHelper::parse('Rp 1.234.567,89'));
    }

    public function test_parse_without_currency_symbol(): void
    {
        $this->assertSame(1234567.89, CurrencyHelper::parse('1.234.567,89'));
    }

    public function test_parse_with_spaces(): void
    {
        $this->assertSame(50000.00, CurrencyHelper::parse('Rp 50.000,00'));
    }

    public function test_parse_zero(): void
    {
        $this->assertSame(0.0, CurrencyHelper::parse('Rp 0,00'));
    }

    public function test_parse_negative_value(): void
    {
        $this->assertSame(-25000.00, CurrencyHelper::parse('-Rp 25.000,00'));
    }
}
