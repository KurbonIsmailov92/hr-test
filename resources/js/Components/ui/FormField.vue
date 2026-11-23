<script setup lang="ts">
defineProps<{ id: string; label: string; error?: string; hint?: string }>();
</script>

<template>
    <div class="space-y-1.5">
        <label :for="id" class="block text-sm font-medium text-ink">{{ label }}</label>
        <slot />
        <p v-if="hint && !error" class="text-xs text-ink-muted">{{ hint }}</p>
        <p v-if="error" :id="`${id}-error`" role="alert" class="text-xs font-medium text-danger-600">{{ error }}</p>
    </div>
</template>
