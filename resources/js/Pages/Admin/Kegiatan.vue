<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const activeTab = ref('Semua');
const searchQuery = ref('');

// Data Kegiatan (Sinkron dengan Dashboard & Halaman Publik)
const events = ref([
  {
    id: 1,
    day: '18',
    month: 'OKT',
    dateInput: '2026-10-18',
    title: 'Pelatihan Dasar Laravel',
    category: 'Workshop',
    location: 'Lab Komputer · 09.00 WIB',
    status: 'Akan datang',
    desc: 'Pelatihan intensif framework Laravel dasar untuk mahasiswa Teknik Informatika.'
  },
  {
    id: 2,
    day: '25',
    month: 'OKT',
    dateInput: '2026-10-25',
    title: 'Lomba Desain Web (ICONFEST)',
    category: 'Perlombaan',
    location: 'Pendaftaran dibuka',
    status: 'Akan datang',
    desc: 'Kompetisi desain UI/UX dan Web Development tingkat nasional.'
  },
  {
    id: 3,
    day: '02',
    month: 'NOV',
    dateInput: '2026-11-02',
    title: 'Rapat Kerja Pengurus',
    category: 'Internal',
    location: 'Internal BPH',
    status: 'Draf',
    desc: 'Evaluasi program kerja tengah semester bersama seluruh kepala divisi.'
  },
  {
    id: 4,
    day: '02',
    month: 'DES',
    dateInput: '2026-12-02',
    title: 'Informatics Care: Donor Darah & Donasi',
    category: 'IT CAST',
    location: 'Pleno POLINDRA',
    status: 'Akan datang',
    desc: 'Kolaborasi HIMATIF bersama kampus dalam kegiatan sosial.'
  },
  {
    id: 5,
    day: '18',
    month: 'DES',
    dateInput: '2026-12-18',
    title: 'Latihan Keterampilan Manajemen Mahasiswa',
    category: 'LKMM-D',
    location: 'Villa 23 Indramayu',
    status: 'Akan datang',
    desc: 'Melatih mahasiswa tingkat 1 agar terampil dalam manajemen organisasi.'
  }
]);

const filteredEvents = computed(() => {
  return events.value.filter(ev => {
    const matchTab = activeTab.value === 'Semua' || ev.status === activeTab.value;
    const matchSearch = ev.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                        ev.location.toLowerCase().includes(searchQuery.value.toLowerCase());
    return matchTab && matchSearch;
  });
});

// State Form Tambah / Edit Kegiatan
const isEditing = ref(false);
const form = ref({
  id: null,
  title: '',
  category: 'Workshop',
  dateInput: '2026-10-18',
  location: '',
  status: 'Akan datang',
  desc: ''
});

const monthNames = ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AGU', 'SEP', 'OKT', 'NOV', 'DES'];

const resetForm = () => {
  isEditing.value = false;
  form.value = {
    id: null,
    title: '',
    category: 'Workshop',
    dateInput: '2026-10-18',
    location: '',
    status: 'Akan datang',
    desc: ''
  };
};

const editEvent = (ev) => {
  isEditing.value = true;
  form.value = {
    id: ev.id,
    title: ev.title,
    category: ev.category,
    dateInput: ev.dateInput,
    location: ev.location,
    status: ev.status,
    desc: ev.desc
  };
};

const saveEvent = () => {
  if (!form.value.title.trim()) return;

  const d = new Date(form.value.dateInput || '2026-10-18');
  const day = String(d.getDate()).padStart(2, '0');
  const month = monthNames[d.getMonth()] || 'OKT';

  if (isEditing.value && form.value.id !== null) {
    const idx = events.value.findIndex(e => e.id === form.value.id);
    if (idx !== -1) {
      events.value[idx] = {
        ...events.value[idx],
        day,
        month,
        dateInput: form.value.dateInput,
        title: form.value.title,
        category: form.value.category,
        location: form.value.location || 'Kampus POLINDRA',
        status: form.value.status,
        desc: form.value.desc
      };
    }
  } else {
    events.value.unshift({
      id: Date.now(),
      day,
      month,
      dateInput: form.value.dateInput,
      title: form.value.title,
      category: form.value.category,
      location: form.value.location || 'Kampus POLINDRA',
      status: form.value.status,
      desc: form.value.desc
    });
  }

  resetForm();
};

const deleteEvent = (id) => {
  events.value = events.value.filter(e => e.id !== id);
  if (form.value.id === id) resetForm();
};
</script>

