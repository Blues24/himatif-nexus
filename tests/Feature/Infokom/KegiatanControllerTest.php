<?php

namespace Tests\Feature\Infokom;

use App\Models\Kegiatan;
use App\Models\User;
use Tests\TestCase;

class KegiatanControllerTest extends TestCase
{
    public function test_index_renders_without_authentication(): void
    {
        $response = $this->get('/kegiatan');

        $response->assertOk();
        $response->assertInertia(function ($page) {
            $page->component('Infokom/Kegiatan/Index')
                ->has('kegiatans')
                ->has('upcoming')
                ->has('filters');
        });
    }

    public function test_show_renders_kegiatan(): void
    {
        $kegiatan = Kegiatan::factory()->create();

        $response = $this->get('/kegiatan/' . $kegiatan->kegiatan_id);

        $response->assertOk();
        $response->assertInertia(function ($page) use ($kegiatan) {
            $page->component('Infokom/Kegiatan/Show')
                ->has('kegiatan', function ($assert) use ($kegiatan) {
                    $assert
                        ->where('kegiatan_id', $kegiatan->kegiatan_id)
                        ->where('nama_kegiatan', $kegiatan->nama_kegiatan);
                });
        });
    }

    public function test_store_creates_kegiatan_when_authenticated(): void
    {
        $user = User::factory()->create();
        $data = [
            'nama_kegiatan' => 'Lomba Coding HMIF',
            'deskripsi_kegiatan' => 'Lomba tahunan tingkat prodi.',
            'tanggal_pelaksanaan' => now()->addDays(7)->toDateString(),
            'status_kegiatan' => 'rencana',
        ];

        $response = $this->actingAs($user)->post('/kegiatan', $data);

        $response->assertRedirect(route('kegiatan.index'));
        $this->assertDatabaseHas('kegiatan', ['nama_kegiatan' => 'Lomba Coding HMIF']);
    }

    public function test_update_modifies_kegiatan(): void
    {
        $user = User::factory()->create();
        $kegiatan = Kegiatan::factory()->create(['nama_kegiatan' => 'Old Name']);
        $data = [
            'nama_kegiatan' => 'New Name',
            'deskripsi_kegiatan' => 'New Desc',
            'tanggal_pelaksanaan' => now()->toDateString(),
            'status_kegiatan' => 'berjalan',
        ];

        $response = $this->actingAs($user)->patch('/kegiatan/' . $kegiatan->kegiatan_id, $data);

        $response->assertRedirect(route('kegiatan.index'));
        $this->assertDatabaseHas('kegiatan', [
            'kegiatan_id' => $kegiatan->kegiatan_id,
            'nama_kegiatan' => 'New Name'
        ]);
    }

    public function test_destroy_deletes_kegiatan(): void
    {
        $user = User::factory()->create();
        $kegiatan = Kegiatan::factory()->create();

        $response = $this->actingAs($user)->delete('/kegiatan/' . $kegiatan->kegiatan_id);

        $response->assertRedirect(route('kegiatan.index'));
        $this->assertDatabaseMissing('kegiatan', ['kegiatan_id' => $kegiatan->kegiatan_id]);
    }
}
