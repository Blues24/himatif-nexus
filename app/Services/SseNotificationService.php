<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SseNotificationService
{
    protected string $prefix;
    protected int $maxStored;
    protected int $ttl;

    public function __construct()
    {
        $this->prefix = config('notifications.cache_prefix', 'notifications_user_');
        $this->maxStored = config('notifications.max_stored', 50);
        $this->ttl = config('notifications.ttl', 604800);
    }

    /**
     * Cache key for user notifications.
     */
    protected function getKey(int|string $userId): string
    {
        return $this->prefix . $userId;
    }

    /**
     * Tagged cache repo if supported (e.g. redis/memcached), fallback to normal cache.
     */
    protected function cacheStore(int|string $userId)
    {
        $store = Cache::store();
        if ($store->supportsTags()) {
            return $store->tags(['notifications', 'user_' . $userId]);
        }

        return $store;
    }

    /**
     * Push a notification to a specific user's queue.
     * Each notification: ['id', 'type' (kegain|formatif|inventaris|penyewaan|system), 'title', 'message', 'data' => [...], 'read' => false, 'created_at']
     */
    public function push(int|string $userId, array $notification): array
    {
        $formatted = [
            'id' => $notification['id'] ?? (string) Str::uuid(),
            'type' => $notification['type'] ?? 'system',
            'title' => $notification['title'] ?? '',
            'message' => $notification['message'] ?? '',
            'data' => $notification['data'] ?? [],
            'read' => $notification['read'] ?? false,
            'created_at' => $notification['created_at'] ?? now()->toIso8601String(),
        ];

        $key = $this->getKey($userId);
        $cache = $this->cacheStore($userId);

        $list = $cache->get($key, []);
        array_unshift($list, $formatted);

        if (count($list) > $this->maxStored) {
            $list = array_slice($list, 0, $this->maxStored);
        }

        $cache->put($key, $list, $this->ttl);

        return $formatted;
    }

    /**
     * Broadcast notification to multiple users.
     *
     * @param array<int|string>|int|string $userIds
     */
    public function broadcast(array|int|string $userIds, array $notification): void
    {
        $users = is_array($userIds) ? $userIds : [$userIds];

        foreach ($users as $uId) {
            $this->push($uId, $notification);
        }
    }

    /**
     * Get unread notifications for a user.
     *
     * @return array<int, array>
     */
    public function getUnread(int|string $userId): array
    {
        $all = $this->getAll($userId);

        return array_values(array_filter($all, fn ($item) => ! ($item['read'] ?? false)));
    }

    /**
     * Get all recent notifications for a user.
     *
     * @return array<int, array>
     */
    public function getAll(int|string $userId): array
    {
        $key = $this->getKey($userId);

        return $this->cacheStore($userId)->get($key, []);
    }

    /**
     * Mark a notification as read for a given user (or search across current auth user if userId not given).
     */
    public function markRead(int|string $notificationId, int|string|null $userId = null): bool
    {
        $userId = $userId ?? auth()->id();
        if (! $userId) {
            return false;
        }

        $key = $this->getKey($userId);
        $cache = $this->cacheStore($userId);
        $list = $cache->get($key, []);

        $updated = false;
        foreach ($list as &$item) {
            if (isset($item['id']) && (string) $item['id'] === (string) $notificationId) {
                $item['read'] = true;
                $updated = true;
                break;
            }
        }

        if ($updated) {
            $cache->put($key, $list, $this->ttl);
        }

        return $updated;
    }
}
