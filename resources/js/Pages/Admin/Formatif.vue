<script setup>
import { ref, computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const page = usePage();

// Deteksi Role (BPH vs Infokom)
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

// Judul & Subtitle Adaptif (Gambar 3 untuk BPH vs Gambar 11 untuk Infokom)
const pageTitle = computed(() => {
  return userRole.value === 'bph' ? 'Informasi - Formatif' : 'Informasi';
});

const pageSubtitle = computed(() => {
  return userRole.value === 'bph'
    ? 'Kelola Artikel, Materi Teknologi, Informasi Perlombaan, dan Berita Himpunan.'
    : 'Kelola Artikel, Materi, Informasi Perlombaan, dan Berita Himpunan.';
});

const slugLabel = computed(() => {
  return userRole.value === 'bph' ? 'Sub Judul' : 'Slug';
});

const slugPlaceholder = computed(() => {
  return userRole.value === 'bph' ? 'Subjudul-artikel' : 'judul-artikel';
});

// Tab Switcher
const activeTab = ref('Semua');

// Filter & Pencarian
const searchQuery = ref('');
const categoryFilter = ref('Semua kategori');

// Data Artikel (Sesuai Gambar 3 & 11)
const articles = ref([
  {
    id: 1,
    title: 'Dasar Git untuk Pemula',
    slug: 'dasar-git-untuk-pemula',
    category: 'Materi IT',
    status: 'Terbit',
    updatedAt: '13 Okt 2026',
    content: 'Panduan praktis penggunaan Git command line untuk mahasiswa baru...',
  },
  {
    id: 2,
    title: 'Pendaftaran Lomba Desain Web',
    slug: 'pendaftaran-lomba-desain-web',
    category: 'Perlombaan',
    status: 'Terbit',
    updatedAt: '11 Okt 2026',
    content: 'Pendaftaran dibuka untuk seluruh mahasiswa Teknik Informatika...',
  },
  {
    id: 3,
    title: 'Pengenalan Laravel dan MVC',
    slug: 'pengenalan-laravel-mvc',
    category: 'Materi IT',
    status: 'Draf',
    updatedAt: '09 Okt 2026',
    content: 'Mengenal konsep Model-View-Controller dalam pengembangan aplikasi web modern...',
  },
  {
    id: 4,
    title: 'Struktur Kepengurusan HIMATIF',
    slug: 'struktur-kepengurusan',
    category: 'Organisasi',
    status: 'Terbit',
    updatedAt: '02 Okt 2026',
    content: 'Profil pengurus HIMATIF periode 2025/2026...',
  },
  {
    id: 5,
    title: 'Tips Memulai Karier di Bidang IT',
    slug: 'tips-karier-it',
    category: 'Materi IT',
    status: 'Terbit',
    updatedAt: '28 Sep 2026',
    content: 'Langkah awal membangun karier di dunia software engineering dan data...',
  },
]);

// Counts
const publishedCount = computed(() => articles.value.filter(a => a.status === 'Terbit').length);
const draftCount = computed(() => articles.value.filter(a => a.status === 'Draf').length);

// Class Kategori
const getCategoryClass = (category) => {
  switch (category) {
    case 'Materi IT': return 'cat-blue';
    case 'Perlombaan': return 'cat-amber';
    case 'Organisasi': return 'cat-green';
    default: return 'cat-blue';
  }
};

// Filtered Articles
const filteredArticles = computed(() => {
  return articles.value.filter(item => {
    if (activeTab.value === 'Terbit' && item.status !== 'Terbit') return false;
    if (activeTab.value === 'Draf' && item.status !== 'Draf') return false;

    const matchSearch =
      item.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      item.slug.toLowerCase().includes(searchQuery.value.toLowerCase());

    const matchCategory =
      categoryFilter.value === 'Semua kategori' || item.category === categoryFilter.value;

    return matchSearch && matchCategory;
  });
});

// State Form Editor
const isEditing = ref(false);
const form = ref({
  id: null,
  title: '',
  slug: '',
  category: 'Materi IT',
  status: 'Draf',
  thumbnailName: '',
  content: '',
});

// Reset Form
const resetForm = () => {
  isEditing.value = false;
  form.value = {
    id: null,
    title: '',
    slug: '',
    category: 'Materi IT',
    status: 'Draf',
    thumbnailName: '',
    content: '',
  };
};

// Update Slug Otomatis
const updateSlug = () => {
  if (!isEditing.value) {
    form.value.slug = form.value.title
      .toLowerCase()
      .trim()
      .replace(/[^\w\s-]/g, '')
      .replace(/[\s_-]+/g, '-')
      .replace(/^-+|-+$/g, '');
  }
};

// Handle File Change
const handleThumbChange = (e) => {
  const file = e.target.files?.[0];
  if (file) {
    form.value.thumbnailName = file.name;
  }
};

// Edit Article
const editArticle = (item) => {
  isEditing.value = true;
  form.value = {
    id: item.id,
    title: item.title,
    slug: item.slug,
    category: item.category,
    status: item.status,
    thumbnailName: '',
    content: item.content || '',
  };
};

// Delete Article
const deleteArticle = (id) => {
  if (confirm('Yakin ingin menghapus artikel ini?')) {
    articles.value = articles.value.filter(a => a.id !== id);
    if (form.value.id === id) resetForm();
  }
};

// Save Article
const saveArticle = (targetStatus = 'Terbit') => {
  if (!form.value.title) return;

  const now = new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }).format(new Date());

  if (isEditing.value && form.value.id) {
    const idx = articles.value.findIndex(a => a.id === form.value.id);
    if (idx !== -1) {
      articles.value[idx] = {
        ...articles.value[idx],
        title: form.value.title,
        slug: form.value.slug || form.value.title.toLowerCase().replace(/\s+/g, '-'),
        category: form.value.category,
        status: targetStatus,
        updatedAt: now,
        content: form.value.content,
      };
    }
  } else {
    articles.value.unshift({
      id: Date.now(),
      title: form.value.title,
      slug: form.value.slug || form.value.title.toLowerCase().replace(/\s+/g, '-'),
      category: form.value.category,
      status: targetStatus,
      updatedAt: now,
      content: form.value.content,
    });
  }

  resetForm();
};

