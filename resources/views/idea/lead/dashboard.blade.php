@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-S...HASH..." crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


@section('content')
    <div class="container">

        <h2 class="mb-4">Program Innovation Lead — Review Ideas</h2>

        @if (session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        @foreach ($ideas as $idea)
            <div class="card mb-4 shadow-sm">
                <div class="card-body">

                    <h4>{{ $idea->title }}</h4>

                    <p class="text-muted mb-2">
                        Votes: <strong>{{ $idea->votes_count }}</strong>
                    </p>

                    <form method="POST" action="{{ route('idea.review', $idea) }}">
                        @csrf

                        <div class="row">

                            <div class="col-md-3 mb-2">
                                <label>Business Impact (1–5)</label>
                                <input type="number" name="business_impact" class="form-control" min="1"
                                    max="5" required>
                            </div>

                            <div class="col-md-3 mb-2">
                                <label>Feasibility (1–5)</label>
                                <input type="number" name="feasibility" class="form-control" min="1" max="5"
                                    required>
                            </div>

                            <div class="col-md-3 mb-2">
                                <label>Sustainability (1–5)</label>
                                <input type="number" name="sustainability" class="form-control" min="1"
                                    max="5" required>
                            </div>

                            <div class="col-md-12 mt-2">
                                <label>Notes (optional)</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Reviewer notes..."></textarea>
                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary mt-3">
                            Submit Review
                        </button>

                    </form>

                </div>
            </div>
        @endforeach

        <a href="{{ route('idea.winner') }}" class="btn btn-success">
            See Final Ranking
        </a>

    </div>
@endsection
