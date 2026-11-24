<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();

const user = computed(() => page.props.auth?.user || {
  nama: 'Admin BPH',
  role: 'bph',
  role_label: 'Akses penuh'
});

const userRole = computed(() => {
  if (typeof window !== 'undefined') {
    const params = new URLSearchParams(window.location.search);
    const r = params.get('role');
    if (r && ['bph', 'manset', 'danus', 'infokom'].includes(r.toLowerCase())) {
      return r.toLowerCase();
    }
  }
  return user.value?.role || 'bph';
});

const roleInitials = {
  bph: 'AB',
  manset: 'MS',
  danus: 'DN',
  infokom: 'IK',
};

const userInitials = computed(() => {
  return roleInitials[userRole.value] || 'AB';
});

const roleLabels = {
  bph: 'Akses penuh',
  manset: 'Inventaris',
  danus: 'Shop',
  infokom: 'Formatif & Kegiatan',
};

const roleNames = {
  bph: 'Admin BPH',
  manset: 'Admin Manset',
  danus: 'Admin Danus',
  infokom: 'Admin Infokom',
};

const roleName = computed(() => {
  return roleNames[userRole.value] || user.value?.nama || 'Admin';
});

const roleLabel = computed(() => roleLabels[userRole.value] || 'Pengurus');

const kelolaMenus = [
  {
    name: 'Shop',
    href: '/admin/shop',
    roles: ['bph', 'danus'],
    iconPaths: [
      'M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z',
      'M3 6h18',
      'M16 10a4 4 0 0 1-8 0'
    ]
  },
  {
    name: 'Informasi',
    href: '/admin/formatif',
    roles: ['bph', 'infokom'],
    iconPaths: [
      'M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z',
      'M14 2v6h6',
      'M16 13H8',
      'M16 17H8',
      'M10 9H8'
    ]
  },
  {
    name: 'Inventaris',
    href: '/admin/inventaris',
    roles: ['bph', 'manset'],
    iconPaths: [
      'M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z',
      'M3.27 6.96L12 12.01l8.73-5.05',
      'M12 22.08V12'
    ]
  },
  {
    name: 'Kegiatan',
    href: '/admin/kegiatan',
    roles: ['bph', 'infokom'],
    iconPaths: [
      'M19 4H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z',
      'M16 2v4',
      'M8 2v4',
      'M3 10h18'
    ]
  }
];

const allowedKelola = computed(() =>
  kelolaMenus.filter(item => item.roles.includes(userRole.value))
);

const isActive = (href) => page.url.startsWith(href);
</script>

<template>
  <aside class="admin-sidebar">
    <div>
      <!-- Brand HIMATIF with Logo -->
      <div class="d-flex align-items-center gap-3 px-2 mb-4">
        <img
          src="/images/logo-himatif.png"
          alt="HIMATIF"
          class="brand-logo"
        />
        <div>
          <div class="fw-bold text-white lh-sm" style="font-size: 15px; letter-spacing: 0.3px;">
            HIMATIF
          </div>
          <div style="font-size: 12px; color: #8A99AD;">
            Panel Admin
          </div>
        </div>
      </div>

      <!-- Menu Dashboard -->
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

      <!-- Grup KELOLA -->
      <div v-if="allowedKelola.length > 0" class="mb-3">
        <div class="sidebar-section-label">KELOLA</div>
        <div class="d-flex flex-column gap-1">
          <Link
            v-for="item in allowedKelola"
            :key="item.href"
            :href="item.href"
            class="sidebar-link"
            :class="{ active: isActive(item.href) }"
          >
            <div class="d-flex align-items-center gap-2">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path v-for="(d, i) in item.iconPaths" :key="i" :d="d"></path>
              </svg>
              <span>{{ item.name }}</span>
            </div>
          </Link>
        </div>
      </div>
    </div>

    <!-- Footer Profil User -->
    <div class="sidebar-footer d-flex align-items-center justify-content-between pt-3 px-2">
      <div class="d-flex align-items-center gap-2">
        <div class="avatar-circle d-flex align-items-center justify-content-center fw-bold text-white">
          {{ userInitials }}
        </div>
        <div>
          <div class="fw-semibold text-white lh-sm" style="font-size: 13px;">
            {{ roleName }}
          </div>
          <div style="font-size: 11px; color: #8A99AD;">
            {{ roleLabel }}
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
  </aside>
</template>

<style scoped>
.brand-logo {
  width: 36px;
  height: 36px;
  border-radius: 9px;
  object-fit: contain;
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
