<?php

namespace Tests\Feature\Portal;

use App\Models\Inventaris;
use App\Models\PenyewaanAlat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenyewaanAlatControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_renders_without_authentication(): void
    {
        $response = $this->get('/penyewaan');

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page->component('Inventory/Loans')->has('loans'));
    }

    public function test_unauthenticated_user_cannot_create_loan(): void
    {
        $inventaris = Inventaris::factory()->baik()->create();

        $this->post('/penyewaan', [
            'inventaris_alat_id' => $inventaris->inventaris_id,
            'jumlah_pinjam' => 2,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_kembali' => now()->addDays(3)->toDateString(),
        ])->assertRedirect();
    }

    public function test_store_submits_loan_request_and_redirects(): void
    {
        $user = User::factory()->create();
        $inventaris = Inventaris::factory()->baik()->create(['stok' => 10]);

        $response = $this->actingAs($user)->post('/penyewaan', [
            'inventaris_alat_id' => $inventaris->inventaris_id,
            'user_id' => $user->user_uuid,
            'jumlah_pinjam' => 2,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_kembali' => now()->addDays(3)->toDateString(),
        ]);

        $response->assertRedirect(route('penyewaan.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('penyewaan_alats', [
            'inventaris_alat_id' => $inventaris->inventaris_id,
            'jumlah_pinjam' => 2,
            'status' => 'pending',
        ]);
    }

    public function test_store_rejects_loan_when_stock_insufficient(): void
    {
        $user = User::factory()->create();
        $inventaris = Inventaris::factory()->create(['stok' => 1]);

        $response = $this->actingAs($user)->post('/penyewaan', [
            'inventaris_alat_id' => $inventaris->inventaris_id,
            'jumlah_pinjam' => 5,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_kembali' => now()->addDays(3)->toDateString(),
        ]);

        $response->assertSessionHasErrors();
    }
}