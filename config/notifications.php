<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Notifications Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for SSE and in-memory notification queue.
    |
    */

    'cache_prefix' => 'notifications_user_',

    'max_stored' => 50,

    'ttl' => 86400 * 7, // 7 days in seconds
];
