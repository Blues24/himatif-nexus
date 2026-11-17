<?php

namespace Tests\Unit\Services;

use App\Models\Inventaris;
use App\Models\PenyewaanAlat;
use App\Models\User;
use App\Services\PenyewaanAlatService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenyewaanAlatServiceTest extends TestCase
{
    use RefreshDatabase;

    private PenyewaanAlatService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(PenyewaanAlatService::class);
    }

    public function test_submit_request_reserves_when_stock_available(): void
    {
        $user = User::factory()->create();
        $item = Inventaris::factory()->create(['stok' => 10]);

        $request = $this->service->submitRequest([
            'inventaris_alat_id' => $item->inventaris_id,
            'user_id' => $user->user_uuid,
            'jumlah_pinjam' => 3,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_kembali' => now()->addDays(3)->toDateString(),
        ]);

        $this->assertInstanceOf(PenyewaanAlat::class, $request);
        $this->assertSame('pending', $request->status);
        // Stock not deducted yet on submit
        $this->assertSame(10, (int) $item->fresh()->stok);
    }

    public function test_submit_request_throws_when_stock_insufficient(): void
    {
        $user = User::factory()->create();
        $item = Inventaris::factory()->create(['stok' => 2]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Stok alat tidak mencukupi');

        $this->service->submitRequest([
            'inventaris_alat_id' => $item->inventaris_id,
            'user_id' => $user->user_uuid,
            'jumlah_pinjam' => 5,
            'tanggal_pinjam' => now()->toDateString(),
        ]);
    }

    public function test_handle_request_approve_deducts_stock(): void
    {
        $user = User::factory()->create();
        $item = Inventaris::factory()->create(['stok' => 10]);
        $rental = PenyewaanAlat::factory()->create([
            'inventaris_alat_id' => $item->inventaris_id,
            'user_id' => $user->user_uuid,
            'jumlah_pinjam' => 4,
            'status' => 'pending',
        ]);

        $result = $this->service->handleRequest($rental->id, 'approve');

        $this->assertTrue($result);
        $this->assertSame('disetujui', $rental->fresh()->status);
        $this->assertSame(6, (int) $item->fresh()->stok);
    }

    public function test_mark_returned_restores_stock(): void
    {
        $user = User::factory()->create();
        $item = Inventaris::factory()->create(['stok' => 6]);
        $rental = PenyewaanAlat::factory()->create([
            'inventaris_alat_id' => $item->inventaris_id,
            'user_id' => $user->user_uuid,
            'jumlah_pinjam' => 4,
            'status' => 'disetujui',
        ]);

        $result = $this->service->markReturned($rental->id);

        $this->assertTrue($result);
        $this->assertSame('dikembalikan', $rental->fresh()->status);
        $this->assertSame(10, (int) $item->fresh()->stok);
    }
}
