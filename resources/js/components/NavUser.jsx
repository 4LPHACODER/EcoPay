import React from 'react';
import { Inertia } from '@inertiajs/inertia';

export default function NavUser({ user }) {
    // Simple user block: avoid passing function components into ref-sensitive sidebar primitives.
    return (
        <div className="px-4 py-3 border-t border-gray-100">
            <div className="flex items-center space-x-3">
                <div className="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center text-white font-bold text-sm">
                    {user?.name ? user.name.charAt(0).toUpperCase() : 'U'}
                </div>
                <div>
                    <div className="text-sm font-medium text-gray-900">{user?.name ?? 'User'}</div>
                    <div className="text-xs text-gray-500">{user?.email ?? ''}</div>
                </div>
            </div>

            <nav className="mt-3 space-y-1">
                <a href="/analytics" className="block px-3 py-2 rounded hover:bg-gray-50 text-sm text-gray-700">Analytics</a>
                <a href="/settings" className="block px-3 py-2 rounded hover:bg-gray-50 text-sm text-gray-700">Settings</a>

                {/* Logout: call Inertia.post to avoid form markup and avoid Blade-only helpers */}
                <button
                    type="button"
                    onClick={() => Inertia.post('/logout')}
                    className="w-full text-left px-3 py-2 rounded hover:bg-gray-50 text-sm text-red-600"
                >
                    Logout
                </button>
            </nav>
        </div>
    );
}