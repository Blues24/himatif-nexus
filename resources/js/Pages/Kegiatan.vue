<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    kegiatans: {
        type: Array,
        default: () => [],
    },
    upcoming: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({ status: null }),
    },
});

// State Filter Status & Pencarian
const activeStatusFilter = ref('semua'); // 'semua', 'akan_datang', 'selesai'
const searchQuery = ref('');
const currentPage = ref(1);

// Data Master Program Kerja HIMATIF (Akan Datang & Selesai Terlaksana)
const masterActivities = [
    // 1. Program Kerja Akan Datang (Upcoming - Placeholder)
    {
        id: 1,
        title: 'Nama Program Kerja Mendatang (Placeholder)',
        category: 'Seminar & Edukasi',
        status: 'akan_datang',
        statusLabel: 'Akan Datang',
        statusBadgeClass: 'bg-amber-500 text-white',
        date: 'DD Bulan YYYY',
        time: '08:00 - Selesai WIB',
        location: 'Lokasi Pelaksanaan Kegiatan',
        image: null,
        placeholderIcon: '💡',
        placeholderLabel: 'Poster Agenda',
        placeholderSub: 'Agenda Placeholder #1',
        target: 'Sasaran Peserta Kegiatan',
        desc: 'Deskripsi lengkap mengenai agenda, tujuan pelaksanaan, materi utama, dan target luaran program kerja yang akan diselenggarakan oleh HIMATIF POLINDRA.',
        pic: 'Panitia Penyelenggara (Kontak PIC)',
        rundown: 'Rincian susunan acara, pembicara, dan jadwal lengkap kegiatan akan diumumkan secara bertahap menjelang hari pelaksanaan.',
        actionText: 'Daftar Kegiatan',
    },

    // 2. Program Kerja yang Sudah Terlaksana (Selesai Terlaksana - Placeholder)
    {
        id: 3,
        title: 'Nama Program Kerja Terlaksana (Placeholder 1)',
        category: 'Kaderisasi & Pengenalan',
        status: 'selesai',
        statusLabel: 'Selesai Terlaksana',
        statusBadgeClass: 'bg-emerald-600 text-white',
        date: 'DD Bulan YYYY',
        time: '08:00 - Selesai WIB',
        location: 'Lokasi Pelaksanaan Kegiatan',
        image: null,
        placeholderIcon: '🎓',
        placeholderLabel: 'Dokumentasi Arsip',
        placeholderSub: 'Arsip Placeholder #2',
        target: 'Sasaran Peserta Kegiatan',
        desc: 'Deskripsi arsip dokumentasi pelaksanaan program kerja yang telah berhasil diselenggarakan dengan sukses oleh pengurus himpunan.',
        pic: 'Panitia Penyelenggara HIMATIF',
        rundown: 'Kegiatan telah sukses diselenggarakan sesuai dengan susunan jadwal dan target partisipasi peserta yang telah direncanakan.',
        actionText: 'Lihat Detail',
    },
    {
        id: 4,
        title: 'Nama Program Kerja Terlaksana (Placeholder 2)',
        category: 'Edukasi & Media',
        status: 'selesai',
        statusLabel: 'Selesai Terlaksana',
        statusBadgeClass: 'bg-emerald-600 text-white',
        date: 'DD Bulan YYYY',
        time: '08:00 - Selesai WIB',
        location: 'Lokasi Pelaksanaan Kegiatan',
        image: null,
        placeholderIcon: '🎙️',
        placeholderLabel: 'Dokumentasi Arsip',
        placeholderSub: 'Arsip Placeholder #3',
        target: 'Sasaran Peserta Kegiatan',
        desc: 'Deskripsi arsip dokumentasi pelaksanaan program kerja yang telah berhasil diselenggarakan dengan sukses oleh pengurus himpunan.',
        pic: 'Panitia Penyelenggara HIMATIF',
        rundown: 'Kegiatan telah sukses diselenggarakan sesuai dengan susunan jadwal dan target partisipasi peserta yang telah direncanakan.',
        actionText: 'Lihat Detail',
    },
    {
        id: 5,
        title: 'Nama Program Kerja Terlaksana (Placeholder 3)',
        category: 'Kaderisasi & Organisasi',
        status: 'selesai',
        statusLabel: 'Selesai Terlaksana',
        statusBadgeClass: 'bg-emerald-600 text-white',
        date: 'DD Bulan YYYY',
        time: '08:00 - Selesai WIB',
        location: 'Lokasi Pelaksanaan Kegiatan',
        image: null,
        placeholderIcon: '👥',
        placeholderLabel: 'Dokumentasi Arsip',
        placeholderSub: 'Arsip Placeholder #4',
        target: 'Sasaran Peserta Kegiatan',
        desc: 'Deskripsi arsip dokumentasi pelaksanaan program kerja yang telah berhasil diselenggarakan dengan sukses oleh pengurus himpunan.',
        pic: 'Panitia Penyelenggara HIMATIF',
        rundown: 'Kegiatan telah sukses diselenggarakan sesuai dengan susunan jadwal dan target partisipasi peserta yang telah direncanakan.',
        actionText: 'Lihat Detail',
    },
];

