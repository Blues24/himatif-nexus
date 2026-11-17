<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const activeTab = ref('penyewaan');
const searchQuery = ref('');

// Daftar pengajuan penyewaan (sinkron dengan data Dashboard)
const rentals = ref([
  { id: 1, penyewa: 'Dina Putri', nim: '2403018', alat: 'Proyektor', tanggal: '15 – 16 Okt', status: 'Menunggu konfirmasi' },
  { id: 2, penyewa: 'Raka Mahendra', nim: '2403044', alat: 'Tripod + Kamera', tanggal: '17 Okt', status: 'Menunggu konfirmasi' },
  { id: 3, penyewa: 'Salsa Nurul', nim: '2503011', alat: 'Terminal kabel', tanggal: '18 – 19 Okt', status: 'Menunggu konfirmasi' },
  { id: 4, penyewa: 'Fajar Ramadhan', nim: '2303005', alat: 'Mikrofon nirkabel', tanggal: '20 Okt', status: 'Menunggu konfirmasi' },
  { id: 5, penyewa: 'Bagas Adi', nim: '2403089', alat: 'TOA Megaphone', tanggal: '12 – 14 Okt', status: 'Sedang disewa' }
]);

// Daftar alat inventaris sekretariat
const items = ref([
  { id: 1, code: 'PR', name: 'Proyektor Epson', category: 'Elektronik', totalUnit: 2, available: 1, tariff: 50000 },
  { id: 2, code: 'TK', name: 'Tripod + Kamera', category: 'Dokumentasi', totalUnit: 2, available: 2, tariff: 75000 },
  { id: 3, code: 'TO', name: 'TOA Megaphone', category: 'Audio', totalUnit: 3, available: 2, tariff: 25000 },
  { id: 4, code: 'KR', name: 'Kabel Roll 25 Meter', category: 'Perlengkapan', totalUnit: 5, available: 0, tariff: 15000 },
  { id: 5, code: 'MN', name: 'Mikrofon Nirkabel', category: 'Audio', totalUnit: 2, available: 2, tariff: 35000 }
]);

const form = ref({
  name: '',
  category: 'Perlengkapan',
  totalUnit: 1,
  tariff: 25000
});

const confirmRental = (rental) => {
  if (rental.status === 'Menunggu konfirmasi') {
    rental.status = 'Sedang disewa';
  } else if (rental.status === 'Sedang disewa') {
    rental.status = 'Sudah kembali';
  }
};

const addItem = () => {
  if (!form.value.name.trim()) return;
  const code = form.value.name.substring(0, 2).toUpperCase();
  items.value.unshift({
    id: Date.now(),
    code,
    name: form.value.name,
    category: form.value.category,
    totalUnit: Number(form.value.totalUnit),
    available: Number(form.value.totalUnit),
    tariff: Number(form.value.tariff)
  });
  form.value = { name: '', category: 'Perlengkapan', totalUnit: 1, tariff: 25000 };
};

const filteredItems = computed(() =>
  items.value.filter(i => i.name.toLowerCase().includes(searchQuery.value.toLowerCase()))
);
</script>

