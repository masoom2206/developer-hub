@extends('tools._layout')

@section('tool-title', 'Regex Tester')
@section('tool-description', 'Test your regular expressions against sample text with live match highlighting. Supports global, case-insensitive, and multiline flags.')

@section('tool-content')
<div x-data="regexTester()">
    {{-- Pattern --}}
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Regular Expression</label>
        <div class="flex items-center gap-2">
            <span class="text-gray-400 text-lg font-mono">/</span>
            <input type="text" x-model="pattern" @input="test()" placeholder="your regex here"
                class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono">
            <span class="text-gray-400 text-lg font-mono">/</span>
            {{-- Flags --}}
            <div class="flex items-center gap-3">
                <label class="flex items-center gap-1 cursor-pointer">
                    <input type="checkbox" x-model="flagG" @change="test()" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <span class="text-sm font-mono text-gray-600">g</span>
                </label>
                <label class="flex items-center gap-1 cursor-pointer">
                    <input type="checkbox" x-model="flagI" @change="test()" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <span class="text-sm font-mono text-gray-600">i</span>
                </label>
                <label class="flex items-center gap-1 cursor-pointer">
                    <input type="checkbox" x-model="flagM" @change="test()" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <span class="text-sm font-mono text-gray-600">m</span>
                </label>
            </div>
        </div>
        <p x-show="error" x-cloak class="text-sm text-red-600 mt-1" x-text="error"></p>
    </div>

    {{-- Test String --}}
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Test String</label>
        <textarea x-model="testStr" @input="test()" rows="5" placeholder="Enter text to test against..."
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono"></textarea>
    </div>

    {{-- Highlighted Result --}}
    <div x-show="pattern && testStr" x-cloak class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Matches Highlighted</label>
        <div class="bg-white border border-gray-300 rounded-lg p-4 font-mono text-sm whitespace-pre-wrap leading-relaxed" x-html="highlighted"></div>
    </div>

    {{-- Match Info --}}
    <div x-show="matches.length > 0" x-cloak>
        <div class="flex items-center gap-4 mb-3">
            <span class="text-sm font-medium text-gray-700">Match count: <span class="text-indigo-600 font-bold" x-text="matches.length"></span></span>
        </div>
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 max-h-40 overflow-y-auto">
            <ul class="space-y-1">
                <template x-for="(m, i) in matches" :key="i">
                    <li class="text-sm font-mono">
                        <span class="text-gray-400" x-text="'[' + i + ']'"></span>
                        <span class="text-indigo-600 bg-indigo-50 px-1 rounded" x-text="m"></span>
                    </li>
                </template>
            </ul>
        </div>
    </div>
</div>

<script>
function regexTester() {
    return {
        pattern: '', testStr: '', flagG: true, flagI: false, flagM: false,
        matches: [], highlighted: '', error: '',

        test() {
            this.matches = []; this.highlighted = ''; this.error = '';
            if (!this.pattern || !this.testStr) return;

            let flags = '';
            if (this.flagG) flags += 'g';
            if (this.flagI) flags += 'i';
            if (this.flagM) flags += 'm';

            let regex;
            try { regex = new RegExp(this.pattern, flags); }
            catch(e) { this.error = e.message; return; }

            // Collect matches
            if (flags.includes('g')) {
                let m; while ((m = regex.exec(this.testStr)) !== null) {
                    this.matches.push(m[0]);
                    if (m[0] === '') { regex.lastIndex++; }
                }
            } else {
                const m = this.testStr.match(regex);
                if (m) this.matches.push(m[0]);
            }

            // Highlight
            const escaped = this.testStr.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
            try {
                const hr = new RegExp(this.pattern, flags);
                this.highlighted = escaped.replace(hr, (match) => {
                    const safeMatch = match.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
                    return '<mark class="bg-yellow-200 text-yellow-900 px-0.5 rounded">' + safeMatch + '</mark>';
                });
            } catch(e) { this.highlighted = escaped; }
        }
    }
}
</script>
@endsection

@section('tool-howto')
<ol>
    <li>Enter your <strong>regular expression</strong> pattern in the input field (without delimiters).</li>
    <li>Select the <strong>flags</strong> you need: <code>g</code> (global), <code>i</code> (case-insensitive), <code>m</code> (multiline).</li>
    <li>Type or paste your <strong>test string</strong> in the textarea.</li>
    <li>Matches are highlighted in yellow in real-time. The match count and list of captured values appear below.</li>
</ol>
@endsection
