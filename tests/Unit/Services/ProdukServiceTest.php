<?php

namespace Tests\Unit\Services;

use App\Models\Produk;
use App\Services\ProdukService;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProdukServiceTest extends TestCase
{
    private ProdukService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(ProdukService::class);
    }

    public function test_search_finds_products_by_name_case_insensitive(): void
    {
        Produk::factory()->create(['nama_produk' => 'Kopi Susu Gula Aren']);
        Produk::factory()->create(['nama_produk' => 'Teh Manis']);

        $result = $this->service->search('kopi');

        $this->assertCount(1, $result->items());
        $this->assertSame('Kopi Susu Gula Aren', $result->items()[0]->nama_produk);
    }

    public function test_search_returns_empty_when_no_match(): void
    {
        Produk::factory()->create(['nama_produk' => 'Kopi Susu']);

        $result = $this->service->search('teh');

        $this->assertCount(0, $result->items());
    }

    public function test_get_low_stock_returns_products_at_or_below_threshold(): void
    {
        Produk::factory()->create(['nama_produk' => 'Low Stock Item', 'stok_produk' => 3]);
        Produk::factory()->create(['nama_produk' => 'Enough Stock', 'stok_produk' => 10]);
        Produk::factory()->create(['nama_produk' => 'Zero Stock', 'stok_produk' => 0]);

        $result = $this->service->getLowStock(5);

        $this->assertCount(2, $result);
        $this->assertEquals([0, 3], $result->pluck('stok_produk')->sort()->values()->toArray());
    }

    public function test_adjust_stock_increases_quantity(): void
    {
        $product = Produk::factory()->create(['stok_produk' => 10]);

        $this->service->adjustStock($product->produk_id, 5);

        $this->assertSame(15, $product->fresh()->stok_produk);
    }

    public function test_adjust_stock_decreases_quantity(): void
    {
        $product = Produk::factory()->create(['stok_produk' => 10]);

        $this->service->adjustStock($product->produk_id, -3);

        $this->assertSame(7, $product->fresh()->stok_produk);
    }

    public function test_adjust_stock_throws_when_stock_would_go_negative(): void
    {
        $product = Produk::factory()->create(['stok_produk' => 5]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Stok tidak mencukupi');

        $this->service->adjustStock($product->produk_id, -10);
    }

    public function test_adjust_stock_throws_when_product_not_found(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Produk tidak ditemukan');

        $this->service->adjustStock(99999, 10);
    }

    public function test_adjust_stock_is_atomic(): void
    {
        $product = Produk::factory()->create(['stok_produk' => 100]);

        DB::transaction(function () use ($product) {
            $this->service->adjustStock($product->produk_id, -50);
            $this->service->adjustStock($product->produk_id, -60);
        });
    }
}