<template>
  <Head title="Kegiatan - Panel Admin" />

  <AdminLayout
    title="Kegiatan"
    subtitle="Kelola agenda program kerja, workshop, dan jadwal kegiatan HIMATIF (Divisi Infokom)."
  >
    <template #actions>
      <button class="btn-himatif" @click="resetForm">
        + Tambah kegiatan
      </button>
    </template>

    <!-- Tab Switcher -->
    <div class="tab-switcher mb-3">
      <button
        type="button"
        class="tab-btn"
        :class="{ active: activeTab === 'Semua' }"
        @click="activeTab = 'Semua'"
      >
        Semua · {{ events.length }}
      </button>
      <button
        type="button"
        class="tab-btn"
        :class="{ active: activeTab === 'Akan datang' }"
        @click="activeTab = 'Akan datang'"
      >
        Akan datang · {{ events.filter(e => e.status === 'Akan datang').length }}
      </button>
      <button
        type="button"
        class="tab-btn"
        :class="{ active: activeTab === 'Draf' }"
        @click="activeTab = 'Draf'"
      >
        Draf · {{ events.filter(e => e.status === 'Draf').length }}
      </button>
    </div>

    <!-- Split View: Kiri Daftar Kegiatan, Kanan Form Editor -->
    <div class="row g-3 align-items-start">
      <div class="col-12 col-xl-8">
        <div class="card-panel">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <input
              v-model="searchQuery"
              type="text"
              class="form-control custom-input"
              style="max-width: 280px;"
              placeholder="Cari nama kegiatan atau lokasi..."
            />
            <span class="text-secondary" style="font-size: 13px;">{{ filteredEvents.length }} agenda</span>
          </div>

          <div class="table-responsive">
            <table class="table align-middle mb-0 custom-table">
              <thead>
                <tr>
                  <th>AGENDA KEGIATAN</th>
                  <th>KATEGORI</th>
                  <th>LOKASI / WAKTU</th>
                  <th>STATUS</th>
                  <th class="text-end">AKSI</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="ev in filteredEvents" :key="ev.id">
                  <td>
                    <div class="d-flex align-items-center gap-3">
                      <div class="date-badge text-center">
                        <div class="fw-bold lh-1" style="font-size: 14px;">{{ ev.day }}</div>
                        <div style="font-size: 9px; font-weight: 600;">{{ ev.month }}</div>
                      </div>
                      <div>
                        <div class="fw-semibold text-dark" style="font-size: 13.5px;">{{ ev.title }}</div>
                        <div class="text-secondary text-truncate" style="font-size: 12px; max-width: 220px;">
                          {{ ev.desc }}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span class="status-pill pill-neutral">{{ ev.category }}</span>
                  </td>
                  <td class="text-secondary">{{ ev.location }}</td>
                  <td>
                    <span
                      class="status-pill"
                      :class="ev.status === 'Akan datang' ? 'pill-info' : 'pill-neutral'"
                    >
                      {{ ev.status }}
                    </span>
                  </td>
                  <td class="text-end">
                    <div class="d-inline-flex gap-1">
                      <button type="button" class="action-icon-btn" @click="editEvent(ev)" title="Edit">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <path d="M12 20h9"></path>
                          <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                        </svg>
                      </button>
                      <button type="button" class="action-icon-btn delete" @click="deleteEvent(ev.id)" title="Hapus">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <polyline points="3 6 5 6 21 6"></polyline>
                          <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Kanan: Form Tambah / Edit Kegiatan -->
      <div class="col-12 col-xl-4">
        <div class="card-panel">
          <h6 class="fw-bold mb-3" style="font-size: 15px;">
            {{ isEditing ? 'Edit kegiatan' : 'Tambah / edit kegiatan' }}
          </h6>

          <form @submit.prevent="saveEvent">
            <div class="mb-3">
              <label class="form-label custom-label">Nama kegiatan</label>
              <input
                v-model="form.title"
                type="text"
                class="form-control custom-input"
                placeholder="Contoh: Pelatihan Dasar Laravel"
                required
              />
            </div>

            <div class="row g-2 mb-3">
              <div class="col-6">
                <label class="form-label custom-label">Kategori</label>
                <select v-model="form.category" class="form-select custom-input">
                  <option value="Workshop">Workshop</option>
                  <option value="Perlombaan">Perlombaan</option>
                  <option value="LKMM-D">LKMM-D</option>
                  <option value="IT CAST">IT CAST</option>
                  <option value="Internal">Internal</option>
                </select>
              </div>
              <div class="col-6">
                <label class="form-label custom-label">Tanggal pelaksanaan</label>
                <input v-model="form.dateInput" type="date" class="form-control custom-input" required />
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label custom-label">Lokasi & waktu</label>
              <input
                v-model="form.location"
                type="text"
                class="form-control custom-input"
                placeholder="Contoh: Lab Komputer · 09.00 WIB"
                required
              />
            </div>

            <div class="mb-3">
              <label class="form-label custom-label">Status publikasi</label>
              <select v-model="form.status" class="form-select custom-input">
                <option value="Akan datang">Akan datang (Terbit)</option>
                <option value="Draf">Draf (Internal)</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label custom-label">Deskripsi singkat</label>
              <textarea
                v-model="form.desc"
                rows="3"
                class="form-control custom-input"
                placeholder="Ringkasan kegiatan untuk ditampilkan di halaman publik"
              ></textarea>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn-himatif flex-grow-1">Simpan kegiatan</button>
              <button type="button" class="btn-cancel" @click="resetForm">Batal</button>
            </div>
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

.status-pill {
  display: inline-block;
  padding: 3px 10px;
  border-radius: 999px;
  font-size: 11.5px;
  font-weight: 600;
}

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
}

.action-icon-btn.delete { color: #DC2626; }

.btn-cancel {
  border: 1px solid #E5E7EB;
  background-color: #FFFFFF;
  color: #374151;
  border-radius: 8px;
  padding: 8px 16px;
  font-size: 13.5px;
  font-weight: 600;
}
</style>