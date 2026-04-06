@extends('tools._layout')

@section('tool-title', 'JSON Formatter & Validator')
@section('tool-description', 'Paste your raw JSON data below to format, validate, and beautify it with syntax highlighting. Instantly spot errors in malformed JSON.')

@section('tool-content')
<div x-data="jsonFormatter()">
    <div class="space-y-4">
        {{-- Input --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Input JSON</label>
            <textarea x-model="input" rows="8" placeholder='{"key": "value", "array": [1, 2, 3]}'
                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono"></textarea>
        </div>

        {{-- Buttons --}}
        <div class="flex items-center gap-3">
            <button @click="format()" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">Format</button>
            <button @click="validate()" class="px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition">Validate</button>
            <button @click="minify()" class="px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition">Minify</button>
            <button @click="clear()" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700 transition">Clear</button>
        </div>

        {{-- Status --}}
        <div x-show="message" x-cloak x-transition>
            <p :class="isError ? 'text-red-600 bg-red-50 border-red-200' : 'text-green-600 bg-green-50 border-green-200'" class="text-sm px-4 py-2 rounded-lg border" x-text="message"></p>
        </div>

        {{-- Output --}}
        <div x-show="output" x-cloak>
            <div class="flex items-center justify-between mb-1">
                <label class="block text-sm font-medium text-gray-700">Output</label>
                <button @click="copy()" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium transition" x-text="copied ? 'Copied!' : 'Copy to clipboard'"></button>
            </div>
            <div class="bg-gray-900 rounded-lg p-4 overflow-x-auto max-h-96 overflow-y-auto">
                <pre class="text-sm font-mono leading-relaxed"><code x-html="highlighted"></code></pre>
            </div>
        </div>
    </div>
</div>

<script>
function jsonFormatter() {
    return {
        input: '',
        output: '',
        highlighted: '',
        message: '',
        isError: false,
        copied: false,

        format() {
            this.message = '';
            try {
                const parsed = JSON.parse(this.input);
                this.output = JSON.stringify(parsed, null, 2);
                this.highlighted = this.syntaxHighlight(this.output);
                this.message = 'Valid JSON — formatted successfully.';
                this.isError = false;
            } catch (e) {
                this.output = '';
                this.highlighted = '';
                this.message = 'Invalid JSON: ' + e.message;
                this.isError = true;
            }
        },

        validate() {
            try {
                JSON.parse(this.input);
                this.message = 'Valid JSON!';
                this.isError = false;
            } catch (e) {
                this.message = 'Invalid JSON: ' + e.message;
                this.isError = true;
            }
        },

        minify() {
            this.message = '';
            try {
                const parsed = JSON.parse(this.input);
                this.output = JSON.stringify(parsed);
                this.highlighted = this.syntaxHighlight(this.output);
                this.message = 'JSON minified.';
                this.isError = false;
            } catch (e) {
                this.message = 'Invalid JSON: ' + e.message;
                this.isError = true;
            }
        },

        clear() { this.input = ''; this.output = ''; this.highlighted = ''; this.message = ''; },

        copy() {
            navigator.clipboard.writeText(this.output);
            this.copied = true;
            setTimeout(() => this.copied = false, 2000);
        },

        syntaxHighlight(json) {
            json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function(match) {
                let cls = 'text-amber-400'; // number
                if (/^"/.test(match)) {
                    cls = /:$/.test(match) ? 'text-purple-400' : 'text-green-400'; // key : string
                } else if (/true|false/.test(match)) {
                    cls = 'text-blue-400';
                } else if (/null/.test(match)) {
                    cls = 'text-gray-500';
                }
                return '<span class="' + cls + '">' + match + '</span>';
            });
        }
    }
}
</script>
@endsection

@section('tool-howto')
<ol>
    <li>Paste or type your raw JSON into the input area.</li>
    <li>Click <strong>Format</strong> to pretty-print with 2-space indentation and syntax highlighting.</li>
    <li>Click <strong>Validate</strong> to check if the JSON is valid without formatting.</li>
    <li>Click <strong>Minify</strong> to compress the JSON into a single line.</li>
    <li>Use <strong>Copy to clipboard</strong> to copy the formatted output.</li>
</ol>
@endsection