<template>
  <Head title="Inventaris - Panel Admin" />

  <AdminLayout
    title="Inventaris"
    subtitle="Kelola ketersediaan alat sekretariat dan konfirmasi peminjaman/penyewaan (Divisi Manset)."
  >
    <template #actions>
      <button class="btn-himatif" @click="activeTab = 'alat'">
        + Tambah alat
      </button>
    </template>

    <!-- Tab Switcher -->
    <div class="tab-switcher mb-3">
      <button
        type="button"
        class="tab-btn"
        :class="{ active: activeTab === 'penyewaan' }"
        @click="activeTab = 'penyewaan'"
      >
        Antrean Penyewaan · {{ rentals.filter(r => r.status === 'Menunggu konfirmasi').length }}
      </button>
      <button
        type="button"
        class="tab-btn"
        :class="{ active: activeTab === 'alat' }"
        @click="activeTab = 'alat'"
      >
        Daftar Alat · {{ items.length }}
      </button>
    </div>

    <div class="row g-3 align-items-start">
      <!-- Kiri: Tabel Penyewaan atau Daftar Alat -->
      <div class="col-12 col-xl-8">
        <div class="card-panel">
          <!-- TAB 1: ANTREAN PENYEWAAN -->
          <div v-if="activeTab === 'penyewaan'">
            <h6 class="fw-bold mb-3">Permintaan Penyewaan & Peminjaman Alat</h6>
            <div class="table-responsive">
              <table class="table align-middle mb-0 custom-table">
                <thead>
                  <tr>
                    <th>PENYEWA</th>
                    <th>ALAT</th>
                    <th>TANGGAL PAKAI</th>
                    <th>STATUS</th>
                    <th class="text-end">AKSI</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="r in rentals" :key="r.id">
                    <td>
                      <div class="fw-semibold">{{ r.penyewa }}</div>
                      <div class="text-secondary" style="font-size: 11.5px;">NIM {{ r.nim }}</div>
                    </td>
                    <td class="fw-medium">{{ r.alat }}</td>
                    <td class="text-secondary">{{ r.tanggal }}</td>
                    <td>
                      <span
                        class="status-pill"
                        :class="{
                          'pill-warning': r.status === 'Menunggu konfirmasi',
                          'pill-info': r.status === 'Sedang disewa',
                          'pill-success': r.status === 'Sudah kembali'
                        }"
                      >
                        {{ r.status }}
                      </span>
                    </td>
                    <td class="text-end">
                      <button
                        v-if="r.status !== 'Sudah kembali'"
                        class="btn-himatif py-1 px-3"
                        style="font-size: 12px;"
                        @click="confirmRental(r)"
                      >
                        {{ r.status === 'Menunggu konfirmasi' ? 'Konfirmasi' : 'Tandai Kembali' }}
                      </button>
                      <span v-else class="text-secondary" style="font-size: 12px;">Selesai</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- TAB 2: DAFTAR ALAT -->
          <div v-else>
            <div class="d-flex justify-content-between align-items-center mb-3">
              <input
                v-model="searchQuery"
                type="text"
                class="form-control custom-input"
                style="max-width: 260px;"
                placeholder="Cari nama alat..."
              />
              <span class="text-secondary" style="font-size: 13px;">{{ filteredItems.length }} unit jenis alat</span>
            </div>

            <div class="table-responsive">
              <table class="table align-middle mb-0 custom-table">
                <thead>
                  <tr>
                    <th>NAMA ALAT</th>
                    <th>TARIF SEWA / HARI</th>
                    <th>KETERSEDIAAN</th>
                    <th>STATUS</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in filteredItems" :key="item.id">
                    <td>
                      <div class="d-flex align-items-center gap-3">
                        <div class="prod-avatar">{{ item.code }}</div>
                        <div>
                          <div class="fw-semibold">{{ item.name }}</div>
                          <div class="text-secondary" style="font-size: 12px;">{{ item.category }}</div>
                        </div>
                      </div>
                    </td>
                    <td>Rp{{ item.tariff.toLocaleString('id-ID') }}</td>
                    <td>{{ item.available }} / {{ item.totalUnit }} unit</td>
                    <td>
                      <span class="status-pill" :class="item.available > 0 ? 'pill-success' : 'pill-danger'">
                        {{ item.available > 0 ? 'Tersedia' : 'Sedang Dipinjam' }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Kanan: Form Tambah Alat Inventaris -->
      <div class="col-12 col-xl-4">
        <div class="card-panel">
          <h6 class="fw-bold mb-3" style="font-size: 15px;">Tambah / edit alat</h6>
          <form @submit.prevent="addItem">
            <div class="mb-3">
              <label class="form-label custom-label">Nama alat</label>
              <input
                v-model="form.name"
                type="text"
                class="form-control custom-input"
                placeholder="Contoh: Proyektor Epson"
                required
              />
            </div>

            <div class="mb-3">
              <label class="form-label custom-label">Kategori</label>
              <select v-model="form.category" class="form-select custom-input">
                <option value="Perlengkapan">Perlengkapan</option>
                <option value="Elektronik">Elektronik</option>
                <option value="Dokumentasi">Dokumentasi</option>
                <option value="Audio">Audio</option>
              </select>
            </div>

            <div class="row g-2 mb-3">
              <div class="col-6">
                <label class="form-label custom-label">Total unit</label>
                <input v-model="form.totalUnit" type="number" min="1" class="form-control custom-input" required />
              </div>
              <div class="col-6">
                <label class="form-label custom-label">Tarif / hari (Rp)</label>
                <input v-model="form.tariff" type="number" min="0" class="form-control custom-input" required />
              </div>
            </div>

            <button type="submit" class="btn-himatif w-100">Simpan alat</button>
          </form>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>
.tab-switcher {
  display: inline-flex;
  background-color: #E8ECEF;
  padding: 4px;
  border-radius: 10px;
  gap: 4px;
}

.tab-btn {
  border: none;
  background: transparent;
  padding: 6px 16px;
  border-radius: 7px;
  font-size: 13px;
  font-weight: 500;
  color: #4B5563;
}

.tab-btn.active {
  background-color: #FFFFFF;
  color: #111827;
  font-weight: 600;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
}

.custom-label {
  font-size: 12.5px;
  font-weight: 600;
  color: #374151;
  margin-bottom: 6px;
}

.custom-input {
  border: 1px solid #E5E7EB;
  border-radius: 8px;
  font-size: 13px;
  padding: 8px 12px;
}

.custom-table th {
  font-size: 11px;
  font-weight: 600;
  color: #6B7280;
  border-bottom: 1px solid #E5E7EB;
  padding-bottom: 10px;
}

.custom-table td {
  font-size: 13px;
  padding: 13px 8px;
  border-bottom: 1px solid #F3F4F6;
}

.prod-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background-color: #E0E7FF;
  color: #3730A3;
  font-size: 11.5px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
}

.status-pill {
  display: inline-block;
  padding: 3px 10px;
  border-radius: 999px;
  font-size: 11.5px;
  font-weight: 600;
}

.pill-warning { background-color: #FEF3C7; color: #B45309; }
.pill-info { background-color: #E0E7FF; color: #3730A3; }
.pill-success { background-color: #D1FAE5; color: #0F765E; }
.pill-danger { background-color: #FEE2E2; color: #B91C1C; }
</style>