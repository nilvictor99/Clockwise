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
    resolve: {
        alias: {
            '@': '/resources/js',
            '~': '/resources'
        }
    },
    build: {
        manifest: true,
        outDir: 'public/build',
        assetsDir: '',
        rollupOptions: {
            output: {
                manualChunks: undefined,
                assetFileNames: '[name].[hash][extname]',
                chunkFileNames: '[name].[hash].js',
                entryFileNames: '[name].[hash].js',
            },
        },
    },
    server: {
        hmr: {
            host: 'localhost',
        },
    },
});
