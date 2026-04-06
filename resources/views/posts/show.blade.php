@extends('layouts.app')

@push('jsonld')
<script type="application/ld+json">
@php
    $jsonLd = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $post->title,
        'description' => $post->excerpt,
        'author' => ['@type' => 'Person', 'name' => $post->user->name],
        'publisher' => ['@type' => 'Organization', 'name' => 'DevHub', 'url' => url('/')],
        'datePublished' => $post->created_at->toW3CString(),
        'dateModified' => $post->updated_at->toW3CString(),
        'mainEntityOfPage' => route('posts.show', $post->slug),
    ];
    if ($post->featured_image) {
        $jsonLd['image'] = asset('storage/' . $post->featured_image);
    }
@endphp
{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
    <article>
        {{-- Featured Image --}}
        @if($post->featured_image)
            <div class="rounded-xl overflow-hidden mb-6 aspect-video">
                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover" loading="lazy">
            </div>
        @endif

        {{-- Category & Sponsored Badge --}}
        <div class="flex items-center gap-2 mb-3">
            @if($post->category)
                <a href="{{ route('posts.index', ['category' => $post->category->slug]) }}" class="text-xs font-semibold text-indigo-600 bg-indigo-50 rounded-full px-3 py-1 hover:bg-indigo-100 transition">{{ $post->category->name }}</a>
            @endif
            @if($post->is_sponsored)
                <span class="text-xs font-medium text-amber-600 bg-amber-50 rounded-full px-2.5 py-1">Sponsored</span>
            @endif
        </div>

        {{-- Title --}}
        <h1 class="text-3xl font-extrabold text-gray-900 mb-4 leading-tight">{{ $post->title }}</h1>

        {{-- Author & Meta --}}
        <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-200">
            <div class="flex items-center gap-3">
                @if($post->user->avatar)
                    <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}" class="w-10 h-10 rounded-full object-cover">
                @else
                    <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm font-semibold">
                        {{ strtoupper(substr($post->user->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ $post->user->name }}</p>
                    <p class="text-xs text-gray-400">
                        <time datetime="{{ $post->created_at->toDateString() }}">{{ $post->created_at->format('M d, Y') }}</time>
                        &middot; {{ number_format($post->views) }} views
                    </p>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="prose prose-indigo max-w-none mb-8
                    prose-headings:font-bold prose-headings:text-gray-900
                    prose-p:text-gray-600 prose-p:leading-relaxed
                    prose-a:text-indigo-600 prose-a:no-underline hover:prose-a:underline
                    prose-code:bg-gray-100 prose-code:px-1 prose-code:py-0.5 prose-code:rounded prose-code:text-sm
                    prose-pre:bg-gray-900 prose-pre:text-gray-100 prose-pre:rounded-xl
                    prose-img:rounded-xl">
            @php
                $paragraphs = preg_split('/(<\/p>)/i', $post->content, 3, PREG_SPLIT_DELIM_CAPTURE);
                $firstPart = ($paragraphs[0] ?? '') . ($paragraphs[1] ?? '');
                $rest = implode('', array_slice($paragraphs, 2));
            @endphp
            {!! $firstPart !!}

            @if($rest)
                {{-- In-article Ad after first paragraph --}}
                <div class="not-prose my-6">
                    <x-ad-unit slot="IN_ARTICLE" format="fluid" class="rounded-lg overflow-hidden" />
                </div>
                {!! $rest !!}
            @endif
        </div>

        {{-- Below Content Ad --}}
        <div class="mb-8">
            <x-ad-unit slot="BELOW_CONTENT" format="auto" class="rounded-xl overflow-hidden" />
        </div>

        {{-- Tags --}}
        @if($post->tags->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-6 pb-6 border-b border-gray-200">
                @foreach($post->tags as $tag)
                    <a href="{{ route('posts.index', ['tag' => $tag->slug]) }}" class="text-xs font-medium text-gray-600 bg-gray-100 rounded-full px-3 py-1.5 hover:bg-indigo-50 hover:text-indigo-600 transition">
                        #{{ $tag->name }}
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Share Buttons --}}
        <div class="flex items-center gap-3 mb-8 pb-8 border-b border-gray-200" x-data="{ copied: false }">
            <span class="text-sm font-medium text-gray-500">Share:</span>
            {{-- Twitter/X --}}
            <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(route('posts.show', $post->slug)) }}"
               target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-600 rounded-lg text-sm hover:bg-gray-900 hover:text-white transition">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                Post
            </a>
            {{-- LinkedIn --}}
            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('posts.show', $post->slug)) }}"
               target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-600 rounded-lg text-sm hover:bg-blue-700 hover:text-white transition">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                Share
            </a>
            {{-- Copy Link --}}
            <button @click="navigator.clipboard.writeText('{{ route('posts.show', $post->slug) }}'); copied = true; setTimeout(() => copied = false, 2000)"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-600 rounded-lg text-sm hover:bg-indigo-600 hover:text-white transition">
                <svg x-show="!copied" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" /></svg>
                <svg x-show="copied" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                <span x-text="copied ? 'Copied!' : 'Copy link'"></span>
            </button>
        </div>

        {{-- Comments Section --}}
        <section id="comments" class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">
                Comments ({{ $post->comments->count() }})
            </h2>

            {{-- Comment Form --}}
            @auth
                <form action="{{ route('comments.store', $post) }}" method="POST" class="mb-8">
                    @csrf
                    <div class="flex gap-3">
                        @if(Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar }}" class="w-10 h-10 rounded-full shrink-0">
                        @else
                            <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm font-semibold shrink-0">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="flex-1">
                            <textarea name="body" rows="3" required placeholder="Write a comment..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm resize-none">{{ old('body') }}</textarea>
                            @error('body')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                            <div class="mt-2 flex justify-end">
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">Post Comment</button>
                            </div>
                        </div>
                    </div>
                </form>
            @else
                <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 text-center mb-8">
                    <p class="text-sm text-gray-500 mb-3">Join the conversation</p>
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                        Login to comment
                    </a>
                </div>
            @endauth

            {{-- Flash --}}
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Comments List --}}
            <div class="space-y-6">
                @forelse($post->comments->sortByDesc('created_at') as $comment)
                    <div class="flex gap-3">
                        @if($comment->user->avatar)
                            <img src="{{ $comment->user->avatar }}" alt="{{ $comment->user->name }}" class="w-10 h-10 rounded-full shrink-0">
                        @else
                            <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center text-sm font-semibold shrink-0">
                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="flex-1 bg-gray-50 rounded-xl p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-sm font-medium text-gray-900">{{ $comment->user->name }}</span>
                                <span class="text-xs text-gray-400">&middot;</span>
                                <time class="text-xs text-gray-400" datetime="{{ $comment->created_at->toDateTimeString() }}">{{ $comment->created_at->diffForHumans() }}</time>
                            </div>
                            <p class="text-sm text-gray-600 leading-relaxed">{{ $comment->body }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-4">No comments yet. Be the first to share your thoughts!</p>
                @endforelse
            </div>
        </section>

        {{-- Related Posts --}}
        @if($relatedPosts->isNotEmpty())
            <section class="border-t border-gray-200 pt-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Related Posts</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach($relatedPosts as $related)
                        <a href="{{ route('posts.show', $related->slug) }}" class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition group">
                            <div class="aspect-video bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center overflow-hidden">
                                @if($related->featured_image)
                                    <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" loading="lazy">
                                @else
                                    <svg class="w-8 h-8 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" /></svg>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="text-sm font-semibold text-gray-900 group-hover:text-indigo-600 transition line-clamp-2">{{ $related->title }}</h3>
                                <time class="text-xs text-gray-400 mt-1 block">{{ $related->created_at->format('M d, Y') }}</time>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </article>

    {{-- Prism.js for code blocks in post content --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/themes/prism-tomorrow.min.css">
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/prism.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-php.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-javascript.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-typescript.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-css.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-python.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-bash.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-sql.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-json.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-markup.min.js"></script>
    <style>
        pre[class*="language-"] { margin: 0; border-radius: 0.75rem; }
        code[class*="language-"] { font-size: 0.85rem; line-height: 1.7; }
        .prose pre { padding: 0; }
    </style>
@endsection
