<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBlueLayout from '@/layouts/auth/AuthBlueLayout.vue';
import { login } from '@/routes';
import { store } from '@/routes/register';
import { Form, Head } from '@inertiajs/vue3';
import { User, Mail, Lock, Eye, EyeOff } from 'lucide-vue-next';
import { ref } from 'vue';

const showPassword = ref(false);
const showConfirm = ref(false);
</script>

<template>
    <AuthBlueLayout>
        <Head title="Daftar" />

        <Form
            v-bind="store.form()"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
        >
            <div class="grid gap-8 lg:grid-cols-2">
                <!-- Left welcome panel -->
                <section class="relative hidden overflow-hidden rounded-xl bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-700 p-8 text-white shadow lg:block">
                    <h2 class="text-2xl font-bold tracking-wide">WELCOME</h2>
                    <p class="mt-1 text-sm opacity-80">Buat akun E‑Voting Anda</p>
                    <p class="mt-4 max-w-sm text-[13px] leading-5 opacity-80">
                        Lengkapi data di kanan untuk membuat akun dan
                        mulai berpartisipasi dalam pemilihan secara online.
                    </p>
                    <div class="pointer-events-none absolute -bottom-10 -left-8 h-36 w-36 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 opacity-80" />
                    <div class="pointer-events-none absolute -bottom-8 left-28 h-32 w-32 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 opacity-70" />
                </section>

                <!-- Right form -->
                <section>
                    <div class="mb-4">
                        <h1 class="text-xl font-semibold text-neutral-900">Daftar</h1>
                        <p class="text-sm text-neutral-600">Masukkan data diri Anda</p>
                    </div>

                    <div class="grid gap-6">
                        <div class="grid gap-2">
                            <Label for="name">Nama Lengkap</Label>
                            <div class="relative">
                                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400">
                                    <User class="size-4" />
                                </span>
                                <Input
                                    id="name"
                                    type="text"
                                    required
                                    autofocus
                                    :tabindex="1"
                                    autocomplete="name"
                                    name="name"
                                    placeholder="Nama lengkap"
                                    class="pl-9"
                                />
                            </div>
                            <InputError :message="errors.name" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="email">Alamat Email</Label>
                            <div class="relative">
                                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400">
                                    <Mail class="size-4" />
                                </span>
                                <Input
                                    id="email"
                                    type="email"
                                    required
                                    :tabindex="2"
                                    autocomplete="email"
                                    name="email"
                                    placeholder="email@example.com"
                                    class="pl-9"
                                />
                            </div>
                            <InputError :message="errors.email" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="password">Kata Sandi</Label>
                            <div class="relative">
                                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400">
                                    <Lock class="size-4" />
                                </span>
                                <Input
                                    id="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    required
                                    :tabindex="3"
                                    autocomplete="new-password"
                                    name="password"
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

                        <div class="grid gap-2">
                            <Label for="password_confirmation">Konfirmasi Kata Sandi</Label>
                            <div class="relative">
                                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400">
                                    <Lock class="size-4" />
                                </span>
                                <Input
                                    id="password_confirmation"
                                    :type="showConfirm ? 'text' : 'password'"
                                    required
                                    :tabindex="4"
                                    autocomplete="new-password"
                                    name="password_confirmation"
                                    placeholder="Konfirmasi kata sandi"
                                    class="pr-10 pl-9"
                                />
                                <button
                                    type="button"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-500 hover:text-neutral-700"
                                    @click="showConfirm = !showConfirm"
                                    :tabindex="7"
                                    aria-label="Tampilkan konfirmasi kata sandi"
                                >
                                    <Eye v-if="!showConfirm" class="size-4" />
                                    <EyeOff v-else class="size-4" />
                                </button>
                            </div>
                            <InputError :message="errors.password_confirmation" />
                        </div>

                        <Button
                            type="submit"
                            class="mt-2 w-full bg-blue-600 text-white hover:bg-blue-700"
                            tabindex="5"
                            :disabled="processing"
                            data-test="register-user-button"
                        >
                            <Spinner v-if="processing" />
                            Buat Akun
                        </Button>
                    </div>

                    <div class="mt-6 text-center text-sm text-muted-foreground">
                        Sudah punya akun?
                        <TextLink :href="login()" class="underline underline-offset-4" :tabindex="8">Masuk</TextLink>
                    </div>
                </section>
            </div>
        </Form>
    </AuthBlueLayout>
</template>
