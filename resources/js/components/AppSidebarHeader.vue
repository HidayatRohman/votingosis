<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItemType } from '@/types';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItemType[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);

const page = usePage();
const isDashboard = computed(() => (page?.url ?? '').startsWith('/dashboard'));
const appLogoUrl = computed(() => page.props.appLogoUrl as string | null);
</script>

<template>
    <header
        :class="[
            'relative flex h-16 shrink-0 items-center gap-2 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4',
            isDashboard
                ? 'bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-700 text-white shadow-sm border-none'
                : 'border-b border-sidebar-border/70'
        ]"
    >
        <div class="flex items-center gap-2">
            <SidebarTrigger :class="['-ml-1', isDashboard ? 'text-white hover:text-white/90' : '']" />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" :variant="isDashboard ? 'inverted' : 'default'" />
            </template>
        </div>
        <!-- Logo kecil di kanan untuk tampilan mobile -->
        <div class="ml-auto md:hidden">
            <Link :href="dashboard()" class="flex items-center">
                <img v-if="appLogoUrl" :src="appLogoUrl" alt="Logo" class="h-6 w-auto object-contain" />
                <AppLogoIcon v-else :class="['size-6', isDashboard ? 'text-white' : 'text-foreground']" />
            </Link>
        </div>
    </header>
</template>
