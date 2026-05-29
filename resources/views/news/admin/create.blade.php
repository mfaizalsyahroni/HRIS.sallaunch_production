@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-S...HASH..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body,
        html {
            background-color: #f0f2f5 !important;
            min-height: 100vh;
        }
    </style>


    <div class="container py-4">

        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
                <strong>
                    <i class="bi bi-newspaper me-2"></i>
                    Add News
                </strong>
            </div>

            <div class="card-body">
                {{-- show an alert only if there is a validation error from the controller  --}}
                @if ($errors->any())
                    {{-- alert from bs can cls w button X  --}}
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                            <strong>Oopss! Please check your file upload</strong>
                        </div>

                        {{-- Loop through all error messages and display them one by one --}}
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                        {{-- Button x to clode or hide this alert --}}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- the validations component file error alerts  -->

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

        <!-- Logout bre  -->
        <div class="d-flex justify-content-center align-item-center mt-4">
            <form action="{{ route('news.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger px-4 fw-bold">
                    <i class="fa-solid fa-right-from-bracket me-2"></i>
                    Logout
                </button>
            </form>
        </div>

    </div>
@endsection
