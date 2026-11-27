<?php

namespace Tests\Feature\DanusSHop;

use App\Jobs\CompressImageJob;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProdukCompressionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Bus::fake([CompressImageJob::class]);
    }

    public function test_store_dispatches_compression_job_on_gambar_produk_upload(): void
    {
        $user = User::factory()->create();
        Storage::fake('public');

        $file = UploadedFile::fake()->image('product.jpg', 1200, 900);

        $response = $this->actingAs($user)->post('/produk', [
            'nama_produk' => 'Kaos HIMATIF',
            'deskripsi_produk' => 'Kaos official HIMATIF 2025.',
            'harga_produk' => 75000,
            'stok_produk' => 50,
            'gambar_produk' => $file,
        ]);

        $response->assertRedirect(route('produk.index'));
        Storage::disk('public')->assertExists('produk/'.$file->hashName());

        Bus::assertDispatched(CompressImageJob::class, function (CompressImageJob $job) use ($file) {
            return str_ends_with($job->storagePath, $file->hashName());
        });
    }

    public function test_store_without_image_no_job_dispatched(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/produk', [
            'nama_produk' => 'Digital Product',
            'deskripsi_produk' => 'E-book tutorial.',
            'harga_produk' => 25000,
            'stok_produk' => 999,
        ]);

        $response->assertRedirect(route('produk.index'));
        Bus::assertNotDispatched(CompressImageJob::class);
    }

    public function test_update_replaces_product_image_and_dispatches_job(): void
    {
        $user = User::factory()->create();
        $produk = Produk::factory()->create([
            'gambar_produk' => null,
        ]);
        Storage::fake('public');

        $newFile = UploadedFile::fake()->image('new-product.jpg', 800, 600);

        $response = $this->actingAs($user)->put('/produk/'.$produk->produk_id, [
            'nama_produk' => 'Updated Product',
            'deskripsi_produk' => 'New description.',
            'harga_produk' => 85000,
            'stok_produk' => 30,
            'gambar_produk' => $newFile,
        ]);

        $response->assertRedirect(route('produk.edit', $produk->produk_id));
        Storage::disk('public')->assertExists('produk/'.$newFile->hashName());

        Bus::assertDispatched(CompressImageJob::class, function (CompressImageJob $job) use ($newFile) {
            return str_ends_with($job->storagePath, $newFile->hashName());
        });
    }

    public function test_update_without_new_image_keeps_existing(): void
    {
        $user = User::factory()->create();
        $produk = Produk::factory()->create([
            'gambar_produk' => 'produk/old-product.jpg',
        ]);
        Storage::fake('public');
        Storage::disk('public')->put('produk/old-product.jpg', 'old-pixel-data');

        $response = $this->actingAs($user)->put('/produk/'.$produk->produk_id, [
            'nama_produk' => 'Same Name',
            'deskripsi_produk' => 'No change.',
            'harga_produk' => 75000,
            'stok_produk' => 20,
        ]);

        $response->assertRedirect(route('produk.edit', $produk->produk_id));
        Bus::assertNotDispatched(CompressImageJob::class);
    }
}
