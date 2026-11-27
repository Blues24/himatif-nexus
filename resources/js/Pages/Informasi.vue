<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { computed, ref, onMounted, onUnmounted } from "vue";

// State Kategori & Pencarian
const activeCategory = ref("Semua");
const searchQuery = ref("");
const currentPage = ref(1);

const categories = ["Semua", "Formatif", "Workshop", "IT Competition"];

// Countdown Timer untuk Hackathon Nasional 2026
const countdown = ref({
    days: 14,
    hours: 9,
    minutes: 42,
    seconds: 16,
});

let timerInterval = null;

const startCountdown = () => {
    timerInterval = setInterval(() => {
        if (countdown.value.seconds > 0) {
            countdown.value.seconds--;
        } else {
            countdown.value.seconds = 59;
            if (countdown.value.minutes > 0) {
                countdown.value.minutes--;
            } else {
                countdown.value.minutes = 59;
                if (countdown.value.hours > 0) {
                    countdown.value.hours--;
                } else {
                    countdown.value.hours = 23;
                    if (countdown.value.days > 0) {
                        countdown.value.days--;
                    }
                }
            }
        }
    }, 1000);
};

onMounted(() => {
    startCountdown();
});

onUnmounted(() => {
    if (timerInterval) clearInterval(timerInterval);
});

