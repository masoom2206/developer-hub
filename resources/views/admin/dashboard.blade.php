@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Posts</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_posts']) }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Views</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_views']) }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Subscribers</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['subscribers']) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Active Sponsors</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['active_sponsors']) }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" /></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        {{-- Recent Posts --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="font-bold text-gray-900">Recent Posts</h2>
                <a href="{{ route('admin.posts.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700">View all</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Title</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Views</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentPosts as $post)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3">
                                    <a href="{{ route('admin.posts.edit', $post) }}" class="text-sm font-medium text-gray-900 hover:text-indigo-600 truncate block max-w-xs">{{ $post->title }}</a>
                                    <p class="text-xs text-gray-400">{{ $post->user->name ?? '' }}</p>
                                </td>
                                <td class="px-6 py-3">
                                    @if($post->status === 'published')
                                        <span class="inline-flex items-center text-xs font-medium text-green-700 bg-green-50 rounded-full px-2 py-0.5"><span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1"></span>Published</span>
                                    @else
                                        <span class="inline-flex items-center text-xs font-medium text-gray-600 bg-gray-100 rounded-full px-2 py-0.5"><span class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1"></span>Draft</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-right text-sm text-gray-500">{{ number_format($post->views) }}</td>
                                <td class="px-6 py-3 text-sm text-gray-500">{{ $post->created_at->format('M d') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-8 text-center text-sm text-gray-400">No posts yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Top Posts by Views --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="font-bold text-gray-900">Top Posts by Views</h2>
            </div>
            <div class="p-4 space-y-3">
                @forelse($topPosts as $i => $post)
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-lg bg-gray-100 text-gray-500 text-xs font-bold flex items-center justify-center shrink-0">{{ $i + 1 }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $post->title }}</p>
                            <p class="text-xs text-gray-400">{{ number_format($post->views) }} views</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-4">No data yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Comments --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="font-bold text-gray-900">Recent Comments</h2>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentComments as $comment)
                <div class="px-6 py-4 flex items-start gap-4">
                    <div class="w-9 h-9 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center text-sm font-semibold shrink-0">
                        {{ strtoupper(substr($comment->user->name ?? '?', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-sm font-medium text-gray-900">{{ $comment->user->name ?? 'Unknown' }}</span>
                            <span class="text-xs text-gray-400">on</span>
                            <a href="{{ route('admin.posts.edit', $comment->post) }}" class="text-sm text-indigo-600 hover:underline truncate">{{ $comment->post->title }}</a>
                        </div>
                        <p class="text-sm text-gray-600 line-clamp-2">{{ $comment->body }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $comment->created_at->diffForHumans() }}</p>
                    </div>
                    <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Delete this comment?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 rounded-md hover:bg-gray-100 transition" title="Delete">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                        </button>
                    </form>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-sm text-gray-400">No comments yet.</div>
            @endforelse
        </div>
    </div>
@endsection
