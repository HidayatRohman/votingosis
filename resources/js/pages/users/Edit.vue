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

const props = defineProps<{ item: { id: number; name: string; email: string } }>();

const breadcrumbItems: BreadcrumbItem[] = [
  { title: 'Users', href: '/users' },
  { title: 'Edit', href: '/users' },
];

const form = useForm({
  name: '',
  email: '',
  password: '',
});

// Prefill dari props.item
form.name = props.item?.name ?? '';
form.email = props.item?.email ?? '';

function submit(id: number) {
  form.put(`/users/${id}`, {
    onSuccess: () => {},
  });
}
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbItems">
    <Head title="Edit User" />

    <div class="px-4 py-6">
      <Card class="max-w-xl">
        <CardHeader class="border-b pb-4">
          <HeadingSmall title="Edit User" description="Perbarui data pengguna" />
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
              <Label for="password">Password (opsional)</Label>
              <Input id="password" v-model="form.password" type="password" placeholder="Kosongkan jika tidak diubah" />
              <p v-if="form.errors.password" class="text-sm text-red-600">{{ form.errors.password }}</p>
            </div>
          </div>
        </CardContent>
        <CardFooter class="pt-4">
          <div class="flex gap-3">
            <Button :disabled="form.processing" @click="submit(item.id)">Simpan</Button>
            <Button variant="outline" as-child>
              <Link href="/users">Batal</Link>
            </Button>
          </div>
        </CardFooter>
      </Card>
    </div>
  </AppLayout>
  </template>