// Data Artikel / Informasi Koleksi Warta (4 Item dengan Placeholder Text & Thumbnail)
const allArticles = [
    {
        id: 1,
        title: "Judul Artikel Informasi atau Warta (Placeholder 1)",
        desc: "Deskripsi ringkas mengenai konten artikel informasi warta terbaru yang akan dipublikasikan secara berkala oleh pengurus himpunan.",
        category: "Formatif",
        badgeText: "Formatif",
        badgeClass: "bg-white/95 text-slate-800 font-bold",
        date: "DD Bulan YYYY",
        author: "Divisi Humas (Subdivisi INFOKOM)",
        readTime: "3 Menit Baca",
        image: null,
        placeholderIcon: "⚡",
        placeholderLabel: "DOKUMENTASI WARTA",
        placeholderSub: "Warta Placeholder #1",
        actionText: "Lihat Selengkapnya...",
        actionClass: "text-[#F95700] hover:text-orange-700",
        tags: ["Formatif", "Edukasi", "Teknologi"],
        paragraphs: [
            "Artikel informasi warta ini menyajikan ulasan mendalam mengenai wawasan teknologi terkini yang relevan dengan perkembangan keilmuan mahasiswa program studi Teknik Informatika POLINDRA.",
            "Materi dirancang secara runut mulai dari pemahaman konsep dasar, studi komparasi arsitektur, hingga implementasi praktis dalam alur kerja pengembangan perangkat lunak modern.",
            "Dengan tersedianya arsip informasi ini, mahasiswa dapat menjadikannya referensi komprehensif untuk meningkatkan kompetensi akademik, tugas perkuliahan, maupun riset mandiri.",
        ],
        keyPoints: [
            "Pemahaman konsep fundamental dan metodologi terkini",
            "Best practices dan strategi implementasi sistem",
            "Referensi materi pembelajaran dan dokumentasi lanjutan",
        ],
    },
    {
        id: 2,
        title: "Judul Pelatihan atau Workshop (Placeholder 2)",
        desc: "Deskripsi ringkas mengenai materi pelatihan teknologi atau workshop komputasi yang diselenggarakan oleh pengurus himpunan.",
        category: "Workshop",
        badgeText: "Workshop",
        badgeClass: "bg-white/95 text-slate-800 font-bold",
        date: "DD Bulan YYYY • Lokasi Kegiatan",
        author: "Divisi Riset dan Teknologi",
        readTime: "4 Menit Baca",
        image: null,
        placeholderIcon: "💻",
        placeholderLabel: "DOKUMENTASI ARSIP",
        placeholderSub: "Workshop Placeholder #2",
        actionText: "Lihat Detail Workshop...",
        actionClass: "text-[#F95700]",
        tags: ["Workshop", "Hands-On", "Coding"],
        paragraphs: [
            "Workshop pelatihan ini dirancang untuk membekali mahasiswa dengan keahlian teknis hands-on live-coding di laboratorium komputer bersama praktisi industri.",
            "Peserta diajak mempraktikkan langsung perancangan arsitektur aplikasi, teknik debugging, dan deployment proyek berbasis cloud yang memenuhi standar industri software engineering.",
            "Arsip dokumentasi workshop ini mencakup ringkasan materi presentasi, repository source code panduan, serta hasil evaluasi project akhir peserta.",
        ],
        keyPoints: [
            "Live-coding interaktif dan pemecahan studi kasus riil",
            "Akses ke repository source code panduan praktikum",
            "Sertifikasi kompetensi dan portofolio hasil pelatihan",
        ],
    },
    {
        id: 3,
        title: "Judul Modul Pembelajaran atau Edukasi (Placeholder 3)",
        desc: "Deskripsi ringkas mengenai panduan modul materi perkuliahan, tips teknologi, serta wawasan akademik mahasiswa informatika.",
        category: "Formatif",
        badgeText: "Formatif",
        badgeClass: "bg-white/95 text-slate-800 font-bold",
        date: "DD Bulan YYYY",
        author: "Divisi PSDM (Subdivisi Kaderisasi)",
        readTime: "3 Menit Baca",
        image: null,
        placeholderIcon: "🌐",
        placeholderLabel: "DOKUMENTASI WARTA",
        placeholderSub: "Modul Placeholder #3",
        actionText: "Lihat Selengkapnya...",
        actionClass: "text-[#F95700] hover:text-orange-700",
        tags: ["Modul", "Akademik", "Formatif"],
        paragraphs: [
            "Modul edukatif ini dirilis sebagai panduan pendamping perkuliahan bagi mahasiswa dalam mendalami kurikulum inti jurusan Teknik Informatika.",
            "Setiap bab modul dilengkapi dengan penjelasan teoritis yang padat, diagram arsitektur sistem, dan latihan soal praktikum untuk memperkuat pemahaman logika pemrograman.",
            "Modul ini dapat diakses secara terbuka oleh seluruh mahasiswa sebagai bahan rujukan belajar mandiri maupun persiapan ujian semester.",
        ],
        keyPoints: [
            "Rangkuman konsep kurikulum semester terkini",
            "Latihan soal praktikum terstruktur dan solusinya",
            "Tautan repositori modul dan catatan pendukung",
        ],
    },
    {
        id: 4,
        title: "Judul Kompetisi atau Perlombaan Mahasiswa (Placeholder 4)",
        desc: "Deskripsi ringkas mengenai informasi pendaftaran kompetisi atau kejuaraan teknologi tingkat kampus maupun nasional.",
        category: "IT Competition",
        badgeText: "Kompetisi",
        badgeClass: "bg-[#F95700] text-white font-bold",
        date: "DD Bulan YYYY • Penyelenggara Acara",
        author: "Divisi Riset dan Teknologi (Subdivisi Kompetisi)",
        readTime: "2 Menit Baca",
        image: null,
        placeholderIcon: "🏆",
        placeholderLabel: "Poster Kompetisi",
        placeholderSub: "Kompetisi Placeholder #4",
        actionText: "Lihat Detail Lomba...",
        actionClass: "text-[#F95700]",
        tags: ["Kompetisi", "Hackathon", "Prestasi"],
        paragraphs: [
            "Informasi mengenai ajang kompetisi teknologi dan perlombaan inovasi digital berskala regional hingga nasional yang terbuka bagi mahasiswa POLINDRA.",
            "Kategori perlombaan mencakup Software Development, UI/UX Design Competition, Data Mining, dan Competitive Programming dengan bimbingan dari himpunan.",
            "HIMATIF memfasilitasi pembentukan tim delegasi, bimbingan dosen pendamping, serta dukungan administrasi kampus untuk meraih prestasi gemilang.",
        ],
        keyPoints: [
            "Timeline resmi pendaftaran dan tahapan seleksi lomba",
            "Syarat & ketentuan penyusunan proposal inovasi",
            "Fasilitas pembinaan delegasi dari divisi prestasi",
        ],
    },
];

