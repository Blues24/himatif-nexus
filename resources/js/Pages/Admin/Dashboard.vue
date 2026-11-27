<script setup>
import { computed } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import StatCard from '@/Components/Admin/StatCard.vue';

const props = defineProps({
  stats: {
    type: Object,
    default: () => ({
      pending_orders: 7,
      pending_rentals: 4,
      active_rentals: 9,
      upcoming_events: 3,
      published_articles: 18,
      draft_articles: 2,
    }),
  },
  pendingRentals: {
    type: Array,
    default: () => [],
  },
  recentOrders: {
    type: Array,
    default: () => [],
  },
  upcomingEvents: {
    type: Array,
    default: () => [],
  },
});

const page = usePage();

// Deteksi Role (Bisa lewat query param ?role=manset|danus|infokom|bph atau auth user)
const userRole = computed(() => {
  if (typeof window !== 'undefined') {
    const params = new URLSearchParams(window.location.search);
    const r = params.get('role');
    if (r && ['bph', 'manset', 'danus', 'infokom'].includes(r.toLowerCase())) {
      return r.toLowerCase();
    }
  }
  return page.props.auth?.user?.role || 'bph';
});

// Format Tanggal Header
const formattedDate = computed(() => {
  return new Intl.DateTimeFormat('id-ID', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  }).format(new Date());
});

// Header Titles & Subtitles per Role
const headerConfig = computed(() => {
  switch (userRole.value) {
    case 'manset':
      return {
        title: 'Dashboard Manset',
        subtitle: `${formattedDate.value} · Ringkasan peminjaman, penyewaan & inventaris alat sekretariat HIMATIF`,
      };
    case 'danus':
      return {
        title: 'Dashboard Danus',
        subtitle: `${formattedDate.value} · Ringkasan penjualan merchandise, verifikasi pesanan & stok`,
      };
    case 'infokom':
      return {
        title: 'Dashboard Infokom',
        subtitle: `${formattedDate.value} · Ringkasan publikasi artikel Formatif, berita himpunan & agenda kegiatan`,
      };
    case 'bph':
    default:
      return {
        title: 'Dashboard',
        subtitle: `${formattedDate.value} · Ringkasan operasional HIMATIF`,
      };
  }
});

// 4 Kartu KPI per Role
const statCards = computed(() => {
  switch (userRole.value) {
    case 'manset':
      return [
        {
          title: 'Penyewaan menunggu konfirmasi',
          value: props.stats?.pending_rentals ?? 4,
          subtitle: 'Perlu persetujuan Manset',
          variant: 'amber',
          icon: 'box',
        },
        {
          title: 'Alat sedang disewa / dipakai',
          value: props.stats?.active_rentals ?? 9,
          subtitle: 'Dari total 24 unit inventaris',
          variant: 'blue',
          icon: 'box',
        },
        {
          title: 'Jadwal kembali hari ini',
          value: 2,
          subtitle: 'Proyektor & Kamera Sony',
          variant: 'green',
          icon: 'calendar',
        },
        {
          title: 'Unit siap pakai di sekretariat',
          value: 15,
          subtitle: 'Kondisi baik & siap disewa',
          variant: 'gray',
          icon: 'box',
        },
      ];
    case 'danus':
      return [
        {
          title: 'Pesanan menunggu verifikasi',
          value: props.stats?.pending_orders ?? 7,
          subtitle: 'Perlu cek bukti WhatsApp',
          variant: 'amber',
          icon: 'bag',
        },
        {
          title: 'Siap diambil di sekretariat',
          value: 5,
          subtitle: 'Pembayaran sudah terverifikasi',
          variant: 'green',
          icon: 'bag',
        },
        {
          title: 'Pendapatan bulan ini',
          value: 'Rp450.000',
          subtitle: 'Dari 28 pesanan merchandise',
          variant: 'blue',
          icon: 'bag',
        },
        {
          title: 'Stok produk menipis / habis',
          value: 2,
          subtitle: 'Hoodie (6) · Lanyard (0)',
          variant: 'red',
          icon: 'bag',
        },
      ];
    case 'infokom':
      return [
        {
          title: 'Artikel Formatif terbit',
          value: props.stats?.published_articles ?? 18,
          subtitle: 'Total 20 artikel di sistem',
          variant: 'amber',
          icon: 'doc',
        },
        {
          title: 'Draf menunggu publikasi',
          value: props.stats?.draft_articles ?? 2,
          subtitle: 'Siap disunting & diterbitkan',
          variant: 'amber',
          icon: 'doc',
        },
        {
          title: 'Kegiatan mendatang',
          value: props.stats?.upcoming_events ?? 3,
          subtitle: '2 publik · 1 masih draf',
          variant: 'blue',
          icon: 'calendar',
        },
        {
          title: 'Pembaca Formatif bulan ini',
          value: '1.420',
          subtitle: '+18% dibanding bulan lalu',
          variant: 'green',
          icon: 'doc',
        },
      ];
    case 'bph':
    default:
      return [
        {
          title: 'Pesanan menunggu verifikasi',
          value: props.stats?.pending_orders ?? 7,
          subtitle: 'Merchandise · dikelola Danus',
          variant: 'amber',
          icon: 'bag',
        },
        {
          title: 'Penyewaan menunggu konfirmasi',
          value: props.stats?.pending_rentals ?? 4,
          subtitle: `${props.stats?.active_rentals ?? 9} alat sedang disewa · dikelola Manset`,
          variant: 'blue',
          icon: 'box',
        },
        {
          title: 'Kegiatan mendatang',
          value: props.stats?.upcoming_events ?? 3,
          subtitle: 'Dalam 30 hari ke depan',
          variant: 'green',
          icon: 'calendar',
        },
        {
          title: 'Artikel Formatif',
          value: props.stats?.published_articles ?? 18,
          subtitle: `Terbit · ${props.stats?.draft_articles ?? 2} masih draf`,
          variant: 'gray',
          icon: 'doc',
        },
      ];
  }
});

