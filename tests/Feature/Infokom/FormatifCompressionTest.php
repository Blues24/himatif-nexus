<?php

namespace Tests\Feature\Infokom;

use App\Jobs\CompressImageJob;
use App\Models\Formatif;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FormatifCompressionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Bus::fake([CompressImageJob::class]);
    }

    public function test_store_dispatches_compression_job_on_thumbnail_upload(): void
    {
        $user = User::factory()->create();
        Storage::fake('public');

        $file = UploadedFile::fake()->image('thumbnail.jpg', 800, 600);

        $this->actingAs($user)->post('/formatif', [
            'judul' => 'Article With Thumbnail',
            'slug' => 'article-with-thumbnail',
            'kategori' => 'Tutorial',
            'text_konten' => 'Some content here.',
            'status' => 'publish',
            'thumbnail_path' => $file,
        ]);

        Storage::disk('public')->assertExists('formatif/'.$file->hashName());

        Bus::assertDispatched(CompressImageJob::class, function (CompressImageJob $job) use ($file) {
            return str_ends_with($job->storagePath, $file->hashName());
        });
    }

    public function test_store_without_thumbnail_does_not_dispatch_job(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/formatif', [
            'judul' => 'Article No Image',
            'slug' => 'article-no-image',
            'kategori' => 'Artikel',
            'text_konten' => 'Content without image.',
            'status' => 'draft',
        ]);

        Bus::assertNotDispatched(CompressImageJob::class);
        $this->assertDatabaseHas('formatif', ['judul' => 'Article No Image']);
    }

    public function test_update_replaces_image_and_dispatches_new_job(): void
    {
        $user = User::factory()->create();
        $formatif = Formatif::factory()->create([
            'user_id' => $user->user_uuid,
            'thumbnail_path' => null,
        ]);
        Storage::fake('public');

        $newFile = UploadedFile::fake()->image('new-thumb.jpg', 1024, 768);

        $this->actingAs($user)->patch('/formatif/'.$formatif->formatif_id, [
            'judul' => 'Updated Title',
            'slug' => 'updated-title',
            'kategori' => 'Tutorial',
            'text_konten' => 'Updated content.',
            'status' => 'publish',
            'thumbnail_path' => $newFile,
        ]);

        Storage::disk('public')->assertExists('formatif/'.$newFile->hashName());

        Bus::assertDispatched(CompressImageJob::class, function (CompressImageJob $job) use ($newFile) {
            return str_ends_with($job->storagePath, $newFile->hashName());
        });
    }

    public function test_update_without_new_thumbnail_keeps_old_path_no_job(): void
    {
        $user = User::factory()->create();
        $formatif = Formatif::factory()->create([
            'user_id' => $user->user_uuid,
            'thumbnail_path' => 'formatif/existing-thumb.jpg',
        ]);
        Storage::fake('public');
        Storage::disk('public')->put('formatif/existing-thumb.jpg', 'old-content');

        $this->actingAs($user)->patch('/formatif/'.$formatif->formatif_id, [
            'judul' => 'Same Title',
            'slug' => 'same-title',
            'kategori' => 'Artikel',
            'text_konten' => 'No change to image.',
            'status' => 'publish',
        ]);

        Bus::assertNotDispatched(CompressImageJob::class);
        $this->assertEquals('formatif/existing-thumb.jpg', $formatif->fresh()->thumbnail_path);
    }

    public function test_destroy_without_care_for_thumbnail_path(): void
    {
        $user = User::factory()->create();
        $formatif = Formatif::factory()->create([
            'user_id' => $user->user_uuid,
            'thumbnail_path' => 'formatif/thumb-to-delete.jpg',
        ]);
        Storage::fake('public');

        $this->actingAs($user)->delete('/formatif/'.$formatif->formatif_id);

        $this->assertDatabaseMissing('formatif', ['formatif_id' => $formatif->formatif_id]);
    }
}
