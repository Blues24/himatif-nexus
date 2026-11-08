<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav
                class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/95 backdrop-blur-md shadow-xs transition-all"
            >
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 sm:h-20 items-center justify-between">
                        <!-- Dua Logo Beriringan (Kiri) -->
                        <div class="flex shrink-0 items-center">
                            <Link :href="route('dashboard')" class="flex items-center gap-2.5 sm:gap-3">
                                <!-- Logo 1 -->
                                <img
                                    alt="Logo 1"
                                    src="/images/logo-polindra.png"
                                    class="h-9 sm:h-10 w-auto object-contain drop-shadow-xs"
                                />

                                <!-- Logo 2 -->
                                <img
                                    src="/images/logo-himatif.png"
                                    alt="Logo 2"
                                    class="h-9 sm:h-10 w-auto object-contain drop-shadow-xs"
                                />

                                <!-- (Opsional) Garis pembatas & Nama Teks di samping logo -->
                                <div class="hidden sm:flex items-center gap-2.5 pl-1 border-l border-slate-300">
                                    <span class="text-xs font-black tracking-tight leading-tight uppercase">
                                        <span class="text-[#F95700]">HIMATIF</span><br /><span class="text-slate-800 font-extrabold">POLINDRA</span>
                                    </span>
                                </div>
                            </Link>
                        </div>

                        <!-- Navigation Links (Tengah) -->
                        <nav
                            class="hidden md:flex flex-1 items-center justify-center space-x-6 lg:space-x-8"
                        >
                            <!-- 1. Beranda (Aktif - Oranye) -->
                            <Link
                                :href="route('dashboard')"
                                class="inline-flex items-center px-1 pt-1 text-sm font-bold text-orange-600 transition"
                            >
                                Beranda
                            </Link>

                            <!-- 2. Informasi Dropdown -->
                            <div class="relative">
                                <Dropdown align="left" width="48">
                                    <template #trigger>
                                        <button
                                            type="button"
                                            class="inline-flex items-center gap-1 px-1 pt-1 text-sm font-semibold text-slate-700 hover:text-orange-600 transition focus:outline-none"
                                        >
                                            <span>Informasi</span>
                                            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </template>
                                    <template #content>
                                        <DropdownLink :href="route('dashboard') + '#informasi'">
                                            Formatif & Artikel
                                        </DropdownLink>
                                        <DropdownLink :href="route('dashboard') + '#layanan'">
                                            Materi & Bank Soal
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>

                            <!-- 3. Kegiatan Dropdown -->
                            <div class="relative">
                                <Dropdown align="left" width="48">
                                    <template #trigger>
                                        <button
                                            type="button"
                                            class="inline-flex items-center gap-1 px-1 pt-1 text-sm font-semibold text-slate-700 hover:text-orange-600 transition focus:outline-none"
                                        >
                                            <span>Kegiatan</span>
                                            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </template>
                                    <template #content>
                                        <DropdownLink :href="route('dashboard') + '#agenda'">
                                            Agenda Terdekat
                                        </DropdownLink>
                                        <DropdownLink :href="route('dashboard') + '#agenda'">
                                            Workshop & Pelatihan
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>

                            <!-- 4. Lainnya Dropdown -->
                            <div class="relative">
                                <Dropdown align="left" width="48">
                                    <template #trigger>
                                        <button
                                            type="button"
                                            class="inline-flex items-center gap-1 px-1 pt-1 text-sm font-semibold text-slate-700 hover:text-orange-600 transition focus:outline-none"
                                        >
                                            <span>Lainnya</span>
                                            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </template>
                                    <template #content>
                                        <DropdownLink :href="route('dashboard') + '#layanan'">
                                            Penyewaan Alat
                                        </DropdownLink>
                                        <DropdownLink :href="route('dashboard') + '#layanan'">
                                            Merch Shop
                                        </DropdownLink>
                                        <DropdownLink :href="route('dashboard') + '#layanan'">
                                            Iuran Kas
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </nav>

                        <!-- Sisi Kanan (Login Admin / Dropdown Akun) -->
                        <div class="hidden sm:flex shrink-0 items-center">
                            <!-- Settings Dropdown / Login Admin -->
                            <template v-if="$page.props.auth && $page.props.auth.user">
                                <div class="relative ms-3">
                                    <Dropdown align="right" width="48">
                                        <template #trigger>
                                            <span class="inline-flex rounded-md">
                                                <button
                                                    type="button"
                                                    class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-700 transition duration-150 ease-in-out hover:text-gray-900 focus:outline-none"
                                                >
                                                    {{ $page.props.auth.user.name }}

                                                    <svg
                                                        class="-me-0.5 ms-2 h-4 w-4"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20"
                                                        fill="currentColor"
                                                    >
                                                        <path
                                                            fill-rule="evenodd"
                                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                            clip-rule="evenodd"
                                                        />
                                                    </svg>
                                                </button>
                                            </span>
                                        </template>

                                        <template #content>
                                            <DropdownLink
                                                :href="route('profile.edit')"
                                            >
                                                Profile
                                            </DropdownLink>
                                            <DropdownLink
                                                :href="route('logout')"
                                                method="post"
                                                as="button"
                                            >
                                                Log Out
                                            </DropdownLink>
                                        </template>
                                    </Dropdown>
                                </div>
                            </template>
                            <template v-else>
                                <Link
                                    :href="route('login')"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white text-xs font-bold rounded-full shadow-xs transition"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                    </svg>
                                    Login Admin
                                </Link>
                            </template>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                            Beranda
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('dashboard') + '#informasi'">
                            Informasi
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('dashboard') + '#agenda'">
                            Kegiatan
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('dashboard') + '#layanan'">
                            Lainnya
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div
                        class="border-t border-gray-200 pb-1 pt-4"
                    >
                        <template v-if="$page.props.auth && $page.props.auth.user">
                            <div class="px-4">
                                <div
                                    class="text-base font-medium text-gray-800"
                                >
                                    {{ $page.props.auth.user.name }}
                                </div>
                                <div class="text-sm font-medium text-gray-500">
                                    {{ $page.props.auth.user.email }}
                                </div>
                            </div>

                            <div class="mt-3 space-y-1">
                                <ResponsiveNavLink :href="route('profile.edit')">
                                    Profile
                                </ResponsiveNavLink>
                                <ResponsiveNavLink
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                >
                                    Log Out
                                </ResponsiveNavLink>
                            </div>
                        </template>
                        <template v-else>
                            <div class="px-4 py-2">
                                <Link
                                    :href="route('login')"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-orange-500 to-orange-600 text-white text-xs font-bold rounded-xl shadow"
                                >
                                    Login Admin
                                </Link>
                            </div>
                        </template>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header
                class="bg-white shadow"
                v-if="$slots.header"
            >
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
