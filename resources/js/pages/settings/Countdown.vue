<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
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

function toDateTimeLocal(value: string | null): string {
  if (!value) return '';
  // Normalize 'YYYY-MM-DD HH:mm:ss' or ISO to 'YYYY-MM-DDTHH:mm'
  const iso = value.replace(' ', 'T');
  const m = iso.match(/^(\d{4}-\d{2}-\d{2})[T ](\d{2}:\d{2})/);
  return m ? `${m[1]}T${m[2]}` : iso;
}

const breadcrumbItems: BreadcrumbItem[] = [
  { title: 'Countdown settings', href: edit().url },
];

const form = useForm({
  voting_start_at: toDateTimeLocal(schedule.startAt),
  voting_end_at: toDateTimeLocal(schedule.endAt),
});

// Form untuk reset voting
const resetForm = useForm({});

const startRef = ref<HTMLInputElement | null>(null);
const endRef = ref<HTMLInputElement | null>(null);

function openStartPicker() {
  const el = startRef.value;
  if (!el) return;
  el.focus();
  // showPicker is supported in modern browsers
  if (typeof (el as any).showPicker === 'function') {
    (el as any).showPicker();
  }
}

function openEndPicker() {
  const el = endRef.value;
  if (!el) return;
  el.focus();
  if (typeof (el as any).showPicker === 'function') {
    (el as any).showPicker();
  }
}

function submit() {
  form.post(update.form.put().action, {
    onSuccess: () => {},
  });
}

function resetVoting() {
  if (!confirm('Reset voting? Semua hasil akan dihapus dan semua user bisa vote lagi.')) {
    return;
  }
  resetForm.post('/settings/reset-voting', {
    preserveScroll: true,
    onSuccess: () => {
      // opsional: bisa tambahkan feedback lain jika diperlukan
    },
  });
}
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbItems">
    <Head title="Countdown settings" />

    <SettingsLayout>
      <div class="space-y-6">
        <div class="rounded-lg bg-gradient-to-r from-sky-600 to-indigo-600 p-4 text-white">
          <HeadingSmall
            :contrast="true"
            title="Countdown settings"
            description="Atur tanggal mulai dan berakhir voting"
          />
        </div>

        <div class="rounded-lg border bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
          <div class="grid gap-6">
            <!-- Start datetime -->
            <div class="grid gap-2">
              <Label for="voting_start_at" class="text-slate-700 dark:text-slate-200">Tanggal mulai voting</Label>
              <div class="relative flex items-center rounded-md border px-3 py-2 focus-within:ring-2 focus-within:ring-blue-600 dark:border-neutral-700">
                <Input
                  id="voting_start_at"
                  ref="startRef"
                  type="datetime-local"
                  step="60"
                  v-model="form.voting_start_at"
                  @click="openStartPicker"
                  class="flex-1 border-0 bg-transparent px-0 pr-10 focus-visible:ring-0"
                />
                <!-- Decorative calendar icon -->
                <svg class="pointer-events-none absolute right-3 h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                  <line x1="16" y1="2" x2="16" y2="6"></line>
                  <line x1="8" y1="2" x2="8" y2="6"></line>
                  <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
              </div>
              <p v-if="form.errors.voting_start_at" class="text-sm text-red-600">
                {{ form.errors.voting_start_at }}
              </p>
            </div>

            <!-- End datetime -->
            <div class="grid gap-2">
              <Label for="voting_end_at" class="text-slate-700 dark:text-slate-200">Tanggal berakhir voting</Label>
              <div class="relative flex items-center rounded-md border px-3 py-2 focus-within:ring-2 focus-within:ring-indigo-600 dark:border-neutral-700">
                <Input
                  id="voting_end_at"
                  ref="endRef"
                  type="datetime-local"
                  step="60"
                  v-model="form.voting_end_at"
                  @click="openEndPicker"
                  class="flex-1 border-0 bg-transparent px-0 pr-10 focus-visible:ring-0"
                />
                <!-- Decorative calendar icon -->
                <svg class="pointer-events-none absolute right-3 h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                  <line x1="16" y1="2" x2="16" y2="6"></line>
                  <line x1="8" y1="2" x2="8" y2="6"></line>
                  <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
              </div>
              <p v-if="form.errors.voting_end_at" class="text-sm text-red-600">
                {{ form.errors.voting_end_at }}
              </p>
            </div>

            <div class="flex gap-3">
              <Button :disabled="form.processing" @click="submit">Simpan</Button>
              <Button variant="outline" as-child>
                <Link :href="edit().url">Batal</Link>
              </Button>
              <Button variant="destructive" :disabled="resetForm.processing" @click="resetVoting">
                Reset Voting
              </Button>
            </div>
          </div>
        </div>
      </div>
    </SettingsLayout>
  </AppLayout>
  </template>