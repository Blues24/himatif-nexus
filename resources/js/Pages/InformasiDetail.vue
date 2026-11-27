<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const props = defineProps({
    id: {
        type: Number,
        default: 1,
    },
});

// Master Data Koleksi Warta & Informasi
const allArticles = [
    {
        id: 1,
        title: "Judul Artikel Informasi atau Warta (Placeholder 1)",
        desc: "Deskripsi ringkas mengenai konten artikel informasi warta terbaru yang akan dipublikasikan secara berkala oleh pengurus himpunan.",
        category: "Formatif",
        badgeText: "Formatif",
        badgeClass: "bg-[#F95700] text-white",
        date: "DD Bulan YYYY",
        author: "Divisi Humas HIMATIF",
        authorRole: "Subdivisi Informasi dan Komunikasi",
        readTime: "3 Menit Baca",
        image: null,
        placeholderIcon: "⚡",
        placeholderLabel: "DOKUMENTASI WARTA",
        placeholderSub: "Warta Placeholder #1",
        tags: ["Formatif", "Edukasi", "Teknologi", "POLINDRA"],
        paragraphs: [
            "Artikel informasi warta ini menyajikan ulasan mendalam mengenai wawasan teknologi terkini yang relevan dengan perkembangan keilmuan mahasiswa program studi Teknik Informatika Politeknik Negeri Indramayu.",
            "Materi dirancang secara runut mulai dari pemahaman konsep dasar, studi komparasi arsitektur sistem, hingga implementasi praktis dalam alur kerja pengembangan perangkat lunak modern di dunia industri.",
            "Dengan tersedianya arsip informasi ini, seluruh mahasiswa dapat menjadikannya referensi komprehensif untuk meningkatkan kompetensi akademik, pengerjaan proyek tugas akhir, maupun pengayaan riset mandiri.",
            "HIMATIF POLINDRA berkomitmen untuk terus menghadirkan konten edukatif berkualitas tinggi yang mendorong kultur literasi teknologi, kolaborasi antarmahasiswa, dan kesiapan karir profesional di masa depan.",
        ],
        keyPoints: [
            "Pemahaman konsep fundamental dan metodologi terkini",
            "Best practices dan strategi implementasi arsitektur perangkat lunak",
            "Referensi materi pembelajaran, dokumentasi resmi, dan studi kasus praktis",
            "Peluang kolaborasi riset dan pengembangan proyek teknologi mahasiswa",
        ],
    },
    {
        id: 2,
        title: "Judul Pelatihan atau Workshop (Placeholder 2)",
        desc: "Deskripsi ringkas mengenai materi pelatihan teknologi atau workshop komputasi yang diselenggarakan oleh pengurus himpunan.",
        category: "Workshop",
        badgeText: "Workshop",
        badgeClass: "bg-emerald-600 text-white",
        date: "DD Bulan YYYY • GSC POLINDRA",
        author: "Divisi Riset dan Teknologi",
        authorRole: "Subdivisi Study Club",
        readTime: "4 Menit Baca",
        image: null,
        placeholderIcon: "💻",
        placeholderLabel: "DOKUMENTASI ARSIP",
        placeholderSub: "Workshop Placeholder #2",
        tags: ["Workshop", "Hands-On", "Coding", "Web Development"],
        paragraphs: [
            "Workshop pelatihan ini dirancang untuk membekali mahasiswa dengan keahlian teknis hands-on live-coding di laboratorium komputer bersama praktisi industri teknologi terkemuka.",
            "Peserta diajak mempraktikkan langsung perancangan arsitektur aplikasi web modern, teknik debugging sistem berskala besar, dan alur kerja deployment cloud computing berstandar industri.",
            "Arsip dokumentasi workshop ini mencakup ringkasan materi presentasi pemateri, tautan repository source code panduan langkah-demi-langkah, serta evaluasi hasil project akhir peserta.",
            "Kegiatan ini diharapkan mampu menumbuhkan rasa percaya diri mahasiswa dalam membangun solusi digital yang siap bersaing di kancah nasional maupun internasional.",
        ],
        keyPoints: [
            "Live-coding interaktif dan pemecahan studi kasus komputasi riil",
            "Akses ke repository source code panduan praktikum resmi",
            "Sertifikasi kompetensi dan portofolio hasil pelatihan peserta",
            "Sesi networking eksklusif bersama praktisi software engineer",
        ],
    },
    {
        id: 3,
        title: "Judul Modul Pembelajaran atau Edukasi (Placeholder 3)",
        desc: "Deskripsi ringkas mengenai panduan modul materi perkuliahan, tips teknologi, serta wawasan akademik mahasiswa informatika.",
        category: "Formatif",
        badgeText: "Formatif",
        badgeClass: "bg-indigo-600 text-white",
        date: "DD Bulan YYYY",
        author: "Divisi Pengembangan Sumber Daya Manusia (PSDM)",
        authorRole: "Subdivisi Kaderisasi",
        readTime: "3 Menit Baca",
        image: null,
        placeholderIcon: "🌐",
        placeholderLabel: "DOKUMENTASI WARTA",
        placeholderSub: "Modul Placeholder #3",
        tags: ["Modul", "Akademik", "Formatif", "Study Club"],
        paragraphs: [
            "Modul edukatif ini dirilis sebagai panduan pendamping perkuliahan bagi mahasiswa dalam mendalami kurikulum inti jurusan Teknik Informatika semester berjalan.",
            "Setiap bab modul dilengkapi dengan penjelasan teoritis yang padat, diagram alur data, contoh algoritma, dan latihan soal praktikum untuk memperkuat pemahaman logika pemrograman.",
            "Modul ini dapat diakses secara terbuka oleh seluruh mahasiswa sebagai bahan rujukan belajar mandiri maupun persiapan menghadapi evaluasi ujian semester.",
            "Pengurus HIMATIF secara berkala memperbarui materi modul agar selalu relevan dengan kurikulum kampus dan perkembangan framework teknologi terkini.",
        ],
        keyPoints: [
            "Rangkuman konsep kurikulum semester terkini secara sistematis",
            "Latihan soal praktikum terstruktur beserta kunci pemecahan masalah",
            "Tautan repositori modul PDF dan catatan rangkuman kuliah",
            "Forum diskusi terbuka study club mahasiswa informatika",
        ],
    },
    {
        id: 4,
        title: "Judul Kompetisi atau Perlombaan Mahasiswa (Placeholder 4)",
        desc: "Deskripsi ringkas mengenai informasi pendaftaran kompetisi atau kejuaraan teknologi tingkat kampus maupun nasional.",
        category: "IT Competition",
        badgeText: "Kompetisi",
        badgeClass: "bg-[#F95700] text-white",
        date: "DD Bulan YYYY • Politeknik Negeri Indramayu",
        author: "Divisi Riset dan Teknologi",
        authorRole: "Subdivisi Kompetisi",
        readTime: "2 Menit Baca",
        image: null,
        placeholderIcon: "🏆",
        placeholderLabel: "Poster Kompetisi",
        placeholderSub: "Kompetisi Placeholder #4",
        tags: ["Kompetisi", "Hackathon", "Prestasi", "Inovasi"],
        paragraphs: [
            "Informasi mengenai ajang kompetisi teknologi dan perlombaan inovasi digital berskala regional hingga nasional yang terbuka bagi mahasiswa POLINDRA.",
            "Kategori perlombaan mencakup Software Development, UI/UX Design Competition, Data Mining, dan Competitive Programming dengan bimbingan terstruktur dari himpunan.",
            "HIMATIF memfasilitasi pembentukan tim delegasi, bimbingan dosen pendamping, serta dukungan administrasi kampus untuk mendampingi tim meraih prestasi gemilang.",
            "Mari tunjukkan potensi terbaik karya mahasiswa Teknik Informatika POLINDRA dan harumkan nama almamater di kancah perlombaan bergengsi!",
        ],
        keyPoints: [
            "Timeline resmi pendaftaran dan tahapan seleksi tim lomba",
            "Syarat & ketentuan penyusunan proposal inovasi dan prototype produk",
            "Fasilitas pembinaan delegasi dan mentoring dosen ahli",
            "Hadiah jutaan rupiah, sertifikat juara, dan pengakuan prestasi",
        ],
    },
];

