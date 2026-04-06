{{-- Title --}}
<div>
    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
    <input type="text" name="title" id="title" value="{{ old('title', $post->title ?? '') }}" required
           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
           placeholder="Enter post title...">
    <p class="text-xs text-gray-400 mt-1">Slug will be auto-generated from the title.</p>
    @error('title')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- Category --}}
    <div>
        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
        <select name="category_id" id="category_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            <option value="">Select category...</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $post->category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Tags --}}
    <div>
        <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
        <input type="text" name="tags" id="tags"
               value="{{ old('tags', isset($post) ? $post->tags->pluck('name')->join(', ') : '') }}"
               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
               placeholder="laravel, php, tutorial (comma separated)">
        @error('tags')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

{{-- Excerpt --}}
<div>
    <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-1">Excerpt</label>
    <textarea name="excerpt" id="excerpt" rows="2" required
              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
              placeholder="Brief summary of the post...">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
    @error('excerpt')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

{{-- Content --}}
<div>
    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content <span class="text-gray-400 font-normal">(Markdown supported)</span></label>
    <textarea name="content" id="content" rows="16" required
              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono"
              placeholder="Write your post content in Markdown...">{{ old('content', $post->content ?? '') }}</textarea>
    @error('content')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

{{-- Featured Image --}}
<div>
    <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-1">Featured Image</label>
    @if(isset($post) && $post->featured_image)
        <div class="mb-3">
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="" class="w-40 h-24 object-cover rounded-lg border border-gray-200">
        </div>
    @endif
    <input type="file" name="featured_image" id="featured_image" accept="image/*"
           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
    @error('featured_image')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    {{-- Status --}}
    <div>
        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
        <select name="status" id="status" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            <option value="draft" {{ old('status', $post->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="published" {{ old('status', $post->status ?? '') === 'published' ? 'selected' : '' }}>Published</option>
        </select>
    </div>

    {{-- Sponsored --}}
    <div class="flex items-end">
        <label class="flex items-center gap-3 cursor-pointer">
            <input type="hidden" name="is_sponsored" value="0">
            <input type="checkbox" name="is_sponsored" value="1"
                   {{ old('is_sponsored', $post->is_sponsored ?? false) ? 'checked' : '' }}
                   class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
            <span class="text-sm font-medium text-gray-700">Sponsored post</span>
        </label>
    </div>
</div>

{{-- Submit --}}
<div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
    <a href="{{ route('admin.posts.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">Cancel</a>
    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
        {{ isset($post) && $post->exists ? 'Update Post' : 'Create Post' }}
    </button>
</div>
