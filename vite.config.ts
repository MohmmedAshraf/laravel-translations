import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import react from '@vitejs/plugin-react';
import fs from 'fs';
import { resolve } from 'path';
import { defineConfig, type Plugin } from 'vite';

function copyStaticAssets(): Plugin {
    return {
        name: 'copy-static-assets',
        closeBundle() {
            fs.copyFileSync(
                resolve(__dirname, 'resources/images/favicon.svg'),
                resolve(__dirname, 'dist/favicon.svg'),
            );
        },
    };
}

const hotFiles = [
    resolve(__dirname, 'public/hot-translations'),
    resolve(__dirname, 'vendor/orchestra/testbench-core/laravel/public/hot-translations'),
];

export default defineConfig({
    plugins: [
        react(),
        tailwindcss(),
        wayfinder({
            command: 'php vendor/bin/testbench wayfinder:generate',
            path: 'resources/js',
            formVariants: true,
            patterns: ['routes/**/*.php', 'src/**/Http/**/*.php'],
        }),
        {
            name: 'translations-hot-file',
            configureServer(server) {
                const clean = () => hotFiles.forEach((f) => fs.existsSync(f) && fs.unlinkSync(f));

                server.httpServer?.once('listening', () => {
                    const addr = server.httpServer?.address();
                    const port = typeof addr === 'object' && addr ? addr.port : 5173;
                    const url = `http://localhost:${port}`;

                    for (const hotFile of hotFiles) {
                        fs.mkdirSync(resolve(hotFile, '..'), { recursive: true });
                        fs.writeFileSync(hotFile, url);
                    }
                });

                process.on('exit', clean);
                process.on('SIGINT', () => process.exit());
                process.on('SIGTERM', () => process.exit());
            },
        },
        copyStaticAssets(),
    ],
    server: {
        cors: true,
    },
    build: {
        outDir: 'dist',
        emptyOutDir: true,
        manifest: true,
        rollupOptions: {
            input: {
                app: resolve(__dirname, 'resources/js/app.tsx'),
            },
            output: {
                entryFileNames: 'js/app.js',
                chunkFileNames: 'js/[name]-[hash].js',
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name?.endsWith('.css')) {
                        return 'css/app.css';
                    }
                    return 'assets/[name]-[hash][extname]';
                },
            },
        },
    },
    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources/js'),
        },
    },
});
