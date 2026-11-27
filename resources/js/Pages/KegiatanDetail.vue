<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const props = defineProps({
    id: {
        type: Number,
        default: 1,
    },
    kegiatan: {
        type: Object,
        default: null,
    },
});

// Master Data Program Kerja HIMATIF (Placeholder Master Items)
const masterActivities = [
    {
        id: 1,
        title: "Nama Program Kerja Mendatang (Placeholder)",
        category: "Seminar & Edukasi",
        status: "akan_datang",
        statusLabel: "Akan Datang",
        statusBadgeClass: "bg-[#F95700] text-white",
        date: "DD Bulan YYYY",
        time: "08:00 - Selesai WIB",
        location: "Lokasi Pelaksanaan Kegiatan",
        image: null,
        placeholderIcon: "💡",
        placeholderLabel: "Poster Agenda",
        placeholderSub: "Agenda Placeholder #1",
        target: "Sasaran Peserta Kegiatan (Mahasiswa POLINDRA & Umum)",
        desc: "Program kerja ini dirancang untuk membekali mahasiswa dengan wawasan teknologi mutakhir, kompetensi praktis, dan jejaring profesional dalam menghadapi tantangan dunia industri digital.",
        pic: "Panitia Penyelenggara HIMATIF (+62 812-3456-7890)",
        organizer: "Divisi Riset dan Teknologi",
        documentationLinks: [
            {
                type: "drive",
                title: "Google Drive: Dokumentasi Foto Kegiatan",
                desc: "Folder arsip foto dokumentasi resmi resolusi tinggi selama kegiatan berlangsung.",
                url: "https://drive.google.com/drive/folders/placeholder-foto-kegiatan",
                icon: "📷",
                badge: "Google Drive",
                badgeClass: "bg-blue-50 text-blue-700 border-blue-200",
            },
        ],
        highlights: [
            "Pemahaman wawasan teknologi terapan langsung dari praktisi industri",
            "Sesi interaktif tanya jawab serta studi kasus implementasi riil",
            "E-Sertifikat resmi ber-SKP dan merchandise eksklusif kegiatan",
            "Peluang jejaring kolaborasi antarmahasiswa dan komunitas IT",
        ],
        actionText: "Daftar Kegiatan Sekarang",
    },
    {
        id: 3,
        title: "Nama Program Kerja Terlaksana (Placeholder 1)",
        category: "Kaderisasi & Pengenalan",
        status: "selesai",
        statusLabel: "Selesai Terlaksana",
        statusBadgeClass: "bg-emerald-600 text-white",
        date: "DD Bulan YYYY",
        time: "08:00 - Selesai WIB",
        location: "Gedung Direktorat & Lab TI POLINDRA",
        image: null,
        placeholderIcon: "🎓",
        placeholderLabel: "Dokumentasi Arsip",
        placeholderSub: "Arsip Placeholder #2",
        target: "Mahasiswa Baru Teknik Informatika POLINDRA",
        desc: "Arsip dokumentasi program kerja pengenalan kehidupan kampus dan kultur jurusan bagi mahasiswa baru guna menumbuhkan integritas, kebersamaan, dan wawasan akademik.",
        pic: "Divisi PSDM (Subdivisi Kaderisasi)",
        organizer: "Divisi Pengembangan Sumber Daya Manusia (PSDM)",
        documentationLinks: [
            {
                type: "drive",
                title: "Google Drive: Dokumentasi Foto PKPS TI",
                desc: "Folder arsip foto dokumentasi kegiatan pengenalan prodi dan inisiasi mahasiswa baru.",
                url: "https://drive.google.com/drive/folders/placeholder-foto-pkps",
                icon: "📷",
                badge: "Google Drive",
                badgeClass: "bg-blue-50 text-blue-700 border-blue-200",
            },
        ],
        highlights: [
            "Pengenalan etika akademik, sarana prasarana, dan sistem perkuliahan",
            "Pembangunan karakter, solidaritas angkatan, dan kepemimpinan mahasiswa",
            "Dokumentasi arsip foto, video, dan evaluasi ketercapaian program",
        ],
        actionText: "Lihat Arsip",
    },
    {
        id: 4,
        title: "Nama Program Kerja Terlaksana (Placeholder 2)",
        category: "Edukasi & Media",
        status: "selesai",
        statusLabel: "Selesai Terlaksana",
        statusBadgeClass: "bg-emerald-600 text-white",
        date: "DD Bulan YYYY",
        time: "19:30 - Selesai WIB",
        location: "Studio Multimedia HIMATIF (Streaming YouTube)",
        image: null,
        placeholderIcon: "🎙️",
        placeholderLabel: "Dokumentasi Arsip",
        placeholderSub: "Arsip Placeholder #3",
        target: "Mahasiswa & Pegiat Teknologi Informatika",
        desc: "Seri bincang inspiratif santai seputar tren rekayasa perangkat lunak, tips magang di startup, dan perjalanan karir alumni informatika di berbagai korporasi ternama.",
        pic: "Divisi Humas (Subdivisi INFOKOM)",
        organizer: "Divisi Humas HIMATIF",
        documentationLinks: [
            {
                type: "drive",
                title: "Google Drive: Dokumentasi Foto IT Cast Series",
                desc: "Folder arsip foto dokumentasi behind the scene sesi rekaman podcast.",
                url: "https://drive.google.com/drive/folders/placeholder-foto-podcast",
                icon: "📷",
                badge: "Google Drive",
                badgeClass: "bg-blue-50 text-blue-700 border-blue-200",
            },
        ],
        highlights: [
            "Wawasan seputar rekrutmen karir teknologi dan persiapan CV/portfolio",
            "Tips praktis adaptasi teknologi baru bagi mahasiswa aktif",
            "Rekaman tayangan ulang tersedia di kanal YouTube resmi HIMATIF",
        ],
        actionText: "Lihat Arsip",
    },
    {
        id: 5,
        title: "Nama Program Kerja Terlaksana (Placeholder 3)",
        category: "Kaderisasi & Organisasi",
        status: "selesai",
        statusLabel: "Selesai Terlaksana",
        statusBadgeClass: "bg-emerald-600 text-white",
        date: "DD Bulan YYYY",
        time: "07:30 - Selesai WIB",
        location: "Bumi Perkemahan & Aula Pertemuan Kampus",
        image: null,
        placeholderIcon: "👥",
        placeholderLabel: "Dokumentasi Arsip",
        placeholderSub: "Arsip Placeholder #4",
        target: "Pengurus & Calon Pengurus HIMATIF POLINDRA",
        desc: "Pelatihan manajemen mahasiswa tingkat dasar untuk mengasah kepemimpinan, manajemen waktu, dinamika kelompok, serta problem-solving dalam tata kelola organisasi.",
        pic: "Divisi PSDM (Subdivisi Kaderisasi)",
        organizer: "Divisi Pengembangan Sumber Daya Manusia (PSDM)",
        documentationLinks: [
            {
                type: "drive",
                title: "Google Drive: Dokumentasi Foto LKMM-D TI",
                desc: "Folder arsip foto simulasi sidang, dinamika kelompok, dan sesi outbound.",
                url: "https://drive.google.com/drive/folders/placeholder-foto-lkmmd",
                icon: "📷",
                badge: "Google Drive",
                badgeClass: "bg-blue-50 text-blue-700 border-blue-200",
            },
        ],
        highlights: [
            "Pembekalan soft skills kepemimpinan dan manajemen organisasi mahasiswa",
            "Simulasi penyusunan proposal kegiatan dan manajemen risiko event",
            "Evaluasi kepengurusan dan rencana tindak lanjut program kerja",
        ],
        actionText: "Lihat Arsip",
    },
];

