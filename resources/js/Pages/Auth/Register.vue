<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import BaseButton from '@/Components/ui/BaseButton.vue';
import FormField from '@/Components/ui/FormField.vue';
import TextInput from '@/Components/ui/TextInput.vue';
import { paths } from '@/routes';

const { t } = useI18n();
const form = useForm({
    last_name: '',
    first_name: '',
    middle_name: '',
    phone: '',
    email: '',
    password: '',
    password_confirmation: '',
    consent: false,
    website: '', // honeypot: должно остаться пустым
});

function submit(): void {
    form.post(paths.register, { onFinish: () => form.reset('password', 'password_confirmation') });
}
</script>

<template>
    <Head :title="t('auth.register.title')" />
    <h1 class="text-xl font-semibold text-ink">{{ t('auth.register.title') }}</h1>
    <p class="mt-1 text-sm text-ink-muted">{{ t('auth.register.subtitle') }}</p>

    <form class="mt-6 space-y-4" @submit.prevent="submit">
        <div class="grid gap-4 sm:grid-cols-2">
            <FormField id="last_name" :label="t('auth.register.lastName')" :error="form.errors.last_name">
                <TextInput id="last_name" v-model="form.last_name" autocomplete="family-name" required :maxlength="100" :invalid="!!form.errors.last_name" />
            </FormField>
            <FormField id="first_name" :label="t('auth.register.firstName')" :error="form.errors.first_name">
                <TextInput id="first_name" v-model="form.first_name" autocomplete="given-name" required :maxlength="100" :invalid="!!form.errors.first_name" />
            </FormField>
        </div>
        <FormField id="middle_name" :label="t('auth.register.middleName')" :error="form.errors.middle_name">
            <TextInput id="middle_name" v-model="form.middle_name" autocomplete="additional-name" :maxlength="100" :invalid="!!form.errors.middle_name" />
        </FormField>
        <FormField id="phone" :label="t('auth.register.phone')" :error="form.errors.phone">
            <TextInput id="phone" v-model="form.phone" type="tel" autocomplete="tel" :maxlength="32" :invalid="!!form.errors.phone" />
        </FormField>
        <FormField id="email" :label="t('auth.register.email')" :error="form.errors.email">
            <TextInput id="email" v-model="form.email" type="email" autocomplete="email" required :invalid="!!form.errors.email" />
        </FormField>
        <FormField id="password" :label="t('auth.register.password')" :hint="t('auth.register.passwordHint')" :error="form.errors.password">
            <TextInput id="password" v-model="form.password" type="password" autocomplete="new-password" required :invalid="!!form.errors.password" />
        </FormField>
        <FormField id="password_confirmation" :label="t('auth.register.passwordConfirmation')" :error="form.errors.password_confirmation">
            <TextInput id="password_confirmation" v-model="form.password_confirmation" type="password" autocomplete="new-password" required />
        </FormField>

        <div class="absolute -left-[9999px]" aria-hidden="true">
            <input v-model="form.website" type="text" name="website" tabindex="-1" autocomplete="off" />
        </div>

        <div>
            <label class="flex items-start gap-2 text-sm text-ink">
                <input v-model="form.consent" type="checkbox" class="mt-0.5 size-4 shrink-0 rounded border-line accent-brand-600" />
                <span>{{ t('auth.register.consent') }}</span>
            </label>
            <p v-if="form.errors.consent" role="alert" class="mt-1 text-xs font-medium text-danger-600">{{ form.errors.consent }}</p>
        </div>

        <BaseButton type="submit" class="w-full" :loading="form.processing">{{ t('auth.register.submit') }}</BaseButton>
    </form>

    <p class="mt-6 text-sm text-ink-muted">
        {{ t('auth.register.haveAccount') }}
        <Link :href="paths.login" class="font-medium text-brand-700 hover:underline">{{ t('common.signIn') }}</Link>
    </p>
</template>