// Format Rent Date
const formatRentDate = (start, end) => {
  if (!start) return '-';
  const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
  const s = new Date(start);
  const e = end ? new Date(end) : s;
  const sDay = s.getDate();
  const eDay = e.getDate();
  const month = months[e.getMonth()] || 'Okt';
  return sDay === eDay ? `${sDay} ${month}` : `${sDay} – ${eDay} ${month}`;
};

// Data Penyewaan Menunggu Konfirmasi (BPH & Manset)
const displayRentals = computed(() => {
  if (props.pendingRentals && props.pendingRentals.length > 0) {
    return props.pendingRentals.map((r) => ({
      id: r.id,
      penyewa: r.nama_penyewa,
      alat: r.inventaris?.nama_alat || 'Alat Inventaris',
      tanggal: formatRentDate(r.tanggal_mulai, r.tanggal_selesai),
      fromDb: true,
    }));
  }
  return [
    { id: 1, penyewa: 'Dina Putri', alat: 'Proyektor', tanggal: '15 – 16 Okt', fromDb: false },
    { id: 2, penyewa: 'Raka Mahendra', alat: 'Tripod + Kamera', tanggal: '17 Okt', fromDb: false },
    { id: 3, penyewa: 'Salsa Nurul', alat: 'Terminal kabel', tanggal: '18 – 19 Okt', fromDb: false },
    { id: 4, penyewa: 'Fajar Ramadhan', alat: 'Mikrofon nirkabel', tanggal: '20 Okt', fromDb: false },
  ];
});

// Manset: Alat sedang disewa & jadwal kembali
const mansetBorrowedItems = [
  { item: 'Proyektor Epson #1', user: 'Bagus Setiawan · Himpunan', deadline: 'Hari ini, 17.00', statusPill: 'warning' },
  { item: 'Kamera Sony Alpha', user: 'Citra Lestari · Dokumentasi', deadline: 'Hari ini, 18.00', statusPill: 'warning' },
  { item: 'Sound System Portable', user: 'Panitia Lomba Web · Aula', deadline: '19 Okt 2026', statusPill: 'info' },
  { item: 'Terminal Kabel Roll (2 unit)', user: 'Divisi Infokom · Lab Komputer', deadline: '20 Okt 2026', statusPill: 'info' },
];

// Manset: Ketersediaan inventaris utama
const mansetInventoryList = [
  { code: 'PR', name: 'Proyektor Epson EB-X500', desc: '2 dari 3 unit tersedia di sekretariat', status: 'Tersedia', statusClass: 'pill-success' },
  { code: 'KB', name: 'Terminal Kabel Roll 15m', desc: '4 dari 6 unit tersedia di sekretariat', status: 'Tersedia', statusClass: 'pill-success' },
  { code: 'TK', name: 'Tripod + Kamera Sony Alpha', desc: '1 dari 2 set tersedia', status: '1 Tersisa', statusClass: 'pill-warning' },
];

// Manset: Aktivitas terbaru
const mansetActivities = [
  { actor: 'Manset', action: 'mengonfirmasi pengembalian Proyektor #2', time: 'Kemarin, 11.05' },
  { actor: 'Manset', action: 'menyetujui peminjaman Sound Portable (Panitia)', time: '12 Okt 2026, 15.30' },
  { actor: 'Manset', action: 'melakukan pengecekan rutin 6 unit Terminal Kabel', time: '10 Okt 2026, 16.00' },
];

// Format Rupiah
const formatRupiah = (amount) => {
  return 'Rp' + Number(amount || 0).toLocaleString('id-ID');
};

// Data Pesanan Merchandise Terbaru (BPH)
const displayOrders = computed(() => {
  if (props.recentOrders && props.recentOrders.length > 0) {
    return props.recentOrders.map((o) => {
      const firstDetail = o.details?.[0];
      const prodName = firstDetail?.produk?.nama_produk || 'Merchandise';
      const qty = firstDetail?.jumlah > 1 ? ` x${firstDetail.jumlah}` : '';
      return {
        id: o.id,
        no: o.kode_pesanan || `ORD-014${o.id}`,
        produk: `${prodName}${qty}`,
        total: formatRupiah(o.total_harga),
        statusLabel:
          o.status === 'pending'
            ? 'Menunggu verifikasi'
            : o.status === 'verified'
              ? 'Terverifikasi'
              : o.status === 'picked_up'
                ? 'Sudah diambil'
                : 'Dibatalkan',
        pillType:
          o.status === 'pending'
            ? 'warning'
            : o.status === 'verified'
              ? 'success'
              : 'neutral',
      };
    });
  }
  return [
    { no: 'ORD-0142', produk: 'Kaos HIMATIF x2', total: 'Rp170.000', statusLabel: 'Menunggu verifikasi', pillType: 'warning' },
    { no: 'ORD-0141', produk: 'Hoodie HIMATIF', total: 'Rp185.000', statusLabel: 'Menunggu verifikasi', pillType: 'warning' },
    { no: 'ORD-0140', produk: 'Paket stiker A', total: 'Rp15.000', statusLabel: 'Terverifikasi', pillType: 'success' },
    { no: 'ORD-0139', produk: 'Ganci', total: 'Rp45.000', statusLabel: 'Sudah diambil', pillType: 'neutral' },
    { no: 'ORD-0138', produk: 'Tumbler HIMATIF', total: 'Rp45.000', statusLabel: 'Sudah diambil', pillType: 'neutral' },
  ];
});

