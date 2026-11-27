<?php

namespace App\Console\Commands;

use App\Services\ImageOptimizer;
use Illuminate\Console\Command;
use Symfony\Component\Finder\Finder;

class OptimizeImagesCommand extends Command
{
    protected $signature = 'images:optimize {--path= : Relative path inside storage/public (default: all)}';

    protected $description = 'Compress all images in storage/app/public via sharp (Node.js).';

    public function handle(ImageOptimizer $optimizer): int
    {
        $basePath = $this->option('path')
            ? rtrim($this->option('path'), '/').'/'
            : '';

        $dir = storage_path('app/public/'.$basePath);
        if (! is_dir($dir)) {
            $this->error("Directory not found: {$dir}");

            return static::FAILURE;
        }

        $files = Finder::create()
            ->in($dir)
            ->files()
            ->name(['*.jpg', '*.jpeg', '*.png', '*.webp'])
            ->sortByName();

        $total = 0;
        $succeeded = 0;
        $skipped = 0;

        foreach ($files as $file) {
            $total++;
            $relativePath = str_replace($dir, '', $file->getPathname());

            $this->task($relativePath, function () use ($optimizer, $file, &$succeeded, &$skipped) {
                $optimized = $optimizer->compress($file->getRealPath());
                if ($optimized === null) {
                    $skipped++;

                    return false;
                }

                try {
                    $optimizer->swap($optimized, $file->getRealPath());
                    $succeeded++;

                    return true;
                } catch (\Throwable $e) {
                    @unlink($optimized);

                    return false;
                }
            });
        }

        $this->newLine();
        $this->info("Done. Processed {$total} image(s): {$succeeded} optimized, {$skipped} skipped.");

        return static::SUCCESS;
    }
}
