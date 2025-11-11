<script setup lang="ts">
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import AppContent from '@/components/AppContent.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Link, router, useForm } from '@inertiajs/vue3';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription, DialogFooter } from '@/components/ui/dialog';
import VotingForm from '@/components/voting/VotingForm.vue';
import { ref } from 'vue';

defineProps<{ items: any }>();

const photoUrl = (path?: string) => (path ? `/storage/${path}` : undefined);

const destroyItem = (id: number) => {
  if (confirm('Hapus data ini?')) {
    router.delete(`/voting/${id}`);
  }
};

const createOpen = ref(false);
const form = useForm({
  nis: '',
  nama: '',
  kelas: '',
  jurusan: '',
  foto: null as any,
  visi: '',
  misi: '',
});

const submitCreate = () => {
  form.post('/voting', {
    forceFormData: true,
    onSuccess: () => {
      createOpen.value = false;
      form.reset('foto');
    },
    preserveScroll: true,
  } as any);
};

// Edit dialog state & form
const editOpen = ref(false);
const editingItem = ref<any | null>(null);
const editForm = useForm({
  nis: '',
  nama: '',
  kelas: '',
  jurusan: '',
  foto: null as any,
  visi: '',
  misi: '',
});

const openEdit = (item: any) => {
  editingItem.value = item;
  editForm.nis = item.nip ?? '';
  editForm.nama = item.nama ?? '';
  editForm.kelas = item.kelas ?? '';
  editForm.jurusan = item.jurusan ?? '';
  editForm.visi = item.visi ?? '';
  editForm.misi = item.misi ?? '';
  editForm.foto = null as any;
  editOpen.value = true;
};

const submitEdit = () => {
  if (!editingItem.value) return;
  editForm
    .transform((data: any) => ({ ...data, _method: 'put' }))
    .post(`/voting/${editingItem.value.id}`, {
      forceFormData: true,
      preserveScroll: true,
      onSuccess: () => {
        editOpen.value = false;
        editForm.reset('foto');
      },
    } as any);
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
            :style="{ background: 'radial-gradient(circle at 20% 20%, #93c5fd33, transparent 35%), radial-gradient(circle at 80% 0%, #60a5fa33, transparent 30%), linear-gradient(135deg, #38bdf8 0%, #3b82f6 50%, #1d4ed8 100%)' }"
          />
          <div class="relative p-6 md:p-8 flex items-center justify-between">
            <div>
              <h1 class="text-2xl md:text-3xl font-bold">Data Voting</h1>
              <p class="mt-1 text-sm md:text-base text-neutral-700 dark:text-neutral-300">
                Kelola kandidat OSIS. Tambahkan, edit, dan hapus data dengan mudah.
              </p>
            </div>
            <Button variant="outline" class="bg-white/70 backdrop-blur-sm dark:bg-neutral-800/60" @click="createOpen = true">Tambah</Button>
          </div>
        </div>

        <!-- Modal Tambah Data Voting -->
        <Dialog v-model:open="createOpen">
          <DialogContent class="sm:max-w-2xl">
            <DialogHeader class="p-0">
              <div
                class="rounded-t-lg -mx-6 -mt-6 px-6 pt-6 pb-4 border-b border-sidebar-border/60 bg-gradient-to-r from-sky-100 via-indigo-100 to-fuchsia-100 dark:from-sky-900/40 dark:via-indigo-900/40 dark:to-fuchsia-900/40"
              >
                <DialogTitle class="text-lg md:text-xl font-semibold">Tambah Data Voting</DialogTitle>
                <DialogDescription class="mt-1 text-sm text-neutral-700 dark:text-neutral-300">Isi form kandidat OSIS dengan lengkap.</DialogDescription>
              </div>
            </DialogHeader>
            <div class="py-2">
              <VotingForm :form="form" :on-submit="submitCreate" variant="plain" :show-actions="false" />
            </div>
            <DialogFooter>
              <div class="flex w-full justify-end gap-2">
                <Button variant="ghost" @click="createOpen = false">Batal</Button>
                <Button variant="default" @click="submitCreate">Simpan</Button>
              </div>
            </DialogFooter>
          </DialogContent>
        </Dialog>

        <!-- Modal Edit Data Voting -->
        <Dialog v-model:open="editOpen">
          <DialogContent class="sm:max-w-2xl">
            <DialogHeader class="p-0">
              <div
                class="rounded-t-lg -mx-6 -mt-6 px-6 pt-6 pb-4 border-b border-sidebar-border/60 bg-gradient-to-r from-sky-100 via-indigo-100 to-fuchsia-100 dark:from-sky-900/40 dark:via-indigo-900/40 dark:to-fuchsia-900/40"
              >
                <DialogTitle class="text-lg md:text-xl font-semibold">Edit Data Voting</DialogTitle>
                <DialogDescription class="mt-1 text-sm text-neutral-700 dark:text-neutral-300">Perbarui data kandidat OSIS.</DialogDescription>
              </div>
            </DialogHeader>
            <div class="py-2">
              <VotingForm :form="editForm" :on-submit="submitEdit" variant="plain" :show-actions="false" :editing-item="editingItem" />
            </div>
            <DialogFooter>
              <div class="flex w-full justify-end gap-2">
                <Button variant="ghost" @click="editOpen = false">Batal</Button>
                <Button variant="default" @click="submitEdit">Simpan</Button>
              </div>
            </DialogFooter>
          </DialogContent>
        </Dialog>

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
            class="overflow-hidden group p-0"
          >
                <CardHeader class="relative p-0">
                  <img
                    v-if="item.foto"
                    :src="photoUrl(item.foto)"
                    alt="Foto Kandidat"
                    class="h-40 w-full object-contain"
                  />
                  <div v-else class="h-40 w-full bg-muted" />
                  <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity" />
                  <div class="absolute top-2 right-2 flex gap-2">
                    <Button size="sm" variant="outline" @click="openEdit(item)">Edit</Button>
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
  </AppShell>
</template>