// Danus: Antrean Verifikasi Pesanan
const danusOrdersQueue = [
  { no: 'ORD-0142', name: 'Kaos HIMATIF x2', buyer: 'Nadia Kusuma · via WhatsApp', total: 'Rp170.000', action: 'verify' },
  { no: 'ORD-0141', name: 'Hoodie HIMATIF', buyer: 'Bagas Pratama · via WhatsApp', total: 'Rp185.000', action: 'verify' },
  { no: 'ORD-0140', name: 'Paket stiker', buyer: 'Alya Rahma · Siap diambil', total: 'Rp15.000', action: 'verified' },
  { no: 'ORD-0139', name: 'Tote bag', buyer: 'Dimas Aditya · Selesai', total: 'Rp45.000', action: 'picked_up' },
];

// Danus: Peringatan Stok & Katalog
const danusStockAlerts = [
  { code: 'LY', name: 'Lanyard HIMATIF', desc: 'Rp20.000 · Sablon dua sisi', stock: 0, status: 'Habis', statusClass: 'pill-danger', action: '+ Restok' },
  { code: 'HD', name: 'Hoodie HIMATIF', desc: 'Rp185.000 · Fleece tebal', stock: 6, status: 'Stok menipis', statusClass: 'pill-warning', action: '+ Restok' },
  { code: 'TB', name: 'Tote bag', desc: 'Rp45.000 · Kanvas', stock: 18, status: 'Tersedia', statusClass: 'pill-success', action: 'Edit' },
  { code: 'KS', name: 'Kaos HIMATIF', desc: 'Rp85.000 · Katun combed 30s', stock: 42, status: 'Tersedia', statusClass: 'pill-success', action: 'Edit' },
];

// Danus: Produk Terlaris
const danusTopProducts = [
  { rank: '#1', name: 'Kaos HIMATIF', desc: '14 pcs terjual · Katun combed 30s', revenue: 'Rp1.190.000' },
  { rank: '#2', name: 'Hoodie HIMATIF', desc: '5 pcs terjual · Fleece tebal', revenue: 'Rp925.000' },
  { rank: '#3', name: 'Tote bag & Paket stiker', desc: '9 transaksi selesai', revenue: 'Rp335.000' },
];

// Danus: Aktivitas Terbaru
const danusActivities = [
  { actor: 'Danus', action: 'memverifikasi pesanan ORD-0140 (Paket stiker)', time: 'Hari ini, 10.15' },
  { actor: 'Danus', action: 'menambah produk "Lanyard HIMATIF"', time: 'Kemarin, 16.40' },
  { actor: 'Danus', action: 'menyerahkan pesanan ORD-0139 di sekretariat', time: 'Kemarin, 13.20' },
];

// Infokom: Draf & Artikel Terbaru
const infokomArticles = [
  { title: 'Pengenalan Laravel dan MVC', slug: '/pengenalan-laravel-mvc', category: 'Materi IT', categoryClass: 'cat-blue', status: 'Draf', statusClass: 'pill-neutral', action: 'publish' },
  { title: 'Dasar Git untuk Pemula', slug: '/dasar-git-untuk-pemula', category: 'Materi IT', categoryClass: 'cat-blue', status: 'Terbit', statusClass: 'pill-success', action: 'edit' },
  { title: 'Pendaftaran Lomba Desain Web', slug: '/pendaftaran-lomba-desain-web', category: 'Perlombaan', categoryClass: 'cat-amber', status: 'Terbit', statusClass: 'pill-success', action: 'edit' },
  { title: 'Struktur Kepengurusan HIMATIF', slug: '/struktur-kepengurusan', category: 'Organisasi', categoryClass: 'cat-green', status: 'Terbit', statusClass: 'pill-success', action: 'edit' },
];

// Infokom: Agenda Kegiatan
const infokomEvents = [
  { day: '18', month: 'OKT', title: 'Pelatihan Dasar Laravel', loc: 'Lab Komputer · 09.00 WIB', status: 'Akan datang', isDraft: false },
  { day: '25', month: 'OKT', title: 'Lomba Desain Web', loc: 'Pendaftaran dibuka · 42 peserta', status: 'Akan datang', isDraft: false },
  { day: '02', month: 'NOV', title: 'Rapat Kerja Pengurus', loc: 'Internal BPH · Sekretariat', status: 'Draf', isDraft: true },
];

// Infokom: Aktivitas Terbaru
const infokomActivities = [
  { actor: 'Infokom', action: 'menerbitkan artikel "Dasar Git untuk Pemula"', time: 'Hari ini, 09.12' },
  { actor: 'Infokom', action: 'membuka pendaftaran "Lomba Desain Web"', time: '11 Okt 2026, 14.00' },
  { actor: 'Infokom', action: 'menyimpan draf "Pengenalan Laravel dan MVC"', time: '09 Okt 2026, 20.15' },
];

