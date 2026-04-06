@extends('layouts.admin')

@section('page-title', 'Create Post')

@section('content')
    <div class="max-w-3xl">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @include('admin.posts._form')
            </form>
        </div>
    </div>
@endsection