// Filter data berdasarkan kategori aktif dan kata kunci pencarian
const filteredArticles = computed(() => {
    let result = allArticles;

    if (activeCategory.value !== "Semua") {
        result = result.filter(
            (item) => item.category === activeCategory.value,
        );
    }

    if (searchQuery.value.trim() !== "") {
        const query = searchQuery.value.toLowerCase();
        result = result.filter(
            (item) =>
                item.title.toLowerCase().includes(query) ||
                item.desc.toLowerCase().includes(query) ||
                item.category.toLowerCase().includes(query),
        );
    }

    return result;
});
</script>

<template>
    <Head title="Pusat Informasi & Pengetahuan IT - HIMATIF POLINDRA" />

    <AuthenticatedLayout>
        <div
            class="bg-gradient-to-b from-white via-slate-50 to-slate-100 min-h-screen pb-20"
        >
            <!-- Container Utama -->
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8 space-y-10 sm:space-y-12"
            >
                <!-- 1. Header Halaman -->
                <div class="space-y-3">
                    <!-- Pill Badge -->
                    <div
                        class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#FFF2EB] border border-orange-200/70 shadow-xs"
                    >
                        <span class="text-xs">📢</span>
                        <span
                            class="text-[11px] sm:text-xs font-black text-[#F95700] tracking-wider uppercase"
                        >
                            PUSAT EDUKASI & INFORMASI IT
                        </span>
                    </div>

                    <!-- Judul Besar -->
                    <h1
                        class="text-3xl sm:text-4xl lg:text-[46px] font-black text-slate-900 tracking-tight leading-[1.15]"
                    >
                        Pusat Informasi &<br />
                        <span class="text-[#F95700]">Pengetahuan IT</span>
                    </h1>

                    <!-- Deskripsi -->
                    <p
                        class="text-xs sm:text-sm md:text-base text-slate-600 max-w-xl font-normal leading-relaxed"
                    >
                        Pusat informasi kompetisi IT, workshop, materi belajar
                        edukatif, serta modul pembelajaran terpadu.
                    </p>
                </div>

                <!-- 2. Featured Banner (Orange Card - Sesuai Spesifikasi & Mockup Referensi) -->
                <div
                    class="featured-card relative overflow-hidden rounded-3xl sm:rounded-[32px] p-6 sm:p-8 lg:p-10 text-white shadow-2xl shadow-orange-500/25"
                    style="background-color: #f95700"
                >
                    <div
                        class="featured-grid flex flex-col md:flex-row items-center justify-between gap-6 lg:gap-10 w-full"
                    >
                        <!-- Kolom Kiri: Teks & Countdown -->
                        <div
                            class="featured-content flex-1 w-full space-y-4 sm:space-y-5 text-left"
                        >
                            <!-- Judul Teks Putih Tebal -->
                            <h2
                                class="text-2xl sm:text-3xl lg:text-[34px] font-black text-white tracking-tight leading-snug"
                            >
                                Informatics Championship &<br
                                    class="hidden sm:inline"
                                />
                                Hackathon Nasional 2026
                            </h2>
                            <!-- Deskripsi Teks Putih -->
                            <p
                                class="text-xs sm:text-[13px] text-white/95 leading-relaxed font-normal max-w-xl"
                            >
                                Kompetisi tahunan bergengsi HIMATIF mengusung
                                tema "Scalable AI for Socio-Economic
                                Resilience". Berkolaborasi dengan industri
                                teknologi terkemuka untuk total hadiah IDR
                                65.000.000 + Golden Ticket Internship.
                            </p>

                            <!-- Kotak Countdown Timer (Abu-abu Gelap dengan 4 Kotak Oranye) -->
                            <div
                                class="rounded-2xl p-3.5 sm:p-4 max-w-md shadow-inner border border-black/10"
                                style="background-color: #2d2f36"
                            >
                                <div
                                    class="flex items-center gap-1.5 text-xs font-semibold text-white/90 mb-2.5"
                                >
                                    <span style="color: #f95700">⏱</span>
                                    <span
                                        >Batas Akhir Registrasi Gelombang
                                        I:</span
                                    >
                                </div>
                                <div
                                    class="flex flex-row items-center gap-2 w-full"
                                >
                                    <!-- Hari -->
                                    <div
                                        class="rounded-xl py-2.5 px-1 text-center shadow-xs"
                                        style="
                                            background-color: #f95700;
                                            flex: 1 1 0%;
                                            min-width: 0;
                                        "
                                    >
                                        <div
                                            class="text-xl sm:text-2xl font-black text-white leading-none"
                                        >
                                            {{
                                                String(countdown.days).padStart(
                                                    2,
                                                    "0",
                                                )
                                            }}
                                        </div>
                                        <div
                                            class="text-[10px] sm:text-xs font-normal text-white mt-1 leading-none"
                                        >
                                            Hari
                                        </div>
                                    </div>
                                    <!-- Jam -->
                                    <div
                                        class="rounded-xl py-2.5 px-1 text-center shadow-xs"
                                        style="
                                            background-color: #f95700;
                                            flex: 1 1 0%;
                                            min-width: 0;
                                        "
                                    >
                                        <div
                                            class="text-xl sm:text-2xl font-black text-white leading-none"
                                        >
                                            {{
                                                String(
                                                    countdown.hours,
                                                ).padStart(2, "0")
                                            }}
                                        </div>
                                        <div
                                            class="text-[10px] sm:text-xs font-normal text-white mt-1 leading-none"
                                        >
                                            Jam
                                        </div>
                                    </div>
                                    <!-- Menit -->
                                    <div
                                        class="rounded-xl py-2.5 px-1 text-center shadow-xs"
                                        style="
                                            background-color: #f95700;
                                            flex: 1 1 0%;
                                            min-width: 0;
                                        "
                                    >
                                        <div
                                            class="text-xl sm:text-2xl font-black text-white leading-none"
                                        >
                                            {{
                                                String(
                                                    countdown.minutes,
                                                ).padStart(2, "0")
                                            }}
                                        </div>
                                        <div
                                            class="text-[10px] sm:text-xs font-normal text-white mt-1 leading-none"
                                        >
                                            Menit
                                        </div>
                                    </div>
                                    <!-- Detik -->
                                    <div
                                        class="rounded-xl py-2.5 px-1 text-center shadow-xs"
                                        style="
                                            background-color: #f95700;
                                            flex: 1 1 0%;
                                            min-width: 0;
                                        "
                                    >
                                        <div
                                            class="text-xl sm:text-2xl font-black text-white leading-none"
                                        >
                                            {{
                                                String(
                                                    countdown.seconds,
                                                ).padStart(2, "0")
                                            }}
                                        </div>
                                        <div
                                            class="text-[10px] sm:text-xs font-normal text-white mt-1 leading-none"
                                        >
                                            Detik
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Aksi (CTA) Sejajar Horizontal -->
                            <div
                                class="flex flex-row flex-wrap items-center gap-3 pt-1"
                            >
                                <a
                                    href="#daftar"
                                    class="inline-flex items-center gap-2 px-5 py-3 rounded-xl font-bold text-xs sm:text-sm shadow-md transition transform active:scale-95 select-none"
                                    style="
                                        background-color: #00d2ff;
                                        color: #ffffff;
                                    "
                                >
                                    <span>Daftar Tim Hackathon</span>
                                    <span>→</span>
                                </a>
                                <a
                                    href="#panduan"
                                    class="inline-flex items-center gap-2 px-5 py-3 rounded-xl font-bold text-xs sm:text-sm border border-white/15 shadow-sm transition transform active:scale-95 select-none"
                                    style="
                                        background-color: #20222a;
                                        color: #ffffff;
                                    "
                                >
                                    <span>📖 Buku Panduan (PDF)</span>
                                </a>
                            </div>
                        </div>

                        <!-- Kolom Kanan: Gambar Banner di Samping Teks -->
                        <div
                            class="featured-poster shrink-0 flex justify-center items-center"
                        >
                            <div
                                class="w-full rounded-2xl overflow-hidden shadow-2xl relative group bg-slate-900 border border-white/15"
                            >
                                <img
                                    src="/images/maskot-lomba.png"
                                    alt="Informatics Championship & Hackathon Nasional 2026"
                                    class="w-full h-auto object-cover group-hover:scale-105 transition duration-500"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Koleksi Warta: Filter & Pencarian -->
                <div class="space-y-6">
                    <div
                        class="flex flex-col md:flex-row md:items-end justify-between gap-4"
                    >
                        <div>
                            <span
                                class="text-xs font-bold tracking-widest text-[#F95700] uppercase block mb-1"
                            >
                                KOLEKSI WARTA
                            </span>
                            <h2
                                class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight"
                            >
                                Eksplorasi Informasi Terbaru
                            </h2>
                        </div>

                        <!-- Kotak Pencarian -->
                        <div class="w-full md:w-80 relative">
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Cari lomba, modul, atau workshop..."
                                class="w-full pl-10 pr-4 py-2.5 rounded-full border border-slate-200 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 shadow-xs transition"
                            />
                            <svg
                                class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                />
                            </svg>
                        </div>
                    </div>

                    <!-- Filter Kategori Pills -->
                    <div
                        class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none select-none"
                    >
                        <button
                            v-for="cat in categories"
                            :key="cat"
                            type="button"
                            @click="activeCategory = cat"
                            class="shrink-0 px-4 py-2 rounded-full text-xs font-bold transition-all duration-200"
                            :class="
                                activeCategory === cat
                                    ? 'bg-slate-900 text-white shadow-sm'
                                    : 'bg-white hover:bg-slate-100 text-slate-700 border border-slate-200/80'
                            "
                        >
                            {{ cat }}
                        </button>
                    </div>

                    <!-- 4. Grid Kartu Informasi (4 Kolom Responsive Sesuai Permintaan) -->
                    <div
                        v-if="filteredArticles.length > 0"
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6"
                    >
                        <Link
                            v-for="item in filteredArticles"
                            :key="item.id"
                            :href="route('informasi.show', item.id)"
                            class="bg-white rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-xl hover:border-orange-300 transition-all duration-300 overflow-hidden flex flex-col justify-between group cursor-pointer"
                        >
                            <div>
                                <!-- Thumbnail Gambar dengan Badge Kategori -->
                                <div class="p-3 pb-0">
                                    <div
                                        class="relative overflow-hidden rounded-xl aspect-[16/10] bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center"
                                    >
                                        <img
                                            v-if="item.image"
                                            :src="item.image"
                                            :alt="item.title"
                                            class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                        />
                                        <!-- Placeholder Elegan Jika Foto Belum Ada -->
                                        <div
                                            v-else
                                            class="flex flex-col items-center justify-center text-center p-4 text-slate-300"
                                        >
                                            <span class="text-3xl mb-1">{{
                                                item.placeholderIcon || "📰"
                                            }}</span>
                                            <span
                                                class="text-[11px] font-black tracking-wider uppercase text-slate-200"
                                                >{{
                                                    item.placeholderLabel ||
                                                    "Dokumentasi Warta"
                                                }}</span
                                            >
                                            <span
                                                class="text-[10px] text-slate-400 mt-0.5 truncate max-w-[190px]"
                                                >{{
                                                    item.placeholderSub ||
                                                    item.title
                                                }}</span
                                            >
                                        </div>
                                        <!-- Badge Kategori di Atas Gambar -->
                                        <div class="absolute top-2.5 left-2.5">
                                            <span
                                                class="inline-block px-2.5 py-1 rounded-full text-[10px] sm:text-[11px] shadow-sm uppercase tracking-wider"
                                                :class="item.badgeClass"
                                            >
                                                {{ item.badgeText }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Konten Teks -->
                                <div class="p-4 sm:p-5 space-y-2">
                                    <div
                                        class="flex items-center gap-1.5 text-[11px] font-semibold text-slate-500"
                                    >
                                        <span>📅</span>
                                        <span class="truncate">{{
                                            item.date
                                        }}</span>
                                    </div>
                                    <h3
                                        class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-[#F95700] transition line-clamp-2 leading-snug"
                                    >
                                        {{ item.title }}
                                    </h3>
                                    <p
                                        class="text-xs text-slate-500 line-clamp-2 leading-relaxed"
                                    >
                                        {{ item.desc }}
                                    </p>
                                </div>
                            </div>

                            <!-- Footer Kartu -->
                            <div
                                class="px-4 sm:px-5 pb-4 sm:pb-5 pt-1 flex items-center justify-between border-t border-slate-100"
                            >
                                <span
                                    class="text-xs font-bold transition group-hover:underline"
                                    :class="item.actionClass"
                                >
                                    {{ item.actionText }}
                                </span>
                                <span
                                    class="text-slate-400 group-hover:text-[#F95700] group-hover:translate-x-1 transition text-sm"
                                >
                                    →
                                </span>
                            </div>
                        </Link>
                    </div>

                    <!-- State Kosong jika Pencarian Tidak Ditemukan -->
                    <div
                        v-else
                        class="text-center py-16 bg-white rounded-3xl border border-slate-200/80 p-8"
                    >
                        <div class="text-4xl mb-3">🔍</div>
                        <h3 class="text-lg font-bold text-slate-800">
                            Tidak ada informasi yang sesuai
                        </h3>
                        <p
                            class="text-xs sm:text-sm text-slate-500 mt-1 max-w-md mx-auto"
                        >
                            Coba gunakan kata kunci lain atau pilih kategori
                            "Semua" untuk melihat seluruh warta.
                        </p>
                        <button
                            type="button"
                            @click="
                                activeCategory = 'Semua';
                                searchQuery = '';
                            "
                            class="mt-4 px-4 py-2 rounded-xl bg-orange-500 text-white font-bold text-xs hover:bg-orange-600 transition"
                        >
                            Reset Filter
                        </button>
                    </div>

                    <!-- 5. Pagination Bar -->
                    <div
                        class="flex items-center justify-center gap-1.5 sm:gap-2 pt-6 select-none"
                    >
                        <!-- Prev -->
                        <button
                            type="button"
                            @click="currentPage = Math.max(1, currentPage - 1)"
                            class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center justify-center text-sm font-bold transition"
                            :disabled="currentPage === 1"
                        >
                            ‹
                        </button>

                        <!-- Numbers 1 to 5 -->
                        <button
                            v-for="page in 5"
                            :key="page"
                            type="button"
                            @click="currentPage = page"
                            class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl text-xs sm:text-sm font-bold transition shadow-xs"
                            :class="
                                currentPage === page
                                    ? 'bg-[#F95700] text-white border border-[#F95700]'
                                    : 'bg-white hover:bg-slate-50 text-slate-700 border border-slate-200'
                            "
                        >
                            {{ page }}
                        </button>

                        <!-- Next -->
                        <button
                            type="button"
                            @click="currentPage = Math.min(5, currentPage + 1)"
                            class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center justify-center text-sm font-bold transition"
                            :disabled="currentPage === 5"
                        >
                            ›
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.featured-card {
    background-color: #f95700;
}

@media (min-width: 768px) {
    .featured-grid {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: space-between !important;
        gap: 2rem !important;
    }
    .featured-content {
        flex: 1 1 0% !important;
        min-width: 0 !important;
    }
    .featured-poster {
        width: 360px !important;
        flex-shrink: 0 !important;
    }
}

@media (min-width: 1024px) {
    .featured-grid {
        gap: 2.5rem !important;
    }
    .featured-poster {
        width: 420px !important;
    }
}
</style>
