<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import Card from '@/components/ui/card/Card.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import CardFooter from '@/components/ui/card/CardFooter.vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbItems: BreadcrumbItem[] = [
  { title: 'Users', href: '/users' },
  { title: 'Create', href: '/users/create' },
];

const form = useForm({
  name: '',
  email: '',
  password: '',
  role: 'user',
});

function submit() {
  form.post('/users', {
    onSuccess: () => {},
  });
}
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbItems">
    <Head title="Create User" />

    <div class="px-4 py-6">
      <Card class="max-w-xl overflow-hidden">
        <CardHeader class="p-3 bg-gradient-to-r from-sky-600 to-indigo-600 text-white">
          <HeadingSmall title="Tambah User" description="Buat akun pengguna baru" contrast />
        </CardHeader>
        <CardContent>
          <div class="mt-4 grid gap-4">
            <div class="grid gap-2">
              <Label for="name">Nama</Label>
              <Input id="name" v-model="form.name" type="text" />
              <p v-if="form.errors.name" class="text-sm text-red-600">{{ form.errors.name }}</p>
            </div>
            <div class="grid gap-2">
              <Label for="email">Email</Label>
              <Input id="email" v-model="form.email" type="email" />
              <p v-if="form.errors.email" class="text-sm text-red-600">{{ form.errors.email }}</p>
            </div>
            <div class="grid gap-2">
              <Label for="password">Password</Label>
              <Input id="password" v-model="form.password" type="password" />
              <p v-if="form.errors.password" class="text-sm text-red-600">{{ form.errors.password }}</p>
            </div>
            <div class="grid gap-2">
              <Label for="role">Role</Label>
              <select
                id="role"
                v-model="form.role"
                :aria-invalid="!!form.errors.role"
                class="file:text-foreground placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30 border-input h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive"
              >
                <option value="user">User</option>
                <option value="admin">Admin</option>
              </select>
              <p v-if="form.errors.role" class="text-sm text-red-600">{{ form.errors.role }}</p>
            </div>
          </div>
        </CardContent>
        <CardFooter class="pt-4">
          <div class="flex gap-3">
            <Button :disabled="form.processing" @click="submit">Simpan</Button>
            <Button variant="outline" as-child>
              <Link href="/users">Batal</Link>
            </Button>
          </div>
        </CardFooter>
      </Card>
    </div>
  </AppLayout>
  </template>