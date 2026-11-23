<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

// Tab aktif: 'Semua', 'Akan datang', 'Draf'
const activeTab = ref('Semua');

// Filter & Pencarian
const searchQuery = ref('');
const statusFilter = ref('Semua status');

// Data Kegiatan (Sesuai Gambar 5 & 12)
const events = ref([
  {
    id: 1,
    day: '18',
    month: 'OKT',
    title: 'Pelatihan Dasar Laravel',
    loc: 'Lab Komputer · 09.00 WIB',
    dateText: '18 Okt 2026',
    timeText: '09.00 WIB',
    locationDetail: 'Lab Komputer Gedung B',
    status: 'Akan datang',
    statusClass: 'pill-info',
    desc: 'Pelatihan pembuatan web framework Laravel untuk mahasiswa tingkat 1 & 2.',
    target: 'Publik',
  },
  {
    id: 2,
    day: '25',
    month: 'OKT',
    title: 'Lomba Desain Web',
    loc: 'Pendaftaran dibuka · Daring',
    dateText: '25 Okt 2026',
    timeText: '08.00 WIB',
    locationDetail: 'Daring / Online Zoom',
    status: 'Akan datang',
    statusClass: 'pill-info',
    desc: 'Kompetisi desain UI/UX dan frontend web se-wilayah 3 Cirebon.',
    target: 'Publik',
  },
  {
    id: 3,
    day: '02',
    month: 'NOV',
    title: 'Rapat Kerja Pengurus',
    loc: 'Internal BPH · Sekretariat',
    dateText: '02 Nov 2026',
    timeText: '13.30 WIB',
    locationDetail: 'Ruang Rapat Sekretariat HIMATIF',
    status: 'Draf',
    statusClass: 'pill-neutral',
    desc: 'Evaluasi program kerja tengah periode dan pembahasan anggaran departemen.',
    target: 'Internal',
  },
  {
    id: 4,
    day: '10',
    month: 'NOV',
    title: 'Seminar Karier IT & Portfolio',
    loc: 'Auditorium Kampus · 13.00 WIB',
    dateText: '10 Nov 2026',
    timeText: '13.00 WIB',
    locationDetail: 'Auditorium Gedung Terpadu Lt. 3',
    status: 'Akan datang',
    statusClass: 'pill-info',
    desc: 'Tips membangun portofolio engineering dan CV ATS-friendly untuk fresh graduate.',
    target: 'Publik',
  },
  {
    id: 5,
    day: '05',
    month: 'OKT',
    title: 'Upgrading Pengurus HIMATIF',
    loc: 'Aula Terpadu · Selesai',
    dateText: '05 Okt 2026',
    timeText: '08.00 WIB',
    locationDetail: 'Aula Terpadu Kampus',
    status: 'Selesai',
    statusClass: 'pill-success',
    desc: 'Penguatan kepemimpinan, komunikasi organisasi, dan manajemen waktu.',
    target: 'Internal',
  },
]);

// Counts
const totalCount = computed(() => events.value.length);
const upcomingCount = computed(() => events.value.filter((e) => e.status === 'Akan datang').length);
const draftCount = computed(() => events.value.filter((e) => e.status === 'Draf').length);

// Filtered Events
const filteredEvents = computed(() => {
  return events.value.filter((item) => {
    // Tab filter
    if (activeTab.value === 'Akan datang' && item.status !== 'Akan datang') {
      return false;
    }
    if (activeTab.value === 'Draf' && item.status !== 'Draf') {
      return false;
    }

    // Search query
    const matchSearch =
      item.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      item.loc.toLowerCase().includes(searchQuery.value.toLowerCase());

    // Status filter dropdown
    const matchStatus =
      statusFilter.value === 'Semua status' || item.status === statusFilter.value;

    return matchSearch && matchStatus;
  });
});

// Form State
const isEditing = ref(false);
const form = ref({
  id: null,
  title: '',
  dateText: '18 Okt 2026',
  timeText: '09.00 WIB',
  locationDetail: '',
  status: 'Akan datang',
  desc: '',
  target: 'Publik',
});

// Reset Form
const resetForm = () => {
  isEditing.value = false;
  form.value = {
    id: null,
    title: '',
    dateText: '18 Okt 2026',
    timeText: '09.00 WIB',
    locationDetail: '',
    status: 'Akan datang',
    desc: '',
    target: 'Publik',
  };
};

// Edit Event
const editEvent = (item) => {
  isEditing.value = true;
  form.value = {
    id: item.id,
    title: item.title,
    dateText: item.dateText,
    timeText: item.timeText,
    locationDetail: item.locationDetail || item.loc,
    status: item.status,
    desc: item.desc || '',
    target: item.target || 'Publik',
  };
};

