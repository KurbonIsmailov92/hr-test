<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import type { PageProps } from '@/types';

defineProps<{ stats: { users: number; candidates: number } }>();

const { t } = useI18n();
const page = usePage<PageProps>();
</script>

<template>
    <Head :title="t('admin.dashboard.title')" />
    <h1 class="text-2xl font-semibold text-ink">{{ t('admin.dashboard.welcome', { name: page.props.auth.user?.name ?? '' }) }}</h1>
    <dl class="mt-6 grid max-w-xl grid-cols-2 gap-4">
        <div class="rounded-lg border border-line bg-surface p-4">
            <dt class="text-sm text-ink-muted">{{ t('admin.dashboard.usersTotal') }}</dt>
            <dd class="mt-1 text-2xl font-semibold text-ink">{{ stats.users }}</dd>
        </div>
        <div class="rounded-lg border border-line bg-surface p-4">
            <dt class="text-sm text-ink-muted">{{ t('admin.dashboard.candidatesTotal') }}</dt>
            <dd class="mt-1 text-2xl font-semibold text-ink">{{ stats.candidates }}</dd>
        </div>
    </dl>
</template>
