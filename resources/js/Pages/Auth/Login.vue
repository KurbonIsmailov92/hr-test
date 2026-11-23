<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import BaseButton from '@/Components/ui/BaseButton.vue';
import FormField from '@/Components/ui/FormField.vue';
import TextInput from '@/Components/ui/TextInput.vue';
import { paths } from '@/routes';

const { t } = useI18n();
const form = useForm({ email: '', password: '', remember: false });

function submit(): void {
    form.post(paths.login, { onFinish: () => form.reset('password') });
}
</script>

<template>
    <Head :title="t('auth.login.title')" />
    <h1 class="text-xl font-semibold text-ink">{{ t('auth.login.title') }}</h1>
    <p class="mt-1 text-sm text-ink-muted">{{ t('auth.login.subtitle') }}</p>

    <form class="mt-6 space-y-4" @submit.prevent="submit">
        <FormField id="email" :label="t('auth.login.email')" :error="form.errors.email">
            <TextInput id="email" v-model="form.email" type="email" autocomplete="username" required :invalid="!!form.errors.email" />
        </FormField>
        <FormField id="password" :label="t('auth.login.password')" :error="form.errors.password">
            <TextInput id="password" v-model="form.password" type="password" autocomplete="current-password" required :invalid="!!form.errors.password" />
        </FormField>
        <label class="flex items-center gap-2 text-sm text-ink">
            <input v-model="form.remember" type="checkbox" class="size-4 rounded border-line accent-brand-600" />
            {{ t('auth.login.remember') }}
        </label>
        <BaseButton type="submit" class="w-full" :loading="form.processing">{{ t('auth.login.submit') }}</BaseButton>
    </form>

    <div class="mt-6 space-y-2 text-sm">
        <p><Link :href="paths.forgotPassword" class="font-medium text-brand-700 hover:underline">{{ t('auth.login.forgot') }}</Link></p>
        <p class="text-ink-muted">
            {{ t('auth.login.noAccount') }}
            <Link :href="paths.register" class="font-medium text-brand-700 hover:underline">{{ t('auth.login.createAccount') }}</Link>
        </p>
    </div>
</template>
