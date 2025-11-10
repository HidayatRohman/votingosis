<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBlueLayout from '@/layouts/auth/AuthBlueLayout.vue';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';
import { Form, Head } from '@inertiajs/vue3';
import { User, Lock, Eye, EyeOff } from 'lucide-vue-next';
import { ref } from 'vue';

defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
}>();

const showPassword = ref(false);
</script>

<template>
    <AuthBlueLayout>
        <Head title="Masuk" />

        <Form
            v-bind="store.form()"
            :reset-on-success="['password']"
            v-slot="{ errors, processing }"
        >
            <div class="grid gap-8 lg:grid-cols-2">
                <!-- Left welcome panel -->
                <section class="relative hidden overflow-hidden rounded-xl bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-700 p-8 text-white shadow lg:block">
                    <h2 class="text-2xl font-bold tracking-wide">WELCOME</h2>
                    <p class="mt-1 text-sm opacity-80">Sistem Informasi E‑Voting</p>
                    <p class="mt-4 max-w-sm text-[13px] leading-5 opacity-80">
                        Selamat datang di sistem e‑voting. Masuk untuk melanjutkan proses
                        pemilihan secara aman dan mudah.
                    </p>
                    <!-- Decorative circles -->
                    <div class="pointer-events-none absolute -bottom-10 -left-8 h-36 w-36 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 opacity-80" />
                    <div class="pointer-events-none absolute -bottom-8 left-28 h-32 w-32 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 opacity-70" />
                </section>

                <!-- Right form -->
                <section>
                    <div class="mb-4">
                        <h1 class="text-xl font-semibold text-neutral-900">Masuk</h1>
                        <p class="text-sm text-neutral-600">Gunakan email dan kata sandi Anda</p>
                    </div>

                    <div class="grid gap-6">
                        <div class="grid gap-2">
                            <Label for="email">Alamat Email</Label>
                            <div class="relative">
                                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400">
                                    <User class="size-4" />
                                </span>
                                <Input
                                    id="email"
                                    type="email"
                                    name="email"
                                    required
                                    autofocus
                                    :tabindex="1"
                                    autocomplete="email"
                                    placeholder="email@example.com"
                                    class="pl-9"
                                />
                            </div>
                            <InputError :message="errors.email" />
                        </div>

                        <div class="grid gap-2">
                            <div class="flex items-center justify-between">
                                <Label for="password">Kata Sandi</Label>
                                <TextLink
                                    v-if="canResetPassword"
                                    :href="request()"
                                    class="text-sm"
                                    :tabindex="5"
                                >
                                    Lupa kata sandi?
                                </TextLink>
                            </div>

                            <div class="relative">
                                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400">
                                    <Lock class="size-4" />
                                </span>
                                <Input
                                    id="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    name="password"
                                    required
                                    :tabindex="2"
                                    autocomplete="current-password"
                                    placeholder="Kata sandi"
                                    class="pr-10 pl-9"
                                />
                                <button
                                    type="button"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-500 hover:text-neutral-700"
                                    @click="showPassword = !showPassword"
                                    :tabindex="6"
                                    aria-label="Tampilkan kata sandi"
                                >
                                    <Eye v-if="!showPassword" class="size-4" />
                                    <EyeOff v-else class="size-4" />
                                </button>
                            </div>

                            <InputError :message="errors.password" />
                        </div>

                        <div class="flex items-center justify-between">
                            <Label for="remember" class="flex items-center space-x-3">
                                <Checkbox id="remember" name="remember" :tabindex="3" />
                                <span>Ingat saya</span>
                            </Label>
                        </div>

                        <Button
                            type="submit"
                            class="mt-2 w-full bg-blue-600 text-white hover:bg-blue-700"
                            :tabindex="4"
                            :disabled="processing"
                            data-test="login-button"
                        >
                            <Spinner v-if="processing" />
                            Masuk
                        </Button>

                        <!-- Or separator -->
                        <div class="flex items-center gap-3">
                            <span class="h-px flex-1 bg-neutral-200" />
                            <span class="text-xs text-neutral-500">atau</span>
                            <span class="h-px flex-1 bg-neutral-200" />
                        </div>

                        <Button type="button" variant="outline" class="w-full">
                            Masuk dengan yang lain
                        </Button>
                    </div>

                    <div class="mt-6 text-center text-sm text-muted-foreground" v-if="canRegister">
                        Belum punya akun?
                        <TextLink :href="register()" :tabindex="5">Daftar</TextLink>
                    </div>

                    <div v-if="status" class="mt-4 text-center text-sm font-medium text-green-600">
                        {{ status }}
                    </div>
                </section>
            </div>
        </Form>
    </AuthBlueLayout>
</template>
