<script setup lang="ts">
import { ref } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

interface Product {
  produk_id: number
  nama_produk: string
  harga_produk: number
  stok_produk: number
  gambar_produk: string | null
}

defineProps<{
  products: Product[]
  filters: { q: string }
  lowStock?: Array<{ produk_id: number; nama_produk: string; stok_produk: number }>
}>()

const search = ref('')
</script>

<template>
  <AuthenticatedLayout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6 flex justify-between items-center">
          <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
            Danus Shop — Katalog Produk
          </h1>
          <Link
            :href="route('produk.create')"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
          >
            + Tambah Produk
          </Link>
        </div>

        <!-- Search -->
        <div class="mb-6">
          <input
            v-model="search"
            type="text"
            placeholder="Cari produk..."
            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white"
          />
        </div>

        <!-- Low Stock Warning -->
        <div v-if="lowStock?.length" class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg dark:bg-yellow-900/20 dark:border-yellow-800">
          <h3 class="font-semibold text-yellow-800 dark:text-yellow-200 mb-2">⚠️ Stok Menipis</h3>
          <ul class="space-y-1">
            <li v-for="item in lowStock" :key="item.produk_id" class="text-sm text-yellow-700 dark:text-yellow-300">
              {{ item.nama_produk }} — tinggal {{ item.stok_produk }}
            </li>
          </ul>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <Link
            v-for="product in products"
            :key="product.produk_id"
            :href="route('produk.show', product.produk_id)"
            class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden hover:shadow-md transition"
          >
            <div class="h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
              <span v-if="!product.gambar_produk" class="text-gray-400">No Image</span>
              <img v-else :src="`/storage/${product.gambar_produk}`" :alt="product.nama_produk" class="w-full h-full object-cover" />
            </div>
            <div class="p-4">
              <h3 class="font-semibold text-gray-900 dark:text-white truncate">{{ product.nama_produk }}</h3>
              <p class="text-lg font-bold text-blue-600 mt-2">
                Rp {{ Number(product.harga_produk).toLocaleString('id-ID') }}
              </p>
              <p class="text-sm mt-1" :class="product.stok_produk < 5 ? 'text-red-600' : 'text-gray-500'">
                Stok: {{ product.stok_produk }}
              </p>
            </div>
          </Link>
        </div>

        <div v-if="!products?.length" class="text-center py-12 text-gray-500">
          Tidak ada produk ditemukan.
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
