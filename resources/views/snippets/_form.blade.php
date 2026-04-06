{{-- Title --}}
<div>
    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
    <input type="text" name="title" id="title" value="{{ old('title', $snippet->title ?? '') }}" required
           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
           placeholder="e.g. Debounce Function">
    @error('title')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- Language --}}
    <div>
        <label for="language" class="block text-sm font-medium text-gray-700 mb-1">Language</label>
        <select name="language" id="language" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            <option value="">Select language...</option>
            @foreach(['PHP','JavaScript','CSS','Python','Bash','SQL','HTML','TypeScript','Vue','React'] as $lang)
                <option value="{{ $lang }}" {{ old('language', $snippet->language ?? '') === $lang ? 'selected' : '' }}>{{ $lang }}</option>
            @endforeach
        </select>
        @error('language')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Tags --}}
    <div>
        <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
        <input type="text" name="tags" id="tags"
               value="{{ old('tags', isset($snippet) ? $snippet->tags->pluck('name')->join(', ') : '') }}"
               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
               placeholder="utility, helper, api (comma separated)">
        @error('tags')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

{{-- Code --}}
<div>
    <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Code</label>
    <textarea name="code" id="code" rows="14" required
              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono"
              placeholder="Paste your code here...">{{ old('code', $snippet->code ?? '') }}</textarea>
    @error('code')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

{{-- Description --}}
<div>
    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-gray-400 font-normal">(optional)</span></label>
    <textarea name="description" id="description" rows="3"
              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
              placeholder="Explain what this snippet does and when to use it...">{{ old('description', $snippet->description ?? '') }}</textarea>
    @error('description')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

{{-- Visibility --}}
<div>
    <label class="flex items-center gap-3 cursor-pointer">
        <input type="hidden" name="is_public" value="0">
        <input type="checkbox" name="is_public" value="1"
               {{ old('is_public', $snippet->is_public ?? true) ? 'checked' : '' }}
               class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
        <div>
            <span class="text-sm font-medium text-gray-700">Public snippet</span>
            <p class="text-xs text-gray-400">Visible to everyone. Uncheck to keep it private.</p>
        </div>
    </label>
</div>

{{-- Submit --}}
<div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
    <a href="{{ route('snippets.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">Cancel</a>
    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
        {{ isset($snippet) && $snippet->exists ? 'Update Snippet' : 'Create Snippet' }}
    </button>
</div>
