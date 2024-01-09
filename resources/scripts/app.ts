import "../css/app.scss";
import { createApp, h, DefineComponent } from "vue";
import { VTooltip } from "floating-vue"
import { modal } from "momentum-modal"
import Toast from "vue-toastification"
import { notifications } from "@/scripts/plugins/notifications"
import "floating-vue/dist/style.css"
import "vue-select/dist/vue-select.css"
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name: string) => {
        return resolvePageComponent(`../views/pages/${name}.vue`, import.meta.glob<DefineComponent>("../views/pages/**/*.vue"))
    },
    setup({ el, App, props, plugin }) {
        // eslint-disable-next-line vue/component-api-style
        createApp({ render: () => h(App, props) })
            .use(Toast)
            .use(notifications)
            .directive('tooltip', VTooltip)
            .use(modal, {
                resolve: (name: string) => {
                    return resolvePageComponent(`../views/pages/${name}.vue`, import.meta.glob<DefineComponent>("../views/pages/**/*.vue"))
                }
            })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
}).then(() => {
    // ...
});
