<script setup>
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
        default: true,
    },
    status: {
        type: String,
    },
});

const showPassword = ref(false);

const form = useForm({
    email: '',
    password: '',
    remember: true,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Masuk - HIMATIF POLINDRA" />

    <div
        class="min-h-screen w-full relative flex items-center justify-center p-4 sm:p-8 lg:p-12 overflow-x-hidden"
        style="background-color: #F95700; background-image: url('/images/login-bg.png'); background-size: cover; background-position: center; background-repeat: no-repeat;"
    >
        <!-- Overlay Lembut bila di layar kecil agar teks tetap tajam terbaca -->
        <div class="absolute inset-0 bg-black/5 pointer-events-none"></div>

        <!-- Main Container (2 Kolom di Desktop Sesuai Desain Mockup) -->
        <div class="max-w-6xl w-full mx-auto flex flex-col-reverse lg:flex-row items-center justify-between gap-10 lg:gap-16 relative z-10 py-8">
            
            <!-- Kolom Kiri: Card Form Login -->
            <div class="w-full max-w-[440px] shrink-0">
                <div class="bg-[#FAF7F2] rounded-[32px] p-7 sm:p-10 shadow-2xl shadow-orange-950/25 border border-white/60 space-y-6 text-left">
                    
                    <!-- Header Card -->
                    <div>
                        <h2
                            class="text-2xl sm:text-[28px] font-merriweather font-extrabold text-slate-900 tracking-tight"
                            style="font-family: 'Merriweather Sans', sans-serif;"
                        >
                            Halo!
                        </h2>
                        <p class="text-xs sm:text-[13.5px] text-slate-500 font-medium mt-1">
                            Masuk untuk lanjut ke akun kamu
                        </p>
                    </div>

                    <!-- Status Notification jika ada -->
                    <div v-if="status" class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-700">
                        {{ status }}
                    </div>

                    <!-- Form Input -->
                    <form @submit.prevent="submit" class="space-y-4">
                        <!-- Input Username / Email -->
                        <div class="space-y-1.5">
                            <label for="email" class="block text-xs font-bold text-slate-700">
                                Username atau Email<span class="text-red-500">*</span>
                            </label>
                            <input
                                id="email"
                                type="text"
                                v-model="form.email"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="Masukkan username atau email"
                                class="w-full px-4 py-3 rounded-xl bg-white border border-slate-200 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 shadow-xs transition"
                            />
                            <InputError class="mt-1" :message="form.errors.email" />
                        </div>

                        <!-- Input Password dengan Toggle Mata & Lupa Password -->
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between">
                                <label for="password" class="block text-xs font-bold text-slate-700">
                                    Password<span class="text-red-500">*</span>
                                </label>
                                <Link
                                    v-if="canResetPassword"
                                    :href="route('password.request')"
                                    class="text-xs font-semibold text-[#0095FF] hover:text-blue-600 transition"
                                >
                                    Lupa Password
                                </Link>
                            </div>
                            <div class="relative">
                                <input
                                    id="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    v-model="form.password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="••••••••"
                                    class="w-full px-4 py-3 pr-11 rounded-xl bg-white border border-slate-200 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 shadow-xs transition"
                                />
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition p-1"
                                    title="Tampilkan / Sembunyikan Password"
                                >
                                    <!-- Ikon Mata (Terbuka) -->
                                    <svg v-if="!showPassword" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <!-- Ikon Mata (Tertutup) -->
                                    <svg v-else class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                    </svg>
                                </button>
                            </div>
                            <InputError class="mt-1" :message="form.errors.password" />
                        </div>

                        <!-- Tombol Masuk -->
                        <div class="pt-2">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="w-full py-3.5 px-6 rounded-2xl font-bold text-sm sm:text-base text-white bg-[#F95700] hover:bg-orange-600 active:scale-[0.98] transition shadow-md shadow-orange-500/25 border border-orange-600/30 disabled:opacity-50 select-none"
                            >
                                <span v-if="form.processing">Memproses...</span>
                                <span v-else>Masuk</span>
                            </button>
                        </div>
                    </form>

                    <!-- Link Kembali ke Beranda -->
                    <div class="text-center pt-1 border-t border-slate-200/60">
                        <Link
                            href="/"
                            class="text-xs text-slate-500 hover:text-slate-800 font-medium transition inline-flex items-center gap-1"
                        >
                            <span>←</span>
                            <span>Kembali ke Beranda</span>
                        </Link>
                    </div>

                </div>
            </div>

            <!-- Kolom Kanan: Teks Selamat Datang (Sesuai Mockup) -->
            <div class="flex-1 w-full text-left space-y-4 lg:pl-6 select-none">
                <h1
                    class="text-5xl sm:text-6xl lg:text-[76px] font-merriweather font-extrabold text-white tracking-tight leading-[1.04] drop-shadow-sm"
                    style="font-family: 'Merriweather Sans', sans-serif;"
                >
                    Selamat<br />
                    datang di<br />
                    HIMATIF
                </h1>
                <p class="text-base sm:text-lg text-white/95 font-medium leading-relaxed max-w-lg pt-2 drop-shadow-xs">
                    Wadah kolaborasi mahasiswa Teknik Informatika untuk terus aktif bertumbuh dan berkarya
                </p>
            </div>

        </div>
    </div>
</template>
