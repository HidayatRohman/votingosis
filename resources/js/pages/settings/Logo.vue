<script setup lang="ts">
import { Head, Form, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { type BreadcrumbItem } from '@/types';
import { edit as editLogo } from '@/routes/logo';
import { update as updateLogo } from '@/routes/logo';

const breadcrumbItems: BreadcrumbItem[] = [
    { title: 'Pengaturan Logo', href: editLogo().url },
];

const page = usePage();
const appLogoUrl = page.props.appLogoUrl as string | null;
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Pengaturan Logo" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <div class="rounded-lg bg-gradient-to-r from-sky-600 to-indigo-600 p-4 text-white">
                    <HeadingSmall
                        :contrast="true"
                        title="Logo aplikasi"
                        description="Upload logo untuk ditampilkan di header dan sidebar"
                    />
                </div>

                <Form v-bind="updateLogo.form()" enctype="multipart/form-data" class="space-y-4 rounded-lg border bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-900" v-slot="{ processing }">
                    <div class="grid gap-2">
                        <Label for="logo" class="text-slate-700 dark:text-slate-200">Pilih file logo</Label>
                        <div class="relative flex items-center rounded-md border px-3 py-2 focus-within:ring-2 focus-within:ring-blue-600 dark:border-neutral-700">
                            <Input id="logo" name="logo" type="file" accept="image/*" class="flex-1 border-0 bg-transparent px-0 pr-10 focus-visible:ring-0" />
                            <svg class="pointer-events-none absolute right-3 h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M4 17V7a2 2 0 0 1 2-2h3l2 2h5a2 2 0 0 1 2 2v8" />
                                <circle cx="12" cy="13" r="3" />
                            </svg>
                        </div>
                    </div>

                    <div v-if="appLogoUrl" class="grid gap-2">
                        <Label>Pratinjau saat ini</Label>
                        <img :src="appLogoUrl" alt="Logo saat ini" class="h-12 w-auto object-contain border rounded-md p-2" />
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="processing">Simpan Logo</Button>
                    </div>
                </Form>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>