<script setup lang="ts">
defineProps<{
    id: string;
    type?: string;
    autocomplete?: string;
    invalid?: boolean;
    required?: boolean;
    maxlength?: number;
}>();

const model = defineModel<string>({ required: true });
</script>

<template>
    <input
        :id="id"
        v-model="model"
        :type="type ?? 'text'"
        :autocomplete="autocomplete"
        :required="required"
        :maxlength="maxlength"
        :aria-invalid="invalid || undefined"
        :aria-describedby="invalid ? `${id}-error` : undefined"
        class="block w-full rounded-md border bg-surface px-3 py-2.5 text-sm text-ink placeholder:text-ink-muted focus-visible:outline-2 focus-visible:outline-offset-1 focus-visible:outline-brand-600"
        :class="invalid ? 'border-danger-600' : 'border-line'"
    />
</template>
