<script setup lang="ts">
import { computed } from 'vue';

const props = withDefaults(
    defineProps<{
        variant?: 'primary' | 'secondary' | 'danger';
        type?: 'button' | 'submit';
        loading?: boolean;
        disabled?: boolean;
    }>(),
    { variant: 'primary', type: 'button', loading: false, disabled: false },
);

const variantClasses = computed(
    () =>
        ({
            primary: 'bg-brand-600 text-white hover:bg-brand-700',
            secondary: 'border border-line bg-surface text-ink hover:bg-canvas',
            danger: 'bg-danger-600 text-white hover:opacity-90',
        })[props.variant],
);
</script>

<template>
    <button
        :type="type"
        :disabled="disabled || loading"
        :aria-busy="loading"
        class="inline-flex items-center justify-center rounded-md px-4 py-2.5 text-sm font-medium transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600 disabled:cursor-not-allowed disabled:opacity-60"
        :class="variantClasses"
    >
        <slot />
    </button>
</template>
