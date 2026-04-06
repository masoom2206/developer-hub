@extends('layouts.app')

@section('content')
    {{-- Flash --}}
    @if(session('success'))
        <div class="mb-6 p-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg">{{ session('success') }}</div>
    @endif

    <article>
        {{-- Breadcrumb --}}
        <nav class="text-sm text-gray-500 mb-4">
            <a href="{{ route('snippets.index') }}" class="hover:text-indigo-600 transition">Snippets</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900">{{ $snippet->title }}</span>
        </nav>

        {{-- Header --}}
        <div class="flex items-start justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 mb-2">{{ $snippet->title }}</h1>
                <div class="flex items-center gap-3 text-sm text-gray-500">
                    <div class="flex items-center gap-2">
                        @if($snippet->user->avatar)
                            <img src="{{ $snippet->user->avatar }}" class="w-6 h-6 rounded-full">
                        @else
                            <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-semibold">{{ strtoupper(substr($snippet->user->name, 0, 1)) }}</div>
                        @endif
                        <span>{{ $snippet->user->name }}</span>
                    </div>
                    <span>&middot;</span>
                    <time>{{ $snippet->created_at->format('M d, Y') }}</time>
                    <span>&middot;</span>
                    @include('snippets._lang_badge', ['lang' => $snippet->language])
                </div>
            </div>

            {{-- Actions --}}
            @auth
                @if(auth()->id() === $snippet->user_id || auth()->user()->role === 'admin')
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('snippets.edit', $snippet) }}" class="px-3 py-1.5 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">Edit</a>
                        <form action="{{ route('snippets.destroy', $snippet) }}" method="POST" onsubmit="return confirm('Delete this snippet?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 text-sm font-medium text-red-600 bg-white border border-red-200 rounded-lg hover:bg-red-50 transition">Delete</button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>

        {{-- Description --}}
        @if($snippet->description)
            <p class="text-gray-600 mb-6 leading-relaxed">{{ $snippet->description }}</p>
        @endif

        {{-- Code Block --}}
        <div class="mb-6 rounded-xl overflow-hidden border border-gray-200 shadow-sm" x-data="{ copied: false }">
            <div class="flex items-center justify-between px-4 py-2.5 bg-gray-800 border-b border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-red-400"></span>
                        <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                        <span class="w-3 h-3 rounded-full bg-green-400"></span>
                    </div>
                    <span class="text-sm text-gray-400 font-mono">{{ strtolower($snippet->language) }}</span>
                </div>
                <button @click="navigator.clipboard.writeText(document.getElementById('snippet-code').textContent); copied = true; setTimeout(() => copied = false, 2000)"
                        class="flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-medium transition"
                        :class="copied ? 'bg-green-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600 hover:text-white'">
                    <svg x-show="!copied" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9.75a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" /></svg>
                    <svg x-show="copied" x-cloak class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                    <span x-text="copied ? 'Copied!' : 'Copy code'"></span>
                </button>
            </div>
            @php
                $prismLang = match($snippet->language) {
                    'PHP' => 'php',
                    'JavaScript' => 'javascript',
                    'TypeScript' => 'typescript',
                    'CSS' => 'css',
                    'HTML' => 'markup',
                    'Python' => 'python',
                    'Bash' => 'bash',
                    'SQL' => 'sql',
                    'Vue' => 'javascript',
                    'React' => 'jsx',
                    default => 'plaintext',
                };
            @endphp
            <pre class="!m-0 !rounded-none"><code id="snippet-code" class="language-{{ $prismLang }}">{{ $snippet->code }}</code></pre>
        </div>

        {{-- Tags --}}
        @if($snippet->tags->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-6">
                @foreach($snippet->tags as $tag)
                    <a href="{{ route('snippets.index', ['tag' => $tag->slug]) }}" class="text-xs font-medium text-gray-600 bg-gray-100 rounded-full px-3 py-1.5 hover:bg-indigo-50 hover:text-indigo-600 transition">
                        #{{ $tag->name }}
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Share --}}
        <div class="flex items-center gap-3 mb-8 pb-8 border-b border-gray-200" x-data="{ linkCopied: false }">
            <span class="text-sm font-medium text-gray-500">Share:</span>
            <a href="https://twitter.com/intent/tweet?text={{ urlencode($snippet->title . ' — Code Snippet') }}&url={{ urlencode(route('snippets.show', $snippet->slug)) }}"
               target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-600 rounded-lg text-sm hover:bg-gray-900 hover:text-white transition">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                Post
            </a>
            <button @click="navigator.clipboard.writeText('{{ route('snippets.show', $snippet->slug) }}'); linkCopied = true; setTimeout(() => linkCopied = false, 2000)"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-600 rounded-lg text-sm hover:bg-indigo-600 hover:text-white transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" /></svg>
                <span x-text="linkCopied ? 'Copied!' : 'Copy link'"></span>
            </button>
        </div>

        {{-- Related --}}
        @if($relatedSnippets->isNotEmpty())
            <section>
                <h2 class="text-lg font-bold text-gray-900 mb-4">More {{ $snippet->language }} Snippets</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($relatedSnippets as $related)
                        <a href="{{ route('snippets.show', $related->slug) }}" class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition group">
                            <div class="bg-gray-900 px-4 py-3 h-20 overflow-hidden">
                                <pre class="text-xs text-gray-400 font-mono leading-relaxed"><code>{{ implode("\n", array_slice(explode("\n", $related->code), 0, 3)) }}</code></pre>
                            </div>
                            <div class="p-3 flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-900 group-hover:text-indigo-600 transition truncate">{{ $related->title }}</span>
                                @include('snippets._lang_badge', ['lang' => $related->language])
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </article>

    {{-- Prism.js --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/themes/prism-tomorrow.min.css">
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/prism.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-php.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-javascript.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-typescript.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-css.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-python.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-bash.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-sql.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-jsx.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-markup.min.js"></script>
    <style>
        pre[class*="language-"] { margin: 0; border-radius: 0; border: none; }
        code[class*="language-"] { font-size: 0.85rem; line-height: 1.7; }
    </style>
@endsection
