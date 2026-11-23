<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { paths } from '@/routes';

// Эта страница может отображаться без общих (shared) props, поэтому не зависит от layout и данных пользователя.
const props = defineProps<{ status: number }>();

const { t, te } = useI18n();
const key = computed(() => (te(`error.${props.status}.title`) ? String(props.status) : 'default'));
</script>

<template>
    <Head :title="t(`error.${key}.title`)" />
    <main class="mx-auto flex min-h-screen max-w-md flex-col justify-center px-4 text-center">
        <p class="text-5xl font-semibold text-brand-700">{{ status }}</p>
        <h1 class="mt-4 text-xl font-semibold text-ink">{{ t(`error.${key}.title`) }}</h1>
        <p class="mt-2 text-sm text-ink-muted">{{ t(`error.${key}.text`) }}</p>
        <p class="mt-6">
            <Link :href="paths.home" class="font-medium text-brand-700 hover:underline">{{ t('common.backHome') }}</Link>
        </p>
    </main>
</template>
