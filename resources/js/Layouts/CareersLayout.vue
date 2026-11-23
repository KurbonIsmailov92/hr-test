<script setup lang="ts">
import { computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import FlashMessages from '@/Components/FlashMessages.vue';
import LocaleSwitcher from '@/Components/LocaleSwitcher.vue';
import BaseButton from '@/Components/ui/BaseButton.vue';
import { homePathFor, paths } from '@/routes';
import type { PageProps } from '@/types';

const { t } = useI18n();
const page = usePage<PageProps>();
const user = computed(() => page.props.auth.user);

function logout(): void {
    router.post(paths.logout);
}
</script>

<template>
    <div class="flex min-h-screen flex-col">
        <a href="#content" class="sr-only focus:not-sr-only focus:absolute focus:left-2 focus:top-2 focus:rounded focus:bg-surface focus:px-3 focus:py-2">
            {{ t('common.skipToContent') }}
        </a>
        <header class="border-b border-line bg-surface">
            <div class="mx-auto flex max-w-5xl flex-wrap items-center justify-between gap-3 px-4 py-3">
                <Link :href="paths.home" class="text-lg font-semibold text-brand-700">{{ page.props.appName }}</Link>
                <nav class="flex flex-wrap items-center gap-3 text-sm">
                    <LocaleSwitcher />
                    <template v-if="user">
                        <Link :href="homePathFor(user)" class="font-medium text-ink hover:text-brand-700">{{ t('nav.candidateCabinet') }}</Link>
                        <BaseButton variant="secondary" @click="logout">{{ t('common.signOut') }}</BaseButton>
                    </template>
                    <template v-else>
                        <Link :href="paths.login" class="font-medium text-ink hover:text-brand-700">{{ t('common.signIn') }}</Link>
                        <Link :href="paths.register" class="rounded-md bg-brand-600 px-4 py-2.5 font-medium text-white hover:bg-brand-700">{{ t('common.register') }}</Link>
                    </template>
                </nav>
            </div>
        </header>
        <main id="content" class="mx-auto w-full max-w-5xl flex-1 space-y-4 px-4 py-8">
            <FlashMessages />
            <slot />
        </main>
    </div>
</template>
