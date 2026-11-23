<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import StatCard from '@/Components/Admin/StatCard.vue';

const today = new Date();
const hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
const bulanPendek = ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AGU', 'SEP', 'OKT', 'NOV', 'DES'];
const formattedDate = `${hari[today.getDay()]}, ${today.getDate()} ${bulan[today.getMonth()]} ${today.getFullYear()}`;

const penyewaanPending = [
    { id: 1, nama_penyewa: 'Dina Putri', nama_alat: 'Proyektor', tanggal_mulai: '2026-10-15', tanggal_selesai: '2026-10-16' },
    { id: 2, nama_penyewa: 'Raka Mahendra', nama_alat: 'Tripod + Kamera', tanggal_mulai: '2026-10-17', tanggal_selesai: '2026-10-17' },
    { id: 3, nama_penyewa: 'Salsa Nurul', nama_alat: 'Terminal kabel', tanggal_mulai: '2026-10-18', tanggal_selesai: '2026-10-19' },
    { id: 4, nama_penyewa: 'Fajar Ramadhan', nama_alat: 'Mikrofon nirkabel', tanggal_mulai: '2026-10-20', tanggal_selesai: '2026-10-20' },
];

const pesananTerbaru = [
    { id: 1, no_pesanan: 'ORD-0142', nama_produk: 'Kaos HIMATIF x2', total: 170000, status: 'Menunggu verifikasi' },
    { id: 2, no_pesanan: 'ORD-0141', nama_produk: 'Hoodie HIMATIF', total: 185000, status: 'Menunggu verifikasi' },
    { id: 3, no_pesanan: 'ORD-0140', nama_produk: 'Paket stiker A', total: 15000, status: 'Terverifikasi' },
    { id: 4, no_pesanan: 'ORD-0139', nama_produk: 'Ganci', total: 45000, status: 'Sudah diambil' },
    { id: 5, no_pesanan: 'ORD-0138', nama_produk: 'Tumbler HIMATIF', total: 45000, status: 'Sudah diambil' },
];

const kegiatanMendatang = [
    { id: 1, nama_kegiatan: 'Pelatihan Dasar Laravel', lokasi: 'Lab Komputer · 09.00 WIB', tanggal_mulai: '2026-10-18', status: 'Akan datang' },
    { id: 2, nama_kegiatan: 'Lomba Desain Web', lokasi: 'Pendaftaran dibuka', tanggal_mulai: '2026-10-25', status: 'Akan datang' },
    { id: 3, nama_kegiatan: 'Rapat Kerja Pengurus', lokasi: 'Internal BPH', tanggal_mulai: '2026-11-02', status: 'Draf' },
];

const aktivitasTerbaru = [
    { id: 1, inisial: 'IK', avatarBg: '#3B82F6', pelaku: 'Infokom', aksi: 'menerbitkan artikel "Dasar Git untuk Pemula"', waktu: 'Hari ini, 09.12' },
    { id: 2, inisial: 'DN', avatarBg: '#F59E0B', pelaku: 'Danus', aksi: 'menambah produk "Lanyard HIMATIF"', waktu: 'Kemarin, 16.40' },
    { id: 3, inisial: 'MS', avatarBg: '#0F765E', pelaku: 'Manset', aksi: 'mengonfirmasi pengembalian Proyektor', waktu: 'Kemarin, 11.05' },
];

function formatTanggalRange(mulai, selesai) {
    const d1 = new Date(mulai);
    const d2 = new Date(selesai);
    const h1 = d1.getDate();
    const h2 = d2.getDate();
    const b = bulanPendek[d1.getMonth()];
    if (h1 === h2) return `${h1} ${b}`;
    return `${h1} – ${h2} ${b}`;
}

function formatRupiah(n) {
    return 'Rp' + n.toLocaleString('id-ID');
}
</script>

