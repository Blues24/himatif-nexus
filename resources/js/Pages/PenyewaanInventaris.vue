<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

// State Kategori, Filter Status & Pencarian
const activeCategory = ref('Semua');
const activeStatusFilter = ref('semua'); // 'semua', 'tersedia', 'dipinjam'
const searchQuery = ref('');

const categories = [
    'Semua',
    'Audio & Visual',
    'Perlengkapan Event',
    'Kabel & Kelistrikan',
    'Dekorasi & Logistik',
];

// Data Master Inventaris HIMATIF (8 Item Placeholder Grid 4 Kolom)
const masterInventory = [
    {
        id: 1,
        code: 'INV-AV-001',
        title: 'Nama Peralatan Audio Visual (Placeholder 1)',
        category: 'Audio & Visual',
        status: 'tersedia',
        statusLabel: 'Tersedia',
        statusBadgeClass: 'bg-emerald-600 text-white',
        condition: 'Sangat Baik (100%)',
        stock: '3 Unit Tersedia',
        price: 'Bebas Ormawa / Tarif Khusus',
        image: null,
        placeholderIcon: '🔊',
        placeholderLabel: 'DOKUMENTASI ALAT',
        placeholderSub: 'Inventaris Audio Visual',
        desc: 'Deskripsi spesifikasi teknis, kelengkapan kabel konektor, daya output, dan panduan pengoperasian unit audio visual inventaris HIMATIF.',
        specs: ['1x Unit Utama', '2x Kabel Power AC', '1x Hardcase Pelindung'],
        pic: 'Divisi Logistik & Inventaris (0812-3456-7890)',
    },
    {
        id: 2,
        code: 'INV-EV-002',
        title: 'Nama Perlengkapan Event & Acara (Placeholder 2)',
        category: 'Perlengkapan Event',
        status: 'tersedia',
        statusLabel: 'Tersedia',
        statusBadgeClass: 'bg-emerald-600 text-white',
        condition: 'Baik (95%)',
        stock: '2 Unit Tersedia',
        price: 'Bebas Ormawa / Tarif Khusus',
        image: null,
        placeholderIcon: '📽️',
        placeholderLabel: 'DOKUMENTASI ALAT',
        placeholderSub: 'Inventaris Perlengkapan',
        desc: 'Deskripsi spesifikasi teknis, kelengkapan unit display atau proyektor presentasi, port HDMI, dan panduan penggunaan.',
        specs: ['1x Unit Display / Proyektor', '1x Kabel HDMI 10m', '1x Tripod Standar'],
        pic: 'Divisi Logistik & Inventaris (0812-3456-7890)',
    },
    {
        id: 3,
        code: 'INV-EL-003',
        title: 'Nama Kabel & Instalasi Kelistrikan (Placeholder 3)',
        category: 'Kabel & Kelistrikan',
        status: 'tersedia',
        statusLabel: 'Tersedia',
        statusBadgeClass: 'bg-emerald-600 text-white',
        condition: 'Baik (100%)',
        stock: '5 Unit Tersedia',
        price: 'Bebas Ormawa / Fasilitas Kampus',
        image: null,
        placeholderIcon: '🔌',
        placeholderLabel: 'DOKUMENTASI ALAT',
        placeholderSub: 'Inventaris Kelistrikan',
        desc: 'Deskripsi spesifikasi rol kabel tembaga kualitas tinggi dengan proteksi arus beban lebih untuk kebutuhan pasokan daya acara kepanitiaan.',
        specs: ['Panjang Kabel 25 Meter', '4 Lubang Stop Kontak', 'Pengaman Arus (Fuse)'],
        pic: 'Divisi Logistik & Inventaris (0812-3456-7890)',
    },
    {
        id: 4,
        code: 'INV-AV-004',
        title: 'Nama Perangkat Multimedia & Kamera (Placeholder 4)',
        category: 'Audio & Visual',
        status: 'dipinjam',
        statusLabel: 'Sedang Dipinjam',
        statusBadgeClass: 'bg-amber-500 text-white',
        condition: 'Baik (Terawat)',
        stock: '1 Unit (Reservasi)',
        price: 'Khusus Dokumentasi Ormawa',
        image: null,
        placeholderIcon: '📷',
        placeholderLabel: 'DOKUMENTASI ALAT',
        placeholderSub: 'Inventaris Multimedia',
        desc: 'Deskripsi spesifikasi kamera dokumentasi resolusi tinggi dan aksesoris memori untuk kebutuhan peliputan kegiatan kemahasiswaan.',
        specs: ['1x Body Kamera', '1x Lensa Standar', '2x Baterai & Charger Pack'],
        pic: 'Departemen Komunikasi & Informasi (INFOKOM)',
    },
    {
        id: 5,
        code: 'INV-LG-005',
        title: 'Nama Perlengkapan Panggung & Logistik (Placeholder 5)',
        category: 'Dekorasi & Logistik',
        status: 'tersedia',
        statusLabel: 'Tersedia',
        statusBadgeClass: 'bg-emerald-600 text-white',
        condition: 'Baik (90%)',
        stock: '4 Unit Tersedia',
        price: 'Bebas Ormawa / Tarif Khusus',
        image: null,
        placeholderIcon: '🎪',
        placeholderLabel: 'DOKUMENTASI ALAT',
        placeholderSub: 'Inventaris Logistik',
        desc: 'Deskripsi perlengkapan tenda lipat, partisi pameran, atau backdrop portabel yang kokoh untuk penunjang stand kegiatan mahasiswa.',
        specs: ['Rangka Baja Ringan', 'Kain Parasut Tahan Cuaca', 'Tas Jinjing Logistik'],
        pic: 'Divisi Logistik & Inventaris (0812-3456-7890)',
    },
    {
        id: 6,
        code: 'INV-AV-006',
        title: 'Nama Perangkat Presentasi & Komputasi (Placeholder 6)',
        category: 'Audio & Visual',
        status: 'tersedia',
        statusLabel: 'Tersedia',
        statusBadgeClass: 'bg-emerald-600 text-white',
        condition: 'Sangat Baik (100%)',
        stock: '2 Unit Tersedia',
        price: 'Bebas Ormawa / Fasilitas Kampus',
        image: null,
        placeholderIcon: '💻',
        placeholderLabel: 'DOKUMENTASI ALAT',
        placeholderSub: 'Inventaris Komputasi',
        desc: 'Deskripsi unit pointer wireless, switch hub LAN, serta adaptor multi-display untuk menunjang kelancaran seminar dan presentasi.',
        specs: ['1x Wireless Presenter Laser', '1x USB Dongle Receiver', '1x Pouch Pelindung'],
        pic: 'Divisi Logistik & Inventaris (0812-3456-7890)',
    },
    {
        id: 7,
        code: 'INV-EV-007',
        title: 'Nama Alat Komunikasi HT (Placeholder 7)',
        category: 'Perlengkapan Event',
        status: 'tersedia',
        statusLabel: 'Tersedia',
        statusBadgeClass: 'bg-emerald-600 text-white',
        condition: 'Baik (95%)',
        stock: '6 Unit Tersedia',
        price: 'Bebas Ormawa / Tarif Khusus',
        image: null,
        placeholderIcon: '📻',
        placeholderLabel: 'DOKUMENTASI ALAT',
        placeholderSub: 'Inventaris Komunikasi',
        desc: 'Deskripsi handy talkie dual band jangkauan luas untuk koordinasi cepat antar seksi panitia acara di lapangan atau auditorium.',
        specs: ['Frekuensi VHF/UHF Dual Band', 'Headset Earpiece PTT', 'Desktop Charger Dock'],
        pic: 'Divisi Logistik & Inventaris (0812-3456-7890)',
    },
    {
        id: 8,
        code: 'INV-LG-008',
        title: 'Nama Perlengkapan Dekorasi Display (Placeholder 8)',
        category: 'Dekorasi & Logistik',
        status: 'tersedia',
        statusLabel: 'Tersedia',
        statusBadgeClass: 'bg-emerald-600 text-white',
        condition: 'Sangat Baik (100%)',
        stock: '2 Unit Tersedia',
        price: 'Bebas Ormawa / Fasilitas Kampus',
        image: null,
        placeholderIcon: '📦',
        placeholderLabel: 'DOKUMENTASI ALAT',
        placeholderSub: 'Inventaris Display',
        desc: 'Deskripsi standing banner display, tiang antrean pembatas, atau display signage informasi kegiatan ormawa.',
        specs: ['Tinggi Dapat Diatur (Adjustable)', 'Material Aluminium Anodize', 'Mudah Dibongkar Pasang'],
        pic: 'Divisi Logistik & Inventaris (0812-3456-7890)',
    },
];

