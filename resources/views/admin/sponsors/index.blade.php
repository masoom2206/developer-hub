@extends('layouts.admin')

@section('page-title', 'Sponsors')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-gray-500">Manage sponsored advertisements</p>
        <a href="{{ route('admin.sponsors.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            New Sponsor
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Company</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Placement</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Period</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($sponsors as $sponsor)
                        @php
                            $now = now()->startOfDay();
                            $isActive = $sponsor->starts_at <= $now && $sponsor->ends_at >= $now;
                            $isUpcoming = $sponsor->starts_at > $now;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $sponsor->banner_url }}" alt="{{ $sponsor->company }}" class="w-16 h-10 rounded object-cover border border-gray-200">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $sponsor->company }}</p>
                                        <a href="{{ $sponsor->target_url }}" target="_blank" class="text-xs text-gray-400 hover:text-indigo-600 truncate block max-w-[200px]">{{ $sponsor->target_url }}</a>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-medium text-gray-600 bg-gray-100 rounded-full px-2.5 py-0.5 capitalize">{{ $sponsor->placement }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($isActive)
                                    <span class="inline-flex items-center text-xs font-medium text-green-700 bg-green-50 rounded-full px-2.5 py-0.5"><span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1"></span>Active</span>
                                @elseif($isUpcoming)
                                    <span class="inline-flex items-center text-xs font-medium text-blue-700 bg-blue-50 rounded-full px-2.5 py-0.5"><span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-1"></span>Upcoming</span>
                                @else
                                    <span class="inline-flex items-center text-xs font-medium text-gray-500 bg-gray-100 rounded-full px-2.5 py-0.5"><span class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1"></span>Expired</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $sponsor->starts_at->format('M d') }} — {{ $sponsor->ends_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.sponsors.destroy', $sponsor) }}" method="POST" onsubmit="return confirm('Delete this sponsor?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 rounded-md hover:bg-gray-100 transition" title="Delete">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-400">No sponsors yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($sponsors->hasPages())
            <div class="border-t border-gray-200 px-6 py-4">{{ $sponsors->links() }}</div>
        @endif
    </div>
@endsection
