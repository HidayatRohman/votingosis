<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import type { AppPageProps } from '@/types';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Trophy } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const props = defineProps<{ stats?: { items: { id: number; nama: string; votes_count: number; nip?: string; kelas?: string; jurusan?: string; foto?: string }[]; totalVotes: number }, hasVoted?: boolean, isVotingOpen?: boolean }>();

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

const photoUrl = (path?: string) => (path ? `/storage/${path}` : undefined);

// Countdown logic using shared votingSchedule
const page = usePage<AppPageProps>();
const schedule = computed(() => page.props.votingSchedule ?? { startAt: null, endAt: null });
const nowMs = ref(Date.now());
let timer: number | null = null;
onMounted(() => {
  timer = window.setInterval(() => {
    nowMs.value = Date.now();
  }, 1000);
});
onUnmounted(() => {
  if (timer) window.clearInterval(timer);
});

const timeTo = (target: string | null) => {
  if (!target) return null;
  const targetMs = new Date(target.replace(' ', 'T')).getTime();
  const diff = targetMs - nowMs.value;
  if (isNaN(targetMs)) return null;
  const sign = diff >= 0 ? 1 : -1;
  const abs = Math.abs(diff);
  const days = Math.floor(abs / (24 * 60 * 60 * 1000));
  const hours = Math.floor((abs % (24 * 60 * 60 * 1000)) / (60 * 60 * 1000));
  const minutes = Math.floor((abs % (60 * 60 * 1000)) / (60 * 1000));
  const seconds = Math.floor((abs % (60 * 1000)) / 1000);
  return { sign, days, hours, minutes, seconds };
};

const countdownState = computed(() => {
  const start = schedule.value.startAt;
  const end = schedule.value.endAt;
  if (!start || !end) return { state: 'unscheduled' as const };
  const now = nowMs.value;
  const startMs = new Date(start.replace(' ', 'T')).getTime();
  const endMs = new Date(end.replace(' ', 'T')).getTime();
  if (isNaN(startMs) || isNaN(endMs)) return { state: 'unscheduled' as const };
  if (now < startMs) return { state: 'before', remain: timeTo(start) };
  if (now > endMs) return { state: 'after' };
  return { state: 'during', remain: timeTo(end) };
});

// Format helper untuk dua digit
const pad2 = (n: number) => String(n ?? 0).padStart(2, '0');

