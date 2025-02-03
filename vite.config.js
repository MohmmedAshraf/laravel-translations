import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    resolve: {
        alias: {
            "@": "/resources/js",
            "~": "/node_modules",
        },
    },
    plugins: [
        laravel({
            input: ['resources/js/app.tsx'],
            publicDirectory: "resources/dist",
            buildDirectory: "vendor/translations-ui",
            refresh: true,
        }),
        react(),
    ],
})
