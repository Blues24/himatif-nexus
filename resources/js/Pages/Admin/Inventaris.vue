<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

// Tab aktif: 'daftar' (Daftar alat), 'penyewaan' (Penyewaan), 'dipinjam' (Dipinjam)
const activeTab = ref('daftar');

// Filter & Pencarian
const searchQuery = ref('');
const statusFilter = ref('Semua status');

// Data Inventaris Alat (Sesuai Gambar 4 & 7)
const inventoryItems = ref([
  {
    id: 1,
    code: 'PR',
    name: 'Handy Talky',
    condition: 'Kondisi Baik',
    rate: 10000,
    availableUnits: 10,
    totalUnits: 10,
    status: 'Tersedia',
    statusClass: 'pill-success',
  },
  {
    id: 2,
    code: 'TK',
    name: 'Tripod',
    condition: 'Kondisi baik',
    rate: 10000,
    availableUnits: 1,
    totalUnits: 2,
    status: '1 Tersisa',
    statusClass: 'pill-warning',
  },
  {
    id: 3,
    code: 'KB',
    name: 'Baterai Kamera',
    condition: 'Baterai + Casan',
    rate: 10000,
    availableUnits: 4,
    totalUnits: 6,
    status: 'Tersedia',
    statusClass: 'pill-success',
  },
  {
    id: 4,
    code: 'MK',
    name: 'Backdrop',
    condition: 'Kondisi Baik',
    rate: 25000,
    availableUnits: 2,
    totalUnits: 2,
    status: 'Tersedia',
    statusClass: 'pill-success',
  },
  {
    id: 5,
    code: 'SP',
    name: 'Toa',
    condition: 'Kondisi Baik',
    rate: 45000,
    availableUnits: 0,
    totalUnits: 2,
    status: 'Dipinjam',
    statusClass: 'pill-danger',
  },
  {
    id: 6,
    code: 'LP',
    name: 'Jasa Print',
    condition: 'Tersedia kertas dari Himatif',
    rate: 20000,
    availableUnits: 2,
    totalUnits: 2,
    status: 'Tersedia',
    statusClass: 'pill-success',
  },
]);

// Data Penyewaan Menunggu Konfirmasi (Tab Penyewaan · 4)
const pendingRentals = ref([
  { id: 1, borrower: 'Dina Putri', nim: '2403011', item: 'Proyektor Epson', date: '15 – 16 Okt', duration: '2 hari', total: 70000 },
  { id: 2, borrower: 'Raka Mahendra', nim: '2403022', item: 'Tripod + Kamera Sony', date: '17 Okt', duration: '1 hari', total: 35000 },
  { id: 3, borrower: 'Salsa Nurul', nim: '2303015', item: 'Terminal Kabel Roll', date: '18 – 19 Okt', duration: '2 hari', total: 20000 },
  { id: 4, borrower: 'Fajar Ramadhan', nim: '2503040', item: 'Mikrofon nirkabel', date: '20 Okt', duration: '1 hari', total: 25000 },
]);

// Data Sedang Dipinjam (Tab Dipinjam · 9)
const activeLoans = ref([
  { id: 1, borrower: 'Bagus Setiawan', unit: 'Himpunan', item: 'Proyektor Epson #1', deadline: 'Hari ini, 17.00', status: 'Hari ini' },
  { id: 2, borrower: 'Citra Lestari', unit: 'Dokumentasi', item: 'Kamera Sony Alpha', deadline: 'Hari ini, 18.00', status: 'Hari ini' },
  { id: 3, borrower: 'Panitia Lomba Web', unit: 'Aula', item: 'Sound System Portable', deadline: '19 Okt 2026', status: 'Dipinjam' },
  { id: 4, borrower: 'Divisi Infokom', unit: 'Lab Komputer', item: 'Terminal Kabel Roll (2 unit)', deadline: '20 Okt 2026', status: 'Dipinjam' },
]);

// Form Tambah / Edit Alat
const isEditing = ref(false);
const form = ref({
  id: null,
  name: '',
  rate: 35000,
  totalUnits: 3,
  condition: '',
  photoName: '',
});