// Computed Filtered Activities
const filteredActivities = computed(() => {
    let result = masterActivities;

    // Filter berdasarkan status
    if (activeStatusFilter.value !== 'semua') {
        result = result.filter(item => item.status === activeStatusFilter.value);
    }

    // Filter pencarian
    if (searchQuery.value.trim() !== '') {
        const q = searchQuery.value.toLowerCase();
        result = result.filter(
            item =>
                item.title.toLowerCase().includes(q) ||
                item.desc.toLowerCase().includes(q) ||
                item.location.toLowerCase().includes(q) ||
                item.category.toLowerCase().includes(q)
        );
    }

    return result;
});

// Hitung jumlah proker per status
const countAll = computed(() => masterActivities.length);
const countUpcoming = computed(() => masterActivities.filter(item => item.status === 'akan_datang').length);
const countCompleted = computed(() => masterActivities.filter(item => item.status === 'selesai').length);

// Fungsi Download Kalender .ics
const downloadCalendarICS = () => {
    const icsContent = [
        'BEGIN:VCALENDAR',
        'VERSION:2.0',
        'PRODID:-//HIMATIF POLINDRA//Kalender Kegiatan 2026//ID',
        'CALSCALE:GREGORIAN',
        'METHOD:PUBLISH',
        'BEGIN:VEVENT',
        'UID:seminar-teknologi-2026@himatif.polindra.ac.id',
        'DTSTAMP:20261109T000000Z',
        'DTSTART:20261215T013000Z',
        'DTEND:20261215T060000Z',
        'SUMMARY:Seminar Teknologi Nasional HIMATIF 2026',
        'DESCRIPTION:Seminar tren Artificial Intelligence dan masa depan software engineering.',
        'LOCATION:Auditorium Utama POLINDRA',
        'STATUS:CONFIRMED',
        'END:VEVENT',
        'END:VCALENDAR'
    ].join('\r\n');

    const blob = new Blob([icsContent], { type: 'text/calendar;charset=utf-8' });
    const link = document.createElement('a');
    link.href = window.URL.createObjectURL(blob);
    link.setAttribute('download', 'Jadwal-Kegiatan-HIMATIF-2026.ics');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};
</script>

