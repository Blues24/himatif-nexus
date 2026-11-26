<?php

namespace Tests\Unit\Jobs;

use App\Jobs\CompressImageJob;
use App\Services\ImageOptimizer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * @covers \App\Jobs\CompressImageJob
 */
class CompressImageJobTest extends TestCase
{
    public function test_job_implements_should_queue(): void
    {
        $job = new CompressImageJob('test/path/image.jpg');

        $this->assertTrue($job instanceof ShouldQueue);
        $this->assertEquals('test/path/image.jpg', $job->storagePath);
    }

    public function test_job_skips_when_storage_file_missing(): void
    {
        Bus::fake([CompressImageJob::class]);
        Queue::fake();

        $job = new CompressImageJob('nonexistent/path/missing.jpg');
        $job->handle(app(ImageOptimizer::class));

        // Should not throw, just log warning — dispatch itself is separate from handle
        $this->expectNotToPerformAssertions();
    }
}