// Cari kegiatan berdasarkan data database atau master placeholder
const currentKegiatan = computed(() => {
    if (props.kegiatan && props.kegiatan.kegiatan_id) {
        return {
            id: props.kegiatan.kegiatan_id,
            title: props.kegiatan.nama_kegiatan,
            category: props.kegiatan.kategori || "Kegiatan HIMATIF",
            status: props.kegiatan.status || "akan_datang",
            statusLabel:
                props.kegiatan.status === "selesai"
                    ? "Selesai Terlaksana"
                    : "Akan Datang",
            statusBadgeClass:
                props.kegiatan.status === "selesai"
                    ? "bg-emerald-600 text-white"
                    : "bg-[#F95700] text-white",
            date: props.kegiatan.tanggal || "DD Bulan YYYY",
            time: props.kegiatan.waktu || "08:00 - Selesai WIB",
            location: props.kegiatan.lokasi || "Kampus POLINDRA",
            image: props.kegiatan.foto || null,
            placeholderIcon: "📋",
            placeholderLabel:
                props.kegiatan.status === "selesai"
                    ? "DOKUMENTASI ARSIP"
                    : "POSTER KEGIATAN",
            placeholderSub: props.kegiatan.nama_kegiatan,
            target: props.kegiatan.sasaran || "Mahasiswa Teknik Informatika",
            desc:
                props.kegiatan.deskripsi ||
                "Deskripsi kegiatan belum tersedia.",
            pic: props.kegiatan.pic || "Panitia HIMATIF",
            organizer: "Pengurus HIMATIF POLINDRA",
            documentationLinks: [
                {
                    type: "drive",
                    title: "Google Drive: Dokumentasi Foto Kegiatan",
                    desc: "Folder arsip foto dokumentasi resmi resolusi tinggi selama kegiatan.",
                    url: "https://drive.google.com/drive/folders/placeholder-foto-kegiatan",
                    icon: "📷",
                    badge: "Google Drive",
                    badgeClass: "bg-blue-50 text-blue-700 border-blue-200",
                },
            ],
            highlights: [
                "Wadah pengembangan kompetensi dan kepemimpinan mahasiswa",
                "E-Sertifikat dan dokumentasi resmi kegiatan",
            ],
            actionText:
                props.kegiatan.status === "selesai"
                    ? "Lihat Detail"
                    : "Daftar Kegiatan",
        };
    }

    const found = masterActivities.find((item) => item.id === Number(props.id));
    return found || masterActivities[0];
});

