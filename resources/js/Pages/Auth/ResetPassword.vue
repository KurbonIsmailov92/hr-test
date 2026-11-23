<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import BaseButton from '@/Components/ui/BaseButton.vue';
import FormField from '@/Components/ui/FormField.vue';
import TextInput from '@/Components/ui/TextInput.vue';
import { paths } from '@/routes';

const props = defineProps<{ token: string; email: string }>();

const { t } = useI18n();
const form = useForm({ token: props.token, email: props.email, password: '', password_confirmation: '' });

function submit(): void {
    form.post(paths.passwordUpdate, { onFinish: () => form.reset('password', 'password_confirmation') });
}
</script>

<template>
    <Head :title="t('auth.reset.title')" />
    <h1 class="text-xl font-semibold text-ink">{{ t('auth.reset.title') }}</h1>

    <form class="mt-6 space-y-4" @submit.prevent="submit">
        <FormField id="email" :label="t('auth.login.email')" :error="form.errors.email">
            <TextInput id="email" v-model="form.email" type="email" autocomplete="username" required :invalid="!!form.errors.email" />
        </FormField>
        <FormField id="password" :label="t('auth.reset.password')" :hint="t('auth.register.passwordHint')" :error="form.errors.password">
            <TextInput id="password" v-model="form.password" type="password" autocomplete="new-password" required :invalid="!!form.errors.password" />
        </FormField>
        <FormField id="password_confirmation" :label="t('auth.reset.passwordConfirmation')" :error="form.errors.password_confirmation">
            <TextInput id="password_confirmation" v-model="form.password_confirmation" type="password" autocomplete="new-password" required />
        </FormField>
        <BaseButton type="submit" class="w-full" :loading="form.processing">{{ t('auth.reset.submit') }}</BaseButton>
    </form>
</template>