// Filter Inventaris
const filteredInventory = computed(() => {
    let result = masterInventory;

    // Filter Kategori
    if (activeCategory.value !== 'Semua') {
        result = result.filter(item => item.category === activeCategory.value);
    }

    // Filter Status Ketersediaan
    if (activeStatusFilter.value !== 'semua') {
        result = result.filter(item => item.status === activeStatusFilter.value);
    }

    // Filter Pencarian
    if (searchQuery.value.trim() !== '') {
        const q = searchQuery.value.toLowerCase();
        result = result.filter(
            item =>
                item.title.toLowerCase().includes(q) ||
                item.code.toLowerCase().includes(q) ||
                item.desc.toLowerCase().includes(q) ||
                item.category.toLowerCase().includes(q)
        );
    }

    return result;
});

// Hitung Jumlah
const countAll = computed(() => masterInventory.length);
const countAvailable = computed(() => masterInventory.filter(item => item.status === 'tersedia').length);
const countLoaned = computed(() => masterInventory.filter(item => item.status === 'dipinjam').length);

// State Modal Detail & Pengajuan Peminjaman
const selectedItem = ref(null);
const isModalOpen = ref(false);
const showSuccessNotification = ref(false);

const rentalForm = ref({
    namaPeminjam: '',
    nim: '',
    organisasi: '',
    keperluan: '',
    tanggalPinjam: '',
    tanggalKembali: '',
    telepon: '',
});

