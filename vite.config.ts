import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import react from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig(({ command }) => ({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.jsx',
                'resources/js/analytics.js',
                'resources/js/dashboard-realtime.js',
            ],
            ssr: 'resources/js/ssr.tsx',
            refresh: true,
        }),
        react(),
        // Wayfinder generates TypeScript types for Laravel routes
        wayfinder({ formVariants: true }),
    ],
    esbuild: {
        jsx: 'automatic',
    },
}));
