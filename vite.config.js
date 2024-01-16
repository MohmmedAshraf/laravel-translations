import { defineConfig } from "vite"
import laravel from "laravel-vite-plugin"
import vue from "@vitejs/plugin-vue"
import autoimport from "unplugin-auto-import/vite"
import components from "unplugin-vue-components/vite"

export default defineConfig({
    resolve: {
        alias: {
            "@": "/resources",
            "~": "/node_modules",
        },
    },
    plugins: [
        laravel({
            input: "resources/scripts/app.ts",
            ssr: "resources/scripts/ssr.ts",
            publicDirectory: "resources/dist",
            buildDirectory: "vendor/translations-ui",
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
            script: {
                defineModel: true,
                propsDestructure: true,
            },
        }),
        autoimport({
            vueTemplate: true,
            dts: "resources/scripts/types/auto-imports.d.ts",
            dirs: ["resources/scripts/composables", "resources/scripts/utils"],
            imports: [
                "vue",
                "@vueuse/core",
                {
                    "momentum-lock": ["can"],
                },
                {
                    "momentum-modal": ["useModal"],
                },
                {
                    "@inertiajs/vue3": ["router", "useForm", "usePage", "useRemember"],
                },
            ],
        }),
        components({
            dirs: ["resources/views/components"],
            dts: "resources/scripts/types/components.d.ts",
            resolvers: [
                (name) => {
                    const components = ["Link", "Head"]

                    if (components.includes(name)) {
                        return {
                            name: name,
                            from: "@inertiajs/vue3",
                        }
                    }
                },

                (name) => {
                    if (name.startsWith("Layout")) {
                        const componentName = name.substring("Layout".length).toLowerCase()

                        return {
                            name: "default",
                            from: `@/views/layouts/${componentName}/layout-${componentName}.vue`,
                        }
                    }
                },
            ],
        }),
    ],
})
