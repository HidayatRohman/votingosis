<script setup lang="ts">
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import AppContent from '@/components/AppContent.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription } from '@/components/ui/dialog';
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps<{ items: any[]; hasVoted: boolean }>();

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
        <AppSidebarHeader />

        <!-- Hero Header dengan background -->
        <div
          class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border mb-4"
        >
          <div
            class="absolute inset-0 opacity-25"
            :style="{ background: 'radial-gradient(circle at 15% 20%, #93c5fd33, transparent 35%), radial-gradient(circle at 85% 0%, #60a5fa33, transparent 30%), linear-gradient(135deg, #38bdf8 0%, #3b82f6 50%, #1d4ed8 100%)' }"
          />
          <div class="relative p-6 md:p-8 flex items-center justify-between">
            <div>
              <h1 class="text-2xl md:text-3xl font-bold">Kandidat OSIS</h1>
              <p class="mt-1 text-sm md:text-base text-neutral-700 dark:text-neutral-300">
                Pilih kandidat terbaik Anda. Satu akun hanya bisa vote sekali.
              </p>
            </div>
            <div>
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
                <Button size="sm" :disabled="props.hasVoted" @click="submitVote(item.id)">
                  {{ props.hasVoted ? 'Sudah vote' : 'Vote' }}
                </Button>
              </div>
            </CardContent>
          </Card>
        </div>
        
        <!-- Dialog Detail Kandidat -->
        <Dialog v-model:open="detailOpen">
          <DialogContent class="sm:max-w-2xl">
            <DialogHeader class="p-0">
              <div class="rounded-t-lg -mx-6 -mt-6 px-6 pt-6 pb-4 border-b border-sidebar-border/60 bg-gradient-to-r from-sky-600 to-indigo-600 text-white">
                <DialogTitle class="text-lg md:text-xl font-semibold">Detail Kandidat</DialogTitle>
                <DialogDescription class="mt-1 text-sm text-white/90">Informasi lengkap kandidat yang dipilih.</DialogDescription>
              </div>
            </DialogHeader>
            <div class="py-2">
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
              <div class="mt-6 flex justify-center">
                <Button :disabled="props.hasVoted || !selectedItem" @click="selectedItem && submitVote(selectedItem.id)">
                  {{ props.hasVoted ? 'Sudah vote' : 'Vote' }}
                </Button>
              </div>
            </div>
          </DialogContent>
        </Dialog>
    </AppContent>
  </AppShell>
</template>