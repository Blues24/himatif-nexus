<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();

// Ambil role dari user login Laravel (jika sudah ada di database), 
// atau gunakan state sementara untuk ngetes tampilan per role:
const activeRole = ref(page.props.auth?.user?.role || 'bph');

const roleProfiles = {
  bph: { name: 'Admin BPH', initials: 'AB', desc: 'Akses penuh' },
  danus: { name: 'Admin Danus', initials: 'AD', desc: 'Divisi Danus' },
  infokom: { name: 'Admin Infokom', initials: 'AI', desc: 'Divisi Infokom' },
  manset: { name: 'Admin Manset', initials: 'AM', desc: 'Divisi Manset' },
};

const currentProfile = computed(() => roleProfiles[activeRole.value] || roleProfiles.bph);

// Daftar menu grup KELOLA
const kelolaMenus = [
  {
    name: 'Shop',
    href: '/admin/danus',
    badge: 'Danus',
    roles: ['bph', 'danus'],
    icon: 'M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z M3 6h18 M16 10a4 4 0 0 1-8 0'
  },
  {
    name: 'Formatif',
    href: '/admin/formatif',
    badge: 'Infokom',
    roles: ['bph', 'infokom'],
    icon: 'M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z M14 2v6h6 M16 13H8 M16 17H8 M10 9H8'
  },
  {
    name: 'Inventaris',
    href: '/admin/inventaris',
    badge: 'Manset',
    roles: ['bph', 'manset'],
    icon: 'M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z M3.27 6.96L12 12.01l8.73-5.05 M12 22.08V12'
  },
  {
    name: 'Kegiatan',
    href: '/admin/kegiatan',
    badge: 'Infokom',
    roles: ['bph', 'infokom'],
    icon: 'M19 4H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z M16 2v4 M8 2v4 M3 10h18'
  }
];

// Daftar menu grup SISTEM
const sistemMenus = [
  {
    name: 'Hak Akses',
    href: '/admin/hak-akses',
    badge: 'BPH',
    roles: ['bph'],
    icon: 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z M9 12l2 2 4-4'
  }
];

const filteredKelola = computed(() =>
  kelolaMenus.filter(item => item.roles.includes(activeRole.value))
);

const filteredSistem = computed(() =>
  sistemMenus.filter(item => item.roles.includes(activeRole.value))
);

// Cek apakah menu sedang aktif berdasarkan URL Inertia
const isActive = (href) => page.url.startsWith(href);
</script>

