<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { homePathFor, paths } from '@/routes';
import type { PageProps } from '@/types';

const { t } = useI18n();
const page = usePage<PageProps>();
const user = computed(() => page.props.auth.user);
</script>

<template>
    <Head :title="t('careers.home.title')" />
    <section class="max-w-2xl py-8 sm:py-14">
        <h1 class="text-3xl font-semibold leading-tight text-ink sm:text-4xl">{{ t('careers.home.title') }}</h1>
        <p class="mt-4 text-lg text-ink-muted">{{ t('careers.home.lead') }}</p>
        <div class="mt-8">
            <Link
                v-if="!user"
                :href="paths.register"
                class="inline-flex rounded-md bg-brand-600 px-5 py-3 text-sm font-medium text-white hover:bg-brand-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600"
            >
                {{ t('careers.home.cta') }}
            </Link>
            <Link
                v-else
                :href="homePathFor(user)"
                class="inline-flex rounded-md bg-brand-600 px-5 py-3 text-sm font-medium text-white hover:bg-brand-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600"
            >
                {{ t('nav.candidateCabinet') }}
            </Link>
        </div>
    </section>
    <section class="rounded-lg border border-dashed border-line bg-surface p-6">
        <h2 class="font-semibold text-ink">{{ t('careers.home.noticeTitle') }}</h2>
        <p class="mt-1 text-sm text-ink-muted">{{ t('careers.home.notice') }}</p>
    </section>
</template>
