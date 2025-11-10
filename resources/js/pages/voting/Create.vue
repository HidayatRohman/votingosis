<script setup lang="ts">
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import AppContent from '@/components/AppContent.vue';
import VotingForm from '@/components/voting/VotingForm.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  nis: '',
  nama: '',
  kelas: '',
  jurusan: '',
  foto: null as any,
  visi: '',
  misi: '',
});

const submit = () => {
  form.post('/voting');
};
</script>

<template>
  <AppShell variant="sidebar">
    <AppSidebar />
    <AppContent variant="sidebar" class="p-4">
        <AppSidebarHeader />
        <h1 class="text-xl font-semibold mb-4">Tambah Data Voting</h1>
        <VotingForm :form="form" :on-submit="submit" />
    </AppContent>
  </AppShell>
</template>