<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

        {!! SEO::generate() !!}

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Google AdSense -->
        <!-- Uncomment and replace ca-pub-XXXXXXXXXXXXXXXX with your AdSense publisher ID -->
        <!-- <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-XXXXXXXXXXXXXXXX" crossorigin="anonymous"></script> -->

        @stack('head')

        {{-- Structured Data --}}
        @stack('jsonld')
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900">
        <div class="min-h-screen flex flex-col">

            {{-- Sponsored Header Banner --}}
            @if($headerAd ?? null)
                <div class="bg-gray-50 border-b border-gray-200">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
                        <a href="{{ $headerAd->target_url }}" target="_blank" rel="sponsored noopener noreferrer" class="flex items-center justify-center gap-2 text-sm text-gray-500 hover:text-indigo-600 transition">
                            <span class="text-[10px] uppercase tracking-wider text-gray-400 border border-gray-300 rounded px-1">Ad</span>
                            <img src="{{ $headerAd->banner_url }}" alt="{{ $headerAd->company }}" class="h-10 rounded">
                        </a>
                    </div>
                </div>
            @endif

            {{-- Navbar --}}
            <nav x-data="{ open: false, searchOpen: false }" class="bg-white border-b border-gray-200 sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        {{-- Left: Logo + Nav Links --}}
                        <div class="flex items-center">
                            <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
                                <svg class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" />
                                </svg>
                                <span class="text-xl font-bold text-gray-900">Dev<span class="text-indigo-600">Hub</span></span>
                            </a>

                            <div class="hidden sm:flex sm:items-center sm:ml-10 space-x-1">
                                <a href="{{ route('posts.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('posts.*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }} transition">Blog</a>
                                <a href="{{ route('tools.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('tools.*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }} transition">Tools</a>
                                <a href="{{ route('snippets.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('snippets.*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }} transition">Snippets</a>
                            </div>
                        </div>

                        {{-- Right: Search + Auth --}}
                        <div class="flex items-center gap-3">
                            {{-- Search Toggle --}}
                            <button @click="searchOpen = !searchOpen" class="p-2 text-gray-400 hover:text-indigo-600 rounded-md hover:bg-gray-50 transition">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                </svg>
                            </button>

                            {{-- Auth Links --}}
                            <div class="hidden sm:flex sm:items-center">
                                @auth
                                    <x-dropdown align="right" width="48">
                                        <x-slot name="trigger">
                                            <button class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-50 transition">
                                                @if(Auth::user()->avatar)
                                                    <img src="{{ Auth::user()->avatar }}" alt="" class="w-8 h-8 rounded-full object-cover">
                                                @else
                                                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm font-semibold">
                                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <span>{{ Auth::user()->name }}</span>
                                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </button>
                                        </x-slot>
                                        <x-slot name="content">
                                            <x-dropdown-link :href="route('dashboard')">Dashboard</x-dropdown-link>
                                            <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                                    Log Out
                                                </x-dropdown-link>
                                            </form>
                                        </x-slot>
                                    </x-dropdown>
                                @else
                                    <a href="{{ route('login') }}" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 transition">Log in</a>
                                    <a href="{{ route('register') }}" class="ml-2 px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">Register</a>
                                @endauth
                            </div>

                            {{-- Mobile Hamburger --}}
                            <button @click="open = !open" class="sm:hidden p-2 text-gray-400 hover:text-gray-600 rounded-md hover:bg-gray-50 transition">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                    <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Search Bar Dropdown --}}
                <div x-show="searchOpen" x-cloak x-transition class="border-t border-gray-100 bg-white">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                        <form action="{{ route('search') }}" method="GET" class="flex">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search articles, tools, snippets..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" autofocus>
                        </form>
                    </div>
                </div>

                {{-- Mobile Menu --}}
                <div x-show="open" x-cloak class="sm:hidden border-t border-gray-100">
                    <div class="px-4 py-3 space-y-1">
                        <a href="{{ route('posts.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50">Blog</a>
                        <a href="{{ route('tools.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50">Tools</a>
                        <a href="{{ route('snippets.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50">Snippets</a>
                    </div>
                    <div class="border-t border-gray-100 px-4 py-3">
                        @auth
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-semibold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                                </div>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50">Log Out</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50">Log in</a>
                            <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-indigo-600 hover:bg-indigo-50">Register</a>
                        @endauth
                    </div>
                </div>
            </nav>

            {{-- Page Content --}}
            <div class="flex-1">
                @hasSection('full-width')
                    @yield('full-width')
                @endif

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    @hasSection('content')
                        <div class="lg:grid lg:grid-cols-3 lg:gap-8">
                            {{-- Main Content (2/3) --}}
                            <div class="lg:col-span-2">
                                @yield('content')
                            </div>

                            {{-- Sidebar (1/3) --}}
                            <aside class="mt-8 lg:mt-0 space-y-6">
                                {{-- AdSense - Top of Sidebar --}}
                                <x-ad-unit slot="SIDEBAR_TOP" format="auto" class="rounded-xl overflow-hidden" />

                                {{-- Categories Widget --}}
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                                    <h3 class="text-lg font-bold text-gray-900 mb-4">Categories</h3>
                                    <ul class="space-y-2">
                                        @foreach($categories ?? [] as $category)
                                            <li>
                                                <a href="{{ route('posts.index', ['category' => $category->slug]) }}" class="flex items-center justify-between text-sm text-gray-600 hover:text-indigo-600 transition py-1">
                                                    <span class="flex items-center gap-2">
                                                        <span class="w-2 h-2 bg-indigo-400 rounded-full"></span>
                                                        {{ $category->name }}
                                                    </span>
                                                    <span class="text-xs text-gray-400">{{ $category->posts_count ?? 0 }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                {{-- Popular Posts Widget --}}
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                                    <h3 class="text-lg font-bold text-gray-900 mb-4">Popular Posts</h3>
                                    @forelse($popularPosts ?? [] as $post)
                                        <a href="{{ route('posts.show', $post->slug) }}" class="flex gap-3 py-3 border-b border-gray-100 last:border-0 group">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg shrink-0 flex items-center justify-center overflow-hidden">
                                                @if($post->featured_image)
                                                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="" class="w-full h-full object-cover" loading="lazy">
                                                @else
                                                    <svg class="w-6 h-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" /></svg>
                                                @endif
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900 group-hover:text-indigo-600 transition line-clamp-2">{{ $post->title }}</h4>
                                                <p class="text-xs text-gray-400 mt-1">{{ $post->created_at->diffForHumans() }}</p>
                                            </div>
                                        </a>
                                    @empty
                                        <p class="text-sm text-gray-400">No posts yet.</p>
                                    @endforelse
                                </div>

                                {{-- Newsletter Widget --}}
                                <x-newsletter-form variant="gradient" />

                                {{-- Sponsored Ad Slot --}}
                                <x-sponsored-banner placement="sidebar" />

                                {{-- AdSense - Sidebar Bottom --}}
                                <x-ad-unit slot="SIDEBAR_BOTTOM" format="auto" class="rounded-xl overflow-hidden" />
                            </aside>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Footer --}}
            <footer class="bg-gray-900 text-gray-400 mt-auto">
                <!-- Google AdSense - Footer Banner Placeholder -->
                <!-- <ins class="adsbygoogle" data-ad-client="ca-pub-XXXXXXXXXXXXXXXX" data-ad-slot="XXXXXXXXXX" data-ad-format="auto"></ins> -->

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        {{-- Brand --}}
                        <div class="md:col-span-1">
                            <a href="{{ route('home') }}" class="flex items-center gap-2 mb-4">
                                <svg class="h-7 w-7 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" />
                                </svg>
                                <span class="text-lg font-bold text-white">Dev<span class="text-indigo-400">Hub</span></span>
                            </a>
                            <p class="text-sm">Tools, tutorials, and resources for modern developers.</p>
                        </div>

                        {{-- Quick Links --}}
                        <div>
                            <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Explore</h4>
                            <ul class="space-y-2 text-sm">
                                <li><a href="{{ route('posts.index') }}" class="hover:text-white transition">Blog</a></li>
                                <li><a href="{{ route('tools.index') }}" class="hover:text-white transition">Tools</a></li>
                                <li><a href="{{ route('snippets.index') }}" class="hover:text-white transition">Snippets</a></li>
                                <li><a href="{{ route('advertise') }}" class="hover:text-white transition">Advertise</a></li>
                            </ul>
                        </div>

                        {{-- Company --}}
                        <div>
                            <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Company</h4>
                            <ul class="space-y-2 text-sm">
                                <li><a href="{{ route('about') }}" class="hover:text-white transition">About</a></li>
                                <li><a href="{{ route('contact') }}" class="hover:text-white transition">Contact</a></li>
                                <li><a href="{{ route('privacy') }}" class="hover:text-white transition">Privacy Policy</a></li>
                            </ul>
                        </div>

                        {{-- Social --}}
                        <div>
                            <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Follow Us</h4>
                            <div class="flex items-center gap-4">
                                {{-- Twitter/X --}}
                                <a href="#" class="text-gray-400 hover:text-white transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                </a>
                                {{-- GitHub --}}
                                <a href="#" class="text-gray-400 hover:text-white transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/></svg>
                                </a>
                                {{-- YouTube --}}
                                <a href="#" class="text-gray-400 hover:text-white transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                </a>
                                {{-- Discord --}}
                                <a href="#" class="text-gray-400 hover:text-white transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028 14.09 14.09 0 0 0 1.226-1.994.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03z"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-800 mt-10 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <p class="text-sm">&copy; {{ date('Y') }} DevHub. All rights reserved.</p>
                        <div class="flex gap-6 text-sm">
                            <a href="{{ route('privacy') }}" class="hover:text-white transition">Privacy</a>
                            <a href="{{ route('contact') }}" class="hover:text-white transition">Contact</a>
                            <a href="{{ route('sitemap') }}" class="hover:text-white transition">Sitemap</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
