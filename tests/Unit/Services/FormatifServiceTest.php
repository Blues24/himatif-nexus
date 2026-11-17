<?php

namespace Tests\Unit\Services;

use App\Models\Formatif;
use App\Models\User;
use App\Services\FormatifService;
use App\Services\SseNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormatifServiceTest extends TestCase
{
    use RefreshDatabase;

    private FormatifService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(FormatifService::class);
    }

    public function test_create_auto_generates_slug_and_broadcasts_notification(): void
    {
        $user = User::factory()->bph()->create();

        $formatif = $this->service->create([
            'user_id' => $user->user_uuid,
            'judul' => 'Belajar Laravel 13',
            'kategori' => 'Tutorial',
            'text_konten' => 'Konten belajar laravel 13 secara mendalam.',
            'status' => 'publish',
        ]);

        $this->assertInstanceOf(Formatif::class, $formatif);
        $this->assertSame('belajar-laravel-13', $formatif->slug);
        $this->assertSame('Belajar Laravel 13', $formatif->judul);

        // Verify SSE notification sent to BPH/Infokom
        $sseService = app(SseNotificationService::class);
        $unread = $sseService->getUnread($user->user_uuid);
        $this->assertNotEmpty($unread);
        $this->assertSame('formatif', $unread[0]['type']);
    }

    public function test_published_returns_only_published_articles(): void
    {
        $user = User::factory()->create();
        Formatif::factory()->create(['user_id' => $user->user_uuid, 'status' => 'publish', 'judul' => 'Published Article']);
        Formatif::factory()->create(['user_id' => $user->user_uuid, 'status' => 'draft', 'judul' => 'Draft Article']);

        $result = $this->service->published();

        $this->assertCount(1, $result->items());
        $this->assertSame('Published Article', $result->items()[0]->judul);
    }

    public function test_search_finds_by_title_or_category(): void
    {
        $user = User::factory()->create();
        Formatif::factory()->create(['user_id' => $user->user_uuid, 'judul' => 'Advanced Eloquent', 'kategori' => 'Database']);
        Formatif::factory()->create(['user_id' => $user->user_uuid, 'judul' => 'Basic Routing', 'kategori' => 'Routing']);

        $result = $this->service->search('Advanced');

        $this->assertCount(1, $result->items());
        $this->assertSame('Advanced Eloquent', $result->items()[0]->judul);
    }
}
