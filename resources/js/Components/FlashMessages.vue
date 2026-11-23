<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import type { PageProps } from '@/types';

const page = usePage<PageProps>();

const messages = computed(() => {
    const flash = page.props.flash ?? {};
    return [
        { kind: 'error', text: flash.error },
        { kind: 'success', text: flash.success ?? flash.status },
    ].filter((m): m is { kind: string; text: string } => Boolean(m.text));
});
</script>

<template>
    <div v-if="messages.length" class="space-y-2" role="status" aria-live="polite">
        <p
            v-for="message in messages"
            :key="message.kind + message.text"
            class="rounded-md border px-4 py-3 text-sm"
            :class="
                message.kind === 'error'
                    ? 'border-danger-600/30 bg-danger-50 text-danger-600'
                    : 'border-success-700/30 bg-success-50 text-success-700'
            "
        >
            {{ message.text }}
        </p>
    </div>
</template>
