@extends('layouts.admin')

@section('page-title', 'Posts')

@section('content')
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-sm text-gray-500">Manage your blog posts</p>
        </div>
        <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            New Post
        </a>
    </div>

    {{-- Posts Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Views</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($posts as $post)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($post->featured_image)
                                        <img src="{{ asset('storage/' . $post->featured_image) }}" class="w-10 h-10 rounded-lg object-cover shrink-0">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" /></svg>
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate max-w-xs">{{ $post->title }}</p>
                                        <p class="text-xs text-gray-400">by {{ $post->user->name ?? 'Unknown' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs text-gray-500">{{ $post->category->name ?? '—' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($post->status === 'published')
                                    <span class="inline-flex items-center text-xs font-medium text-green-700 bg-green-50 rounded-full px-2.5 py-0.5">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                        Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-xs font-medium text-gray-600 bg-gray-100 rounded-full px-2.5 py-0.5">
                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></span>
                                        Draft
                                    </span>
                                @endif
                                @if($post->is_sponsored)
                                    <span class="inline-flex items-center text-xs font-medium text-amber-700 bg-amber-50 rounded-full px-2 py-0.5 ml-1">Sponsored</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-sm text-gray-500">{{ number_format($post->views) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-500">{{ $post->created_at->format('M d, Y') }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($post->status === 'published')
                                        <a href="{{ route('posts.show', $post->slug) }}" target="_blank" class="p-1.5 text-gray-400 hover:text-indigo-600 rounded-md hover:bg-gray-100 transition" title="View">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.posts.edit', $post) }}" class="p-1.5 text-gray-400 hover:text-indigo-600 rounded-md hover:bg-gray-100 transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                    </a>
                                    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Delete this post?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 rounded-md hover:bg-gray-100 transition" title="Delete">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12H9.75m3 0H9.75m3 0H9.75M3.375 20.25h17.25c.621 0 1.125-.504 1.125-1.125V4.875c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v14.25c0 .621.504 1.125 1.125 1.125Z" /></svg>
                                <p class="text-sm font-medium text-gray-900 mb-1">No posts yet</p>
                                <p class="text-sm text-gray-500 mb-4">Get started by creating your first post.</p>
                                <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                    New Post
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($posts->hasPages())
            <div class="border-t border-gray-200 px-6 py-4">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
@endsection
