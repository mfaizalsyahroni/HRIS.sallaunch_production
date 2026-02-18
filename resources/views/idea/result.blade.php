@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    integrity="sha512-S...HASH..." crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@section('content')
    <div class="container mt-4">

        <div class="card shadow-lg border-0">
            <div class="card-body">

                <h2 class="mb-3">
                    <i class="bi bi-trophy-fill text-warning"></i>
                    Final Score Result
                </h2>

                <hr>

                <h4 class="fw-bold">{{ $idea->title }}</h4>

                <div class="mt-3">

                    <span class="badge bg-primary">
                        Status: {{ strtoupper($idea->status) }}
                    </span>

                    <span class="badge bg-secondary">
                        Votes: {{ $idea->votes()->count() }}
                    </span>

                </div>

                <div class="mt-4 text-center">

                    <h1 class="display-3 fw-bold text-success">
                        {{ number_format($finalScore, 2) }}
                    </h1>

                    <p class="text-muted">Final Weighted Score</p>

                </div>

            </div>
        </div>

        <div class="text-center" style="margin-top:-50px;">
            <form action="{{ route('idea.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger px-4 fw-bold">
                    <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                </button>
            </form>
        </div>

    </div>
@endsection
