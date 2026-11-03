<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed, ref, onMounted, onUnmounted } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth?.user || { name: 'Tamu' });
const isLoggedIn = computed(() => !!page.props.auth?.user);

// Data Galeri Dokumentasi (Bangga Menjadi Bagian Dari Kami)
const galleryPhotos = [
    {
        src: '/images/galeri-1.png',
        alt: 'Briefing dan Rapat Anggota HIMATIF',
        title: 'Rapat Kerja & Pengarahan Anggota',
    },
    {
        src: '/images/galeri-2.jpg',
        alt: 'Keluarga Besar Mahasiswa Informatika',
        title: 'Kebersamaan & Solidaritas Informatika',
    },
    {
        src: '/images/galeri-3.png',
        alt: 'Aksi Semangat dan Kaderisasi',
        title: 'Semangat Kaderisasi & Yel-Yel Mahasiswa',
    },
    {
        src: '/images/hero-pengurus.jpg',
        alt: 'Pengurus Lengkap Periode 2025/2026',
        title: 'Keluarga Besar Pengurus HIMATIF 18',
    },
    {
        src: '/images/aktivitas-workshop.png',
        alt: 'Pelatihan dan Workshop Teknologi',
        title: 'Workshop & Upgrade Skill Teknologi',
    },
    {
        src: '/images/aktivitas-itcast.png',
        alt: 'Informatics Care & Bakti Sosial',
        title: 'Bakti Sosial & Kolaborasi Mahasiswa',
    },

    {
        src: '/images/galeri-4.jpg',
        alt: 'PKPS',
        title: 'Semangat Jargon Kegiatan PKPS 2026',
    },
   
];

// Carousel Kontrol & Auto Scroll 2 Detik
const galleryContainer = ref(null);
const currentSlideIndex = ref(0);
const isHovering = ref(false);
let autoScrollTimer = null;

const scrollNext = () => {
    if (!galleryContainer.value) return;
    const container = galleryContainer.value;
    const maxScroll = container.scrollWidth - container.clientWidth;
    const cardWidth = container.firstElementChild?.clientWidth || 340;
    const gap = 20;
    const step = cardWidth + gap;

    if (container.scrollLeft + step >= maxScroll - 15) {
        // Kembali ke foto pertama jika sudah di ujung kanan
        container.scrollTo({ left: 0, behavior: 'smooth' });
        currentSlideIndex.value = 0;
    } else {
        container.scrollBy({ left: step, behavior: 'smooth' });
        currentSlideIndex.value = (currentSlideIndex.value + 1) % galleryPhotos.length;
    }
};

const scrollPrev = () => {
    if (!galleryContainer.value) return;
    const container = galleryContainer.value;
    const cardWidth = container.firstElementChild?.clientWidth || 340;
    const gap = 20;
    const step = cardWidth + gap;

    if (container.scrollLeft - step <= 10) {
        container.scrollTo({ left: container.scrollWidth, behavior: 'smooth' });
        currentSlideIndex.value = galleryPhotos.length - 1;
    } else {
        container.scrollBy({ left: -step, behavior: 'smooth' });
        currentSlideIndex.value = Math.max(0, currentSlideIndex.value - 1);
    }
};

const scrollToSlide = (index) => {
    if (!galleryContainer.value) return;
    const container = galleryContainer.value;
    const cardWidth = container.firstElementChild?.clientWidth || 340;
    const gap = 20;
    container.scrollTo({ left: index * (cardWidth + gap), behavior: 'smooth' });
    currentSlideIndex.value = index;
};

const startAutoScroll = () => {
    stopAutoScroll();
    autoScrollTimer = setInterval(() => {
        if (!isHovering.value) {
            scrollNext();
        }
    }, 2000); // Otomatis scroll tiap 2 detik
};

const stopAutoScroll = () => {
    if (autoScrollTimer) {
        clearInterval(autoScrollTimer);
        autoScrollTimer = null;
    }
};

onMounted(() => {
    startAutoScroll();
});

