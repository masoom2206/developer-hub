@extends('tools._layout')

@section('tool-title', 'Color Converter')
@section('tool-description', 'Pick any color and instantly get its HEX, RGB, and HSL equivalents. Copy any format with a single click.')

@section('tool-content')
<div x-data="colorConverter()">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Picker --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Pick a Color</label>
            <div class="flex items-center gap-4">
                <input type="color" x-model="hex" @input="fromHex()" class="w-20 h-20 rounded-lg border border-gray-300 cursor-pointer p-1">
                <div class="flex-1">
                    <label class="block text-xs text-gray-500 mb-1">HEX</label>
                    <div class="flex items-center gap-2">
                        <input type="text" x-model="hex" @input="fromHex()" maxlength="7"
                            class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono">
                        <button @click="copyVal(hex, 'hex')" class="p-2 text-gray-400 hover:text-indigo-600 transition" :title="copiedField === 'hex' ? 'Copied!' : 'Copy'">
                            <svg x-show="copiedField !== 'hex'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9.75a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" /></svg>
                            <svg x-show="copiedField === 'hex'" x-cloak class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Preview --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Preview</label>
            <div class="h-20 rounded-xl border border-gray-200 shadow-inner" :style="'background-color: ' + hex"></div>
        </div>
    </div>

    {{-- Values --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
        {{-- RGB --}}
        <div>
            <label class="block text-xs text-gray-500 mb-1">RGB</label>
            <div class="flex items-center gap-2">
                <input type="text" :value="rgb" readonly class="flex-1 rounded-lg border-gray-300 bg-gray-50 shadow-sm text-sm font-mono">
                <button @click="copyVal(rgb, 'rgb')" class="p-2 text-gray-400 hover:text-indigo-600 transition">
                    <svg x-show="copiedField !== 'rgb'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9.75a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" /></svg>
                    <svg x-show="copiedField === 'rgb'" x-cloak class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                </button>
            </div>
        </div>

        {{-- HSL --}}
        <div>
            <label class="block text-xs text-gray-500 mb-1">HSL</label>
            <div class="flex items-center gap-2">
                <input type="text" :value="hsl" readonly class="flex-1 rounded-lg border-gray-300 bg-gray-50 shadow-sm text-sm font-mono">
                <button @click="copyVal(hsl, 'hsl')" class="p-2 text-gray-400 hover:text-indigo-600 transition">
                    <svg x-show="copiedField !== 'hsl'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9.75a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" /></svg>
                    <svg x-show="copiedField === 'hsl'" x-cloak class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function colorConverter() {
    return {
        hex: '#6366f1', rgb: '', hsl: '', copiedField: '',

        init() { this.fromHex(); },

        fromHex() {
            const h = this.hex.replace('#', '');
            if (h.length !== 6) return;
            const r = parseInt(h.substring(0,2), 16);
            const g = parseInt(h.substring(2,4), 16);
            const b = parseInt(h.substring(4,6), 16);
            this.rgb = `rgb(${r}, ${g}, ${b})`;
            // HSL
            const rn = r/255, gn = g/255, bn = b/255;
            const max = Math.max(rn,gn,bn), min = Math.min(rn,gn,bn);
            let hue, sat, lig = (max+min)/2;
            if (max === min) { hue = sat = 0; }
            else {
                const d = max - min;
                sat = lig > 0.5 ? d/(2-max-min) : d/(max+min);
                switch(max) {
                    case rn: hue = ((gn-bn)/d + (gn < bn ? 6 : 0))/6; break;
                    case gn: hue = ((bn-rn)/d + 2)/6; break;
                    case bn: hue = ((rn-gn)/d + 4)/6; break;
                }
            }
            this.hsl = `hsl(${Math.round(hue*360)}, ${Math.round(sat*100)}%, ${Math.round(lig*100)}%)`;
        },

        copyVal(val, field) {
            navigator.clipboard.writeText(val);
            this.copiedField = field;
            setTimeout(() => this.copiedField = '', 2000);
        }
    }
}
</script>
@endsection

@section('tool-howto')
<ol>
    <li>Use the <strong>color picker</strong> to select any color visually, or type a HEX value directly.</li>
    <li>The <strong>RGB</strong> and <strong>HSL</strong> equivalents update automatically in real-time.</li>
    <li>Click the <strong>copy button</strong> next to any value to copy it to your clipboard.</li>
</ol>
@endsection
