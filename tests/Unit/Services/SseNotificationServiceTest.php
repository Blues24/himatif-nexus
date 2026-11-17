<?php

namespace Tests\Unit\Services;

use App\Services\SseNotificationService;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SseNotificationServiceTest extends TestCase
{
    private SseNotificationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(SseNotificationService::class);
    }

    public function test_push_adds_notification_to_user_queue(): void
    {
        $userId = 'user-123';
        $notification = [
            'type' => 'kegain',
            'title' => 'Kegiatan Baru',
            'message' => 'Ada kegiatan baru.',
            'data' => ['kegiatan_id' => 1],
        ];

        $result = $this->service->push($userId, $notification);

        $this->assertSame('kegain', $result['type']);
        $this->assertSame('Kegiatan Baru', $result['title']);
        $this->assertFalse($result['read']);
    }

    public function test_broadcast_sends_to_multiple_users(): void
    {
        config(['notifications.max_stored' => 50]); // reset default for safety
        $users = ['user-1', 'user-2', 'user-3'];
        $notification = [
            'type' => 'system',
            'title' => 'Maintenance',
            'message' => 'Server akan maintenance.',
        ];

        $this->service->broadcast($users, $notification);

        foreach ($users as $userId) {
            $notifications = $this->service->getAll($userId);
            $this->assertCount(1, $notifications);
            $this->assertSame('system', $notifications[0]['type']);
        }
    }

    public function test_get_unread_returns_only_unread(): void
    {
        config(['notifications.max_stored' => 50]);
        $userId = 'user-test';
        $this->service->push($userId, [
            'type' => 'formatif',
            'title' => 'Artikel Baru',
            'message' => 'Artikel sudah dibaca.',
            'read' => true,
        ]);
        $this->service->push($userId, [
            'type' => 'formatif',
            'title' => 'Artikel Belum Dibaca',
            'message' => 'Ini belum dibaca.',
        ]);

        $unread = $this->service->getUnread($userId);

        $this->assertCount(1, $unread);
        $this->assertSame('Artikel Belum Dibaca', $unread[0]['title']);
    }

    public function test_mark_read_updates_status(): void
    {
        config(['notifications.max_stored' => 50]);
        $userId = 'user-mark';
        $notif = $this->service->push($userId, [
            'type' => 'system',
            'title' => 'Notifikasi Penting',
            'message' => 'Penting!',
        ]);

        $this->assertTrue($this->service->markRead($notif['id'], $userId));

        $updated = $this->service->getAll($userId);
        $this->assertTrue($updated[0]['read']);
    }

    public function test_max_stored_limit_is_enforced(): void
    {
        config(['notifications.max_stored' => 3]);
        // Use a unique user ID to avoid stale data from other tests.
        $userId = 'user-limit-' . uniqid();
        // Clear any prior data via the same prefix the service uses.
        \Illuminate\Support\Facades\Cache::store()->forget('notifications_user_' . $userId);

        // Create a fresh service so it picks up the updated config value
        // (the setUp() instance captured the default max_stored of 50).
        $freshService = app(SseNotificationService::class);

        for ($i = 1; $i <= 5; $i++) {
            $freshService->push($userId, [
                'type' => 'system',
                'title' => "Notifikasi {$i}",
                'message' => "Pesan {$i}",
            ]);
        }

        $all = $freshService->getAll($userId);
        $this->assertCount(3, $all);
        // Array is built via array_unshift so newest entries sit at index 0.
        $this->assertSame('Notifikasi 5', $all[0]['title']);
        $this->assertSame('Notifikasi 3', $all[2]['title']);
    }
}