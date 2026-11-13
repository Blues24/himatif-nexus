<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CsrfProbeTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_probe_csrf(): void
    {
        dump('APP ENV: ' . $this->app->environment());
        dump('ENV BOUND: ' . ($this->app->bound('env') ? 'true' : 'false'));
        dump('RUNNING IN CONSOLE: ' . ($this->app->runningInConsole() ? 'true' : 'false'));
        dump('RUNNING UNIT TESTS: ' . ($this->app->runningUnitTests() ? 'true' : 'false'));
        $user = User::factory()->create();
        $resp = $this->actingAs($user)->post('/produk', [
            'nama_produk' => 'Probe',
            'harga_produk' => 1000,
            'stok_produk' => 1,
        ]);
        dump('RESP STATUS: ' . $resp->status());
        if ($resp->status() === 419) {
            dump($resp->content());
        }
    }
}
