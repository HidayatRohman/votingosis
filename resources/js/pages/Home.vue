<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { Head, Link, usePage } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { computed, ref } from 'vue';
import { Dialog, DialogScrollContent, DialogHeader, DialogTitle, DialogDescription } from '@/components/ui/dialog';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

withDefaults(
  defineProps<{ canRegister: boolean; previewCandidates?: any[] }>(),
  { canRegister: true, previewCandidates: () => [] }
);

// Ambil URL logo aplikasi dari props (hasil upload di Settings/Logo)
const page = usePage();
const appLogoUrl = computed(() => page.props.appLogoUrl as string | null);

// Dialog preview kandidat
const previewOpen = ref(false);
const photoUrl = (path?: string) => (path ? `/storage/${path}` : undefined);
</script>

<template>
  <Head title="E-Voting" />
  <div class="flex min-h-screen flex-col bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-700 text-white">
    <!-- Header -->
    <header class="sticky top-0 z-50 w-full">
      <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
        <div class="flex items-center gap-2">
          <!-- Tampilkan logo unggahan tanpa background jika tersedia -->
          <img v-if="appLogoUrl" :src="appLogoUrl" alt="Logo" class="h-6 w-auto object-contain" />
          <AppLogoIcon v-else class="size-6 text-white" />
          <span class="text-lg font-semibold tracking-tight">e-voting.</span>
        </div>
        <nav class="flex items-center gap-3">
          <template v-if="$page.props.auth?.user">
            <Link :href="dashboard()" class="rounded-md bg-white/20 px-4 py-2 text-sm font-medium backdrop-blur hover:bg-white/30">Dashboard</Link>
          </template>
          <template v-else>
            <Link :href="login()" class="rounded-md bg-white/20 px-4 py-2 text-sm font-medium backdrop-blur hover:bg-white/30">Login</Link>
            <Link v-if="canRegister" :href="register()" class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-50">Register</Link>
          </template>
        </nav>
      </div>
    </header>

    <!-- Hero -->
    <section class="mx-auto w-full max-w-7xl px-4 py-12 lg:py-20">
      <div class="grid items-center gap-8 lg:grid-cols-2">
        <div>
          <h1 class="text-3xl font-extrabold leading-tight md:text-4xl">Sistem Informasi E-Voting</h1>
          <p class="mt-2 text-lg font-semibold">Pemilihan Kepala Desa Online Berbasis Web</p>
          <p class="mt-4 text-sm text-blue-100">Selamat datang di sistem informasi e-voting online berbasis web. Akses mudah, kapan saja dan di mana saja.</p>
          <div class="mt-6 flex flex-wrap gap-3">
            <button @click="previewOpen = true" class="rounded-full border border-white/60 bg-white/10 px-5 py-2 text-sm font-medium hover:bg-white/20">Lihat Kandidat</button>
            <Link href="/voting" class="rounded-full bg-white px-5 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-50">Voting Sekarang</Link>
          </div>
        </div>
        <div class="relative hidden lg:block">
          <div class="absolute inset-0 -z-10 opacity-30" :style="{ background: 'radial-gradient(circle at 20% 10%, #93c5fd, transparent 40%), radial-gradient(circle at 80% 0%, #60a5fa, transparent 35%)' }" />
          <!-- Ganti laravel favicon dengan gambar unggahan transparan -->
          <img v-if="appLogoUrl" :src="appLogoUrl" alt="Illustration" class="mx-auto h-64 w-auto object-contain opacity-95" />
          <AppLogoIcon v-else class="mx-auto size-64 text-white opacity-80" />
        </div>
      </div>
    </section>

    <!-- Dialog preview kandidat dari "Daftar Kandidat" -->
    <Dialog v-model:open="previewOpen">
      <DialogScrollContent class="sm:max-w-3xl">
        <DialogHeader class="p-0">
          <div class="rounded-t-lg -mx-6 -mt-6 px-6 pt-6 pb-4 border-b border-sidebar-border/60 bg-gradient-to-r from-sky-100 via-indigo-100 to-fuchsia-100 dark:from-sky-900/40 dark:via-indigo-900/40 dark:to-fuchsia-900/40">
            <DialogTitle class="text-lg md:text-xl font-semibold">Daftar Kandidat</DialogTitle>
            <DialogDescription class="mt-1 text-sm text-neutral-700 dark:text-neutral-300">Preview kandidat yang tersedia saat ini.</DialogDescription>
          </div>
        </DialogHeader>

        <div class="py-2 max-h-[75vh] overflow-y-auto">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <Card v-for="item in ($props.previewCandidates ?? [])" :key="item.id" class="overflow-hidden">
              <CardHeader class="flex items-center justify-center text-center">
                <img v-if="item.foto" :src="photoUrl(item.foto)" alt="Foto Kandidat" class="h-32 w-32 object-cover rounded mx-auto" />
                <div v-else class="h-32 w-32 bg-muted rounded mx-auto" />
              </CardHeader>
              <CardContent class="space-y-1 text-sm">
                <div class="grid grid-cols-3">
                  <span class="font-medium">NIS</span>
                  <span class="col-span-2">{{ item.nip }}</span>
                </div>
                <div class="grid grid-cols-3">
                  <span class="font-medium">Nama</span>
                  <span class="col-span-2">{{ item.nama }}</span>
                </div>
                <div class="grid grid-cols-3">
                  <span class="font-medium">Kelas</span>
                  <span class="col-span-2">{{ item.kelas }}</span>
                </div>
                <div class="grid grid-cols-3">
                  <span class="font-medium">Jurusan</span>
                  <span class="col-span-2">{{ item.jurusan }}</span>
                </div>
                <div class="pt-3 flex justify-center">
                  <span class="text-xs text-muted-foreground">Total Suara: {{ item.votes_count }}</span>
                </div>
              </CardContent>
            </Card>
          </div>
          <div v-if="(($props.previewCandidates?.length ?? 0) === 0)" class="text-sm text-muted-foreground">Belum ada kandidat.</div>
        </div>
      </DialogScrollContent>
    </Dialog>

    <!-- Wave separator -->
    <div class="mt-auto">
      <svg viewBox="0 0 1440 120" class="h-24 w-full" preserveAspectRatio="none">
        <path d="M0,96 C240,144 480,48 720,72 C960,96 1200,24 1440,60 L1440,120 L0,120 Z" fill="white" fill-opacity="0.9" />
      </svg>
    </div>
  </div>
</template>