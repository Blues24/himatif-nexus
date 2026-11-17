<?php

namespace Tests\Unit\Services;

use App\Models\Transaksi;
use App\Models\User;
use App\Services\TransaksiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransaksiServiceTest extends TestCase
{
    use RefreshDatabase;

    private TransaksiService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(TransaksiService::class);
    }

    public function test_record_creates_transaction(): void
    {
        $user = User::factory()->create();

        $transaksi = $this->service->record([
            'user_id' => $user->user_uuid,
            'total_bayar' => 150000.00,
            'status_transaksi' => 'menunggu',
        ]);

        $this->assertInstanceOf(Transaksi::class, $transaksi);
        $this->assertSame('150000.00', $transaksi->total_bayar);
        $this->assertSame('menunggu', $transaksi->status_transaksi);
    }

    public function test_by_status_filters_transactions(): void
    {
        $user = User::factory()->create();
        Transaksi::factory()->create(['user_id' => $user->user_uuid, 'status_transaksi' => 'menunggu', 'total_bayar' => 100000]);
        Transaksi::factory()->create(['user_id' => $user->user_uuid, 'status_transaksi' => 'selesai', 'total_bayar' => 200000]);

        $result = $this->service->byStatus('menunggu');

        $this->assertCount(1, $result->items());
        $this->assertSame('menunggu', $result->items()[0]->status_transaksi);
    }

    public function test_confirm_updates_pending_to_selesai(): void
    {
        $user = User::factory()->create();
        $transaksi = Transaksi::factory()->create([
            'user_id' => $user->user_uuid,
            'status_transaksi' => 'menunggu',
            'total_bayar' => 50000,
        ]);

        $result = $this->service->confirm($transaksi->transaksi_id);

        $this->assertTrue($result);
        $this->assertSame('selesai', $transaksi->fresh()->status_transaksi);
    }

    public function test_confirm_throws_when_not_pending(): void
    {
        $user = User::factory()->create();
        $transaksi = Transaksi::factory()->create([
            'user_id' => $user->user_uuid,
            'status_transaksi' => 'selesai',
            'total_bayar' => 50000,
        ]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Transaksi tidak dalam status menunggu');

        $this->service->confirm($transaksi->transaksi_id);
    }
}