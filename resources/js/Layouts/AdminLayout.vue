<script setup lang="ts">
import PanelLayout from '@/Layouts/PanelLayout.vue';
import { paths } from '@/routes';

// Новые разделы админ-панели добавляются в этот список по мере этапов.
const navItems = [{ key: 'dashboard', href: paths.admin }];
</script>

<template>
    <PanelLayout area-key="nav.adminArea" :nav-items="navItems"><slot /></PanelLayout>
</template>