// Format Rupiah
const formatRupiah = (val) => {
  return 'Rp' + Number(val || 0).toLocaleString('id-ID');
};

// Filtered Items untuk Tab Daftar Alat
const filteredItems = computed(() => {
  return inventoryItems.value.filter((item) => {
    const matchSearch =
      item.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      item.code.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      item.condition.toLowerCase().includes(searchQuery.value.toLowerCase());

    const matchStatus =
      statusFilter.value === 'Semua status' || item.status === statusFilter.value;

    return matchSearch && matchStatus;
  });
});

// Total count
const totalAlatCount = computed(() => {
  return inventoryItems.value.reduce((acc, curr) => acc + curr.totalUnits, 0);
});

// Reset Form
const resetForm = () => {
  isEditing.value = false;
  form.value = {
    id: null,
    name: '',
    rate: 35000,
    totalUnits: 3,
    condition: '',
    photoName: '',
  };
};

// Edit Item
const editItem = (item) => {
  isEditing.value = true;
  form.value = {
    id: item.id,
    name: item.name,
    rate: item.rate,
    totalUnits: item.totalUnits,
    condition: item.condition,
    photoName: '',
  };
};

// Delete Item
const deleteItem = (id) => {
  if (confirm('Yakin ingin menghapus alat ini dari daftar inventaris?')) {
    inventoryItems.value = inventoryItems.value.filter((i) => i.id !== id);
  }
};

// Handle File Change
const handleFileChange = (e) => {
  const file = e.target.files[0];
  if (file) {
    form.value.photoName = file.name;
  }
};

// Save Item
const saveItem = () => {
  if (!form.value.name) return;

  if (isEditing.value && form.value.id) {
    const idx = inventoryItems.value.findIndex((i) => i.id === form.value.id);
    if (idx !== -1) {
      inventoryItems.value[idx] = {
        ...inventoryItems.value[idx],
        name: form.value.name,
        rate: Number(form.value.rate),
        totalUnits: Number(form.value.totalUnits),
        availableUnits: Number(form.value.totalUnits),
        condition: form.value.condition,
      };
    }
  } else {
    const code = form.value.name.substring(0, 2).toUpperCase();
    inventoryItems.value.push({
      id: Date.now(),
      code,
      name: form.value.name,
      condition: form.value.condition || 'Kondisi Baik',
      rate: Number(form.value.rate),
      availableUnits: Number(form.value.totalUnits),
      totalUnits: Number(form.value.totalUnits),
      status: 'Tersedia',
      statusClass: 'pill-success',
    });
  }
  resetForm();
};

// Confirm Rental
const confirmRental = (id) => {
  pendingRentals.value = pendingRentals.value.filter((r) => r.id !== id);
};

// Mark Returned
const markReturned = (id) => {
  activeLoans.value = activeLoans.value.filter((l) => l.id !== id);
};
</script>

