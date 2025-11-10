<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const props = defineProps<{ stats?: { items: { id: number; nama: string; votes_count: number }[]; totalVotes: number } }>();

const percent = (count: number) => {
    const total = props.stats?.totalVotes ?? 0;
    if (!total) return 0;
    return Math.round((count / total) * 100);
};

const palette = ['#3b82f6', '#ef4444', '#22c55e', '#f59e0b', '#a855f7', '#06b6d4', '#ec4899', '#64748b', '#6366f1', '#10b981'];

const chartSegments = computed(() => {
    const items = props.stats?.items ?? [];
    const total = props.stats?.totalVotes ?? 0;
    let acc = 0;
    return items.map((item, idx) => {
        const pct = total ? (item.votes_count / total) * 100 : 0;
        const start = acc;
        const end = acc + pct;
        acc = end;
        return {
            name: item.nama,
            count: item.votes_count,
            pct,
            start,
            end,
            color: palette[idx % palette.length],
        };
    });
});

const gradientStyle = computed(() => {
    const segs = chartSegments.value;
    const total = props.stats?.totalVotes ?? 0;
    if (!segs.length || !total) {
        return { background: 'conic-gradient(#e5e7eb 0% 100%)' };
    }
    const stops = segs.map((s) => `${s.color} ${s.start}% ${s.end}%`).join(', ');
    return { background: `conic-gradient(${stops})` };
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-4">
                <h2 class="text-lg font-semibold mb-2">Hasil Voting (Pie)</h2>
                <p class="text-sm text-muted-foreground mb-4">Total suara: {{ props.stats?.totalVotes ?? 0 }}</p>

                <div class="flex flex-col items-center md:flex-row md:items-start md:gap-8">
                    <div class="mx-auto h-64 w-64 rounded-full" :style="gradientStyle"></div>
                    <div class="mt-6 w-full md:mt-0 md:w-64">
                        <div v-for="seg in chartSegments" :key="seg.name" class="flex items-center justify-between py-1 text-sm">
                            <div class="flex items-center gap-2">
                                <span class="inline-block h-3 w-3 rounded-sm" :style="{ background: seg.color }"></span>
                                <span class="font-medium">{{ seg.name }}</span>
                            </div>
                            <span class="text-muted-foreground">{{ seg.count }} ({{ Math.round(seg.pct) }}%)</span>
                        </div>
                        <div v-if="(props.stats?.items?.length ?? 0) === 0" class="text-sm text-muted-foreground">Belum ada data voting.</div>
                    </div>
                </div>
            </div>

            <!-- Kartu daftar kandidat di bawah pie chart -->
            <div class="rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-4">
                <h2 class="text-lg font-semibold mb-2">Daftar Kandidat</h2>
                <div class="space-y-2">
                    <div v-for="item in props.stats?.items ?? []" :key="item.id" class="flex items-center justify-between text-sm">
                        <span class="font-medium">{{ item.nama }}</span>
                        <span class="text-muted-foreground">{{ item.votes_count }} suara</span>
                    </div>
                    <div v-if="(props.stats?.items?.length ?? 0) === 0" class="text-sm text-muted-foreground">Belum ada kandidat.</div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
