<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

// Tab aktif: 'Semua' | 'Terbit' | 'Draf'
const activeTab = ref('Semua');
const searchQuery = ref('');
const categoryFilter = ref('Semua kategori');

// Data Artikel sesuai gambar referensi 4 · Manajemen Formatif
const articles = ref([
  {
    id: 1,
    title: 'Dasar Git untuk Pemula',
    slug: 'dasar-git-untuk-pemula',
    category: 'Materi IT',
    status: 'Terbit',
    updatedAt: '13 Okt 2026',
    content: 'Panduan lengkap menggunakan Git mulai dari git init, commit, hingga push ke repository GitHub.'
  },
  {
    id: 2,
    title: 'Pendaftaran Lomba Desain Web',
    slug: 'pendaftaran-lomba-desain-web',
    category: 'Perlombaan',
    status: 'Terbit',
    updatedAt: '11 Okt 2026',
    content: 'Informasi pendaftaran kompetisi UI/UX dan Web Design tingkat nasional.'
  },
  {
    id: 3,
    title: 'Pengenalan Laravel dan MVC',
    slug: 'pengenalan-laravel-mvc',
    category: 'Materi IT',
    status: 'Draf',
    updatedAt: '09 Okt 2026',
    content: 'Memahami konsep Model-View-Controller pada framework PHP Laravel.'
  },
  {
    id: 4,
    title: 'Struktur Kepengurusan HIMATIF',
    slug: 'struktur-kepengurusan',
    category: 'Organisasi',
    status: 'Terbit',
    updatedAt: '02 Okt 2026',
    content: 'Susunan pengurus BPH, Infokom, Danus, dan Manset HIMATIF periode berjalan.'
  },
  {
    id: 5,
    title: 'Tips Memulai Karier di Bidang IT',
    slug: 'tips-karier-it',
    category: 'Materi IT',
    status: 'Terbit',
    updatedAt: '28 Sep 2026',
    content: 'Langkah-langkah menyiapkan portofolio dan CV untuk mahasiswa Teknik Informatika.'
  }
]);

// Hitung jumlah untuk badge tab
const publishedCount = computed(() => articles.value.filter(a => a.status === 'Terbit').length);
const draftCount = computed(() => articles.value.filter(a => a.status === 'Draf').length);

// Warna badge kategori sesuai referensi
const getCategoryClass = (cat) => {
  if (cat === 'Materi IT') return 'cat-blue';
  if (cat === 'Perlombaan') return 'cat-amber';
  if (cat === 'Organisasi') return 'cat-green';
  return 'cat-gray';
};

// Filter artikel berdasarkan tab, pencarian, dan kategori
const filteredArticles = computed(() => {
  return articles.value.filter((item) => {
    const matchTab = activeTab.value === 'Semua' || item.status === activeTab.value;
    const matchSearch = item.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                        item.slug.toLowerCase().includes(searchQuery.value.toLowerCase());
    const matchCat = categoryFilter.value === 'Semua kategori' || item.category === categoryFilter.value;
    return matchTab && matchSearch && matchCat;
  });
});

// State Form Editor di sebelah kanan
const isEditing = ref(false);
const form = ref({
  id: null,
  title: '',
  slug: '',
  category: 'Materi IT',
  status: 'Draf',
  thumbnailName: '',
  content: ''
});

// Auto-generate slug saat judul diketik
const updateSlug = () => {
  form.value.slug = form.value.title
    .toLowerCase()
    .trim()
    .replace(/[^a-z0-9\s-]/g, '')
    .replace(/\s+/g, '-');
};

const resetForm = () => {
  isEditing.value = false;
  form.value = {
    id: null,
    title: '',
    slug: '',
    category: 'Materi IT',
    status: 'Draf',
    thumbnailName: '',
    content: ''
  };
};

const editArticle = (item) => {
  isEditing.value = true;
  form.value = {
    id: item.id,
    title: item.title,
    slug: item.slug,
    category: item.category,
    status: item.status,
    thumbnailName: '',
    content: item.content || ''
  };
};