onUnmounted(() => {
    stopAutoScroll();
});

// Data Layanan HIMATIF (Fitur Yang Tersedia)
const himatifServices = [
    {
        category: 'INVENTARIS HIMPUNAN',
        title: 'Peminjaman Inventaris',
        desc: 'Ajukan permohonan peminjaman HT, Kamera dan Inventaris HIMATIF lainnya.',
        btnText: 'Booking Sekarang',
        href: '#inventaris',
        iconType: 'inventory',
    },
    {
        category: 'OFFICIAL MERCHANDISE',
        title: 'HIMATIF – Merch Shop',
        desc: 'Pesan PDH resmi jurusan, hoodie angkatan, lanyard eksklusif HIMA, tech stickers, dan kaos edisi khusus Techfest.',
        btnText: 'Kunjungi Katalog',
        href: '#shop',
        iconType: 'shop',
    },
    {
        category: 'PRESTASI & DELEGASI',
        title: 'Info Seputar Lomba',
        desc: 'Pusat informasi lomba yang bisa diikuti oleh Mahasiswa Teknik Informatika.',
        btnText: 'Lihat Kompetisi',
        href: '#lomba',
        iconType: 'trophy',
    },
    {
        category: 'ARSIP AKADEMIK',
        title: 'Materi dan Bank Soal Study Club',
        desc: 'Akses modul praktikum Algoritma, Basis Data, Jaringan Komputer, hingga arsip soal UTS/UAS 5 tahun terakhir.',
        btnText: 'Lihat Materi',
        href: '#materi',
        iconType: 'academic',
    },
];

// Data Aktivitas / Program Kerja Terkini
const activities = [
    {
        title: 'Workshop Laravel 13: Laravel 13 Integrated with AI',
        desc: 'Pelatihan intensif terkait Laravel 13 yang digabungkan dengan fitur AI',
        date: '20 Oktober 2026',
        location: 'GSC Polindra',
        status: 'Selesai Terlaksana',
        image: '/images/aktivitas-workshop.png',
    },
    {
        title: 'ICONFEST 2026: Hackathon & UI/UX',
        desc: 'Ajang adu kreasi solusi software berskala nasional dengan total hadiah 45 Juta..',
        date: '15 Nov 2026',
        location: 'Politeknik Negeri Indramayu',
        status: 'Sisa 8 Hari',
        image: '/images/aktivitas-iconfest.png',
    },
    {
        title: 'Informatics Care: Donor Darah & Donasi Laptop...',
        desc: 'Kolaborasi HIMATIF bersama Kampus',
        date: '02 Des 2026',
        location: 'Pleno POLINDRA',
        status: 'Akan Datang',
        image: '/images/aktivitas-itcast.png',
    },
    
];
</script>

