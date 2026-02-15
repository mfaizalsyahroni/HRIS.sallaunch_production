    @extends('suggestions.layout')

    @section('content')
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-md-8">

                    <div class="card shadow border-0">
                        <div class="card-header bg-light fw-semibold fs-5">
                            💡 Send Suggestion
                        </div>

                        <div class="card-body">

                            {{-- SUCCESS --}}
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            {{-- ERROR --}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0 small">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('suggestions.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                {{-- CATEGORY --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Category</label>
                                    <select name="category" class="form-select" required>
                                        <option value="">-- Select Category --</option>
                                        <option value="facility">Facility</option>
                                        <option value="system">System</option>
                                        <option value="workload">Workload</option>
                                        <option value="policy">Policy</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>

                                {{-- TITLE --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Title</label>
                                    <input type="text" name="title" class="form-control" placeholder="Short summary"
                                        required>
                                </div>

                                {{-- DESCRIPTION --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Description</label>
                                    <textarea name="description" rows="4" class="form-control" placeholder="Explain the issue or suggestion clearly"
                                        required></textarea>
                                </div>

                                {{-- ATTACHMENT --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        Attachment <span class="text-muted">(optional)</span>
                                    </label>
                                    <input type="file" name="attachment" class="form-control">
                                    <div class="form-text">
                                        Allowed: JPG, PNG, MP4 (max 10MB)
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary px-4">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>

                </div>
            </div>
        </div>
    @endsection
