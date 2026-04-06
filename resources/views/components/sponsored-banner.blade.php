@props(['placement' => 'sidebar'])

@php
    $ad = app(\App\Services\AdService::class)->getActiveAd($placement);
@endphp

@if($ad)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-3 py-1.5 flex items-center justify-between border-b border-gray-100">
            <span class="text-[10px] uppercase tracking-wider text-gray-400">Sponsored</span>
            <span class="text-[10px] text-gray-400">{{ $ad->company }}</span>
        </div>
        <a href="{{ $ad->target_url }}" target="_blank" rel="sponsored noopener noreferrer" class="block hover:opacity-90 transition">
            <img src="{{ $ad->banner_url }}" alt="{{ $ad->company }}" class="w-full h-auto" loading="lazy">
        </a>
    </div>
@endif
