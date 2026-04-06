@extends('layouts.app')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Blog</h1>
        <p class="text-gray-500 mt-1">Articles, tutorials, and insights for developers</p>
    </div>

    {{-- Active Filters --}}
    @if(request('category') || request('tag'))
        <div class="mb-6 flex items-center gap-2 flex-wrap">
            <span class="text-sm text-gray-500">Filtering by:</span>
            @if(request('category'))
                <a href="{{ route('posts.index', request()->except('category')) }}" class="inline-flex items-center gap-1 text-sm bg-indigo-50 text-indigo-700 rounded-full px-3 py-1 hover:bg-indigo-100 transition">
                    {{ request('category') }}
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                </a>
            @endif
            @if(request('tag'))
                <a href="{{ route('posts.index', request()->except('tag')) }}" class="inline-flex items-center gap-1 text-sm bg-purple-50 text-purple-700 rounded-full px-3 py-1 hover:bg-purple-100 transition">
                    #{{ request('tag') }}
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                </a>
            @endif
            <a href="{{ route('posts.index') }}" class="text-sm text-gray-400 hover:text-gray-600 transition">Clear all</a>
        </div>
    @endif

    {{-- Tags Cloud --}}
    @if($tags->isNotEmpty())
        <div class="mb-8 flex flex-wrap gap-2">
            @foreach($tags as $tag)
                <a href="{{ route('posts.index', ['tag' => $tag->slug]) }}"
                   class="text-xs font-medium px-3 py-1.5 rounded-full transition {{ request('tag') === $tag->slug ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-indigo-300 hover:text-indigo-600' }}">
                    #{{ $tag->name }}
                </a>
            @endforeach
        </div>
    @endif

    {{-- Posts Grid --}}
    @if($posts->isNotEmpty())
        <div class="space-y-6">
            @foreach($posts as $post)
                <article class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition group">
                    <div class="sm:flex">
                        {{-- Thumbnail --}}
                        <div class="sm:w-56 sm:shrink-0">
                            <div class="aspect-video sm:aspect-auto sm:h-full bg-gray-100 relative overflow-hidden">
                                @if($post->featured_image)
                                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" loading="lazy">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-50 to-purple-50 min-h-[120px]">
                                        <svg class="w-10 h-10 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" /></svg>
                                    </div>
                                @endif
                            </div>
                        </div>
                        {{-- Content --}}
                        <div class="p-5 flex-1 flex flex-col">
                            <div class="flex items-center gap-2 mb-2">
                                @if($post->category)
                                    <a href="{{ route('posts.index', ['category' => $post->category->slug]) }}" class="text-xs font-semibold text-indigo-600 bg-indigo-50 rounded-full px-2.5 py-0.5 hover:bg-indigo-100 transition">{{ $post->category->name }}</a>
                                @endif
                                @if($post->is_sponsored)
                                    <span class="text-xs font-medium text-amber-600 bg-amber-50 rounded-full px-2 py-0.5">Sponsored</span>
                                @endif
                            </div>
                            <h2 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition mb-2">
                                <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
                            </h2>
                            <p class="text-sm text-gray-500 line-clamp-2 mb-3 flex-1">{{ $post->excerpt }}</p>
                            <div class="flex items-center justify-between text-xs text-gray-400">
                                <div class="flex items-center gap-3">
                                    <span class="flex items-center gap-1">
                                        @if($post->user->avatar)
                                            <img src="{{ $post->user->avatar }}" class="w-5 h-5 rounded-full">
                                        @else
                                            <div class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-[10px] font-semibold">{{ strtoupper(substr($post->user->name, 0, 1)) }}</div>
                                        @endif
                                        {{ $post->user->name }}
                                    </span>
                                    <time datetime="{{ $post->created_at->toDateString() }}">{{ $post->created_at->format('M d, Y') }}</time>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                        {{ number_format($post->views) }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785A5.969 5.969 0 0 0 6 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337Z" /></svg>
                                        {{ $post->comments_count ?? $post->comments->count() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $posts->withQueryString()->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12H9.75m3 0H9.75m3 0H9.75M3.375 20.25h17.25c.621 0 1.125-.504 1.125-1.125V4.875c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v14.25c0 .621.504 1.125 1.125 1.125Z" /></svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">No posts found</h3>
            <p class="text-sm text-gray-500">Check back later for new content.</p>
        </div>
    @endif
@endsection
