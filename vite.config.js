import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import i18n from 'laravel-vue-i18n/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        i18n(),
    ],
    server: {
        https: process.env.VITE_DEV_SERVER_HTTPS === 'true',
    },
    build: {
        manifest: 'manifest.json', // Genera en public/build/
        outDir: 'public/build',
        assetsDir: 'assets',
        base: process.env.APP_URL ? `${process.env.APP_URL}/build/` : '/build/', // HTTPS
        rollupOptions: {
        output: {
            manualChunks: undefined,
        },
        },
    },
    // Better cache handling for Docker environments
    cacheDir: '/tmp/.vite',
    optimizeDeps: {
        force: true
    }
});
