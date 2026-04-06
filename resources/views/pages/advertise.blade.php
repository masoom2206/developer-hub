@extends('layouts.app')

@section('full-width')
    {{-- Hero --}}
    <section class="bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
            <h1 class="text-4xl font-extrabold tracking-tight mb-3">Advertise With Us</h1>
            <p class="text-lg text-indigo-100 max-w-2xl mx-auto">
                Reach thousands of developers every month. Promote your product, service, or tool where developers actually look.
            </p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        {{-- Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-16">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                <p class="text-3xl font-bold text-indigo-600">50K+</p>
                <p class="text-sm text-gray-500 mt-1">Monthly Visitors</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                <p class="text-3xl font-bold text-indigo-600">15K+</p>
                <p class="text-sm text-gray-500 mt-1">Newsletter Subs</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                <p class="text-3xl font-bold text-indigo-600">85%</p>
                <p class="text-sm text-gray-500 mt-1">Developer Audience</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                <p class="text-3xl font-bold text-indigo-600">4.2m</p>
                <p class="text-sm text-gray-500 mt-1">Avg. Session Duration</p>
            </div>
        </div>

        {{-- Placements --}}
        <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">Ad Placement Options</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-16">
            {{-- Header Banner --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h17.25c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125H3.375A1.125 1.125 0 0 1 2.25 16.875v-9.75Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.125 12 13.5l9.75-6.375" /></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Header Banner</h3>
                        <p class="text-xs text-gray-500">Above navigation, sitewide</p>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-4">Full-width banner displayed above the main navigation on every page. Maximum visibility with premium placement.</p>
                <div class="flex items-center justify-between">
                    <span class="text-lg font-bold text-indigo-600">$499<span class="text-sm text-gray-400 font-normal">/month</span></span>
                    <span class="text-xs text-gray-500">728x90 or responsive</span>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6Zm0 9.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6Z" /></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Sidebar Ad</h3>
                        <p class="text-xs text-gray-500">Blog & snippet pages</p>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-4">Sticky sidebar placement visible while users read articles and browse snippets. Great for developer tools and SaaS products.</p>
                <div class="flex items-center justify-between">
                    <span class="text-lg font-bold text-emerald-600">$299<span class="text-sm text-gray-400 font-normal">/month</span></span>
                    <span class="text-xs text-gray-500">300x250</span>
                </div>
            </div>

            {{-- In-Article --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">In-Article Ad</h3>
                        <p class="text-xs text-gray-500">Inside blog content</p>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-4">Native-feeling ad placed within article content after the first paragraph. High engagement from readers actively consuming content.</p>
                <div class="flex items-center justify-between">
                    <span class="text-lg font-bold text-amber-600">$199<span class="text-sm text-gray-400 font-normal">/month</span></span>
                    <span class="text-xs text-gray-500">Fluid / responsive</span>
                </div>
            </div>

            {{-- Sponsored Post --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Sponsored Post</h3>
                        <p class="text-xs text-gray-500">Featured article</p>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-4">A dedicated article about your product written by our team or yours, published on the blog with a "Sponsored" badge and promoted in the newsletter.</p>
                <div class="flex items-center justify-between">
                    <span class="text-lg font-bold text-purple-600">$799<span class="text-sm text-gray-400 font-normal">/post</span></span>
                    <span class="text-xs text-gray-500">Includes newsletter</span>
                </div>
            </div>
        </div>

        {{-- Contact Form --}}
        <div class="max-w-2xl mx-auto">
            <h2 class="text-2xl font-bold text-gray-900 mb-2 text-center">Get in Touch</h2>
            <p class="text-gray-500 text-center mb-8">Fill out the form and we'll get back to you within 24 hours.</p>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <form action="{{ route('advertise.contact') }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="company" class="block text-sm font-medium text-gray-700 mb-1">Company <span class="text-gray-400">(optional)</span></label>
                            <input type="text" name="company" id="company" value="{{ old('company') }}"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label for="placement" class="block text-sm font-medium text-gray-700 mb-1">Placement</label>
                            <select name="placement" id="placement" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Select placement...</option>
                                <option value="header" {{ old('placement') === 'header' ? 'selected' : '' }}>Header Banner — $499/mo</option>
                                <option value="sidebar" {{ old('placement') === 'sidebar' ? 'selected' : '' }}>Sidebar Ad — $299/mo</option>
                                <option value="in-article" {{ old('placement') === 'in-article' ? 'selected' : '' }}>In-Article Ad — $199/mo</option>
                                <option value="sponsored-post" {{ old('placement') === 'sponsored-post' ? 'selected' : '' }}>Sponsored Post — $799/post</option>
                            </select>
                            @error('placement') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                        <textarea name="message" id="message" rows="4" required
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            placeholder="Tell us about your product and advertising goals...">{{ old('message') }}</textarea>
                        @error('message') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">Send Inquiry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
