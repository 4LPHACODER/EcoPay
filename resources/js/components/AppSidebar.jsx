import React from 'react';
import { Link, usePage } from '@inertiajs/react';
import { Inertia } from '@inertiajs/inertia';

export default function AppSidebar() {
    const user = usePage().props?.auth?.user ?? null;

    return (
        <aside className="w-64 bg-white shadow-sm border-r border-gray-200 fixed h-screen overflow-y-auto">
            <div className="p-6 border-b border-gray-200 flex justify-center">
                <img src="/EcoPay.png" alt="EcoPay" className="h-12 w-auto" />
            </div>

            <nav className="mt-6">
                <Link
                    href="/dashboard"
                    className={`flex items-center px-6 py-3 ${window.location.pathname === '/dashboard' ? 'text-green-600 bg-green-50 font-semibold border-r-4 border-green-600' : 'text-gray-700 hover:bg-gray-50'}`}
                >
                    <svg className="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4m0 0l4 4m-4-4V3"></path>
                    </svg>
                    Dashboard
                </Link>

                <Link
                    href="/analytics"
                    className={`flex items-center px-6 py-3 ${window.location.pathname === '/analytics' ? 'text-green-600 bg-green-50 font-semibold border-r-4 border-green-600' : 'text-gray-700 hover:bg-gray-50'}`}
                >
                    <svg className="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 3v10a1 1 0 001 1h9M6 8h.01M6 12h.01M6 16h.01"></path>
                    </svg>
                    Analytics
                </Link>

                <Link
                    href="/settings"
                    className={`flex items-center px-6 py-3 ${window.location.pathname === '/settings' ? 'text-green-600 bg-green-50 font-semibold border-r-4 border-green-600' : 'text-gray-700 hover:bg-gray-50'}`}
                >
                    <svg className="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Settings
                </Link>
            </nav>

            <div className="absolute bottom-0 w-64 p-6 border-t border-gray-200 bg-white">
                <div className="flex items-center">
                    <div className="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center text-white font-bold text-sm">
                        {user?.name ? user.name.charAt(0).toUpperCase() : 'U'}
                    </div>
                    <div className="ml-3 flex-1 min-w-0">
                        <p className="text-sm font-medium text-gray-900 truncate">{user?.name ?? 'User'}</p>
                        <p className="text-xs text-gray-500 truncate">{user?.email ?? ''}</p>
                    </div>
                </div>

                <div className="mt-4">
                    <button
                        type="button"
                        onClick={() => Inertia.post('/logout')}
                        className="w-full text-left text-sm text-gray-700 hover:text-gray-900 font-medium"
                    >
                        Logout
                    </button>
                </div>
            </div>
        </aside>
    );
}