@extends('layouts.app')

@push('jsonld')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebApplication',
    'name' => $tool->name,
    'description' => $tool->description,
    'url' => route('tools.show', $tool->slug),
    'applicationCategory' => 'DeveloperApplication',
    'operatingSystem' => 'Any',
    'offers' => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'USD'],
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('full-width')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Breadcrumb --}}
        <nav class="text-sm text-gray-500 mb-6">
            <a href="{{ route('tools.index') }}" class="hover:text-indigo-600 transition">Tools</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900">{{ $tool->name }}</span>
        </nav>

        <div class="lg:grid lg:grid-cols-4 lg:gap-8">
            {{-- Main Content --}}
            <div class="lg:col-span-3">
                {{-- Title --}}
                <h1 class="text-3xl font-extrabold text-gray-900 mb-2">@yield('tool-title', $tool->name)</h1>
                <p class="text-gray-500 mb-6">@yield('tool-description', $tool->description)</p>

                {{-- Ad between description and tool --}}
                <div class="mb-6">
                    <x-ad-unit slot="TOOL_ABOVE" format="horizontal" class="rounded-lg overflow-hidden" />
                </div>

                {{-- Tool --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                    @yield('tool-content')
                </div>

                {{-- How to Use --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">How to Use</h2>
                    <div class="prose prose-sm prose-gray max-w-none">
                        @yield('tool-howto')
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <aside class="mt-8 lg:mt-0">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-24">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Other Tools</h3>
                    <ul class="space-y-1">
                        @foreach($relatedTools as $rt)
                            <li>
                                <a href="{{ route('tools.show', $rt->slug) }}"
                                   class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition {{ $rt->id === $tool->id ? 'bg-indigo-50 text-indigo-600 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-600' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $rt->id === $tool->id ? 'bg-indigo-500' : 'bg-gray-300' }}"></span>
                                    {{ $rt->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </aside>
        </div>
    </div>
@endsection
