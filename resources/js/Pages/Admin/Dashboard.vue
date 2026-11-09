<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import StatCard from '@/Components/Admin/StatCard.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    stats: {
        type: Object,
        default: () => ({
            pesananMenungguVerifikasi: 0,
            penyewaanMenungguKonfirmasi: 0,
            kegiatanMendatang: 0,
            artikelFormatif: 0,
        }),
    },
    penyewaanPending: {
        type: Array,
        default: () => [],
    },
    pesananTerbaru: {
        type: Array,
        default: () => [],
    },
    kegiatanMendatang: {
        type: Array,
        default: () => [],
    },
    aktivitasTerbaru: {
        type: Array,
        default: () => [],
    },
    subtitleStats: {
        type: Object,
        default: () => ({
            pesanan: '',
            penyewaan: '',
            kegiatan: '',
            artikel: '',
        }),
    },
});

const today = new Date();
const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
const formattedDate = today.toLocaleDateString('id-ID', options);

function statusClass(status) {
    const classes = {
        'Menunggu verifikasi': 'bg-amber-100 text-amber-700',
        'Terverifikasi': 'bg-teal-100 text-teal-700',
        'Sudah diambil': 'bg-gray-100 text-gray-600',
        'Dibatalkan': 'bg-red-100 text-red-700',
    };
    return classes[status] ?? 'bg-gray-100 text-gray-600';
}

function kegiatanStatusClass(status) {
    const classes = {
        'Akan datang': 'text-teal-600',
        'Draf': 'text-gray-400',
        'Selesai': 'text-gray-500',
    };
    return classes[status] ?? 'text-gray-400';
}

const bulanPendek = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];

function formatTanggal(dateStr) {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return `${date.getDate()} ${bulanPendek[date.getMonth()]}`;
}

function formatTanggalRange(start, end) {
    if (!start) return '';
    const s = formatTanggal(start);
    if (!end || start === end) return s;
    return `${s} – ${formatTanggal(end)}`;
}

