@extends('layouts.admin')

@section('page-title', 'Edit Post')

@section('content')
    <div class="max-w-3xl">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                @include('admin.posts._form')
            </form>
        </div>
    </div>
@endsection
