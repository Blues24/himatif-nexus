<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

// Tab aktif: 'produk' atau 'pesanan'
const activeTab = ref('produk');

// Filter & Pencarian
const searchQuery = ref('');
const statusFilter = ref('Semua status');

// Data Produk (Sesuai referensi desain)
const products = ref([
  { id: 1, code: 'KS', name: 'Kaos HIMATIF', desc: 'Katun combed 30s', price: 85000, stock: 42 },
  { id: 2, code: 'HD', name: 'Hoodie HIMATIF', desc: 'Fleece tebal', price: 185000, stock: 6 },
  { id: 3, code: 'TB', name: 'Tote bag', desc: 'Kanvas', price: 45000, stock: 18 },
  { id: 4, code: 'ST', name: 'Paket stiker', desc: 'Isi 5 lembar', price: 15000, stock: 60 },
  { id: 5, code: 'LY', name: 'Lanyard HIMATIF', desc: 'Sablon dua sisi', price: 20000, stock: 0 },
  { id: 6, code: 'GC', name: 'Gantungan kunci', desc: 'Akrilik', price: 12000, stock: 35 }
]);

// Data Pesanan Masuk (Untuk tab ke-2)
const incomingOrders = ref([
  { id: 'ORD-0142', buyer: 'Dimas Pratama', nim: '2403012', item: 'Kaos HIMATIF x2', total: 170000, status: 'Menunggu verifikasi' },
  { id: 'ORD-0141', buyer: 'Nabila Putri', nim: '2403045', item: 'Hoodie HIMATIF x1', total: 185000, status: 'Menunggu verifikasi' },
  { id: 'ORD-0140', buyer: 'Rizky Fauzan', nim: '2303019', item: 'Paket stiker x1', total: 15000, status: 'Terverifikasi' },
  { id: 'ORD-0139', buyer: 'Siti Aisyah', nim: '2503008', item: 'Tote bag x1', total: 45000, status: 'Sudah diambil' }
]);

// Helper penentu status otomatis dari jumlah stok
const getProductStatus = (stock) => {
  if (stock <= 0) return { label: 'Habis', type: 'danger' };
  if (stock <= 10) return { label: 'Stok menipis', type: 'warning' };
  return { label: 'Tersedia', type: 'success' };
};

// Format Rupiah (contoh: Rp85.000)
const formatRupiah = (angka) => {
  return 'Rp' + Number(angka).toLocaleString('id-ID');
};

// Produk yang sudah difilter berdasarkan input cari & dropdown status
const filteredProducts = computed(() => {
  return products.value.filter((item) => {
    const matchName = item.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                      item.desc.toLowerCase().includes(searchQuery.value.toLowerCase());
    const statusLabel = getProductStatus(item.stock).label;
    const matchStatus = statusFilter.value === 'Semua status' || statusLabel === statusFilter.value;
    return matchName && matchStatus;
  });
});

// State Form Tambah / Edit Produk di panel kanan
const isEditing = ref(false);
const form = ref({
  id: null,
  name: '',
  price: 85000,
  stock: 0,
  desc: '',
  imageName: ''
});

const resetForm = () => {
  isEditing.value = false;
  form.value = {
    id: null,
    name: '',
    price: 85000,
    stock: 0,
    desc: '',
    imageName: ''
  };
};

const editProduct = (product) => {
  isEditing.value = true;
  form.value = {
    id: product.id,
    name: product.name,
    price: product.price,
    stock: product.stock,
    desc: product.desc,
    imageName: ''
  };
};

const saveProduct = () => {
  if (!form.value.name.trim()) return;

  // Buat inisial 2 huruf dari nama produk
  const words = form.value.name.trim().split(' ');
  const code = words.length > 1
    ? (words[0][0] + words[1][0]).toUpperCase()
    : form.value.name.substring(0, 2).toUpperCase();

  if (isEditing.value && form.value.id !== null) {
    const idx = products.value.findIndex(p => p.id === form.value.id);
    if (idx !== -1) {
      products.value[idx] = {
        ...products.value[idx],
        code,
        name: form.value.name,
        price: Number(form.value.price),
        stock: Number(form.value.stock),
        desc: form.value.desc || '-'
      };
    }
  } else {
    products.value.unshift({
      id: Date.now(),
      code,
      name: form.value.name,
      price: Number(form.value.price),
      stock: Number(form.value.stock),
      desc: form.value.desc || '-'
    });
  }

  resetForm();
};

const deleteProduct = (id) => {
  products.value = products.value.filter(p => p.id !== id);
  if (form.value.id === id) resetForm();
};

const handleFileChange = (e) => {
  const file = e.target.files?.[0];
  if (file) {
    form.value.imageName = file.name;
  }
};

