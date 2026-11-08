<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'pesananMenungguVerifikasi' => 0,
                'penyewaanMenungguKonfirmasi' => 0,
                'kegiatanMendatang' => 0,
                'artikelFormatif' => 0,
            ],
            'subtitleStats' => [
                'pesanan' => 'Merchandise · dikelola Danus',
                'penyewaan' => '0 alat sedang disewa · dikelola Manset',
                'kegiatan' => 'Dalam 30 hari ke depan',
                'artikel' => 'Terbit · 0 masih draf',
            ],
            'penyewaanPending' => [
                [
                    'id' => 1,
                    'nama_penyewa' => 'Dina Putri',
                    'nama_alat' => 'Proyektor',
                    'tanggal_mulai' => '2026-10-15',
                    'tanggal_selesai' => '2026-10-16',
                ],
                [
                    'id' => 2,
                    'nama_penyewa' => 'Raka Mahendra',
                    'nama_alat' => 'Tripod + Kamera',
                    'tanggal_mulai' => '2026-10-17',
                    'tanggal_selesai' => '2026-10-17',
                ],
                [
                    'id' => 3,
                    'nama_penyewa' => 'Salsa Nurul',
                    'nama_alat' => 'Terminal kabel',
                    'tanggal_mulai' => '2026-10-18',
                    'tanggal_selesai' => '2026-10-19',
                ],
                [
                    'id' => 4,
                    'nama_penyewa' => 'Fajar Ramadhan',
                    'nama_alat' => 'Mikrofon nirkabel',
                    'tanggal_mulai' => '2026-10-20',
                    'tanggal_selesai' => '2026-10-20',
                ],
            ],
            'pesananTerbaru' => [
                [
                    'id' => 1,
                    'no_pesanan' => 'ORD-0142',
                    'nama_produk' => 'Kaos HIMATIF x2',
                    'total' => 170000,
                    'status' => 'Menunggu verifikasi',
                ],
                [
                    'id' => 2,
                    'no_pesanan' => 'ORD-0141',
                    'nama_produk' => 'Hoodie HIMATIF',
                    'total' => 185000,
                    'status' => 'Menunggu verifikasi',
                ],
                [
                    'id' => 3,
                    'no_pesanan' => 'ORD-0140',
                    'nama_produk' => 'Paket stiker A',
                    'total' => 15000,
                    'status' => 'Terverifikasi',
                ],
                [
                    'id' => 4,
                    'no_pesanan' => 'ORD-0139',
                    'nama_produk' => 'Ganci',
                    'total' => 45000,
                    'status' => 'Sudah diambil',
                ],
                [
                    'id' => 5,
                    'no_pesanan' => 'ORD-0138',
                    'nama_produk' => 'Tumbler HIMATIF',
                    'total' => 45000,
                    'status' => 'Sudah diambil',
                ],
            ],
            'kegiatanMendatang' => [
                [
                    'id' => 1,
                    'nama_kegiatan' => 'Pelatihan Dasar Laravel',
                    'lokasi' => 'Lab Komputer · 09.00 WIB',
                    'tanggal_mulai' => '2026-10-18',
                    'status' => 'Akan datang',
                ],
                [
                    'id' => 2,
                    'nama_kegiatan' => 'Lomba Desain Web',
                    'lokasi' => 'Pendaftaran dibuka',
                    'tanggal_mulai' => '2026-10-25',
                    'status' => 'Akan datang',
                ],
                [
                    'id' => 3,
                    'nama_kegiatan' => 'Rapat Kerja Pengurus',
                    'lokasi' => 'Internal BPH',
                    'tanggal_mulai' => '2026-11-02',
                    'status' => 'Draf',
                ],
            ],
            'aktivitasTerbaru' => [
                [
                    'id' => 1,
                    'inisial' => 'IK',
                    'pelaku' => 'Infokom',
                    'aksi' => 'menerbitkan artikel "Dasar Git untuk Pemula"',
                    'waktu' => 'Hari ini, 09.12',
                    'avatarColor' => 'bg-blue-400',
                ],
                [
                    'id' => 2,
                    'inisial' => 'DN',
                    'pelaku' => 'Danus',
                    'aksi' => 'menambah produk "Lanyard HIMATIF"',
                    'waktu' => 'Kemarin, 16.40',
                    'avatarColor' => 'bg-amber-400',
                ],
                [
                    'id' => 3,
                    'inisial' => 'MS',
                    'pelaku' => 'Manset',
                    'aksi' => 'mengonfirmasi pengembalian Proyektor',
                    'waktu' => 'Kemarin, 11.05',
                    'avatarColor' => 'bg-emerald-400',
                ],
            ],
        ]);
    }
}