const saveArticle = (targetStatus) => {
  if (!form.value.title.trim()) return;

  const finalStatus = targetStatus || form.value.status;
  const finalSlug = form.value.slug.trim() || form.value.title.toLowerCase().replace(/\s+/g, '-');

  if (isEditing.value && form.value.id !== null) {
    const idx = articles.value.findIndex(a => a.id === form.value.id);
    if (idx !== -1) {
      articles.value[idx] = {
        ...articles.value[idx],
        title: form.value.title,
        slug: finalSlug,
        category: form.value.category,
        status: finalStatus,
        updatedAt: 'Baru saja',
        content: form.value.content
      };
    }
  } else {
    articles.value.unshift({
      id: Date.now(),
      title: form.value.title,
      slug: finalSlug,
      category: form.value.category,
      status: finalStatus,
      updatedAt: 'Baru saja',
      content: form.value.content
    });
  }

  resetForm();
};

const deleteArticle = (id) => {
  articles.value = articles.value.filter(a => a.id !== id);
  if (form.value.id === id) resetForm();
};

const handleThumbChange = (e) => {
  const file = e.target.files?.[0];
  if (file) form.value.thumbnailName = file.name;
};

const insertFormat = (tag) => {
  if (tag === 'B') form.value.content += ' **teks tebal** ';
  if (tag === 'I') form.value.content += ' *teks miring* ';
  if (tag === 'H2') form.value.content += '\n## Subjudul Artikel\n';
  if (tag === 'LIST') form.value.content += '\n• Item poin ';
  if (tag === 'LINK') form.value.content += ' [Teks Link](https://) ';
};
</script>

