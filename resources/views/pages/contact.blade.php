@extends('layouts.app')

@section('full-width')
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Contact Us</h1>
        <p class="text-gray-500 mb-8">Have a question, suggestion, or just want to say hi? Fill out the form below and we'll get back to you.</p>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form action="{{ route('contact.submit') }}" method="POST" class="space-y-5">
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

                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @error('subject') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                    <textarea name="message" id="message" rows="5" required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                        placeholder="How can we help?">{{ old('message') }}</textarea>
                    @error('message') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">Send Message</button>
                </div>
            </form>
        </div>

        <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 text-center">
                <svg class="w-8 h-8 text-indigo-500 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
                <h3 class="font-semibold text-gray-900 mb-1">Email</h3>
                <p class="text-sm text-gray-500">hello@devhub.com</p>
            </div>
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 text-center">
                <svg class="w-8 h-8 text-indigo-500 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" /></svg>
                <h3 class="font-semibold text-gray-900 mb-1">Social</h3>
                <p class="text-sm text-gray-500">@devhub on X / GitHub</p>
            </div>
        </div>
    </div>
@endsection
