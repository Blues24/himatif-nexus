<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Image Optimizer Configuration
    |--------------------------------------------------------------------------
    */

    /**
     * Maximum width/height in pixels. Images larger than this are scaled down.
     */
    'max_size' => env('IMAGE_OPTIMIZER_MAX_SIZE', 1920),

    /**
     * Compression quality (0–100). Applies to both JPEG and PNG outputs.
     */
    'quality' => env('IMAGE_OPTIMIZER_QUALITY', 85),

    /**
     * When true, convert eligible images (non-PNG) to WebP for ~30% smaller files.
     * PNG sources stay as PNG to preserve transparency.
     */
    'prefer_webp' => env('IMAGE_OPTIMIZER_PREFER_WEBP', true),

    /**
     * Minimum output size in KB below which WebP conversion is skipped.
     * Small images don't benefit from format switching.
     */
    'webp_threshold_kb' => env('IMAGE_OPTIMIZER_WEBP_THRESHOLD_KB', 200),

    /**
     * Temporary directory for processing before the final swap.
     * Must be writable by the PHP process and cleared periodically.
     */
    'temp_dir' => storage_path('app/temp/images'),

];
