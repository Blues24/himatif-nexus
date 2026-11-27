<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const props = defineProps({
    products: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({ q: "" }),
    },
    lowStock: {
        type: Array,
        default: () => [],
    },
});

// State Kategori, Filter, Pencarian, & Urutan
const activeCategory = ref("Semua");
const searchQuery = ref(props.filters?.q || "");
const sortBy = ref("terpopuler"); // 'terpopuler', 'termurah', 'termahal'

const categories = [
    "Semua",
    "Kaos & Pakaian",
    "Aksesoris & Lanyard",
    "Stiker & Souvenir",
    "Jaket & Hoodie",
];

// Data Master Merchandise HIMATIF (8 Item Placeholder Grid 4 Kolom)
const masterMerch = [
    {
        id: 101,
        title: "Nama Produk Souvenir & Aksesoris (Placeholder 1)",
        category: "Stiker & Souvenir",
        price: 15000,
        priceFormatted: "Rp 15.000",
        stock: 50,
        stockStatus: "Ready Stock",
        badge: "Placeholder",
        badgeClass: "bg-[#F95700] text-white",
        image: null,
        placeholderIcon: "🔑",
        placeholderLabel: "DOKUMENTASI MERCH",
        placeholderSub: "Merchandise Placeholder #1",
        hasSize: false,
        desc: "Deskripsi ringkas spesifikasi dan detail produk merchandise souvenir resmi HIMATIF POLINDRA yang akan segera dirilis.",
        features: [
            "Material Kualitas Pilihan",
            "Desain Maskot Resmi HIMATIF",
            "Stok Tersedia di Sekretariat",
        ],
    },
    {
        id: 102,
        title: "Nama Produk Kaos & Apparel (Placeholder 2)",
        category: "Kaos & Pakaian",
        price: 85000,
        priceFormatted: "Rp 85.000",
        stock: 25,
        stockStatus: "Ready Stock",
        badge: "Placeholder",
        badgeClass: "bg-emerald-600 text-white",
        image: null,
        placeholderIcon: "👕",
        placeholderLabel: "DOKUMENTASI MERCH",
        placeholderSub: "Merchandise Placeholder #2",
        hasSize: true,
        sizes: ["S", "M", "L", "XL", "XXL"],
        desc: "Deskripsi ringkas bahan kain, pola potongan, sablon grafis, dan varian ukuran pakaian resmi mahasiswa Teknik Informatika.",
        features: [
            "100% Cotton Combed Premium",
            "Pola Regular Fit Unisex",
            "Tersedia Pilihan Ukuran S - XXL",
        ],
    },
    {
        id: 103,
        title: "Nama Produk Lanyard & ID Card (Placeholder 3)",
        category: "Aksesoris & Lanyard",
        price: 25000,
        priceFormatted: "Rp 25.000",
        stock: 40,
        stockStatus: "Ready Stock",
        badge: "Placeholder",
        badgeClass: "bg-amber-500 text-white",
        image: null,
        placeholderIcon: "🪪",
        placeholderLabel: "DOKUMENTASI MERCH",
        placeholderSub: "Merchandise Placeholder #3",
        hasSize: false,
        desc: "Deskripsi ringkas spesifikasi bahan tali lanyard printing, tipe kait logam, dan kelengkapan card holder identitas kampus.",
        features: [
            "Bahan Satin Halus Printing 2 Sisi",
            "Kait Logam Anti Karat",
            "Termasuk Card Case Transparan",
        ],
    },
    {
        id: 104,
        title: "Nama Produk Jaket & Outerwear (Placeholder 4)",
        category: "Jaket & Hoodie",
        price: 175000,
        priceFormatted: "Rp 175.000",
        stock: 15,
        stockStatus: "Pre-Order Buka",
        badge: "Placeholder",
        badgeClass: "bg-indigo-600 text-white",
        image: null,
        placeholderIcon: "🧥",
        placeholderLabel: "DOKUMENTASI MERCH",
        placeholderSub: "Merchandise Placeholder #4",
        hasSize: true,
        sizes: ["M", "L", "XL", "XXL"],
        desc: "Deskripsi ringkas ketebalan bahan kain, resleting, aksen bordir minimalis, dan saku jaket hangat untuk kegiatan mahasiswa.",
        features: [
            "Bahan Cotton Fleece Tebal & Hangat",
            "Resleting Besi YKK Berkualitas",
            "Pola Nyaman untuk Pria & Wanita",
        ],
    },
    {
        id: 105,
        title: "Nama Paket Stiker Laptop Dev (Placeholder 5)",
        category: "Stiker & Souvenir",
        price: 15000,
        priceFormatted: "Rp 15.000",
        stock: 60,
        stockStatus: "Ready Stock",
        badge: "Placeholder",
        badgeClass: "bg-emerald-600 text-white",
        image: null,
        placeholderIcon: "✨",
        placeholderLabel: "DOKUMENTASI MERCH",
        placeholderSub: "Merchandise Placeholder #5",
        hasSize: false,
        desc: "Deskripsi ringkas bahan stiker vinyl anti air dan anti gores dengan berbagai grafis teknologi dan maskot himpunan.",
        features: [
            "Bahan Vinyl Anti Air & Tahan Cuci",
            "Laminasi Doff Anti Gores",
            "Die-Cut Rapi Tinggal Tempel",
        ],
    },
    {
        id: 106,
        title: "Nama Produk Tas & Tote Bag (Placeholder 6)",
        category: "Aksesoris & Lanyard",
        price: 45000,
        priceFormatted: "Rp 45.000",
        stock: 20,
        stockStatus: "Ready Stock",
        badge: "Placeholder",
        badgeClass: "bg-slate-900 text-white",
        image: null,
        placeholderIcon: "👜",
        placeholderLabel: "DOKUMENTASI MERCH",
        placeholderSub: "Merchandise Placeholder #6",
        hasSize: false,
        desc: "Deskripsi ringkas material kain kanvas kuat, kapasitas muat laptop kuliah, dan tipe resleting penutup utama tas jinjing.",
        features: [
            "Material Kanvas Tebal Berkualitas",
            "Resleting Penutup Utama Aman",
            "Muat Laptop Hingga 15 Inci",
        ],
    },
    {
        id: 107,
        title: "Nama Produk Botol Minum Tumbler (Placeholder 7)",
        category: "Stiker & Souvenir",
        price: 65000,
        priceFormatted: "Rp 65.000",
        stock: 15,
        stockStatus: "Ready Stock",
        badge: "Placeholder",
        badgeClass: "bg-[#F95700] text-white",
        image: null,
        placeholderIcon: "🥤",
        placeholderLabel: "DOKUMENTASI MERCH",
        placeholderSub: "Merchandise Placeholder #7",
        hasSize: false,
        desc: "Deskripsi ringkas kapasitas volume botol termos stainless steel, insulasi penahan suhu panas/dingin, dan grafir logo HIMATIF.",
        features: [
            "Stainless Steel SUS 304 Food Grade",
            "Tahan Panas/Dingin hingga 12 Jam",
            "Kapasitas 500ml Pas di Tas Ransel",
        ],
    },
    {
        id: 108,
        title: "Nama Produk Topi & Headwear (Placeholder 8)",
        category: "Aksesoris & Lanyard",
        price: 40000,
        priceFormatted: "Rp 40.000",
        stock: 20,
        stockStatus: "Ready Stock",
        badge: "Placeholder",
        badgeClass: "bg-slate-900 text-white",
        image: null,
        placeholderIcon: "🧢",
        placeholderLabel: "DOKUMENTASI MERCH",
        placeholderSub: "Merchandise Placeholder #8",
        hasSize: false,
        desc: "Deskripsi ringkas material kain katun twill tebal, aksen bordir timbul inisial himpunan, dan pengatur ukuran lingkar kepala.",
        features: [
            "Bahan Katun Twill Tebal Berkualitas",
            "Bordir Timbul Rapi & Awet",
            "Gesper Pengatur Logam Belakang",
        ],
    },
];

