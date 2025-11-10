<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import type { BreadcrumbItem } from '@/types';

defineProps<{ items: { data: any[]; links: any[] } }>();

const breadcrumbItems: BreadcrumbItem[] = [
  { title: 'Users', href: '/users' },
];
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbItems">
    <Head title="Users" />

    <div class="px-4 py-6">
      <div class="flex items-center justify-between">
        <HeadingSmall title="Users" description="Kelola pengguna aplikasi" />
        <Link href="/users/create" class="inline-flex items-center rounded-md bg-neutral-900 px-3 py-2 text-sm text-white hover:bg-neutral-800 dark:bg-neutral-100 dark:text-neutral-900">Tambah User</Link>
      </div>

      <div class="mt-6 overflow-x-auto">
        <table class="min-w-full text-left text-sm">
          <thead>
            <tr class="border-b">
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
                  <Link :href="`/users/${u.id}/edit`" class="rounded-md border px-2 py-1 text-xs">Edit</Link>
                  <Link :href="`/users/${u.id}`" method="delete" as="button" class="rounded-md border px-2 py-1 text-xs" preserve-scroll>Hapus</Link>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AppLayout>
  </template>