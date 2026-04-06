@extends('tools._layout')

@section('tool-title', 'Password Generator')
@section('tool-description', 'Generate strong, secure passwords with customizable length and character sets. See real-time password strength feedback.')

@section('tool-content')
<div x-data="passwordGenerator()" x-init="generate()">
    {{-- Generated Password --}}
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-1">Generated Password</label>
        <div class="flex items-center gap-2">
            <input type="text" x-model="password" readonly
                class="flex-1 rounded-lg border-gray-300 bg-gray-50 shadow-sm text-sm font-mono text-lg tracking-wider py-3">
            <button @click="copy()" class="px-4 py-3 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition whitespace-nowrap" x-text="copied ? 'Copied!' : 'Copy'"></button>
            <button @click="generate()" class="px-4 py-3 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182" /></svg>
            </button>
        </div>
    </div>

    {{-- Strength --}}
    <div class="mb-6">
        <div class="flex items-center justify-between mb-1">
            <span class="text-sm font-medium text-gray-700">Strength</span>
            <span class="text-sm font-medium" :class="strengthColor" x-text="strengthLabel"></span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="h-2 rounded-full transition-all duration-300" :class="strengthBarColor" :style="'width: ' + strengthPercent + '%'"></div>
        </div>
    </div>

    {{-- Length --}}
    <div class="mb-6">
        <div class="flex items-center justify-between mb-1">
            <label class="text-sm font-medium text-gray-700">Length</label>
            <span class="text-sm font-bold text-indigo-600" x-text="length"></span>
        </div>
        <input type="range" x-model="length" @input="generate()" min="8" max="64" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-indigo-600">
        <div class="flex justify-between text-xs text-gray-400 mt-1">
            <span>8</span><span>64</span>
        </div>
    </div>

    {{-- Options --}}
    <div class="grid grid-cols-2 gap-3">
        <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition">
            <input type="checkbox" x-model="upper" @change="generate()" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
            <div>
                <span class="text-sm font-medium text-gray-700">Uppercase</span>
                <span class="text-xs text-gray-400 block">A-Z</span>
            </div>
        </label>
        <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition">
            <input type="checkbox" x-model="lower" @change="generate()" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
            <div>
                <span class="text-sm font-medium text-gray-700">Lowercase</span>
                <span class="text-xs text-gray-400 block">a-z</span>
            </div>
        </label>
        <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition">
            <input type="checkbox" x-model="numbers" @change="generate()" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
            <div>
                <span class="text-sm font-medium text-gray-700">Numbers</span>
                <span class="text-xs text-gray-400 block">0-9</span>
            </div>
        </label>
        <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition">
            <input type="checkbox" x-model="symbols" @change="generate()" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
            <div>
                <span class="text-sm font-medium text-gray-700">Symbols</span>
                <span class="text-xs text-gray-400 block">!@#$%&*</span>
            </div>
        </label>
    </div>
</div>

<script>
function passwordGenerator() {
    return {
        password: '', length: 16,
        upper: true, lower: true, numbers: true, symbols: false,
        copied: false, strengthLabel: '', strengthPercent: 0, strengthColor: '', strengthBarColor: '',

        generate() {
            let chars = '';
            if (this.upper) chars += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            if (this.lower) chars += 'abcdefghijklmnopqrstuvwxyz';
            if (this.numbers) chars += '0123456789';
            if (this.symbols) chars += '!@#$%^&*()_+-=[]{}|;:,.<>?';
            if (!chars) { this.password = ''; this.calcStrength(); return; }

            const arr = new Uint32Array(this.length);
            crypto.getRandomValues(arr);
            this.password = Array.from(arr, v => chars[v % chars.length]).join('');
            this.calcStrength();
        },

        calcStrength() {
            const len = this.password.length;
            let pool = 0;
            if (/[a-z]/.test(this.password)) pool += 26;
            if (/[A-Z]/.test(this.password)) pool += 26;
            if (/[0-9]/.test(this.password)) pool += 10;
            if (/[^a-zA-Z0-9]/.test(this.password)) pool += 32;
            const entropy = len * Math.log2(pool || 1);

            if (entropy < 30) { this.strengthLabel = 'Very Weak'; this.strengthPercent = 15; this.strengthColor = 'text-red-600'; this.strengthBarColor = 'bg-red-500'; }
            else if (entropy < 50) { this.strengthLabel = 'Weak'; this.strengthPercent = 35; this.strengthColor = 'text-orange-600'; this.strengthBarColor = 'bg-orange-500'; }
            else if (entropy < 70) { this.strengthLabel = 'Fair'; this.strengthPercent = 55; this.strengthColor = 'text-yellow-600'; this.strengthBarColor = 'bg-yellow-500'; }
            else if (entropy < 90) { this.strengthLabel = 'Strong'; this.strengthPercent = 80; this.strengthColor = 'text-green-600'; this.strengthBarColor = 'bg-green-500'; }
            else { this.strengthLabel = 'Very Strong'; this.strengthPercent = 100; this.strengthColor = 'text-emerald-600'; this.strengthBarColor = 'bg-emerald-500'; }
        },

        copy() { navigator.clipboard.writeText(this.password); this.copied = true; setTimeout(() => this.copied = false, 2000); }
    }
}
</script>
@endsection

@section('tool-howto')
<ol>
    <li>Adjust the <strong>length slider</strong> to set your desired password length (8–64 characters).</li>
    <li>Toggle <strong>character sets</strong>: uppercase, lowercase, numbers, and symbols.</li>
    <li>Click the <strong>refresh button</strong> to generate a new password with the same settings.</li>
    <li>The <strong>strength indicator</strong> shows the estimated entropy of the generated password.</li>
    <li>Click <strong>Copy</strong> to copy the password to your clipboard.</li>
</ol>
@endsection
