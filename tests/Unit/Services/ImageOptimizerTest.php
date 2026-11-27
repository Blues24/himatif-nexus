<?php

namespace Tests\Unit\Services;

use App\Services\ImageOptimizer;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

/**
 * @covers \App\Services\ImageOptimizer
 */
class ImageOptimizerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Config::set('image-optimizer.temp_dir', storage_path('app/temp/images'));
        Config::set('image-optimizer.quality', 85);
    }

    public function test_compress_returns_null_when_script_missing(): void
    {
        // Force script path to a non-existent file to avoid needing sharp installed
        $optimizer = new ImageOptimizer;
        $result = $optimizer->compress('/nonexistent/path/image.jpg');

        $this->assertNull($result);
    }

    public function test_compress_returns_null_when_input_file_missing(): void
    {
        $optimizer = new ImageOptimizer;
        $result = $optimizer->compress(storage_path('app/nonexistent.jpg'));

        $this->assertNull($result);
    }

    public function test_swap_throws_when_optimized_file_missing(): void
    {
        $optimizer = new ImageOptimizer;
        $tmpFile = tempnam(sys_get_temp_dir(), 'img_test_');
        unlink($tmpFile);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Compressed file missing');

        $optimizer->swap($tmpFile.'missing', '/some/destination.jpg');
    }

    public function test_swap_swaps_existing_files_correctly(): void
    {
        $optimizer = new ImageOptimizer;

        $optimized = tempnam(sys_get_temp_dir(), 'opt_');
        file_put_contents($optimized, 'compressed-content');

        $destination = storage_path('app/test-opt-'.uniqid().'.jpg');
        file_put_contents($destination, 'original-content');

        $info = $optimizer->swap($optimized, $destination);

        $this->assertEquals('compressed-content', file_get_contents($destination));
        $this->assertGreaterThan(0, $info['size']);
    }

    public function test_swap_marks_original_deleted_after_swap(): void
    {
        $optimizer = new ImageOptimizer;

        $optimized = tempnam(sys_get_temp_dir(), 'opt_');
        file_put_contents($optimized, 'swapped-content');

        $destination = storage_path('app/test-opt-'.uniqid().'.jpg');
        file_put_contents($destination, 'old-content');

        $optimizer->swap($optimized, $destination);

        $this->assertFalse(file_exists($optimized), 'Optimized temp should be removed after swap');
        $this->assertEquals('swapped-content', file_get_contents($destination));
    }
}
