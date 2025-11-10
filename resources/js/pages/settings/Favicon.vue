<script setup lang="ts">
import { Head, Form, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { type BreadcrumbItem } from '@/types';
import { edit as editFavicon } from '@/routes/favicon';
import { update as updateFavicon } from '@/routes/favicon';

const breadcrumbItems: BreadcrumbItem[] = [
    { title: 'Pengaturan Favicon', href: editFavicon().url },
];

const page = usePage();
const appFaviconUrl = page.props.appFaviconUrl as string | null;
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Pengaturan Favicon" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall
                    title="Favicon aplikasi"
                    description="Upload favicon (.ico, .png, .svg) untuk tab browser"
                />

                <Form v-bind="updateFavicon.form()" enctype="multipart/form-data" class="space-y-4" v-slot="{ processing }">
                    <div class="grid gap-2">
                        <Label for="favicon">Pilih file favicon</Label>
                        <Input id="favicon" name="favicon" type="file" accept=".ico,image/png,image/svg+xml,image/webp" />
                    </div>

                    <div v-if="appFaviconUrl" class="grid gap-2">
                        <Label>Pratinjau saat ini</Label>
                        <img :src="appFaviconUrl" alt="Favicon saat ini" class="h-12 w-12 object-contain border rounded-md p-2" />
                        <p class="text-xs text-muted-foreground">Catatan: Pratinjau tampilan tab browser dapat berbeda.</p>
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="processing">Simpan Favicon</Button>
                    </div>
                </Form>
            </div>
        </SettingsLayout>
    </AppLayout>
    </template>