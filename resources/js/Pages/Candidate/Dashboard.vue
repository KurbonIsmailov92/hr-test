<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import type { PageProps } from '@/types';

const { t } = useI18n();
const page = usePage<PageProps>();
</script>

<template>
    <Head :title="t('candidate.dashboard.title')" />
    <h1 class="text-2xl font-semibold text-ink">{{ t('candidate.dashboard.welcome', { name: page.props.auth.user?.name ?? '' }) }}</h1>
    <section class="mt-6 rounded-lg border border-line bg-surface p-6">
        <h2 class="font-semibold text-ink">{{ t('candidate.dashboard.applicationsTitle') }}</h2>
        <p class="mt-1 text-sm text-ink-muted">{{ t('candidate.dashboard.applicationsEmpty') }}</p>
    </section>
</template>
