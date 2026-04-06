@extends('tools._layout')

@section('tool-title', 'Word & Character Counter')
@section('tool-description', 'Count words, characters, sentences, paragraphs, and estimated reading time instantly as you type.')

@section('tool-content')
<div x-data="wordCounter()">
    {{-- Stats --}}
    <div class="grid grid-cols-3 sm:grid-cols-6 gap-3 mb-4">
        <div class="bg-gray-50 rounded-lg p-3 text-center">
            <p class="text-2xl font-bold text-indigo-600" x-text="chars"></p>
            <p class="text-xs text-gray-500">Characters</p>
        </div>
        <div class="bg-gray-50 rounded-lg p-3 text-center">
            <p class="text-2xl font-bold text-indigo-600" x-text="charsNoSpace"></p>
            <p class="text-xs text-gray-500">No Spaces</p>
        </div>
        <div class="bg-gray-50 rounded-lg p-3 text-center">
            <p class="text-2xl font-bold text-indigo-600" x-text="words"></p>
            <p class="text-xs text-gray-500">Words</p>
        </div>
        <div class="bg-gray-50 rounded-lg p-3 text-center">
            <p class="text-2xl font-bold text-indigo-600" x-text="sentences"></p>
            <p class="text-xs text-gray-500">Sentences</p>
        </div>
        <div class="bg-gray-50 rounded-lg p-3 text-center">
            <p class="text-2xl font-bold text-indigo-600" x-text="paragraphs"></p>
            <p class="text-xs text-gray-500">Paragraphs</p>
        </div>
        <div class="bg-gray-50 rounded-lg p-3 text-center">
            <p class="text-2xl font-bold text-indigo-600" x-text="readingTime"></p>
            <p class="text-xs text-gray-500">Read Time</p>
        </div>
    </div>

    {{-- Textarea --}}
    <textarea x-model="text" @input="count()" rows="14" placeholder="Start typing or paste your text here..."
        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm leading-relaxed"></textarea>

    <div class="flex items-center justify-end gap-3 mt-3">
        <button @click="text = ''; count()" class="text-sm text-gray-500 hover:text-gray-700 transition">Clear</button>
    </div>
</div>

<script>
function wordCounter() {
    return {
        text: '', chars: 0, charsNoSpace: 0, words: 0, sentences: 0, paragraphs: 0, readingTime: '0s',

        count() {
            this.chars = this.text.length;
            this.charsNoSpace = this.text.replace(/\s/g, '').length;
            this.words = this.text.trim() ? this.text.trim().split(/\s+/).length : 0;
            this.sentences = this.text.trim() ? (this.text.match(/[.!?]+(\s|$)/g) || []).length || (this.text.trim() ? 1 : 0) : 0;
            this.paragraphs = this.text.trim() ? this.text.split(/\n\s*\n/).filter(p => p.trim()).length : 0;
            const mins = Math.ceil(this.words / 200);
            this.readingTime = mins < 1 ? (this.words > 0 ? '<1m' : '0s') : mins + 'm';
        }
    }
}
</script>
@endsection

@section('tool-howto')
<ol>
    <li>Type or paste your text into the large textarea.</li>
    <li>All stats update <strong>live</strong> as you type: character count, character count without spaces, word count, sentence count, paragraph count, and estimated reading time.</li>
    <li>Reading time is calculated at an average of 200 words per minute.</li>
    <li>Click <strong>Clear</strong> to reset the textarea and all counters.</li>
</ol>
@endsection