// Temukan artikel sesuai ID props (fallback ke artikel pertama)
const currentArticle = computed(() => {
    const found = allArticles.find((item) => item.id === Number(props.id));
    return found || allArticles[0];
});

// Artikel Lainnya (Terkait)
const relatedArticles = computed(() => {
    return allArticles
        .filter((item) => item.id !== currentArticle.value.id)
        .slice(0, 3);
});

// State Salin Tautan
const isCopied = ref(false);
const copyCurrentUrl = () => {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(window.location.href);
        isCopied.value = true;
        setTimeout(() => {
            isCopied.value = false;
        }, 2500);
    }
};

// Share ke WhatsApp
const shareToWhatsApp = () => {
    const title = encodeURIComponent(currentArticle.value.title);
    const url = encodeURIComponent(window.location.href);
    window.open(
        `https://api.whatsapp.com/send?text=${title}%20-%20${url}`,
        "_blank",
    );
};
</script>

<template>
    <Head :title="`${currentArticle.title} - HIMATIF POLINDRA`" />

    <AuthenticatedLayout>
        <div
            class="bg-gradient-to-b from-white via-slate-50 to-slate-100 min-h-screen pb-24"
        >
            <!-- Container Utama -->
            <div
                class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8 space-y-8"
            >
                <!-- 1. Breadcrumb & Tombol Kembali -->
                <div
                    class="flex flex-wrap items-center justify-between gap-3 select-none"
                >
                    <nav
                        class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 font-medium"
                    >
                        <Link
                            :href="route('dashboard')"
                            class="hover:text-orange-600 transition"
                            >Beranda</Link
                        >
                        <span>/</span>
                        <Link
                            :href="route('informasi')"
                            class="hover:text-orange-600 transition"
                            >Informasi</Link
                        >
                        <span>/</span>
                        <span
                            class="text-slate-800 font-semibold truncate max-w-[200px] sm:max-w-xs"
                        >
                            {{ currentArticle.category }}
                        </span>
                    </nav>

                    <Link
                        :href="route('informasi')"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:text-orange-600 hover:border-orange-300 shadow-xs transition"
                    >
                        <span>←</span>
                        <span>Kembali ke Semua Informasi</span>
                    </Link>
                </div>

                <!-- 2. Header Artikel -->
                <header class="space-y-4 text-left">
                    <!-- Badge Kategori -->
                    <div class="inline-block">
                        <span
                            class="px-3.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider shadow-xs"
                            :class="currentArticle.badgeClass"
                        >
                            {{ currentArticle.category }}
                        </span>
                    </div>

                    <!-- Judul Besar Artikel -->
                    <h1
                        class="text-2xl sm:text-4xl lg:text-[42px] font-black text-slate-900 tracking-tight leading-[1.2]"
                    >
                        {{ currentArticle.title }}
                    </h1>

                    <!-- Meta Author & Tanggal -->
                    <div
                        class="flex flex-wrap items-center justify-between gap-4 pt-2 pb-4 border-b border-slate-200 text-xs sm:text-sm text-slate-600 font-medium"
                    >
                        <div class="flex items-center gap-3">
                            <!-- Avatar Inisial -->
                            <div
                                class="w-10 h-10 rounded-full bg-gradient-to-tr from-orange-500 to-amber-500 text-white font-black flex items-center justify-center text-sm shadow-xs shrink-0"
                            >
                                📢
                            </div>
                            <div>
                                <div class="font-bold text-slate-900">
                                    {{ currentArticle.author }}
                                </div>
                                <div class="text-xs text-slate-400">
                                    {{ currentArticle.authorRole }}
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 text-xs sm:text-sm">
                            <span
                                class="flex items-center gap-1.5 text-slate-500"
                            >
                                <span>📅</span>
                                <span>{{ currentArticle.date }}</span>
                            </span>
                            <span class="text-slate-300">•</span>
                            <span
                                class="flex items-center gap-1.5 text-slate-500"
                            >
                                <span>⏱️</span>
                                <span>{{ currentArticle.readTime }}</span>
                            </span>
                        </div>
                    </div>
                </header>

                <!-- 3. Featured Image / Placeholder Banner (Ukuran Penuh) -->
                <div
                    class="rounded-3xl overflow-hidden shadow-xl border border-slate-200/90 bg-gradient-to-br from-slate-800 to-slate-900"
                >
                    <div
                        class="relative aspect-[16/8] sm:aspect-[21/9] w-full flex items-center justify-center text-slate-300"
                    >
                        <img
                            v-if="currentArticle.image"
                            :src="currentArticle.image"
                            :alt="currentArticle.title"
                            class="w-full h-full object-cover"
                        />
                        <div
                            v-else
                            class="flex flex-col items-center justify-center text-center p-8"
                        >
                            <span class="text-6xl mb-2">{{
                                currentArticle.placeholderIcon || "📰"
                            }}</span>
                            <span
                                class="text-base sm:text-lg font-black tracking-wider uppercase text-white"
                            >
                                {{
                                    currentArticle.placeholderLabel ||
                                    "DOKUMENTASI WARTA"
                                }}
                            </span>
                            <span
                                class="text-xs sm:text-sm text-slate-400 mt-1 max-w-md"
                            >
                                {{
                                    currentArticle.placeholderSub ||
                                    currentArticle.title
                                }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- 4. Konten Utama Artikel (Layout 2 Kolom di Desktop) -->
                <div
                    class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 text-left pt-2"
                >
                    <!-- Kolom Kiri: Isi Artikel (8 Kolom) -->
                    <main class="lg:col-span-8 space-y-6">
                        <!-- Lead Paragraph (Highlight Box) -->
                        <div
                            class="p-5 sm:p-6 rounded-2xl bg-orange-50 border-l-4 border-orange-500 text-slate-800 text-sm sm:text-base font-semibold leading-relaxed shadow-xs"
                        >
                            {{ currentArticle.desc }}
                        </div>

                        <!-- Paragraf Isi Artikel -->
                        <div
                            class="space-y-4 text-slate-700 text-sm sm:text-base leading-relaxed"
                        >
                            <p
                                v-for="(p, idx) in currentArticle.paragraphs"
                                :key="idx"
                                class="text-justify"
                            >
                                {{ p }}
                            </p>
                        </div>

                        <!-- Key Takeaways / Poin Penting Pembahasan -->
                        <div
                            class="p-6 rounded-3xl bg-white border border-slate-200/90 shadow-xs space-y-3 mt-6"
                        >
                            <div
                                class="flex items-center gap-2 text-slate-900 font-bold text-sm sm:text-base"
                            >
                                <span class="text-orange-500 text-lg">📌</span>
                                <span>Poin Pembahasan Utama:</span>
                            </div>
                            <ul
                                class="space-y-2 text-xs sm:text-sm text-slate-600"
                            >
                                <li
                                    v-for="(
                                        point, idx
                                    ) in currentArticle.keyPoints"
                                    :key="idx"
                                    class="flex items-start gap-2.5"
                                >
                                    <span
                                        class="text-emerald-500 font-bold shrink-0"
                                        >✓</span
                                    >
                                    <span>{{ point }}</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Tags Topik -->
                        <div
                            class="pt-4 flex flex-wrap items-center gap-2 border-t border-slate-200"
                        >
                            <span class="text-xs font-bold text-slate-400 mr-1"
                                >Label Topik:</span
                            >
                            <span
                                v-for="tag in currentArticle.tags"
                                :key="tag"
                                class="px-3 py-1 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-orange-50 hover:text-orange-600 transition"
                            >
                                #{{ tag }}
                            </span>
                        </div>

                        <!-- Bagikan Artikel -->
                        <div
                            class="p-5 rounded-2xl bg-white border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 mt-6"
                        >
                            <div class="space-y-0.5">
                                <div
                                    class="text-xs font-black uppercase text-slate-400 tracking-wider"
                                >
                                    Bagikan Warta Ini
                                </div>
                                <div class="text-xs text-slate-600">
                                    Ajak teman kamu untuk membaca artikel
                                    informatif ini.
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    @click="shareToWhatsApp"
                                    class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center gap-1.5 shadow-xs transition"
                                >
                                    <span>💬 WhatsApp</span>
                                </button>
                                <button
                                    type="button"
                                    @click="copyCurrentUrl"
                                    class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs flex items-center gap-1.5 transition"
                                >
                                    <span>{{
                                        isCopied
                                            ? "✓ Tersalin!"
                                            : "🔗 Salin Tautan"
                                    }}</span>
                                </button>
                            </div>
                        </div>
                    </main>

                    <!-- Kolom Kanan: Sidebar Widget (4 Kolom) -->
                    <aside class="lg:col-span-4 space-y-6">
                        <!-- Card Penulis / Divisi Pengelola -->
                        <div
                            class="p-6 rounded-3xl bg-white border border-slate-200/90 shadow-xs space-y-3"
                        >
                            <div
                                class="text-xs font-black text-orange-600 uppercase tracking-widest"
                            >
                                PENERBIT RESMI
                            </div>
                            <h3 class="text-base font-black text-slate-900">
                                HIMATIF POLINDRA
                            </h3>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Himpunan Mahasiswa Teknik Informatika Politeknik
                                Negeri Indramayu sebagai wadah publikasi
                                edukasi, event kompetisi, dan warta civitas
                                kampus.
                            </p>
                            <div class="pt-2">
                                <Link
                                    :href="route('informasi')"
                                    class="block w-full py-2.5 px-4 rounded-xl text-center bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition"
                                >
                                    Lihat Seluruh Arsip Warta
                                </Link>
                            </div>
                        </div>

                        <!-- Card Artikel Terkait -->
                        <div
                            class="p-6 rounded-3xl bg-white border border-slate-200/90 shadow-xs space-y-4"
                        >
                            <div
                                class="text-xs font-black text-slate-400 uppercase tracking-widest"
                            >
                                ARTIKEL TERKAIT LAINNYA
                            </div>

                            <div class="space-y-3 divide-y divide-slate-100">
                                <div
                                    v-for="rel in relatedArticles"
                                    :key="rel.id"
                                    class="pt-3 first:pt-0 space-y-1 group"
                                >
                                    <span
                                        class="text-[10px] font-bold text-orange-600 uppercase tracking-wider block"
                                    >
                                        {{ rel.category }}
                                    </span>
                                    <Link
                                        :href="route('informasi.show', rel.id)"
                                        class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-orange-600 transition line-clamp-2 block leading-snug"
                                    >
                                        {{ rel.title }}
                                    </Link>
                                    <span
                                        class="text-[11px] text-slate-400 block"
                                        >📅 {{ rel.date }}</span
                                    >
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
