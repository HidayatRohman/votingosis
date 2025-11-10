<script setup lang="ts">
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from '@/components/ui/breadcrumb';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

interface BreadcrumbItemType {
    title: string;
    href?: string;
}

const props = withDefaults(defineProps<{
    breadcrumbs: BreadcrumbItemType[];
    variant?: 'default' | 'inverted';
}>(), {
    variant: 'default',
});

const inverted = computed(() => props.variant === 'inverted');
const listClass = computed(() => (inverted.value ? 'text-white/85' : undefined));
const linkClass = computed(() => (inverted.value ? 'text-white/90 hover:text-white' : undefined));
const pageClass = computed(() => (inverted.value ? 'text-white' : undefined));
const sepClass = computed(() => (inverted.value ? 'text-white/70' : undefined));
</script>

<template>
    <Breadcrumb>
        <BreadcrumbList :class="listClass">
            <template v-for="(item, index) in breadcrumbs" :key="index">
                <BreadcrumbItem>
                    <template v-if="index === breadcrumbs.length - 1">
                        <BreadcrumbPage :class="pageClass">{{ item.title }}</BreadcrumbPage>
                    </template>
                    <template v-else>
                        <BreadcrumbLink as-child :class="linkClass">
                            <Link :href="item.href ?? '#'">{{
                                item.title
                            }}</Link>
                        </BreadcrumbLink>
                    </template>
                </BreadcrumbItem>
                <BreadcrumbSeparator v-if="index !== breadcrumbs.length - 1" :class="sepClass" />
            </template>
        </BreadcrumbList>
    </Breadcrumb>
</template>
