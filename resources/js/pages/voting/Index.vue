<script setup lang="ts">
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import AppContent from '@/components/AppContent.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Link, router } from '@inertiajs/vue3';

defineProps<{ items: any }>();

const photoUrl = (path?: string) => (path ? `/storage/${path}` : undefined);

const destroyItem = (id: number) => {
  if (confirm('Hapus data ini?')) {
    router.delete(`/voting/${id}`);
  }
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
            :style="{ background: 'radial-gradient(circle at 20% 20%, #93c5fd33, transparent 35%), radial-gradient(circle at 80% 0%, #60a5fa33, transparent 30%), linear-gradient(135deg, #38bdf8 0%, #3b82f6 50%, #1d4ed8 100%)' }"
          />
          <div class="relative p-6 md:p-8 flex items-center justify-between">
            <div>
              <h1 class="text-2xl md:text-3xl font-bold">Data Voting</h1>
              <p class="mt-1 text-sm md:text-base text-neutral-700 dark:text-neutral-300">
                Kelola kandidat OSIS. Tambahkan, edit, dan hapus data dengan mudah.
              </p>
            </div>
            <Link href="/voting/create">
              <Button variant="outline" class="bg-white/70 backdrop-blur-sm dark:bg-neutral-800/60">Tambah</Button>
            </Link>
          </div>
        </div>

        <Card class="mt-4">
          <CardHeader>
            <CardTitle>Daftar Kandidat</CardTitle>
          </CardHeader>
          <CardContent>
            <div
              class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4"
            >
              <Card
                v-for="item in items.data"
                :key="item.id"
                class="overflow-hidden group"
              >
                <CardHeader class="relative p-0">
                  <img
                    v-if="item.foto"
                    :src="photoUrl(item.foto)"
                    alt="Foto Kandidat"
                    class="h-40 w-full object-cover"
                  />
                  <div v-else class="h-40 w-full bg-muted" />
                  <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity" />
                  <div class="absolute top-2 right-2 flex gap-2">
                    <Link :href="`/voting/${item.id}/edit`">
                      <Button size="sm" variant="outline">Edit</Button>
                    </Link>
                    <Button size="sm" variant="destructive" @click="destroyItem(item.id)">Hapus</Button>
                  </div>
                </CardHeader>
                <CardContent class="p-4 space-y-2 text-sm">
                  <div class="flex items-center justify-between">
                    <span class="text-xs font-medium px-2 py-0.5 rounded bg-accent text-accent-foreground">{{ item.kelas }}</span>
                    <span class="text-xs text-muted-foreground">NIS: {{ item.nip }}</span>
                  </div>
                  <div class="text-base font-semibold">{{ item.nama }}</div>
                  <div class="text-sm text-muted-foreground">Jurusan: {{ item.jurusan }}</div>
                </CardContent>
              </Card>
            </div>

            <div v-if="(items?.data?.length ?? 0) === 0" class="text-sm text-muted-foreground">
              Belum ada kandidat.
            </div>
          </CardContent>
        </Card>
      </AppContent>
    </div>
  </AppShell>
</template>