const openDetailModal = (item) => {
    selectedItem.value = item;
    isModalOpen.value = true;
    showSuccessNotification.value = false;
};

const closeModal = () => {
    isModalOpen.value = false;
    selectedItem.value = null;
    showSuccessNotification.value = false;
};

const submitRental = () => {
    // Simulasi Pengajuan Sukses (Placeholder)
    showSuccessNotification.value = true;
    setTimeout(() => {
        rentalForm.value = {
            namaPeminjam: '',
            nim: '',
            organisasi: '',
            keperluan: '',
            tanggalPinjam: '',
            tanggalKembali: '',
            telepon: '',
        };
    }, 1500);
};
</script>

<template>
    <Head title="Penyewaan Inventaris & Peralatan - HIMATIF POLINDRA" />

    <AuthenticatedLayout>
        <div class="bg-gradient-to-b from-white via-slate-50 to-slate-100 min-h-screen pb-20">
            <!-- Container Utama -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8 space-y-10 sm:space-y-12">
                
                <!-- 1. Featured Dark Banner Hero (Sesuai Desain Konsisten HIMATIF NEXUS) -->
                <div
                    class="relative overflow-hidden rounded-3xl sm:rounded-[36px] p-6 sm:p-10 lg:p-12 text-white shadow-2xl shadow-slate-900/40 border border-slate-800"
                    style="background-color: #13151D;"
                >
                    <div class="flex flex-col lg:flex-row items-center justify-between gap-8 lg:gap-12">
                        <!-- Kolom Kiri: Teks & Aksi -->
                        <div class="flex-1 w-full space-y-5 text-left">
                            <!-- Pill Badge Layanan -->
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 border border-white/15 shadow-inner">
                                <span class="text-xs">📦</span>
                                <span class="text-[11px] sm:text-xs font-black text-[#F95700] tracking-wider uppercase">
                                    LAYANAN PENYEWAAN INVENTARIS & ALAT HIMATIF
                                </span>
                            </div>

                            <!-- Judul Besar -->
                            <h1 class="text-3xl sm:text-4xl lg:text-[42px] font-black text-white tracking-tight leading-[1.18]">
                                Fasilitas Peminjaman Peralatan & Aset Organisasi<br class="hidden sm:inline" />
                            </h1>

                            <!-- Deskripsi Singkat -->
                            <p class="text-xs sm:text-sm text-slate-300 font-normal leading-relaxed max-w-xl">
                                Layanan peminjaman dan penyewaan perlengkapan operasional HIMATIF POLINDRA untuk mendukung kegiatan kepanitiaan ormawa, praktikum, dan agenda kemahasiswaan.
                            </p>

                            <!-- Tombol CTA & Status Layanan -->
                            <div class="flex flex-wrap items-center gap-3 pt-2">
                                <a
                                    href="#katalog-alat"
                                    class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-xs sm:text-sm text-white bg-[#F95700] hover:bg-orange-600 shadow-md shadow-orange-500/20 transition transform active:scale-95 select-none"
                                >
                                    <span>Lihat Katalog Alat</span>
                                    <span>↓</span>
                                </a>

                                <div class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-white/10 border border-white/15 text-xs sm:text-sm text-slate-200 font-medium">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <span>Status Layanan: <strong>Buka untuk Pengajuan</strong></span>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan: Card Ringkasan Prosedur Peminjaman -->
                        <div class="w-full lg:w-[380px] shrink-0">
                            <div class="bg-[#212430]/90 backdrop-blur-md border border-slate-700/60 rounded-2xl p-5 sm:p-6 shadow-xl text-left space-y-3">
                                <div>
                                    <span class="text-[11px] font-black tracking-widest text-[#F95700] uppercase block">
                                        ALUR SINGKAT PEMINJAMAN
                                    </span>
                                    <h3 class="text-lg font-black text-white mt-0.5">
                                        PROSEDUR SEWA ALAT
                                    </h3>
                                </div>

                                <!-- Langkah Prosedur -->
                                <div class="divide-y divide-white/10 text-xs sm:text-[13px] pt-1">
                                    <div class="py-2 flex items-start gap-2.5 text-slate-200">
                                        <span class="w-5 h-5 rounded-full bg-orange-500/20 text-orange-400 font-bold flex items-center justify-center shrink-0 text-[11px]">1</span>
                                        <span class="font-medium">Pilih alat & cek ketersediaan stok di katalog</span>
                                    </div>
                                    <div class="py-2 flex items-start gap-2.5 text-slate-200">
                                        <span class="w-5 h-5 rounded-full bg-orange-500/20 text-orange-400 font-bold flex items-center justify-center shrink-0 text-[11px]">2</span>
                                        <span class="font-medium">Isi formulir pengajuan minimal H-3 sebelum acara</span>
                                    </div>
                                    <div class="py-2 flex items-start gap-2.5 text-slate-200">
                                        <span class="w-5 h-5 rounded-full bg-orange-500/20 text-orange-400 font-bold flex items-center justify-center shrink-0 text-[11px]">3</span>
                                        <span class="font-medium">Konfirmasi & ambil alat di Sekretariat HIMATIF</span>
                                    </div>
                                </div>

                                <!-- Info Sekretariat -->
                                <div class="pt-2">
                                    <div class="p-2.5 rounded-xl bg-white/5 border border-white/10 text-[11px] text-slate-300 flex items-center justify-between">
                                        <span>📍 Sekre HIMATIF POLINDRA</span>
                                        <span class="text-orange-400 font-bold">08:00 - 16:00 WIB</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Section Katalog Inventaris -->
                <div id="katalog-alat" class="space-y-6 pt-2">
                    
                    <!-- Header Section & Search Bar -->
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                        <div>
                            <span class="text-xs font-black tracking-widest text-[#F95700] uppercase block mb-1">
                                KATALOG PERALATAN & ASET
                            </span>
                            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                                Daftar Inventaris HIMATIF
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-xl">
                                Temukan berbagai perlengkapan audio, proyektor, kelistrikan, hingga logistik panggung yang tersedia untuk dipinjam.
                            </p>
                        </div>

                        <!-- Kotak Pencarian -->
                        <div class="w-full md:w-80 relative">
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Cari nama alat, kode, kategori..."
                                class="w-full pl-10 pr-4 py-2.5 rounded-full border border-slate-200 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 shadow-xs transition"
                            />
                            <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Filter Kategori & Status Ketersediaan -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <!-- Kategori Pills -->
                        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none select-none">
                            <button
                                v-for="cat in categories"
                                :key="cat"
                                type="button"
                                @click="activeCategory = cat"
                                class="shrink-0 px-4 py-2 rounded-full text-xs font-bold transition-all duration-200"
                                :class="activeCategory === cat ? 'bg-slate-900 text-white shadow-sm' : 'bg-white hover:bg-slate-100 text-slate-700 border border-slate-200/80'"
                            >
                                {{ cat }}
                            </button>
                        </div>

                        <!-- Filter Status Pills: Semua, Tersedia, Dipinjam -->
                        <div class="flex items-center gap-1.5 p-1 bg-slate-100/90 rounded-2xl w-fit border border-slate-200/80 self-start sm:self-auto">
                            <button
                                type="button"
                                @click="activeStatusFilter = 'semua'"
                                class="px-3 py-1.5 rounded-xl text-xs font-bold transition"
                                :class="activeStatusFilter === 'semua' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                            >
                                Semua ({{ countAll }})
                            </button>
                            <button
                                type="button"
                                @click="activeStatusFilter = 'tersedia'"
                                class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1"
                                :class="activeStatusFilter === 'tersedia' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                            >
                                <span>Tersedia ({{ countAvailable }})</span>
                            </button>
                            <button
                                type="button"
                                @click="activeStatusFilter = 'dipinjam'"
                                class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1"
                                :class="activeStatusFilter === 'dipinjam' ? 'bg-amber-500 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                            >
                                <span>Dipinjam ({{ countLoaned }})</span>
                            </button>
                        </div>
                    </div>

                    <!-- 3. Grid Kartu Inventaris (4 Kolom Sesuai Desain Konsisten) -->
                    <div v-if="filteredInventory.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6">
                        <div
                            v-for="item in filteredInventory"
                            :key="item.id"
                            class="bg-white rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-xl hover:border-orange-300 transition-all duration-300 overflow-hidden flex flex-col justify-between group"
                        >
                            <div>
                                <!-- Thumbnail Gambar Placeholder dengan Badge Status & Kategori -->
                                <div class="p-3 pb-0">
                                    <div class="relative overflow-hidden rounded-xl aspect-[16/10] bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center">
                                        <img
                                            v-if="item.image"
                                            :src="item.image"
                                            :alt="item.title"
                                            class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                            loading="lazy"
                                        />
                                        <!-- Placeholder Gambar Elegan -->
                                        <div v-else class="flex flex-col items-center justify-center text-center p-4 text-slate-300">
                                            <span class="text-3xl mb-1">{{ item.placeholderIcon || '📦' }}</span>
                                            <span class="text-[11px] font-black tracking-wider uppercase text-slate-200">{{ item.placeholderLabel || 'DOKUMENTASI ALAT' }}</span>
                                            <span class="text-[10px] text-slate-400 mt-0.5 truncate max-w-[190px]">{{ item.placeholderSub || item.code }}</span>
                                        </div>

                                        <!-- Badge Status Ketersediaan (Kiri Atas) -->
                                        <div class="absolute top-2.5 left-2.5">
                                            <span
                                                class="inline-block px-2.5 py-1 rounded-full text-[10px] sm:text-[11px] font-black uppercase tracking-wider shadow-sm"
                                                :class="item.statusBadgeClass"
                                            >
                                                {{ item.statusLabel }}
                                            </span>
                                        </div>

                                        <!-- Badge Kategori (Kanan Atas) -->
                                        <div class="absolute top-2.5 right-2.5">
                                            <span class="inline-block px-2.5 py-1 rounded-full text-[10px] sm:text-[11px] font-bold bg-slate-900/80 backdrop-blur-xs text-white border border-white/20">
                                                {{ item.category }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Konten Teks Kartu -->
                                <div class="p-4 sm:p-5 space-y-2 text-left">
                                    <!-- Kode & Kondisi -->
                                    <div class="flex items-center justify-between text-[11px] font-semibold text-slate-500">
                                        <span class="font-mono text-orange-600 font-bold bg-orange-50 px-2 py-0.5 rounded-md border border-orange-100">
                                            {{ item.code }}
                                        </span>
                                        <span class="text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md font-medium">
                                            ✓ {{ item.condition }}
                                        </span>
                                    </div>

                                    <!-- Judul Alat (Placeholder) -->
                                    <h3 class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-[#F95700] transition line-clamp-2 leading-snug">
                                        {{ item.title }}
                                    </h3>

                                    <!-- Deskripsi Singkat -->
                                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                        {{ item.desc }}
                                    </p>

                                    <!-- Info Stok & Tarif -->
                                    <div class="pt-1 flex items-center justify-between text-[11px] font-semibold text-slate-600 border-t border-slate-100">
                                        <span class="text-slate-500">Stok: <strong>{{ item.stock }}</strong></span>
                                        <span class="text-orange-600 font-bold">{{ item.price }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Kartu -->
                            <div class="px-4 sm:px-5 pb-4 sm:pb-5 pt-1 flex items-center justify-between border-t border-slate-100">
                                <button
                                    type="button"
                                    @click="openDetailModal(item)"
                                    class="text-xs font-bold text-slate-900 group-hover:text-[#F95700] transition"
                                >
                                    {{ item.status === 'tersedia' ? 'Ajukan Peminjaman' : 'Cek Detail Alat' }}
                                </button>
                                <span class="text-slate-400 group-hover:text-[#F95700] group-hover:translate-x-1 transition text-sm">
                                    →
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- State Kosong jika Tidak Ada Alat yang Cocok -->
                    <div v-else class="text-center py-16 bg-white rounded-3xl border border-slate-200/80 p-8">
                        <div class="text-4xl mb-3">🔍</div>
                        <h3 class="text-lg font-bold text-slate-800">Tidak ada peralatan yang sesuai</h3>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-md mx-auto">
                            Coba ubah filter kategori, status ketersediaan, atau gunakan kata kunci pencarian yang lain.
                        </p>
                        <button
                            type="button"
                            @click="activeCategory = 'Semua'; activeStatusFilter = 'semua'; searchQuery = ''"
                            class="mt-4 px-4 py-2 rounded-xl bg-orange-500 text-white font-bold text-xs hover:bg-orange-600 transition"
                        >
                            Reset Filter
                        </button>
                    </div>

                </div>

                <!-- 4. Ketentuan & SOP Peminjaman Inventaris (Informasi Edukatif) -->
                <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-10 shadow-xs space-y-6 text-left">
                    <div>
                        <span class="text-xs font-black tracking-widest text-[#F95700] uppercase block mb-1">
                            PANDUAN & KETENTUAN
                        </span>
                        <h3 class="text-xl sm:text-2xl font-black text-slate-900">
                            Syarat & Ketentuan Peminjaman Inventaris
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 pt-2">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/60 space-y-2">
                            <span class="text-2xl">⏳</span>
                            <h4 class="text-sm font-bold text-slate-900">Pengajuan Minimal H-3</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Pengajuan jadwal peminjaman dilakukan paling lambat 3 hari sebelum waktu kegiatan dimulai.
                            </p>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/60 space-y-2">
                            <span class="text-2xl">🪪</span>
                            <h4 class="text-sm font-bold text-slate-900">Jaminan Identitas / KTM</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Peminjam wajib melampirkan kartu tanda mahasiswa (KTM) atau surat izin kegiatan resmi ormawa.
                            </p>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/60 space-y-2">
                            <span class="text-2xl">🛡️</span>
                            <h4 class="text-sm font-bold text-slate-900">Tanggung Jawab Kondisi</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Peralatan harus dikembalikan dalam kondisi bersih, utuh, dan berfungsi normal seperti saat serah terima.
                            </p>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/60 space-y-2">
                            <span class="text-2xl">🔄</span>
                            <h4 class="text-sm font-bold text-slate-900">Pengembalian Tepat Waktu</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Pengembalian dilakukan sesuai tenggat waktu yang disepakati untuk kenyamanan peminjam berikutnya.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- MODAL DETAIL & FORMULIR PEMINJAMAN ALAT -->
        <div
            v-if="isModalOpen && selectedItem"
            class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-4"
            @click.self="closeModal"
        >
            <div class="bg-white rounded-3xl max-w-xl w-full overflow-hidden shadow-2xl border border-slate-200 transform transition-all animate-in fade-in zoom-in-95 duration-200 max-h-[90vh] flex flex-col">
                <!-- Header Gambar / Placeholder Modal -->
                <div class="relative aspect-[16/9] w-full bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center shrink-0">
                    <div class="flex flex-col items-center justify-center text-center p-6 text-slate-300">
                        <span class="text-5xl mb-2">{{ selectedItem.placeholderIcon || '📦' }}</span>
                        <span class="text-sm font-black tracking-wider uppercase text-white">{{ selectedItem.placeholderLabel || 'DOKUMENTASI ALAT' }}</span>
                        <span class="text-xs text-slate-400 mt-1 max-w-sm">{{ selectedItem.placeholderSub || selectedItem.code }}</span>
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
                            :class="selectedItem.statusBadgeClass"
                        >
                            {{ selectedItem.statusLabel }}
                        </span>
                    </div>
                </div>

                <!-- Konten Modal (Scrollable) -->
                <div class="p-6 sm:p-8 space-y-5 text-left overflow-y-auto">
                    <!-- Judul & Kode -->
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold font-mono px-2 py-0.5 rounded-md bg-orange-100 text-orange-700">
                                {{ selectedItem.code }}
                            </span>
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">
                                {{ selectedItem.category }}
                            </span>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-black text-slate-900 mt-1 leading-snug">
                            {{ selectedItem.title }}
                        </h3>
                    </div>

                    <!-- Deskripsi & Kelengkapan -->
                    <div class="space-y-3">
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            {{ selectedItem.desc }}
                        </p>

                        <!-- Kelengkapan Unit -->
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1.5">
                            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wide">
                                📋 Kelengkapan Paket Unit:
                            </h4>
                            <ul class="text-xs text-slate-600 space-y-1 list-disc list-inside">
                                <li v-for="(spec, idx) in selectedItem.specs" :key="idx">{{ spec }}</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Notifikasi Pengajuan Sukses -->
                    <div
                        v-if="showSuccessNotification"
                        class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm space-y-1"
                    >
                        <div class="font-bold flex items-center gap-1.5">
                            <span>✓</span>
                            <span>Pengajuan Peminjaman Berhasil Dikirim!</span>
                        </div>
                        <p class="text-emerald-700 text-xs">
                            Formulir pengajuan peminjaman alat (demo placeholder) telah berhasil dicatat. Silakan hubungi divisi inventaris untuk konfirmasi pengambilan unit.
                        </p>
                    </div>

                    <!-- Formulir Pengajuan Cepat Peminjaman -->
                    <form @submit.prevent="submitRental" class="space-y-3 pt-2 border-t border-slate-200">
                        <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">
                            Formulir Pengajuan Peminjaman (Demo)
                        </h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="block text-[11px] font-bold text-slate-700">Nama Peminjam / PIC*</label>
                                <input
                                    v-model="rentalForm.namaPeminjam"
                                    type="text"
                                    required
                                    placeholder="Contoh: Ahmad Fauzi"
                                    class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500"
                                />
                            </div>
                            <div class="space-y-1">
                                <label class="block text-[11px] font-bold text-slate-700">Organisasi / Kelas*</label>
                                <input
                                    v-model="rentalForm.organisasi"
                                    type="text"
                                    required
                                    placeholder="Contoh: Panitia IT Cast / TI-2A"
                                    class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500"
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="block text-[11px] font-bold text-slate-700">Tanggal Pinjam*</label>
                                <input
                                    v-model="rentalForm.tanggalPinjam"
                                    type="date"
                                    required
                                    class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500"
                                />
                            </div>
                            <div class="space-y-1">
                                <label class="block text-[11px] font-bold text-slate-700">Tanggal Kembali*</label>
                                <input
                                    v-model="rentalForm.tanggalKembali"
                                    type="date"
                                    required
                                    class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500"
                                />
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-[11px] font-bold text-slate-700">Keperluan Penggunaan*</label>
                            <input
                                v-model="rentalForm.keperluan"
                                type="text"
                                required
                                placeholder="Contoh: Kebutuhan sound system acara seminar"
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500"
                            />
                        </div>

                        <!-- Tombol Aksi Modal -->
                        <div class="pt-3 flex items-center justify-end gap-2">
                            <button
                                type="button"
                                @click="closeModal"
                                class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 font-bold text-xs transition"
                            >
                                Tutup
                            </button>
                            <button
                                type="submit"
                                class="px-5 py-2.5 rounded-xl bg-[#F95700] hover:bg-orange-600 text-white font-bold text-xs shadow-md shadow-orange-500/20 transition active:scale-95"
                            >
                                Kirim Pengajuan Sewa
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
