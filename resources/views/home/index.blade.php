@extends('layouts.app')

@push('jsonld')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => 'DevHub',
    'url' => url('/'),
    'description' => 'Tools, tutorials, and resources for modern developers.',
    'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => url('/blog') . '?search={search_term_string}',
        'query-input' => 'required name=search_term_string',
    ],
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('full-width')
    {{-- Hero Section --}}
    <section class="bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
            <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight mb-4">
                Tools & Resources for <span class="text-indigo-200">Developers</span>
            </h1>
            <p class="text-lg text-indigo-100 max-w-2xl mx-auto mb-8">
                Discover tutorials, dev tools, code snippets, and everything you need to level up your development workflow.
            </p>
            <form action="#" method="GET" class="max-w-xl mx-auto">
                <div class="relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input type="text" name="q" placeholder="Search articles, tools, snippets..." class="w-full pl-12 pr-4 py-4 rounded-xl border-0 text-gray-900 shadow-lg text-base focus:ring-2 focus:ring-indigo-300 placeholder-gray-400">
                </div>
            </form>
        </div>
    </section>

    {{-- Featured Tools Section --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Featured Tools</h2>
                <p class="text-gray-500 mt-1">Handpicked tools to supercharge your workflow</p>
            </div>
            <a href="{{ route('tools.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 transition hidden sm:block">View all tools &rarr;</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($tools as $tool)
                <a href="{{ route('tools.show', $tool->slug) }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-indigo-200 transition group block">
                    <div class="w-12 h-12 bg-indigo-50 rounded-lg flex items-center justify-center mb-4 group-hover:bg-indigo-100 transition">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.049.58.025 1.194-.14 1.743" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-indigo-600 transition">{{ $tool->name }}</h3>
                    <p class="text-sm text-gray-500 line-clamp-2 mb-3">{{ $tool->description }}</p>
                    <span class="inline-flex items-center text-xs font-medium text-indigo-600 bg-indigo-50 rounded-full px-2.5 py-0.5">{{ $tool->tool_type }}</span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Latest Blog Posts Section --}}
    <section class="bg-white border-y border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Latest Blog Posts</h2>
                    <p class="text-gray-500 mt-1">Fresh articles and tutorials from the community</p>
                </div>
                <a href="{{ route('posts.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 transition hidden sm:block">All posts &rarr;</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($posts as $post)
                    <a href="{{ route('posts.show', $post->slug) }}" class="bg-gray-50 rounded-xl overflow-hidden border border-gray-200 hover:shadow-md transition group block">
                        <div class="aspect-video bg-gray-200 relative overflow-hidden">
                            @if($post->featured_image)
                                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-100 to-purple-100">
                                    <svg class="w-12 h-12 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" /></svg>
                                </div>
                            @endif
                            @if($post->category)
                                <span class="absolute top-3 left-3 text-xs font-semibold bg-indigo-600 text-white rounded-full px-2.5 py-1">{{ $post->category->name }}</span>
                            @endif
                        </div>
                        <div class="p-5">
                            <h3 class="font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition line-clamp-2">{{ $post->title }}</h3>
                            <p class="text-sm text-gray-500 line-clamp-2 mb-4">{{ $post->excerpt }}</p>
                            <div class="flex items-center justify-between text-xs text-gray-400">
                                <span>{{ $post->user->name ?? 'Unknown' }}</span>
                                <time datetime="{{ $post->created_at->toDateString() }}">{{ $post->created_at->format('M d, Y') }}</time>
                            </div>
                        </div>
                    </a>
                @empty
                    @php
                        $placeholders = [
                            ['title' => '10 Laravel Tips You Should Know in 2026', 'excerpt' => 'Boost your Laravel productivity with these lesser-known tips and tricks that every developer should master.', 'cat' => 'Laravel', 'date' => 'Apr 01, 2026'],
                            ['title' => 'Building Responsive Layouts with CSS Grid', 'excerpt' => 'A complete guide to creating modern, responsive page layouts using CSS Grid and Flexbox together.', 'cat' => 'CSS & Design', 'date' => 'Mar 28, 2026'],
                            ['title' => 'JavaScript ES2026 Features You Need to Know', 'excerpt' => 'Explore the newest JavaScript features landing in browsers this year and how to use them today.', 'cat' => 'JavaScript', 'date' => 'Mar 25, 2026'],
                        ];
                    @endphp
                    @foreach($placeholders as $ph)
                        <article class="bg-gray-50 rounded-xl overflow-hidden border border-gray-200 hover:shadow-md transition group">
                            <div class="aspect-video bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center relative">
                                <svg class="w-12 h-12 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" /></svg>
                                <span class="absolute top-3 left-3 text-xs font-semibold bg-indigo-600 text-white rounded-full px-2.5 py-1">{{ $ph['cat'] }}</span>
                            </div>
                            <div class="p-5">
                                <h3 class="font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition line-clamp-2">
                                    <a href="#">{{ $ph['title'] }}</a>
                                </h3>
                                <p class="text-sm text-gray-500 line-clamp-2 mb-4">{{ $ph['excerpt'] }}</p>
                                <div class="flex items-center justify-between text-xs text-gray-400">
                                    <span>Admin</span>
                                    <time>{{ $ph['date'] }}</time>
                                </div>
                            </div>
                        </article>
                    @endforeach
                @endforelse
            </div>
        </div>
    </section>

    {{-- Popular Code Snippets Section --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Popular Code Snippets</h2>
                <p class="text-gray-500 mt-1">Ready-to-use code for your projects</p>
            </div>
            <a href="{{ route('snippets.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 transition hidden sm:block">All snippets &rarr;</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6" x-data>
            @foreach($snippets as $snippet)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition group">
                    {{-- Terminal header --}}
                    <a href="{{ route('snippets.show', $snippet->slug) }}">
                        <div class="flex items-center justify-between px-4 py-3 bg-gray-800">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-red-400"></span>
                                <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                                <span class="w-3 h-3 rounded-full bg-green-400"></span>
                            </div>
                            <span class="text-xs font-mono text-gray-400">{{ strtolower($snippet->language) }}</span>
                        </div>
                        <div class="bg-gray-900 p-4 h-32 overflow-hidden">
                            <pre class="text-sm text-green-400 font-mono leading-relaxed"><code>{{ implode("\n", array_slice(explode("\n", $snippet->code), 0, 5)) }}</code></pre>
                        </div>
                    </a>
                    {{-- Info --}}
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-900 text-sm group-hover:text-indigo-600 transition">
                                <a href="{{ route('snippets.show', $snippet->slug) }}">{{ $snippet->title }}</a>
                            </h3>
                            @include('snippets._lang_badge', ['lang' => $snippet->language])
                        </div>
                        <button x-data="{ copied: false }"
                                @click="navigator.clipboard.writeText({{ json_encode($snippet->code) }}); copied = true; setTimeout(() => copied = false, 2000)"
                                class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition" title="Copy code">
                            <svg x-show="!copied" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9.75a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" /></svg>
                            <svg x-show="copied" x-cloak class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Newsletter CTA Section --}}
    <section class="bg-gradient-to-r from-indigo-600 to-purple-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="max-w-2xl mx-auto text-center">
                <h2 class="text-3xl font-bold text-white mb-3">Stay in the Loop</h2>
                <p class="text-indigo-100 mb-8">Join 5,000+ developers getting weekly tips, tools, and tutorials straight to their inbox.</p>
                <form action="#" method="POST" class="flex flex-col sm:flex-row gap-3 max-w-lg mx-auto">
                    @csrf
                    <input type="email" name="email" placeholder="Enter your email" required class="flex-1 rounded-lg border-0 px-4 py-3 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-300 text-sm">
                    <button type="submit" class="px-6 py-3 bg-white text-indigo-700 font-semibold text-sm rounded-lg hover:bg-indigo-50 shadow-sm transition whitespace-nowrap">Subscribe Free</button>
                </form>
                <p class="text-xs text-indigo-200 mt-4">No spam, ever. Unsubscribe anytime.</p>
            </div>
        </div>
    </section>

    <!-- Google AdSense - In-content Ad Placeholder -->
    <!-- <ins class="adsbygoogle" data-ad-client="ca-pub-XXXXXXXXXXXXXXXX" data-ad-slot="XXXXXXXXXX" data-ad-format="auto"></ins> -->
@endsection
