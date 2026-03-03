import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import react from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';
import { defineConfig, loadEnv } from 'vite';

export default defineConfig(({ mode }) => {
  // Load env vars from .env and Vercel env
  const env = loadEnv(mode, process.cwd(), '');

  // On Vercel, process.env.VERCEL is usually set to "1"
  // We'll also allow a manual override: WAYFINDER_SKIP_TYPES=1
  const isVercel = process.env.VERCEL === '1';
  const skipWayfinder = isVercel || env.WAYFINDER_SKIP_TYPES === '1';

  return {
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

      // Wayfinder generates TS types by calling: php artisan wayfinder:generate
      // Vercel's Node build environment doesn't have PHP, so we skip it there.
      ...(skipWayfinder ? [] : [wayfinder({ formVariants: true })]),
    ],
    esbuild: {
      jsx: 'automatic',
    },
  };
});