<template>
    <Head title="Kalender Kegiatan & Program Kerja - HIMATIF POLINDRA" />

    <AuthenticatedLayout>
        <div class="bg-gradient-to-b from-white via-slate-50 to-slate-100 min-h-screen pb-20">
            <!-- Container Utama -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8 space-y-10 sm:space-y-12">
                
                <!-- 1. Featured Dark Banner (Sesuai Persis dengan Mockup Referensi) -->
                <div
                    class="relative overflow-hidden rounded-3xl sm:rounded-[36px] p-6 sm:p-10 lg:p-12 text-white shadow-2xl shadow-slate-900/40 border border-slate-800"
                    style="background-color: #13151D;"
                >
                    <div class="flex flex-col lg:flex-row items-center justify-between gap-8 lg:gap-12">
                        <!-- Kolom Kiri: Teks & Tombol Aksi -->
                        <div class="flex-1 w-full space-y-5 text-left">
                            <!-- Pill Badge Kalender -->
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 border border-white/15 shadow-inner">
                                <span class="text-xs">🗓</span>
                                <span class="text-[11px] sm:text-xs font-black text-orange-400 tracking-wider uppercase">
                                    KALENDER KEGIATAN & PROGRAM KERJA HIMATIF 2026
                                </span>
                            </div>

                            <!-- Judul Besar -->
                            <h1 class="text-3xl sm:text-4xl lg:text-[42px] font-black text-white tracking-tight leading-[1.18]">
                                Agenda & Kegiatan Mahasiswa Teknik Informatika<br class="hidden sm:inline" />
                                
                            </h1>

                            <!-- Deskripsi Singkat -->
                            <p class="text-xs sm:text-sm text-slate-300 font-normal leading-relaxed max-w-xl">
                                Ikuti berbagai rangkaian program kerja, agenda seminar teknologi, pelatihan praktis, hingga forum diskusi yang diselenggarakan oleh HIMATIF POLINDRA.
                            </p>

                            <!-- Tombol CTA & Status Terdekat -->
                            <div class="flex flex-wrap items-center gap-3 pt-2">
                                <a
                                    href="#daftar-proker"
                                    class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-xs sm:text-sm text-white bg-[#F95700] hover:bg-orange-600 shadow-md shadow-orange-500/20 transition transform active:scale-95 select-none"
                                >
                                    <span>Lihat Kegiatan Terdekat</span>
                                </a>

                                <div class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-white/10 border border-white/15 text-xs sm:text-sm text-slate-200 font-medium">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <span>Terdekat: <strong>Agenda Mendatang (Segera Diumumkan)</strong></span>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan: Card Sorotan Bulan Ini (Sesuai Mockup) -->
                        <div class="w-full lg:w-[380px] shrink-0">
                            <div class="bg-[#212430]/90 backdrop-blur-md border border-slate-700/60 rounded-2xl p-5 sm:p-6 shadow-xl text-left space-y-3">
                                <div>
                                    <span class="text-[11px] font-black tracking-widest text-[#F95700] uppercase block">
                                        SOROTAN KEGIATAN TERDEKAT
                                    </span>
                                    <h3 class="text-lg font-black text-white mt-0.5">
                                        AGENDA KEGIATAN HIMATIF 2026
                                    </h3>
                                </div>

                                <!-- Baris Sorotan -->
                                <div class="divide-y divide-white/10 text-xs sm:text-[13px] pt-1">
                                    <div class="py-2.5 flex items-center justify-between text-slate-200">
                                        <span class="font-medium">Jadwal Acara</span>
                                        <span class="font-bold text-orange-400">Segera Diumumkan</span>
                                    </div>
                                    <div class="py-2.5 flex items-center justify-between text-slate-200">
                                        <span class="font-medium">Lokasi</span>
                                        <span class="font-bold text-slate-300">Kampus POLINDRA</span>
                                    </div>
                                    <div class="py-2.5 flex items-center justify-between text-slate-200">
                                        <span class="font-medium">Status Pendaftaran</span>
                                        <span class="inline-flex items-center gap-1.5 font-bold text-emerald-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                            Segera Dibuka
                                        </span>
                                    </div>
                                </div>

                                <!-- Tombol Simpan Jadwal ke Kalender -->
                                <button
                                    type="button"
                                    @click="downloadCalendarICS"
                                    class="w-full mt-3 py-2.5 px-4 bg-white hover:bg-slate-100 text-slate-900 font-bold text-xs rounded-xl text-center block transition shadow-sm active:scale-95"
                                >
                                    + Simpan Jadwal ke Kalender (.ics)
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Section Program Kerja: Telah Terlaksana & Akan Dilaksanakan -->
                <div id="daftar-proker" class="space-y-6 pt-2">
                    
                    <!-- Header Section & Search Bar -->
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                        <div>
                            <span class="text-xs font-black tracking-widest text-[#F95700] uppercase block mb-1">
                                PROGRAM KERJA & AGENDA HIMATIF
                            </span>
                            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                                Rangkaian Kegiatan Organisasi
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-xl">
                                Pantau jadwal agenda yang akan datang serta arsip kegiatan yang telah sukses diselenggarakan.
                            </p>
                        </div>

                        <!-- Kotak Pencarian -->
                        <div class="w-full md:w-80 relative">
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Cari kegiatan atau lokasi..."
                                class="w-full pl-10 pr-4 py-2.5 rounded-full border border-slate-200 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 shadow-xs transition"
                            />
                            <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Filter Status Utama: Semua, Akan Datang, Selesai Terlaksana -->
                    <div class="flex flex-wrap items-center gap-2 p-1.5 bg-slate-100/90 rounded-2xl w-fit border border-slate-200/80">
                        <button
                            type="button"
                            @click="activeStatusFilter = 'semua'"
                            class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all duration-200"
                            :class="activeStatusFilter === 'semua' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                        >
                            Semua Kegiatan ({{ countAll }})
                        </button>
                        <button
                            type="button"
                            @click="activeStatusFilter = 'akan_datang'"
                            class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all duration-200 flex items-center gap-1.5"
                            :class="activeStatusFilter === 'akan_datang' ? 'bg-[#F95700] text-white shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                        >
                            <span class="w-2 h-2 rounded-full bg-amber-300"></span>
                            <span>Akan Datang ({{ countUpcoming }})</span>
                        </button>
                        <button
                            type="button"
                            @click="activeStatusFilter = 'selesai'"
                            class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all duration-200 flex items-center gap-1.5"
                            :class="activeStatusFilter === 'selesai' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                        >
                            <span>✓ Selesai Terlaksana ({{ countCompleted }})</span>
                        </button>
                    </div>

                    <!-- 3. Grid Kartu Program Kerja (Ukuran Presisi Selaras dengan Halaman Informasi) -->
                    <div v-if="filteredActivities.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6">
                        <Link
                            v-for="item in filteredActivities"
                            :key="item.id"
                            :href="route('kegiatan.show', item.id)"
                            class="bg-white rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-xl hover:border-orange-300 transition-all duration-300 overflow-hidden flex flex-col justify-between group cursor-pointer"
                        >
                            <div>
                                <!-- Thumbnail Gambar dengan Badge Status & Kategori -->
                                <div class="p-3 pb-0">
                                    <div class="relative overflow-hidden rounded-xl aspect-[16/10] bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center">
                                        <img
                                            v-if="item.image"
                                            :src="item.image"
                                            :alt="item.title"
                                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                            loading="lazy"
                                        />
                                        <!-- Placeholder Elegan Jika Foto Belum Ada -->
                                        <div v-else class="flex flex-col items-center justify-center text-center p-4 text-slate-300">
                                            <span class="text-3xl mb-1">{{ item.placeholderIcon || '📋' }}</span>
                                            <span class="text-[11px] font-black tracking-wider uppercase text-slate-200">{{ item.placeholderLabel || 'Dokumentasi Arsip' }}</span>
                                            <span class="text-[10px] text-slate-400 mt-0.5 truncate max-w-[190px]">{{ item.placeholderSub || item.title }}</span>
                                        </div>
                                        <!-- Badge Status di Atas Kiri -->
                                        <div class="absolute top-2.5 left-2.5">
                                            <span
                                                class="inline-block px-2.5 py-1 rounded-full text-[10px] sm:text-[11px] font-black uppercase tracking-wider shadow-sm"
                                                :class="item.status === 'akan_datang' ? 'bg-[#F95700] text-white' : 'bg-emerald-600 text-white'"
                                            >
                                                {{ item.statusLabel }}
                                            </span>
                                        </div>

                                        <!-- Badge Kategori di Atas Kanan -->
                                        <div class="absolute top-2.5 right-2.5">
                                            <span class="inline-block px-2.5 py-1 rounded-full text-[10px] sm:text-[11px] font-bold bg-slate-900/80 backdrop-blur-xs text-white border border-white/20">
                                                {{ item.category }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Konten Teks -->
                                <div class="p-4 sm:p-5 space-y-2">
                                    <!-- Tanggal & Lokasi -->
                                    <div class="space-y-0.5 text-[11px] font-semibold text-slate-500">
                                        <div class="flex items-center gap-1.5 text-orange-600">
                                            <span>📅</span>
                                            <span>{{ item.date }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5 text-slate-500 truncate" :title="item.location">
                                            <span>📍</span>
                                            <span class="truncate">{{ item.location }}</span>
                                        </div>
                                    </div>

                                    <!-- Judul Kegiatan -->
                                    <h3 class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-[#F95700] transition line-clamp-2 leading-snug">
                                        {{ item.title }}
                                    </h3>

                                    <!-- Deskripsi Singkat -->
                                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                        {{ item.desc }}
                                    </p>

                                    <!-- Sasaran Target -->
                                    <div class="pt-0.5">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 inline-block truncate max-w-full">
                                            🎯 {{ item.target }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Aksi Kartu -->
                            <div class="px-4 sm:px-5 pb-4 sm:pb-5 pt-1 flex items-center justify-between border-t border-slate-100">
                                <span class="text-xs font-bold text-slate-900 group-hover:text-[#F95700] transition">
                                    {{ item.status === 'akan_datang' ? item.actionText : 'Lihat Detail' }}
                                </span>
                                <span class="text-slate-400 group-hover:text-[#F95700] group-hover:translate-x-1 transition text-sm">
                                    →
                                </span>
                            </div>
                        </Link>
                    </div>

                    <!-- State Kosong jika Tidak Ada Kegiatan -->
                    <div v-else class="text-center py-16 bg-white rounded-3xl border border-slate-200/80 p-8">
                        <div class="text-4xl mb-3">📋</div>
                        <h3 class="text-lg font-bold text-slate-800">Tidak ada program kerja yang sesuai</h3>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-md mx-auto">
                            Coba ubah status filter atau gunakan kata kunci pencarian yang berbeda.
                        </p>
                        <button
                            type="button"
                            @click="activeStatusFilter = 'semua'; searchQuery = ''"
                            class="mt-4 px-4 py-2 rounded-xl bg-orange-500 text-white font-bold text-xs hover:bg-orange-600 transition"
                        >
                            Reset Semua Filter
                        </button>
                    </div>

                </div>

            </div>
        </div>



    </AuthenticatedLayout>
</template>
