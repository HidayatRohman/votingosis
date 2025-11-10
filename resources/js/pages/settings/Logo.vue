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
                <HeadingSmall
                    title="Logo aplikasi"
                    description="Upload logo untuk ditampilkan di header dan sidebar"
                />

                <Form v-bind="updateLogo.form()" enctype="multipart/form-data" class="space-y-4" v-slot="{ processing }">
                    <div class="grid gap-2">
                        <Label for="logo">Pilih file logo</Label>
                        <Input id="logo" name="logo" type="file" accept="image/*" />
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