<template>
    <Head title="Beranda - HIMATIF POLINDRA" />

    <AuthenticatedLayout>
        <div class="bg-gradient-to-b from-white via-slate-50 to-slate-100 min-h-screen pb-16">
            <!-- Container Utama -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 sm:pt-6 space-y-8">
                
                <!-- Status Bar Admin jika Login -->
                <div v-if="isLoggedIn" class="p-3.5 px-5 rounded-2xl bg-slate-900 text-white flex items-center justify-between text-xs sm:text-sm shadow-md">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>Halo Admin, <strong class="text-orange-400">{{ user.name }}</strong> (Sesi Admin Aktif)</span>
                    </div>
                    <Link :href="route('profile.edit')" class="text-xs font-semibold px-3 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 transition">
                        Kelola Akun
                    </Link>
                </div>

                <!-- 1. Hero Section dengan Foto Pengurus (Background + Opacity) & Teks HTML di Atasnya -->
                <div class="relative overflow-hidden rounded-3xl border border-slate-200/90 shadow-xl bg-white min-h-[420px] sm:min-h-[460px] flex items-center">
                    <!-- Foto Pengurus sebagai Background dengan Opacity -->
                    <div class="absolute inset-0 z-0">
                        <img
                            src="/images/hero-pengurus.jpg"
                            alt="Background Pengurus HIMATIF"
                            class="w-full h-full object-cover object-center opacity-40 select-none pointer-events-none"
                        />
                        <!-- Soft White Gradient Wash agar teks di atasnya sangat kontras & terbaca jelas -->
                        <div class="absolute inset-0 bg-gradient-to-r from-white/90 via-white/75 to-white/30"></div>
                    </div>

                    <!-- Teks & Elemen HTML di atas Foto (z-10) -->
                    <div class="relative z-10 w-full p-6 sm:p-10 lg:p-14 grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                        <!-- Kolom Kiri: Teks & Tombol -->
                        <div class="md:col-span-8 lg:col-span-8 space-y-4 sm:space-y-5 text-left">
                            <!-- Pill Badge -->
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/95 border border-slate-300 shadow-xs backdrop-blur-xs">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#F95700]"></span>
                                <span class="text-[11px] sm:text-xs font-bold text-slate-800 tracking-wider uppercase">
                                    PERIODE KEPENGURUSAN 2025/2026 • HIMATIF 18
                                </span>
                            </div>

                            <!-- Judul Utama -->
                            <h1 class="text-3xl sm:text-5xl lg:text-[54px] font-black text-slate-900 tracking-tight leading-none">
                                Himpunan Mahasiswa
                                <span class="block text-[#F95700] mt-1 sm:mt-2">Teknik Informatika</span>
                            </h1>

                            <!-- Deskripsi Singkat -->
                            <p class="text-xs sm:text-sm lg:text-base text-slate-600 max-w-xl font-normal leading-relaxed">
                                Wadah kolaborasi, akselerasi riset terapan, dan pemberdayaan talenta teknologi mahasiswa Informatika. Bersama mencetak inovator kompeten berintegritas tinggi menuju lanskap digital global.
                            </p>

                            <!-- Tombol Aksi Hero -->
                            <div class="pt-2 flex flex-wrap gap-3 sm:gap-4 items-center">
                                <a
                                    href="#layanan"
                                    class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-xs sm:text-sm text-white bg-gradient-to-r from-[#F95700] to-orange-600 hover:from-orange-600 hover:to-orange-700 shadow-md shadow-orange-500/25 transition transform active:scale-95"
                                >
                                    <span>Jelajahi Program</span>
                                    <span>→</span>
                                </a>
                                <a
                                    href="#agenda"
                                    class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-xs sm:text-sm text-slate-800 bg-white hover:bg-slate-50 border border-slate-200/90 shadow-xs hover:shadow transition transform active:scale-95"
                                >
                                    <span>📅 Daftar Kegiatan</span>
                                </a>
                            </div>
                        </div>

                        <!-- Kolom Kanan: Grafis Megafon 3D dengan floating icons -->
                        <div class="hidden md:flex md:col-span-4 lg:col-span-4 justify-center items-center">
                            <img
                                src="/images/megaphone-illustration.png"
                                alt="Ilustrasi Informasi HIMATIF"
                                class="w-56 sm:w-64 lg:w-72 h-auto object-contain drop-shadow-lg select-none transform hover:scale-105 transition duration-300"
                            />
                        </div>
                    </div>
                </div>

                <!-- 2. Orange Stats Banner (Seperti di Desain) -->
                <div class="relative z-10 -mt-2 sm:-mt-4">
                    <div class="bg-gradient-to-r from-[#F95700] via-[#F95900] to-[#FF6A00] rounded-2xl sm:rounded-3xl shadow-xl shadow-orange-500/25 p-5 sm:p-7 text-slate-900">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 sm:gap-4 divide-y sm:divide-y-0 sm:divide-x divide-white/20">
                            <!-- 1. 500+ Anggota Mahasiswa -->
                            <div class="flex items-center gap-3.5 sm:gap-4">
                                <div class="w-12 h-12 rounded-xl bg-white shadow-xs flex items-center justify-center shrink-0 text-slate-800">
                                    <svg class="w-6 h-6 text-slate-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-xl sm:text-2xl font-black text-slate-950 tracking-tight leading-none">
                                        500+
                                    </div>
                                    <div class="text-xs sm:text-sm font-semibold text-slate-900/90 mt-1">
                                        Anggota Mahasiswa
                                    </div>
                                </div>
                            </div>

                            <!-- 2. 12 Program Kerja Tahunan -->
                            <div class="flex items-center gap-3.5 sm:gap-4 pt-4 sm:pt-0 sm:pl-6">
                                <div class="w-12 h-12 rounded-xl bg-white shadow-xs flex items-center justify-center shrink-0 text-slate-800">
                                    <svg class="w-6 h-6 text-slate-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.63 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.58-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7a3 3 0 114.243-4.243 3 3 0 01-4.243 4.243z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-xl sm:text-2xl font-black text-slate-950 tracking-tight leading-none">
                                        12 Program
                                    </div>
                                    <div class="text-xs sm:text-sm font-semibold text-slate-900/90 mt-1">
                                        Kerja Tahunan
                                    </div>
                                </div>
                            </div>

                            <!-- 3. 3 Divisi Pilar Organisasi -->
                            <div class="flex items-center gap-3.5 sm:gap-4 pt-4 sm:pt-0 sm:pl-6">
                                <div class="w-12 h-12 rounded-xl bg-white shadow-xs flex items-center justify-center shrink-0 text-slate-800">
                                    <svg class="w-6 h-6 text-slate-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="9" y="3" width="6" height="4" rx="1"/>
                                        <rect x="2" y="17" width="6" height="4" rx="1"/>
                                        <rect x="16" y="17" width="6" height="4" rx="1"/>
                                        <path d="M12 7v5m0 0H5v5m7-5h7v5"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-xl sm:text-2xl font-black text-slate-950 tracking-tight leading-none">
                                        3 Divisi
                                    </div>
                                    <div class="text-xs sm:text-sm font-semibold text-slate-900/90 mt-1">
                                        Pilar Organisasi
                                    </div>
                                </div>
                            </div>

                            <!-- 4. Sangat Baik Akreditasi Jurusan -->
                            <div class="flex items-center gap-3.5 sm:gap-4 pt-4 sm:pt-0 sm:pl-6">
                                <div class="w-12 h-12 rounded-xl bg-white shadow-xs flex items-center justify-center shrink-0 text-slate-800">
                                    <svg class="w-6 h-6 text-slate-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="8" r="6"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.21 13.89L7 22l5-3 5 3-1.21-8.11"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-xl sm:text-2xl font-black text-slate-950 tracking-tight leading-none">
                                        Baik Sekali
                                    </div>
                                    <div class="text-xs sm:text-sm font-semibold text-slate-900/90 mt-1">
                                        Akreditasi Prodi
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Profile Organisasi (Sesuai Mockup Desain) -->
                <div id="informasi" class="rounded-3xl bg-[#090F24] border border-slate-800 p-6 sm:p-10 lg:p-12 shadow-2xl text-white">
                    <!-- Pill Badge Header -->
                    <div class="mb-6 sm:mb-8">
                        <span class="inline-block px-4 py-1.5 rounded-full bg-white text-[#F95700] text-[11px] sm:text-xs font-black tracking-widest uppercase shadow-md">
                            PROFILE ORGANISASI
                        </span>
                    </div>

                    <!-- Dua Card Pimpinan: Ketua & Wakil Ketua (Sesuai Mockup Baru) -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- 1. Card Ketua Himpunan -->
                        <div class="rounded-3xl border border-slate-700/60 bg-[#111C38]/80 p-5 sm:p-7 shadow-xl hover:border-slate-600 transition flex flex-col sm:flex-row items-center sm:items-stretch gap-5 sm:gap-6 group">
                            <!-- Foto Ketua -->
                            <div class="w-36 sm:w-44 md:w-48 aspect-[3/4] rounded-2xl overflow-hidden shrink-0 shadow-lg border border-slate-700/50 bg-[#1a294e]">
                                <img
                                    src="/images/ketua-himpunan.png"
                                    alt="Foto Ketua Himpunan"
                                    class="w-full h-full object-cover object-top group-hover:scale-105 transition duration-300"
                                />
                            </div>

                            <!-- Info Teks Ketua -->
                            <div class="flex flex-col justify-center text-center sm:text-left flex-1">
                                <!-- Badge KETUA HIMPUNAN -->
                                <div>
                                    <span class="inline-block px-3.5 py-1 rounded-full bg-[#F95700] text-slate-950 font-black text-[11px] sm:text-xs tracking-wider uppercase shadow-xs">
                                        KETUA HIMPUNAN
                                    </span>
                                </div>

                                <!-- Nama Ketua -->
                                <h3 class="text-xl sm:text-2xl lg:text-3xl font-black text-white tracking-tight leading-tight mt-3">
                                    Achmad Rizqi<br class="hidden sm:inline" /> Adiyatma
                                </h3>

                                <!-- Subtitle / Organisasi -->
                                <p class="text-xs sm:text-sm text-slate-400 font-medium mt-2">
                                    Himpunan Mahasiswa Teknik Informatika
                                </p>
                            </div>
                        </div>

                        <!-- 2. Card Wakil Ketua Himpunan -->
                        <div class="rounded-3xl border border-slate-700/60 bg-[#111C38]/80 p-5 sm:p-7 shadow-xl hover:border-slate-600 transition flex flex-col sm:flex-row items-center sm:items-stretch gap-5 sm:gap-6 group">
                            <!-- Foto Wakil Ketua -->
                            <div class="w-36 sm:w-44 md:w-48 aspect-[3/4] rounded-2xl overflow-hidden shrink-0 shadow-lg border border-slate-700/50 bg-[#1a294e]">
                                <img
                                    src="/images/wakil-ketua-himpunan.png"
                                    alt="Foto Wakil Ketua"
                                    class="w-full h-full object-cover object-top group-hover:scale-105 transition duration-300"
                                />
                            </div>

                            <!-- Info Teks Wakil Ketua -->
                            <div class="flex flex-col justify-center text-center sm:text-left flex-1">
                                <!-- Badge WAKIL KETUA -->
                                <div>
                                    <span class="inline-block px-3.5 py-1 rounded-full bg-[#F95700] text-slate-950 font-black text-[11px] sm:text-xs tracking-wider uppercase shadow-xs">
                                        WAKIL KETUA
                                    </span>
                                </div>

                                <!-- Nama Wakil Ketua -->
                                <h3 class="text-xl sm:text-2xl lg:text-3xl font-black text-white tracking-tight leading-tight mt-3">
                                    Amanda Aulia<br class="hidden sm:inline" /> Pramudita
                                </h3>

                                <!-- Subtitle / Organisasi -->
                                <p class="text-xs sm:text-sm text-slate-400 font-medium mt-2">
                                    Himpunan Mahasiswa Teknik Informatika
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian VISI & MISI -->
                    <div class="mt-8 sm:mt-10 grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 items-start">
                        <!-- Kolom VISI (Kiri) -->
                        <div class="space-y-4">
                            <!-- Header Pill VISI -->
                            <div class="inline-flex items-center gap-3 px-6 py-2.5 rounded-full bg-[#132247] border border-blue-400/30 text-white shadow-md">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <span class="text-base sm:text-lg font-black tracking-[0.25em] uppercase">VISI</span>
                            </div>

                            <!-- Box Isi VISI -->
                            <div class="p-6 sm:p-8 rounded-3xl bg-[#111C38]/70 border border-slate-700/60 shadow-lg flex items-center justify-center min-h-[170px]">
                                <p class="text-center text-slate-100 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
                                    Mewujudkan HIMATIF sebagai wadah mahasiswa informatika yang aktif berkembang, produktif berkarya, dan mampu memberikan dampak melalui pemanfaatan teknologi serta budaya organisasi yang kolaboratif.
                                </p>
                            </div>
                        </div>

                        <!-- Kolom MISI (Kanan) -->
                        <div class="space-y-4">
                            <!-- Header Pill MISI -->
                            <div class="inline-flex items-center gap-3 px-6 py-2.5 rounded-full bg-[#132247] border border-blue-400/30 text-white shadow-md">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                                <span class="text-base sm:text-lg font-black tracking-[0.25em] uppercase">MISI</span>
                            </div>

                            <!-- 3 Butir Poin MISI -->
                            <div class="space-y-3">
                                <div class="px-5 sm:px-6 py-3.5 sm:py-4 rounded-2xl sm:rounded-full bg-[#111C38]/70 border border-slate-700/60 shadow-md text-center text-slate-100 text-xs sm:text-[13px] font-medium leading-relaxed">
                                    Membangun budaya belajar, diskusi, dan pengembangan skill yang konsisten bagi mahasiswa teknik informatika.
                                </div>

                                <div class="px-5 sm:px-6 py-3.5 sm:py-4 rounded-2xl sm:rounded-full bg-[#111C38]/70 border border-slate-700/60 shadow-md text-center text-slate-100 text-xs sm:text-[13px] font-medium leading-relaxed">
                                    Mendorong mahasiswa teknik informatika untuk menghasilkan karya dan berpartisipasi dalam kompetisi sebagai sarana pengembangan kompetensi serta kontribusi nyata HIMATIF.
                                </div>

                                <div class="px-5 sm:px-6 py-3.5 sm:py-4 rounded-2xl sm:rounded-full bg-[#111C38]/70 border border-slate-700/60 shadow-md text-center text-slate-100 text-xs sm:text-[13px] font-medium leading-relaxed">
                                    Mengembangkan organisasi yang aktif, disiplin, dan informatif melalui pemanfaatan teknologi digital guna mendukung penyebaran informasi, publikasi prestasi, serta penguatan branding HIMATIF.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. AKTIVITAS - Program Kerja & Informasi Terkini (Sesuai Mockup) -->
                <div id="agenda" class="space-y-6 pt-4">
                    <!-- Section Header -->
                    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3">
                        <div>
                            <span class="text-xs sm:text-sm font-black tracking-widest text-[#F95700] uppercase">
                                AKTIVITAS
                            </span>
                            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-1">
                                Program Kerja & Informasi Terkini
                            </h2>
                        </div>
                        <a
                            href="#agenda"
                            class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-[#F95700] hover:text-orange-700 transition group"
                        >
                            <span>Lihat Semua Agenda</span>
                            <span class="group-hover:translate-x-1 transition-transform">→</span>
                        </a>
                    </div>

                    <!-- 4 Cards Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div
                            v-for="item in activities"
                            :key="item.title"
                            class="bg-white rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-lg transition-all duration-300 overflow-hidden flex flex-col justify-between group"
                        >
                            <div>
                                <!-- Image Thumbnail -->
                                <div class="p-3 pb-0">
                                    <div class="relative overflow-hidden rounded-xl aspect-[16/10] bg-slate-100">
                                        <img
                                            :src="item.image"
                                            :alt="item.title"
                                            class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                        />
                                    </div>
                                </div>

                                <!-- Card Content -->
                                <div class="p-4 space-y-2">
                                    <div class="flex items-center gap-1.5 text-[11px] font-semibold text-slate-500">
                                        <span>📅</span>
                                        <span>{{ item.date }} • {{ item.location }}</span>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-900 group-hover:text-[#F95700] transition line-clamp-2 leading-snug">
                                        {{ item.title }}
                                    </h3>
                                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                        {{ item.desc }}
                                    </p>
                                </div>
                            </div>

                            <!-- Card Footer -->
                            <div class="px-4 pb-4 pt-1 flex items-center justify-between border-t border-slate-100">
                                <span
                                    class="text-xs font-bold"
                                    :class="item.status.includes('Selesai') || item.status.includes('Sisa') ? 'text-[#F95700]' : 'text-slate-600'"
                                >
                                    {{ item.status }}
                                </span>
                                <span class="text-slate-400 group-hover:text-[#F95700] group-hover:translate-x-1 transition text-sm">
                                    →
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 5. FITUR YANG TERSEDIA (Layanan HIMATIF - Sesuai Mockup) -->
                <div id="layanan" class="pt-6 space-y-6">
                    <div>
                        <span class="text-xs sm:text-sm font-black tracking-widest text-[#F95700] uppercase">
                            FITUR YANG TERSEDIA
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-1">
                            Layanan HIMATIF
                        </h2>
                    </div>

                    <!-- 3 Kolom Grid untuk Layanan HIMATIF -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div
                            v-for="service in himatifServices"
                            :key="service.title"
                            class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-xs hover:shadow-xl hover:border-orange-300 transition-all duration-300 flex flex-col justify-between group"
                        >
                            <div>
                                <!-- Kotak Ikon Peach/Oranye -->
                                <div class="w-12 h-12 rounded-2xl bg-orange-100/70 border border-orange-200/50 flex items-center justify-center mb-5 group-hover:scale-110 transition duration-300">
                                    <!-- Icon 1: Inventaris (Device / Console) -->
                                    <svg
                                        v-if="service.iconType === 'inventory'"
                                        class="w-6 h-6 text-[#F95700]"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <rect x="3" y="6" width="18" height="12" rx="2" stroke-width="2"/>
                                        <circle cx="15.5" cy="12" r="2" stroke-width="2"/>
                                        <line x1="9" y1="9" x2="9" y2="15" stroke-width="2"/>
                                    </svg>

                                    <!-- Icon 2: Official Merchandise (Shop) -->
                                    <svg
                                        v-else-if="service.iconType === 'shop'"
                                        class="w-6 h-6 text-[#F95700]"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9l1-5h16l1 5M3 9a3 3 0 006 0 3 3 0 006 0 3 3 0 006 0M3 9v11a1 1 0 001 1h16a1 1 0 001-1V9m-7 11v-6h-4v6"/>
                                    </svg>

                                    <!-- Icon 3: Prestasi & Delegasi (Trophy) -->
                                    <svg
                                        v-else-if="service.iconType === 'trophy'"
                                        class="w-6 h-6 text-[#F95700]"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3h14v3a7 7 0 01-14 0V3zM5 5H2v2a4 4 0 004 4h1M19 5h3v2a4 4 0 01-4 4h-1M12 14v4m-4 3h8"/>
                                    </svg>

                                    <!-- Icon 4: Arsip Akademik (Folder with Star) -->
                                    <svg
                                        v-else-if="service.iconType === 'academic'"
                                        class="w-6 h-6 text-[#F95700]"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 11l.9 1.8 2 .3-1.5 1.4.3 2-1.7-.9-1.7.9.3-2-1.5-1.4 2-.3.9-1.8z"/>
                                    </svg>
                                </div>

                                <!-- Kategori Tag Kecil Oranye -->
                                <span class="text-[11px] font-bold tracking-wider text-[#F95700] uppercase block">
                                    {{ service.category }}
                                </span>

                                <!-- Judul Layanan -->
                                <h3 class="text-base sm:text-lg font-black text-slate-900 group-hover:text-[#F95700] transition mt-1.5">
                                    {{ service.title }}
                                </h3>

                                <!-- Deskripsi Layanan -->
                                <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
                                    {{ service.desc }}
                                </p>
                            </div>

                            <!-- Tombol / Tautan Aksi Bawah -->
                            <a
                                :href="service.href"
                                class="mt-6 inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-slate-900 group-hover:text-[#F95700] transition select-none"
                            >
                                <span>{{ service.btnText }}</span>
                                <span class="group-hover:translate-x-1 transition-transform">→</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 6. Galeri Dokumentasi (Bangga Menjadi Bagian Dari Kami - Auto Scroll 2 Detik & Manual Scroll) -->
                <div
                    class="rounded-3xl bg-[#090F24] border border-slate-800 p-6 sm:p-10 shadow-2xl overflow-hidden relative"
                    @mouseenter="isHovering = true"
                    @mouseleave="isHovering = false"
                >
                    <!-- Header Judul & Tombol Kontrol Kanan Kiri -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6 sm:mb-8 text-center sm:text-left">
                        <div>
                            <span class="text-xs font-bold tracking-widest text-[#F95700] uppercase block mb-1">
                                DOKUMENTASI HIMPUNAN
                            </span>
                            <h2 class="text-xl sm:text-2xl md:text-3xl font-black text-white tracking-[0.15em] sm:tracking-[0.25em] uppercase">
                                BANGGA MENJADI BAGIAN <span class="text-[#F95700]">DARI KAMI</span>
                            </h2>
                        </div>

                        <!-- Tombol Navigasi Manual Kiri - Kanan -->
                        <div class="flex items-center gap-2.5">
                            <button
                                type="button"
                                @click="scrollPrev"
                                class="w-10 h-10 rounded-full bg-slate-800/90 hover:bg-[#F95700] text-white flex items-center justify-center border border-slate-700 hover:border-orange-500 shadow-md transition transform active:scale-95"
                                title="Foto Sebelumnya"
                            >
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <button
                                type="button"
                                @click="scrollNext"
                                class="w-10 h-10 rounded-full bg-slate-800/90 hover:bg-[#F95700] text-white flex items-center justify-center border border-slate-700 hover:border-orange-500 shadow-md transition transform active:scale-95"
                                title="Foto Selanjutnya"
                            >
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Carousel Container (Scroll Kanan Kiri & Auto Scroll - Scrollbar Tersembunyi) -->
                    <div
                        ref="galleryContainer"
                        class="flex gap-4 sm:gap-6 overflow-x-auto scroll-smooth snap-x snap-mandatory py-2 pb-4 select-none cursor-grab active:cursor-grabbing no-scrollbar"
                        style="scrollbar-width: none; -ms-overflow-style: none;"
                    >
                        <div
                            v-for="(photo, idx) in galleryPhotos"
                            :key="idx"
                            class="snap-start shrink-0 w-[280px] sm:w-[360px] md:w-[420px] aspect-[16/10] relative rounded-2xl overflow-hidden border border-slate-700/60 shadow-lg group bg-slate-900"
                        >
                            <img
                                :src="photo.src"
                                :alt="photo.alt"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                loading="lazy"
                            />
                            <!-- Gradient overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent"></div>
                            
                            <!-- Caption Teks di Bawah Foto -->
                            <div class="absolute bottom-0 inset-x-0 p-4 transform translate-y-1 group-hover:translate-y-0 transition">
                                <span class="inline-block px-2.5 py-0.5 rounded-md bg-[#F95700]/90 text-white text-[10px] font-bold uppercase tracking-wider mb-1 shadow-xs">
                                    Dokumentasi
                                </span>
                                <h4 class="text-xs sm:text-sm font-bold text-white tracking-tight line-clamp-1">
                                    {{ photo.title }}
                                </h4>
                            </div>
                        </div>
                    </div>

                    <!-- Dot Indicators -->
                    <div class="flex items-center justify-center gap-2 mt-4 sm:mt-6">
                        <button
                            v-for="(_, idx) in galleryPhotos"
                            :key="'dot-' + idx"
                            @click="scrollToSlide(idx)"
                            class="h-2 rounded-full transition-all duration-300"
                            :class="currentSlideIndex === idx ? 'w-8 bg-[#F95700]' : 'w-2 bg-slate-700 hover:bg-slate-500'"
                            :title="'Slide ' + (idx + 1)"
                        />
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Sembunyikan scrollbar visual namun tetap aktif berfungsi scroll / swipe */
.no-scrollbar::-webkit-scrollbar {
    display: none !important;
    width: 0 !important;
    height: 0 !important;
}
.no-scrollbar {
    -ms-overflow-style: none !important;
    scrollbar-width: none !important;
}
</style>