// Gabungkan Produk DB jika ada dengan Master Merch
const allProducts = computed(() => {
    if (props.products && props.products.length > 0) {
        return props.products.map((p) => ({
            id: p.produk_id,
            title: p.nama_produk,
            category: "Kaos & Pakaian",
            price: Number(p.harga_produk),
            priceFormatted: `Rp ${Number(p.harga_produk).toLocaleString("id-ID")}`,
            stock: p.stok_produk,
            stockStatus: p.stok_produk > 0 ? "Ready Stock" : "Habis",
            badge: p.stok_produk > 0 ? "Tersedia" : "Habis",
            badgeClass:
                p.stok_produk > 0
                    ? "bg-emerald-600 text-white"
                    : "bg-red-600 text-white",
            image: p.gambar_produk ? `/storage/${p.gambar_produk}` : null,
            placeholderIcon: "🛍️",
            placeholderLabel: "OFFICIAL MERCH",
            placeholderSub: "Danus HIMATIF",
            hasSize: false,
            desc: `Produk resmi merchandise HIMATIF POLINDRA dengan stok ${p.stok_produk} unit.`,
            features: [
                "Kualitas Terjamin",
                "Pengambilan Langsung di Sekre HIMATIF",
            ],
        }));
    }
    return masterMerch;
});

