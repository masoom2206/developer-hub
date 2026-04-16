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
    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content</label>
    <textarea name="content" id="content" rows="16" required
              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('content', $post->content ?? '') }}</textarea>
    @error('content')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

{{-- CKEditor 5 — enhances the textarea above --}}
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/44.3.0/ckeditor5.css">
<style>
    .ck-editor__editable { min-height: 400px; font-size: 0.95rem; }
    .ck-editor__editable:focus { border-color: #6366f1 !important; box-shadow: 0 0 0 2px rgba(99,102,241,0.2) !important; }
    .ck.ck-editor__main>.ck-editor__editable { border-radius: 0 0 0.5rem 0.5rem; }
    .ck.ck-toolbar { border-radius: 0.5rem 0.5rem 0 0 !important; }
</style>
<script type="importmap">
    { "imports": { "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/44.3.0/ckeditor5.js", "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/44.3.0/" } }
</script>
<script type="module">
    import { ClassicEditor, Essentials, Bold, Italic, Strikethrough, Heading, Link, List, BlockQuote, CodeBlock, Code, HorizontalLine, Indent, Undo, SourceEditing } from 'ckeditor5';

    ClassicEditor.create(document.getElementById('content'), {
        plugins: [Essentials, Bold, Italic, Strikethrough, Heading, Link, List, BlockQuote, CodeBlock, Code, HorizontalLine, Indent, Undo, SourceEditing],
        toolbar: {
            items: ['undo', 'redo', '|', 'heading', '|', 'bold', 'italic', 'strikethrough', 'code', '|', 'link', 'blockQuote', 'codeBlock', 'horizontalLine', '|', 'bulletedList', 'numberedList', 'outdent', 'indent', '|', 'sourceEditing'],
        },
        heading: {
            options: [
                { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
            ]
        },
        codeBlock: {
            languages: [
                { language: 'php', label: 'PHP' },
                { language: 'javascript', label: 'JavaScript' },
                { language: 'css', label: 'CSS' },
                { language: 'html', label: 'HTML' },
                { language: 'bash', label: 'Bash' },
                { language: 'sql', label: 'SQL' },
                { language: 'python', label: 'Python' },
                { language: 'plaintext', label: 'Plain text' },
            ]
        },
    }).catch(err => {
        console.warn('CKEditor failed to load, textarea remains editable.', err);
    });
</script>

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
