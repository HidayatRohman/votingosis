<script setup lang="ts">
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import AppContent from '@/components/AppContent.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Dialog, DialogScrollContent, DialogHeader, DialogTitle, DialogDescription } from '@/components/ui/dialog';
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{ items: any[]; hasVoted: boolean; isVotingOpen: boolean }>();

const breadcrumbItems: BreadcrumbItem[] = [
  { title: 'Kandidat', href: '/candidates' },
];

const photoUrl = (path?: string) => (path ? `/storage/${path}` : undefined);

const submitVote = (id: number) => {
  router.post(`/vote/${id}`);
};

// Dialog detail kandidat
const detailOpen = ref(false);
const selectedItem = ref<any | null>(null);
const openDetail = (item: any) => {
  selectedItem.value = item;
  detailOpen.value = true;
};
</script>

<template>
  <AppShell variant="sidebar">
    <AppSidebar />
    <AppContent variant="sidebar" class="p-4">
        <AppSidebarHeader :breadcrumbs="breadcrumbItems" />

        <!-- Hero Header dengan background biru dan teks kontras -->
        <div
          class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border mt-3 mb-4 bg-gradient-to-r from-sky-600 to-indigo-600 text-white"
        >
          <div class="relative p-6 md:p-8 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
              <h1 class="text-xl md:text-2xl font-bold">Kandidat OSIS</h1>
              <p class="mt-1 text-xs md:text-sm text-white/90">
                Pilih kandidat terbaik Anda. Satu akun hanya bisa vote sekali.
              </p>
            </div>
            <div class="mt-3 md:mt-0">
              <span
                v-if="props.hasVoted"
                class="text-xs font-medium px-3 py-1 rounded bg-green-600 text-white"
              >Sudah vote</span>
              <span
                v-else
                class="text-xs font-medium px-3 py-1 rounded bg-yellow-500 text-white"
              >Belum vote</span>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
          <Card v-for="item in props.items" :key="item.id" class="overflow-hidden">
            <CardHeader class="flex items-center justify-center text-center">
              <img
                v-if="item.foto"
                :src="photoUrl(item.foto)"
                alt="Foto Kandidat"
                class="h-40 w-40 object-cover rounded mx-auto"
              />
              <div v-else class="h-40 w-40 bg-muted rounded mx-auto" />
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
              <div class="pt-3 flex justify-center gap-2">
                <Button size="sm" variant="outline" @click="openDetail(item)">Detail</Button>
                <Button v-if="props.isVotingOpen" size="sm" :disabled="props.hasVoted" @click="submitVote(item.id)">
                  {{ props.hasVoted ? 'Sudah vote' : 'Vote' }}
                </Button>
              </div>
            </CardContent>
          </Card>
        </div>
        
        <!-- Dialog Detail Kandidat -->
        <Dialog v-model:open="detailOpen">
          <DialogScrollContent class="sm:max-w-2xl">
            <DialogHeader class="p-0">
              <div class="rounded-t-lg -mt-2 md:-mt-4 px-4 md:px-6 pt-5 pb-4 border-b border-sidebar-border/60 bg-gradient-to-r from-sky-600 to-indigo-600 text-white">
                <DialogTitle class="text-lg md:text-xl font-semibold">Detail Kandidat</DialogTitle>
                <DialogDescription class="mt-1 text-sm text-white/90">Informasi lengkap kandidat yang dipilih.</DialogDescription>
              </div>
            </DialogHeader>
            <div class="py-2 max-h-[80vh] overflow-y-auto px-2 md:px-4">
              <!-- Susunan dua kolom: foto kiri full kolom, data kanan -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                <div>
                  <img v-if="selectedItem?.foto" :src="photoUrl(selectedItem?.foto)" alt="Foto Kandidat" class="w-full h-64 object-cover rounded" />
                  <div v-else class="w-full h-64 bg-muted rounded" />
                </div>
                <div class="grid gap-2 text-sm">
                  <div class="grid grid-cols-3">
                    <span class="font-medium">NIS</span>
                    <span class="col-span-2">{{ selectedItem?.nip }}</span>
                  </div>
                  <div class="grid grid-cols-3">
                    <span class="font-medium">Nama</span>
                    <span class="col-span-2">{{ selectedItem?.nama }}</span>
                  </div>
                  <div class="grid grid-cols-3">
                    <span class="font-medium">Kelas</span>
                    <span class="col-span-2">{{ selectedItem?.kelas }}</span>
                  </div>
                  <div class="grid grid-cols-3">
                    <span class="font-medium">Jurusan</span>
                    <span class="col-span-2">{{ selectedItem?.jurusan }}</span>
                  </div>
                </div>
              </div>

              <!-- Visi dan Misi dua kolom di bagian bawah, masing-masing dalam kartu dengan header biru -->
              <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <Card class="overflow-hidden">
                  <CardHeader class="p-3 bg-gradient-to-r from-sky-600 to-indigo-600 text-white">
                    <CardTitle class="text-sm md:text-base">Visi</CardTitle>
                  </CardHeader>
                  <CardContent class="p-4">
                    <p class="text-sm text-muted-foreground whitespace-pre-line">{{ selectedItem?.visi || '-' }}</p>
                  </CardContent>
                </Card>
                <Card class="overflow-hidden">
                  <CardHeader class="p-3 bg-gradient-to-r from-sky-600 to-indigo-600 text-white">
                    <CardTitle class="text-sm md:text-base">Misi</CardTitle>
                  </CardHeader>
                  <CardContent class="p-4">
                    <p class="text-sm text-muted-foreground whitespace-pre-line">{{ selectedItem?.misi || '-' }}</p>
                  </CardContent>
                </Card>
              </div>

              <!-- Tombol Vote di bawah -->
              <div v-if="props.isVotingOpen" class="mt-6 flex justify-center">
                <Button :disabled="props.hasVoted || !selectedItem" @click="selectedItem && submitVote(selectedItem.id)">
                  {{ props.hasVoted ? 'Sudah vote' : 'Vote' }}
                </Button>
              </div>
            </div>
          </DialogScrollContent>
        </Dialog>
    </AppContent>
  </AppShell>
</template>