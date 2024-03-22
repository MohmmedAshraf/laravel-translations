import "../css/app.scss"
import { createApp, h, DefineComponent } from "vue"
import { VTooltip } from "floating-vue"
import { modal } from "momentum-modal"
import "floating-vue/dist/style.css"
import Toast from "vue-toastification"
import "vue-select/dist/vue-select.css"
import { createInertiaApp } from "@inertiajs/vue3"
import { Tabs, Tab } from "vue3-tabs-component"
import { ZiggyVue } from "ziggy-js"
import { notifications } from "./plugins/notifications"
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers"
import "vue-toastification/dist/index.css"

const appName = import.meta.env.VITE_APP_NAME || "Laravel"

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name: string) => {
        return resolvePageComponent(`../views/pages/${name}.vue`, import.meta.glob<DefineComponent>("../views/pages/**/*.vue"))
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .directive("tooltip", VTooltip)
            .use(modal, {
                resolve: (name: string) => {
                    return resolvePageComponent(`../views/pages/${name}.vue`, import.meta.glob<DefineComponent>("../views/pages/**/*.vue"))
                },
            })
            .use(Toast, {
                transition: "Vue-Toastification__bounce",
                maxToasts: 20,
                newestOnTop: true,
            })
            .use(notifications)
            .use(plugin)
            .use(ZiggyVue)
            .component("tabs", Tabs)
            .component("tab", Tab)
            .mount(el)
    },
    progress: {
        color: "#4B5563",
    },
}).then(() => {
    // ...
})
