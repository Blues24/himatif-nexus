<script setup>
import { Link } from '@inertiajs/vue3';

const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};
</script>

<template>
    <footer class="bg-slate-950 text-slate-300 border-t border-slate-800/80 relative overflow-hidden select-none">
        <!-- Subtle Background Glow -->
        <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-[600px] h-32 bg-orange-500/10 blur-3xl pointer-events-none rounded-full"></div>

        <!-- Main Footer Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 sm:pt-16 pb-12 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-8">
                
                <!-- Kolom 1: Profil Organisasi & Dua Logo (POLINDRA & HIMATIF) -->
                <div class="lg:col-span-4 space-y-5 text-left">
                    <!-- Logo POLINDRA & HIMATIF Side-by-Side dengan Container Rapi -->
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2.5 bg-white p-2 sm:p-2.5 rounded-2xl shadow-sm border border-white/40">
                            <!-- Logo Politeknik Negeri Indramayu -->
                            <img
                                src="/images/logo-polindra.png"
                                alt="Logo POLINDRA"
                                class="h-10 sm:h-11 w-auto object-contain drop-shadow-2xs"
                                loading="lazy"
                            />
                            <!-- Garis Pembatas -->
                            <div class="h-7 w-[1px] bg-slate-200"></div>
                            <!-- Logo HIMATIF POLINDRA -->
                            <img
                                src="/images/logo-himatif.png"
                                alt="Logo HIMATIF"
                                class="h-10 sm:h-11 w-auto object-contain drop-shadow-2xs"
                                loading="lazy"
                            />
                        </div>

                        <!-- Nama Singkat Organisasi -->
                        <div class="border-l border-slate-800 pl-3">
                            <span class="text-xs font-black tracking-tight leading-tight uppercase block text-[#F95700]">
                                HIMATIF
                            </span>
                            <span class="text-xs font-extrabold tracking-tight leading-tight uppercase text-white block">
                                POLINDRA
                            </span>
                        </div>
                    </div>

                    <!-- Judul Lembaga & Deskripsi -->
                    <div class="space-y-2">
                        <h3 class="text-sm sm:text-base font-extrabold text-white tracking-tight leading-snug">
                            Himpunan Mahasiswa Teknik Informatika
                        </h3>
                        <p class="text-xs text-slate-400 leading-relaxed max-w-sm">
                            Wadah resmi organisasi kemahasiswaan Program Studi Teknik Informatika Politeknik Negeri Indramayu untuk berkolaborasi, berinovasi teknologi, dan mengembangkan kepemimpinan mahasiswa.
                        </p>
                    </div>

                    <!-- Social Media Links -->
                    <div class="pt-1 flex items-center gap-2.5">
                        <a
                            href="https://instagram.com"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="Instagram HIMATIF"
                            class="w-9 h-9 rounded-xl bg-slate-900 border border-slate-800 hover:border-orange-500/60 hover:bg-[#F95700] hover:text-white text-slate-400 flex items-center justify-center transition shadow-2xs"
                        >
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <a
                            href="https://youtube.com"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="YouTube HIMATIF"
                            class="w-9 h-9 rounded-xl bg-slate-900 border border-slate-800 hover:border-red-500/60 hover:bg-red-600 hover:text-white text-slate-400 flex items-center justify-center transition shadow-2xs"
                        >
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </a>
                        <a
                            href="https://github.com"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="GitHub HIMATIF"
                            class="w-9 h-9 rounded-xl bg-slate-900 border border-slate-800 hover:border-slate-500 hover:bg-slate-800 hover:text-white text-slate-400 flex items-center justify-center transition shadow-2xs"
                        >
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/>
                            </svg>
                        </a>
                        <a
                            href="mailto:himatif@polindra.ac.id"
                            aria-label="Email HIMATIF"
                            class="w-9 h-9 rounded-xl bg-slate-900 border border-slate-800 hover:border-orange-500/60 hover:bg-[#F95700] hover:text-white text-slate-400 flex items-center justify-center transition shadow-2xs"
                        >
                            <svg class="w-4 h-4 fill-none stroke-current" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Kolom 2: Navigasi Cepat (Quick Links) -->
                <div class="lg:col-span-2 space-y-4 text-left">
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">
                        Jelajahi Portal
                    </h4>
                    <ul class="space-y-2.5 text-xs">
                        <li>
                            <Link :href="route('dashboard')" class="text-slate-400 hover:text-[#F95700] transition inline-block">
                                Beranda
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('informasi')" class="text-slate-400 hover:text-[#F95700] transition inline-block">
                                Informasi & Warta
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('kegiatan.index')" class="text-slate-400 hover:text-[#F95700] transition inline-block">
                                Program Kegiatan
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('penyewaan-inventaris')" class="text-slate-400 hover:text-[#F95700] transition inline-block">
                                Penyewaan Alat
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('produk.index')" class="text-slate-400 hover:text-[#F95700] transition inline-block">
                                Merchandise Shop
                            </Link>
                        </li>
                    </ul>
                </div>

                <!-- Kolom 3: Layanan & Departemen -->
                <div class="lg:col-span-3 space-y-4 text-left">
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">
                        Departemen & Program
                    </h4>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li class="flex items-center gap-2">
                            <span class="text-[#F95700]">▸</span>
                            <span>Departemen INFOKOM (Komunikasi & Warta)</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-[#F95700]">▸</span>
                            <span>Departemen Kaderisasi & Organisasi</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-[#F95700]">▸</span>
                            <span>Departemen Riset, Teknologi & Workshop</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-[#F95700]">▸</span>
                            <span>Departemen Danus (Dana & Usaha)</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-[#F95700]">▸</span>
                            <span>Layanan Inventaris & Fasilitas Lab</span>
                        </li>
                    </ul>
                </div>

                <!-- Kolom 4: Sekretariat & Kontak Kampus -->
                <div class="lg:col-span-3 space-y-4 text-left">
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">
                        Sekretariat & Kontak
                    </h4>
                    
                    <div class="space-y-3 text-xs text-slate-400">
                        <!-- Alamat Kampus -->
                        <div class="flex items-start gap-2.5">
                            <span class="text-base text-orange-500 shrink-0 mt-0.5">📍</span>
                            <span class="leading-relaxed">
                                Gedung Student Center POLINDRA Lt. 2<br />
                                Jl. Lohbener Lama No. 08, Legok, Lohbener, Indramayu, Jawa Barat 45252
                            </span>
                        </div>

                        <!-- Email -->
                        <div class="flex items-center gap-2.5">
                            <span class="text-base text-orange-500 shrink-0">✉️</span>
                            <a href="mailto:himatif@polindra.ac.id" class="hover:text-white transition">
                                himatif@polindra.ac.id
                            </a>
                        </div>

                        <!-- Hotline / WhatsApp -->
                        <div class="flex items-center gap-2.5">
                            <span class="text-base text-orange-500 shrink-0">📱</span>
                            <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer" class="hover:text-white transition">
                                +62 812-3456-7890 (Hotline Resmi)
                            </a>
                        </div>

                        <!-- Status Pelayanan -->
                        <div class="pt-1">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-900 border border-slate-800 text-[11px] font-semibold text-emerald-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                Sekretariat Buka (Senin - Jumat)
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Bottom Copyright Bar -->
        <div class="border-t border-slate-900 bg-slate-950/90 py-6 relative z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 text-center sm:text-left">
                    <span>© 2026 <strong>HIMATIF POLINDRA</strong>. Hak Cipta Dilindungi Undang-Undang.</span>
                    <span class="hidden sm:inline">•</span>
                    <span class="text-slate-400">Politeknik Negeri Indramayu</span>
                </div>

                <div class="flex items-center gap-4">
                    <span class="text-[11px] text-slate-500 hidden md:inline">
                        Dikembangkan oleh Departemen INFOKOM HIMATIF
                    </span>
                    <button
                        type="button"
                        @click="scrollToTop"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-300 hover:text-white transition text-xs font-semibold shadow-xs"
                    >
                        <span>Ke Atas</span>
                        <span>↑</span>
                    </button>
                </div>
            </div>
        </div>
    </footer>
</template>