const verifyOrder = (order) => {
  if (order.status === 'Menunggu verifikasi') {
    order.status = 'Terverifikasi';
  } else if (order.status === 'Terverifikasi') {
    order.status = 'Sudah diambil';
  }
};
</script>

<template>
  <Head title="Danus / Shop - Panel Admin" />

  <AdminLayout
    title="Danus / Shop"
    subtitle="Kelola produk merchandise resmi HIMATIF dan verifikasi pesanan."
  >
    <!-- Tombol Kanan Atas Header -->
    <template #actions>
      <button class="btn-himatif" @click="resetForm(); activeTab = 'produk'">
        + Tambah produk
      </button>
    </template>

    <!-- Segmented Tabs (Produk | Pesanan masuk · 7) -->
    <div class="tab-switcher mb-3">
      <button
        type="button"
        class="tab-btn"
        :class="{ active: activeTab === 'produk' }"
        @click="activeTab = 'produk'"
      >
        Produk
      </button>
      <button
        type="button"
        class="tab-btn"
        :class="{ active: activeTab === 'pesanan' }"
        @click="activeTab = 'pesanan'"
      >
        Pesanan masuk · 7
      </button>
    </div>

    <!-- TAB 1: KELOLA PRODUK (Split View Kiri Tabel, Kanan Form) -->
    <div v-if="activeTab === 'produk'" class="row g-3 align-items-start">
      <!-- Kolom Kiri: Filter & Tabel Produk -->
      <div class="col-12 col-xl-8">
        <div class="card-panel">
          <!-- Baris Filter & Search -->
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div class="d-flex flex-wrap align-items-center gap-2 flex-grow-1">
              <input
                v-model="searchQuery"
                type="text"
                class="form-control custom-input"
                style="max-width: 260px;"
                placeholder="Cari nama produk"
              />
              <select
                v-model="statusFilter"
                class="form-select custom-input"
                style="max-width: 170px;"
              >
                <option value="Semua status">Semua status</option>
                <option value="Tersedia">Tersedia</option>
                <option value="Stok menipis">Stok menipis</option>
                <option value="Habis">Habis</option>
              </select>
            </div>
            <div class="text-secondary" style="font-size: 13px;">
              {{ filteredProducts.length }} produk
            </div>
          </div>

          <!-- Tabel Produk -->
          <div class="table-responsive">
            <table class="table align-middle mb-0 custom-table">
              <thead>
                <tr>
                  <th>PRODUK</th>
                  <th>HARGA</th>
                  <th>STOK</th>
                  <th>STATUS</th>
                  <th class="text-end">AKSI</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in filteredProducts" :key="item.id">
                  <td>
                    <div class="d-flex align-items-center gap-3">
                      <div class="prod-avatar">{{ item.code }}</div>
                      <div>
                        <div class="fw-semibold text-dark" style="font-size: 13.5px;">{{ item.name }}</div>
                        <div class="text-secondary" style="font-size: 12px;">{{ item.desc }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="text-dark">{{ formatRupiah(item.price) }}</td>
                  <td class="text-dark">{{ item.stock }}</td>
                  <td>
                    <span
                      class="status-pill"
                      :class="`pill-${getProductStatus(item.stock).type}`"
                    >
                      {{ getProductStatus(item.stock).label }}
                    </span>
                  </td>
                  <td class="text-end">
                    <div class="d-inline-flex gap-1">
                      <!-- Tombol Edit -->
                      <button
                        type="button"
                        class="action-icon-btn"
                        title="Edit produk"
                        @click="editProduct(item)"
                      >
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <path d="M12 20h9"></path>
                          <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                        </svg>
                      </button>
                      <!-- Tombol Hapus -->
                      <button
                        type="button"
                        class="action-icon-btn delete"
                        title="Hapus produk"
                        @click="deleteProduct(item.id)"
                      >
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <polyline points="3 6 5 6 21 6"></polyline>
                          <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                      </button>
                    </div>
                  </td>
                </tr>
                <tr v-if="filteredProducts.length === 0">
                  <td colspan="5" class="text-center text-secondary py-4">
                    Produk tidak ditemukan.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Kolom Kanan: Form Tambah / Edit Produk -->
      <div class="col-12 col-xl-4">
        <div class="card-panel">
          <h6 class="fw-bold mb-3" style="font-size: 15px;">
            {{ isEditing ? 'Edit produk' : 'Tambah / edit produk' }}
          </h6>

          <form @submit.prevent="saveProduct">
            <div class="mb-3">
              <label class="form-label custom-label">Nama produk</label>
              <input
                v-model="form.name"
                type="text"
                class="form-control custom-input"
                placeholder="Contoh: Kaos HIMATIF"
                required
              />
            </div>

            <div class="row g-2 mb-3">
              <div class="col-7">
                <label class="form-label custom-label">Harga (Rp)</label>
                <input
                  v-model="form.price"
                  type="number"
                  min="0"
                  class="form-control custom-input"
                  required
                />
              </div>
              <div class="col-5">
                <label class="form-label custom-label">Stok</label>
                <input
                  v-model="form.stock"
                  type="number"
                  min="0"
                  class="form-control custom-input"
                  required
                />
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label custom-label">Deskripsi</label>
              <textarea
                v-model="form.desc"
                rows="3"
                class="form-control custom-input"
                placeholder="Bahan, ukuran, dan keterangan lain"
              ></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label custom-label">Gambar produk</label>
              <label class="upload-dropzone d-flex flex-column align-items-center justify-content-center text-center">
                <input type="file" class="d-none" accept=".jpg,.jpeg,.png" @change="handleFileChange" />
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6B7280" stroke-width="2" class="mb-1">
                  <path d="M12 19V5M5 12l7-7 7 7" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div style="font-size: 12.5px;">
                  <span class="fw-semibold" style="color: #0F765E;">Pilih file</span>
                  <span class="text-secondary"> atau seret ke sini</span>
                </div>
                <div class="text-secondary" style="font-size: 11px;">
                  {{ form.imageName || 'JPG atau PNG, maks. 2 MB' }}
                </div>
              </label>
            </div>

            <div class="d-flex align-items-center gap-2 mb-3">
              <button type="submit" class="btn-himatif flex-grow-1">
                Simpan produk
              </button>
              <button type="button" class="btn-cancel" @click="resetForm">
                Batal
              </button>
            </div>

            <p class="mb-0 text-secondary" style="font-size: 11.5px; line-height: 1.45;">
              Pembayaran dikonfirmasi lewat WhatsApp dan pengambilan barang dilakukan di sekretariat HIMATIF.
            </p>
          </form>
        </div>
      </div>
    </div>

    <!-- TAB 2: PESANAN MASUK -->
    <div v-else class="card-panel">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h6 class="fw-bold mb-0">Daftar Pesanan Masuk</h6>
        <span class="text-secondary" style="font-size: 13px;">{{ incomingOrders.length }} pesanan</span>
      </div>

      <div class="table-responsive">
        <table class="table align-middle mb-0 custom-table">
          <thead>
            <tr>
              <th>NO. PESANAN</th>
              <th>PEMESAN</th>
              <th>PRODUK</th>
              <th>TOTAL</th>
              <th>STATUS</th>
              <th class="text-end">AKSI</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="order in incomingOrders" :key="order.id">
              <td class="font-monospace text-secondary" style="font-size: 12px;">{{ order.id }}</td>
              <td>
                <div class="fw-semibold">{{ order.buyer }}</div>
                <div class="text-secondary" style="font-size: 11.5px;">NIM {{ order.nim }}</div>
              </td>
              <td>{{ order.item }}</td>
              <td class="fw-medium">{{ formatRupiah(order.total) }}</td>
              <td>
                <span
                  class="status-pill"
                  :class="{
                    'pill-warning': order.status === 'Menunggu verifikasi',
                    'pill-success': order.status === 'Terverifikasi',
                    'pill-neutral': order.status === 'Sudah diambil'
                  }"
                >
                  {{ order.status }}
                </span>
              </td>
              <td class="text-end">
                <button
                  v-if="order.status !== 'Sudah diambil'"
                  class="btn-himatif py-1 px-3"
                  style="font-size: 12px;"
                  @click="verifyOrder(order)"
                >
                  {{ order.status === 'Menunggu verifikasi' ? 'Verifikasi WA' : 'Tandai Diambil' }}
                </button>
                <span v-else class="text-secondary" style="font-size: 12px;">Selesai</span>
              </td>
            </tr>
          </tbody>
        </table>
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
  transition: all 0.15s ease;
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
  color: #111827;
}

.custom-input:focus {
  border-color: #0F765E;
  box-shadow: 0 0 0 3px rgba(15, 118, 94, 0.12);
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

.prod-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
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

.pill-success {
  background-color: #D1FAE5;
  color: #0F765E;
}

.pill-warning {
  background-color: #FEF3C7;
  color: #B45309;
}

.pill-danger {
  background-color: #FEE2E2;
  color: #B91C1C;
}

.pill-neutral {
  background-color: #F3F4F6;
  color: #4B5563;
}

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

.action-icon-btn.delete {
  color: #DC2626;
}

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
  border-color: #0F765E;
  background-color: #F4FBF9;
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