// Program Kerja Lainnya (Rekomendasi)
const otherActivities = computed(() => {
    return masterActivities
        .filter((item) => item.id !== currentKegiatan.value.id)
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
    const title = encodeURIComponent(currentKegiatan.value.title);
    const url = encodeURIComponent(window.location.href);
    window.open(
        `https://api.whatsapp.com/send?text=${title}%20-%20${url}`,
        "_blank",
    );
};
</script>

<template>
    <Head :title="`${currentKegiatan.title} - HIMATIF POLINDRA`" />

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
                            :href="route('kegiatan.index')"
                            class="hover:text-orange-600 transition"
                            >Kegiatan</Link
                        >
                        <span>/</span>
                        <span
                            class="text-slate-800 font-semibold truncate max-w-[200px] sm:max-w-xs"
                        >
                            {{ currentKegiatan.title }}
                        </span>
                    </nav>

                    <Link
                        :href="route('kegiatan.index')"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:text-orange-600 hover:border-orange-300 shadow-xs transition"
                    >
                        <span>←</span>
                        <span>Kembali ke Semua Kegiatan</span>
                    </Link>
                </div>

                <!-- 2. Header Kegiatan -->
                <header class="space-y-4 text-left">
                    <!-- Badges -->
                    <div class="flex flex-wrap items-center gap-2">
                        <span
                            class="px-3.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider shadow-xs"
                            :class="
                                currentKegiatan.status === 'akan_datang'
                                    ? 'bg-[#F95700] text-white'
                                    : 'bg-emerald-600 text-white'
                            "
                        >
                            {{ currentKegiatan.statusLabel }}
                        </span>
                        <span
                            class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-slate-900 text-white border border-slate-800"
                        >
                            {{ currentKegiatan.category }}
                        </span>
                    </div>

                    <!-- Judul Besar Program Kerja -->
                    <h1
                        class="text-2xl sm:text-4xl lg:text-[40px] font-black text-slate-900 tracking-tight leading-[1.2]"
                    >
                        {{ currentKegiatan.title }}
                    </h1>

                    <!-- Ringkasan Info Cepat (Tanggal, Waktu, Lokasi, Sasaran) -->
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pt-3 pb-2 text-xs font-semibold text-slate-700"
                    >
                        <div
                            class="flex items-center gap-2.5 p-3 rounded-2xl bg-white border border-slate-200/90 shadow-2xs"
                        >
                            <span class="text-lg">📅</span>
                            <div>
                                <div
                                    class="text-[10px] text-slate-400 uppercase tracking-wider"
                                >
                                    Tanggal Acara
                                </div>
                                <div class="font-bold text-slate-800">
                                    {{ currentKegiatan.date }}
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex items-center gap-2.5 p-3 rounded-2xl bg-white border border-slate-200/90 shadow-2xs"
                        >
                            <span class="text-lg">⏰</span>
                            <div>
                                <div
                                    class="text-[10px] text-slate-400 uppercase tracking-wider"
                                >
                                    Waktu Pelaksanaan
                                </div>
                                <div class="font-bold text-slate-800">
                                    {{ currentKegiatan.time }}
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex items-center gap-2.5 p-3 rounded-2xl bg-white border border-slate-200/90 shadow-2xs"
                        >
                            <span class="text-lg">📍</span>
                            <div class="truncate">
                                <div
                                    class="text-[10px] text-slate-400 uppercase tracking-wider"
                                >
                                    Lokasi Kegiatan
                                </div>
                                <div
                                    class="font-bold text-slate-800 truncate"
                                    :title="currentKegiatan.location"
                                >
                                    {{ currentKegiatan.location }}
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex items-center gap-2.5 p-3 rounded-2xl bg-white border border-slate-200/90 shadow-2xs"
                        >
                            <span class="text-lg">🎯</span>
                            <div class="truncate">
                                <div
                                    class="text-[10px] text-slate-400 uppercase tracking-wider"
                                >
                                    Target Sasaran
                                </div>
                                <div
                                    class="font-bold text-slate-800 truncate"
                                    :title="currentKegiatan.target"
                                >
                                    {{ currentKegiatan.target }}
                                </div>
                            </div>
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
                            v-if="currentKegiatan.image"
                            :src="currentKegiatan.image"
                            :alt="currentKegiatan.title"
                            class="w-full h-full object-cover"
                        />
                        <div
                            v-else
                            class="flex flex-col items-center justify-center text-center p-8"
                        >
                            <span class="text-6xl mb-2">{{
                                currentKegiatan.placeholderIcon || "📋"
                            }}</span>
                            <span
                                class="text-base sm:text-lg font-black tracking-wider uppercase text-white"
                            >
                                {{
                                    currentKegiatan.placeholderLabel ||
                                    "DOKUMENTASI ARSIP"
                                }}
                            </span>
                            <span
                                class="text-xs sm:text-sm text-slate-400 mt-1 max-w-md"
                            >
                                {{
                                    currentKegiatan.placeholderSub ||
                                    currentKegiatan.title
                                }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- 4. Konten Utama Kegiatan (2 Kolom di Desktop) -->
                <div
                    class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 text-left pt-2"
                >
                    <!-- Kolom Kiri: Ulasan, Link Dokumentasi, Manfaat -->
                    <main class="lg:col-span-8 space-y-6">
                        <!-- Box Ringkasan Acara -->
                        <div
                            class="p-5 sm:p-6 rounded-3xl bg-orange-50/80 border border-orange-200/60 text-slate-800 space-y-2"
                        >
                            <div
                                class="text-xs font-black uppercase tracking-wider text-orange-600"
                            >
                                Tentang Program Kerja
                            </div>
                            <p
                                class="text-xs sm:text-sm leading-relaxed text-slate-700"
                            >
                                {{ currentKegiatan.desc }}
                            </p>
                        </div>

                        <!-- Tautan Dokumentasi Foto Kegiatan (Khusus Google Drive) -->
                        <div
                            class="p-5 sm:p-6 rounded-3xl bg-white border border-slate-200/90 shadow-xs space-y-4"
                        >
                            <div
                                class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 pb-3"
                            >
                                <div>
                                    <h3
                                        class="text-base font-black text-slate-900 flex items-center gap-2"
                                    >
                                        <svg
                                            class="w-5 h-5 text-blue-600 shrink-0"
                                            viewBox="0 0 24 24"
                                            fill="currentColor"
                                        >
                                            <path
                                                d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM19 18H6c-2.21 0-4-1.79-4-4 0-2.05 1.53-3.76 3.56-3.97l1.07-.11.5-.95C8.08 7.14 9.94 6 12 6c2.62 0 4.88 1.86 5.39 4.43l.3 1.5 1.53.11c1.56.1 2.78 1.41 2.78 2.96 0 1.65-1.35 3-3 3z"
                                            />
                                        </svg>
                                        <span
                                            >Dokumentasi Foto Kegiatan (Google
                                            Drive)</span
                                        >
                                    </h3>
                                    <p class="text-xs text-slate-400 mt-0.5">
                                        Akses galeri foto dokumentasi resmi
                                        kegiatan melalui tautan Google Drive
                                    </p>
                                </div>
                                <span
                                    class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200 flex items-center gap-1.5"
                                >
                                    <span
                                        class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"
                                    ></span>
                                    Google Drive
                                </span>
                            </div>

                            <!-- Link Dokumentasi Foto Google Drive Placeholder -->
                            <div class="space-y-3">
                                <a
                                    v-for="(
                                        link, idx
                                    ) in currentKegiatan.documentationLinks"
                                    :key="idx"
                                    :href="link.url"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="flex items-center justify-between p-3.5 sm:p-4 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-blue-400 hover:bg-blue-50/20 transition group"
                                >
                                    <div
                                        class="flex items-center gap-3.5 min-w-0"
                                    >
                                        <div
                                            class="w-10 h-10 rounded-xl bg-white border border-slate-200 shadow-2xs flex items-center justify-center text-xl shrink-0 group-hover:scale-105 group-hover:border-blue-300 transition"
                                        >
                                            {{ link.icon }}
                                        </div>
                                        <div class="min-w-0">
                                            <div
                                                class="flex items-center gap-2"
                                            >
                                                <span
                                                    class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-blue-600 transition truncate"
                                                >
                                                    {{ link.title }}
                                                </span>
                                                <span
                                                    class="text-[10px] font-bold px-2 py-0.5 rounded-md border shrink-0 bg-blue-50 text-blue-700 border-blue-200 hidden sm:inline-block"
                                                >
                                                    Google Drive
                                                </span>
                                            </div>
                                            <p
                                                class="text-[11px] text-slate-500 truncate mt-0.5"
                                            >
                                                {{ link.desc }}
                                            </p>
                                            <span
                                                class="text-[10px] text-blue-600 font-mono font-medium truncate block mt-0.5"
                                            >
                                                {{ link.url }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="shrink-0 ml-3">
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 group-hover:bg-blue-600 group-hover:text-white group-hover:border-transparent transition shadow-2xs"
                                        >
                                            <span>Buka Drive</span>
                                            <span class="text-xs">↗</span>
                                        </span>
                                    </div>
                                </a>
                            </div>

                            <!-- Catatan Tambahan Placeholder -->
                            <div
                                class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/60 flex items-center gap-2.5 text-xs text-slate-500"
                            >
                                <span class="text-base">💡</span>
                                <span>
                                    Tautan di atas merupakan placeholder folder
                                    Google Drive untuk dokumentasi foto
                                    kegiatan. Tautan folder resmi akan
                                    diperbarui secara langsung oleh pengurus
                                    HIMATIF POLINDRA.
                                </span>
                            </div>
                        </div>

                        <!-- Poin Utama & Manfaat Kegiatan -->
                        <div
                            class="p-5 sm:p-6 rounded-3xl bg-white border border-slate-200/90 shadow-xs space-y-3"
                        >
                            <h3
                                class="text-base font-black text-slate-900 flex items-center gap-2"
                            >
                                <span>🌟</span>
                                <span>Manfaat & Target Luaran Program</span>
                            </h3>
                            <ul
                                class="space-y-2.5 text-xs sm:text-sm text-slate-600"
                            >
                                <li
                                    v-for="(
                                        hl, idx
                                    ) in currentKegiatan.highlights || [
                                        'Meningkatkan pemahaman praktis di bidang teknologi',
                                        'Mendapatkan e-sertifikat resmi HIMATIF POLINDRA',
                                        'Membangun jejaring kolaborasi profesional',
                                    ]"
                                    :key="idx"
                                    class="flex items-start gap-2.5"
                                >
                                    <span
                                        class="text-emerald-500 font-bold text-base leading-none"
                                        >✓</span
                                    >
                                    <span>{{ hl }}</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Bagikan Kegiatan -->
                        <div
                            class="p-5 rounded-3xl bg-slate-50 border border-slate-200/80 flex flex-wrap items-center justify-between gap-4"
                        >
                            <div>
                                <div class="text-xs font-bold text-slate-800">
                                    Bagikan Program Kerja Ini
                                </div>
                                <div class="text-[11px] text-slate-400">
                                    Ajak rekan dan mahasiswa lain untuk ikut
                                    berpartisipasi
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    @click="copyCurrentUrl"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:border-slate-400 transition shadow-2xs"
                                >
                                    <span>{{ isCopied ? "✓" : "🔗" }}</span>
                                    <span>{{
                                        isCopied ? "Tersalin!" : "Salin Link"
                                    }}</span>
                                </button>
                                <button
                                    type="button"
                                    @click="shareToWhatsApp"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-2xs"
                                >
                                    <span>💬</span>
                                    <span>WhatsApp</span>
                                </button>
                            </div>
                        </div>
                    </main>

                    <!-- Kolom Kanan: Status Pendaftaran, Narahubung & Kegiatan Lainnya -->
                    <aside class="lg:col-span-4 space-y-6">
                        <!-- Card Status & Pendaftaran -->
                        <div
                            class="p-5 sm:p-6 rounded-3xl bg-white border border-slate-200/90 shadow-md space-y-4"
                        >
                            <div>
                                <span
                                    class="text-[10px] font-black uppercase tracking-wider text-slate-400"
                                    >Status Registrasi</span
                                >
                                <div
                                    class="text-lg font-black text-slate-900 mt-0.5"
                                >
                                    {{
                                        currentKegiatan.status === "akan_datang"
                                            ? "Pendaftaran Dibuka"
                                            : "Kegiatan Terlaksana"
                                    }}
                                </div>
                            </div>

                            <p class="text-xs text-slate-500 leading-relaxed">
                                {{
                                    currentKegiatan.status === "akan_datang"
                                        ? "Pastikan kamu mendaftar sebelum kuota kursi peserta terpenuhi atau sebelum batas waktu pendaftaran ditutup."
                                        : "Kegiatan ini telah selesai dilaksanakan. Kamu dapat melihat ringkasan serta dokumentasi arsip pelaksanaan di halaman ini."
                                }}
                            </p>

                            <div
                                v-if="currentKegiatan.status === 'akan_datang'"
                            >
                                <button
                                    type="button"
                                    @click="shareToWhatsApp"
                                    class="w-full py-3 px-4 rounded-2xl bg-[#F95700] hover:bg-orange-600 text-white font-black text-xs sm:text-sm transition shadow-md active:scale-95 text-center block"
                                >
                                    {{
                                        currentKegiatan.actionText ||
                                        "Daftar Kegiatan Sekarang"
                                    }}
                                    →
                                </button>
                            </div>
                            <div v-else>
                                <div
                                    class="w-full py-3 px-4 rounded-2xl bg-slate-100 text-slate-500 font-bold text-xs text-center border border-slate-200"
                                >
                                    ✓ Selesai Terlaksana
                                </div>
                            </div>
                        </div>

                        <!-- Card Narahubung / PIC Panitia -->
                        <div
                            class="p-5 rounded-3xl bg-white border border-slate-200/90 shadow-xs space-y-3"
                        >
                            <h4
                                class="text-xs font-black uppercase tracking-wider text-slate-500"
                            >
                                Narahubung & Penyelenggara
                            </h4>

                            <div class="space-y-2 text-xs">
                                <div>
                                    <span
                                        class="text-slate-400 block text-[11px]"
                                        >Penyelenggara:</span
                                    >
                                    <span class="font-bold text-slate-800">{{
                                        currentKegiatan.organizer ||
                                        "HIMATIF POLINDRA"
                                    }}</span>
                                </div>
                                <div>
                                    <span
                                        class="text-slate-400 block text-[11px]"
                                        >Kontak PIC / Panitia:</span
                                    >
                                    <span class="font-bold text-orange-600">{{
                                        currentKegiatan.pic
                                    }}</span>
                                </div>
                                <div>
                                    <span
                                        class="text-slate-400 block text-[11px]"
                                        >Sekretariat:</span
                                    >
                                    <span class="font-medium text-slate-600"
                                        >Gedung Student Center POLINDRA Lt.
                                        2</span
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Kegiatan Lainnya -->
                        <div
                            class="p-5 rounded-3xl bg-white border border-slate-200/90 shadow-xs space-y-4"
                        >
                            <h4
                                class="text-xs font-black uppercase tracking-wider text-slate-500"
                            >
                                Program Kerja Lainnya
                            </h4>

                            <div class="space-y-3">
                                <Link
                                    v-for="act in otherActivities"
                                    :key="act.id"
                                    :href="route('kegiatan.show', act.id)"
                                    class="group block p-2.5 rounded-2xl hover:bg-slate-50 transition border border-transparent hover:border-slate-200"
                                >
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-12 h-12 rounded-xl bg-slate-800 flex items-center justify-center text-lg shrink-0 text-white"
                                        >
                                            {{ act.placeholderIcon || "📋" }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div
                                                class="text-[10px] font-bold text-orange-600 truncate"
                                            >
                                                {{ act.category }}
                                            </div>
                                            <div
                                                class="text-xs font-bold text-slate-900 group-hover:text-orange-600 transition truncate"
                                            >
                                                {{ act.title }}
                                            </div>
                                            <div
                                                class="text-[10px] text-slate-400"
                                            >
                                                {{ act.date }}
                                            </div>
                                        </div>
                                    </div>
                                </Link>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
