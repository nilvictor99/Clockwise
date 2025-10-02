import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import i18n from 'laravel-vue-i18n/vite';

const baseUrl = process.env.APP_URL || '/';
const assetPath = 'assets/'; 


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
    base: baseUrl,
    build: {
        rollupOptions: {
            output: {
                assetFileNames: `${assetPath}[name]-[hash][extname]`,
                chunkFileNames: `${assetPath}[name]-[hash].js`,
                entryFileNames: `${assetPath}[name]-[hash].js`,
            },
        },
    },
});