// Filter & Sortir Produk
const filteredProducts = computed(() => {
    let result = [...allProducts.value];

    // Filter Kategori
    if (activeCategory.value !== "Semua") {
        result = result.filter(
            (item) => item.category === activeCategory.value,
        );
    }

    // Filter Pencarian
    if (searchQuery.value.trim() !== "") {
        const q = searchQuery.value.toLowerCase();
        result = result.filter(
            (item) =>
                item.title.toLowerCase().includes(q) ||
                item.desc.toLowerCase().includes(q) ||
                item.category.toLowerCase().includes(q),
        );
    }

    // Sortir
    if (sortBy.value === "termurah") {
        result.sort((a, b) => a.price - b.price);
    } else if (sortBy.value === "termahal") {
        result.sort((a, b) => b.price - a.price);
    }

    return result;
});

// State Modal Detail & Order WhatsApp
const selectedProduct = ref(null);
const isModalOpen = ref(false);
const orderQuantity = ref(1);
const selectedSize = ref("L");
const buyerName = ref("");
const buyerNim = ref("");
const buyerNotes = ref("");

const openOrderModal = (item) => {
    selectedProduct.value = item;
    orderQuantity.value = 1;
    selectedSize.value = item.sizes?.[1] || "L";
    buyerName.value = "";
    buyerNim.value = "";
    buyerNotes.value = "";
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    selectedProduct.value = null;
};

// Hitung Total Belanja
const totalPrice = computed(() => {
    if (!selectedProduct.value) return 0;
    return selectedProduct.value.price * orderQuantity.value;
});

const totalPriceFormatted = computed(() => {
    return `Rp ${totalPrice.value.toLocaleString("id-ID")}`;
});

// Kirim Order Langsung ke WhatsApp Danus HIMATIF
const checkoutWhatsApp = () => {
    if (!selectedProduct.value) return;

    const noWaAdmin = "6281234567890"; // Nomor WhatsApp Danus HIMATIF
    const p = selectedProduct.value;
    const sizeInfo = p.hasSize ? `%0A• Ukuran: *${selectedSize.value}*` : "";
    const noteInfo = buyerNotes.value
        ? `%0A• Catatan: _${buyerNotes.value}_`
        : "";
    const identitas = buyerName.value
        ? `%0A• Nama: *${buyerName.value}* (NIM/Kelas: ${buyerNim.value || "-"})`
        : "";

    const text =
        `Halo Admin Danus HIMATIF!%0ASaya ingin memesan merchandise resmi berikut:%0A%0A` +
        `• Produk: *${p.title}*${sizeInfo}%0A` +
        `• Jumlah: *${orderQuantity.value} Pcs*%0A` +
        `• Total Harga: *${totalPriceFormatted.value}*${identitas}${noteInfo}%0A%0A` +
        `Mohon info ketersediaan dan prosedur pembayarannya. Terima kasih!`;

    window.open(`https://wa.me/${noWaAdmin}?text=${text}`, "_blank");
};
</script>

