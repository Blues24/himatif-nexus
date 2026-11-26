<script setup>
import { router, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    kegiatan: {
        type: Object,
        required: true,
    },
});

const form = router.form(
    "put",
    route("kegiatan.update", props.kegiatan.kegiatan_id),
    {
        _method: "put",
        nama_kegiatan: props.kegiatan.nama_kegiatan,
        deskripsi_kegiatan: props.kegiatan.deskripsi_kegiatan,
        tanggal_pelaksanaan: props.kegiatan.tanggal_pelaksanaan,
        status_kegiatan: props.kegiatan.status_kegiatan,
    },
);

function submit() {
    form.submit({
        onError: () => null,
        onSuccess: () => router.visit(route("kegiatan.index")),
    });
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="py-8">
            <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-xl font-semibold text-slate-800 mb-6">
                    Edit Kegiatan
                </h1>

                <form
                    @submit.prevent="submit"
                    class="space-y-5 bg-white p-6 rounded-lg border border-slate-200 shadow-xs"
                >
                    <div>
                        <InputLabel for="nama_kegiatan" value="Nama Kegiatan" />
                        <TextInput
                            id="nama_kegiatan"
                            v-model="form.nama_kegiatan"
                            type="text"
                            required
                            class="mt-1 block w-full"
                        />
                        <InputError
                            :message="form.errors.nama_kegiatan"
                            class="mt-2"
                        />
                    </div>

                    <div>
                        <InputLabel
                            for="deskripsi_kegiatan"
                            value="Deskripsi"
                        />
                        <textarea
                            id="deskripsi_kegiatan"
                            v-model="form.deskripsi_kegiatan"
                            required
                            rows="4"
                            class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200/50 text-sm"
                        />
                        <InputError
                            :message="form.errors.deskripsi_kegiatan"
                            class="mt-2"
                        />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <InputLabel
                                for="tanggal_pelaksanaan"
                                value="Tanggal Pelaksanaan"
                            />
                            <TextInput
                                id="tanggal_pelaksanaan"
                                v-model="form.tanggal_pelaksanaan"
                                type="date"
                                required
                                class="mt-1 block w-full"
                            />
                            <InputError
                                :message="form.errors.tanggal_pelaksanaan"
                                class="mt-2"
                            />
                        </div>
                        <div>
                            <InputLabel for="status_kegiatan" value="Status" />
                            <select
                                id="status_kegiatan"
                                v-model="form.status_kegiatan"
                                class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200/50 text-sm"
                            >
                                <option value="rencana">Rencana</option>
                                <option value="berjalan">Berjalan</option>
                                <option value="selesai">Selesai</option>
                            </select>
                            <InputError
                                :message="form.errors.status_kegiatan"
                                class="mt-2"
                            />
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <Link
                            :href="route('kegiatan.index')"
                            class="px-4 py-2 text-sm text-slate-600 hover:text-slate-800 transition"
                            >Batal</Link
                        >
                        <PrimaryButton :disabled="form.processing"
                            >Simpan Perubahan</PrimaryButton
                        >
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
