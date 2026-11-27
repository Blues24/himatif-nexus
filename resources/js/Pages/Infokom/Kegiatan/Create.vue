<script setup>
import { useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const form = useForm({
    nama_kegiatan: "",
    deskripsi_kegiatan: "",
    tanggal_pelaksanaan: "",
    status_kegiatan: "rencana",
});

function submit() {
    form.post(route("kegiatan.store"), {
        onSuccess: () => form.reset(),
    });
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="py-8">
            <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-xl font-semibold text-slate-800 mb-6">
                    Tambah Kegiatan Baru
                </h1>

                <form
                    @submit.prevent="submit"
                    class="space-y-5 bg-white p-6 rounded-lg border border-slate-200 shadow-xs"
                >
                    <div>
                        <label class="block text-sm font-medium text-slate-700"
                            >Nama Kegiatan</label
                        >
                        <input
                            v-model="form.nama_kegiatan"
                            type="text"
                            required
                            class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200/50 text-sm"
                        />
                        <p
                            v-if="form.errors.nama_kegiatan"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ form.errors.nama_kegiatan }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700"
                            >Deskripsi</label
                        >
                        <textarea
                            v-model="form.deskripsi_kegiatan"
                            required
                            rows="4"
                            class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200/50 text-sm"
                        ></textarea>
                        <p
                            v-if="form.errors.deskripsi_kegiatan"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ form.errors.deskripsi_kegiatan }}
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-slate-700"
                                >Tanggal Pelaksanaan</label
                            >
                            <input
                                v-model="form.tanggal_pelaksanaan"
                                type="date"
                                required
                                class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200/50 text-sm"
                            />
                            <p
                                v-if="form.errors.tanggal_pelaksanaan"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.tanggal_pelaksanaan }}
                            </p>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-slate-700"
                                >Status</label
                            >
                            <select
                                v-model="form.status_kegiatan"
                                class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200/50 text-sm"
                            >
                                <option value="rencana">Rencana</option>
                                <option value="berjalan">Berjalan</option>
                                <option value="selesai">Selesai</option>
                            </select>
                            <p
                                v-if="form.errors.status_kegiatan"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.status_kegiatan }}
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <Link
                            :href="route('kegiatan.index')"
                            class="px-4 py-2 text-sm text-slate-600 hover:text-slate-800 transition"
                            >Batal</Link
                        >
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-4 py-2 text-sm bg-orange-500 text-white rounded-md hover:bg-orange-600 transition disabled:opacity-50"
                        >
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
