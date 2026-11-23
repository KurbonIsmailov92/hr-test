import '../css/app.css';

import { createApp, h, type Component, type DefineComponent } from 'vue';
import { createInertiaApp, router } from '@inertiajs/vue3';
import { i18n, setLocale } from '@/i18n';
import type { Locale } from '@/types';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import CareersLayout from '@/Layouts/CareersLayout.vue';
import PortalLayout from '@/Layouts/PortalLayout.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const appName = import.meta.env.VITE_APP_NAME || 'HR Platform';

// Layout выбирается по первому сегменту имени страницы: Auth/*, Careers/*, Candidate/*, Portal/*, Admin/*.
const layouts: Record<string, Component> = {
    Auth: AuthLayout,
    Careers: CareersLayout,
    Candidate: CareersLayout,
    Portal: PortalLayout,
    Admin: AdminLayout,
};

const pages = import.meta.glob<{ default: DefineComponent & { layout?: Component } }>('./Pages/**/*.vue');

createInertiaApp({
    title: (title) => (title ? `${title} · ${appName}` : appName),
    resolve: async (name) => {
        const loader = pages[`./Pages/${name}.vue`];
        if (!loader) throw new Error(`Page not found: ${name}`);

        const module = await loader();
        const group = name.split('/')[0];
        module.default.layout ??= layouts[group];

        return module;
    },
    setup({ el, App, props, plugin }) {
        setLocale(props.initialPage.props.locale as Locale | undefined);

        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(i18n)
            .mount(el);
    },
    progress: { color: '#0e6b6b' },
});

// Язык может смениться при навигации (переключатель языка, вход пользователя).
router.on('navigate', (event) => {
    setLocale(event.detail.page.props.locale as Locale | undefined);
});
