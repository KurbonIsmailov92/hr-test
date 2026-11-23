<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { paths } from '@/routes';
import type { Locale, PageProps } from '@/types';

const { t } = useI18n();
const page = usePage<PageProps>();

function change(locale: Locale): void {
    if (locale === page.props.locale) return;
    router.post(paths.locale, { locale }, { preserveScroll: true, preserveState: true });
}
</script>

<template>
    <div class="inline-flex overflow-hidden rounded-md border border-line text-xs" role="group" :aria-label="t('common.language')">
        <button
            v-for="locale in page.props.locales"
            :key="locale"
            type="button"
            class="px-2.5 py-1.5 font-medium transition-colors focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-brand-600"
            :class="locale === page.props.locale ? 'bg-brand-600 text-white' : 'bg-surface text-ink hover:bg-canvas'"
            :aria-pressed="locale === page.props.locale"
            @click="change(locale)"
        >
            {{ t(`locale.${locale}`) }}
        </button>
    </div>
</template>
