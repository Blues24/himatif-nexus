<?php

namespace App\Services;

use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;

class ImageOptimizer
{
    /**
     * Compress an image file in place via the Node.js sharp script.
     * Returns the optimized path on success, null on failure (original untouched).
     */
    public function compress(string $inputPath): ?string
    {
        if (! is_file($inputPath) || ! is_readable($inputPath)) {
            return null;
        }

        $script = base_path('scripts/compress-image.cjs');
        if (! is_file($script)) {
            report(new \RuntimeException('compress-image.cjs not found. Run `npm install sharp`.'));

            return null;
        }

        // ponytail: environment variables are read by scripts/compress-image.cjs via process.env
        $env = [
            'IMAGE_OPTIMIZER_MAX_SIZE' => config('image-optimizer.max_size', 1920),
            'IMAGE_OPTIMIZER_QUALITY' => (string) config('image-optimizer.quality', 85),
            'IMAGE_OPTIMIZER_PREFER_WEBP' => config('image-optimizer.prefer_webp', true) ? 'true' : 'false',
            'IMAGE_OPTIMIZER_WEBP_THRESHOLD' => (string) config('image-optimizer.webp_threshold_kb', 200),
            'IMAGE_OPTIMIZER_OUT_DIR' => config('image-optimizer.temp_dir', storage_path('app/temp/images')),
        ];

        $result = Process::timeout(30)
            ->env($env)
            ->run(['node', $script, $inputPath]);

        if ($result->failed()) {
            report(new \RuntimeException('Image compression failed: '.$result->errorOutput()));

            return null;
        }

        try {
            $payload = json_decode($result->output(), true);
        } catch (\Throwable $e) {
            report($e);

            return null;
        }

        if (! $payload || empty($payload['success']) || empty($payload['outputPath'])) {
            return null;
        }

        return $payload['outputPath'];
    }

    /**
     * Swap a compressed output into the final storage location, overwriting the original.
     * The original file is deleted first; if swap fails the job marks itself failed.
     *
     * @return array{oldSize:int, newSize:int} size info for logging
     *
     * @throws \RuntimeException when swap cannot complete
     */
    public function swap(string $optimizedPath, string $destinationPath): array
    {
        if (! is_file($optimizedPath)) {
            throw new \RuntimeException("Compressed file missing: {$optimizedPath}");
        }

        $tmpSwap = sys_get_temp_dir().DIRECTORY_SEPARATOR.'img-opt-'.Str::random(16).pathinfo($destinationPath, PATHINFO_EXTENSION);

        // Move optimized to target location atomically where possible.
        // If same disk we copy+delete (cross-device fallback).
        if (@rename($optimizedPath, $destinationPath)) {
            return $this->sizeInfo($destinationPath);
        }

        copy($optimizedPath, $destinationPath);
        unlink($optimizedPath);

        return $this->sizeInfo($destinationPath);
    }

    protected function sizeInfo(string $path): array
    {
        $stat = stat($path);

        return [
            'path' => $path,
            'size' => $stat !== false ? $stat['size'] : 0,
        ];
    }
}
