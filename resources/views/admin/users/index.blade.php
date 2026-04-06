@extends('layouts.admin')

@section('page-title', 'Users')

@section('content')
    <div class="mb-6">
        <p class="text-sm text-gray-500">{{ $users->total() }} registered users</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Joined</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Change Role</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($user->avatar)
                                        <img src="{{ $user->avatar }}" class="w-8 h-8 rounded-full object-cover">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm font-semibold">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span class="text-sm font-medium text-gray-900">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $roleBadge = match($user->role) {
                                        'admin' => 'text-red-700 bg-red-50',
                                        'author' => 'text-blue-700 bg-blue-50',
                                        default => 'text-gray-600 bg-gray-100',
                                    };
                                @endphp
                                <span class="inline-flex items-center text-xs font-medium rounded-full px-2.5 py-0.5 capitalize {{ $roleBadge }}">{{ $user->role }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.users.updateRole', $user) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" class="rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs py-1.5">
                                        <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                        <option value="author" {{ $user->role === 'author' ? 'selected' : '' }}>Author</option>
                                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    <button type="submit" class="px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded-lg hover:bg-indigo-700 transition">Update</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="border-t border-gray-200 px-6 py-4">{{ $users->links() }}</div>
        @endif
    </div>
@endsection
