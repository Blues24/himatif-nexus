<script setup>
import Sidebar from '@/Components/Admin/Sidebar.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    title: {
        type: String,
        default: '',
    },
});
</script>

<template>
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