function formatRupiah(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(amount);
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
    <AdminLayout title="Dashboard">
        <template #header>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                <p class="mt-0.5 text-sm text-gray-500">
                    {{ formattedDate }} · Ringkasan operasional HIMATIF
                </p>
            </div>
        </template>

        <template #actions>
            <button class="rounded-lg border border-gray-200 p-2 text-gray-500 transition-colors hover:bg-gray-50">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9" />
                    <path d="M13.73 21a2 2 0 01-3.46 0" />
                </svg>
            </button>
            <Link
                :href="route('admin.kegiatan.index')"
                class="inline-flex items-center gap-2 rounded-lg bg-teal-500 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-teal-600"
            >
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">

                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Tambah kegiatan
            </Link>
        </template>

        <!-- Stat Cards -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-lg-3">
                <StatCard
                    label="Pesanan menunggu verifikasi"
                    :value="stats.pesananMenungguVerifikasi"
                    :subtitle="subtitleStats.pesanan"
                    color="amber"
                />
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <StatCard
                    label="Penyewaan menunggu konfirmasi"
                    :value="stats.penyewaanMenungguKonfirmasi"
                    :subtitle="subtitleStats.penyewaan"
                    color="blue"
                />
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <StatCard
                    label="Kegiatan mendatang"
                    :value="stats.kegiatanMendatang"
                    :subtitle="subtitleStats.kegiatan"
                    color="teal"
                />
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <StatCard
                    label="Artikel Formatif"
                    :value="stats.artikelFormatif"
                    :subtitle="subtitleStats.artikel"
                    color="teal"
                />
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
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <StatCard
                label="Pesanan menunggu verifikasi"
                :value="stats.pesananMenungguVerifikasi"
                :subtitle="subtitleStats.pesanan"
                color="amber"
            />
            <StatCard
                label="Penyewaan menunggu konfirmasi"
                :value="stats.penyewaanMenungguKonfirmasi"
                :subtitle="subtitleStats.penyewaan"
                color="blue"
            />
            <StatCard
                label="Kegiatan mendatang"
                :value="stats.kegiatanMendatang"
                :subtitle="subtitleStats.kegiatan"
                color="teal"
            />
            <StatCard
                label="Artikel Formatif"
                :value="stats.artikelFormatif"
                :subtitle="subtitleStats.artikel"
                color="teal"
            />
        </div>

        <!-- Tables Row -->
        <div class="row g-4 mb-4">
            <!-- Penyewaan Menunggu Konfirmasi -->
            <div class="rounded-xl border border-gray-200 bg-white">
                <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                    <h2 class="font-semibold text-gray-900">Penyewaan menunggu konfirmasi</h2>
                    <Link
                        :href="route('admin.inventaris.index')"
                        class="text-sm font-medium text-teal-600 hover:text-teal-700"
                    >
                        Lihat semua
                    </Link>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-100 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                <th class="px-5 py-3">Penyewa</th>
                                <th class="px-5 py-3">Alat</th>
                                <th class="px-5 py-3">Tanggal pakai</th>
                                <th class="px-5 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr v-for="item in penyewaanPending" :key="item.id">
                                <td class="px-5 py-3 text-sm font-medium text-gray-900">{{ item.nama_penyewa }}</td>
                                <td class="px-5 py-3 text-sm text-gray-600">{{ item.nama_alat }}</td>
                                <td class="px-5 py-3 text-sm text-gray-600">{{ formatTanggalRange(item.tanggal_mulai, item.tanggal_selesai) }}</td>
                                <td class="px-5 py-3">
                                    <button class="rounded-md bg-teal-500 px-3 py-1.5 text-xs font-medium text-white transition-colors hover:bg-teal-600">
                                        Konfirmasi
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="penyewaanPending.length === 0">
                                <td colspan="4" class="px-5 py-8 text-center text-sm text-gray-400">
                                    Tidak ada penyewaan menunggu konfirmasi
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>

            <!-- Pesanan Merchandise Terbaru -->
            <div class="col-12 col-lg-6">
                <div class="card-panel">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-semibold mb-0">Pesanan merchandise terbaru</h6>
                        <Link href="/admin/danus" style="font-size: 13px; color: var(--primary-green); text-decoration: none; font-weight: 500;">
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
            <div class="rounded-xl border border-gray-200 bg-white">
                <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                    <h2 class="font-semibold text-gray-900">Pesanan merchandise terbaru</h2>
                    <Link
                        :href="route('admin.shop.index')"
                        class="text-sm font-medium text-teal-600 hover:text-teal-700"
                    >
                        Lihat semua
                    </Link>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-100 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                <th class="px-5 py-3">No. Pesanan</th>
                                <th class="px-5 py-3">Produk</th>
                                <th class="px-5 py-3">Total</th>
                                <th class="px-5 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr v-for="item in pesananTerbaru" :key="item.id">
                                <td class="px-5 py-3 text-sm font-medium text-gray-900">{{ item.no_pesanan }}</td>
                                <td class="px-5 py-3 text-sm text-gray-600">{{ item.nama_produk }}</td>
                                <td class="px-5 py-3 text-sm text-gray-900">{{ formatRupiah(item.total) }}</td>
                                <td class="px-5 py-3">
                                    <span :class="[statusClass(item.status), 'inline-block rounded-full px-2.5 py-1 text-xs font-medium']">
                                        {{ item.status }}
                                    </span>
                                </td>
                            </tr>
                            <tr v-if="pesananTerbaru.length === 0">
                                <td colspan="4" class="px-5 py-8 text-center text-sm text-gray-400">
                                    Belum ada pesanan
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Bottom Row -->
        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
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
                            <div class="flex-grow-1 min-width-0">
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
                        <!-- Status -->
                        <span :class="[kegiatanStatusClass(item.status), 'whitespace-nowrap text-sm font-medium']">
                            {{ item.status }}
                        </span>
                    </div>
                    <div v-if="kegiatanMendatang.length === 0" class="py-8 text-center text-sm text-gray-400">
                        Tidak ada kegiatan mendatang
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
            <div class="rounded-xl border border-gray-200 bg-white">
                <div class="border-b border-gray-100 px-5 py-4">
                    <h2 class="font-semibold text-gray-900">Aktivitas terbaru</h2>
                </div>
                <div class="divide-y divide-gray-50 px-5">
                    <div
                        v-for="item in aktivitasTerbaru"
                        :key="item.id"
                        class="flex items-start gap-3 py-4"
                    >
                        <!-- Avatar -->
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
                    <div v-if="aktivitasTerbaru.length === 0" class="py-8 text-center text-sm text-gray-400">
                        Belum ada aktivitas
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>