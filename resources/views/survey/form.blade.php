@extends('layouts.app')

@section('content')
    <div class="container-fluid py-5 bg-light">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-7 col-md-9">

                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-5">

                        {{-- Header --}}
                        <div class="text-center mb-4">
                            <h4 class="fw-semibold mb-1">Employee Suggestion</h4>
                            <p class="text-muted small mb-0">
                                Submit issues, feedback, or improvement suggestions confidentially.
                            </p>
                        </div>

                        {{-- Alerts --}}
                        @if (session('success'))
                            <div class="alert alert-success small text-center">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger small">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Form --}}
                        <form action="{{ route('suggestions.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- Category --}}
                            <div class="mb-4">
                                <label class="form-label fw-medium">
                                    Category
                                </label>
                                <select name="category" class="form-select" required>
                                    <option value="">Select category</option>
                                    <option value="facility">Facility</option>
                                    <option value="system">System</option>
                                    <option value="workload">Workload</option>
                                    <option value="policy">Policy</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            {{-- Title --}}
                            <div class="mb-4">
                                <label class="form-label fw-medium">
                                    Title
                                </label>
                                <input type="text" name="title" class="form-control"
                                    placeholder="Brief summary of your suggestion" required>
                            </div>

                            {{-- Description --}}
                            <div class="mb-4">
                                <label class="form-label fw-medium">
                                    Description
                                </label>
                                <textarea name="description" rows="5" class="form-control" placeholder="Explain the issue or suggestion in detail"
                                    required></textarea>
                            </div>

                            {{-- Attachment --}}
                            <div class="mb-5">
                                <label class="form-label fw-medium">
                                    Attachment
                                    <span class="text-muted">(optional)</span>
                                </label>
                                <input type="file" name="attachment" class="form-control">
                                <div class="form-text">
                                    Supported: JPG, PNG, MP4 · Max size 10MB
                                </div>
                            </div>

                            {{-- Action --}}
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                                    Submit Suggestion
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
