<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import type { PageProps } from '@/types';

const { t } = useI18n();
const page = usePage<PageProps>();
</script>

<template>
    <Head :title="t('portal.dashboard.title')" />
    <h1 class="text-2xl font-semibold text-ink">{{ t('portal.dashboard.welcome', { name: page.props.auth.user?.name ?? '' }) }}</h1>
    <p class="mt-4 max-w-xl text-sm text-ink-muted">{{ t('portal.dashboard.empty') }}</p>
</template>
