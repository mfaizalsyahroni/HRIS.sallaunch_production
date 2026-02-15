@extends('survey.layout')

@section('content')
    <div class="card p-5 text-center shadow">
        <h3>Thank you for completing the survey!</h3>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
            Back to Home
        </a>
    </div>
@endsection
