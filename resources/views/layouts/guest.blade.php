<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

        <title>{{ config('app.name', 'DevHub') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-indigo-50 via-white to-purple-50 text-gray-900">
        <div class="min-h-screen flex flex-col items-center justify-center px-4 py-8">
            {{-- Logo --}}
            <div class="mb-8">
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <svg class="h-10 w-10 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" />
                    </svg>
                    <span class="text-2xl font-bold text-gray-900">Dev<span class="text-indigo-600">Hub</span></span>
                </a>
            </div>

            {{-- Card --}}
            <div class="w-full sm:max-w-md bg-white rounded-2xl shadow-xl border border-gray-200 p-8">
                {{ $slot }}
            </div>

            {{-- Footer --}}
            <p class="mt-8 text-sm text-gray-400">
                &copy; {{ date('Y') }} DevHub. All rights reserved.
            </p>
        </div>
    </body>
</html>
