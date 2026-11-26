<?php

namespace App\Jobs;

use App\Services\ImageOptimizer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CompressImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var string Relative storage path (e.g. "formatif/abc.jpg") */
    public readonly string $storagePath;

    public int $tries = 2;

    public int $timeout = 60;

    public function __construct(string $storagePath)
    {
        $this->storagePath = $storagePath;
    }

    /**
     * Run the image compression pipeline and replace the stored file with the optimized version.
     */
    public function handle(ImageOptimizer $optimizer): void
    {
        $fullInput = storage_path('app/public/'.$this->storagePath);

        if (! is_file($fullInput)) {
            Log::warning('[CompressImage] input file missing, skip.', [
                'storage_path' => $this->storagePath,
            ]);

            return;
        }

        $optimized = $optimizer->compress($fullInput);
        if ($optimized === null) {
            Log::error('[CompressImage] compression returned no result.', ['storage_path' => $this->storagePath]);

            return;
        }

        [$size] = $optimizer->swap($optimized, $fullInput);

        Log::info('[CompressImage] done.', [
            'storage_path' => $this->storagePath,
            'final_size_bytes' => $size,
        ]);
    }
}
