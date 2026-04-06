@extends('layouts.app')

@section('content')
    <div class="max-w-3xl">
        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">New Snippet</h1>
            <p class="text-gray-500 mt-1">Share a useful code snippet with the community</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form action="{{ route('snippets.store') }}" method="POST" class="space-y-6">
                @csrf
                @include('snippets._form')
            </form>
        </div>
    </div>
@endsection