// Data Kegiatan Mendatang (BPH)
const displayEvents = computed(() => {
  const months = ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AGU', 'SEP', 'OKT', 'NOV', 'DES'];
  if (props.upcomingEvents && props.upcomingEvents.length > 0) {
    return props.upcomingEvents.map((ev) => {
      const d = new Date(ev.tanggal_acara);
      return {
        id: ev.id,
        day: String(d.getDate()).padStart(2, '0'),
        month: months[d.getMonth()] || 'OKT',
        title: ev.judul,
        loc: ev.lokasi || 'Kampus POLINDRA',
        statusLabel: ev.status === 'draft' ? 'Draf' : 'Akan datang',
        isDraft: ev.status === 'draft',
      };
    });
  }
  return [
    { id: 1, day: '18', month: 'OKT', title: 'Pelatihan Dasar Laravel', loc: 'Lab Komputer · 09.00 WIB', statusLabel: 'Akan datang', isDraft: false },
    { id: 2, day: '25', month: 'OKT', title: 'Lomba Desain Web', loc: 'Pendaftaran dibuka', statusLabel: 'Akan datang', isDraft: false },
    { id: 3, day: '02', month: 'NOV', title: 'Rapat Kerja Pengurus', loc: 'Internal BPH', statusLabel: 'Draf', isDraft: true },
  ];
});

// Log Aktivitas BPH
const bphActivities = [
  { initials: 'IK', badgeClass: 'act-blue', actor: 'Infokom', action: 'menerbitkan artikel "Dasar Git untuk Pemula"', time: 'Hari ini, 09.12' },
  { initials: 'DN', badgeClass: 'act-amber', actor: 'Danus', action: 'menambah produk "Lanyard HIMATIF"', time: 'Kemarin, 16.40' },
  { initials: 'MS', badgeClass: 'act-green', actor: 'Manset', action: 'mengonfirmasi pengembalian Proyektor', time: 'Kemarin, 11.05' },
];

// Aksi Konfirmasi Penyewaan
const confirmRental = (item) => {
  if (item.fromDb) {
    router.post(`/admin/penyewaan/${item.id}/confirm`, {}, { preserveScroll: true });
  }
};
</script>

