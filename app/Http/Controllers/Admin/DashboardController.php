<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FormatifService;
use App\Services\KegiatanService;
use App\Services\PenyewaanAlatService;
use App\Services\TransaksiService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(
        TransaksiService $transaksiService,
        PenyewaanAlatService $penyewaanService,
        KegiatanService $kegiatanService,
        FormatifService $formatifService
    ): Response {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'pesananMenungguVerifikasi' => $transaksiService->byStatus('menunggu')->total(),
                'penyewaanMenungguKonfirmasi' => $penyewaanService->pending()->total(),
                'kegiatanMendatang' => $kegiatanService->upcoming()->count(),
                'artikelFormatif' => $formatifService->published()->total(),
            ],
            'subtitleStats' => [
                'pesanan' => 'Merchandise · dikelola Danus',
                'penyewaan' => '0 alat sedang disewa · dikelola Manset',
                'kegiatan' => 'Dalam 30 hari ke depan',
                'artikel' => 'Terbit · 0 masih draf',
            ],
            'penyewaanPending' => fn () => $penyewaanService->pendingWithRelations(),
            'pesananTerbaru' => fn () => $transaksiService->recent(5),
            'kegiatanMendatang' => fn () => $kegiatanService->upcoming()->map(fn ($k) => [
                'id' => $k->kegiatan_id,
                'nama_kegiatan' => $k->nama_kegiatan,
                'lokasi' => '',
                'tanggal_mulai' => $k->tanggal_pelaksanaan?->format('Y-m-d'),
                'status' => match ($k->status_kegiatan) {
                    'rencana' => 'Rencana',
                    'berjalan' => 'Berjalan',
                    default => 'Selesai',
                },
            ]),
        ]);
    }
}
