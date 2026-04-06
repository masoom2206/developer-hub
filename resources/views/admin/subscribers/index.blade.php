@extends('layouts.admin')

@section('page-title', 'Newsletter Subscribers')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-gray-500">{{ $subscribers->total() }} total subscribers</p>
        <a href="{{ route('admin.subscribers.export') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
            Export CSV
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Subscribed</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($subscribers as $sub)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $sub->email }}</td>
                            <td class="px-6 py-4">
                                @if($sub->status === 'active')
                                    <span class="inline-flex items-center text-xs font-medium text-green-700 bg-green-50 rounded-full px-2.5 py-0.5"><span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1"></span>Active</span>
                                @else
                                    <span class="inline-flex items-center text-xs font-medium text-gray-500 bg-gray-100 rounded-full px-2.5 py-0.5"><span class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1"></span>Unsubscribed</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $sub->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.subscribers.destroy', $sub) }}" method="POST" onsubmit="return confirm('Remove this subscriber?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 rounded-md hover:bg-gray-100 transition" title="Remove">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-12 text-center text-sm text-gray-400">No subscribers yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($subscribers->hasPages())
            <div class="border-t border-gray-200 px-6 py-4">{{ $subscribers->links() }}</div>
        @endif
    </div>
@endsection
