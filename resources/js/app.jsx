import '../css/app.css';
import { initializeTheme } from './hooks/use-appearance';

import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Helper to resolve page with case-insensitive matching
const resolvePage = (name) => {
    const pages = import.meta.glob('./Pages/**/*.jsx', { eager: true });
    const pagesLower = import.meta.glob('./pages/**/*.jsx', { eager: true });
    
    // Try exact match first (PascalCase like Auth/Login)
    const exactPath = `./Pages/${name}.jsx`;
    if (pages[exactPath]) {
        return pages[exactPath];
    }
    
    // Try lowercase path (like auth/login)
    const lowerPath = `./pages/${name}.jsx`;
    if (pagesLower[lowerPath]) {
        return pagesLower[lowerPath];
    }
    
    // Try case-insensitive match in Pages
    const lowerName = name.toLowerCase();
    for (const [path, component] of Object.entries(pages)) {
        const relativePath = path.replace('./Pages/', '').replace('.jsx', '').toLowerCase();
        if (relativePath === lowerName) {
            return component;
        }
    }
    
    // Fallback to resolvePageComponent
    return resolvePageComponent(
        `./Pages/${name}.jsx`,
        pages,
    );
};

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: resolvePage,
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(<App {...props} />);
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on load...
initializeTheme();