// Delete Event
const deleteEvent = (id) => {
  if (confirm('Yakin ingin menghapus agenda kegiatan ini?')) {
    events.value = events.value.filter((e) => e.id !== id);
  }
};

// Save Event
const saveEvent = (statusOverride = null) => {
  if (!form.value.title) return;

  const finalStatus = statusOverride || form.value.status;
  const statusClass =
    finalStatus === 'Akan datang'
      ? 'pill-info'
      : finalStatus === 'Selesai'
        ? 'pill-success'
        : 'pill-neutral';

  const parts = form.value.dateText.trim().split(' ');
  const day = parts[0] || '18';
  const month = (parts[1] || 'OKT').toUpperCase();

  if (isEditing.value && form.value.id) {
    const idx = events.value.findIndex((e) => e.id === form.value.id);
    if (idx !== -1) {
      events.value[idx] = {
        ...events.value[idx],
        title: form.value.title,
        day,
        month,
        dateText: form.value.dateText,
        timeText: form.value.timeText,
        loc: `${form.value.locationDetail} · ${form.value.timeText}`,
        locationDetail: form.value.locationDetail,
        status: finalStatus,
        statusClass,
        desc: form.value.desc,
        target: form.value.target,
      };
    }
  } else {
    events.value.push({
      id: Date.now(),
      day,
      month,
      title: form.value.title,
      loc: `${form.value.locationDetail} · ${form.value.timeText}`,
      dateText: form.value.dateText,
      timeText: form.value.timeText,
      locationDetail: form.value.locationDetail,
      status: finalStatus,
      statusClass,
      desc: form.value.desc,
      target: form.value.target || 'Publik',
    });
  }

  resetForm();
};
</script>