<template>
    <Head title="Danus Shop & Official Merchandise - HIMATIF POLINDRA" />

    <AuthenticatedLayout>
        <div
            class="bg-gradient-to-b from-white via-slate-50 to-slate-100 min-h-screen pb-20"
        >
            <!-- Container Utama -->
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8 space-y-10 sm:space-y-12"
            >
                <!-- 1. Featured Dark Banner Hero (Sesuai Desain Konsisten HIMATIF NEXUS) -->
                <div
                    class="relative overflow-hidden rounded-3xl sm:rounded-[36px] p-6 sm:p-10 lg:p-12 text-white shadow-2xl shadow-slate-900/40 border border-slate-800"
                    style="background-color: #13151d"
                >
                    <div
                        class="flex flex-col lg:flex-row items-center justify-between gap-8 lg:gap-12"
                    >
                        <!-- Kolom Kiri: Teks & Aksi -->
                        <div class="flex-1 w-full space-y-5 text-left">
                            <!-- Pill Badge Merch -->
                            <div
                                class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 border border-white/15 shadow-inner"
                            >
                                <span class="text-xs">🛍️</span>
                                <span
                                    class="text-[11px] sm:text-xs font-black text-[#F95700] tracking-wider uppercase"
                                >
                                    DANA USAHA & OFFICIAL MERCHANDISE HIMATIF
                                </span>
                            </div>

                            <!-- Judul Besar -->
                            <h1
                                class="text-3xl sm:text-4xl lg:text-[42px] font-black text-white tracking-tight leading-[1.18]"
                            >
                                Official Apparel & Merchandise Mahasiswa
                                Informatika<br class="hidden sm:inline" />
                            </h1>

                            <!-- Deskripsi Singkat -->
                            <p
                                class="text-xs sm:text-sm text-slate-300 font-normal leading-relaxed max-w-xl"
                            >
                                Dapatkan t-shirt eksklusif, jaket hoodie,
                                lanyard satin, dan berbagai aksesoris identitas
                                mahasiswa Teknik Informatika POLINDRA. Setiap
                                pembelian mendukung operasional kegiatan
                                himpunan.
                            </p>

                            <!-- Tombol CTA & Status Toko -->
                            <div class="flex flex-wrap items-center gap-3 pt-2">
                                <a
                                    href="#katalog-merch"
                                    class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-xs sm:text-sm text-white bg-[#F95700] hover:bg-orange-600 shadow-md shadow-orange-500/20 transition transform active:scale-95 select-none"
                                >
                                    <span>Jelajahi Produk</span>
                                    <span>↓</span>
                                </a>

                                <div
                                    class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-white/10 border border-white/15 text-xs sm:text-sm text-slate-200 font-medium"
                                >
                                    <span
                                        class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"
                                    ></span>
                                    <span
                                        >Order Online:
                                        <strong
                                            >WhatsApp Danus Buka</strong
                                        ></span
                                    >
                                </div>

                                <!-- Tombol Kelola Produk jika Admin -->
                                <Link
                                    v-if="
                                        $page.props.auth &&
                                        $page.props.auth.user
                                    "
                                    :href="route('produk.create')"
                                    class="inline-flex items-center gap-1.5 px-4 py-3 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-xs sm:text-sm text-orange-400 font-bold transition"
                                >
                                    <span>+ Tambah Produk</span>
                                </Link>
                            </div>
                        </div>

                        <!-- Kolom Kanan: Card Sorotan Merch Terpopuler -->
                        <div class="w-full lg:w-[380px] shrink-0">
                            <div
                                class="bg-[#212430]/90 backdrop-blur-md border border-slate-700/60 rounded-2xl p-5 sm:p-6 shadow-xl text-left space-y-3"
                            >
                                <div>
                                    <span
                                        class="text-[11px] font-black tracking-widest text-[#F95700] uppercase block"
                                    >
                                        SOROTAN PRODUK MERCHANDISE
                                    </span>
                                    <h3
                                        class="text-lg font-black text-white mt-0.5"
                                    >
                                        KATALOG MERCH 2026
                                    </h3>
                                </div>

                                <!-- Baris Sorotan -->
                                <div
                                    class="divide-y divide-white/10 text-xs sm:text-[13px] pt-1"
                                >
                                    <div
                                        class="py-2.5 flex items-center justify-between text-slate-200"
                                    >
                                        <span class="font-medium"
                                            >Kaos & Apparel (Placeholder)</span
                                        >
                                        <span class="font-bold text-orange-400"
                                            >Segera Hadir</span
                                        >
                                    </div>
                                    <div
                                        class="py-2.5 flex items-center justify-between text-slate-200"
                                    >
                                        <span class="font-medium"
                                            >Aksesoris & Lanyard
                                            (Placeholder)</span
                                        >
                                        <span class="font-bold text-orange-400"
                                            >Segera Hadir</span
                                        >
                                    </div>
                                    <div
                                        class="py-2.5 flex items-center justify-between text-slate-200"
                                    >
                                        <span class="font-medium"
                                            >Stiker & Souvenir
                                            (Placeholder)</span
                                        >
                                        <span class="font-bold text-orange-400"
                                            >Segera Hadir</span
                                        >
                                    </div>
                                </div>

                                <!-- Info Pengambilan -->
                                <div class="pt-2">
                                    <div
                                        class="p-2.5 rounded-xl bg-white/5 border border-white/10 text-[11px] text-slate-300 flex items-center justify-between"
                                    >
                                        <span>📍 Ambil di Sekre HIMATIF</span>
                                        <span class="text-emerald-400 font-bold"
                                            >Bebas Ongkir</span
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Section Katalog Produk Merchandise -->
                <div id="katalog-merch" class="space-y-6 pt-2">
                    <!-- Header Section & Search Bar -->
                    <div
                        class="flex flex-col md:flex-row md:items-end justify-between gap-4"
                    >
                        <div>
                            <span
                                class="text-xs font-black tracking-widest text-[#F95700] uppercase block mb-1"
                            >
                                KATALOG DANA USAHA
                            </span>
                            <h2
                                class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight"
                            >
                                Koleksi Merchandise HIMATIF
                            </h2>
                            <p
                                class="text-xs sm:text-sm text-slate-500 mt-1 max-w-xl"
                            >
                                Pilih apparel, seragam kemeja, kaos, dan
                                perlengkapan identitas favorit kamu dengan harga
                                mahasiswa yang terjangkau.
                            </p>
                        </div>

                        <!-- Kotak Pencarian & Sortir -->
                        <div class="flex items-center gap-3 w-full md:w-auto">
                            <div class="w-full md:w-72 relative">
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Cari kaos, hoodie, lanyard, souvenir..."
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

                            <select
                                v-model="sortBy"
                                class="py-2.5 pl-3 pr-8 rounded-full border border-slate-200 text-xs font-bold text-slate-700 bg-white focus:outline-none focus:border-orange-500 shadow-xs transition"
                            >
                                <option value="terpopuler">Terpopuler</option>
                                <option value="termurah">Termurah</option>
                                <option value="termahal">Termahal</option>
                            </select>
                        </div>
                    </div>

                    <!-- Filter Kategori Pills -->
                    <div
                        class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none select-none"
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

                    <!-- 3. Grid Kartu Produk (4 Kolom Sesuai Standar HIMATIF NEXUS) -->
                    <div
                        v-if="filteredProducts.length > 0"
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6"
                    >
                        <div
                            v-for="item in filteredProducts"
                            :key="item.id"
                            class="bg-white rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-xl hover:border-orange-300 transition-all duration-300 overflow-hidden flex flex-col justify-between group"
                        >
                            <div>
                                <!-- Thumbnail Gambar / Placeholder Gambar Elegan -->
                                <div class="p-3 pb-0">
                                    <div
                                        class="relative overflow-hidden rounded-xl aspect-[16/11] bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center"
                                    >
                                        <img
                                            v-if="item.image"
                                            :src="item.image"
                                            :alt="item.title"
                                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                            loading="lazy"
                                        />
                                        <!-- Placeholder Gambar Merchandise -->
                                        <div
                                            v-else
                                            class="flex flex-col items-center justify-center text-center p-4 text-slate-300"
                                        >
                                            <span class="text-3xl mb-1">{{
                                                item.placeholderIcon || "🛍️"
                                            }}</span>
                                            <span
                                                class="text-[11px] font-black tracking-wider uppercase text-slate-200"
                                                >{{
                                                    item.placeholderLabel ||
                                                    "OFFICIAL MERCH"
                                                }}</span
                                            >
                                            <span
                                                class="text-[10px] text-slate-400 mt-0.5 truncate max-w-[190px]"
                                                >{{
                                                    item.placeholderSub ||
                                                    item.category
                                                }}</span
                                            >
                                        </div>

                                        <!-- Badge Status Produk (Kiri Atas) -->
                                        <div class="absolute top-2.5 left-2.5">
                                            <span
                                                class="inline-block px-2.5 py-1 rounded-full text-[10px] sm:text-[11px] font-black uppercase tracking-wider shadow-sm"
                                                :class="item.badgeClass"
                                            >
                                                {{ item.badge }}
                                            </span>
                                        </div>

                                        <!-- Badge Kategori (Kanan Atas) -->
                                        <div class="absolute top-2.5 right-2.5">
                                            <span
                                                class="inline-block px-2.5 py-1 rounded-full text-[10px] sm:text-[11px] font-bold bg-slate-900/80 backdrop-blur-xs text-white border border-white/20"
                                            >
                                                {{ item.category }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Konten Teks Kartu Produk -->
                                <div class="p-4 sm:p-5 space-y-2 text-left">
                                    <!-- Kategori & Stok -->
                                    <div
                                        class="flex items-center justify-between text-[11px] font-semibold text-slate-500"
                                    >
                                        <span
                                            class="text-slate-400 uppercase tracking-wider font-bold"
                                        >
                                            {{ item.stockStatus }}
                                        </span>
                                        <span class="text-slate-500">
                                            Sisa:
                                            <strong class="text-slate-800"
                                                >{{ item.stock }} pcs</strong
                                            >
                                        </span>
                                    </div>

                                    <!-- Judul Produk -->
                                    <h3
                                        class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-[#F95700] transition line-clamp-2 leading-snug"
                                    >
                                        {{ item.title }}
                                    </h3>

                                    <!-- Deskripsi Singkat -->
                                    <p
                                        class="text-xs text-slate-500 line-clamp-2 leading-relaxed"
                                    >
                                        {{ item.desc }}
                                    </p>

                                    <!-- Harga Produk -->
                                    <div
                                        class="pt-1.5 flex items-baseline justify-between border-t border-slate-100"
                                    >
                                        <div
                                            class="text-base sm:text-lg font-black text-[#F95700]"
                                        >
                                            {{ item.priceFormatted }}
                                        </div>
                                        <span
                                            v-if="item.hasSize"
                                            class="text-[10px] font-bold text-slate-400"
                                        >
                                            Size S - XXL
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Kartu: Tombol Pesan -->
                            <div
                                class="px-4 sm:px-5 pb-4 sm:pb-5 pt-1 flex items-center justify-between border-t border-slate-100"
                            >
                                <button
                                    type="button"
                                    @click="openOrderModal(item)"
                                    class="text-xs font-bold text-slate-900 group-hover:text-[#F95700] transition flex items-center gap-1.5"
                                >
                                    <span>Pesan via WhatsApp</span>
                                </button>
                                <span
                                    class="text-slate-400 group-hover:text-[#F95700] group-hover:translate-x-1 transition text-sm"
                                >
                                    →
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- State Kosong jika Tidak Ada Produk -->
                    <div
                        v-else
                        class="text-center py-16 bg-white rounded-3xl border border-slate-200/80 p-8"
                    >
                        <div class="text-4xl mb-3">🔍</div>
                        <h3 class="text-lg font-bold text-slate-800">
                            Tidak ada produk yang cocok
                        </h3>
                        <p
                            class="text-xs sm:text-sm text-slate-500 mt-1 max-w-md mx-auto"
                        >
                            Coba ubah filter kategori atau gunakan kata kunci
                            pencarian yang lain.
                        </p>
                        <button
                            type="button"
                            @click="
                                activeCategory = 'Semua';
                                searchQuery = '';
                                sortBy = 'terpopuler';
                            "
                            class="mt-4 px-4 py-2 rounded-xl bg-orange-500 text-white font-bold text-xs hover:bg-orange-600 transition"
                        >
                            Reset Filter
                        </button>
                    </div>
                </div>

                <!-- 4. Keuntungan Berbelanja di HIMATIF Merch Shop -->
                <div
                    class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-10 shadow-xs space-y-6 text-left"
                >
                    <div>
                        <span
                            class="text-xs font-black tracking-widest text-[#F95700] uppercase block mb-1"
                        >
                            KENAPA BELANJA DI DANUS HIMATIF?
                        </span>
                        <h3
                            class="text-xl sm:text-2xl font-black text-slate-900"
                        >
                            Keuntungan & Standar Merchandise Resmi
                        </h3>
                    </div>

                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 pt-2"
                    >
                        <div
                            class="p-4 rounded-2xl bg-slate-50 border border-slate-200/60 space-y-2"
                        >
                            <span class="text-2xl">🏛️</span>
                            <h4 class="text-sm font-bold text-slate-900">
                                Resmi & Berstandar
                            </h4>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Seluruh merchandise dan atribut dirancang resmi
                                oleh pengurus HIMATIF POLINDRA.
                            </p>
                        </div>
                        <div
                            class="p-4 rounded-2xl bg-slate-50 border border-slate-200/60 space-y-2"
                        >
                            <span class="text-2xl">⭐</span>
                            <h4 class="text-sm font-bold text-slate-900">
                                Bahan Pilihan Terbaik
                            </h4>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Menggunakan bahan kain katun combed reaktif,
                                bordir komputer presisi, dan sablon awet.
                            </p>
                        </div>
                        <div
                            class="p-4 rounded-2xl bg-slate-50 border border-slate-200/60 space-y-2"
                        >
                            <span class="text-2xl">🤝</span>
                            <h4 class="text-sm font-bold text-slate-900">
                                Ambil Langsung di Kampus
                            </h4>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Pengambilan barang dapat dilakukan langsung di
                                Sekretariat HIMATIF tanpa biaya ongkos kirim.
                            </p>
                        </div>
                        <div
                            class="p-4 rounded-2xl bg-slate-50 border border-slate-200/60 space-y-2"
                        >
                            <span class="text-2xl">❤️</span>
                            <h4 class="text-sm font-bold text-slate-900">
                                Dukung Kegiatan Ormawa
                            </h4>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Keuntungan penjualan disalurkan untuk pendanaan
                                program kerja dan lomba mahasiswa.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL ORDER LANGSUNG KE WHATSAPP DANUS -->
        <div
            v-if="isModalOpen && selectedProduct"
            class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-4"
            @click.self="closeModal"
        >
            <div
                class="bg-white rounded-3xl max-w-xl w-full overflow-hidden shadow-2xl border border-slate-200 transform transition-all animate-in fade-in zoom-in-95 duration-200 max-h-[90vh] flex flex-col"
            >
                <!-- Header Gambar / Placeholder Modal -->
                <div
                    class="relative aspect-[16/9] w-full bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center shrink-0"
                >
                    <img
                        v-if="selectedProduct.image"
                        :src="selectedProduct.image"
                        :alt="selectedProduct.title"
                        class="w-full h-full object-cover"
                    />
                    <div
                        v-else
                        class="flex flex-col items-center justify-center text-center p-6 text-slate-300"
                    >
                        <span class="text-5xl mb-2">{{
                            selectedProduct.placeholderIcon || "🛍️"
                        }}</span>
                        <span
                            class="text-sm font-black tracking-wider uppercase text-white"
                            >{{
                                selectedProduct.placeholderLabel ||
                                "OFFICIAL MERCH"
                            }}</span
                        >
                        <span class="text-xs text-slate-400 mt-1 max-w-sm">{{
                            selectedProduct.placeholderSub ||
                            selectedProduct.category
                        }}</span>
                    </div>

                    <!-- Tombol Tutup -->
                    <button
                        type="button"
                        @click="closeModal"
                        class="absolute top-4 right-4 w-9 h-9 rounded-full bg-black/60 hover:bg-black text-white flex items-center justify-center transition shadow-lg text-sm"
                    >
                        ✕
                    </button>

                    <!-- Badge Kategori -->
                    <div class="absolute bottom-4 left-4">
                        <span
                            class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider text-white shadow-md"
                            :class="selectedProduct.badgeClass"
                        >
                            {{ selectedProduct.badge }}
                        </span>
                    </div>
                </div>

                <!-- Konten Modal (Scrollable) -->
                <div class="p-6 sm:p-8 space-y-5 text-left overflow-y-auto">
                    <!-- Judul & Harga -->
                    <div>
                        <span
                            class="text-xs font-black tracking-widest text-[#F95700] uppercase block"
                        >
                            {{ selectedProduct.category }}
                        </span>
                        <h3
                            class="text-xl sm:text-2xl font-black text-slate-900 mt-1 leading-snug"
                        >
                            {{ selectedProduct.title }}
                        </h3>
                        <div class="text-2xl font-black text-[#F95700] mt-2">
                            {{ selectedProduct.priceFormatted }}
                        </div>
                    </div>

                    <!-- Deskripsi & Fitur -->
                    <div class="space-y-3">
                        <p
                            class="text-xs sm:text-sm text-slate-600 leading-relaxed"
                        >
                            {{ selectedProduct.desc }}
                        </p>

                        <!-- Poin Fitur -->
                        <div
                            class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1.5"
                        >
                            <h4
                                class="text-xs font-bold text-slate-800 uppercase tracking-wide"
                            >
                                ✨ Keunggulan Produk:
                            </h4>
                            <ul
                                class="text-xs text-slate-600 space-y-1 list-disc list-inside"
                            >
                                <li
                                    v-for="(
                                        feat, idx
                                    ) in selectedProduct.features"
                                    :key="idx"
                                >
                                    {{ feat }}
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Formulir Checkout WhatsApp -->
                    <div class="space-y-4 pt-3 border-t border-slate-200">
                        <!-- Pilihan Ukuran jika Pakaian -->
                        <div v-if="selectedProduct.hasSize" class="space-y-1.5">
                            <label
                                class="block text-xs font-bold text-slate-700"
                                >Pilih Ukuran Baju:</label
                            >
                            <div class="flex items-center gap-2">
                                <button
                                    v-for="size in selectedProduct.sizes"
                                    :key="size"
                                    type="button"
                                    @click="selectedSize = size"
                                    class="w-10 h-10 rounded-xl font-bold text-xs border transition"
                                    :class="
                                        selectedSize === size
                                            ? 'bg-[#F95700] border-[#F95700] text-white shadow-sm'
                                            : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-100'
                                    "
                                >
                                    {{ size }}
                                </button>
                            </div>
                        </div>

                        <!-- Jumlah Pesanan (+ / -) -->
                        <div class="space-y-1.5">
                            <label
                                class="block text-xs font-bold text-slate-700"
                                >Jumlah Pesanan:</label
                            >
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex items-center border border-slate-200 rounded-xl bg-white overflow-hidden shadow-xs"
                                >
                                    <button
                                        type="button"
                                        @click="
                                            orderQuantity = Math.max(
                                                1,
                                                orderQuantity - 1,
                                            )
                                        "
                                        class="px-3.5 py-2 hover:bg-slate-100 text-slate-700 font-bold text-sm transition"
                                    >
                                        −
                                    </button>
                                    <span
                                        class="px-4 py-2 text-xs font-bold text-slate-900 border-x border-slate-100 min-w-[40px] text-center"
                                    >
                                        {{ orderQuantity }}
                                    </span>
                                    <button
                                        type="button"
                                        @click="
                                            orderQuantity = Math.min(
                                                selectedProduct.stock || 99,
                                                orderQuantity + 1,
                                            )
                                        "
                                        class="px-3.5 py-2 hover:bg-slate-100 text-slate-700 font-bold text-sm transition"
                                    >
                                        +
                                    </button>
                                </div>
                                <span class="text-xs text-slate-400">
                                    (Tersedia {{ selectedProduct.stock }} pcs)
                                </span>
                            </div>
                        </div>

                        <!-- Data Pembeli (Opsional) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                            <div class="space-y-1">
                                <label
                                    class="block text-[11px] font-bold text-slate-700"
                                    >Nama Pemesan</label
                                >
                                <input
                                    v-model="buyerName"
                                    type="text"
                                    placeholder="Nama lengkap kamu"
                                    class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500"
                                />
                            </div>
                            <div class="space-y-1">
                                <label
                                    class="block text-[11px] font-bold text-slate-700"
                                    >NIM / Kelas</label
                                >
                                <input
                                    v-model="buyerNim"
                                    type="text"
                                    placeholder="Contoh: 2305001 / TI-2B"
                                    class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500"
                                />
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label
                                class="block text-[11px] font-bold text-slate-700"
                                >Catatan Pesanan (Opsional)</label
                            >
                            <input
                                v-model="buyerNotes"
                                type="text"
                                placeholder="Contoh: Titip di Sekre atau pesan 2 warna berbeda"
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500"
                            />
                        </div>

                        <!-- Ringkasan Total & Tombol Checkout WA -->
                        <div
                            class="pt-4 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3"
                        >
                            <div>
                                <span class="text-[11px] text-slate-500 block"
                                    >Total Pembayaran:</span
                                >
                                <span
                                    class="text-xl font-black text-[#F95700]"
                                    >{{ totalPriceFormatted }}</span
                                >
                            </div>

                            <button
                                type="button"
                                @click="checkoutWhatsApp"
                                class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-emerald-600/25 transition transform active:scale-95"
                            >
                                <span>💬 Pesan Sekarang via WhatsApp</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
