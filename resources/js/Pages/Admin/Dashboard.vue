<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import StatCard from '@/Components/Admin/StatCard.vue';

const stats = [
  {
    title: 'Pesanan menunggu verifikasi',
    value: 7,
    subtitle: 'Merchandise · dikelola Danus',
    variant: 'amber',
    icon: 'bag'
  },
  {
    title: 'Penyewaan menunggu konfirmasi',
    value: 4,
    subtitle: '9 alat sedang disewa · dikelola Manset',
    variant: 'blue',
    icon: 'box'
  },
  {
    title: 'Kegiatan mendatang',
    value: 3,
    subtitle: 'Dalam 30 hari ke depan',
    variant: 'green',
    icon: 'calendar'
  },
  {
    title: 'Artikel Formatif',
    value: 18,
    subtitle: 'Terbit · 2 masih draf',
    variant: 'gray',
    icon: 'doc'
  }
];

const rentals = [
  { penyewa: 'Dina Putri', alat: 'Proyektor', tanggal: '15 – 16 Okt' },
  { penyewa: 'Raka Mahendra', alat: 'Tripod + Kamera', tanggal: '17 Okt' },
  { penyewa: 'Salsa Nurul', alat: 'Terminal kabel', tanggal: '18 – 19 Okt' },
  { penyewa: 'Fajar Ramadhan', alat: 'Mikrofon nirkabel', tanggal: '20 Okt' }
];

const orders = [
  { no: 'ORD-0142', produk: 'Kaos HIMATIF x2', total: 'Rp170.000', status: 'Menunggu verifikasi', type: 'warning' },
  { no: 'ORD-0141', produk: 'Hoodie HIMATIF', total: 'Rp185.000', status: 'Menunggu verifikasi', type: 'warning' },
  { no: 'ORD-0140', produk: 'Paket stiker', total: 'Rp15.000', status: 'Terverifikasi', type: 'success' },
  { no: 'ORD-0139', produk: 'Tote bag', total: 'Rp45.000', status: 'Sudah diambil', type: 'neutral' }
];

const events = [
  { day: '18', month: 'OKT', title: 'Pelatihan Dasar Laravel', loc: 'Lab Komputer · 09.00 WIB', status: 'Akan datang', isDraft: false },
  { day: '25', month: 'OKT', title: 'Lomba Desain Web', loc: 'Pendaftaran dibuka', status: 'Akan datang', isDraft: false },
  { day: '02', month: 'NOV', title: 'Rapat Kerja Pengurus', loc: 'Internal BPH', status: 'Draf', isDraft: true }
];

const activities = [
  { initials: 'IK', badgeClass: 'act-blue', actor: 'Infokom', action: 'menerbitkan artikel "Dasar Git untuk Pemula"', time: 'Hari ini, 09.12' },
  { initials: 'DN', badgeClass: 'act-amber', actor: 'Danus', action: 'menambah produk "Lanyard HIMATIF"', time: 'Kemarin, 16.40' },
  { initials: 'MS', badgeClass: 'act-green', actor: 'Manset', action: 'mengonfirmasi pengembalian Proyektor', time: 'Kemarin, 11.05' }
];
</script>

<template>
  <Head title="Dashboard Admin" />

  <AdminLayout
    title="Dashboard"
    subtitle="Selasa, 13 Oktober 2026 · Ringkasan operasional HIMATIF"
  >
    <template #actions>
      <button class="notif-btn" title="Notifikasi">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
          <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
          <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
        </svg>
      </button>
      <Link href="/admin/kegiatan" class="btn-himatif text-decoration-none">
        + Tambah kegiatan
      </Link>
    </template>

    <!-- 4 StatCard KPI -->
    <div class="row g-3 mb-4">
      <div v-for="(item, idx) in stats" :key="idx" class="col-12 col-sm-6 col-xl-3">
        <StatCard
          :title="item.title"
          :value="item.value"
          :subtitle="item.subtitle"
          :variant="item.variant"
          :icon="item.icon"
        />
      </div>
    </div>

    <!-- Baris Tengah: Tabel Penyewaan & Pesanan Merchandise -->
    <div class="row g-3 mb-4">
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
                <tr v-for="(item, i) in rentals" :key="i">
                  <td class="fw-medium">{{ item.penyewa }}</td>
                  <td>{{ item.alat }}</td>
                  <td class="text-secondary">{{ item.tanggal }}</td>
                  <td class="text-end">
                    <button class="btn-confirm">Konfirmasi</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-12 col-xl-6">
        <div class="card-panel h-100">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="fw-bold mb-0">Pesanan merchandise terbaru</h6>
            <Link href="/admin/danus" class="panel-link">Lihat semua</Link>
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
                <tr v-for="(order, i) in orders" :key="i">
                  <td class="font-monospace text-secondary" style="font-size: 12px;">{{ order.no }}</td>
                  <td class="fw-medium">{{ order.produk }}</td>
                  <td>{{ order.total }}</td>
                  <td>
                    <span class="status-pill" :class="`pill-${order.type}`">
                      {{ order.status }}
                    </span>
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
            <div v-for="(ev, i) in events" :key="i" class="d-flex align-items-center justify-content-between">
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
              <span class="status-pill" :class="ev.isDraft ? 'pill-neutral' : 'pill-info'">
                {{ ev.status }}
              </span>
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
            <div v-for="(act, i) in activities" :key="i" class="d-flex align-items-start gap-3">
              <div class="act-avatar" :class="act.badgeClass">
                {{ act.initials }}
              </div>
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

.panel-link {
  font-size: 12.5px;
  font-weight: 600;
  color: #0F765E;
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

.btn-confirm {
  background-color: #0F765E;
  color: #ffffff;
  border: none;
  border-radius: 6px;
  padding: 5px 12px;
  font-size: 12px;
  font-weight: 600;
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
.pill-neutral { background-color: #F3F4F6; color: #4B5563; }

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