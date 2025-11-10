<script setup lang="ts">
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import AppContent from '@/components/AppContent.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps<{ items: any[]; hasVoted: boolean }>();

const photoUrl = (path?: string) => (path ? `/storage/${path}` : undefined);

const submitVote = (id: number) => {
  router.post(`/vote/${id}`);
};
</script>

<template>
  <AppShell variant="sidebar">
    <AppSidebar />
    <div class="flex flex-1">
      <AppContent class="p-4">
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
              <div class="pt-3 flex justify-center">
                <Button size="sm" :disabled="props.hasVoted" @click="submitVote(item.id)">
                  {{ props.hasVoted ? 'Sudah vote' : 'Vote' }}
                </Button>
              </div>
            </CardContent>
          </Card>
        </div>
      </AppContent>
    </div>
  </AppShell>
</template>