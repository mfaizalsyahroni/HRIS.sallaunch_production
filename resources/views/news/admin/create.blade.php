@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <div class="container py-4">

        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
                <strong>
                    <i class="bi bi-newspaper me-2"></i>
                    Add News
                </strong>
            </div>

            <div class="card-body">

                <form method="POST" action="{{ route('news.admin.store') }}" enctype="multipart/form-data"
                    autocomplete="off">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-type me-1 text-primary"></i>
                            Title
                        </label>
                        <input type="text" name="title" class="form-control" required>
                    </div>


                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-tags me-1 text-warning"></i>
                            Category
                        </label>
                        <input type="text" name="category" class="form-control" required>
                    </div>


                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-file-text me-1 text-info"></i>
                            Content
                        </label>
                        <textarea name="content" class="form-control" rows="5" required></textarea>
                    </div>


                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-image me-1 text-danger"></i>
                            Thumbnail
                        </label>
                        <input type="file" name="thumbnail" class="form-control" required>
                    </div>


                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save-fill me-1"></i>
                        Save News
                    </button>

                </form>
            </div>
        </div>

    </div>
@endsection
