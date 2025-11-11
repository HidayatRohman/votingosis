<script setup lang="ts">
interface Props {
    title: string;
    description?: string;
    contrast?: boolean;
}

const props = defineProps<Props>();
</script>

<template>
    <header>
        <h3 :class="['mb-0.5', props.contrast ? 'text-xl font-semibold text-white' : 'text-base font-medium']">{{ title }}</h3>
        <p v-if="description" :class="[props.contrast ? 'text-sm text-white/80' : 'text-sm text-muted-foreground']">
            {{ description }}
        </p>
    </header>
</template>
