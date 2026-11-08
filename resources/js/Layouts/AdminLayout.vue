<script setup>
import Sidebar from '@/Components/Admin/Sidebar.vue';
<<<<<<< HEAD

defineProps({
  title: {
    type: String,
    default: 'Dashboard'
  },
  subtitle: {
    type: String,
    default: ''
  }
=======
import { Head } from '@inertiajs/vue3';

defineProps({
    title: {
        type: String,
        default: '',
    },
>>>>>>> 608554c4545df0bcc674d83bb0c07fe78b0f3759
});
</script>

<template>
<<<<<<< HEAD
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
=======
    <div>
        <Head :title="title" />

        <div class="min-h-screen bg-gray-100">
            <!-- Sidebar -->
            <Sidebar />

            <!-- Main Content -->
            <div class="pl-64">
                <!-- Top Header -->
                <header class="sticky top-0 z-20 border-b border-gray-200 bg-white">
                    <div class="flex h-16 items-center justify-between px-6">
                        <div>
                            <slot name="header" />
                        </div>
                        <div class="flex items-center gap-3">
                            <slot name="actions" />
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="p-6">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>

>>>>>>> 608554c4545df0bcc674d83bb0c07fe78b0f3759
