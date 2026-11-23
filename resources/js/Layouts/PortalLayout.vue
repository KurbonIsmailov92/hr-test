<script setup lang="ts">
import PanelLayout from '@/Layouts/PanelLayout.vue';
import { paths } from '@/routes';

const navItems = [{ key: 'dashboard', href: paths.portal }];
</script>

<template>
    <PanelLayout area-key="nav.portalArea" :nav-items="navItems"><slot /></PanelLayout>
</template>