<template>
  <Head :title="`${headerConfig.title} - Panel Admin HIMATIF`" />

  <AdminLayout
    :title="headerConfig.title"
    :subtitle="headerConfig.subtitle"
  >
    <!-- Tombol Aksi Kanan Atas Sesuai Role -->
    <template #actions>
      <!-- BPH Actions -->
      <div v-if="userRole === 'bph'" class="d-flex align-items-center gap-2">
        <button type="button" class="notif-btn" title="Notifikasi">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
          </svg>
        </button>
        <Link href="/admin/kegiatan" class="btn-cyan text-decoration-none">
          + Tambah kegiatan
        </Link>
      </div>

      <!-- Manset Actions -->
      <div v-else-if="userRole === 'manset'" class="d-flex align-items-center gap-2">
        <Link href="/admin/inventaris" class="btn-outline-action text-decoration-none">
          Cek inventaris
        </Link>
        <Link href="/admin/inventaris" class="btn-cyan text-decoration-none">
          + Catat peminjaman
        </Link>
      </div>

      <!-- Danus Actions -->
      <div v-else-if="userRole === 'danus'" class="d-flex align-items-center gap-2">
        <Link href="/admin/shop" class="btn-outline-action text-decoration-none">
          Verifikasi pesanan
        </Link>
        <Link href="/admin/shop" class="btn-cyan text-decoration-none">
          + Tambah produk
        </Link>
      </div>

      <!-- Infokom Actions -->
      <div v-else-if="userRole === 'infokom'" class="d-flex align-items-center gap-2">
        <Link href="/admin/kegiatan" class="btn-outline-action text-decoration-none">
          + Tambah kegiatan
        </Link>
        <Link href="/admin/formatif" class="btn-cyan text-decoration-none">
          + Tulis artikel
        </Link>
      </div>
    </template>

    <!-- 4 Kartu KPI -->
    <div class="row g-3 mb-4">
      <div v-for="(item, idx) in statCards" :key="idx" class="col-12 col-sm-6 col-xl-3">
        <StatCard
          :title="item.title"
          :value="item.value"
          :subtitle="item.subtitle"
          :variant="item.variant"
          :icon="item.icon"
        />
      </div>
    </div>

    <!-- ==================== TAMPILAN DASHBOARD BPH (Gambar 1) ==================== -->
    <div v-if="userRole === 'bph'">
      <!-- Baris Tengah: Penyewaan & Pesanan -->
      <div class="row g-3 mb-4">
        <!-- Penyewaan menunggu konfirmasi -->
        <div class="col-12 col-xl-6">
          <div class="card-panel h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <h6 class="fw-bold mb-0">Penyewaan menunggu konfirmasi</h6>
              <Link href="/admin/inventaris" class="panel-link">Lihat semua</Link>
            </div>
            <div class="table-responsive">
              <table class="table align-middle mb-0 custom-table">
                <thead>
                  <tr>
                    <th>PENYEWA</th>
                    <th>ALAT</th>
                    <th>TANGGAL PAKAI</th>
                    <th class="text-end">AKSI</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, i) in displayRentals" :key="i">
                    <td class="fw-medium">{{ item.penyewa }}</td>
                    <td>{{ item.alat }}</td>
                    <td class="text-secondary">{{ item.tanggal }}</td>
                    <td class="text-end">
                      <button type="button" class="btn-cyan-sm" @click="confirmRental(item)">
                        Konfirmasi
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Pesanan merchandise terbaru -->
        <div class="col-12 col-xl-6">
          <div class="card-panel h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <h6 class="fw-bold mb-0">Pesanan merchandise terbaru</h6>
              <Link href="/admin/shop" class="panel-link">Lihat semua</Link>
            </div>
            <div class="table-responsive">
              <table class="table align-middle mb-0 custom-table">
                <thead>
                  <tr>
                    <th>NO. PESANAN</th>
                    <th>PRODUK</th>
                    <th>TOTAL</th>
                    <th>STATUS</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(order, i) in displayOrders" :key="i">
                    <td class="font-monospace text-secondary" style="font-size: 12px;">{{ order.no }}</td>
                    <td class="fw-medium">{{ order.produk }}</td>
                    <td>{{ order.total }}</td>
                    <td>
                      <span class="status-pill" :class="`pill-${order.pillType}`">{{ order.statusLabel }}</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Baris Bawah: Kegiatan Mendatang & Aktivitas Terbaru -->
      <div class="row g-3">
        <div class="col-12 col-xl-6">
          <div class="card-panel h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <h6 class="fw-bold mb-0">Kegiatan mendatang</h6>
              <Link href="/admin/kegiatan" class="panel-link">Kelola</Link>
            </div>
            <div class="d-flex flex-column gap-3">
              <div v-for="(ev, i) in displayEvents" :key="i" class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                  <div class="date-badge text-center">
                    <div class="fw-bold lh-1" style="font-size: 14px;">{{ ev.day }}</div>
                    <div style="font-size: 9px; font-weight: 600;">{{ ev.month }}</div>
                  </div>
                  <div>
                    <div class="fw-semibold" style="font-size: 13.5px;">{{ ev.title }}</div>
                    <div class="text-secondary" style="font-size: 12px;">{{ ev.loc }}</div>
                  </div>
                </div>
                <span class="status-pill" :class="ev.isDraft ? 'pill-neutral' : 'pill-info'">{{ ev.statusLabel }}</span>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-xl-6">
          <div class="card-panel h-100">
            <div class="mb-3">
              <h6 class="fw-bold mb-0">Aktivitas terbaru</h6>
            </div>
            <div class="d-flex flex-column gap-3">
              <div v-for="(act, i) in bphActivities" :key="i" class="d-flex align-items-start gap-3">
                <div class="act-avatar" :class="act.badgeClass">{{ act.initials }}</div>
                <div>
                  <div style="font-size: 13.5px; color: #111827;">
                    <span class="fw-bold">{{ act.actor }}</span> {{ act.action }}
                  </div>
                  <div class="text-secondary" style="font-size: 12px;">{{ act.time }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ==================== TAMPILAN DASHBOARD MANSET (Gambar 6) ==================== -->
    <div v-else-if="userRole === 'manset'">
      <!-- Baris Tengah: Penyewaan Menunggu Konfirmasi & Alat Sedang Disewa -->
      <div class="row g-3 mb-4">
        <!-- Penyewaan menunggu konfirmasi -->
        <div class="col-12 col-xl-6">
          <div class="card-panel h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <h6 class="fw-bold mb-0">Penyewaan menunggu konfirmasi</h6>
              <Link href="/admin/inventaris" class="panel-link">Lihat semua</Link>
            </div>
            <div class="table-responsive">
              <table class="table align-middle mb-0 custom-table">
                <thead>
                  <tr>
                    <th>PENYEWA</th>
                    <th>ALAT</th>
                    <th>TANGGAL PAKAI</th>
                    <th class="text-end">AKSI</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, i) in displayRentals" :key="i">
                    <td class="fw-medium">{{ item.penyewa }}</td>
                    <td>{{ item.alat }}</td>
                    <td class="text-secondary">{{ item.tanggal }}</td>
                    <td class="text-end">
                      <button type="button" class="btn-cyan-sm" @click="confirmRental(item)">
                        Konfirmasi
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Alat sedang disewa & jadwal kembali -->
        <div class="col-12 col-xl-6">
          <div class="card-panel h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <h6 class="fw-bold mb-0">Alat sedang disewa & jadwal kembali</h6>
              <Link href="/admin/inventaris" class="panel-link">Kelola alat</Link>
            </div>
            <div class="table-responsive">
              <table class="table align-middle mb-0 custom-table">
                <thead>
                  <tr>
                    <th>PENYEWA & ALAT</th>
                    <th>BATAS KEMBALI</th>
                    <th class="text-end">AKSI</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(row, i) in mansetBorrowedItems" :key="i">
                    <td>
                      <div class="fw-semibold text-dark" style="font-size: 13.5px;">{{ row.item }}</div>
                      <div class="text-secondary" style="font-size: 11.5px;">{{ row.user }}</div>
                    </td>
                    <td>
                      <span class="status-pill" :class="`pill-${row.statusPill}`">{{ row.deadline }}</span>
                    </td>
                    <td class="text-end">
                      <button type="button" class="btn-outline-cyan-sm">
                        Tandai kembali
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Baris Bawah: Ketersediaan Inventaris Utama & Aktivitas Manset -->
      <div class="row g-3">
        <!-- Ketersediaan Inventaris Utama -->
        <div class="col-12 col-xl-6">
          <div class="card-panel h-100">
            <div class="mb-3">
              <h6 class="fw-bold mb-0">Ketersediaan inventaris utama</h6>
            </div>
            <div class="d-flex flex-column gap-3">
              <div v-for="(item, i) in mansetInventoryList" :key="i" class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                  <div class="item-avatar">{{ item.code }}</div>
                  <div>
                    <div class="fw-semibold" style="font-size: 13.5px;">{{ item.name }}</div>
                    <div class="text-secondary" style="font-size: 12px;">{{ item.desc }}</div>
                  </div>
                </div>
                <span class="status-pill" :class="item.statusClass">{{ item.status }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Aktivitas Manset Terbaru -->
        <div class="col-12 col-xl-6">
          <div class="card-panel h-100">
            <div class="mb-3">
              <h6 class="fw-bold mb-0">Aktivitas Manset terbaru</h6>
            </div>
            <div class="d-flex flex-column gap-3">
              <div v-for="(act, i) in mansetActivities" :key="i" class="d-flex align-items-start gap-3">
                <div class="act-avatar act-green">MS</div>
                <div>
                  <div style="font-size: 13.5px; color: #111827;">
                    <span class="fw-bold">{{ act.actor }}</span> {{ act.action }}
                  </div>
                  <div class="text-secondary" style="font-size: 12px;">{{ act.time }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ==================== TAMPILAN DASHBOARD DANUS (Gambar 8) ==================== -->
    <div v-else-if="userRole === 'danus'">
      <!-- Baris Tengah: Antrean Verifikasi & Peringatan Stok -->
      <div class="row g-3 mb-4">
        <!-- Antrean verifikasi pesanan masuk -->
        <div class="col-12 col-xl-6">
          <div class="card-panel h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <h6 class="fw-bold mb-0">Antrean verifikasi pesanan masuk</h6>
              <Link href="/admin/shop" class="panel-link">Buka pesanan</Link>
            </div>
            <div class="table-responsive">
              <table class="table align-middle mb-0 custom-table">
                <thead>
                  <tr>
                    <th>NO. PESANAN</th>
                    <th>PRODUK & PEMBELI</th>
                    <th>TOTAL</th>
                    <th class="text-end">AKSI</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(ord, i) in danusOrdersQueue" :key="i">
                    <td class="font-monospace text-secondary" style="font-size: 12px;">{{ ord.no }}</td>
                    <td>
                      <div class="fw-semibold text-dark" style="font-size: 13.5px;">{{ ord.name }}</div>
                      <div class="text-secondary" style="font-size: 11.5px;">{{ ord.buyer }}</div>
                    </td>
                    <td class="fw-medium">{{ ord.total }}</td>
                    <td class="text-end">
                      <button v-if="ord.action === 'verify'" type="button" class="btn-cyan-sm">
                        Verifikasi
                      </button>
                      <span v-else-if="ord.action === 'verified'" class="status-pill pill-success">
                        Terverifikasi
                      </span>
                      <span v-else class="status-pill pill-neutral">
                        Sudah diambil
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Peringatan stok & katalog produk -->
        <div class="col-12 col-xl-6">
          <div class="card-panel h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <h6 class="fw-bold mb-0">Peringatan stok & katalog produk</h6>
              <Link href="/admin/shop" class="panel-link">Kelola produk</Link>
            </div>
            <div class="table-responsive">
              <table class="table align-middle mb-0 custom-table">
                <thead>
                  <tr>
                    <th>PRODUK</th>
                    <th>STOK</th>
                    <th>STATUS</th>
                    <th class="text-end">AKSI</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(prod, i) in danusStockAlerts" :key="i">
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <div class="item-avatar">{{ prod.code }}</div>
                        <div>
                          <div class="fw-semibold text-dark" style="font-size: 13.5px;">{{ prod.name }}</div>
                          <div class="text-secondary" style="font-size: 11.5px;">{{ prod.desc }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="fw-semibold">{{ prod.stock }}</td>
                    <td>
                      <span class="status-pill" :class="prod.statusClass">{{ prod.status }}</span>
                    </td>
                    <td class="text-end">
                      <button type="button" class="btn-outline-action-sm">
                        {{ prod.action }}
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Baris Bawah: Produk Terlaris & Aktivitas Danus -->
      <div class="row g-3">
        <!-- Produk terlaris bulan ini -->
        <div class="col-12 col-xl-6">
          <div class="card-panel h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <h6 class="fw-bold mb-0">Produk terlaris bulan ini</h6>
              <span class="text-secondary" style="font-size: 12.5px;">Oktober 2026</span>
            </div>
            <div class="d-flex flex-column gap-3">
              <div v-for="(p, i) in danusTopProducts" :key="i" class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                  <div class="rank-badge">{{ p.rank }}</div>
                  <div>
                    <div class="fw-semibold" style="font-size: 13.5px;">{{ p.name }}</div>
                    <div class="text-secondary" style="font-size: 12px;">{{ p.desc }}</div>
                  </div>
                </div>
                <div class="fw-bold text-dark" style="font-size: 13.5px;">{{ p.revenue }}</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Aktivitas Danus terbaru -->
        <div class="col-12 col-xl-6">
          <div class="card-panel h-100">
            <div class="mb-3">
              <h6 class="fw-bold mb-0">Aktivitas Danus terbaru</h6>
            </div>
            <div class="d-flex flex-column gap-3">
              <div v-for="(act, i) in danusActivities" :key="i" class="d-flex align-items-start gap-3">
                <div class="act-avatar act-amber">DN</div>
                <div>
                  <div style="font-size: 13.5px; color: #111827;">
                    <span class="fw-bold">{{ act.actor }}</span> {{ act.action }}
                  </div>
                  <div class="text-secondary" style="font-size: 12px;">{{ act.time }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ==================== TAMPILAN DASHBOARD INFOKOM (Gambar 10) ==================== -->
    <div v-else-if="userRole === 'infokom'">
      <!-- Baris Tengah: Draf & Artikel Terbaru & Agenda Kegiatan -->
      <div class="row g-3 mb-4">
        <!-- Draf & artikel Formatif terbaru -->
        <div class="col-12 col-xl-6">
          <div class="card-panel h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <h6 class="fw-bold mb-0">Draf & artikel Formatif terbaru</h6>
              <Link href="/admin/formatif" class="panel-link">Buka Formatif</Link>
            </div>
            <div class="table-responsive">
              <table class="table align-middle mb-0 custom-table">
                <thead>
                  <tr>
                    <th>JUDUL ARTIKEL</th>
                    <th>KATEGORI</th>
                    <th>STATUS</th>
                    <th class="text-end">AKSI</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(art, i) in infokomArticles" :key="i">
                    <td style="max-width: 200px;">
                      <div class="fw-semibold text-dark text-truncate" style="font-size: 13.5px;">{{ art.title }}</div>
                      <div class="font-monospace text-secondary text-truncate" style="font-size: 11px;">{{ art.slug }}</div>
                    </td>
                    <td>
                      <span class="cat-pill" :class="art.categoryClass">{{ art.category }}</span>
                    </td>
                    <td>
                      <span class="status-pill" :class="art.statusClass">{{ art.status }}</span>
                    </td>
                    <td class="text-end">
                      <button v-if="art.action === 'publish'" type="button" class="btn-cyan-sm">
                        Terbitkan
                      </button>
                      <button v-else type="button" class="btn-outline-action-sm">
                        Edit
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Agenda kegiatan HIMATIF -->
        <div class="col-12 col-xl-6">
          <div class="card-panel h-100 d-flex flex-column justify-content-between">
            <div>
              <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="fw-bold mb-0">Agenda kegiatan HIMATIF</h6>
                <Link href="/admin/kegiatan" class="panel-link">Kelola Kegiatan</Link>
              </div>
              <div class="d-flex flex-column gap-3 mb-3">
                <div v-for="(ev, i) in infokomEvents" :key="i" class="d-flex align-items-center justify-content-between">
                  <div class="d-flex align-items-center gap-3">
                    <div class="date-badge text-center">
                      <div class="fw-bold lh-1" style="font-size: 14px;">{{ ev.day }}</div>
                      <div style="font-size: 9px; font-weight: 600;">{{ ev.month }}</div>
                    </div>
                    <div>
                      <div class="fw-semibold" style="font-size: 13.5px;">{{ ev.title }}</div>
                      <div class="text-secondary" style="font-size: 12px;">{{ ev.loc }}</div>
                    </div>
                  </div>
                  <span class="status-pill" :class="ev.isDraft ? 'pill-neutral' : 'pill-info'">{{ ev.status }}</span>
                </div>
              </div>
            </div>
            <div class="text-secondary border-top pt-2" style="font-size: 11.5px;">
              Semua jadwal disinkronkan otomatis ke halaman depan website HIMATIF.
            </div>
          </div>
        </div>
      </div>

      <!-- Baris Bawah: Distribusi Kategori & Aktivitas Infokom -->
      <div class="row g-3">
        <!-- Distribusi kategori artikel Formatif -->
        <div class="col-12 col-xl-6">
          <div class="card-panel h-100">
            <div class="mb-3">
              <h6 class="fw-bold mb-0">Distribusi kategori artikel Formatif</h6>
            </div>
            <div class="d-flex flex-column gap-3 pt-2">
              <!-- Materi IT 55% -->
              <div>
                <div class="d-flex align-items-center justify-content-between mb-1" style="font-size: 13px;">
                  <span class="cat-pill cat-blue">Materi IT</span>
                  <span class="text-secondary fw-medium">11 artikel (55%)</span>
                </div>
                <div class="progress" style="height: 8px; border-radius: 999px; background-color: #F3F4F6;">
                  <div class="progress-bar" style="width: 55%; background-color: #2563EB; border-radius: 999px;"></div>
                </div>
              </div>

              <!-- Perlombaan 25% -->
              <div>
                <div class="d-flex align-items-center justify-content-between mb-1" style="font-size: 13px;">
                  <span class="cat-pill cat-amber">Perlombaan</span>
                  <span class="text-secondary fw-medium">5 artikel (25%)</span>
                </div>
                <div class="progress" style="height: 8px; border-radius: 999px; background-color: #F3F4F6;">
                  <div class="progress-bar" style="width: 25%; background-color: #D97706; border-radius: 999px;"></div>
                </div>
              </div>

              <!-- Organisasi 20% -->
              <div>
                <div class="d-flex align-items-center justify-content-between mb-1" style="font-size: 13px;">
                  <span class="cat-pill cat-green">Organisasi</span>
                  <span class="text-secondary fw-medium">4 artikel (20%)</span>
                </div>
                <div class="progress" style="height: 8px; border-radius: 999px; background-color: #F3F4F6;">
                  <div class="progress-bar" style="width: 20%; background-color: #059669; border-radius: 999px;"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Aktivitas Infokom terbaru -->
        <div class="col-12 col-xl-6">
          <div class="card-panel h-100">
            <div class="mb-3">
              <h6 class="fw-bold mb-0">Aktivitas Infokom terbaru</h6>
            </div>
            <div class="d-flex flex-column gap-3">
              <div v-for="(act, i) in infokomActivities" :key="i" class="d-flex align-items-start gap-3">
                <div class="act-avatar act-blue">IK</div>
                <div>
                  <div style="font-size: 13.5px; color: #111827;">
                    <span class="fw-bold">{{ act.actor }}</span> {{ act.action }}
                  </div>
                  <div class="text-secondary" style="font-size: 12px;">{{ act.time }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>
.notif-btn {
  width: 38px;
  height: 38px;
  border-radius: 8px;
  background: #ffffff;
  border: 1px solid #E5E7EB;
  color: #374151;
  display: flex;
  align-items: center;
  justify-content: center;
}

.btn-cyan {
  background-color: #00A3FF;
  color: #ffffff;
  border: none;
  border-radius: 8px;
  padding: 8px 16px;
  font-size: 13px;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  transition: background-color 0.15s ease;
}

.btn-cyan:hover {
  background-color: #008be5;
  color: #ffffff;
}

.btn-outline-action {
  background-color: #ffffff;
  color: #374151;
  border: 1px solid #E5E7EB;
  border-radius: 8px;
  padding: 8px 16px;
  font-size: 13px;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  transition: all 0.15s ease;
}

.btn-outline-action:hover {
  background-color: #F9FAFB;
  color: #111827;
}

.panel-link {
  font-size: 12.5px;
  font-weight: 600;
  color: #00A3FF;
  text-decoration: none;
}

.custom-table th {
  font-size: 11px;
  font-weight: 600;
  color: #6B7280;
  letter-spacing: 0.4px;
  border-bottom: 1px solid #E5E7EB;
  padding-bottom: 10px;
}

.custom-table td {
  font-size: 13px;
  padding: 12px 8px;
  border-bottom: 1px solid #F3F4F6;
}

.custom-table tbody tr:last-child td {
  border-bottom: none;
}

.btn-cyan-sm {
  background-color: #00A3FF;
  color: #ffffff;
  border: none;
  border-radius: 6px;
  padding: 5px 14px;
  font-size: 12px;
  font-weight: 600;
}

.btn-cyan-sm:hover {
  background-color: #008be5;
}

.btn-outline-cyan-sm {
  background-color: #ffffff;
  color: #00A3FF;
  border: 1px solid #BAE6FD;
  border-radius: 6px;
  padding: 5px 12px;
  font-size: 12px;
  font-weight: 600;
}

.btn-outline-cyan-sm:hover {
  background-color: #F0F9FF;
}

.btn-outline-action-sm {
  background-color: #ffffff;
  color: #374151;
  border: 1px solid #E5E7EB;
  border-radius: 6px;
  padding: 4px 12px;
  font-size: 12px;
  font-weight: 600;
}

.btn-outline-action-sm:hover {
  background-color: #F9FAFB;
}

.status-pill {
  display: inline-block;
  padding: 3px 10px;
  border-radius: 999px;
  font-size: 11.5px;
  font-weight: 600;
}

.pill-warning { background-color: #FEF3C7; color: #B45309; }
.pill-success { background-color: #D1FAE5; color: #0F765E; }
.pill-info { background-color: #E0E7FF; color: #3730A3; }
.pill-danger { background-color: #FEE2E2; color: #B91C1C; }
.pill-neutral { background-color: #F3F4F6; color: #4B5563; }

.cat-pill {
  display: inline-block;
  padding: 3px 10px;
  border-radius: 999px;
  font-size: 11.5px;
  font-weight: 600;
}

.cat-blue { background-color: #EFF6FF; color: #1D4ED8; }
.cat-amber { background-color: #FFFBEB; color: #B45309; }
.cat-green { background-color: #ECFDF5; color: #047857; }

.date-badge {
  width: 42px;
  height: 42px;
  border-radius: 8px;
  background-color: #E6F4F1;
  color: #0F765E;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.item-avatar {
  width: 34px;
  height: 34px;
  border-radius: 8px;
  background-color: #E0F2FE;
  color: #0284C7;
  font-size: 11.5px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.rank-badge {
  width: 34px;
  height: 34px;
  border-radius: 8px;
  background-color: #ECFDF5;
  color: #059669;
  font-size: 12px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.act-avatar {
  width: 34px;
  height: 34px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 11.5px;
  font-weight: 700;
  flex-shrink: 0;
}

.act-blue { background-color: #E0E7FF; color: #3730A3; }
.act-amber { background-color: #FEF3C7; color: #B45309; }
.act-green { background-color: #D1FAE5; color: #0F765E; }
</style>
