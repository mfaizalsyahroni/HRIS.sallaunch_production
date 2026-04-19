@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/show_news.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-S...HASH..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<div class="container mt-4">
    <h3>
        Edit News
            <hr class="border border-primary border-2 opacity-15  mt-1 mb-4">
    </h3>

    <div class="text-left py-4 px-4" style="background-color: #f0f4f8; border-radius: 25px;">
    
    <form method="POST" action="{{ route('news.admin.update', $news->id) }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label><h5>Title</h5></label>
            <input type="text" name="title" class="form-control" value="{{ $news->title }}">
        </div>

        <div class="mb-3">
            <label><h5>Category</h5></label>
            <input type="text" name="category" class="form-control" value="{{ $news->category }}">
        </div>

        <div class="mb-3">
            <label><h5>Content</h5></label>
            <textarea name="content" class="form-control">{{ $news->content }}</textarea>
        </div>

        <div class="mb-3">
            <label><h5>New Thumbnail</h5></label>
            <input type="file" name="thumbnail" class="form-control">
        </div>

        <button class="btn btn-success"><i class="bi bi-pencil-square"></i> Update</button>
    </form>
    </div>
</div>
@endsection
