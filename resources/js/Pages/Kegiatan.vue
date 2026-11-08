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

// State Modal Detail Kegiatan
const selectedKegiatan = ref(null);
const isModalOpen = ref(false);

const openDetailModal = (item) => {
    selectedKegiatan.value = item;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    selectedKegiatan.value = null;
};

// Data Master Program Kerja HIMATIF (Akan Datang & Selesai Terlaksana)
const masterActivities = [
    // 1. Program Kerja Akan Datang (Upcoming)
    {
        id: 1,
        title: 'Seminar Teknologi Nasional: The Rise of AI & Modern Software Engineering',
        category: 'Seminar & Edukasi',
        status: 'akan_datang',
        statusLabel: 'Akan Datang',
        statusBadgeClass: 'bg-amber-500 text-white',
        date: '15 Desember 2026',
        time: '08:30 - 13:00 WIB',
        location: 'Auditorium Utama POLINDRA',
        image: '/images/aktivitas-iconfest.png',
        target: 'Mahasiswa POLINDRA & Umum',
        desc: 'Seminar teknologi nasional yang menghadirkan praktisi software engineer industri terkemuka untuk mengupas tuntas akselerasi kecerdasan buatan, arsitektur sistem software masa kini, dan strategi membangun portofolio karir teknologi.',
        pic: 'Departemen Riset & Teknologi (0821-9876-5432)',
        rundown: '08:30 Registrasi Peserta • 09:00 Keynote Session AI & Cloud • 11:00 Panel Talk Karir Tech • 12:30 Sesi Tanya Jawab & Doorprize',
        actionText: 'Daftar Seminar',
    },
    {
        id: 2,
        title: 'Workshop Hands-On: Fullstack Web Development & Cloud Deployment',
        category: 'Workshop & Pelatihan',
        status: 'akan_datang',
        statusLabel: 'Akan Datang',
        statusBadgeClass: 'bg-amber-500 text-white',
        date: '22 Desember 2026',
        time: '09:00 - 15:30 WIB',
        location: 'Laboratorium Komputer 2 POLINDRA',
        image: '/images/aktivitas-workshop.png',
        target: 'Mahasiswa Teknik Informatika',
        desc: 'Pelatihan intensif praktik langsung (hands-on) membangun aplikasi web modern, merancang RESTful API, integrasi basis data, dan konfigurasi otomatisasi deployment ke server cloud berbasis staging.',
        pic: 'Departemen Pendidikan & Pelatihan (0877-6543-2109)',
        rundown: '09:00 Fundamental Modern Web Stack • 10:30 Sesi Live Coding Hands-On • 13:30 Cloud Deployment & CI/CD • 15:00 Evaluasi & Sertifikat',
        actionText: 'Daftar Workshop',
    },

    // 2. Program Kerja yang Sudah Terlaksana (Selesai Terlaksana)
    {
        id: 3,
        title: 'PKPS (Pengenalan Kehidupan Program Studi) Teknik Informatika 2026',
        category: 'Kaderisasi & Pengenalan',
        status: 'selesai',
        statusLabel: 'Selesai Terlaksana',
        statusBadgeClass: 'bg-emerald-600 text-white',
        date: '02 - 04 September 2026',
        time: '07:30 - 16:00 WIB',
        location: 'Gedung Direktorat & Lab TI POLINDRA',
        image: null,
        target: 'Mahasiswa Baru Teknik Informatika 2026',
        desc: 'Pengenalan kehidupan program studi Teknik Informatika bagi mahasiswa baru angkatan 2026, meliputi kurikulum akademik perkuliahan, etika civitas kampus, pengenalan laboratorium, serta sambutan pengurus himpunan.',
        pic: 'Panitia PKPS HIMATIF (0813-2233-4455)',
        rundown: 'Telah sukses diselenggarakan menyambut 160 mahasiswa baru Teknik Informatika dengan pengenalan kurikulum dan tour fasilitas laboratorium terpadu.',
        actionText: 'Lihat Dokumentasi',
    },
    {
        id: 4,
        title: 'IT Cast: Podcast & Tech Talk Series Mahasiswa Informatika',
        category: 'Edukasi & Media',
        status: 'selesai',
        statusLabel: 'Selesai Terlaksana',
        statusBadgeClass: 'bg-emerald-600 text-white',
        date: '12 Oktober 2026',
        time: '14:00 - 16:00 WIB',
        location: 'Studio Multimedia HIMATIF (Streaming YouTube)',
        image: '/images/aktivitas-itcast.png',
        target: 'Mahasiswa & Pegiat Teknologi',
        desc: 'Program bincang santai inspiratif bersama alumni dan mahasiswa berprestasi yang mengulas dinamika industri software engineering, tips lolos magang unicorn, dan riset teknologi terkini.',
        pic: 'Departemen Komunikasi & Informasi (INFOKOM)',
        rundown: 'Telah rampung ditayangkan pada kanal resmi YouTube HIMATIF dengan total interaksi lebih dari 1.500 penonton aktif.',
        actionText: 'Lihat Dokumentasi',
    },
    {
        id: 5,
        title: 'LKMM-D (Latihan Keterampilan Manajemen Mahasiswa Tingkat Dasar) 2026',
        category: 'Kaderisasi & Organisasi',
        status: 'selesai',
        statusLabel: 'Selesai Terlaksana',
        statusBadgeClass: 'bg-emerald-600 text-white',
        date: '18 - 20 Oktober 2026',
        time: '3 Hari 2 Malam (Inap)',
        location: 'Bumi Perkemahan & Aula Pertemuan Kampus',
        image: '/images/galeri-3.png',
        target: 'Mahasiswa Baru Teknik Informatika 2026',
        desc: 'Kaderisasi formal pembentukan kepemimpinan mahasiswa, manajemen organisasi, komunikasi efektif, analisis SWOT, etika persidangan, serta perancangan program kerja himpunan.',
        pic: 'Departemen PSDM HIMATIF (0857-1234-5678)',
        rundown: 'Telah sukses diselenggarakan dengan 100% kelulusan dari 145 peserta kader baru Teknik Informatika.',
        actionText: 'Lihat Dokumentasi',
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
        'BEGIN:VEVENT',
        'UID:workshop-hands-on-2026@himatif.polindra.ac.id',
        'DTSTAMP:20261109T000000Z',
        'DTSTART:20261222T020000Z',
        'DTEND:20261222T083000Z',
        'SUMMARY:Workshop Hands-On Web Development & Cloud HIMATIF 2026',
        'DESCRIPTION:Pelatihan praktis live-coding web modern & cloud deployment.',
        'LOCATION:Laboratorium Komputer 2 POLINDRA',
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
                                Ikuti berbagai rangkaian seminar teknologi, pelatihan praktis workshop, kaderisasi LKMM-D, pengenalan PKPS, hingga bincang inspiratif IT Cast yang diselenggarakan oleh HIMATIF POLINDRA.
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
                                    <span>Terdekat: <strong>Seminar Teknologi (15 Des 2026)</strong></span>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan: Card Sorotan Bulan Ini (Sesuai Mockup) -->
                        <div class="w-full lg:w-[380px] shrink-0">
                            <div class="bg-[#212430]/90 backdrop-blur-md border border-slate-700/60 rounded-2xl p-5 sm:p-6 shadow-xl text-left space-y-3">
                                <div>
                                    <span class="text-[11px] font-black tracking-widest text-[#F95700] uppercase block">
                                        SOROTAN BULAN INI
                                    </span>
                                    <h3 class="text-lg font-black text-white mt-0.5">
                                        SEMINAR & WORKSHOP 2026
                                    </h3>
                                </div>

                                <!-- Baris Sorotan -->
                                <div class="divide-y divide-white/10 text-xs sm:text-[13px] pt-1">
                                    <div class="py-2.5 flex items-center justify-between text-slate-200">
                                        <span class="font-medium">Seminar Teknologi</span>
                                        <span class="font-bold text-orange-400">15 Des 2026</span>
                                    </div>
                                    <div class="py-2.5 flex items-center justify-between text-slate-200">
                                        <span class="font-medium">Workshop Fullstack Web</span>
                                        <span class="font-bold text-orange-400">22 Des 2026</span>
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
                        <div
                            v-for="item in filteredActivities"
                            :key="item.id"
                            class="bg-white rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-xl hover:border-orange-300 transition-all duration-300 overflow-hidden flex flex-col justify-between group"
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
                                        <!-- Placeholder Elegan Jika Foto Belum Ada (e.g. PKPS) -->
                                        <div v-else class="flex flex-col items-center justify-center text-center p-4 text-slate-300">
                                            <span class="text-3xl mb-1">🎓</span>
                                            <span class="text-[11px] font-black tracking-wider uppercase text-slate-200">Dokumentasi Arsip</span>
                                            <span class="text-[10px] text-slate-400 mt-0.5">PKPS Teknik Informatika</span>
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
                                <button
                                    type="button"
                                    @click="openDetailModal(item)"
                                    class="text-xs font-bold text-slate-900 group-hover:text-[#F95700] transition"
                                >
                                    {{ item.status === 'akan_datang' ? item.actionText : 'Lihat Detail' }}
                                </button>
                                <span class="text-slate-400 group-hover:text-[#F95700] group-hover:translate-x-1 transition text-sm">
                                    →
                                </span>
                            </div>
                        </div>
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

        <!-- MODAL POPUP DETAIL PROGRAM KERJA -->
        <div
            v-if="isModalOpen && selectedKegiatan"
            class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-4"
            @click.self="closeModal"
        >
            <div class="bg-white rounded-3xl max-w-xl w-full overflow-hidden shadow-2xl border border-slate-200 transform transition-all animate-in fade-in zoom-in-95 duration-200">
                <!-- Header Gambar Modal -->
                <div class="relative aspect-[16/9] w-full bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center">
                    <img
                        v-if="selectedKegiatan.image"
                        :src="selectedKegiatan.image"
                        :alt="selectedKegiatan.title"
                        class="w-full h-full object-cover"
                    />
                    <div v-else class="flex flex-col items-center justify-center text-center p-6 text-slate-300">
                        <span class="text-5xl mb-2">🎓</span>
                        <span class="text-sm font-black tracking-wider uppercase text-white">Dokumentasi PKPS 2026</span>
                        <span class="text-xs text-slate-400 mt-1">Foto dokumentasi kegiatan akan diperbarui segera</span>
                    </div>
                    <!-- Tombol Tutup -->
                    <button
                        type="button"
                        @click="closeModal"
                        class="absolute top-4 right-4 w-9 h-9 rounded-full bg-black/60 hover:bg-black text-white flex items-center justify-center transition shadow-lg text-sm"
                    >
                        ✕
                    </button>
                    <!-- Status Badge -->
                    <div class="absolute bottom-4 left-4">
                        <span
                            class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider text-white shadow-md"
                            :class="selectedKegiatan.status === 'akan_datang' ? 'bg-[#F95700]' : 'bg-emerald-600'"
                        >
                            {{ selectedKegiatan.statusLabel }}
                        </span>
                    </div>
                </div>

                <!-- Konten Modal -->
                <div class="p-6 sm:p-8 space-y-5 text-left">
                    <div>
                        <span class="text-xs font-black tracking-widest text-[#F95700] uppercase block">
                            {{ selectedKegiatan.category }}
                        </span>
                        <h3 class="text-xl sm:text-2xl font-black text-slate-900 mt-1 leading-snug">
                            {{ selectedKegiatan.title }}
                        </h3>
                    </div>

                    <!-- Meta Information Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-700">
                        <div>
                            <span class="font-bold text-slate-400 block mb-0.5">WAKTU PELAKSANAAN</span>
                            <span class="font-semibold text-slate-900">📅 {{ selectedKegiatan.date }}</span>
                            <span class="block text-slate-500 mt-0.5">⏰ {{ selectedKegiatan.time }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-slate-400 block mb-0.5">LOKASI KEGIATAN</span>
                            <span class="font-semibold text-slate-900">📍 {{ selectedKegiatan.location }}</span>
                        </div>
                    </div>

                    <!-- Deskripsi Lengkap -->
                    <div class="space-y-1.5">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Deskripsi Kegiatan</h4>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                            {{ selectedKegiatan.desc }}
                        </p>
                    </div>

                    <!-- Rundown / Catatan Pelaksanaan -->
                    <div class="space-y-1.5 p-3.5 rounded-xl bg-orange-50 border border-orange-200/60 text-xs">
                        <h4 class="font-bold text-orange-950 uppercase tracking-wider">Rundown / Hasil Pelaksanaan</h4>
                        <p class="text-orange-900/90 leading-relaxed">
                            {{ selectedKegiatan.rundown }}
                        </p>
                    </div>

                    <!-- PIC Kontak & Sasaran -->
                    <div class="flex items-center justify-between text-xs text-slate-500 pt-1">
                        <span>🎯 Sasaran: <strong class="text-slate-800">{{ selectedKegiatan.target }}</strong></span>
                    </div>

                    <!-- Footer Modal Action -->
                    <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                        <button
                            type="button"
                            @click="closeModal"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition"
                        >
                            Tutup
                        </button>
                        <button
                            type="button"
                            @click="closeModal"
                            class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-[#F95700] hover:bg-orange-600 transition shadow-md shadow-orange-500/20"
                        >
                            {{ selectedKegiatan.status === 'akan_datang' ? 'Konfirmasi Kehadiran' : 'Tutup' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
