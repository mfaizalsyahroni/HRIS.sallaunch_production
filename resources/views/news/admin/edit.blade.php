@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Edit Berita</h3>

    <form method="POST" action="{{ route('news.admin.update', $news->id) }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" value="{{ $news->title }}">
        </div>

        <div class="mb-3">
            <label>Category</label>
            <input type="text" name="category" class="form-control" value="{{ $news->category }}">
        </div>

        <div class="mb-3">
            <label>Content</label>
            <textarea name="content" class="form-control">{{ $news->content }}</textarea>
        </div>

        <div class="mb-3">
            <label>New Thumbnail</label>
            <input type="file" name="thumbnail" class="form-control">
        </div>

        <button class="btn btn-warning">Update</button>
    </form>
</div>
@endsection
