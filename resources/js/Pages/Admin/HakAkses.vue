<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

// Matriks Ringkasan Role
const roleSummary = [
  { role: 'BPH', label: 'Super Admin', scope: 'Semua menu + Hak Akses (Full CRUD)', color: 'pill-success' },
  { role: 'Danus', label: 'Divisi Danus', scope: 'Dashboard Danus & Kelola Shop / Merchandise', color: 'pill-warning' },
  { role: 'Infokom', label: 'Divisi Infokom', scope: 'Dashboard Infokom, Artikel Formatif, & Kegiatan', color: 'pill-info' },
  { role: 'Manset', label: 'Divisi Manset', scope: 'Dashboard Manset & Penyewaan Inventaris', color: 'pill-purple' }
];

// Daftar Akun Pengurus
const admins = ref([
  { id: 1, initials: 'AB', name: 'Admin BPH', email: 'bph@himatif.polindra.ac.id', role: 'BPH', status: 'Aktif' },
  { id: 2, initials: 'DN', name: 'Koordinator Danus', email: 'danus@himatif.polindra.ac.id', role: 'Danus', status: 'Aktif' },
  { id: 3, initials: 'IK', name: 'Koordinator Infokom', email: 'infokom@himatif.polindra.ac.id', role: 'Infokom', status: 'Aktif' },
  { id: 4, initials: 'MS', name: 'Koordinator Manset', email: 'manset@himatif.polindra.ac.id', role: 'Manset', status: 'Aktif' }
]);

const form = ref({
  name: '',
  email: '',
  role: 'Danus'
});

const addAdmin = () => {
  if (!form.value.name.trim() || !form.value.email.trim()) return;
  const words = form.value.name.trim().split(' ');
  const initials = words.length > 1
    ? (words[0][0] + words[1][0]).toUpperCase()
    : form.value.name.substring(0, 2).toUpperCase();

  admins.value.push({
    id: Date.now(),
    initials,
    name: form.value.name,
    email: form.value.email,
    role: form.value.role,
    status: 'Aktif'
  });

  form.value = { name: '', email: '', role: 'Danus' };
};

const removeAdmin = (id) => {
  admins.value = admins.value.filter(a => a.id !== id);
};
</script>

<template>
  <Head title="Hak Akses - Panel Admin" />

  <AdminLayout
    title="Hak Akses"
    subtitle="Kelola akun pengurus dan pembagian hak akses sistem per divisi (Khusus BPH)."
  >
    <!-- 4 Kartu Matriks Role -->
    <div class="row g-3 mb-4">
      <div v-for="item in roleSummary" :key="item.role" class="col-12 col-sm-6 col-xl-3">
        <div class="card-panel h-100">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="fw-bold text-dark" style="font-size: 14px;">Role {{ item.role }}</span>
            <span class="status-pill" :class="item.color">{{ item.label }}</span>
          </div>
          <div class="text-secondary" style="font-size: 12.5px; line-height: 1.45;">
            {{ item.scope }}
          </div>
        </div>
      </div>
    </div>

    <!-- Split View: Tabel Pengurus & Form Tambah Pengurus -->
    <div class="row g-3 align-items-start">
      <div class="col-12 col-xl-8">
        <div class="card-panel">
          <h6 class="fw-bold mb-3">Daftar Akun Pengurus Aktif</h6>
          <div class="table-responsive">
            <table class="table align-middle mb-0 custom-table">
              <thead>
                <tr>
                  <th>PENGURUS</th>
                  <th>EMAIL</th>
                  <th>ROLE DIVISI</th>
                  <th>STATUS</th>
                  <th class="text-end">AKSI</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="adm in admins" :key="adm.id">
                  <td>
                    <div class="d-flex align-items-center gap-3">
                      <div class="admin-avatar">{{ adm.initials }}</div>
                      <span class="fw-semibold text-dark">{{ adm.name }}</span>
                    </div>
                  </td>
                  <td class="text-secondary">{{ adm.email }}</td>
                  <td>
                    <select v-model="adm.role" class="form-select form-select-sm custom-input" style="max-width: 130px;">
                      <option value="BPH">BPH</option>
                      <option value="Danus">Danus</option>
                      <option value="Infokom">Infokom</option>
                      <option value="Manset">Manset</option>
                    </select>
                  </td>
                  <td>
                    <span class="status-pill pill-success">{{ adm.status }}</span>
                  </td>
                  <td class="text-end">
                    <button
                      v-if="adm.role !== 'BPH'"
                      type="button"
                      class="btn btn-sm btn-outline-danger"
                      style="font-size: 12px;"
                      @click="removeAdmin(adm.id)"
                    >
                      Cabut Akses
                    </button>
                    <span v-else class="text-secondary" style="font-size: 12px;">Utama</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Kanan: Form Tambah Akun Pengurus -->
      <div class="col-12 col-xl-4">
        <div class="card-panel">
          <h6 class="fw-bold mb-3" style="font-size: 15px;">Tambah akun pengurus</h6>
          <form @submit.prevent="addAdmin">
            <div class="mb-3">
              <label class="form-label custom-label">Nama pengurus / divisi</label>
              <input
                v-model="form.name"
                type="text"
                class="form-control custom-input"
                placeholder="Contoh: Staf Danus"
                required
              />
            </div>

            <div class="mb-3">
              <label class="form-label custom-label">Email institusi</label>
              <input
                v-model="form.email"
                type="email"
                class="form-control custom-input"
                placeholder="nama@polindra.ac.id"
                required
              />
            </div>

            <div class="mb-3">
              <label class="form-label custom-label">Tetapkan Role</label>
              <select v-model="form.role" class="form-select custom-input">
                <option value="Danus">Danus (Kelola Shop)</option>
                <option value="Infokom">Infokom (Formatif & Kegiatan)</option>
                <option value="Manset">Manset (Inventaris & Penyewaan)</option>
                <option value="BPH">BPH (Super Admin / Akses Penuh)</option>
              </select>
            </div>

            <button type="submit" class="btn-himatif w-100">Simpan hak akses</button>
          </form>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>
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

.admin-avatar {
  width: 34px;
  height: 34px;
  border-radius: 50%;
  background-color: #101C24;
  color: #FFFFFF;
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

.pill-success { background-color: #D1FAE5; color: #0F765E; }
.pill-warning { background-color: #FEF3C7; color: #B45309; }
.pill-info { background-color: #E0E7FF; color: #1D4ED8; }
.pill-purple { background-color: #EDE9FE; color: #6D28D9; }
</style>