<template>
  <Head title="Inventaris - Panel Admin HIMATIF" />

  <AdminLayout
    title="Inventaris"
    subtitle="Kelola peralatan sekretariat HIMATIF dan verifikasi peminjaman/penyewaan Manset."
  >
    <!-- Action Top Right -->
    <template #actions>
      <button type="button" class="btn-cyan" @click="resetForm">
        + Tambah alat
      </button>
    </template>

    <!-- Segmented Tabs (Gambar 4 & 7) -->
    <div class="tab-switcher mb-3">
      <button
        type="button"
        class="tab-btn"
        :class="{ active: activeTab === 'daftar' }"
        @click="activeTab = 'daftar'"
      >
        Daftar alat · {{ totalAlatCount }}
      </button>
      <button
        type="button"
        class="tab-btn"
        :class="{ active: activeTab === 'penyewaan' }"
        @click="activeTab = 'penyewaan'"
      >
        Penyewaan · {{ pendingRentals.length }}
      </button>
      <button
        type="button"
        class="tab-btn"
        :class="{ active: activeTab === 'dipinjam' }"
        @click="activeTab = 'dipinjam'"
      >
        Dipinjam · {{ activeLoans.length }}
      </button>
    </div>

    <!-- Layout Grid: Kiri Tabel (8 Kolom), Kanan Form (4 Kolom) -->
    <div class="row g-3 align-items-start">
      <!-- Kolom Kiri: Tabel -->
      <div class="col-12 col-xl-8">
        <div class="card-panel">
          <!-- TAB 1: DAFTAR ALAT -->
          <div v-if="activeTab === 'daftar'">
            <!-- Filter Bar -->
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
              <div class="d-flex align-items-center gap-2 flex-grow-1" style="max-width: 480px;">
                <input
                  v-model="searchQuery"
                  type="text"
                  class="form-control custom-input"
                  placeholder="Cari nama atau kode alat/jasa"
                />
                <select v-model="statusFilter" class="form-select custom-input" style="max-width: 150px;">
                  <option value="Semua status">Semua status</option>
                  <option value="Tersedia">Tersedia</option>
                  <option value="1 Tersisa">1 Tersisa</option>
                  <option value="Dipinjam">Dipinjam</option>
                </select>
              </div>
              <span class="text-secondary" style="font-size: 13px;">
                {{ totalAlatCount }} unit alat
              </span>
            </div>

            <!-- Tabel Daftar Alat -->
            <div class="table-responsive">
              <table class="table align-middle mb-0 custom-table">
                <thead>
                  <tr>
                    <th>NAMA ALAT</th>
                    <th>TARIF / HARI</th>
                    <th>TERSEDIA</th>
                    <th>STATUS</th>
                    <th class="text-end">AKSI</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in filteredItems" :key="item.id">
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <div class="item-avatar">{{ item.code }}</div>
                        <div>
                          <div class="fw-semibold text-dark" style="font-size: 13.5px;">{{ item.name }}</div>
                          <div class="text-secondary" style="font-size: 11.5px;">{{ item.condition }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="fw-medium text-dark">{{ formatRupiah(item.rate) }}</td>
                    <td class="fw-semibold">{{ item.availableUnits }} / {{ item.totalUnits }}</td>
                    <td>
                      <span class="status-pill" :class="item.statusClass">{{ item.status }}</span>
                    </td>
                    <td class="text-end">
                      <div class="d-inline-flex gap-1">
                        <button
                          type="button"
                          class="action-icon-btn"
                          title="Edit alat"
                          @click="editItem(item)"
                        >
                          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 20h9"></path>
                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                          </svg>
                        </button>
                        <button
                          type="button"
                          class="action-icon-btn delete"
                          title="Hapus alat"
                          @click="deleteItem(item.id)"
                        >
                          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                          </svg>
                        </button>
                      </div>
                    </td>
                  </tr>
                  <tr v-if="filteredItems.length === 0">
                    <td colspan="5" class="text-center text-secondary py-4">
                      Tidak ada alat yang cocok dengan pencarian.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- TAB 2: PENYEWAAN MENUNGGU KONFIRMASI -->
          <div v-else-if="activeTab === 'penyewaan'">
            <div class="mb-3">
              <h6 class="fw-bold mb-1">Permohonan Penyewaan Menunggu Verifikasi</h6>
              <p class="text-secondary mb-0" style="font-size: 12.5px;">Periksa ketersediaan alat dan konfirmasi jadwal sewa.</p>
            </div>
            <div class="table-responsive">
              <table class="table align-middle mb-0 custom-table">
                <thead>
                  <tr>
                    <th>PENYEWA</th>
                    <th>ALAT & DURASI</th>
                    <th>TANGGAL PAKAI</th>
                    <th>TOTAL</th>
                    <th class="text-end">AKSI</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="r in pendingRentals" :key="r.id">
                    <td>
                      <div class="fw-semibold text-dark" style="font-size: 13.5px;">{{ r.borrower }}</div>
                      <div class="text-secondary font-monospace" style="font-size: 11.5px;">{{ r.nim }}</div>
                    </td>
                    <td>
                      <div class="fw-medium">{{ r.item }}</div>
                      <div class="text-secondary" style="font-size: 11.5px;">{{ r.duration }}</div>
                    </td>
                    <td class="text-secondary">{{ r.date }}</td>
                    <td class="fw-semibold text-dark">{{ formatRupiah(r.total) }}</td>
                    <td class="text-end">
                      <button type="button" class="btn-cyan-sm" @click="confirmRental(r.id)">
                        Konfirmasi
                      </button>
                    </td>
                  </tr>
                  <tr v-if="pendingRentals.length === 0">
                    <td colspan="5" class="text-center text-secondary py-4">
                      Tidak ada penyewaan menunggu konfirmasi.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- TAB 3: ALAT SEDANG DIPINJAM -->
          <div v-else-if="activeTab === 'dipinjam'">
            <div class="mb-3">
              <h6 class="fw-bold mb-1">Alat Sedang Dipinjam / Disewa</h6>
              <p class="text-secondary mb-0" style="font-size: 12.5px;">Daftar inventaris yang sedang aktif di luar sekretariat.</p>
            </div>
            <div class="table-responsive">
              <table class="table align-middle mb-0 custom-table">
                <thead>
                  <tr>
                    <th>PENYEWA & UNIT</th>
                    <th>ALAT</th>
                    <th>BATAS KEMBALI</th>
                    <th class="text-end">AKSI</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="loan in activeLoans" :key="loan.id">
                    <td>
                      <div class="fw-semibold text-dark" style="font-size: 13.5px;">{{ loan.borrower }}</div>
                      <div class="text-secondary" style="font-size: 11.5px;">{{ loan.unit }}</div>
                    </td>
                    <td class="fw-medium">{{ loan.item }}</td>
                    <td>
                      <span class="status-pill" :class="loan.status === 'Hari ini' ? 'pill-warning' : 'pill-info'">
                        {{ loan.deadline }}
                      </span>
                    </td>
                    <td class="text-end">
                      <button type="button" class="btn-outline-cyan-sm" @click="markReturned(loan.id)">
                        Tandai kembali
                      </button>
                    </td>
                  </tr>
                  <tr v-if="activeLoans.length === 0">
                    <td colspan="4" class="text-center text-secondary py-4">
                      Tidak ada alat yang sedang dipinjam saat ini.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Kolom Kanan: Form Tambah / Edit Alat -->
      <div class="col-12 col-xl-4">
        <div class="card-panel">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="fw-bold mb-0" style="font-size: 15px;">
              {{ isEditing ? 'Edit alat' : 'Tambah / edit alat' }}
            </h6>
          </div>

          <form @submit.prevent="saveItem">
            <!-- Nama Alat -->
            <div class="mb-3">
              <label class="form-label custom-label">Nama alat sekretariat</label>
              <input
                v-model="form.name"
                type="text"
                class="form-control custom-input"
                placeholder="Contoh: Proyektor Epson"
                required
              />
            </div>

            <!-- Tarif Sewa & Total Unit -->
            <div class="row g-2 mb-3">
              <div class="col-7">
                <label class="form-label custom-label">Tarif sewa / hari (Rp)</label>
                <input
                  v-model.number="form.rate"
                  type="number"
                  class="form-control custom-input"
                  placeholder="35000"
                  required
                />
              </div>
              <div class="col-5">
                <label class="form-label custom-label">Total unit</label>
                <input
                  v-model.number="form.totalUnits"
                  type="number"
                  class="form-control custom-input"
                  placeholder="3"
                  required
                />
              </div>
            </div>

            <!-- Kelengkapan & Kondisi -->
            <div class="mb-3">
              <label class="form-label custom-label">Kelengkapan & kondisi</label>
              <textarea
                v-model="form.condition"
                rows="3"
                class="form-control custom-input"
                placeholder="Kabel HDMI, tas, dan kondisi fisik"
              ></textarea>
            </div>

            <!-- Foto Alat Dropzone -->
            <div class="mb-3">
              <label class="form-label custom-label">Foto alat</label>
              <label class="upload-dropzone d-flex flex-column align-items-center justify-content-center text-center">
                <input type="file" class="d-none" accept=".jpg,.jpeg,.png" @change="handleFileChange" />
                <span class="text-secondary" style="font-size: 12.5px;">
                  <strong style="color: #00A3FF;">Pilih file</strong> atau seret ke sini
                </span>
                <span class="text-muted" style="font-size: 11px; margin-top: 2px;">
                  {{ form.photoName || 'JPG atau PNG, maks. 2 MB' }}
                </span>
              </label>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex align-items-center gap-2 mb-3">
              <button type="submit" class="btn-cyan flex-grow-1">
                {{ isEditing ? 'Perbarui alat' : 'Simpan alat' }}
              </button>
              <button type="button" class="btn-cancel" @click="resetForm">
                Batal
              </button>
            </div>

            <!-- Footer Warning Note -->
            <p class="text-secondary mb-0" style="font-size: 11px; line-height: 1.45;">
              Peminjaman wajib meninggalkan KTM aktif dan diverifikasi oleh pengurus Manset di sekretariat HIMATIF.
            </p>
          </form>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>
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

.tab-switcher {
  display: inline-flex;
  background-color: #ECEFF3;
  padding: 4px;
  border-radius: 8px;
  gap: 2px;
}

.tab-btn {
  border: none;
  background: transparent;
  color: #4B5563;
  font-size: 13px;
  font-weight: 600;
  padding: 6px 14px;
  border-radius: 6px;
  transition: all 0.15s ease;
}

.tab-btn.active {
  background-color: #FFFFFF;
  color: #111827;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}

.custom-label {
  font-size: 12.5px;
  font-weight: 600;
  color: #374151;
  margin-bottom: 5px;
}

.custom-input {
  border: 1px solid #E5E7EB;
  border-radius: 8px;
  font-size: 13px;
  padding: 8px 12px;
  color: #111827;
}

.custom-input:focus {
  border-color: #00A3FF;
  box-shadow: 0 0 0 3px rgba(0, 163, 255, 0.12);
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
  padding: 13px 8px;
  border-bottom: 1px solid #F3F4F6;
}

.custom-table tbody tr:last-child td {
  border-bottom: none;
}

.item-avatar {
  width: 34px;
  height: 34px;
  border-radius: 8px;
  background-color: #E6F4F1;
  color: #0F765E;
  font-size: 11.5px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.status-pill {
  display: inline-block;
  padding: 3px 10px;
  border-radius: 999px;
  font-size: 11.5px;
  font-weight: 600;
}

.pill-success { background-color: #D1FAE5; color: #0F765E; }
.pill-warning { background-color: #FEF3C7; color: #B45309; }
.pill-danger { background-color: #FEE2E2; color: #B91C1C; }
.pill-info { background-color: #E0E7FF; color: #3730A3; }
.pill-neutral { background-color: #F3F4F6; color: #4B5563; }

.action-icon-btn {
  width: 30px;
  height: 30px;
  border-radius: 7px;
  border: 1px solid #E5E7EB;
  background: #FFFFFF;
  color: #4B5563;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  transition: all 0.15s ease;
}

.action-icon-btn:hover {
  background-color: #F9FAFB;
  color: #111827;
}

.action-icon-btn.delete { color: #DC2626; }
.action-icon-btn.delete:hover {
  background-color: #FEF2F2;
  border-color: #FECACA;
}

.upload-dropzone {
  border: 1.5px dashed #D1D5DB;
  border-radius: 10px;
  padding: 20px 14px;
  cursor: pointer;
  background-color: #FAFAFA;
  transition: all 0.15s ease;
}

.upload-dropzone:hover {
  border-color: #00A3FF;
  background-color: #F0F9FF;
}

.btn-cancel {
  border: 1px solid #E5E7EB;
  background-color: #FFFFFF;
  color: #374151;
  border-radius: 8px;
  padding: 8px 18px;
  font-size: 13.5px;
  font-weight: 600;
}

.btn-cancel:hover {
  background-color: #F9FAFB;
}
</style>
