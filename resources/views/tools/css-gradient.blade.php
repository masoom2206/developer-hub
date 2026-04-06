@extends('tools._layout')

@section('tool-title', 'CSS Gradient Generator')
@section('tool-description', 'Create beautiful CSS linear gradients visually. Pick two colors, choose a direction, and copy the CSS code.')

@section('tool-content')
<div x-data="gradientGenerator()">
    {{-- Preview --}}
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Preview</label>
        <div class="h-48 rounded-xl border border-gray-200 shadow-inner" :style="'background: ' + cssValue"></div>
    </div>

    {{-- Controls --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
        {{-- Color 1 --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Color 1</label>
            <div class="flex items-center gap-3">
                <input type="color" x-model="color1" @input="update()" class="w-12 h-10 rounded border border-gray-300 cursor-pointer p-0.5">
                <input type="text" x-model="color1" @input="update()" maxlength="7"
                    class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono">
            </div>
        </div>

        {{-- Color 2 --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Color 2</label>
            <div class="flex items-center gap-3">
                <input type="color" x-model="color2" @input="update()" class="w-12 h-10 rounded border border-gray-300 cursor-pointer p-0.5">
                <input type="text" x-model="color2" @input="update()" maxlength="7"
                    class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono">
            </div>
        </div>

        {{-- Direction --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Direction</label>
            <select x-model="direction" @change="update()" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <option value="to right">Left to Right</option>
                <option value="to left">Right to Left</option>
                <option value="to bottom">Top to Bottom</option>
                <option value="to top">Bottom to Top</option>
                <option value="to bottom right">Diagonal ↘</option>
                <option value="to bottom left">Diagonal ↙</option>
                <option value="to top right">Diagonal ↗</option>
                <option value="to top left">Diagonal ↖</option>
                <option value="135deg">135°</option>
                <option value="45deg">45°</option>
            </select>
        </div>
    </div>

    {{-- Direction Quick Picks --}}
    <div class="flex items-center gap-2 mb-6">
        <span class="text-xs text-gray-500 mr-1">Quick:</span>
        <template x-for="d in ['to right', 'to bottom', 'to bottom right', '135deg']" :key="d">
            <button @click="direction = d; update()"
                :class="direction === d ? 'bg-indigo-100 text-indigo-700 border-indigo-300' : 'bg-white text-gray-600 border-gray-200 hover:border-indigo-300'"
                class="px-3 py-1 text-xs font-medium rounded-full border transition" x-text="d"></button>
        </template>
    </div>

    {{-- CSS Output --}}
    <div>
        <div class="flex items-center justify-between mb-1">
            <label class="block text-sm font-medium text-gray-700">CSS Code</label>
            <button @click="copy()" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium transition" x-text="copied ? 'Copied!' : 'Copy CSS'"></button>
        </div>
        <div class="bg-gray-900 rounded-lg p-4">
            <code class="text-sm font-mono text-green-400" x-text="cssCode"></code>
        </div>
    </div>
</div>

<script>
function gradientGenerator() {
    return {
        color1: '#6366f1', color2: '#ec4899', direction: 'to right',
        cssValue: '', cssCode: '', copied: false,

        init() { this.update(); },

        update() {
            this.cssValue = `linear-gradient(${this.direction}, ${this.color1}, ${this.color2})`;
            this.cssCode = `background: linear-gradient(${this.direction}, ${this.color1}, ${this.color2});`;
        },

        copy() {
            navigator.clipboard.writeText(this.cssCode);
            this.copied = true;
            setTimeout(() => this.copied = false, 2000);
        }
    }
}
</script>
@endsection

@section('tool-howto')
<ol>
    <li>Pick your two <strong>gradient colors</strong> using the color pickers or by entering HEX codes.</li>
    <li>Select a <strong>direction</strong> from the dropdown or use the quick-pick buttons.</li>
    <li>The <strong>preview</strong> updates in real-time as you change settings.</li>
    <li>Click <strong>Copy CSS</strong> to copy the generated CSS rule to your clipboard.</li>
</ol>
@endsection
