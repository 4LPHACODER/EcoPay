import '../css/app.css';
import { initializeTheme } from './hooks/use-appearance';

import { createInertiaApp } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Helper to convert kebab-case or lowercase to PascalCase
// e.g., "auth/login" -> "Auth/Login", "dashboard" -> "Dashboard"
const toPascalCase = (str) => {
    return str
        .split('/')
        .map((segment) =>
            segment
                .replace(/[-_\s]+(.)?/g, (_, c) => (c ? c.toUpperCase() : ''))
                .replace(/^(.)/, (c) => c.toUpperCase()),
        )
        .join('/');
};

// Case-insensitive page resolver for Inertia
const resolvePage = (name) => {
    // Get all available pages
    const pages = import.meta.glob('./Pages/**/*.jsx', { eager: true });
    
    // 1. Try exact match (e.g., "Dashboard" -> "./Pages/Dashboard.jsx")
    const exactPath = `./Pages/${name}.jsx`;
    if (pages[exactPath]) {
        return pages[exactPath];
    }
    
    // 2. Try with the name converted to PascalCase (e.g., "auth/login" -> "Auth/Login")
    const pascalName = toPascalCase(name);
    const pascalPath = `./Pages/${pascalName}.jsx`;
    if (pages[pascalPath]) {
        return pages[pascalPath];
    }
    
    // 3. Try case-insensitive search in all Pages
    const normalizedName = name.toLowerCase();
    for (const [path, component] of Object.entries(pages)) {
        // Extract the relative path without "./Pages/" prefix and ".jsx" suffix
        const relativePath = path.slice(2, -5); // Remove "./Pages/" and ".jsx"
        if (relativePath.toLowerCase() === normalizedName) {
            return component;
        }
    }
    
    // 4. Try additional common variations (Profile/Edit, etc.)
    // If name is "profile", also try "Profile/Edit"
    if (!name.includes('/')) {
        const variations = [
            `./Pages/${name}.jsx`,
            `./Pages/Auth/${name}.jsx`,
            `./Pages/Profile/Edit.jsx`,
        ];
        for (const variant of variations) {
            if (pages[variant]) {
                return pages[variant];
            }
        }
    }
    
    // 5. If nothing found, throw a helpful error
    const availablePages = Object.keys(pages).join(', ');
    throw new Error(
        `Page not found: ${name}.\n` +
        `Tried: ${exactPath}, ${pascalPath}.\n` +
        `Available pages: ${availablePages}`
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
