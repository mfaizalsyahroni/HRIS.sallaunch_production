@extends('survey.layout')

@section('content')
    <div class="card shadow-sm p-4 text-center">
        <i class="bi bi-check-circle-fill text-success fs-1 mb-3"></i>

        <h4 class="fw-semibold">Survey Completed</h4>

        <p class="text-muted mb-4">
            You have already completed this survey.<br>
            Thank you for your participation.
        </p>

        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
            Back to Home
        </a>
    </div>
@endsection
