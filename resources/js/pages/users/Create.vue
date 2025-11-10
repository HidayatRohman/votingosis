<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import type { BreadcrumbItem } from '@/types';

const breadcrumbItems: BreadcrumbItem[] = [
  { title: 'Users', href: '/users' },
  { title: 'Create', href: '/users/create' },
];

const form = useForm({
  name: '',
  email: '',
  password: '',
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
      <HeadingSmall title="Tambah User" description="Buat akun pengguna baru" />
      <div class="mt-6 grid max-w-xl gap-4">
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
        <div class="flex gap-3">
          <Button :disabled="form.processing" @click="submit">Simpan</Button>
          <Button variant="outline" as-child>
            <Link href="/users">Batal</Link>
          </Button>
        </div>
      </div>
    </div>
  </AppLayout>
  </template>