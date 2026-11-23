<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import FlashMessages from '@/Components/FlashMessages.vue';
import LocaleSwitcher from '@/Components/LocaleSwitcher.vue';
import { paths } from '@/routes';
import type { PageProps } from '@/types';

const page = usePage<PageProps>();
</script>

<template>
    <div class="flex min-h-screen flex-col items-center px-4 py-8">
        <div class="flex w-full max-w-md items-center justify-between">
            <Link :href="paths.home" class="text-lg font-semibold text-brand-700">{{ page.props.appName }}</Link>
            <LocaleSwitcher />
        </div>
        <main class="mt-10 w-full max-w-md space-y-4">
            <FlashMessages />
            <div class="rounded-lg border border-line bg-surface p-6 shadow-sm sm:p-8">
                <slot />
            </div>
        </main>
    </div>
</template>
