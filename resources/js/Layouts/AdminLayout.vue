<script setup>
import Sidebar from '@/Components/Admin/Sidebar.vue';

defineProps({
  title: {
    type: String,
    default: 'Dashboard'
  },
  subtitle: {
    type: String,
    default: ''
  }
});
</script>

<template>
  <div class="admin-wrapper">
    <!-- Sidebar Kiri -->
    <Sidebar />

    <!-- Area Konten Utama Kanan -->
    <main class="admin-main">
      <!-- Topbar Header Dinamis -->
      <header class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
          <h1 class="fw-bold mb-1" style="font-size: 24px; color: #111827;">
            {{ title }}
          </h1>
          <p v-if="subtitle" class="mb-0" style="font-size: 13.5px; color: #6B7280;">
            {{ subtitle }}
          </p>
        </div>

        <!-- Slot untuk tombol kanan atas (seperti + Tambah kegiatan / + Tambah produk) -->
        <div class="d-flex align-items-center gap-2">
          <slot name="actions" />
        </div>
      </header>

      <!-- Isi Konten Halaman -->
      <slot />
    </main>
  </div>
</template>
