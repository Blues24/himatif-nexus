<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

const menuItems = [
    {
        label: 'Dashboard',
        routeName: 'admin.dashboard.index',
        icon: 'dashboard',
    },
    {
        heading: 'KELOLA',
    },
    {
        label: 'Shop',
        routeName: 'admin.shop.index',
        icon: 'shop',
    },
    {
        label: 'Informasi',
        routeName: 'admin.formatif.index',
        icon: 'informasi',
    },
    {
        label: 'Inventaris',
        routeName: 'admin.inventaris.index',
        icon: 'inventaris',
    },
    {
        label: 'Kegiatan',
        routeName: 'admin.kegiatan.index',
        icon: 'kegiatan',
    },
];

function isActive(routeName) {
    if (!routeName) return false;
    return route().current(routeName + '*');
}
</script>

<template>
    <aside class="relative z-30 flex w-full flex-col bg-gray-900 md:fixed md:inset-y-0 md:left-0 md:w-64">
        <!-- Logo -->
        <div class="flex items-center gap-3 px-6 py-5">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-teal-500/20">
                <svg class="h-6 w-6 text-teal-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2L2 7l10 5 10-5-10-5z" />
                    <path d="M2 17l10 5 10-5" />
                    <path d="M2 12l10 5 10-5" />
                </svg>
            </div>
            <div>
                <div class="text-base font-bold text-white">HIMATIF</div>
                <div class="text-xs text-gray-400">Panel Admin</div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
            <template v-for="(item, index) in menuItems" :key="index">
                <!-- Section Heading -->
                <div
                    v-if="item.heading"
                    class="px-3 pb-2 pt-5 text-xs font-semibold uppercase tracking-wider text-gray-500"
                >
                    {{ item.heading }}
                </div>

                <!-- Menu Link -->
                <Link
                    v-else
                    :href="route(item.routeName)"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors"
                    :class="[
                        isActive(item.routeName)
                            ? 'bg-teal-500 text-white'
                            : 'text-gray-300 hover:bg-gray-800 hover:text-white',
                    ]"
                >
                    <!-- Icons -->
                    <svg v-if="item.icon === 'dashboard'" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" rx="1" />
                        <rect x="14" y="3" width="7" height="7" rx="1" />
                        <rect x="3" y="14" width="7" height="7" rx="1" />
                        <rect x="14" y="14" width="7" height="7" rx="1" />
                    </svg>
                    <svg v-else-if="item.icon === 'shop'" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <path d="M16 10a4 4 0 01-8 0" />
                    </svg>
                    <svg v-else-if="item.icon === 'informasi'" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" y1="13" x2="8" y2="13" />
                        <line x1="16" y1="17" x2="8" y2="17" />
                        <polyline points="10 9 9 9 8 9" />
                    </svg>
                    <svg v-else-if="item.icon === 'inventaris'" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="21 8 21 21 3 21 3 8" />
                        <rect x="1" y="3" width="22" height="5" />
                        <line x1="10" y1="12" x2="14" y2="12" />
                    </svg>
                    <svg v-else-if="item.icon === 'kegiatan'" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>

                    {{ item.label }}
                </Link>
            </template>
        </nav>

        <!-- User Info -->
        <div class="border-t border-gray-800 px-4 py-4">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-700 text-sm font-medium text-gray-300">
                    {{ user?.nama?.substring(0, 2)?.toUpperCase() ?? 'AD' }}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="truncate text-sm font-medium text-white">
                        {{ user?.nama ?? 'Admin' }}
                    </div>
                    <div class="truncate text-xs text-gray-400">
                        Akses penuh
                    </div>
                </div>
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="rounded-md p-1.5 text-gray-400 transition-colors hover:bg-gray-800 hover:text-white"
                    title="Logout"
                >
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" y1="12" x2="9" y2="12" />
                    </svg>
                </Link>
            </div>
        </div>
    </aside>
</template>

