import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';

// Inertia v2 Form passes inert={false} to DOM <form> elements, which React 18
// doesn't handle correctly (React 19 does). Suppress until React is upgraded.
const _consoleError = console.error.bind(console);
console.error = (...args) => {
    if (typeof args[0] === 'string' && args[0].includes('`inert`')) return;
    _consoleError(...args);
};

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const pages = {
    ...import.meta.glob('./Pages/**/*.jsx', { eager: true }),
    ...import.meta.glob('./pages/**/*.tsx', { eager: true }),
};

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        const jsxKey = `./Pages/${name}.jsx`;
        const tsxKey = `./pages/${name}.tsx`;

        const page = pages[tsxKey] ?? pages[jsxKey];

        if (!page) {
            throw new Error(`Page not found: ${name}`);
        }

        return page;
    },
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(<App {...props} />);
    },
    progress: {
        color: '#4B5563',
    },
});
