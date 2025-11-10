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

        <div class="flex items-center justify-between">
          <h1 class="text-xl font-semibold">Data Voting</h1>
          <Link href="/voting/create">
            <Button>Tambah</Button>
          </Link>
        </div>

        <Card class="mt-4">
          <CardHeader>
            <CardTitle>Daftar Kandidat</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="overflow-x-auto">
              <table class="min-w-full text-sm">
                <thead>
                  <tr class="text-left border-b">
                    <th class="p-2">Foto</th>
                    <th class="p-2">NIS</th>
                    <th class="p-2">Nama</th>
                    <th class="p-2">Kelas</th>
                    <th class="p-2">Jurusan</th>
                    <th class="p-2">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in items.data" :key="item.id" class="border-b">
                    <td class="p-2">
                      <img v-if="item.foto" :src="photoUrl(item.foto)" alt="foto" class="h-12 w-12 object-cover rounded" />
                    </td>
                    <td class="p-2">{{ item.nip }}</td>
                    <td class="p-2">{{ item.nama }}</td>
                    <td class="p-2">{{ item.kelas }}</td>
                    <td class="p-2">{{ item.jurusan }}</td>
                    <td class="p-2 space-x-2">
                      <Link :href="`/voting/${item.id}/edit`"><Button variant="outline">Edit</Button></Link>
                      <Button variant="destructive" @click="destroyItem(item.id)">Hapus</Button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </CardContent>
        </Card>
      </AppContent>
    </div>
  </AppShell>
</template>