<template>
  <Head title="Formatif - Panel Admin" />

  <AdminLayout
    title="Formatif"
    subtitle="Kelola artikel, materi teknologi, informasi perlombaan, dan berita himpunan."
  >
    <!-- Tombol Kanan Atas Header -->
    <template #actions>
      <button class="btn-himatif" @click="resetForm">
        + Tulis artikel
      </button>
    </template>

    <!-- Segmented Tabs (Semua · Terbit · Draf) -->
    <div class="tab-switcher mb-3">
      <button
        type="button"
        class="tab-btn"
        :class="{ active: activeTab === 'Semua' }"
        @click="activeTab = 'Semua'"
      >
        Semua · {{ articles.length + 15 }}
      </button>
      <button
        type="button"
        class="tab-btn"
        :class="{ active: activeTab === 'Terbit' }"
        @click="activeTab = 'Terbit'"
      >
        Terbit · {{ publishedCount + 14 }}
      </button>
      <button
        type="button"
        class="tab-btn"
        :class="{ active: activeTab === 'Draf' }"
        @click="activeTab = 'Draf'"
      >
        Draf · {{ draftCount + 1 }}
      </button>
    </div>

    <!-- Split View: Kiri Tabel Artikel (8 Kolom), Kanan Editor Artikel (4 Kolom) -->
    <div class="row g-3 align-items-start">
      <!-- Kolom Kiri: Daftar Artikel -->
      <div class="col-12 col-xl-8">
        <div class="card-panel">
          <!-- Filter Bar -->
          <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
            <input
              v-model="searchQuery"
              type="text"
              class="form-control custom-input"
              style="max-width: 280px;"
              placeholder="Cari judul artikel"
            />
            <select
              v-model="categoryFilter"
              class="form-select custom-input"
              style="max-width: 190px;"
            >
              <option value="Semua kategori">Semua kategori</option>
              <option value="Materi IT">Materi IT</option>
              <option value="Perlombaan">Perlombaan</option>
              <option value="Organisasi">Organisasi</option>
            </select>
          </div>

          <!-- Tabel Artikel -->
          <div class="table-responsive">
            <table class="table align-middle mb-0 custom-table">
              <thead>
                <tr>
                  <th>JUDUL</th>
                  <th>KATEGORI</th>
                  <th>STATUS</th>
                  <th>DIPERBARUI</th>
                  <th class="text-end">AKSI</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in filteredArticles" :key="item.id">
                  <td style="max-width: 250px;">
                    <div class="fw-semibold text-dark lh-sm mb-1" style="font-size: 13.5px;">
                      {{ item.title }}
                    </div>
                    <div class="font-monospace text-secondary" style="font-size: 11.5px;">
                      /{{ item.slug }}
                    </div>
                  </td>
                  <td>
                    <span class="cat-pill" :class="getCategoryClass(item.category)">
                      {{ item.category }}
                    </span>
                  </td>
                  <td>
                    <span
                      class="status-pill"
                      :class="item.status === 'Terbit' ? 'pill-success' : 'pill-neutral'"
                    >
                      {{ item.status }}
                    </span>
                  </td>
                  <td class="text-secondary" style="font-size: 13px;">
                    {{ item.updatedAt }}
                  </td>
                  <td class="text-end">
                    <div class="d-inline-flex gap-1">
                      <button
                        type="button"
                        class="action-icon-btn"
                        title="Edit artikel"
                        @click="editArticle(item)"
                      >
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <path d="M12 20h9"></path>
                          <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                        </svg>
                      </button>
                      <button
                        type="button"
                        class="action-icon-btn delete"
                        title="Hapus artikel"
                        @click="deleteArticle(item.id)"
                      >
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <polyline points="3 6 5 6 21 6"></polyline>
                          <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                      </button>
                    </div>
                  </td>
                </tr>
                <tr v-if="filteredArticles.length === 0">
                  <td colspan="5" class="text-center text-secondary py-4">
                    Tidak ada artikel yang cocok.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Kolom Kanan: Editor Artikel -->
      <div class="col-12 col-xl-4">
        <div class="card-panel">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="fw-bold mb-0" style="font-size: 15px;">
              Editor artikel
            </h6>
            <span
              class="status-pill"
              :class="form.status === 'Terbit' ? 'pill-success' : 'pill-neutral'"
            >
              {{ form.status }}
            </span>
          </div>

          <form @submit.prevent="saveArticle('Terbit')">
            <!-- Input Judul -->
            <div class="mb-3">
              <label class="form-label custom-label">Judul</label>
              <input
                v-model="form.title"
                type="text"
                class="form-control custom-input"
                placeholder="Judul artikel"
                required
                @input="updateSlug"
              />
            </div>

            <!-- Input Slug -->
            <div class="mb-3">
              <label class="form-label custom-label">Slug</label>
              <input
                v-model="form.slug"
                type="text"
                class="form-control custom-input font-monospace"
                style="font-size: 12.5px;"
                placeholder="judul-artikel"
              />
            </div>

            <!-- Kategori & Status -->
            <div class="row g-2 mb-3">
              <div class="col-7">
                <label class="form-label custom-label">Kategori</label>
                <select v-model="form.category" class="form-select custom-input">
                  <option value="Materi IT">Materi IT</option>
                  <option value="Perlombaan">Perlombaan</option>
                  <option value="Organisasi">Organisasi</option>
                </select>
              </div>
              <div class="col-5">
                <label class="form-label custom-label">Status</label>
                <select v-model="form.status" class="form-select custom-input">
                  <option value="Draf">Draf</option>
                  <option value="Terbit">Terbit</option>
                </select>
              </div>
            </div>

            <!-- Thumbnail Upload -->
            <div class="mb-3">
              <label class="form-label custom-label">Thumbnail</label>
              <label class="thumb-upload-box d-flex align-items-center gap-2">
                <input type="file" class="d-none" accept=".jpg,.jpeg,.png" @change="handleThumbChange" />
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6B7280" stroke-width="2">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                  <polyline points="17 8 12 3 7 8"></polyline>
                  <line x1="12" y1="3" x2="12" y2="15"></line>
                </svg>
                <span style="font-size: 12.5px;">
                  <strong style="color: #0F765E;">Pilih gambar</strong>
                  <span class="text-secondary"> · {{ form.thumbnailName || 'JPG atau PNG' }}</span>
                </span>
              </label>
            </div>

            <!-- Rich Text Konten -->
            <div class="mb-3">
              <label class="form-label custom-label">Konten</label>
              <div class="editor-box">
                <div class="editor-toolbar d-flex align-items-center gap-1">
                  <button type="button" class="tool-btn fw-bold" @click="insertFormat('B')">B</button>
                  <button type="button" class="tool-btn fst-italic" @click="insertFormat('I')">I</button>
                  <button type="button" class="tool-btn fw-semibold" @click="insertFormat('H2')">H2</button>
                  <button type="button" class="tool-btn" @click="insertFormat('LIST')">•</button>
                  <button type="button" class="tool-btn" @click="insertFormat('LINK')">↗</button>
                </div>
                <textarea
                  v-model="form.content"
                  rows="6"
                  class="form-control editor-textarea"
                  placeholder="Tulis isi artikel di sini"
                ></textarea>
              </div>
            </div>

            <!-- Tombol Simpan Draf & Terbitkan -->
            <div class="d-flex align-items-center gap-2">
              <button
                type="button"
                class="btn-draft flex-grow-1"
                @click="saveArticle('Draf')"
              >
                Simpan draf
              </button>
              <button
                type="submit"
                class="btn-himatif flex-grow-1"
              >
                Terbitkan
              </button>
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
  padding: 14px 8px;
  border-bottom: 1px solid #F3F4F6;
}

