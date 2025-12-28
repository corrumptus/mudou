import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        tailwindcss(),
        wayfinder({
            formVariants: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: [
            { find: "@pages", replacement: path.resolve(__dirname, "./resources/js/pages/routes") },
            { find: "@layout", replacement: path.resolve(__dirname, "./resources/js/pages/layout") },
            { find: "@header", replacement: path.resolve(__dirname, "./resources/js/pages/components/header") },
            { find: "@navbar", replacement: path.resolve(__dirname, "./resources/js/pages/components/navbar") },
            { find: "@content", replacement: path.resolve(__dirname, "./resources/js/pages/components/content") },
            { find: "@sidebar", replacement: path.resolve(__dirname, "./resources/js/pages/components/sidebar") },
            { find: "@hooks", replacement: path.resolve(__dirname, "./resources/js/hooks") },
            { find: "@data", replacement: path.resolve(__dirname, "./resources/js/data") },
            { find: "@request", replacement: path.resolve(__dirname, "./resources/js/request") },
            { find: "@utils", replacement: path.resolve(__dirname, "./resources/js/utils") },
            { find: "@type", replacement: path.resolve(__dirname, "./resources/js/types") }
        ]
    }
});
