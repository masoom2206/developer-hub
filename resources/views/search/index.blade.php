@extends('layouts.app')

@section('full-width')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Search Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Search</h1>
        <form action="{{ route('search') }}" method="GET">
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <input type="text" name="q" value="{{ $query }}" placeholder="Search posts, tools, snippets..."
                    class="w-full pl-12 pr-4 py-3 rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base" autofocus>
            </div>
        </form>
    </div>

    @if($query && strlen($query) >= 2)
        <p class="text-sm text-gray-500 mb-6">{{ $totalResults }} result{{ $totalResults !== 1 ? 's' : '' }} for "<strong class="text-gray-900">{{ $query }}</strong>"</p>

        {{-- Posts --}}
        @if($posts->isNotEmpty())
            <div class="mb-10">
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                    Posts
                    <span class="text-sm font-normal text-gray-400">({{ $posts->count() }})</span>
                </h2>
                <div class="space-y-3">
                    @foreach($posts as $post)
                        <a href="{{ route('posts.show', $post->slug) }}" class="block bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md hover:border-indigo-200 transition group">
                            <div class="flex items-start gap-4">
                                @if($post->featured_image)
                                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="" class="w-20 h-14 rounded-lg object-cover shrink-0" loading="lazy">
                                @endif
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-gray-900 group-hover:text-indigo-600 transition mb-1">{!! highlightSearch($post->title, $query) !!}</h3>
                                    <p class="text-sm text-gray-500 line-clamp-2">{!! highlightSearch($post->excerpt, $query) !!}</p>
                                    <div class="flex items-center gap-3 mt-2 text-xs text-gray-400">
                                        @if($post->category)
                                            <span class="text-indigo-600 bg-indigo-50 rounded-full px-2 py-0.5 font-medium">{{ $post->category->name }}</span>
                                        @endif
                                        <span>{{ $post->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Tools --}}
        @if($tools->isNotEmpty())
            <div class="mb-10">
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.049.58.025 1.194-.14 1.743" /></svg>
                    Tools
                    <span class="text-sm font-normal text-gray-400">({{ $tools->count() }})</span>
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($tools as $tool)
                        <a href="{{ route('tools.show', $tool->slug) }}" class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md hover:border-emerald-200 transition group">
                            <h3 class="font-semibold text-gray-900 group-hover:text-emerald-600 transition mb-1">{!! highlightSearch($tool->name, $query) !!}</h3>
                            <p class="text-sm text-gray-500 line-clamp-2">{!! highlightSearch($tool->description, $query) !!}</p>
                            <span class="inline-flex items-center text-xs font-medium text-emerald-600 bg-emerald-50 rounded-full px-2 py-0.5 mt-2">{{ $tool->tool_type }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Snippets --}}
        @if($snippets->isNotEmpty())
            <div class="mb-10">
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" /></svg>
                    Snippets
                    <span class="text-sm font-normal text-gray-400">({{ $snippets->count() }})</span>
                </h2>
                <div class="space-y-3">
                    @foreach($snippets as $snippet)
                        <a href="{{ route('snippets.show', $snippet->slug) }}" class="block bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md hover:border-purple-200 transition group">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="font-semibold text-gray-900 group-hover:text-purple-600 transition">{!! highlightSearch($snippet->title, $query) !!}</h3>
                                @include('snippets._lang_badge', ['lang' => $snippet->language])
                            </div>
                            @if($snippet->description)
                                <p class="text-sm text-gray-500 line-clamp-1">{!! highlightSearch($snippet->description, $query) !!}</p>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- No Results --}}
        @if($totalResults === 0)
            <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">No results found</h3>
                <p class="text-sm text-gray-500">Try different keywords or browse our sections:</p>
                <div class="flex items-center justify-center gap-3 mt-4">
                    <a href="{{ route('posts.index') }}" class="text-sm text-indigo-600 hover:underline">Blog</a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('tools.index') }}" class="text-sm text-indigo-600 hover:underline">Tools</a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('snippets.index') }}" class="text-sm text-indigo-600 hover:underline">Snippets</a>
                </div>
            </div>
        @endif
    @elseif($query)
        <p class="text-sm text-gray-500">Please enter at least 2 characters to search.</p>
    @endif
</div>
@endsection
