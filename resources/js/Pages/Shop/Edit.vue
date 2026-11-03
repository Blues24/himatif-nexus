<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

interface Product {
  produk_id: number
  nama_produk: string
  deskripsi_produk: string
  harga_produk: number
  stok_produk: number
  gambar_produk: string | null
}

const props = defineProps<{
  product: Product
}>()

const form = useForm({
  nama_produk: props.product.nama_produk,
  deskripsi_produk: props.product.deskripsi_produk,
  harga_produk: props.product.harga_produk,
  stok_produk: props.product.stok_produk,
  gambar_produk: null as File | null,
})

function submit() {
  form.put(route('produk.update', props.product.produk_id))
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="py-12">
      <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">
          Edit Produk
        </h1>

        <form @submit.prevent="submit" class="space-y-6 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Produk</label>
            <input v-model="form.nama_produk" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600" />
            <p v-if="form.errors.nama_produk" class="text-red-600 text-sm mt-1">{{ form.errors.nama_produk }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
            <textarea v-model="form.deskripsi_produk" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600" />
            <p v-if="form.errors.deskripsi_produk" class="text-red-600 text-sm mt-1">{{ form.errors.deskripsi_produk }}</p>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga (Rp)</label>
              <input v-model="form.harga_produk" type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600" />
              <p v-if="form.errors.harga_produk" class="text-red-600 text-sm mt-1">{{ form.errors.harga_produk }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stok</label>
              <input v-model="form.stok_produk" type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600" />
              <p v-if="form.errors.stok_produk" class="text-red-600 text-sm mt-1">{{ form.errors.stok_produk }}</p>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gambar (ganti jika diperlukan)</label>
            <input v-model="form.gambar_produk" type="file" accept="image/*" class="mt-1 block w-full text-sm dark:text-gray-400" />
            <p v-if="form.errors.gambar_produk" class="text-red-600 text-sm mt-1">{{ form.errors.gambar_produk }}</p>
          </div>

          <div class="flex justify-end gap-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700" :disabled="form.processing">
              {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
