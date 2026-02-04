import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        // Check if it's an Admin page
        if (name.startsWith('Admin/')) {
            return resolvePageComponent(
                `./${name}.vue`,
                import.meta.glob('./Admin/Pages/**/*.vue'),
            );
        }
        // Check if it's a Frontend page
        if (name.startsWith('Frontend/')) {
            return resolvePageComponent(
                `./${name}.vue`,
                import.meta.glob('./Frontend/Pages/**/*.vue'),
            );
        }
        // Otherwise, use the default Pages directory
        return resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        );
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
