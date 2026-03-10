<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'EcoPay') }}</title>
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-green-50 via-white to-blue-50 min-h-screen">
        <div class="flex items-center justify-center min-h-screen py-12 px-4">
            <div class="w-full max-w-md">
                <!-- Card -->
                <div class="bg-white rounded-lg shadow-lg border border-gray-100 p-8">
                    {{ $slot }}
                </div>

                <!-- Footer Links -->
                <div class="mt-6 text-center text-sm text-gray-600">
                    @if(Route::currentRouteName() === 'login')
                        Don't have an account?
                        <a href="{{ route('register') }}" class="font-medium text-green-600 hover:text-green-700">
                            Sign up
                        </a>
                    @elseif(Route::currentRouteName() === 'register')
                        Already have an account?
                        <a href="{{ route('login') }}" class="font-medium text-green-600 hover:text-green-700">
                            Sign in
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </body>
</html>