<template>
  <aside class="admin-sidebar">
    <!-- Bagian Atas: Brand & Navigasi -->
    <div>
      <!-- Brand HIMATIF -->
      <div class="d-flex align-items-center gap-3 px-2 mb-4">
        <div class="brand-box d-flex align-items-center justify-content-center fw-bold text-white">
          HT
        </div>
        <div>
          <div class="fw-bold text-white lh-sm" style="font-size: 15px; letter-spacing: 0.3px;">
            HIMATIF
          </div>
          <div style="font-size: 12px; color: #8A99AD;">
            Panel Admin
          </div>
        </div>
      </div>

      <!-- Menu Utama: Dashboard -->
      <div class="mb-3">
        <Link
          href="/admin/dashboard"
          class="sidebar-link"
          :class="{ active: isActive('/admin/dashboard') }"
        >
          <div class="d-flex align-items-center gap-2">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="7" height="7" rx="1"></rect>
              <rect x="14" y="3" width="7" height="7" rx="1"></rect>
              <rect x="14" y="14" width="7" height="7" rx="1"></rect>
              <rect x="3" y="14" width="7" height="7" rx="1"></rect>
            </svg>
            <span>Dashboard</span>
          </div>
        </Link>
      </div>

      <!-- Grup Menu: KELOLA -->
      <div v-if="filteredKelola.length > 0" class="mb-3">
        <div class="sidebar-section-label">KELOLA</div>
        <div class="d-flex flex-column gap-1">
          <Link
            v-for="item in filteredKelola"
            :key="item.href"
            :href="item.href"
            class="sidebar-link"
            :class="{ active: isActive(item.href) }"
          >
            <div class="d-flex align-items-center gap-2">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path :d="item.icon"></path>
              </svg>
              <span>{{ item.name }}</span>
            </div>
            <span class="role-pill">{{ item.badge }}</span>
          </Link>
        </div>
      </div>

      <!-- Grup Menu: SISTEM -->
      <div v-if="filteredSistem.length > 0" class="mb-3">
        <div class="sidebar-section-label">SISTEM</div>
        <div class="d-flex flex-column gap-1">
          <Link
            v-for="item in filteredSistem"
            :key="item.href"
            :href="item.href"
            class="sidebar-link"
            :class="{ active: isActive(item.href) }"
          >
            <div class="d-flex align-items-center gap-2">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path :d="item.icon"></path>
              </svg>
              <span>{{ item.name }}</span>
            </div>
            <span class="role-pill">{{ item.badge }}</span>
          </Link>
        </div>
      </div>
    </div>

    <!-- Bagian Bawah: Switcher Tes Role & Profil User -->
    <div>
      <!-- Alat bantu tes role selama development (bisa dihapus jika auth backend sudah jadi) -->
      <div class="px-2 mb-3">
        <label class="d-block mb-1" style="font-size: 10px; color: #6C7D93; text-transform: uppercase; letter-spacing: 0.6px;">
          Simulasi Role:
        </label>
        <select v-model="activeRole" class="form-select form-select-sm bg-dark text-white border-secondary" style="font-size: 12px;">
          <option value="bph">Role: BPH (Super Admin)</option>
          <option value="danus">Role: Danus (Shop)</option>
          <option value="infokom">Role: Infokom (Formatif & Kegiatan)</option>
          <option value="manset">Role: Manset (Inventaris)</option>
        </select>
      </div>

      <!-- Footer Profil User -->
      <div class="sidebar-footer d-flex align-items-center justify-content-between pt-3 px-2">
        <div class="d-flex align-items-center gap-2">
          <div class="avatar-circle d-flex align-items-center justify-content-center fw-bold text-white">
            {{ currentProfile.initials }}
          </div>
          <div>
            <div class="fw-semibold text-white lh-sm" style="font-size: 13px;">
              {{ currentProfile.name }}
            </div>
            <div style="font-size: 11px; color: #8A99AD;">
              {{ currentProfile.desc }}
            </div>
          </div>
        </div>

        <Link
          href="/logout"
          method="post"
          as="button"
          class="logout-btn d-flex align-items-center justify-content-center"
          title="Keluar"
        >
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
            <polyline points="16 17 21 12 16 7"></polyline>
            <line x1="21" y1="12" x2="9" y2="12"></line>
          </svg>
        </Link>
      </div>
    </div>
  </aside>
</template>

<style scoped>
.brand-box {
  width: 36px;
  height: 36px;
  background-color: #0F765E;
  border-radius: 9px;
  font-size: 13px;
}

.sidebar-section-label {
  font-size: 11px;
  font-weight: 600;
  color: #6C7D93;
  letter-spacing: 0.8px;
  padding: 8px 12px 6px;
}

.sidebar-link {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px 12px;
  border-radius: 8px;
  color: #B0BCCD;
  text-decoration: none;
  font-size: 13.5px;
  font-weight: 500;
  transition: all 0.15s ease;
}

.sidebar-link:hover {
  background-color: rgba(255, 255, 255, 0.06);
  color: #FFFFFF;
}

.sidebar-link.active {
  background-color: #0F765E;
  color: #FFFFFF;
  font-weight: 600;
}

.role-pill {
  font-size: 11px;
  padding: 2px 8px;
  border-radius: 6px;
  background-color: rgba(255, 255, 255, 0.08);
  color: #9FB0C6;
  font-weight: 500;
}

.sidebar-link.active .role-pill {
  background-color: rgba(0, 0, 0, 0.22);
  color: #E2F5EE;
}

.sidebar-footer {
  border-top: 1px solid rgba(255, 255, 255, 0.08);
}

.avatar-circle {
  width: 34px;
  height: 34px;
  border-radius: 50%;
  background-color: #243442;
  font-size: 12px;
}

.logout-btn {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  border: 1px solid rgba(255, 255, 255, 0.12);
  background: transparent;
  color: #9FB0C6;
  transition: all 0.15s ease;
}

.logout-btn:hover {
  background-color: rgba(255, 255, 255, 0.08);
  color: #FFFFFF;
}
</style>