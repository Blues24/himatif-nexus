<script setup>
import Sidebar from '@/Components/Admin/Sidebar.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    title: {
        type: String,
        default: 'Dashboard',
    },
    subtitle: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div>
        <Head :title="title" />

        <div class="admin-wrapper">
            <Sidebar />

            <main class="admin-main">
                <header class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                    <div>
                        <h1 class="fw-bold mb-1" style="font-size: 24px; color: #111827;">
                            {{ title }}
                        </h1>
                        <p v-if="subtitle" class="mb-0" style="font-size: 13.5px; color: #6B7280;">
                            {{ subtitle }}
                        </p>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <slot name="actions" />
                    </div>
                </header>

                <slot />
            </main>
        </div>
    </div>
</template>
