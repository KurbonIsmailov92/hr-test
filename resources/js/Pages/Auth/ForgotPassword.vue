<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import BaseButton from '@/Components/ui/BaseButton.vue';
import FormField from '@/Components/ui/FormField.vue';
import TextInput from '@/Components/ui/TextInput.vue';
import { paths } from '@/routes';

const { t } = useI18n();
const form = useForm({ email: '' });

function submit(): void {
    form.post(paths.forgotPassword);
}
</script>

<template>
    <Head :title="t('auth.forgot.title')" />
    <h1 class="text-xl font-semibold text-ink">{{ t('auth.forgot.title') }}</h1>
    <p class="mt-1 text-sm text-ink-muted">{{ t('auth.forgot.subtitle') }}</p>

    <form class="mt-6 space-y-4" @submit.prevent="submit">
        <FormField id="email" :label="t('auth.login.email')" :error="form.errors.email">
            <TextInput id="email" v-model="form.email" type="email" autocomplete="email" required :invalid="!!form.errors.email" />
        </FormField>
        <BaseButton type="submit" class="w-full" :loading="form.processing">{{ t('auth.forgot.submit') }}</BaseButton>
    </form>

    <p class="mt-6 text-sm">
        <Link :href="paths.login" class="font-medium text-brand-700 hover:underline">{{ t('auth.forgot.back') }}</Link>
    </p>
</template>
