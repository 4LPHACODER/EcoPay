import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.jsx',
                'resources/js/landing.js',
                'resources/js/dashboard-realtime.js',
                'resources/js/analytics.js',
            ],
            refresh: true,
        }),
        react(),
    ],
});