.custom-table tbody tr:last-child td {
  border-bottom: none;
}

.cat-pill,
.status-pill {
  display: inline-block;
  padding: 3px 10px;
  border-radius: 999px;
  font-size: 11.5px;
  font-weight: 600;
}

.cat-blue {
  background-color: #E0E7FF;
  color: #1D4ED8;
}

.cat-amber {
  background-color: #FEF3C7;
  color: #B45309;
}

.cat-green {
  background-color: #D1FAE5;
  color: #0F765E;
}

.cat-gray {
  background-color: #F3F4F6;
  color: #4B5563;
}

.pill-success {
  background-color: #D1FAE5;
  color: #0F765E;
}

.pill-neutral {
  background-color: #F3F4F6;
  color: #4B5563;
}

.action-icon-btn {
  width: 32px;
  height: 32px;
  border-radius: 8px;
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

.thumb-upload-box {
  border: 1.5px dashed #D1D5DB;
  border-radius: 8px;
  padding: 11px 14px;
  cursor: pointer;
  background-color: #FAFAFA;
  transition: all 0.15s ease;
}

.thumb-upload-box:hover {
  border-color: #0F765E;
  background-color: #F4FBF9;
}

.editor-box {
  border: 1px solid #E5E7EB;
  border-radius: 8px;
  overflow: hidden;
}

.editor-toolbar {
  background-color: #F9FAFB;
  border-bottom: 1px solid #E5E7EB;
  padding: 6px 10px;
}

.tool-btn {
  border: none;
  background: transparent;
  color: #374151;
  font-size: 13px;
  padding: 4px 8px;
  border-radius: 5px;
}

.tool-btn:hover {
  background-color: #E5E7EB;
}

.editor-textarea {
  border: none;
  border-radius: 0;
  font-size: 13px;
  padding: 10px 12px;
  resize: vertical;
}

.editor-textarea:focus {
  box-shadow: none;
}

.btn-draft {
  border: 1px solid #E5E7EB;
  background-color: #FFFFFF;
  color: #111827;
  border-radius: 8px;
  padding: 8px 16px;
  font-size: 13.5px;
  font-weight: 600;
}

.btn-draft:hover {
  background-color: #F9FAFB;
}
</style>