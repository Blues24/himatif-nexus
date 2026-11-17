<?php

namespace Tests\Unit\Services;

use App\Models\Inventaris;
use App\Services\InventarisService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventarisServiceTest extends TestCase
{
    use RefreshDatabase;

    private InventarisService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(InventarisService::class);
    }

    public function test_by_condition_filters_inventory(): void
    {
        Inventaris::factory()->baik()->create(['nama_barang' => 'Proyektor Epson']);
        Inventaris::factory()->rusak()->create(['nama_barang' => 'Kabel HDMI Rusak']);

        $result = $this->service->byCondition('baik');

        $this->assertCount(1, $result->items());
        $this->assertSame('Proyektor Epson', $result->items()[0]->nama_barang);
    }

    public function test_adjust_stock_increases_and_decreases_atomically(): void
    {
        $item = Inventaris::factory()->create(['stok' => 10]);

        $this->service->adjustStock($item->inventaris_id, 5);
        $this->assertSame(15, (int) $item->fresh()->stok);

        $this->service->adjustStock($item->inventaris_id, -3);
        $this->assertSame(12, (int) $item->fresh()->stok);
    }

    public function test_adjust_stock_throws_when_stock_would_go_negative(): void
    {
        $item = Inventaris::factory()->create(['stok' => 5]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Stok tidak mencukupi');

        $this->service->adjustStock($item->inventaris_id, -10);
    }

    public function test_adjust_stock_throws_when_item_not_found(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Alat inventaris tidak ditemukan');

        $this->service->adjustStock(99999, 1);
    }
}