// Hitung pemenang (kandidat dengan suara terbanyak) setelah voting berakhir
const winnerItem = computed(() => {
  const items = props.stats?.items ?? [];
  const total = props.stats?.totalVotes ?? 0;
  if (!items.length || !total) return null as any;
  return items.reduce((best: any, cur: any) => {
    if (!best) return cur;
    return cur.votes_count > best.votes_count ? cur : best;
  }, null as any);
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

            <!-- Countdown Voting di antara pie dan daftar kandidat -->
            <div class="rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-0 overflow-hidden">
              <!-- Header yang menarik dan rata tengah -->
              <div class="px-4 py-3 bg-gradient-to-r from-sky-600 to-indigo-600 text-white text-center">
                <h2 class="text-base md:text-lg font-semibold">Countdown Voting</h2>
                <p v-if="countdownState.state === 'before'" class="mt-1 text-xs md:text-sm text-white/90">Voting mulai dalam:</p>
                <p v-else-if="countdownState.state === 'during'" class="mt-1 text-xs md:text-sm text-white/90">Voting berakhir dalam:</p>
                <p v-else-if="countdownState.state === 'unscheduled'" class="mt-1 text-xs md:text-sm text-white/90">Jadwal voting belum diatur</p>
                <p v-else class="mt-1 text-xs md:text-sm text-white/90">Voting telah berakhir</p>
              </div>
              <div class="p-4">
                <template v-if="countdownState.state === 'unscheduled'">
                  <p class="text-sm text-muted-foreground text-center">Silakan atur jadwal di Settings → Countdown.</p>
                </template>
                <template v-else-if="countdownState.state === 'before'">
                  <div class="mt-1 flex flex-nowrap justify-center gap-3 md:gap-4">
                    <div class="flex flex-col items-center">
                      <div class="relative h-14 w-14 sm:h-16 sm:w-16 md:h-24 md:w-24 rounded-full border-2 border-dashed border-sky-500 flex items-center justify-center">
                        <span class="text-xl sm:text-2xl md:text-3xl font-semibold">{{ pad2(countdownState.remain?.days ?? 0) }}</span>
                      </div>
                      <span class="mt-1 text-[10px] sm:text-xs text-muted-foreground">Hari</span>
                    </div>
                    <div class="flex flex-col items-center">
                      <div class="relative h-14 w-14 sm:h-16 sm:w-16 md:h-24 md:w-24 rounded-full border-2 border-dashed border-indigo-500 flex items-center justify-center">
                        <span class="text-xl sm:text-2xl md:text-3xl font-semibold">{{ pad2(countdownState.remain?.hours ?? 0) }}</span>
                      </div>
                      <span class="mt-1 text-[10px] sm:text-xs text-muted-foreground">Jam</span>
                    </div>
                    <div class="flex flex-col items-center">
                      <div class="relative h-14 w-14 sm:h-16 sm:w-16 md:h-24 md:w-24 rounded-full border-2 border-dashed border-fuchsia-500 flex items-center justify-center">
                        <span class="text-xl sm:text-2xl md:text-3xl font-semibold">{{ pad2(countdownState.remain?.minutes ?? 0) }}</span>
                      </div>
                      <span class="mt-1 text-[10px] sm:text-xs text-muted-foreground">Menit</span>
                    </div>
                    <div class="flex flex-col items-center">
                      <div class="relative h-14 w-14 sm:h-16 sm:w-16 md:h-24 md:w-24 rounded-full border-2 border-dashed border-cyan-500 flex items-center justify-center">
                        <span class="text-xl sm:text-2xl md:text-3xl font-semibold">{{ pad2(countdownState.remain?.seconds ?? 0) }}</span>
                      </div>
                      <span class="mt-1 text-[10px] sm:text-xs text-muted-foreground">Detik</span>
                    </div>
                  </div>
                </template>
                <template v-else-if="countdownState.state === 'during'">
                  <div class="mt-1 flex flex-nowrap justify-center gap-3 md:gap-4">
                    <div class="flex flex-col items-center">
                      <div class="relative h-14 w-14 sm:h-16 sm:w-16 md:h-24 md:w-24 rounded-full border-2 border-dashed border-sky-500 flex items-center justify-center">
                        <span class="text-xl sm:text-2xl md:text-3xl font-semibold">{{ pad2(countdownState.remain?.days ?? 0) }}</span>
                      </div>
                      <span class="mt-1 text-[10px] sm:text-xs text-muted-foreground">Hari</span>
                    </div>
                    <div class="flex flex-col items-center">
                      <div class="relative h-14 w-14 sm:h-16 sm:w-16 md:h-24 md:w-24 rounded-full border-2 border-dashed border-indigo-500 flex items-center justify-center">
                        <span class="text-xl sm:text-2xl md:text-3xl font-semibold">{{ pad2(countdownState.remain?.hours ?? 0) }}</span>
                      </div>
                      <span class="mt-1 text-[10px] sm:text-xs text-muted-foreground">Jam</span>
                    </div>
                    <div class="flex flex-col items-center">
                      <div class="relative h-14 w-14 sm:h-16 sm:w-16 md:h-24 md:w-24 rounded-full border-2 border-dashed border-fuchsia-500 flex items-center justify-center">
                        <span class="text-xl sm:text-2xl md:text-3xl font-semibold">{{ pad2(countdownState.remain?.minutes ?? 0) }}</span>
                      </div>
                      <span class="mt-1 text-[10px] sm:text-xs text-muted-foreground">Menit</span>
                    </div>
                    <div class="flex flex-col items-center">
                      <div class="relative h-14 w-14 sm:h-16 sm:w-16 md:h-24 md:w-24 rounded-full border-2 border-dashed border-cyan-500 flex items-center justify-center">
                        <span class="text-xl sm:text-2xl md:text-3xl font-semibold">{{ pad2(countdownState.remain?.seconds ?? 0) }}</span>
                      </div>
                      <span class="mt-1 text-[10px] sm:text-xs text-muted-foreground">Detik</span>
                    </div>
                  </div>
                </template>
                <template v-else>
                    <div v-if="winnerItem" class="mt-3">
                      <Card class="overflow-hidden">
                        <CardHeader class="p-3 bg-gradient-to-r from-sky-600 to-indigo-600 text-white flex items-center justify-between">
                          <h3 class="text-sm md:text-base">Pemenang</h3>
                          <Badge class="bg-amber-500 text-amber-900 border-transparent">
                            <Trophy />
                            Juara
                          </Badge>
                        </CardHeader>
                        <CardContent class="p-4 flex flex-col md:flex-row items-center md:items-start gap-4">
                          <img
                            v-if="winnerItem.foto"
                            :src="photoUrl(winnerItem.foto)"
                            alt="Foto Pemenang"
                            class="w-full h-40 object-contain md:object-cover rounded bg-muted md:bg-transparent md:h-16 md:w-16"
                          />
                          <div v-else class="w-full h-40 bg-muted rounded md:h-16 md:w-16" />
                          <div class="w-full md:flex-1 text-center md:text-left">
                            <div class="text-base md:text-sm font-semibold">{{ winnerItem.nama }}</div>
                            <div class="text-sm md:text-xs text-muted-foreground">Total Suara: {{ winnerItem.votes_count }}</div>
                          </div>
                        </CardContent>
                      </Card>
                    </div>
                    <div v-else class="mt-1 text-sm text-muted-foreground">Belum ada suara yang masuk.</div>
                </template>
              </div>
            </div>

            <!-- Kartu daftar kandidat di bawah pie chart -->
            <div class="rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-4">
                <h2 class="text-lg font-semibold mb-2">Daftar Kandidat</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                  <Card v-for="item in props.stats?.items ?? []" :key="item.id" class="overflow-hidden">
                    <CardHeader class="flex items-center justify-center text-center">
                      <img
                        v-if="item.foto"
                        :src="photoUrl(item.foto)"
                        alt="Foto Kandidat"
                        class="h-40 w-40 object-cover rounded mx-auto"
                      />
                      <div v-else class="h-40 w-40 bg-muted rounded mx-auto" />
                    </CardHeader>
                    <CardContent class="space-y-1 text-sm">
                      <div class="grid grid-cols-3">
                        <span class="font-medium">NIS</span>
                        <span class="col-span-2">{{ item.nip }}</span>
                      </div>
                      <div class="grid grid-cols-3">
                        <span class="font-medium">Nama</span>
                        <span class="col-span-2">{{ item.nama }}</span>
                      </div>
                      <div class="grid grid-cols-3">
                        <span class="font-medium">Kelas</span>
                        <span class="col-span-2">{{ item.kelas }}</span>
                      </div>
                      <div class="grid grid-cols-3">
                        <span class="font-medium">Jurusan</span>
                        <span class="col-span-2">{{ item.jurusan }}</span>
                      </div>
                      <div class="pt-3 flex justify-center">
                        <span class="text-sm font-medium">Total Suara: {{ item.votes_count }}</span>
                      </div>
                      
                    </CardContent>
                  </Card>
                </div>
                <div v-if="(props.stats?.items?.length ?? 0) === 0" class="mt-2 text-sm text-muted-foreground">Belum ada kandidat.</div>
                <div v-if="props.isVotingOpen && !props.hasVoted" class="mt-4 flex justify-center">
                  <Button as-child>
                    <Link href="/candidates">Voting Sekarang</Link>
                  </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
