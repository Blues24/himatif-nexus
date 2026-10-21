<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

interface Formatif {
  formatif_id: number
  judul: string
  slug: string
  kategori: string
  status: 'draft' | 'published'
  created_at: string
}

defineProps<{
  formatifs?: Formatif[]
}>()
</script>

<template>
  <AuthenticatedLayout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6 flex justify-between items-center">
          <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
            Blog — Formatif
          </h1>
          <Link
            :href="route('formatif.create')"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
          >
            + Artikel Baru
          </Link>
        </div>

        <div class="space-y-4">
          <div
            v-for="article in formatifs"
            :key="article.formatif_id"
            class="bg-white dark:bg-gray-800 rounded-lg shadow p-6"
          >
            <div class="flex justify-between items-start">
              <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                  {{ article.judul }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                  {{ article.kategori }} • {{ new Date(article.created_at).toLocaleDateString('id-ID') }}
                </p>
              </div>
              <span
                class="px-2 py-1 rounded text-sm font-medium"
                :class="article.status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'"
              >
                {{ article.status }}
              </span>
            </div>
            <div class="mt-4 flex gap-2">
              <Link
                :href="route('formatif.show', article.formatif_id)"
                class="text-blue-600 hover:underline text-sm"
              >
                Lihat
              </Link>
              <Link
                :href="route('formatif.edit', article.formatif_id)"
                class="text-yellow-600 hover:underline text-sm"
              >
                Edit
              </Link>
            </div>
          </div>
        </div>

        <div v-if="!formatifs?.length" class="text-center py-12 text-gray-500">
          Belum ada artikel.
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>