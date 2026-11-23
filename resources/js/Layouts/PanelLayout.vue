<script setup lang="ts">
import { computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import FlashMessages from '@/Components/FlashMessages.vue';
import LocaleSwitcher from '@/Components/LocaleSwitcher.vue';
import BaseButton from '@/Components/ui/BaseButton.vue';
import { paths } from '@/routes';
import type { PageProps } from '@/types';

defineProps<{ areaKey: string; navItems: { key: string; href: string }[] }>();

const { t } = useI18n();
const page = usePage<PageProps>();
const user = computed(() => page.props.auth.user);

function logout(): void {
    router.post(paths.logout);
}
</script>

<template>
    <div class="flex min-h-screen flex-col">
        <header class="border-b border-line bg-surface">
            <div class="flex flex-wrap items-center justify-between gap-3 px-4 py-3">
                <div class="flex items-baseline gap-3">
                    <Link :href="paths.home" class="text-lg font-semibold text-brand-700">{{ page.props.appName }}</Link>
                    <span class="text-sm text-ink-muted">{{ t(areaKey) }}</span>
                </div>
                <div class="flex flex-wrap items-center gap-3 text-sm">
                    <LocaleSwitcher />
                    <span v-if="user" class="text-ink-muted">{{ user.name }}</span>
                    <BaseButton variant="secondary" @click="logout">{{ t('common.signOut') }}</BaseButton>
                </div>
            </div>
        </header>
        <div class="flex flex-1 flex-col md:flex-row">
            <nav class="shrink-0 overflow-x-auto border-b border-line bg-surface md:w-56 md:border-b-0 md:border-r" :aria-label="t(areaKey)">
                <ul class="flex gap-1 p-2 md:flex-col">
                    <li v-for="item in navItems" :key="item.key">
                        <Link
                            :href="item.href"
                            class="block whitespace-nowrap rounded-md px-3 py-2 text-sm font-medium"
                            :class="page.url === item.href ? 'bg-brand-50 text-brand-700' : 'text-ink hover:bg-canvas'"
                            :aria-current="page.url === item.href ? 'page' : undefined"
                        >
                            {{ t(`nav.${item.key}`) }}
                        </Link>
                    </li>
                </ul>
            </nav>
            <main class="flex-1 space-y-4 p-4 md:p-8">
                <FlashMessages />
                <slot />
            </main>
        </div>
    </div>
</template>
