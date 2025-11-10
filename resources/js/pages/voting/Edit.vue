<script setup lang="ts">
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import AppContent from '@/components/AppContent.vue';
import VotingForm from '@/components/voting/VotingForm.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{ item: any }>();

const form = useForm({
  nis: props.item.nip,
  nama: props.item.nama,
  kelas: props.item.kelas,
  jurusan: props.item.jurusan,
  foto: null as any,
  visi: props.item.visi,
  misi: props.item.misi,
});

const submit = () => {
  form.post(`/voting/${props.item.id}`, {
    forceFormData: true,
    onFinish: () => form.reset('foto'),
    preserveScroll: true,
    data: form.data(),
    _method: 'put',
  } as any);
};
</script>

<template>
  <AppShell variant="sidebar">
    <AppSidebar />
    <div class="flex flex-1">
      <AppContent class="p-4">
        <AppSidebarHeader />
        <h1 class="text-xl font-semibold mb-4">Edit Data Voting</h1>
        <VotingForm :form="form" :on-submit="submit" :editing-item="props.item" />
      </AppContent>
    </div>
  </AppShell>
</template>