<template>
    <AdminLayout
        title="Dashboard"
        :subtitle="`${formattedDate} · Ringkasan operasional HIMATIF`"
    >
        <template #actions>
            <button class="btn btn-outline-secondary btn-sm d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; border-radius: 8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9" />
                    <path d="M13.73 21a2 2 0 01-3.46 0" />
                </svg>
            </button>
            <Link
                href="/admin/kegiatan"
                class="btn btn-himatif d-flex align-items-center gap-2"
            >
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Tambah kegiatan
            </Link>
        </template>

        <!-- Stat Cards -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-lg-3">
                <StatCard label="Pesanan menunggu verifikasi" :value="7" subtitle="Merchandise · dikelola Danus" color="amber" />
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <StatCard label="Penyewaan menunggu konfirmasi" :value="4" subtitle="9 alat sedang disewa · dikelola Manset" color="blue" />
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <StatCard label="Kegiatan mendatang" :value="3" subtitle="Dalam 30 hari ke depan" color="teal" />
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <StatCard label="Artikel Formatif" :value="18" subtitle="Terbit · 2 masih draf" color="teal" />
            </div>
        </div>

        <!-- Tables Row -->
        <div class="row g-4 mb-4">
            <!-- Penyewaan Menunggu Konfirmasi -->
            <div class="col-12 col-lg-6">
                <div class="card-panel">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-semibold mb-0">Penyewaan menunggu konfirmasi</h6>
                        <Link href="/admin/inventaris" style="font-size: 13px; color: var(--primary-green); text-decoration: none; font-weight: 500;">
                            Lihat semua
                        </Link>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0" style="font-size: 13.5px;">
                            <thead>
                                <tr style="color: #6B7280; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">
                                    <th class="fw-medium border-bottom py-2">Penyewa</th>
                                    <th class="fw-medium border-bottom py-2">Alat</th>
                                    <th class="fw-medium border-bottom py-2">Tanggal pakai</th>
                                    <th class="fw-medium border-bottom py-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in penyewaanPending" :key="item.id">
                                    <td class="fw-medium py-2">{{ item.nama_penyewa }}</td>
                                    <td class="py-2" style="color: #6B7280;">{{ item.nama_alat }}</td>
                                    <td class="py-2" style="color: #6B7280;">{{ formatTanggalRange(item.tanggal_mulai, item.tanggal_selesai) }}</td>
                                    <td class="py-2">
                                        <button class="btn btn-himatif" style="font-size: 12px; padding: 4px 12px;">
                                            Konfirmasi
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="penyewaanPending.length === 0">
                                    <td colspan="4" class="text-center py-4" style="color: #9CA3AF;">
                                        Tidak ada penyewaan menunggu konfirmasi
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pesanan Merchandise Terbaru -->
            <div class="col-12 col-lg-6">
                <div class="card-panel">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-semibold mb-0">Pesanan merchandise terbaru</h6>
                        <Link href="/admin/shop" style="font-size: 13px; color: var(--primary-green); text-decoration: none; font-weight: 500;">
                            Lihat semua
                        </Link>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0" style="font-size: 13.5px;">
                            <thead>
                                <tr style="color: #6B7280; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">
                                    <th class="fw-medium border-bottom py-2">No. Pesanan</th>
                                    <th class="fw-medium border-bottom py-2">Produk</th>
                                    <th class="fw-medium border-bottom py-2">Total</th>
                                    <th class="fw-medium border-bottom py-2">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in pesananTerbaru" :key="item.id">
                                    <td class="fw-medium py-2">{{ item.no_pesanan }}</td>
                                    <td class="py-2" style="color: #6B7280;">{{ item.nama_produk }}</td>
                                    <td class="fw-medium py-2">{{ formatRupiah(item.total) }}</td>
                                    <td class="py-2">
                                        <span
                                            class="badge rounded-pill"
                                            :class="{
                                                'bg-warning bg-opacity-10 text-warning': item.status === 'Menunggu verifikasi',
                                                'bg-success bg-opacity-10 text-success': item.status === 'Terverifikasi',
                                                'bg-secondary bg-opacity-10 text-secondary': item.status === 'Sudah diambil',
                                                'bg-danger bg-opacity-10 text-danger': item.status === 'Dibatalkan',
                                            }"
                                            style="font-size: 11.5px; font-weight: 500; padding: 4px 10px;"
                                        >
                                            {{ item.status }}
                                        </span>
                                    </td>
                                </tr>
                                <tr v-if="pesananTerbaru.length === 0">
                                    <td colspan="4" class="text-center py-4" style="color: #9CA3AF;">
                                        Belum ada pesanan
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Row -->
        <div class="row g-4">
            <!-- Kegiatan Mendatang -->
            <div class="col-12 col-lg-6">
                <div class="card-panel">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-semibold mb-0">Kegiatan mendatang</h6>
                        <Link href="/admin/kegiatan" style="font-size: 13px; color: var(--primary-green); text-decoration: none; font-weight: 500;">
                            Kelola
                        </Link>
                    </div>
                    <div>
                        <div
                            v-for="(item, idx) in kegiatanMendatang"
                            :key="item.id"
                            class="d-flex align-items-center gap-3 py-3"
                            :class="{ 'border-top': idx > 0 }"
                            style="border-color: #F3F4F6 !important;"
                        >
                            <!-- Date Badge -->
                            <div
                                class="d-flex flex-column align-items-center justify-content-center flex-shrink-0"
                                style="width: 48px; height: 48px; border-radius: 50%; border: 2px solid var(--primary-green);"
                            >
                                <span style="font-size: 16px; font-weight: 700; line-height: 1; color: #111827;">
                                    {{ new Date(item.tanggal_mulai).getDate() }}
                                </span>
                                <span style="font-size: 10px; font-weight: 600; text-transform: uppercase; line-height: 1; color: var(--primary-green); margin-top: 2px;">
                                    {{ bulanPendek[new Date(item.tanggal_mulai).getMonth()] }}
                                </span>
                            </div>
                            <!-- Event Info -->
                            <div class="flex-grow-1" style="min-width: 0;">
                                <div class="fw-medium" style="color: #111827;">{{ item.nama_kegiatan }}</div>
                                <div style="font-size: 13px; color: #6B7280;">{{ item.lokasi }}</div>
                            </div>
                            <!-- Status -->
                            <span
                                style="font-size: 13px; font-weight: 500; white-space: nowrap;"
                                :style="{ color: item.status === 'Akan datang' ? 'var(--primary-green)' : '#9CA3AF' }"
                            >
                                {{ item.status }}
                            </span>
                        </div>
                        <div v-if="kegiatanMendatang.length === 0" class="text-center py-4" style="color: #9CA3AF; font-size: 13.5px;">
                            Tidak ada kegiatan mendatang
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aktivitas Terbaru -->
            <div class="col-12 col-lg-6">
                <div class="card-panel">
                    <h6 class="fw-semibold mb-3">Aktivitas terbaru</h6>
                    <div>
                        <div
                            v-for="(item, idx) in aktivitasTerbaru"
                            :key="item.id"
                            class="d-flex align-items-start gap-3 py-3"
                            :class="{ 'border-top': idx > 0 }"
                            style="border-color: #F3F4F6 !important;"
                        >
                            <!-- Avatar -->
                            <div
                                class="d-flex align-items-center justify-content-center flex-shrink-0 text-white fw-semibold"
                                :style="{
                                    width: '36px',
                                    height: '36px',
                                    borderRadius: '50%',
                                    fontSize: '12px',
                                    backgroundColor: item.avatarBg || '#0F765E',
                                }"
                            >
                                {{ item.inisial }}
                            </div>
                            <!-- Content -->
                            <div class="flex-grow-1">
                                <div style="font-size: 13.5px; color: #111827;">
                                    <span class="fw-medium">{{ item.pelaku }}</span>
                                    {{ item.aksi }}
                                </div>
                                <div style="font-size: 12px; color: #9CA3AF; margin-top: 2px;">{{ item.waktu }}</div>
                            </div>
                        </div>
                        <div v-if="aktivitasTerbaru.length === 0" class="text-center py-4" style="color: #9CA3AF; font-size: 13.5px;">
                            Belum ada aktivitas
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
