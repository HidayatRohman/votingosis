<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import Card from '@/components/ui/card/Card.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import CardAction from '@/components/ui/card/CardAction.vue';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription, DialogFooter } from '@/components/ui/dialog';
import { Pencil, Trash2, UserPlus } from 'lucide-vue-next';
import type { BreadcrumbItem } from '@/types';

defineProps<{ items: { data: any[]; links: any[] } }>();

const breadcrumbItems: BreadcrumbItem[] = [
  { title: 'Users', href: '/users' },
];

const confirmOpen = ref(false);
const selectedId = ref<number | null>(null);
const formDelete = useForm({});

function askDelete(id: number) {
  selectedId.value = id;
  confirmOpen.value = true;
}

function performDelete() {
  if (!selectedId.value) return;
  formDelete.delete(`/users/${selectedId.value}` , {
    preserveScroll: true,
    onFinish: () => {
      confirmOpen.value = false;
      selectedId.value = null;
    },
  });
}
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbItems">
    <Head title="Users" />

    <div class="px-4 py-6">
      <Card>
        <CardHeader class="border-b pb-4">
          <HeadingSmall title="Users" description="Kelola pengguna aplikasi" />
          <CardAction>
            <Button as-child>
              <Link href="/users/create">
                <UserPlus class="mr-2 h-4 w-4" />
                Tambah User
              </Link>
            </Button>
          </CardAction>
        </CardHeader>
        <CardContent>
          <div class="mt-4 overflow-x-auto">
            <table class="min-w-full text-left text-sm">
              <thead>
                <tr class="border-b bg-muted/30">
                  <th class="px-3 py-2">Nama</th>
                  <th class="px-3 py-2">Email</th>
                  <th class="px-3 py-2">Dibuat</th>
                  <th class="px-3 py-2 text-right">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="u in items.data" :key="u.id" class="border-b hover:bg-neutral-50 dark:hover:bg-neutral-900/40">
                  <td class="px-3 py-2">{{ u.name }}</td>
                  <td class="px-3 py-2">{{ u.email }}</td>
                  <td class="px-3 py-2">{{ new Date(u.created_at).toLocaleString() }}</td>
                  <td class="px-3 py-2">
                    <div class="flex justify-end gap-2">
                      <Button variant="outline" size="sm" as-child>
                        <Link :href="`/users/${u.id}/edit`">
                          <Pencil class="mr-1 h-3 w-3" />
                          Edit
                        </Link>
                      </Button>
                      <Button variant="outline" size="sm" class="text-red-600 border-red-600 hover:bg-red-50" @click="askDelete(u.id)">
                        <Trash2 class="mr-1 h-3 w-3" />
                        Hapus
                      </Button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </CardContent>
      </Card>

      <Dialog v-model:open="confirmOpen">
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Hapus User?</DialogTitle>
            <DialogDescription>
              Tindakan ini tidak dapat dibatalkan. User akan dihapus permanen.
            </DialogDescription>
          </DialogHeader>
          <DialogFooter>
            <Button variant="outline" @click="confirmOpen = false">Batal</Button>
            <Button class="bg-red-600 hover:bg-red-700" :disabled="formDelete.processing" @click="performDelete">Hapus</Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </div>
  </AppLayout>
  </template>