@extends('layouts.admin')

@section('page-title', 'New Sponsor')

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form action="{{ route('admin.sponsors.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label for="company" class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                    <input type="text" name="company" id="company" value="{{ old('company') }}" required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @error('company') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="banner_image" class="block text-sm font-medium text-gray-700 mb-1">Banner Image</label>
                    <input type="file" name="banner_image" id="banner_image" accept="image/*" required
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    @error('banner_image') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="target_url" class="block text-sm font-medium text-gray-700 mb-1">Target URL</label>
                    <input type="url" name="target_url" id="target_url" value="{{ old('target_url') }}" required placeholder="https://..."
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @error('target_url') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="placement" class="block text-sm font-medium text-gray-700 mb-1">Placement</label>
                    <select name="placement" id="placement" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">Select...</option>
                        <option value="header" {{ old('placement') === 'header' ? 'selected' : '' }}>Header Banner</option>
                        <option value="sidebar" {{ old('placement') === 'sidebar' ? 'selected' : '' }}>Sidebar</option>
                        <option value="in-article" {{ old('placement') === 'in-article' ? 'selected' : '' }}>In-Article</option>
                    </select>
                    @error('placement') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="starts_at" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" name="starts_at" id="starts_at" value="{{ old('starts_at') }}" required
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @error('starts_at') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="ends_at" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" name="ends_at" id="ends_at" value="{{ old('ends_at') }}" required
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @error('ends_at') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.sponsors.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">Cancel</a>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">Create Sponsor</button>
                </div>
            </form>
        </div>
    </div>
@endsection
