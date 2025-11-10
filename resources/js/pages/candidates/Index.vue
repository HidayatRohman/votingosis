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

        <h1 class="text-xl font-semibold mb-4">Kandidat</h1>

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