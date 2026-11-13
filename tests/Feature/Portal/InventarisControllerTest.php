<?php

namespace Tests\Feature\Portal;

use App\Models\Inventaris;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventarisControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_renders_without_authentication(): void
    {
        $response = $this->get('/inventaris');

        $response->assertOk();
        $response->assertInertia(function ($page) {
            $page->component('Inventory/Index')
                ->has('baik')
                ->has('rusak')
                ->has('hilang');
        });
    }

    public function test_unauthenticated_user_cannot_create_inventaris(): void
    {
        $this->post('/inventaris', [
            'nama_barang' => 'Test Item',
            'stok' => 10,
            'kondisi' => 'baik',
        ])->assertRedirect();
    }

    public function test_store_creates_inventaris_and_redirects(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/inventaris', [
            'nama_barang' => 'Laptop Asus',
            'stok' => 5,
            'kondisi' => 'baik',
        ]);

        $response->assertRedirect(route('inventaris.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('inventaris', ['nama_barang' => 'Laptop Asus']);
    }

    public function test_store_rejects_missing_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/inventaris', [
            'nama_barang' => '',
            'stok' => 0,
            'kondisi' => 'invalid',
        ]);

        $response->assertSessionHasErrors(['nama_barang', 'stok', 'kondisi']);
    }

    public function test_unauthenticated_user_cannot_edit_inventaris(): void
    {
        $item = Inventaris::factory()->create();

        $this->patch('/inventaris/'.$item->inventaris_id, [
            'nama_barang' => 'Updated',
        ])->assertRedirect();
    }

    public function test_update_changes_inventaris_attributes(): void
    {
        $user = User::factory()->create();
        $item = Inventaris::factory()->create(['nama_barang' => 'Original', 'stok' => 5]);

        $response = $this->actingAs($user)->patch('/inventaris/'.$item->inventaris_id, [
            'nama_barang' => 'Updated',
            'stok' => 10,
            'kondisi' => 'rusak',
        ]);

        $response->assertRedirect(route('inventaris.edit', $item->inventaris_id));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('inventaris', [
            'inventaris_id' => $item->inventaris_id,
            'nama_barang' => 'Updated',
            'stok' => 10,
        ]);
    }

    public function test_unauthenticated_user_cannot_delete_inventaris(): void
    {
        $item = Inventaris::factory()->create();

        $this->delete('/inventaris/'.$item->inventaris_id)->assertRedirect();
    }

    public function test_destroy_removes_inventaris(): void
    {
        $user = User::factory()->create();
        $item = Inventaris::factory()->create();

        $response = $this->actingAs($user)->delete('/inventaris/'.$item->inventaris_id);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('inventaris', ['inventaris_id' => $item->inventaris_id]);
    }
}