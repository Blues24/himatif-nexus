<?php

namespace App\Http\Controllers;

use App\Services\SseNotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationStreamController extends Controller
{
    public function __construct(protected SseNotificationService $sse) {}

    /**
     * SSE stream endpoint.
     * Emits unread notifications as SSE frames every 3 seconds.
     * Handles client disconnect gracefully.
     */
    public function stream(Request $request): void
    {
        $user = $request->user();
        if (! $user) {
            abort(401);
        }

        // Disable Laravel output buffering
        while (ob_get_level()) {
            ob_end_clean();
        }

        // Set headers for SSE
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no');

        ignore_user_abort(true);

        // Send an initial heartbeat to ensure connection is established
        echo "event: ping\ndata: {\"ok\": true}\n\n";
        flush();

        $lastCount = $this->sse->getUnread($user->getAuthIdentifier());

        while (! connection_aborted() && ignore_user_abort(true)) {
            $unread = $this->sse->getUnread($user->getAuthIdentifier());

            if (count($unread) !== $lastCount) {
                $payload = json_encode([
                    'type' => 'notifications',
                    'unread' => $unread,
                    'count' => count($unread),
                ]) ?: '{}';

                echo "data: {$payload}\n\n";
                flush();
                $lastCount = count($unread);
            }

            sleep(3);
        }
    }

    /**
     * JSON list of recent notifications for the authenticated user (limited to 20).
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            abort(401);
        }

        $all = $this->sse->getAll($user->getAuthIdentifier());
        $limited = array_slice($all, 0, 20);

        return response()->json([
            'notifications' => array_values($limited),
            'unread_count' => count(array_filter($all, fn ($n) => ! ($n['read'] ?? false))),
        ]);
    }
}