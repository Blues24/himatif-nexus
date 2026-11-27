<?php

namespace App\Observers;

use App\Jobs\CompressImageJob;
use App\Models\Formatif;
use Illuminate\Support\Facades\Log;

class FormatifObserver
{
    /**
     * Compress thumbnail after create or update when a new file is provided.
     */
    public function created(Formatif $formatif): void
    {
        $this->maybeCompress($formatif->thumbnail_path);
    }

    public function updated(Formatif $formatif): void
    {
        // Detect if thumbnail was replaced (not just metadata)
        if ($formatif->wasChanged('thumbnail_path')) {
            $this->maybeCompress($formatif->thumbnail_path);
        }
    }

    /**
     * Dispatch a queue job only when a non-null path is present.
     */
    protected function maybeCompress(?string $path): void
    {
        if (! $path) {
            return;
        }

        Log::info('[FormatifObserver] dispatching compression for '.$path);
        dispatch(new CompressImageJob($path));
    }

    public function deleted(Formatif $formatif): void {}
}
