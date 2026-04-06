@extends('tools._layout')

@section('tool-title', 'Base64 Encoder / Decoder')
@section('tool-description', 'Encode plain text to Base64 or decode Base64 strings back to readable text. Conversion happens in real-time as you type.')

@section('tool-content')
<div x-data="base64Tool()">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Plain Text --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Plain Text</label>
            <textarea x-model="plain" @input="encode()" rows="10" placeholder="Type plain text here..."
                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono"></textarea>
        </div>
        {{-- Base64 --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Base64 Encoded</label>
            <textarea x-model="encoded" @input="decode()" rows="10" placeholder="Or paste Base64 here..."
                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono"></textarea>
        </div>
    </div>

    {{-- Status --}}
    <div x-show="error" x-cloak class="mt-3">
        <p class="text-sm text-red-600 bg-red-50 border border-red-200 px-4 py-2 rounded-lg" x-text="error"></p>
    </div>

    {{-- Actions --}}
    <div class="flex items-center gap-3 mt-4">
        <button @click="copyEncoded()" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition" x-text="copiedE ? 'Copied!' : 'Copy Base64'"></button>
        <button @click="copyPlain()" class="px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition" x-text="copiedP ? 'Copied!' : 'Copy Plain Text'"></button>
        <button @click="swap()" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700 transition flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" /></svg>
            Swap
        </button>
        <button @click="plain = ''; encoded = ''; error = ''" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700 transition">Clear</button>
    </div>
</div>

<script>
function base64Tool() {
    return {
        plain: '', encoded: '', error: '', copiedE: false, copiedP: false, _skip: false,

        encode() {
            if (this._skip) { this._skip = false; return; }
            this.error = '';
            try {
                this._skip = true;
                this.encoded = btoa(unescape(encodeURIComponent(this.plain)));
            } catch(e) { this.error = 'Encoding error: ' + e.message; }
        },

        decode() {
            if (this._skip) { this._skip = false; return; }
            this.error = '';
            try {
                this._skip = true;
                this.plain = decodeURIComponent(escape(atob(this.encoded)));
            } catch(e) { this.error = 'Invalid Base64 string.'; }
        },

        swap() { const t = this.plain; this.plain = this.encoded; this.encoded = t; },

        copyEncoded() { navigator.clipboard.writeText(this.encoded); this.copiedE = true; setTimeout(() => this.copiedE = false, 2000); },
        copyPlain() { navigator.clipboard.writeText(this.plain); this.copiedP = true; setTimeout(() => this.copiedP = false, 2000); }
    }
}
</script>
@endsection

@section('tool-howto')
<ol>
    <li>Type or paste <strong>plain text</strong> on the left — the Base64 output updates in real-time on the right.</li>
    <li>Or paste a <strong>Base64 string</strong> on the right — the decoded text appears on the left.</li>
    <li>Use the <strong>Copy</strong> buttons to copy either value to your clipboard.</li>
    <li>Click <strong>Swap</strong> to exchange the contents of both fields.</li>
</ol>
@endsection
