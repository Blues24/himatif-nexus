<?php

namespace Tests\Feature\Infokom;

use App\Models\Formatif;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FormatifControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_renders_without_authentication(): void
    {
        $response = $this->get('/formatif');

        $response->assertOk();
        $response->assertInertia(function ($page) {
            $page->component('Blog/Index')
                ->has('formatifs')
                ->has('filters');
        });
    }

    public function test_index_with_search_returns_matching_formatifs(): void
    {
        $user = User::factory()->create();
        Formatif::factory()->create(['judul' => 'Laravel Advanced Routing', 'user_id' => $user->user_uuid]);
        Formatif::factory()->create(['judul' => 'Vue Composition API', 'user_id' => $user->user_uuid]);

        $response = $this->get('/formatif?q=Laravel');

        $response->assertOk();
        $response->assertInertia(function ($page) {
            $page->where('filters.q', 'Laravel')
                ->has('formatifs', 1);
        });
    }

    public function test_show_renders_formatif(): void
    {
        $user = User::factory()->create();
        $formatif = Formatif::factory()->create(['user_id' => $user->user_uuid]);

        $response = $this->get('/formatif/'.$formatif->formatif_id);

        $response->assertOk();
        $response->assertInertia(function ($page) use ($formatif) {
            $page->component('Blog/Show')
                ->has('formatif', function ($assert) use ($formatif) {
                    $assert->where('formatif_id', $formatif->formatif_id);
                });
        });
    }

    public function test_show_returns_404_for_nonexistent_formatif(): void
    {
        $this->get('/formatif/99999')->assertNotFound();
    }

    public function test_unauthenticated_user_cannot_create_formatif(): void
    {
        $this->post('/formatif', [
            'judul' => 'Test',
            'slug' => 'test',
            'kategori' => 'Artikel',
            'text_konten' => 'Content here',
            'status' => 'published',
        ])->assertRedirect();
    }

    public function test_store_creates_formatif_and_redirects(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/formatif', [
            'judul' => 'Belajar Laravel 13',
            'slug' => 'belajar-laravel-13',
            'kategori' => 'Tutorial',
            'text_konten' => 'Isi konten formatif belajar laravel 13.',
            'status' => 'published',
        ]);

        $response->assertRedirect(route('formatif.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('formatif', ['judul' => 'Belajar Laravel 13']);
    }

    public function test_store_rejects_missing_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/formatif', [
            'judul' => '',
            'slug' => '',
            'kategori' => '',
            'text_konten' => '',
        ]);

        $response->assertSessionHasErrors(['judul', 'slug', 'kategori', 'text_konten']);
    }

    public function test_store_uploads_thumbnail(): void
    {
        $user = User::factory()->create();
        Storage::fake('public');

        $file = UploadedFile::fake()->create('thumb.jpg', 150, 'image/jpeg');

        $response = $this->actingAs($user)->post('/formatif', [
            'judul' => 'Formatif With Thumbnail',
            'slug' => 'formatif-with-thumbnail',
            'kategori' => 'Artikel',
            'text_konten' => 'Content with image.',
            'status' => 'published',
            'thumbnail_path' => $file,
        ]);

        $response->assertRedirect(route('formatif.index'));
        Storage::disk('public')->assertExists('formatif/'.$file->hashName());
    }

    public function test_update_changes_formatif(): void
    {
        $user = User::factory()->create();
        $formatif = Formatif::factory()->create(['user_id' => $user->user_uuid, 'judul' => 'Old Title']);

        $response = $this->actingAs($user)->patch('/formatif/'.$formatif->formatif_id, [
            'judul' => 'Updated Title',
            'slug' => 'updated-title',
            'kategori' => 'Artikel',
            'text_konten' => 'Updated content.',
            'status' => 'published',
        ]);

        $response->assertRedirect(route('formatif.edit', $formatif->formatif_id));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('formatif', [
            'formatif_id' => $formatif->formatif_id,
            'judul' => 'Updated Title',
        ]);
    }

    public function test_destroy_removes_formatif(): void
    {
        $user = User::factory()->create();
        $formatif = Formatif::factory()->create(['user_id' => $user->user_uuid]);

        $response = $this->actingAs($user)->delete('/formatif/'.$formatif->formatif_id);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('formatif', ['formatif_id' => $formatif->formatif_id]);
    }
}