<template>
  <Head title="Kegiatan - Panel Admin HIMATIF" />

  <AdminLayout
    title="Kegiatan"
    subtitle="Kelola Jadwal Program Kerja, Pelatihan, dan Agenda Internal HIMATIF."
  >
    <!-- Action Top Right -->
    <template #actions>
      <button type="button" class="btn-cyan" @click="resetForm">
        + Tambah kegiatan
      </button>
    </template>

    <!-- Segmented Tabs (Gambar 5 & 12) -->
    <div class="tab-switcher mb-3">
      <button
        type="button"
        class="tab-btn"
        :class="{ active: activeTab === 'Semua' }"
        @click="activeTab = 'Semua'"
      >
        Semua · {{ totalCount }}
      </button>
      <button
        type="button"
        class="tab-btn"
        :class="{ active: activeTab === 'Akan datang' }"
        @click="activeTab = 'Akan datang'"
      >
        Akan datang · {{ upcomingCount }}
      </button>
      <button
        type="button"
        class="tab-btn"
        :class="{ active: activeTab === 'Draf' }"
        @click="activeTab = 'Draf'"
      >
        Draf · {{ draftCount }}
      </button>
    </div>

    <!-- Layout Grid: Kiri Tabel (8 Kolom), Kanan Form (4 Kolom) -->
    <div class="row g-3 align-items-start">
      <!-- Kolom Kiri: Daftar Kegiatan -->
      <div class="col-12 col-xl-8">
        <div class="card-panel">
          <!-- Filter Bar -->
          <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
            <input
              v-model="searchQuery"
              type="text"
              class="form-control custom-input"
              style="max-width: 300px;"
              placeholder="Cari nama kegiatan"
            />
            <select v-model="statusFilter" class="form-select custom-input" style="max-width: 170px;">
              <option value="Semua status">Semua status</option>
              <option value="Akan datang">Akan datang</option>
              <option value="Draf">Draf</option>
              <option value="Selesai">Selesai</option>
            </select>
          </div>

          <!-- Tabel Kegiatan -->
          <div class="table-responsive">
            <table class="table align-middle mb-0 custom-table">
              <thead>
                <tr>
                  <th>KEGIATAN & LOKASI</th>
                  <th>TANGGAL</th>
                  <th>STATUS</th>
                  <th class="text-end">AKSI</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in filteredEvents" :key="item.id">
                  <td>
                    <div class="d-flex align-items-center gap-3">
                      <div class="date-badge text-center">
                        <div class="fw-bold lh-1" style="font-size: 14px;">{{ item.day }}</div>
                        <div style="font-size: 9px; font-weight: 600;">{{ item.month }}</div>
                      </div>
                      <div>
                        <div class="fw-semibold text-dark" style="font-size: 13.5px;">{{ item.title }}</div>
                        <div class="text-secondary" style="font-size: 12px;">{{ item.loc }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="text-secondary" style="font-size: 13px;">
                    {{ item.dateText }}
                  </td>
                  <td>
                    <span class="status-pill" :class="item.statusClass">
                      {{ item.status }}
                    </span>
                  </td>
                  <td class="text-end">
                    <div class="d-inline-flex gap-1">
                      <button
                        type="button"
                        class="action-icon-btn"
                        title="Edit kegiatan"
                        @click="editEvent(item)"
                      >
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <path d="M12 20h9"></path>
                          <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                        </svg>
                      </button>
                      <button
                        type="button"
                        class="action-icon-btn delete"
                        title="Hapus kegiatan"
                        @click="deleteEvent(item.id)"
                      >
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <polyline points="3 6 5 6 21 6"></polyline>
                          <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                      </button>
                    </div>
                  </td>
                </tr>
                <tr v-if="filteredEvents.length === 0">
                  <td colspan="4" class="text-center text-secondary py-4">
                    Tidak ada kegiatan yang cocok.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Kolom Kanan: Form Tambah / Edit Kegiatan -->
      <div class="col-12 col-xl-4">
        <div class="card-panel">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="fw-bold mb-0" style="font-size: 15px;">
              {{ isEditing ? 'Edit kegiatan' : 'Tambah / edit kegiatan' }}
            </h6>
            <span class="badge-target">
              {{ form.target }}
            </span>
          </div>

          <form @submit.prevent="saveEvent('Akan datang')">
            <!-- Nama Kegiatan -->
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

            <!-- Tanggal & Waktu -->
            <div class="row g-2 mb-3">
              <div class="col-7">
                <label class="form-label custom-label">Tanggal pelaksanaan</label>
                <input
                  v-model="form.dateText"
                  type="text"
                  class="form-control custom-input"
                  placeholder="18 Okt 2026"
                  required
                />
              </div>
              <div class="col-5">
                <label class="form-label custom-label">Waktu</label>
                <input
                  v-model="form.timeText"
                  type="text"
                  class="form-control custom-input"
                  placeholder="09.00 WIB"
                  required
                />
              </div>
            </div>

            <!-- Lokasi -->
            <div class="mb-3">
              <label class="form-label custom-label">Lokasi / Keterangan tempat</label>
              <input
                v-model="form.locationDetail"
                type="text"
                class="form-control custom-input"
                placeholder="Contoh: Lab Komputer Gedung B"
                required
              />
            </div>

            <!-- Status Publikasi -->
            <div class="mb-3">
              <label class="form-label custom-label">Status publikasi</label>
              <select v-model="form.status" class="form-select custom-input">
                <option value="Akan datang">Akan datang</option>
                <option value="Draf">Draf</option>
                <option value="Selesai">Selesai</option>
              </select>
            </div>

            <!-- Deskripsi Singkat & Link Formulir -->
            <div class="mb-3">
              <label class="form-label custom-label">Deskripsi singkat & tautan pendaftaran</label>
              <textarea
                v-model="form.desc"
                rows="4"
                class="form-control custom-input"
                placeholder="Tulis rincian agenda atau link formulir..."
              ></textarea>
            </div>

            <!-- Tombol Aksi -->
            <div class="d-flex align-items-center gap-2">
              <button
                type="button"
                class="btn-draft flex-grow-1"
                @click="saveEvent('Draf')"
              >
                Simpan draf
              </button>
              <button
                type="submit"
                class="btn-cyan flex-grow-1"
              >
                Simpan kegiatan
              </button>
            </div>
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

.btn-draft {
  border: 1px solid #E5E7EB;
  background-color: #FFFFFF;
  color: #374151;
  border-radius: 8px;
  padding: 8px 16px;
  font-size: 13px;
  font-weight: 600;
  transition: all 0.15s ease;
}

.btn-draft:hover {
  background-color: #F9FAFB;
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

.pill-info { background-color: #EFF6FF; color: #1D4ED8; }
.pill-neutral { background-color: #F3F4F6; color: #4B5563; }
.pill-success { background-color: #ECFDF5; color: #047857; }

.badge-target {
  background-color: #EFF6FF;
  color: #1D4ED8;
  font-size: 11.5px;
  font-weight: 600;
  padding: 2px 8px;
  border-radius: 999px;
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

.action-icon-btn.delete { color: #DC2626; }
.action-icon-btn.delete:hover {
  background-color: #FEF2F2;
  border-color: #FECACA;
}
</style>
