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
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Pencil, Trash2, UserPlus } from 'lucide-vue-next';
import type { BreadcrumbItem } from '@/types';

defineProps<{ items: { data: any[]; links: any[] } }>();

const breadcrumbItems: BreadcrumbItem[] = [
  { title: 'Users', href: '/users' },
];

const confirmOpen = ref(false);
const createOpen = ref(false);
const selectedId = ref<number | null>(null);
const formDelete = useForm({});
const formCreate = useForm({
  name: '',
  email: '',
  password: '',
  role: 'user',
});

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

function submitCreate() {
  formCreate.post('/users', {
    onSuccess: () => {
      createOpen.value = false;
      formCreate.reset();
    },
  });
}
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbItems">
    <Head title="Users" />

    <div class="px-4 py-6">
      <Card class="overflow-hidden">
        <CardHeader class="p-3 bg-gradient-to-r from-sky-600 to-indigo-600 text-white">
          <HeadingSmall title="Users" description="Kelola pengguna aplikasi" contrast />
          <CardAction>
            <Button @click="createOpen = true">
              <UserPlus class="mr-2 h-4 w-4" />
              Tambah User
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
                  <th class="px-3 py-2">Role</th>
                  <th class="px-3 py-2">Dibuat</th>
                  <th class="px-3 py-2 text-right">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="u in items.data" :key="u.id" class="border-b hover:bg-neutral-50 dark:hover:bg-neutral-900/40">
                  <td class="px-3 py-2">{{ u.name }}</td>
                  <td class="px-3 py-2">{{ u.email }}</td>
                  <td class="px-3 py-2">{{ u.role ?? 'user' }}</td>
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

      <Dialog v-model:open="createOpen">
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Tambah User</DialogTitle>
            <DialogDescription>Masukkan data pengguna baru.</DialogDescription>
          </DialogHeader>
          <div class="grid gap-4">
            <div class="grid gap-2">
              <Label for="name">Nama</Label>
              <Input id="name" v-model="formCreate.name" type="text" />
              <p v-if="formCreate.errors.name" class="text-sm text-red-600">{{ formCreate.errors.name }}</p>
            </div>
            <div class="grid gap-2">
              <Label for="email">Email</Label>
              <Input id="email" v-model="formCreate.email" type="email" />
              <p v-if="formCreate.errors.email" class="text-sm text-red-600">{{ formCreate.errors.email }}</p>
            </div>
            <div class="grid gap-2">
              <Label for="password">Password</Label>
              <Input id="password" v-model="formCreate.password" type="password" />
              <p v-if="formCreate.errors.password" class="text-sm text-red-600">{{ formCreate.errors.password }}</p>
            </div>
            <div class="grid gap-2">
              <Label for="role">Role</Label>
              <select
                id="role"
                v-model="formCreate.role"
                :aria-invalid="!!formCreate.errors.role"
                class="file:text-foreground placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30 border-input h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive"
              >
                <option value="user">User</option>
                <option value="admin">Admin</option>
              </select>
              <p v-if="formCreate.errors.role" class="text-sm text-red-600">{{ formCreate.errors.role }}</p>
            </div>
          </div>
          <DialogFooter>
            <Button variant="outline" @click="createOpen = false">Batal</Button>
            <Button :disabled="formCreate.processing" @click="submitCreate">Simpan</Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </div>
  </AppLayout>
  </template>