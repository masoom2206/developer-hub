@props(['variant' => 'default'])

<div x-data="{ submitted: false, email: '' }">
    @if($variant === 'gradient')
        <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-xl shadow-sm p-6 text-white">
            <h3 class="text-lg font-bold mb-2">Newsletter</h3>
            <p class="text-sm text-indigo-100 mb-4">Get the latest dev tips and tools delivered weekly.</p>

            @if(session('newsletter_success'))
                <div class="p-3 bg-white/20 rounded-lg text-sm">{{ session('newsletter_success') }}</div>
            @elseif(session('newsletter_info'))
                <div class="p-3 bg-white/20 rounded-lg text-sm">{{ session('newsletter_info') }}</div>
            @else
                <form action="{{ route('newsletter.subscribe') }}" method="POST">
                    @csrf
                    <input type="email" name="email" x-model="email" required placeholder="you@example.com"
                        class="w-full rounded-lg border-0 bg-white/20 text-white placeholder-indigo-200 text-sm focus:ring-2 focus:ring-white mb-3">
                    @error('email')
                        <p class="text-xs text-red-200 mb-2">{{ $message }}</p>
                    @enderror
                    <button type="submit" class="w-full py-2 px-4 bg-white text-indigo-700 font-semibold text-sm rounded-lg hover:bg-indigo-50 transition">Subscribe</button>
                </form>
            @endif
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-2">Newsletter</h3>
            <p class="text-sm text-gray-500 mb-4">Weekly dev tips, tools, and tutorials.</p>

            @if(session('newsletter_success'))
                <div class="p-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg">{{ session('newsletter_success') }}</div>
            @elseif(session('newsletter_info'))
                <div class="p-3 bg-blue-50 border border-blue-200 text-blue-700 text-sm rounded-lg">{{ session('newsletter_info') }}</div>
            @else
                <form action="{{ route('newsletter.subscribe') }}" method="POST">
                    @csrf
                    <input type="email" name="email" x-model="email" required placeholder="you@example.com"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm mb-3">
                    @error('email')
                        <p class="text-xs text-red-600 mb-2">{{ $message }}</p>
                    @enderror
                    <button type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold text-sm rounded-lg hover:bg-indigo-700 transition">Subscribe</button>
                </form>
            @endif
        </div>
    @endif
</div>
