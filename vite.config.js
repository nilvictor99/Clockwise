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
    base: '/',
    build: {
        rollupOptions: {
            output: {
                assetFileNames: 'build/assets/[name]-[hash][extname]',
                chunkFileNames: 'build/assets/[name]-[hash].js',
                entryFileNames: 'build/assets/[name]-[hash].js',
            },
        },
    },
});
