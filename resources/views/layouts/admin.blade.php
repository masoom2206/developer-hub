<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

        <title>{{ $title ?? 'Admin' }} - DevHub Admin</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="min-h-screen flex" x-data="{ sidebarOpen: false }">

            {{-- Sidebar --}}
            <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 transform transition-transform duration-200 lg:translate-x-0 lg:static lg:inset-auto"
                   :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
                <div class="flex items-center justify-between h-16 px-6 border-b border-gray-800">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <svg class="h-7 w-7 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" />
                        </svg>
                        <span class="text-lg font-bold text-white">Dev<span class="text-indigo-400">Hub</span></span>
                    </a>
                    <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <nav class="px-4 py-6 space-y-1 overflow-y-auto" style="max-height: calc(100vh - 130px)">
                    @php
                        $navItems = [
                            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'match' => 'admin.dashboard', 'icon' => 'M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25'],
                        ];
                        $contentItems = [
                            ['label' => 'Posts', 'route' => 'admin.posts.index', 'match' => 'admin.posts.*', 'icon' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9Z'],
                            ['label' => 'Tools', 'route' => 'tools.index', 'match' => 'admin.tools.*', 'icon' => 'M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.049.58.025 1.194-.14 1.743'],
                            ['label' => 'Snippets', 'route' => 'snippets.index', 'match' => 'admin.snippets.*', 'icon' => 'M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5'],
                        ];
                        $monetItems = [
                            ['label' => 'Sponsors', 'route' => 'admin.sponsors.index', 'match' => 'admin.sponsors.*', 'icon' => 'M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z'],
                            ['label' => 'Subscribers', 'route' => 'admin.subscribers.index', 'match' => 'admin.subscribers.*', 'icon' => 'M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75'],
                        ];
                        $userItems = [
                            ['label' => 'Users', 'route' => 'admin.users.index', 'match' => 'admin.users.*', 'icon' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z'],
                        ];
                    @endphp

                    @foreach($navItems as $item)
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs($item['match']) ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" /></svg>
                            {{ $item['label'] }}
                        </a>
                    @endforeach

                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 pt-4 pb-1 block">Content</span>
                    @foreach($contentItems as $item)
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs($item['match']) ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" /></svg>
                            {{ $item['label'] }}
                        </a>
                    @endforeach

                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 pt-4 pb-1 block">Monetisation</span>
                    @foreach($monetItems as $item)
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs($item['match']) ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" /></svg>
                            {{ $item['label'] }}
                        </a>
                    @endforeach

                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 pt-4 pb-1 block">System</span>
                    @foreach($userItems as $item)
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs($item['match']) ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" /></svg>
                            {{ $item['label'] }}
                        </a>
                    @endforeach

                    <div class="pt-4 border-t border-gray-800 mt-4">
                        <a href="{{ route('home') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-gray-800 hover:text-white transition">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                            View Site
                        </a>
                    </div>
                </nav>

                {{-- User --}}
                <div class="absolute bottom-0 left-0 right-0 border-t border-gray-800 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-semibold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-white transition" title="Log out">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" /></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            {{-- Overlay --}}
            <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/50 lg:hidden"></div>

            {{-- Main --}}
            <div class="flex-1 flex flex-col min-w-0">
                {{-- Top Bar --}}
                <header class="sticky top-0 z-30 bg-white border-b border-gray-200 h-16 flex items-center px-4 sm:px-6 lg:px-8">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-50 mr-3">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
                    </button>
                    <h1 class="text-lg font-semibold text-gray-900">@yield('page-title', 'Admin')</h1>
                </header>

                {{-- Content --}}
                <main class="flex-1 p-4 sm:p-6 lg:p-8">
                    {{-- Flash Messages --}}
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg flex items-center gap-2">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
