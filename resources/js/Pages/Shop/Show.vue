<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

interface Product {
  produk_id: number
  nama_produk: string
  deskripsi_produk: string
  harga_produk: number
  harga_formatted?: string
  stok_produk: number
  gambar_produk: string | null
}

defineProps<{
  product: Product
}>()
</script>

<template>
  <AuthenticatedLayout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <Link :href="route('produk.index')" class="text-blue-600 hover:underline mb-4 inline-block">
          ← Kembali ke daftar produk
        </Link>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
          <div class="md:flex">
            <!-- Image -->
            <div class="md:w-1/2 h-96 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
              <img
                v-if="product.gambar_produk"
                :src="`/storage/${product.gambar_produk}`"
                :alt="product.nama_produk"
                class="w-full h-full object-cover"
              />
              <span v-else class="text-gray-400">No Image</span>
            </div>

            <!-- Details -->
            <div class="md:w-1/2 p-6">
              <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ product.nama_produk }}</h1>
              <p class="text-3xl font-bold text-blue-600 mt-4">
                {{ product.harga_formatted || `Rp ${Number(product.harga_produk).toLocaleString('id-ID')}` }}
              </p>

              <div class="mt-4">
                <span class="text-sm text-gray-500">Stok:</span>
                <span
                  class="ml-2 px-2 py-1 rounded text-sm font-medium"
                  :class="product.stok_produk < 5 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'"
                >
                  {{ product.stok_produk }}
                </span>
              </div>

              <div class="mt-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Deskripsi</h2>
                <p class="mt-2 text-gray-600 dark:text-gray-300">{{ product.deskripsi_produk }}</p>
              </div>

              <div class="mt-6 flex gap-3">
                <Link
                  :href="route('produk.edit', product.produk_id)"
                  class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600"
                >
                  Edit
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