// Insert Formatting
const insertFormat = (formatType) => {
  const prefixMap = {
    'B': '**teks tebal**',
    'I': '*teks miring*',
    'H2': '\n## Judul Bagian\n',
    'LIST': '\n- Poin daftar\n',
    'LINK': '[tautan](https://)',
  };
  form.value.content += (prefixMap[formatType] || '');
};
</script>

<template>
  <Head :title="`${pageTitle} - Panel Admin HIMATIF`" />

  <AdminLayout
    :title="pageTitle"
    :subtitle="pageSubtitle"
  >
    <!-- Tombol Kanan Atas Header -->
    <template #actions>
      <button class="btn-cyan" @click="resetForm">
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

            <!-- Input Slug / Sub Judul (Adaptif BPH vs Infokom) -->
            <div class="mb-3">
              <label class="form-label custom-label">{{ slugLabel }}</label>
              <input
                v-model="form.slug"
                type="text"
                class="form-control custom-input font-monospace"
                style="font-size: 12.5px;"
                :placeholder="slugPlaceholder"
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
                  <strong style="color: #00A3FF;">Pilih gambar</strong>
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
                class="btn-cyan flex-grow-1"
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
.btn-cyan {
  background-color: #00A3FF;
  color: #ffffff;
  border: none;
  border-radius: 8px;
  padding: 8px 16px;
  font-size: 13.5px;
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
  font-size: 13.5px;
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

.status-pill {
  display: inline-block;
  padding: 3px 10px;
  border-radius: 999px;
  font-size: 11.5px;
  font-weight: 600;
}

.pill-success { background-color: #D1FAE5; color: #0F765E; }
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

.thumb-upload-box {
  border: 1px dashed #D1D5DB;
  border-radius: 8px;
  padding: 10px 14px;
  cursor: pointer;
  background-color: #FAFAFA;
  transition: all 0.15s ease;
}

.thumb-upload-box:hover {
  border-color: #00A3FF;
  background-color: #F0F9FF;
}

.editor-box {
  border: 1px solid #E5E7EB;
  border-radius: 8px;
  overflow: hidden;
}

.editor-toolbar {
  background-color: #F9FAFB;
  border-bottom: 1px solid #E5E7EB;
  padding: 6px 8px;
}

.tool-btn {
  width: 26px;
  height: 26px;
  border-radius: 4px;
  border: 1px solid transparent;
  background: transparent;
  color: #374151;
  font-size: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.tool-btn:hover {
  background-color: #E5E7EB;
}

.editor-textarea {
  border: none;
  border-radius: 0;
  resize: vertical;
  font-size: 13px;
  padding: 10px 12px;
}

.editor-textarea:focus {
  box-shadow: none;
}
</style>
