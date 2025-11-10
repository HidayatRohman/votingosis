<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import type { AppPageProps, BreadcrumbItem } from '@/types';
import { edit, update } from '@/routes/countdown';

const page = usePage<AppPageProps>();
const schedule = page.props.votingSchedule ?? { startAt: null, endAt: null };

const breadcrumbItems: BreadcrumbItem[] = [
  { title: 'Countdown settings', href: edit().url },
];

const form = useForm({
  voting_start_at: schedule.startAt ?? '',
  voting_end_at: schedule.endAt ?? '',
});

function submit() {
  form.post(update.form.put().action, {
    onSuccess: () => {},
  });
}
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbItems">
    <Head title="Countdown settings" />

    <SettingsLayout>
      <div class="space-y-6">
        <HeadingSmall
          title="Countdown settings"
          description="Atur tanggal mulai dan berakhir voting"
        />

        <div class="grid gap-6">
          <div class="grid gap-2">
            <Label for="voting_start_at">Tanggal mulai voting</Label>
            <Input
              id="voting_start_at"
              type="datetime-local"
              v-model="form.voting_start_at"
            />
            <p v-if="form.errors.voting_start_at" class="text-sm text-red-600">
              {{ form.errors.voting_start_at }}
            </p>
          </div>

          <div class="grid gap-2">
            <Label for="voting_end_at">Tanggal berakhir voting</Label>
            <Input
              id="voting_end_at"
              type="datetime-local"
              v-model="form.voting_end_at"
            />
            <p v-if="form.errors.voting_end_at" class="text-sm text-red-600">
              {{ form.errors.voting_end_at }}
            </p>
          </div>

          <div class="flex gap-3">
            <Button :disabled="form.processing" @click="submit">Simpan</Button>
            <Button variant="outline" as-child>
              <Link :href="edit().url">Batal</Link>
            </Button>
          </div>
        </div>
      </div>
    </SettingsLayout>
  </AppLayout>
  </template>