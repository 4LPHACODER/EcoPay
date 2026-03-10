import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import react from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.jsx',
                'resources/js/app.tsx',
                'resources/js/landing.js',
                'resources/js/dashboard-realtime.js',
                'resources/js/analytics.js',
            ],
            refresh: true,
        }),
        react(),
        wayfinder(),
    ],
});
