@extends('layouts.app')

@section('content')
    {{-- Header --}}
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Code Snippets</h1>
            <p class="text-gray-500 mt-1">Ready-to-use code snippets from the community</p>
        </div>
        @auth
            <a href="{{ route('snippets.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                New Snippet
            </a>
        @endauth
    </div>

    {{-- Search + Filter --}}
    <form action="{{ route('snippets.index') }}" method="GET" class="mb-6">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search snippets..."
                    class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </div>
            <select name="language" onchange="this.form.submit()" class="rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <option value="">All Languages</option>
                @foreach(['PHP','JavaScript','CSS','Python','Bash','SQL','HTML','TypeScript','Vue','React'] as $lang)
                    <option value="{{ $lang }}" {{ request('language') === $lang ? 'selected' : '' }}>{{ $lang }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition">Search</button>
        </div>
    </form>

    {{-- Active Filters --}}
    @if(request('language') || request('tag') || request('search'))
        <div class="mb-6 flex items-center gap-2 flex-wrap">
            <span class="text-sm text-gray-500">Filtering:</span>
            @if(request('language'))
                <a href="{{ route('snippets.index', request()->except('language')) }}" class="inline-flex items-center gap-1 text-sm bg-indigo-50 text-indigo-700 rounded-full px-3 py-1 hover:bg-indigo-100 transition">
                    {{ request('language') }}
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                </a>
            @endif
            @if(request('tag'))
                <a href="{{ route('snippets.index', request()->except('tag')) }}" class="inline-flex items-center gap-1 text-sm bg-purple-50 text-purple-700 rounded-full px-3 py-1 hover:bg-purple-100 transition">
                    #{{ request('tag') }}
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                </a>
            @endif
            @if(request('search'))
                <a href="{{ route('snippets.index', request()->except('search')) }}" class="inline-flex items-center gap-1 text-sm bg-gray-100 text-gray-700 rounded-full px-3 py-1 hover:bg-gray-200 transition">
                    "{{ request('search') }}"
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                </a>
            @endif
            <a href="{{ route('snippets.index') }}" class="text-sm text-gray-400 hover:text-gray-600 transition">Clear all</a>
        </div>
    @endif

    {{-- Tags --}}
    @if($tags->isNotEmpty())
        <div class="mb-6 flex flex-wrap gap-2">
            @foreach($tags as $tag)
                <a href="{{ route('snippets.index', ['tag' => $tag->slug]) }}"
                   class="text-xs font-medium px-3 py-1.5 rounded-full transition {{ request('tag') === $tag->slug ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-indigo-300 hover:text-indigo-600' }}">
                    #{{ $tag->name }}
                </a>
            @endforeach
        </div>
    @endif

    {{-- Flash --}}
    @if(session('success'))
        <div class="mb-6 p-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg">{{ session('success') }}</div>
    @endif

    {{-- Grid --}}
    @if($snippets->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($snippets as $snippet)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition group" x-data="{ codeCopied: false }">
                    {{-- Code Preview --}}
                    <div class="bg-gray-900 relative">
                        <div class="flex items-center justify-between px-4 py-2 border-b border-gray-800">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-yellow-400"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-green-400"></span>
                            </div>
                            <span class="text-xs text-gray-500 font-mono">{{ strtolower($snippet->language) }}</span>
                        </div>
                        <a href="{{ route('snippets.show', $snippet->slug) }}" class="block px-4 py-3 h-28 overflow-hidden">
                            <pre class="text-xs text-gray-300 font-mono leading-relaxed"><code>{{ implode("\n", array_slice(explode("\n", $snippet->code), 0, 5)) }}</code></pre>
                        </a>
                        {{-- Copy --}}
                        <button @click.prevent="navigator.clipboard.writeText({{ json_encode($snippet->code) }}); codeCopied = true; setTimeout(() => codeCopied = false, 2000)"
                                class="absolute top-2 right-2 p-1.5 rounded-md bg-gray-800/80 text-gray-400 hover:text-white hover:bg-gray-700 transition opacity-0 group-hover:opacity-100"
                                :title="codeCopied ? 'Copied!' : 'Copy code'">
                            <svg x-show="!codeCopied" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9.75a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" /></svg>
                            <svg x-show="codeCopied" x-cloak class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                        </button>
                    </div>

                    {{-- Info --}}
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <h3 class="font-semibold text-gray-900 text-sm group-hover:text-indigo-600 transition">
                                <a href="{{ route('snippets.show', $snippet->slug) }}">{{ $snippet->title }}</a>
                            </h3>
                            @include('snippets._lang_badge', ['lang' => $snippet->language])
                        </div>

                        @if($snippet->tags->isNotEmpty())
                            <div class="flex flex-wrap gap-1 mb-3">
                                @foreach($snippet->tags->take(3) as $tag)
                                    <a href="{{ route('snippets.index', ['tag' => $tag->slug]) }}" class="text-[11px] text-gray-500 hover:text-indigo-600 transition">#{{ $tag->name }}</a>
                                @endforeach
                            </div>
                        @endif

                        <div class="flex items-center justify-between text-xs text-gray-400">
                            <div class="flex items-center gap-1.5">
                                @if($snippet->user->avatar)
                                    <img src="{{ $snippet->user->avatar }}" class="w-4 h-4 rounded-full">
                                @else
                                    <div class="w-4 h-4 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-[9px] font-bold">{{ strtoupper(substr($snippet->user->name, 0, 1)) }}</div>
                                @endif
                                {{ $snippet->user->name }}
                            </div>
                            <time>{{ $snippet->created_at->diffForHumans() }}</time>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $snippets->withQueryString()->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" /></svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">No snippets found</h3>
            <p class="text-sm text-gray-500 mb-4">Be the first to share a code snippet.</p>
            @auth
                <a href="{{ route('snippets.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    New Snippet
                </a>
            @endauth
        </div>
    @endif
@endsection
