<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '@/components/ui/card';
import { home } from '@/routes';
import { Link } from '@inertiajs/vue3';

defineProps<{
  title?: string;
  description?: string;
}>();
</script>

<template>
  <div class="relative flex min-h-svh flex-col items-center justify-center bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-700 p-6 text-white overflow-hidden">
    <!-- Decorative background circles -->
    <div class="pointer-events-none absolute -top-24 -left-24 h-64 w-64 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 opacity-40 blur-sm" />
    <div class="pointer-events-none absolute top-1/3 -left-12 h-40 w-40 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 opacity-40 blur-sm" />
    <div class="pointer-events-none absolute -bottom-28 -right-28 h-72 w-72 rounded-full bg-gradient-to-br from-indigo-600 to-blue-700 opacity-35 blur-sm" />
    <div class="pointer-events-none absolute bottom-10 right-1/3 h-20 w-20 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 opacity-30 blur-sm" />

    <div class="relative z-10 w-full max-w-xl sm:max-w-2xl lg:max-w-4xl">
      <div class="mb-6 flex items-center justify-center gap-2">
        <Link :href="home()" class="flex items-center gap-2">
          <AppLogoIcon class="size-8 text-white" />
          <span class="text-lg font-semibold tracking-tight">e-voting.</span>
        </Link>
      </div>

      <Card class="rounded-xl border-none bg-white/95 text-neutral-900 shadow-xl backdrop-blur">
        <CardHeader v-if="title || description" class="px-8 pt-8 pb-0 text-center">
          <CardTitle class="text-xl font-semibold text-neutral-900">{{ title }}</CardTitle>
          <CardDescription class="text-neutral-600">{{ description }}</CardDescription>
        </CardHeader>
        <CardContent class="px-8 py-8">
          <slot />
        </CardContent>
      </Card>
    </div>
  </div>
</template>