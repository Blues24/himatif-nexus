<?php

namespace Tests\Feature;

use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProdukControllerTest extends TestCase
{
    public function test_index_renders_without_authentication(): void
    {
        $response = $this->get('/produk');

        $response->assertOk();
        $response->assertInertia(function ($page) {
            $page->component('Shop/Index')
                ->has('products')
                ->has('lowStock')
                ->has('filters');
        });
    }

    public function test_index_with_search_returns_matching_products(): void
    {
        Produk::factory()->create(['nama_produk' => 'Kopi Susu Gula Aren']);
        Produk::factory()->create(['nama_produk' => 'Teh Manis']);

        $response = $this->get('/produk?q=kopi');

        $response->assertOk();
        $response->assertInertia(function ($page) {
            $page->where('filters.q', 'kopi')
                ->has('products', 1);
        });
    }

    public function test_show_renders_product_with_formatted_price(): void
    {
        $product = Produk::factory()->create(['harga_produk' => 25000]);

        $response = $this->get('/produk/'.$product->produk_id);

        $response->assertOk();
        $response->assertInertia(function ($page) use ($product) {
            $page->component('Shop/Show')
                ->has('product', function ($assert) use ($product) {
                    $assert
                        ->where('produk_id', $product->produk_id)
                        ->where('harga_formatted', 'Rp 25.000,00');
                });
        });
    }

    public function test_show_returns_404_for_nonexistent_product(): void
    {
        $this->get('/produk/99999')
            ->assertNotFound();
    }

    public function test_unauthenticated_user_cannot_create_product(): void
    {
        $this->post('/produk', [
            'nama_produk' => 'Test',
            'harga_produk' => 10000,
            'stok_produk' => 5,
        ])->assertRedirect();
    }

    public function test_store_creates_product_and_redirects(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/produk', [
            'nama_produk' => 'Kopi Susu',
            'deskripsi_produk' => 'Minuman favorit',
            'harga_produk' => 25000,
            'stok_produk' => 50,
        ]);

        $response->assertRedirect(route('produk.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('produk', ['nama_produk' => 'Kopi Susu']);
    }

    public function test_store_rejects_missing_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/produk', [
            'nama_produk' => '',
            'harga_produk' => 0,
            'stok_produk' => 0,
        ]);

        $response->assertSessionHasErrors(['nama_produk', 'harga_produk']);
    }

    public function test_store_uploads_image(): void
    {
        $user = User::factory()->create();
        Storage::fake('public');

        $file = UploadedFile::fake()->create('image.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($user)->post('/produk', [
            'nama_produk' => 'Kopi Susu',
            'harga_produk' => 25000,
            'stok_produk' => 10,
            'gambar_produk' => $file,
        ]);

        $response->assertRedirect(route('produk.index'));
        Storage::disk('public')->assertExists('produk/'.$file->hashName());
    }

    public function test_update_requires_authentication(): void
    {
        $product = Produk::factory()->create();

        $this->patch('/produk/'.$product->produk_id, [
            'nama_produk' => 'Updated',
            'harga_produk' => 30000,
            'stok_produk' => 10,
        ])->assertRedirect();
    }

    public function test_update_changes_product_attributes(): void
    {
        $user = User::factory()->create();
        $product = Produk::factory()->create(['nama_produk' => 'Original']);

        $response = $this->actingAs($user)->patch('/produk/'.$product->produk_id, [
            'nama_produk' => 'Updated',
            'harga_produk' => 30000,
            'stok_produk' => 20,
        ]);

        $response->assertRedirect(route('produk.edit', $product->produk_id));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('produk', ['nama_produk' => 'Updated']);
    }

    public function test_update_replaces_image(): void
    {
        $user = User::factory()->create();
        Storage::fake('public');

        $oldFile = 'produk/old.jpg';
        Storage::disk('public')->put($oldFile, 'old content');

        $product = Produk::factory()->create(['gambar_produk' => $oldFile]);
        $newFile = UploadedFile::fake()->create('new.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($user)->patch('/produk/'.$product->produk_id, [
            'nama_produk' => $product->nama_produk,
            'harga_produk' => $product->harga_produk,
            'stok_produk' => $product->stok_produk,
            'gambar_produk' => $newFile,
        ]);

        $response->assertRedirect(route('produk.edit', $product->produk_id));
        Storage::disk('public')->assertMissing($oldFile);
        Storage::disk('public')->assertExists('produk/'.$newFile->hashName());
    }

    public function test_destroy_requires_authentication(): void
    {
        $product = Produk::factory()->create();

        $this->delete('/produk/'.$product->produk_id)->assertRedirect();
    }

    public function test_destroy_removes_product(): void
    {
        $user = User::factory()->create();
        $product = Produk::factory()->create();

        $response = $this->actingAs($user)->delete('/produk/'.$product->produk_id);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('produk', ['produk_id' => $product->produk_id]);
    }
}
