@extends('layouts.app')

@section('content')
    <div class="max-w-3xl">
        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Edit Snippet</h1>
            <p class="text-gray-500 mt-1">Update your code snippet</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form action="{{ route('snippets.update', $snippet) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                @include('snippets._form')
            </form>
        </div>
    </div>
@endsection
